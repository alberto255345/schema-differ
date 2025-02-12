# Schema Differ

[![Latest Version](https://img.shields.io/packagist/v/seu-vendor/schema-differ.svg?style=flat-square)](https://packagist.org/packages/seu-vendor/schema-differ)
[![License](https://img.shields.io/packagist/l/seu-vendor/schema-differ.svg?style=flat-square)](LICENSE)

O **Schema Differ** é um framework para gerar _migrations_ de diff comparando o _schema_ esperado (definido pelas suas migrations ou entidades) com o _schema_ atual do banco de dados. Ele automatiza a criação de migrations com métodos `up` e `down` para sincronizar o banco conforme as alterações detectadas, facilitando a manutenção e evolução do seu banco de dados.

## Recursos

- **Extração do Schema Atual:** Obtém informações do banco de dados (tabelas, colunas, índices, etc.) através de consultas ou recursos nativos.
- **Extração do Schema Esperado:** Lê as definições a partir das migrations ou dos models (entidades) da aplicação.
- **Comparação de Schemas:** Identifica diferenças, como tabelas ou colunas ausentes, alterações de tipo, etc.
- **Geração Automática de Migration:** Cria um arquivo de migration contendo os métodos `up` (para atualizar o banco) e `down` (para reverter as alterações).
- **Integração com Artisan:** Comando Artisan para facilitar a execução do processo em projetos Laravel.
- **Fácil Instalação via Composer:** Compatível com auto-discovery no Laravel.

## Instalação

### Via Packagist

Adicione o pacote ao seu projeto utilizando o Composer:

```bash
composer require seu-vendor/schema-differ
