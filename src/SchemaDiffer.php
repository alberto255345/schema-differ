<?php

namespace DiffFramework;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class SchemaDiffer
{
    protected $dbSchema;
    protected $expectedSchema;

    public function __construct()
    {
        $this->dbSchema = $this->getDatabaseSchema();
        $this->expectedSchema = $this->getExpectedSchema();
    }

    /**
     * Extrai o schema atual do banco de dados.
     * Aqui você pode usar consultas à information_schema (no MySQL, por exemplo)
     * ou funções do próprio Laravel para obter essa informação.
     */
    protected function getDatabaseSchema()
    {
        // Exemplo simplificado com dados fictícios.
        return [
            'users' => [
                'columns' => [
                    'id'   => ['type' => 'integer', 'nullable' => false],
                    'name' => ['type' => 'string', 'nullable' => false],
                ],
            ],
        ];
    }

    /**
     * Extrai o schema esperado a partir das migrations ou das entidades.
     * Aqui, para simplificar, estamos utilizando um array fixo.
     */
    protected function getExpectedSchema()
    {
        // Exemplo simplificado com dados fictícios.
        return [
            'users' => [
                'columns' => [
                    'id'    => ['type' => 'integer', 'nullable' => false],
                    'name'  => ['type' => 'string', 'nullable' => false],
                    'email' => ['type' => 'string', 'nullable' => false],
                ],
            ],
        ];
    }

    /**
     * Compara os schemas atual e esperado e retorna as diferenças.
     */
    public function diffSchemas()
    {
        $diff = [];

        foreach ($this->expectedSchema as $tableName => $tableDefinition) {
            // Se a tabela não existe no banco, marcar para criação
            if (!isset($this->dbSchema[$tableName])) {
                $diff['tables'][$tableName] = [
                    'action'     => 'create',
                    'definition' => $tableDefinition,
                ];
            } else {
                // Tabela existe: verifique diferenças nas colunas
                $dbColumns       = $this->dbSchema[$tableName]['columns'];
                $expectedColumns = $tableDefinition['columns'];

                foreach ($expectedColumns as $column => $definition) {
                    if (!isset($dbColumns[$column])) {
                        $diff['tables'][$tableName]['columns'][$column] = [
                            'action'     => 'add',
                            'definition' => $definition,
                        ];
                    }
                }
            }
        }

        return $diff;
    }

    /**
     * Gera o conteúdo de uma migration com base no diff encontrado.
     */
    public function generateMigrationContent(array $diff)
    {
        $upCommands   = $this->generateUpCommands($diff);
        $downCommands = $this->generateDownCommands($diff);

        $template = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DiffMigrationGenerated extends Migration
{
    public function up()
    {
{$upCommands}
    }

    public function down()
    {
{$downCommands}
    }
}
PHP;
        return $template;
    }

    /**
     * Gera os comandos do método `up` com base no diff.
     */
    protected function generateUpCommands(array $diff)
    {
        \$commands = "";
        if (isset(\$diff['tables'])) {
            foreach (\$diff['tables'] as \$tableName => \$tableDiff) {
                if (\$tableDiff['action'] === 'create') {
                    \$commands .= "        Schema::create('{$tableName}', function (Blueprint \$table) {\n";
                    foreach (\$tableDiff['definition']['columns'] as \$column => \$def) {
                        // Aqui assumimos que o tipo mapeia diretamente para um método do Blueprint.
                        \$type = \$def['type'];
                        \$commands .= "            \$table->{$type}('{$column}');\n";
                    }
                    \$commands .= "        });\n\n";
                } elseif (isset(\$tableDiff['columns'])) {
                    foreach (\$tableDiff['columns'] as \$column => \$colDiff) {
                        if (\$colDiff['action'] === 'add') {
                            \$type = \$colDiff['definition']['type'];
                            \$commands .= "        Schema::table('{$tableName}', function (Blueprint \$table) {\n";
                            \$commands .= "            \$table->{$type}('{$column}');\n";
                            \$commands .= "        });\n\n";
                        }
                    }
                }
            }
        }
        return \$commands;
    }

    /**
     * Gera os comandos do método `down` para reverter as alterações.
     */
    protected function generateDownCommands(array \$diff)
    {
        \$commands = "";
        if (isset(\$diff['tables'])) {
            foreach (\$diff['tables'] as \$tableName => \$tableDiff) {
                if (\$tableDiff['action'] === 'create') {
                    \$commands .= "        Schema::dropIfExists('{$tableName}');\n\n";
                } elseif (isset(\$tableDiff['columns'])) {
                    foreach (\$tableDiff['columns'] as \$column => \$colDiff) {
                        if (\$colDiff['action'] === 'add') {
                            \$commands .= "        Schema::table('{$tableName}', function (Blueprint \$table) {\n";
                            \$commands .= "            \$table->dropColumn('{$column}');\n";
                            \$commands .= "        });\n\n";
                        }
                    }
                }
            }
        }
        return \$commands;
    }
}
