-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 28/02/2026 às 11:10
-- Versão do servidor: 12.2.2-MariaDB
-- Versão do PHP: 8.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `food`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `bairros`
--

CREATE TABLE `bairros` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `cidade` varchar(20) NOT NULL DEFAULT 'Contagem',
  `valor_entrega` decimal(10,2) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `bairros`
--

INSERT INTO `bairros` (`id`, `nome`, `slug`, `cidade`, `valor_entrega`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(26, 'Agua Branca', 'agua-branca', 'Contagem', 4.55, 1, '2026-02-20 17:08:34', '2026-02-20 17:08:45', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho_temporario`
--

CREATE TABLE `carrinho_temporario` (
  `id` int(11) UNSIGNED NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `produto_id` int(11) UNSIGNED NOT NULL,
  `produto_nome` varchar(255) NOT NULL,
  `produto_imagem` varchar(255) DEFAULT NULL,
  `quantidade` int(11) UNSIGNED DEFAULT 1,
  `preco_unitario` decimal(10,2) NOT NULL,
  `preco_total` decimal(10,2) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `criado_em` datetime NOT NULL,
  `atualizado_em` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `ordem` int(11) DEFAULT 0,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `slug`, `ativo`, `ordem`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Pizza salgada', 'pizza-salgada', 1, 3, '2025-07-24 08:47:28', '2026-02-25 16:31:11', NULL),
(2, 'Pizza Doce', 'pizza-doce', 1, 1, '2025-07-24 08:47:44', '2025-07-24 08:47:44', NULL),
(3, 'Hambúrger', 'hamburger', 1, 2, '2025-07-24 08:48:07', '2025-07-24 08:48:07', NULL),
(22, 'Pizza', 'pizza', 1, 0, '2026-02-20 17:00:06', '2026-02-20 17:00:06', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `Cidade` varchar(100) DEFAULT NULL,
  `Bairro` varchar(100) DEFAULT NULL,
  `Endereco` varchar(200) DEFAULT NULL,
  `Numero` int(11) DEFAULT 0,
  `complemento` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `email`, `telefone`, `cep`, `Cidade`, `Bairro`, `Endereco`, `Numero`, `complemento`, `created_at`, `updated_at`) VALUES
(6, 'Lucas Alencar', 'lucas130333@gmail.com', '(30) 95090-3020', '32371000', 'Contagem', 'Água Branca', 'Rua Cardeal Arcoverde', 169, '102', NULL, NULL),
(7, 'Larissa ', 'larissaaapclemente2012@gmail.com', '(31) 98247-3800', '32371000', 'Contagem', 'Água Branca', 'Rua Cardeal Arcoverde', 169, 'Js', NULL, NULL),
(8, 'Lucas Alencar Pereira Clemente', 'deliverymv017@gmail.com', '(31) 98247-3800', '31270901', 'Belo Horizonte', 'São José', 'Avenida Presidente Antônio Carlos', 169, 'Apt 102', NULL, NULL),
(9, 'Lucas Alencar', 'lucas230333@gmail.com', '(32) 99635-8241', '32370360', 'Contagem', 'Conjunto Água Branca', 'Avenida Cinco', 132, 'Apt 102', NULL, NULL),
(10, 'sda', 'lucas3330333@gmail.com', '31982473800', NULL, 'Contagem', '545', 'dasd', 169, '51', '2026-02-28 10:17:38', '2026-02-28 10:17:38'),
(11, 'sdas', 'lucas33230333@gmail.com', '31982473800', NULL, 'Contagem', '545', 'dasd', 169, '51', '2026-02-28 10:17:59', '2026-02-28 10:17:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracao_entrega`
--

CREATE TABLE `configuracao_entrega` (
  `id` int(11) NOT NULL,
  `modo_cobranca` varchar(20) NOT NULL,
  `taxa_por_km` double NOT NULL,
  `taxa_minima` double NOT NULL,
  `distancia_maxima` int(11) NOT NULL,
  `cep_loja` varchar(20) NOT NULL,
  `preco_minimo_compra` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `dados_corporativos`
--

CREATE TABLE `dados_corporativos` (
  `id` int(11) UNSIGNED NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `instagram` varchar(50) DEFAULT NULL,
  `twitter` varchar(50) DEFAULT NULL,
  `facebook` varchar(50) DEFAULT NULL,
  `preco_minimo_compra` double NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Despejando dados para a tabela `dados_corporativos`
--

INSERT INTO `dados_corporativos` (`id`, `endereco`, `numero`, `email`, `instagram`, `twitter`, `facebook`, `preco_minimo_compra`, `created_at`, `updated_at`, `cep`, `whatsapp`) VALUES
(1, 'Cardeal Arcoverde, 169', '(31) 98247-3800', 'lucas130333@gmail.com', '', NULL, '', 20.5, '2026-02-23 09:20:16', '2026-02-23 09:34:33', '32371000', '31982473800');

-- --------------------------------------------------------

--
-- Estrutura para tabela `expedientes`
--

CREATE TABLE `expedientes` (
  `id` int(5) UNSIGNED NOT NULL,
  `dia` int(5) NOT NULL,
  `dia_descricao` varchar(50) NOT NULL,
  `abertura` time DEFAULT NULL,
  `fechamento` time DEFAULT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `expedientes`
--

INSERT INTO `expedientes` (`id`, `dia`, `dia_descricao`, `abertura`, `fechamento`, `situacao`) VALUES
(1, 0, 'Domingo', '18:00:00', '23:30:00', 1),
(2, 1, 'Segunda-feira', '15:20:00', '23:30:00', 1),
(3, 2, 'Terça-feira', '08:00:00', '23:30:00', 1),
(4, 3, 'Quarta-feira', '08:00:00', '23:30:00', 1),
(5, 4, 'Quinta-feira', '10:00:00', '23:30:00', 1),
(6, 5, 'Sexta-feira', '09:00:00', '23:30:00', 1),
(7, 6, 'Sábado', '09:00:00', '23:30:00', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `extras`
--

CREATE TABLE `extras` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL,
  `multitude` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Permite selecionar o mesmo extra múltiplas vezes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `extras`
--

INSERT INTO `extras` (`id`, `nome`, `slug`, `preco`, `descricao`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`, `multitude`) VALUES
(19, 'Churros', 'churros', 4.00, '2s', 1, '2026-02-20 17:42:55', '2026-02-20 17:42:55', NULL, 1),
(20, 'Agua Branca', 'agua-branca', 0.00, '', 1, '2026-02-20 18:55:10', '2026-02-20 18:55:10', NULL, 0),
(21, 'Chocolate', 'chocolate', 2.50, 's', 1, '2026-02-21 09:13:33', '2026-02-21 09:13:33', NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `formas_pagamento`
--

CREATE TABLE `formas_pagamento` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL COMMENT 'Nome da forma de pagamento',
  `slug` varchar(255) NOT NULL COMMENT 'Identificador único',
  `icone` varchar(100) DEFAULT NULL COMMENT 'Classe do ícone Font Awesome',
  `ativo` tinyint(1) DEFAULT 1 COMMENT '1 = Ativo, 0 = Inativo',
  `ordem` int(11) DEFAULT 0 COMMENT 'Ordem de exibição',
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deletado_em` datetime DEFAULT NULL,
  `codigo` varchar(100) DEFAULT NULL,
  `qrcode_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `formas_pagamento`
--

INSERT INTO `formas_pagamento` (`id`, `nome`, `slug`, `icone`, `ativo`, `ordem`, `criado_em`, `atualizado_em`, `deletado_em`, `codigo`, `qrcode_image`) VALUES
(1, 'Dinheiro', 'dinheiro', 'fas fa-money-bill-wave', 1, 1, '2025-11-27 16:49:43', NULL, NULL, NULL, NULL),
(2, 'Cartão de Débito', 'debito', 'fas fa-credit-card', 1, 2, '2025-11-27 16:49:43', '2025-11-28 14:43:55', NULL, NULL, NULL),
(3, 'Cartão de Crédito', 'credito', 'fas fa-credit-card', 1, 3, '2025-11-27 16:49:43', '2025-11-27 17:16:54', NULL, NULL, NULL),
(4, 'PIX', 'pix', 'fas fa-qrcode', 1, 4, '2025-11-27 16:49:43', '2026-02-26 14:34:25', NULL, '153.913.056-83', '1772127265_8b9879e3a434183551cc.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `medidas`
--

CREATE TABLE `medidas` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(5, '2025-01-27-000000', 'App\\Database\\Migrations\\AdicionaCampoPrecoTabelaProdutos', 'default', 'App', 1772126729, 1),
(6, '2025-01-27-000000', 'App\\Database\\Migrations\\CorrigeCampoFechamentoExpediente', 'default', 'App', 1772126729, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) UNSIGNED NOT NULL,
  `codigo` varchar(20) NOT NULL COMMENT 'Código único do pedido (ex: PED-20250130-0001)',
  `usuario_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'ID do usuário (se estiver logado)',
  `nome_cliente` varchar(120) NOT NULL COMMENT 'Nome do cliente',
  `telefone_cliente` varchar(20) NOT NULL COMMENT 'Telefone do cliente',
  `endereco_entrega` text NOT NULL COMMENT 'Endereço completo de entrega',
  `bairro_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'ID do bairro',
  `complemento` varchar(200) DEFAULT NULL COMMENT 'Complemento do endereço',
  `forma_pagamento` varchar(50) NOT NULL COMMENT 'Forma de pagamento escolhida',
  `tipo_entrega` enum('entrega','retirada') DEFAULT 'entrega' COMMENT 'Tipo de entrega: entrega ou retirada',
  `troco_para` decimal(10,2) DEFAULT NULL COMMENT 'Valor para troco (se pagamento em dinheiro)',
  `valor_produtos` decimal(10,2) NOT NULL COMMENT 'Valor total dos produtos',
  `valor_entrega` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor da taxa de entrega',
  `valor_total` decimal(10,2) NOT NULL COMMENT 'Valor total do pedido (produtos + entrega)',
  `observacoes` text DEFAULT NULL COMMENT 'Observações gerais do pedido',
  `status` enum('pendente','confirmado','preparando','saiu_entrega','entregue','cancelado') NOT NULL DEFAULT 'pendente' COMMENT 'Status do pedido',
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL,
  `inativo_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigo`, `usuario_id`, `nome_cliente`, `telefone_cliente`, `endereco_entrega`, `bairro_id`, `complemento`, `forma_pagamento`, `tipo_entrega`, `troco_para`, `valor_produtos`, `valor_entrega`, `valor_total`, `observacoes`, `status`, `criado_em`, `atualizado_em`, `deletado_em`, `inativo_em`) VALUES
(80, 'PED20260220173741779', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'debito', 'entrega', NULL, 220.00, 4.55, 224.55, NULL, 'cancelado', '2026-02-20 17:37:41', '2026-02-20 17:41:03', NULL, '2026-02-20 17:41:03'),
(81, 'PED20260220174045594', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'debito', 'entrega', NULL, 40.00, 4.55, 44.55, NULL, 'entregue', '2026-02-20 17:40:45', '2026-02-20 17:41:22', NULL, NULL),
(82, 'PED20260220174447623', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 20.00, 4.55, 24.55, NULL, 'cancelado', '2026-02-20 17:44:47', '2026-02-20 17:44:51', NULL, NULL),
(83, 'PED20260223093828567', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 128.00, 4.55, 132.55, NULL, 'cancelado', '2026-02-23 09:38:28', '2026-02-23 09:39:08', NULL, NULL),
(84, 'PED20260223094159956', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'pix', 'retirada', NULL, 92.00, 0.00, 92.00, NULL, 'cancelado', '2026-02-23 09:41:59', '2026-02-23 09:42:32', NULL, '2026-02-23 09:42:32'),
(85, 'PED20260223094450806', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'credito', 'entrega', NULL, 80.00, 4.55, 84.55, NULL, 'cancelado', '2026-02-23 09:44:50', '2026-02-23 09:45:10', NULL, NULL),
(86, 'PED20260223145210518', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'debito', 'entrega', NULL, 22.80, 4.55, 27.35, NULL, 'cancelado', '2026-02-23 14:52:10', '2026-02-23 14:55:17', NULL, NULL),
(87, 'PED20260223152205389', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'debito', 'entrega', NULL, 87.41, 4.55, 91.96, NULL, 'cancelado', '2026-02-23 15:22:05', '2026-02-23 15:55:31', NULL, NULL),
(88, 'PED20260224153229423', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 405.93, 4.55, 410.48, NULL, 'cancelado', '2026-02-24 15:32:29', '2026-02-24 15:32:44', NULL, NULL),
(89, 'PED20260224153310622', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 45.09, 4.55, 49.64, NULL, 'cancelado', '2026-02-24 15:33:10', '2026-02-24 15:33:31', NULL, NULL),
(90, 'PED20260224153754262', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 29.55, 4.55, 34.10, NULL, 'cancelado', '2026-02-24 15:37:54', '2026-02-24 15:41:04', NULL, '2026-02-24 15:41:04'),
(91, 'PED20260224154128628', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 80.29, 4.55, 84.84, NULL, 'cancelado', '2026-02-24 15:41:28', '2026-02-24 15:41:44', NULL, NULL),
(92, 'PED20260225141126885', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 31.77, 4.55, 36.32, NULL, 'cancelado', '2026-02-25 14:11:26', '2026-02-25 14:13:59', NULL, NULL),
(93, 'PED20260226142006970', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'pix', 'retirada', NULL, 56.00, 0.00, 56.00, NULL, 'cancelado', '2026-02-26 14:20:06', '2026-02-26 14:20:17', NULL, NULL),
(94, 'PED20260226142856533', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'pix', 'retirada', NULL, 74.13, 0.00, 74.13, NULL, 'cancelado', '2026-02-26 14:28:56', '2026-02-26 15:46:24', NULL, NULL),
(95, 'PED20260226155544207', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'pix', 'retirada', NULL, 64.10, 0.00, 64.10, NULL, 'cancelado', '2026-02-26 15:55:44', '2026-02-26 15:58:06', NULL, NULL),
(96, 'PED20260226155840130', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'pix', 'retirada', NULL, 49.25, 0.00, 49.25, NULL, 'cancelado', '2026-02-26 15:58:40', '2026-02-26 15:58:56', NULL, NULL),
(97, 'PED20260226155914198', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'pix', 'retirada', NULL, 49.25, 0.00, 49.25, NULL, 'cancelado', '2026-02-26 15:59:14', '2026-02-26 15:59:46', NULL, NULL),
(98, 'PED20260226160000886', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'pix', 'retirada', NULL, 39.40, 0.00, 39.40, NULL, 'cancelado', '2026-02-26 16:00:00', '2026-02-26 16:01:06', NULL, NULL),
(99, 'PED20260226160931318', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 49.25, 4.55, 53.80, NULL, 'cancelado', '2026-02-26 16:09:31', '2026-02-26 16:09:46', NULL, NULL),
(100, 'PED20260226161017697', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'pix', 'retirada', NULL, 32.95, 0.00, 32.95, NULL, 'cancelado', '2026-02-26 16:10:17', '2026-02-26 16:10:54', NULL, NULL),
(101, 'PED20260226164408505', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'pix', 'retirada', NULL, 66.36, 0.00, 66.36, NULL, 'cancelado', '2026-02-26 16:44:08', '2026-02-26 16:46:14', NULL, NULL),
(102, 'PED20260226164636373', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'pix', 'retirada', NULL, 42.36, 0.00, 42.36, NULL, 'cancelado', '2026-02-26 16:46:36', '2026-02-26 16:49:07', NULL, NULL),
(103, 'PED20260226165520683', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 88.65, 4.55, 93.20, NULL, 'cancelado', '2026-02-26 16:55:20', '2026-02-26 16:55:36', NULL, NULL),
(104, 'PED20260226165605751', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 39.40, 4.55, 43.95, NULL, 'cancelado', '2026-02-26 16:56:05', '2026-02-26 16:58:03', NULL, NULL),
(105, 'PED20260226165848415', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 80.00, 4.55, 84.55, NULL, 'cancelado', '2026-02-26 16:58:48', '2026-02-26 16:58:55', NULL, NULL),
(106, 'PED20260226165912598', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 60.00, 4.55, 64.55, NULL, 'cancelado', '2026-02-26 16:59:12', '2026-02-27 09:03:28', NULL, '2026-02-27 09:03:28'),
(107, 'PED20260227090408165', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'credito', 'entrega', NULL, 29.55, 4.55, 34.10, NULL, 'cancelado', '2026-02-27 09:04:08', '2026-02-27 09:53:12', NULL, NULL),
(108, 'PED20260227094933641', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'debito', 'retirada', NULL, 39.40, 0.00, 39.40, NULL, '', '2026-02-27 09:49:33', NULL, NULL, '2026-02-28 10:40:07'),
(109, 'PED20260227095330664', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'debito', 'entrega', NULL, 63.54, 4.55, 68.09, NULL, 'cancelado', '2026-02-27 09:53:30', '2026-02-27 09:59:01', NULL, NULL),
(110, 'PED20260227095825157', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'debito', 'retirada', NULL, 120.00, 0.00, 120.00, NULL, '', '2026-02-27 09:58:25', NULL, NULL, '2026-02-28 10:40:07'),
(111, 'PED20260227095925675', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'debito', 'retirada', NULL, 49.25, 0.00, 49.25, NULL, 'cancelado', '2026-02-27 09:59:25', '2026-02-27 10:10:45', NULL, NULL),
(112, 'PED20260227101110717', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Retirada no local', NULL, NULL, 'pix', 'retirada', NULL, 160.00, 0.00, 160.00, NULL, 'entregue', '2026-02-27 10:11:10', '2026-02-27 10:21:10', NULL, NULL),
(113, 'PED20260227102221534', NULL, 'Lucas Alencar', '(31) 98247-3800', 'Retirada no local', NULL, NULL, 'debito', 'retirada', NULL, 26.36, 0.00, 26.36, NULL, 'cancelado', '2026-02-27 10:22:21', '2026-02-27 11:00:20', NULL, NULL),
(114, 'PED20260227155635640', NULL, 'Lucas Alencar', '(32) 99635-8241', 'Retirada no local', NULL, NULL, 'pix', 'retirada', NULL, 60.00, 0.00, 60.00, NULL, 'cancelado', '2026-02-27 15:56:35', '2026-02-27 15:57:55', NULL, NULL),
(115, 'PED20260228103749163', NULL, 'Lucas Alencar', '(30) 95090-3020', 'Rua Cardeal Arcoverde, 169 - Água Branca, Contagem', NULL, NULL, 'pix', 'entrega', NULL, 59.10, 4.55, 63.65, NULL, 'cancelado', '2026-02-28 10:37:49', '2026-02-28 10:39:21', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos_itens`
--

CREATE TABLE `pedidos_itens` (
  `id` int(11) UNSIGNED NOT NULL,
  `pedido_id` int(11) UNSIGNED NOT NULL COMMENT 'ID do pedido',
  `produto_id` int(11) UNSIGNED DEFAULT NULL,
  `produto_nome` varchar(128) NOT NULL COMMENT 'Nome do produto (snapshot)',
  `quantidade` int(11) NOT NULL COMMENT 'Quantidade do produto',
  `preco_unitario` decimal(10,2) NOT NULL COMMENT 'Preço unitário no momento da compra',
  `preco_total` decimal(10,2) NOT NULL COMMENT 'Preço total do item (quantidade * preço unitário)',
  `observacoes` text DEFAULT NULL COMMENT 'Observações específicas do item',
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `pedidos_itens`
--

INSERT INTO `pedidos_itens` (`id`, `pedido_id`, `produto_id`, `produto_nome`, `quantidade`, `preco_unitario`, `preco_total`, `observacoes`, `criado_em`, `atualizado_em`) VALUES
(86, 80, NULL, 'X CALABACON', 11, 20.00, 220.00, '', '2026-02-20 17:37:41', NULL),
(87, 81, NULL, 'X CALABACON', 2, 20.00, 40.00, '', '2026-02-20 17:40:45', NULL),
(88, 82, NULL, 'X CALABACON', 1, 20.00, 20.00, '', '2026-02-20 17:44:47', NULL),
(89, 83, NULL, 'X CALABACON', 4, 20.00, 128.00, '', '2026-02-23 09:38:28', NULL),
(90, 84, NULL, 'X CALABACON', 1, 20.00, 20.00, '', '2026-02-23 09:41:59', NULL),
(91, 84, NULL, 'X CALABACON', 3, 20.00, 72.00, '', '2026-02-23 09:41:59', NULL),
(92, 85, NULL, 'X CALABACON', 4, 20.00, 80.00, '', '2026-02-23 09:44:50', NULL),
(93, 86, 131, 'Hamburguer', 1, 9.85, 9.85, '', '2026-02-23 14:52:10', NULL),
(94, 86, 132, 'Molho', 5, 2.59, 12.95, '', '2026-02-23 14:52:10', NULL),
(95, 87, 132, 'Molho', 4, 2.59, 10.36, '', '2026-02-23 15:22:05', NULL),
(96, 87, 130, 'Pizza', 2, 20.00, 40.00, '', '2026-02-23 15:22:05', NULL),
(97, 87, 131, 'Hamburguer', 3, 9.85, 37.05, '', '2026-02-23 15:22:05', NULL),
(98, 88, 131, 'Hamburguer', 3, 9.85, 29.55, '', '2026-02-24 15:32:29', NULL),
(99, 88, 130, 'Pizza', 4, 20.00, 80.00, '', '2026-02-24 15:32:29', NULL),
(100, 88, 130, 'Pizza', 3, 20.00, 84.00, '', '2026-02-24 15:32:29', NULL),
(101, 88, 132, 'Molho', 1, 2.59, 2.59, '', '2026-02-24 15:32:29', NULL),
(102, 88, 132, 'Molho', 81, 2.59, 209.79, '', '2026-02-24 15:32:29', NULL),
(103, 89, 132, 'Molho', 6, 2.59, 15.54, '', '2026-02-24 15:33:10', NULL),
(104, 89, 131, 'Hamburguer', 3, 9.85, 29.55, '', '2026-02-24 15:33:10', NULL),
(105, 90, 131, 'Hamburguer', 3, 9.85, 29.55, '', '2026-02-24 15:37:54', NULL),
(106, 91, 132, 'Molho', 31, 2.59, 80.29, '', '2026-02-24 15:41:28', NULL),
(107, 92, 132, 'Molho', 3, 2.59, 31.77, '', '2026-02-25 14:11:26', NULL),
(108, 93, 130, 'Pizza', 2, 20.00, 56.00, '', '2026-02-26 14:20:06', NULL),
(109, 94, 132, 'Molho', 7, 2.59, 74.13, '', '2026-02-26 14:28:56', NULL),
(110, 95, 131, 'Hamburguer', 2, 9.85, 24.70, '', '2026-02-26 15:55:44', NULL),
(111, 95, 131, 'Hamburguer', 4, 9.85, 39.40, '', '2026-02-26 15:55:44', NULL),
(112, 96, 131, 'Hamburguer', 5, 9.85, 49.25, '', '2026-02-26 15:58:40', NULL),
(113, 97, 131, 'Hamburguer', 5, 9.85, 49.25, '', '2026-02-26 15:59:14', NULL),
(114, 98, 131, 'Hamburguer', 4, 9.85, 39.40, '', '2026-02-26 16:00:00', NULL),
(115, 99, 131, 'Hamburguer', 5, 9.85, 49.25, '', '2026-02-26 16:09:31', NULL),
(116, 100, 132, 'Molho', 5, 2.59, 32.95, '', '2026-02-26 16:10:17', NULL),
(117, 101, 132, 'Molho', 4, 2.59, 26.36, '', '2026-02-26 16:44:08', NULL),
(118, 101, 130, 'Pizza', 2, 20.00, 40.00, '', '2026-02-26 16:44:08', NULL),
(119, 102, 132, 'Molho', 4, 2.59, 42.36, '', '2026-02-26 16:46:36', NULL),
(120, 103, 131, 'Hamburguer', 9, 9.85, 88.65, '', '2026-02-26 16:55:20', NULL),
(121, 104, 131, 'Hamburguer', 4, 9.85, 39.40, '', '2026-02-26 16:56:05', NULL),
(122, 105, 130, 'Pizza', 4, 20.00, 80.00, '', '2026-02-26 16:58:48', NULL),
(123, 106, 130, 'Pizza', 3, 20.00, 60.00, '', '2026-02-26 16:59:12', NULL),
(124, 107, 131, 'Hamburguer', 3, 9.85, 29.55, '', '2026-02-27 09:04:08', NULL),
(125, 108, 131, 'Hamburguer', 4, 9.85, 39.40, '', '2026-02-27 09:49:33', NULL),
(126, 109, 132, 'Molho', 6, 2.59, 63.54, '', '2026-02-27 09:53:30', NULL),
(127, 110, 130, 'Pizza', 6, 20.00, 120.00, '', '2026-02-27 09:58:25', NULL),
(128, 111, 131, 'Hamburguer', 5, 9.85, 49.25, '', '2026-02-27 09:59:25', NULL),
(129, 112, 130, 'Pizza', 4, 20.00, 160.00, '', '2026-02-27 10:11:10', NULL),
(130, 113, 132, 'Molho', 4, 2.59, 26.36, '', '2026-02-27 10:22:21', NULL),
(131, 114, 130, 'Pizza', 3, 20.00, 60.00, '', '2026-02-27 15:56:35', NULL),
(132, 115, 131, 'Hamburguer', 6, 9.85, 59.10, '', '2026-02-28 10:37:49', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos_itens_extras`
--

CREATE TABLE `pedidos_itens_extras` (
  `id` int(10) UNSIGNED NOT NULL,
  `pedido_item_id` int(10) UNSIGNED NOT NULL,
  `extra_id` int(10) UNSIGNED NOT NULL,
  `extra_nome` varchar(120) NOT NULL,
  `extra_preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantidade` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `pedidos_itens_extras`
--

INSERT INTO `pedidos_itens_extras` (`id`, `pedido_item_id`, `extra_id`, `extra_nome`, `extra_preco`, `quantidade`) VALUES
(15, 89, 19, 'Churros', 4.00, 1),
(16, 91, 19, 'Churros', 4.00, 1),
(17, 97, 21, 'Chocolate', 2.50, 1),
(18, 100, 19, 'Churros', 4.00, 1),
(19, 101, 19, 'Churros', 4.00, 1),
(20, 105, 21, 'Chocolate', 2.50, 1),
(21, 107, 19, 'Churros', 4.00, 1),
(22, 108, 19, 'Churros', 4.00, 2),
(23, 109, 19, 'Churros', 4.00, 2),
(24, 110, 21, 'Chocolate', 2.50, 1),
(25, 116, 19, 'Churros', 4.00, 1),
(26, 117, 19, 'Churros', 4.00, 1),
(27, 119, 19, 'Churros', 4.00, 2),
(28, 126, 19, 'Churros', 4.00, 2),
(29, 129, 19, 'Churros', 4.00, 5),
(30, 130, 19, 'Churros', 4.00, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(5) UNSIGNED NOT NULL,
  `categoria_id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `ingredientes` text DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `imagem` varchar(200) DEFAULT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `obrigatorio_extras` int(11) DEFAULT 0 COMMENT 'Quantidade de extras obrigatórios',
  `max_extras` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `categoria_id`, `nome`, `slug`, `ingredientes`, `ativo`, `imagem`, `criado_em`, `atualizado_em`, `deletado_em`, `preco`, `obrigatorio_extras`, `max_extras`) VALUES
(130, 1, 'Pizza', 'pizza', '', 1, '1771854229_0275d1e59cf72d902162.jpeg', '2026-02-23 10:43:49', '2026-02-23 10:43:49', NULL, 20.00, 0, 0),
(131, 3, 'Hamburguer', 'hamburguer', '', 1, '1771854261_adb3cdcba2981a2bbaf9.jpeg', '2026-02-23 10:44:21', '2026-02-25 09:30:56', NULL, 9.85, 0, 0),
(132, 22, 'Molho', 'molho', 's', 1, '1771854289_ed80cd5575681649c544.jpeg', '2026-02-23 10:44:49', '2026-02-25 09:35:23', NULL, 2.59, 1, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos_especificacoes`
--

CREATE TABLE `produtos_especificacoes` (
  `id` int(5) UNSIGNED NOT NULL,
  `produto_id` int(5) UNSIGNED NOT NULL,
  `medida_id` int(5) UNSIGNED NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `customizavel` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos_extras`
--

CREATE TABLE `produtos_extras` (
  `id` int(5) UNSIGNED NOT NULL,
  `produto_id` int(5) UNSIGNED NOT NULL,
  `extra_id` int(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos_extras`
--

INSERT INTO `produtos_extras` (`id`, `produto_id`, `extra_id`) VALUES
(586, 130, 19),
(588, 132, 19),
(589, 132, 21),
(590, 132, 20),
(591, 131, 21);

-- --------------------------------------------------------

--
-- Estrutura para tabela `suporte_pedidos`
--

CREATE TABLE `suporte_pedidos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `codigo_pedido` varchar(50) NOT NULL,
  `cliente_nome` varchar(255) NOT NULL,
  `cliente_telefone` varchar(20) NOT NULL,
  `razao` varchar(255) NOT NULL,
  `status` enum('pendente','resolvido','cancelado') DEFAULT 'pendente',
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Despejando dados para a tabela `suporte_pedidos`
--

INSERT INTO `suporte_pedidos` (`id`, `pedido_id`, `codigo_pedido`, `cliente_nome`, `cliente_telefone`, `razao`, `status`, `criado_em`, `atualizado_em`) VALUES
(1, 106, 'PED20260226165912598', 'Cliente', '', 'Pedido não chegou', 'resolvido', '2026-02-26 17:13:17', '2026-02-26 17:13:30'),
(9, 107, 'PED20260227090408165', 'Cliente', '31982473800', 'Pedido não chegou - Realizei um pedido pelo delivery dentro do horário informado e recebi a confirmação normalmente. Porém, após o prazo estimado de entrega, o pedido não foi entregue e não recebi nenhuma atualização no aplicativo.', 'resolvido', '2026-02-27 09:22:24', '2026-02-27 10:19:40'),
(10, 107, 'PED20260227090408165', 'Cliente', '31982473800', 'Pedido não chegou - dasd', 'resolvido', '2026-02-27 09:26:20', '2026-02-27 10:19:40'),
(11, 107, 'PED20260227090408165', 'Cliente', '31982473800', 'Pedido não chegou - dasdasdas', 'resolvido', '2026-02-27 09:31:26', '2026-02-27 10:19:40'),
(12, 107, 'PED20260227090408165', 'Cliente', '31982473800', 'Atraso na entrega - sdasd', 'resolvido', '2026-02-27 09:31:51', '2026-02-27 10:19:40'),
(13, 107, 'PED20260227090408165', 'Lucas Alencar', '31982473800', 'Produto veio errado - sdasd', 'resolvido', '2026-02-27 09:37:46', '2026-02-27 10:19:40'),
(14, 107, 'PED20260227090408165', 'Lucas Alencar', '31982473800', 'asdasd', 'resolvido', '2026-02-27 09:46:15', '2026-02-27 10:19:40'),
(15, 107, 'PED20260227090408165', 'Lucas Alencar', '31982473800', 'O pedido não não foi entregue', 'resolvido', '2026-02-27 09:48:16', '2026-02-27 10:19:40'),
(16, 108, 'PED20260227094933641', 'Lucas Alencar', '31982473800', 'adasd', 'resolvido', '2026-02-27 09:49:47', '2026-02-27 10:19:33'),
(17, 109, 'PED20260227095330664', 'Lucas Alencar', '31982473800', 'sadasdd', 'resolvido', '2026-02-27 09:53:35', '2026-02-27 09:56:06'),
(18, 109, 'PED20260227095330664', 'Lucas Alencar', '31982473800', 's', 'resolvido', '2026-02-27 09:55:23', '2026-02-27 09:56:06'),
(19, 109, 'PED20260227095330664', 'Lucas Alencar', '31982473800', 's', 'resolvido', '2026-02-27 09:55:27', '2026-02-27 09:56:06'),
(20, 109, 'PED20260227095330664', 'Lucas Alencar', '31982473800', 'ss', 'resolvido', '2026-02-27 09:55:30', '2026-02-27 09:56:06'),
(21, 111, 'PED20260227095925675', 'Lucas Alencar', '31982473800', 's', 'resolvido', '2026-02-27 10:06:07', '2026-02-27 10:15:05'),
(22, 111, 'PED20260227095925675', 'Lucas Alencar', '31982473800', 'sdasda', 'resolvido', '2026-02-27 10:10:23', '2026-02-27 10:15:05'),
(23, 112, 'PED20260227101110717', 'Lucas Alencar', '31982473800', 'sasdas', 'pendente', '2026-02-27 10:11:16', '2026-02-27 10:11:16'),
(24, 113, 'PED20260227102221534', 'Lucas Alencar', '31982473800', 'asd', 'pendente', '2026-02-27 10:22:25', '2026-02-27 10:22:25'),
(25, 113, 'PED20260227102221534', 'Lucas Alencar', '31982473800', 'Pedido atrasado', 'pendente', '2026-02-27 10:25:48', '2026-02-27 10:25:48');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 0,
  `password_hash` varchar(255) NOT NULL,
  `ativacao_hash` varchar(255) DEFAULT NULL,
  `reset_hash` varchar(255) DEFAULT NULL,
  `reset_expira_em` datetime DEFAULT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `cpf`, `telefone`, `is_admin`, `ativo`, `password_hash`, `ativacao_hash`, `reset_hash`, `reset_expira_em`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Administrador', 'admin@gmail.com', '792.444.896-94', '31 982473800', 1, 1, '$2y$10$QQypKZDTRILxLNkm7.jS2uSU2pV05FJCMDZpGInuSbRwsQqsJhCoy', '1', NULL, NULL, NULL, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `bairros`
--
ALTER TABLE `bairros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Índices de tabela `carrinho_temporario`
--
ALTER TABLE `carrinho_temporario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `configuracao_entrega`
--
ALTER TABLE `configuracao_entrega`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `dados_corporativos`
--
ALTER TABLE `dados_corporativos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `expedientes`
--
ALTER TABLE `expedientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `extras`
--
ALTER TABLE `extras`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `formas_pagamento`
--
ALTER TABLE `formas_pagamento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Índices de tabela `medidas`
--
ALTER TABLE `medidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_codigo` (`codigo`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_bairro_id` (`bairro_id`);

--
-- Índices de tabela `pedidos_itens`
--
ALTER TABLE `pedidos_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pedido_id` (`pedido_id`),
  ADD KEY `idx_produto_id` (`produto_id`);

--
-- Índices de tabela `pedidos_itens_extras`
--
ALTER TABLE `pedidos_itens_extras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_item_id` (`pedido_item_id`),
  ADD KEY `extra_id` (`extra_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices de tabela `produtos_especificacoes`
--
ALTER TABLE `produtos_especificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`),
  ADD KEY `medida_id` (`medida_id`);

--
-- Índices de tabela `produtos_extras`
--
ALTER TABLE `produtos_extras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`),
  ADD KEY `extra_id` (`extra_id`);

--
-- Índices de tabela `suporte_pedidos`
--
ALTER TABLE `suporte_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `codigo_pedido` (`codigo_pedido`),
  ADD KEY `status` (`status`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `ativacao_hash` (`ativacao_hash`),
  ADD UNIQUE KEY `reset_hash` (`reset_hash`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `bairros`
--
ALTER TABLE `bairros`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `carrinho_temporario`
--
ALTER TABLE `carrinho_temporario`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `configuracao_entrega`
--
ALTER TABLE `configuracao_entrega`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `dados_corporativos`
--
ALTER TABLE `dados_corporativos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `expedientes`
--
ALTER TABLE `expedientes`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `extras`
--
ALTER TABLE `extras`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `formas_pagamento`
--
ALTER TABLE `formas_pagamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `medidas`
--
ALTER TABLE `medidas`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT de tabela `pedidos_itens`
--
ALTER TABLE `pedidos_itens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT de tabela `pedidos_itens_extras`
--
ALTER TABLE `pedidos_itens_extras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT de tabela `produtos_especificacoes`
--
ALTER TABLE `produtos_especificacoes`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos_extras`
--
ALTER TABLE `produtos_extras`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=592;

--
-- AUTO_INCREMENT de tabela `suporte_pedidos`
--
ALTER TABLE `suporte_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_bairros` FOREIGN KEY (`bairro_id`) REFERENCES `bairros` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedidos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `pedidos_itens`
--
ALTER TABLE `pedidos_itens`
  ADD CONSTRAINT `fk_pedidos_itens_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedidos_itens_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Restrições para tabelas `produtos_especificacoes`
--
ALTER TABLE `produtos_especificacoes`
  ADD CONSTRAINT `produtos_especificacoes_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produtos_especificacoes_ibfk_2` FOREIGN KEY (`medida_id`) REFERENCES `medidas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `produtos_extras`
--
ALTER TABLE `produtos_extras`
  ADD CONSTRAINT `produtos_extras_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produtos_extras_ibfk_2` FOREIGN KEY (`extra_id`) REFERENCES `extras` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
