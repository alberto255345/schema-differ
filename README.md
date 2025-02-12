# Schema Differ

[![Latest Version](https://img.shields.io/packagist/v/alberto255345/schema-differ.svg?style=flat-square)](https://packagist.org/packages/alberto255345/schema-differ)
[![License](https://img.shields.io/packagist/l/alberto255345/schema-differ.svg?style=flat-square)](LICENSE)

O **Schema Differ** é um framework para gerar migrations de _diff_ que comparam o _schema_ esperado (definido por suas migrations ou entidades) com o _schema_ atual do banco de dados. Ele automatiza a criação de migrations com os métodos `up` e `down` para sincronizar o banco de dados conforme as alterações detectadas, facilitando a manutenção e evolução do seu sistema.

## Recursos

- **Extração do Schema Atual:** Obtém informações do banco (exemplo fictício) ou pode ser estendido para usar _information_schema_ ou métodos nativos do Laravel.
- **Extração do Schema Esperado:** Utiliza definições (exemplo fixo) das migrations ou das entidades.
- **Comparação de Schemas:** Identifica tabelas ou colunas ausentes e outras diferenças.
- **Geração Automática de Migration:** Cria um arquivo de migration contendo os métodos `up` (para aplicar as mudanças) e `down` (para reverter).
- **Comando Artisan:** Fornece o comando `diff:migration` para executar o processo diretamente via linha de comando.
- **PSR-4 Autoload:** Compatível com a estrutura de autoload do Composer.

## Estrutura do Pacote

```
schema-differ/
├── src/
│   ├── Console/
│   │   └── Commands/
│   │       └── GenerateDiffMigration.php
│   ├── SchemaDiffer.php
│   └── ServiceProvider.php
├── composer.json
└── README.md
```

## Instalação

### Via Packagist

Adicione o pacote ao seu projeto Laravel utilizando o Composer:

```bash
composer require alberto255345/schema-differ:^1.0.2
```

### Configuração para Laravel

Se o seu projeto Laravel não estiver usando o auto-discovery, adicione o Service Provider manualmente no arquivo `config/app.php`:

```php
'providers' => [
    // Outros providers...
    DiffFramework\ServiceProvider::class,
],
```

## Uso

### Executando o Comando Artisan

O pacote registra o comando Artisan `diff:migration`. Para gerar a migration que reflete a diferença entre o schema esperado e o atual, execute:

```bash
php artisan diff:migration
```

Esse comando realizará os seguintes passos:

1. Instanciará a classe `SchemaDiffer`, que compara o schema atual (exemplo fictício) com o schema esperado.
2. Gerará o conteúdo da migration com os métodos `up` e `down`.
3. Criará um arquivo de migration na pasta `database/migrations` com um nome baseado em timestamp (ex.: `2025_02_11_123456_diff_migration_generated.php`).

### Detalhes Técnicos

- **Escapamento de Variáveis:**  
  O código gerado para a migration usa _heredoc_ para incluir trechos do Blueprint do Laravel. Note que, dentro das strings, os sinais de dólar são escapados (ex.: `\$table`) para que o código gerado contenha literalmente `$table` e não tente interpretar variáveis durante a execução do comando.
  
- **Regeneração do Autoload:**  
  Se você realizar alterações no pacote ou na sua estrutura, execute:
  ```bash
  composer dump-autoload
  ```
  para garantir que todas as classes sejam carregadas corretamente.

## Atualizando o Pacote

Após realizar alterações no seu pacote, siga estes passos para atualizar a versão no GitHub e no Packagist:

1. **Atualize o `composer.json` (opcional):**  
   Atualize a chave `"version"` se desejar (por exemplo, `"version": "1.0.2"`).

2. **Commit e Push:**  
   ```bash
   git add .
   git commit -m "Atualiza versão para 1.0.2 e corrige escapes de variáveis"
   git push origin master
   ```

3. **Crie uma Tag no Git:**  
   ```bash
   git tag -a v1.0.2 -m "Lançamento da versão 1.0.2"
   git push --tags
   ```

4. **Packagist:**  
   Se o webhook estiver configurado, o Packagist atualizará automaticamente. Caso contrário, acesse a página do pacote no Packagist e clique em "Update" para forçar a atualização.

## Problemas Comuns

- **Extensão bcmath:**  
  Caso alguma dependência (por exemplo, `moneyphp/money`) exija a extensão `bcmath` e ela não esteja instalada, você pode instalar via:
  ```bash
  sudo apt-get install php8.2-bcmath
  ```
  (Ajuste a versão conforme sua instalação do PHP.)

- **Mensagens do Xdebug:**  
  As mensagens do Xdebug podem aparecer durante a execução do Composer ou Artisan. Geralmente, elas não impedem o funcionamento do pacote, mas se desejar, você pode desabilitar o Xdebug temporariamente.

## Contribuição

Contribuições são muito bem-vindas! Abra _issues_ ou envie _pull requests_ para ajudar a melhorar o pacote.

## Licença

Este projeto está licenciado sob a [Licença MIT](LICENSE).

## Contato

Em caso de dúvidas ou sugestões, abra uma issue em [GitHub Issues](https://github.com/alberto255345/schema-differ/issues).
