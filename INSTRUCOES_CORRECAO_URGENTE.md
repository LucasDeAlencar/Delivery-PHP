# 🚨 CORREÇÃO URGENTE: Erro valor_entrega

## 🎯 Problema Identificado

O erro persiste porque existe uma **constraint única** no campo `valor_entrega` da tabela `bairros`. Isso impede que múltiplos bairros tenham o mesmo valor de entrega.

## 🚀 SOLUÇÕES (Execute uma das opções abaixo)

### ⚡ OPÇÃO 1: Script PHP Automatizado (Mais Seguro)

```bash
cd /home/lucasclemente/ProjetosPHP/meu-projeto-ci4
php forcar_remocao_constraint.php
```

### ⚡ OPÇÃO 2: SQL Direto no MySQL/phpMyAdmin

1. **Abra o phpMyAdmin ou cliente MySQL**
2. **Selecione seu banco de dados**
3. **Execute este comando para identificar a constraint:**

```sql
SHOW INDEX FROM bairros WHERE Column_name = 'valor_entrega' AND Non_unique = 0;
```

4. **Anote o nome da constraint que aparecer (ex: `valor_entrega`, `bairros_valor_entrega_unique`, etc.)**

5. **Execute o comando para remover (substitua `NOME_DA_CONSTRAINT`):**

```sql
ALTER TABLE bairros DROP INDEX `NOME_DA_CONSTRAINT`;
```

6. **Teste se funcionou:**

```sql
INSERT INTO bairros (nome, slug, cidade, valor_entrega, ativo, criado_em, atualizado_em) 
VALUES ('TESTE_1', 'teste-1', 'Contagem', 20.00, 1, NOW(), NOW());

INSERT INTO bairros (nome, slug, cidade, valor_entrega, ativo, criado_em, atualizado_em) 
VALUES ('TESTE_2', 'teste-2', 'Contagem', 20.00, 1, NOW(), NOW());

-- Se funcionou, limpe os testes:
DELETE FROM bairros WHERE nome IN ('TESTE_1', 'TESTE_2');
```

### ⚡ OPÇÃO 3: Comandos SQL Rápidos (Tente um por vez)

Execute **UM** destes comandos até um funcionar:

```sql
ALTER TABLE bairros DROP INDEX `valor_entrega`;
```

```sql
ALTER TABLE bairros DROP INDEX `bairros_valor_entrega_unique`;
```

```sql
ALTER TABLE bairros DROP INDEX `valor_entrega_UNIQUE`;
```

```sql
ALTER TABLE bairros DROP INDEX `uk_valor_entrega`;
```

### 🆘 OPÇÃO 4: Solução Extrema (Se nada funcionar)

**⚠️ ATENÇÃO: Faça backup antes!**

```sql
-- 1. Backup
CREATE TABLE bairros_backup AS SELECT * FROM bairros;

-- 2. Recriar tabela
DROP TABLE bairros;

CREATE TABLE `bairros` (
    `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
    `nome` varchar(128) NOT NULL,
    `slug` varchar(128) NOT NULL,
    `cidade` varchar(20) NOT NULL DEFAULT 'Contagem',
    `valor_entrega` decimal(10,2) NOT NULL,
    `ativo` tinyint(1) NOT NULL DEFAULT 1,
    `criado_em` datetime DEFAULT NULL,
    `atualizado_em` datetime DEFAULT NULL,
    `deletado_em` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `nome` (`nome`),
    UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Restaurar dados
INSERT INTO bairros SELECT * FROM bairros_backup;

-- 4. Remover backup
DROP TABLE bairros_backup;
```

## ✅ Como Verificar se Foi Corrigido

Após executar qualquer solução:

1. **Tente cadastrar um bairro com valor R$ 20,00**
2. **O erro não deve mais aparecer**
3. **Você deve conseguir cadastrar múltiplos bairros com o mesmo valor**

## 🔍 Diagnóstico Adicional

Se quiser investigar mais, execute:

```bash
php debug_detalhado_bairros.php
```

## 📞 Arquivos de Apoio Criados

- `forcar_remocao_constraint.php` - Script automatizado
- `debug_detalhado_bairros.php` - Diagnóstico completo
- `fix_constraint_direto.sql` - Comandos SQL diretos
- `INSTRUCOES_CORRECAO_URGENTE.md` - Este guia

## 🎯 Causa Raiz

Alguém adicionou manualmente uma constraint única no campo `valor_entrega`, provavelmente através de:
- phpMyAdmin
- Comando SQL direto
- Script de migração personalizado

## 💡 Prevenção Futura

- **NÃO** adicione constraints únicas no campo `valor_entrega`
- **Apenas** `nome` e `slug` devem ser únicos
- **Se precisar** de validação de negócio, implemente no modelo PHP

---

**🚀 Execute uma das solu��ões acima e o problema será resolvido!**