-- ============================================================================
-- SCRIPT DIRETO PARA CORRIGIR CONSTRAINT valor_entrega
-- Execute este script diretamente no MySQL/phpMyAdmin
-- ============================================================================

-- 1. VERIFICAR PROBLEMA ATUAL
SELECT 'DIAGNÓSTICO: Verificando constraints atuais...' as status;

SHOW INDEX FROM bairros WHERE Column_name = 'valor_entrega';

-- 2. TENTAR REMOVER TODAS AS POSSÍVEIS CONSTRAINTS
SELECT 'CORREÇÃO: Removendo constraints...' as status;

-- Tentar remover diferentes possíveis nomes de constraint
-- (Apenas uma dessas linhas deve funcionar, as outras darão erro - isso é normal)

SET @sql = '';
SELECT CONCAT('ALTER TABLE bairros DROP INDEX `', INDEX_NAME, '`;') INTO @sql
FROM INFORMATION_SCHEMA.STATISTICS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'bairros' 
AND COLUMN_NAME = 'valor_entrega' 
AND NON_UNIQUE = 0
LIMIT 1;

-- Se a query acima encontrou uma constraint, execute o comando gerado:
-- (Copie e cole o resultado da query acima aqui)

-- Alternativamente, tente estes comandos um por um até um funcionar:
-- ALTER TABLE bairros DROP INDEX `valor_entrega`;
-- ALTER TABLE bairros DROP INDEX `bairros_valor_entrega_unique`;
-- ALTER TABLE bairros DROP INDEX `valor_entrega_UNIQUE`;
-- ALTER TABLE bairros DROP INDEX `uk_valor_entrega`;

-- 3. VERIFICAR SE FOI REMOVIDA
SELECT 'VERIFICAÇÃO: Confirmando remoção...' as status;

SHOW INDEX FROM bairros WHERE Column_name = 'valor_entrega' AND Non_unique = 0;

-- Se a query acima não retornar nenhum resultado, a constraint foi removida!

-- 4. TESTE FINAL
SELECT 'TESTE: Verificando se funciona...' as status;

-- Limpar testes anteriores
DELETE FROM bairros WHERE nome LIKE 'TESTE_SQL_%';

-- Inserir dois bairros com mesmo valor
INSERT INTO bairros (nome, slug, cidade, valor_entrega, ativo, criado_em, atualizado_em) 
VALUES ('TESTE_SQL_1', 'teste-sql-1', 'Contagem', 20.00, 1, NOW(), NOW());

INSERT INTO bairros (nome, slug, cidade, valor_entrega, ativo, criado_em, atualizado_em) 
VALUES ('TESTE_SQL_2', 'teste-sql-2', 'Contagem', 20.00, 1, NOW(), NOW());

-- Se ambos os INSERTs funcionaram, o problema foi resolvido!

-- Verificar se foram inseridos
SELECT 'RESULTADO: Bairros de teste inseridos...' as status;
SELECT id, nome, valor_entrega FROM bairros WHERE nome LIKE 'TESTE_SQL_%';

-- Limpar testes
DELETE FROM bairros WHERE nome LIKE 'TESTE_SQL_%';

SELECT 'CONCLU��DO: Teste finalizado!' as status;

-- ============================================================================
-- SE NADA ACIMA FUNCIONOU, USE ESTA ABORDAGEM MAIS AGRESSIVA:
-- ============================================================================

/*
-- BACKUP DOS DADOS
CREATE TABLE bairros_backup_temp AS SELECT * FROM bairros;

-- RECRIAR TABELA SEM CONSTRAINT PROBLEMÁTICA
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- RESTAURAR DADOS
INSERT INTO bairros SELECT * FROM bairros_backup_temp;

-- REMOVER BACKUP
DROP TABLE bairros_backup_temp;

-- TESTAR
INSERT INTO bairros (nome, slug, cidade, valor_entrega, ativo, criado_em, atualizado_em) 
VALUES ('TESTE_FINAL_1', 'teste-final-1', 'Contagem', 20.00, 1, NOW(), NOW());

INSERT INTO bairros (nome, slug, cidade, valor_entrega, ativo, criado_em, atualizado_em) 
VALUES ('TESTE_FINAL_2', 'teste-final-2', 'Contagem', 20.00, 1, NOW(), NOW());

-- Limpar testes
DELETE FROM bairros WHERE nome LIKE 'TESTE_FINAL_%';
*/