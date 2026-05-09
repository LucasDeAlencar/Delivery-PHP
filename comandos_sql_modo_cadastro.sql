-- ============================================================
-- Remove a coluna modo_cadastro da tabela clientes
-- Execute no phpMyAdmin ou via CLI do MySQL/MariaDB
-- ============================================================

ALTER TABLE clientes DROP COLUMN modo_cadastro;
