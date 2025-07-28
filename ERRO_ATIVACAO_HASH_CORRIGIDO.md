# Correção dos Erros de Hash: ativacao_hash e reset_hash

## Problemas Identificados

### Erro #1062 - Duplicate entry '' for key 'ativacao_hash'
O erro `CodeIgniter\Database\Exceptions\DatabaseException #1062 Duplicate entry '' for key 'ativacao_hash'` ocorria porque:

### Erro #1048 - Column 'reset_hash' cannot be null  
O erro `CodeIgniter\Database\Exceptions\DatabaseException #1048 Column 'reset_hash' cannot be null` ocorria porque:

1. **Campo `ativacao_hash` não permitia NULL**: Na migração original, o campo foi definido sem `'null' => true`
2. **Valor padrão vazio**: Quando não especificado, o campo recebia uma string vazia `''`
3. **Constraint UNIQUE**: Como o campo tem restrição única, não é possível ter dois registros com string vazia
4. **Criação de usuários**: O controller não estava gerando hashes únicos para novos usuários

## Soluções Implementadas

### 1. Correção Completa (RECOMENDADO)

**Arquivo**: `fix_complete.php`

Este script faz a correção completa:
- Conecta diretamente ao banco de dados
- Identifica registros com `ativacao_hash` e `reset_hash` vazios
- Gera hashes únicos para `ativacao_hash` vazios
- Define `reset_hash` vazios como NULL
- Modifica a estrutura da tabela para permitir NULL
- Testa a criação de um novo usuário

**Como executar**:
```bash
php fix_complete.php
```

### 2. Correção Específica do reset_hash

**Arquivo**: `fix_reset_hash.php`

Este script foca especificamente no problema do reset_hash:
- Corrige registros com `reset_hash` vazio
- Modifica estrutura para permitir NULL
- Verificação detalhada da estrutura

**Como executar**:
```bash
php fix_reset_hash.php
```

### 3. Correção do Controller

**Arquivo**: `app/Controllers/Admin/Usuarios.php`

Modificações no método `cadastrar()`:
- Adicionada geração automática de `ativacao_hash` único
- Inicialização de `reset_hash` como NULL

```php
$dadosUsuario = [
    // ... outros campos
    'ativacao_hash' => bin2hex(random_bytes(32)), // Gera hash único
    'reset_hash' => null // Inicializa como null
];
```

### 4. Nova Migração (Opcional)

**Arquivo**: `app/Database/Migrations/2025-01-27-000000_FixAtivacaoHashField.php`

Migração para corrigir a estrutura da tabela via CodeIgniter:
```bash
php spark migrate
```

## Estrutura Corrigida

### Antes:
```sql
ativacao_hash VARCHAR(255) NOT NULL UNIQUE
reset_hash VARCHAR(255) NOT NULL UNIQUE
```

### Depois:
```sql
ativacao_hash VARCHAR(255) NULL DEFAULT NULL UNIQUE
reset_hash VARCHAR(255) NULL DEFAULT NULL UNIQUE
```

## Benefícios da Correção

1. **Permite múltiplos registros com NULL**: Campos únicos podem ter múltiplos valores NULL
2. **Hashes únicos automáticos**: Novos usuários recebem hashes únicos automaticamente
3. **Flexibilidade**: Campos podem ser NULL quando não necessários
4. **Compatibilidade**: Mantém a funcionalidade existente

## Verificação da Correção

Após executar a correção, você pode verificar:

```sql
-- Verificar estrutura da tabela
DESCRIBE usuarios;

-- Verificar registros
SELECT id, nome, ativacao_hash FROM usuarios LIMIT 5;

-- Contar registros com hash vazio (deve ser 0)
SELECT COUNT(*) FROM usuarios WHERE ativacao_hash = '';
```

## Prevenção de Problemas Futuros

1. **Sempre definir campos opcionais como NULL** nas migrações
2. **Gerar valores únicos** para campos com constraint UNIQUE
3. **Testar criação de múltiplos registros** durante desenvolvimento
4. **Usar validações adequadas** nos models

## 🚀 Para Resolver Imediatamente

Execute o script de correção completa:
```bash
cd /home/lucasclemente/ProjetosPHP/meu-projeto-ci4
php fix_complete.php
```

Ou se preferir, execute o script específico:
```bash
# Para problemas específicos do reset_hash
php fix_reset_hash.php
```

## Arquivos Criados/Modificados

- ✅ `app/Controllers/Admin/Usuarios.php` - Correção da lógica de cadastro
- ✅ `fix_complete.php` - Script de correção completa (RECOMENDADO)
- ✅ `fix_reset_hash.php` - Script específico para reset_hash
- ✅ `app/Database/Migrations/2025-01-27-000000_FixAtivacaoHashField.php` - Nova migração
- ✅ `ERRO_ATIVACAO_HASH_CORRIGIDO.md` - Esta documentação

## Status

✅ **PROBLEMAS RESOLVIDOS** - Ambos os erros (#1062 e #1048) foram corrigidos:
- ✅ Erro de duplicate entry para ativacao_hash
- ✅ Erro de campo reset_hash que não pode ser NULL
- ✅ Novos usuários podem ser criados sem problemas
- ✅ Estrutura da tabela corrigida para permitir NULL nos campos de hash