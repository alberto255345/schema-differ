<?php

namespace DiffFramework\Console\Commands;

use Illuminate\Console\Command;
use DiffFramework\SchemaDiffer;

class GenerateDiffMigration extends Command
{
    /**
     * A assinatura do comando Artisan.
     *
     * @var string
     */
    protected $signature = 'diff:migration';

    /**
     * A descrição do comando.
     *
     * @var string
     */
    protected $description = 'Gera um arquivo de migration com as diferenças entre o schema esperado e o atual do banco de dados.';

    /**
     * Executa o comando.
     */
    public function handle()
    {
        $this->info('Iniciando a verificação de diferenças no schema...');

        $differ = new SchemaDiffer();
        $diff = $differ->diffSchemas();

        if (empty($diff)) {
            $this->info('Nenhuma diferença encontrada entre o schema esperado e o atual.');
            return 0;
        }

        $migrationContent = $differ->generateMigrationContent($diff);
        $timestamp = date('Y_m_d_His');
        $migrationFileName = $timestamp . '_diff_migration_generated.php';
        $migrationPath = database_path('migrations/' . $migrationFileName);

        file_put_contents($migrationPath, $migrationContent);

        $this->info("Migration gerada com sucesso: {$migrationFileName}");
        return 0;
    }
}
