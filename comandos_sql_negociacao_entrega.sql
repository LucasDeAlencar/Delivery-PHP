-- ============================================================
-- 1. Adiciona coluna negociacao_entrega em dados_corporativos
-- ============================================================
ALTER TABLE dados_corporativos
    ADD COLUMN negociacao_entrega TINYINT(1) NOT NULL DEFAULT 0
    AFTER entrega_ate;

-- ============================================================
-- 2. O status 'nao_concluido' é um valor de ENUM/VARCHAR na
--    coluna status da tabela pedidos.
--    Se a coluna for VARCHAR/TEXT, nenhuma alteração é necessária
--    — o valor será inserido diretamente.
--    Se for ENUM, execute o comando abaixo para adicionar o valor:
-- ============================================================
-- (execute apenas se status for ENUM)
ALTER TABLE pedidos
    MODIFY COLUMN status ENUM('pendente','confirmado','finalizado','cancelado','nao_concluido')
    NOT NULL DEFAULT 'pendente';
