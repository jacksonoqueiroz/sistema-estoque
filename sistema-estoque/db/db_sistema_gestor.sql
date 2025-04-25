-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/04/2025 às 23:23
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_sistema_gestor`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `endereco` varchar(220) NOT NULL,
  `telefone` varchar(220) NOT NULL,
  `email` varchar(220) NOT NULL,
  `contato` varchar(220) NOT NULL,
  `created` date NOT NULL DEFAULT current_timestamp(),
  `modifield` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `encerra_operacao`
--

CREATE TABLE `encerra_operacao` (
  `id` int(11) NOT NULL,
  `id_ordem` int(11) NOT NULL,
  `id_peca` int(11) NOT NULL,
  `id_operacao` int(11) DEFAULT NULL,
  `tempo` time DEFAULT '00:00:00',
  `hora_inicio` time DEFAULT NULL,
  `hora_fim` time DEFAULT NULL,
  `eficiencia` int(11) DEFAULT NULL,
  `status` varchar(5) NOT NULL DEFAULT 'ENC',
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario` varchar(220) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `encerra_operacao`
--

INSERT INTO `encerra_operacao` (`id`, `id_ordem`, `id_peca`, `id_operacao`, `tempo`, `hora_inicio`, `hora_fim`, `eficiencia`, `status`, `created`, `usuario`) VALUES
(1, 1, 1, 1, '01:00:00', '01:01:00', '02:01:00', 100, 'ENC', '2025-04-18 21:31:01', 'Jackson Queiroz'),
(2, 1, 1, 2, '01:00:00', '06:00:00', '07:00:00', 100, 'ENC', '2025-04-18 21:37:03', 'Jackson Queiroz'),
(3, 1, 1, 20, '01:00:00', '08:01:00', '09:01:00', 100, 'ENC', '2025-04-18 21:38:24', 'Jackson Queiroz'),
(5, 1, 1, 26, '01:00:00', '09:00:00', '10:00:00', 100, 'ENC', '2025-04-18 21:44:45', 'Jackson Queiroz');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `tipo` varchar(220) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id`, `nome`, `cnpj`, `telefone`, `email`, `endereco`, `tipo`, `criado_em`) VALUES
(1, 'Fornecedor', '112220202', '(00)1122334', 'fornecedor@gmail.com', 'Rua Endereço,01', 'material', '2025-04-23 16:40:48'),
(3, 'Fornecedor 2', '3030304302', '(00)0101010848', 'fornecedor2@gmail.com', 'Rua Endereço', 'material', '2025-04-24 18:31:22');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor_itens`
--

CREATE TABLE `fornecedor_itens` (
  `id` int(11) NOT NULL,
  `fornecedor_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor_materiais`
--

CREATE TABLE `fornecedor_materiais` (
  `id` int(11) NOT NULL,
  `fornecedor_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedor_materiais`
--

INSERT INTO `fornecedor_materiais` (`id`, `fornecedor_id`, `material_id`) VALUES
(3, 1, 3),
(5, 3, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `descricao` text NOT NULL,
  `usuario` varchar(220) DEFAULT NULL,
  `created` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `grupos`
--

INSERT INTO `grupos` (`id`, `nome`, `descricao`, `usuario`, `created`) VALUES
(1, 'Estrutura', 'Estrutura', 'Administrador', '2025-04-04'),
(2, 'Transmissão', 'Transmissão', 'Administrador', '2025-04-04'),
(3, 'Alinhamento', 'Alinhamento', 'Jackson Queiroz', '2025-04-04'),
(4, 'Elétrica', 'Montagem de componentes.', 'Jackson Queiroz', '2025-04-04');

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico`
--

CREATE TABLE `historico` (
  `id` int(11) NOT NULL,
  `acao` text DEFAULT NULL,
  `nome` varchar(220) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `usuario` varchar(220) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `historico`
--

INSERT INTO `historico` (`id`, `acao`, `nome`, `descricao`, `data`, `usuario`) VALUES
(1, 'Cadastrou Ordem de Produção.', NULL, 'Produção para compor estoque', '2025-04-13 21:12:22', 'Jackson Queiroz'),
(2, 'Inseriu Operação.', NULL, NULL, '2025-04-14 14:27:11', 'Jackson Queiroz'),
(3, 'Inseriu Operação.', NULL, NULL, '2025-04-14 14:28:06', 'Jackson Queiroz'),
(4, 'Inseriu Operação.', NULL, NULL, '2025-04-14 14:28:23', 'Jackson Queiroz'),
(5, 'Inseriu Operação.', NULL, NULL, '2025-04-14 14:28:31', 'Jackson Queiroz'),
(6, 'Inseriu Operação.', NULL, NULL, '2025-04-14 16:10:33', 'Jackson Queiroz'),
(7, 'Inseriu Operação.', NULL, NULL, '2025-04-14 16:11:45', 'Jackson Queiroz'),
(8, 'Inseriu Operação.', NULL, NULL, '2025-04-14 16:12:07', 'Jackson Queiroz'),
(9, 'Inseriu Operação.', NULL, NULL, '2025-04-14 16:12:29', 'Jackson Queiroz'),
(10, 'Inseriu Operação.', NULL, 'Separar Material para produção', '2025-04-15 19:35:02', 'Jackson Queiroz'),
(11, 'Inseriu Operação.', NULL, 'Cortar e separar material', '2025-04-15 19:37:59', 'Jackson Queiroz'),
(12, 'Inseriu Operação.', NULL, 'Pintar na cor programada.', '2025-04-16 15:07:42', 'Jackson Queiroz'),
(13, 'Inseriu Operação.', NULL, 'Cortar material', '2025-04-17 14:28:21', 'Jackson Queiroz'),
(14, 'Inseriu Operação.', NULL, 'Separar Material', '2025-04-17 14:28:39', 'Jackson Queiroz'),
(15, 'Inseriu Operação.', NULL, 'Soldar', '2025-04-17 14:28:54', 'Jackson Queiroz'),
(16, 'Inseriu Operação.', NULL, 'Pintar', '2025-04-17 14:29:07', 'Jackson Queiroz'),
(17, 'Inseriu Operação.', NULL, 'Cortar e dobrar', '2025-04-17 14:29:26', 'Jackson Queiroz'),
(18, 'Inseriu Operação.', NULL, 'Furar', '2025-04-17 14:29:43', 'Jackson Queiroz'),
(19, 'Inseriu Operação.', NULL, 'Soldar', '2025-04-17 14:29:57', 'Jackson Queiroz'),
(20, 'Inseriu Operação.', NULL, 'Pintar', '2025-04-17 14:53:05', 'Jackson Queiroz'),
(21, 'Inseriu Operação.', NULL, 'Separar Material', '2025-04-17 15:17:21', 'Jackson Queiroz'),
(22, 'Inseriu Operação.', NULL, 'Cortar material', '2025-04-17 15:18:06', 'Jackson Queiroz'),
(23, 'Inseriu Operação.', NULL, 'Soldar', '2025-04-17 15:18:20', 'Jackson Queiroz'),
(24, 'Inseriu Operação.', NULL, 'Pintar', '2025-04-17 15:18:37', 'Jackson Queiroz'),
(25, 'Inseriu Operação.', NULL, 'Separar Material', '2025-04-17 15:18:57', 'Jackson Queiroz'),
(26, 'Inseriu Operação.', NULL, 'Cortar Material', '2025-04-17 15:19:12', 'Jackson Queiroz'),
(27, 'Inseriu Operação.', NULL, 'Separar Material', '2025-04-17 19:43:27', 'Jackson Queiroz'),
(28, 'Inseriu Operação.', NULL, 'Corte Material', '2025-04-17 19:43:41', 'Jackson Queiroz'),
(29, 'Inseriu Operação.', NULL, 'Soldar', '2025-04-17 19:44:03', 'Jackson Queiroz'),
(30, 'Inseriu Operação.', NULL, 'Pintura das Peças', '2025-04-17 19:44:25', 'Jackson Queiroz'),
(31, 'Inseriu Operação.', NULL, 'Cortar Material', '2025-04-17 19:53:15', 'Jackson Queiroz'),
(32, 'Inseriu Operação.', NULL, 'Separar material', '2025-04-17 19:53:45', 'Jackson Queiroz'),
(33, 'Inseriu Operação.', NULL, 'Soldar', '2025-04-17 19:54:02', 'Jackson Queiroz'),
(34, 'Inseriu Operação.', NULL, 'Pintar', '2025-04-17 20:51:58', 'Jackson Queiroz'),
(35, 'Inseriu Operação.', NULL, 'Pintar', '2025-04-18 20:57:07', 'Jackson Queiroz'),
(36, 'Inseriu Operação.', NULL, 'Separar Material', '2025-04-18 21:25:17', 'Jackson Queiroz'),
(37, 'Inseriu Operação.', NULL, 'Cortar Material', '2025-04-18 21:25:36', 'Jackson Queiroz'),
(38, 'Inseriu Operação.', NULL, 'Soldar', '2025-04-18 21:25:53', 'Jackson Queiroz'),
(39, 'Inseriu Operação.', NULL, 'Roscar conforme desenho', '2025-04-18 21:41:57', 'Jackson Queiroz'),
(40, 'Cadastrou Item.', 'teste', 'teste', '2025-04-25 15:20:40', 'Jackson Queiroz'),
(41, 'Cadastrou Item.', 'teste', 'teste', '2025-04-25 15:24:10', 'Jackson Queiroz');

-- --------------------------------------------------------

--
-- Estrutura para tabela `inserir_op`
--

CREATE TABLE `inserir_op` (
  `id` int(11) NOT NULL,
  `id_operacao` int(11) NOT NULL,
  `id_peca` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `tempo` varchar(5) DEFAULT '0,00',
  `status` varchar(5) NOT NULL DEFAULT 'LB',
  `created` datetime DEFAULT current_timestamp(),
  `usuario` varchar(220) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `inserir_op`
--

INSERT INTO `inserir_op` (`id`, `id_operacao`, `id_peca`, `descricao`, `tempo`, `status`, `created`, `usuario`) VALUES
(1, 1, 1, 'Separar Material', '01:00', 'ENC', '2025-04-18 21:25:17', NULL),
(2, 2, 1, 'Cortar Material', '01:00', 'ENC', '2025-04-18 21:25:36', NULL),
(3, 20, 1, 'Soldar', '01:00', 'ENC', '2025-04-18 21:25:53', NULL),
(4, 26, 1, 'Roscar conforme desenho', '01:00', 'ENC', '2025-04-18 21:41:57', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `inserir_ordem`
--

CREATE TABLE `inserir_ordem` (
  `id` int(11) NOT NULL,
  `id_ordem` int(11) NOT NULL,
  `id_peca` int(11) NOT NULL,
  `qtd` int(11) NOT NULL,
  `status` varchar(5) DEFAULT 'LB',
  `created` date NOT NULL DEFAULT current_timestamp(),
  `modifield` date DEFAULT NULL,
  `usuario` varchar(220) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `inserir_ordem`
--

INSERT INTO `inserir_ordem` (`id`, `id_ordem`, `id_peca`, `qtd`, `status`, `created`, `modifield`, `usuario`) VALUES
(1, 1, 1, 2, 'LB', '2025-04-14', NULL, 'Jackson Queiroz'),
(2, 1, 2, 2, 'LB', '2025-04-14', NULL, 'Jackson Queiroz'),
(3, 1, 8, 2, 'LB', '2025-04-14', NULL, 'Jackson Queiroz'),
(4, 1, 7, 2, 'LB', '2025-04-14', NULL, 'Jackson Queiroz');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens`
--

CREATE TABLE `itens` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `descricao` text NOT NULL,
  `id_material` int(11) NOT NULL,
  `id_peca` int(11) NOT NULL,
  `qtd_itens` int(11) NOT NULL,
  `codigo` varchar(220) DEFAULT NULL,
  `localizacao` varchar(100) DEFAULT NULL,
  `estoque_atual` int(11) DEFAULT NULL,
  `estoque_minimo` int(11) DEFAULT NULL,
  `unidade` varchar(50) NOT NULL DEFAULT 'un',
  `usuario` varchar(220) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modifield` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens`
--

INSERT INTO `itens` (`id`, `nome`, `descricao`, `id_material`, `id_peca`, `qtd_itens`, `codigo`, `localizacao`, `estoque_atual`, `estoque_minimo`, `unidade`, `usuario`, `created`, `modifield`) VALUES
(1, 'Lateral direito 250 mm', '300X400X500', 3, 1, 1, 'ITC-001-01', NULL, 9, 2, 'un', 'Jackson Queiroz', '2025-04-11 17:14:00', NULL),
(2, 'Lateral esquerdo 250mm', '300X400X500', 3, 1, 1, 'ITC-001-02', NULL, 10, 2, 'un', 'Jackson Queiroz', '2025-04-11 17:18:39', NULL),
(3, 'Triangulo lateral', '100X50X30X45', 1, 1, 2, 'ITC-001-03', NULL, 13, 2, 'un', 'Jackson Queiroz', '2025-04-11 17:21:48', NULL),
(4, 'Junção Laterais', '300X100', 3, 1, 1, 'ITC-001-04', NULL, 5, 2, 'un', 'Jackson Queiroz', '2025-04-11 17:22:54', NULL),
(5, 'Topo frontal 250 mm', '100X200X100', 1, 1, 1, 'ITC-001-05', NULL, 5, 2, 'un', 'Jackson Queiroz', '2025-04-11 17:24:54', NULL),
(6, 'Rolamento para volantte', 'Diâmetro 30mm', 6, 10, 2, 'ITC-010-01', NULL, 9, 2, 'un', 'Jackson Queiroz', '2025-04-11 18:08:16', NULL),
(7, 'Volante fundido 300mm', 'Volante ferro fundido conforme modelo', 2, 10, 1, 'ITC-010-02', NULL, 1, 2, 'un', 'Jackson Queiroz', '2025-04-11 18:58:14', NULL),
(8, 'Tubo Superior 250mm', '1500X100', 3, 1, 1, 'ITC-001-06', NULL, 10, 2, 'un', 'Jackson Queiroz', '2025-04-11 19:09:18', NULL),
(9, 'Lateral Base 250mm', 'Dimenssões 300X200X200mm', 3, 2, 2, 'ITC-002-01', NULL, 2, 4, 'un', 'Jackson Queiroz', '2025-04-11 19:12:03', NULL),
(10, 'Bandeja 250mm', 'Bandeja mesa dimenssões 1000X400', 3, 2, 2, 'ITC-002-02', NULL, 5, 3, 'un', 'Jackson Queiroz', '2025-04-11 19:13:32', NULL),
(11, 'Caixa Eletrica', 'Dimenssões 200X200X100', 3, 3, 1, 'ITC-003-01', NULL, 1, NULL, 'un', 'Jackson Queiroz', '2025-04-11 19:15:21', NULL),
(12, 'Tampa caixa elétrica', 'Dimenssões 100X100', 1, 3, 1, 'ITC-003-02', NULL, 2, NULL, 'un', 'Jackson Queiroz', '2025-04-11 19:16:33', NULL),
(13, 'Chapa Dobrada Porta', 'Porta do Basculante', 3, 5, 2, 'ITC-005-01', NULL, 1, NULL, 'un', 'Jackson Queiroz', '2025-04-11 19:40:19', NULL),
(14, 'Tubo trefilado 25mm', 'Tubo cortado 25mm', 5, 11, 2, 'ITC-011-01', NULL, 1, NULL, 'un', 'Jackson Queiroz', '2025-04-11 19:42:45', NULL),
(15, 'Braço Direito Fundido', 'Fundido conforme modelo', 2, 8, 2, 'ITC-008-01', NULL, 1, NULL, 'un', 'Jackson Queiroz', '2025-04-11 20:02:44', NULL),
(16, 'Braço Esquerdo 250mm', 'Fundio conforme modelo', 2, 7, 2, 'ITC-007-01', NULL, 1, NULL, 'un', 'Jackson Queiroz', '2025-04-11 20:03:17', NULL),
(17, 'Suporte rolamento fundido 250mm', 'Fundido para suporte', 2, 9, 2, 'ITC-009-01', NULL, 0, NULL, 'un', 'Jackson Queiroz', '2025-04-11 20:04:59', NULL),
(18, 'Contator trifásico', 'Trifásico 220 volts', 7, 6, 2, 'ITC-006-01', NULL, 1, NULL, 'un', 'Jackson Queiroz', '2025-04-11 21:29:55', NULL),
(19, 'Fio PP3', 'Fio 1,5 mm', 8, 6, 5, 'ITC-006-02', NULL, NULL, NULL, 'un', 'Jackson Queiroz', '2025-04-11 21:33:40', NULL),
(20, 'Fim de Curso', 'Fim de curso', 7, 6, 2, 'ITC-006-03', NULL, NULL, NULL, 'un', 'Jackson Queiroz', '2025-04-11 21:34:47', NULL),
(21, 'Tomada trifásico', 'Tomada entrada trifásico', 7, 6, 1, 'ITC-006-04', NULL, NULL, NULL, 'un', 'Jackson Queiroz', '2025-04-11 21:36:24', NULL),
(22, 'Botão Liga /Desliga', 'Liga desliga com led', 7, 6, 1, 'ITC-006-05', NULL, NULL, NULL, 'un', 'Jackson Queiroz', '2025-04-11 21:37:37', NULL),
(23, 'Caixa Eletrica Pintada', 'Caixa elétrica para montagem de componentes', 3, 6, 1, 'ITC-006-06', NULL, NULL, NULL, 'un', 'Jackson Queiroz', '2025-04-11 21:49:13', NULL),
(24, 'Tampa caixa elétrica pintada', 'Tampa furada para montagem de componentes', 1, 12, 1, 'ITC-012-01', NULL, NULL, NULL, 'un', 'Jackson Queiroz', '2025-04-11 21:53:50', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `local_itens`
--

CREATE TABLE `local_itens` (
  `id` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `local` varchar(220) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'ocupado',
  `created` date DEFAULT current_timestamp(),
  `modifield` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `local_itens`
--

INSERT INTO `local_itens` (`id`, `id_item`, `local`, `status`, `created`, `modifield`) VALUES
(1, 1, 'A1', 'ocupado', '2025-04-25', NULL),
(2, 2, 'A2', 'ocupado', '2025-04-25', NULL),
(3, 3, 'A3', 'ocupado', '2025-04-25', NULL),
(4, 4, 'A4', 'ocupado', '2025-04-25', NULL),
(5, 5, 'A5', 'ocupado', '2025-04-25', NULL),
(6, 8, 'A6', 'ocupado', '2025-04-25', NULL),
(7, 9, 'B1', 'ocupado', '2025-04-25', NULL),
(8, 10, 'B2', 'ocupado', '2025-04-25', NULL),
(9, 11, 'C1', 'ocupado', '2025-04-25', NULL),
(10, 12, 'C2', 'ocupado', '2025-04-25', NULL),
(11, 15, 'G1', 'ocupado', '2025-04-25', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `material`
--

CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `descricao` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modifield` datetime DEFAULT NULL,
  `usuario` varchar(220) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `material`
--

INSERT INTO `material` (`id`, `nome`, `descricao`, `created`, `modifield`, `usuario`) VALUES
(1, 'Chapa', 'Chapa', '2025-04-10 14:28:53', NULL, 'Jackson Queiroz'),
(2, 'Ferro Fundido', 'Ferro Fundido', '2025-04-10 14:28:53', NULL, 'Administrador'),
(3, 'Chapa Dobrada', 'Chapa Dobrada', '2025-04-10 14:28:53', NULL, 'Jackson Queiroz'),
(4, 'Trefilado 2\" 50 mm', 'SAE 1020 barra', '2025-04-10 15:00:06', NULL, 'Jackson Queiroz'),
(5, 'Trefilado 1\" 25mm', 'Barra trefilado 25mm SAE 1020', '2025-04-10 15:07:59', NULL, 'Jackson Queiroz'),
(6, 'Comercial', 'Itens comprados', '2025-04-11 18:07:19', NULL, 'Jackson Queiroz'),
(7, 'Material elétrico', 'Material condutivo', '2025-04-11 21:28:40', NULL, 'Jackson Queiroz'),
(8, 'Fio PP3', 'Fio PP3 de 1,5 mm', '2025-04-11 21:32:08', NULL, 'Jackson Queiroz');

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacoes_estoque`
--

CREATE TABLE `movimentacoes_estoque` (
  `id` int(11) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `tabela_referencia` enum('item','peca') NOT NULL,
  `referencia_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `data_movimentacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `movimentacoes_estoque`
--

INSERT INTO `movimentacoes_estoque` (`id`, `tipo`, `tabela_referencia`, `referencia_id`, `quantidade`, `descricao`, `data_movimentacao`) VALUES
(1, 'entrada', '', 1, 1, 'Recebimento de Pedido', '2025-04-21 23:32:22'),
(2, 'entrada', '', 1, 2, 'Recebimento de Pedido', '2025-04-21 23:41:47'),
(3, 'entrada', '', 3, 4, 'Recebimento de Pedido', '2025-04-22 01:28:36'),
(4, 'entrada', '', 5, 4, 'Recebimento de Pedido', '2025-04-22 01:28:42'),
(5, 'entrada', '', 6, 4, 'Recebimento de Pedido', '2025-04-22 01:28:45'),
(6, 'entrada', '', 4, 4, 'Recebimento de Pedido', '2025-04-22 01:28:47'),
(7, 'entrada', '', 8, 4, 'Recebimento de Pedido', '2025-04-22 01:28:51'),
(8, 'entrada', '', 1, 2, 'Descrição', '2025-04-22 01:38:04'),
(9, 'entrada', '', 2, 4, 'Recebimento de Pedido', '2025-04-22 01:39:22'),
(10, 'entrada', '', 2, 4, 'Recebimento de Pedido', '2025-04-22 01:39:23'),
(11, 'entrada', '', 3, 4, 'Recebimento de Pedido', '2025-04-22 01:39:25'),
(12, 'entrada', '', 8, 4, 'Recebimento de Pedido', '2025-04-22 01:39:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ordem_producao`
--

CREATE TABLE `ordem_producao` (
  `id` int(11) NOT NULL,
  `id_modelo` int(11) NOT NULL,
  `motivo` text NOT NULL,
  `qtd_itens` int(11) NOT NULL,
  `codigo` varchar(220) NOT NULL,
  `data_inicio` date DEFAULT current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modifield` datetime DEFAULT NULL,
  `usuario` varchar(220) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ordem_producao`
--

INSERT INTO `ordem_producao` (`id`, `id_modelo`, `motivo`, `qtd_itens`, `codigo`, `data_inicio`, `created`, `modifield`, `usuario`) VALUES
(1, 1, 'Produção para compor estoque', 2, '04259089', '2025-04-13', '2025-04-13 21:12:22', NULL, 'Jackson Queiroz');

-- --------------------------------------------------------

--
-- Estrutura para tabela `peca`
--

CREATE TABLE `peca` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `descricao` text NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `id_modelo` int(11) NOT NULL,
  `codigo` varchar(220) DEFAULT NULL,
  `estoque_atual` int(11) DEFAULT NULL,
  `estoque_minimo` int(11) DEFAULT NULL,
  `usuario` varchar(220) DEFAULT NULL,
  `createad` datetime DEFAULT current_timestamp(),
  `modifield` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `peca`
--

INSERT INTO `peca` (`id`, `nome`, `descricao`, `id_grupo`, `id_modelo`, `codigo`, `estoque_atual`, `estoque_minimo`, `usuario`, `createad`, `modifield`) VALUES
(1, 'Basculante', 'Basculante', 1, 1, 'H250-001-01', 0, 0, 'Administrador', '2025-04-04 14:57:37', NULL),
(2, 'Base', 'Base', 1, 1, 'H250-001-02', NULL, NULL, 'Administrador', '2025-04-04 16:11:29', NULL),
(3, 'Caixa elétrica', 'Caixa elétrica', 1, 1, 'H250-001-03', NULL, NULL, 'Administrador', '2025-04-04 16:13:00', NULL),
(5, 'Porta Baculante', 'Porta esquerda do basculante', 1, 1, 'H250-001-04', NULL, NULL, 'Administrador', '2025-04-04 16:48:47', NULL),
(6, 'Painel Elétrico', 'Painel Elétrico', 4, 1, 'H250-004-01', NULL, NULL, 'Administrador', '2025-04-04 16:54:55', NULL),
(7, 'Braço esquerdo', 'Braço esquerdo da fita', 3, 1, 'H250-003-01', NULL, NULL, 'Jackson Queiroz', '2025-04-09 14:27:25', NULL),
(8, 'Braço direito', 'Braço direito da fita', 3, 1, 'H250-003-02', NULL, NULL, 'Jackson Queiroz', '2025-04-09 14:33:50', NULL),
(9, 'Suporte dos rolamentos', 'Suporte dos rolamentos guia.', 3, 1, 'H250-003-03', NULL, NULL, 'Jackson Queiroz', '2025-04-10 08:36:09', NULL),
(10, 'Volante transmissão motor 250mm', 'Dimenssão 300 mm', 2, 1, 'H250-002-01', NULL, NULL, 'Jackson Queiroz', '2025-04-11 17:26:32', NULL),
(11, 'Eixo Central Volante 250mm', 'Eixo diametro 35 mm', 2, 1, 'H250-002-02', NULL, NULL, 'Jackson Queiroz', '2025-04-11 17:28:59', NULL),
(12, 'Tampa caixa elétrica', 'Tampa para caixa elétrica', 1, 1, 'H250-001-05', NULL, NULL, 'Jackson Queiroz', '2025-04-11 21:52:19', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos_compra`
--

CREATE TABLE `pedidos_compra` (
  `id` int(11) NOT NULL,
  `fornecedor_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) DEFAULT NULL,
  `valor_total` decimal(10,2) DEFAULT NULL,
  `status` enum('pendente','recebido') DEFAULT 'pendente',
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_recebimento` timestamp NULL DEFAULT NULL,
  `prazo_entrega` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos_compra`
--

INSERT INTO `pedidos_compra` (`id`, `fornecedor_id`, `item_id`, `quantidade`, `preco_unitario`, `valor_total`, `status`, `data_pedido`, `data_recebimento`, `prazo_entrega`) VALUES
(1, 1, 9, 1, 50.00, 50.00, 'recebido', '2025-04-24 18:17:53', '2025-04-24 23:04:13', NULL),
(2, 1, 1, 1, 50.00, 50.00, 'recebido', '2025-04-24 18:17:53', '2025-04-25 01:23:08', NULL),
(3, 1, 2, 1, 50.00, 50.00, 'recebido', '2025-04-24 18:17:53', '2025-04-25 01:58:09', NULL),
(4, 1, 12, 1, 50.00, 50.00, 'recebido', '2025-04-24 18:17:53', '2025-04-25 01:59:11', NULL),
(5, 3, 18, 1, 150.00, 150.00, 'recebido', '2025-04-24 18:32:37', '2025-04-24 20:11:52', NULL),
(6, 3, 20, 1, 80.00, 80.00, 'recebido', '2025-04-24 18:32:37', '2025-04-24 20:41:35', NULL),
(7, 3, 19, 3, 100.00, 300.00, 'recebido', '2025-04-24 18:32:37', '2025-04-24 21:38:58', NULL),
(8, 1, 5, 3, 85.00, 255.00, 'recebido', '2025-04-24 20:45:29', '2025-04-24 20:45:42', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `descricao` text DEFAULT NULL,
  `dim_retangular` varchar(220) DEFAULT NULL,
  `dim_quadrado` varchar(220) DEFAULT NULL,
  `dim_redondo` varchar(220) DEFAULT NULL,
  `peso` varchar(220) DEFAULT NULL,
  `img` varchar(220) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `dim_retangular`, `dim_quadrado`, `dim_redondo`, `peso`, `img`) VALUES
(1, 'H250', 'Serra Horizontal 250 mmm', '200 x 250', '180 x 250', '250 x 250', '800 kg', NULL),
(2, 'H200', 'Serra de fita Horizontal 200 mm.', '180 x 180', '180 x 200', '200 x 200', '600 kg', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_operacao`
--

CREATE TABLE `tipo_operacao` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `desc_op` text NOT NULL,
  `cod_op` varchar(220) NOT NULL,
  `usuario` varchar(220) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modifield` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipo_operacao`
--

INSERT INTO `tipo_operacao` (`id`, `nome`, `desc_op`, `cod_op`, `usuario`, `created`, `modifield`) VALUES
(1, 'Separar Material', 'Separar itens conforme descritos no documento.', '010', 'Jackson Queiroz', '2025-04-03 19:54:52', '2025-04-04 11:23:02'),
(2, 'Cortar Material', 'Cortar Itens.', '020', 'Jackson Queiroz', '2025-04-03 19:56:53', '2025-04-04 11:25:16'),
(20, 'Solda', 'Solda', '030', 'Jackson Queiroz', '2025-04-04 11:00:16', NULL),
(21, 'Pintura', 'Pintar isolando conforme desenho.', '040', 'Jackson Queiroz', '2025-04-04 11:28:01', NULL),
(26, 'Ajustagem', 'Ajustar peça.', '050', 'Jackson Queiroz', '2025-04-10 17:48:50', NULL),
(27, 'Furadeira', 'Furar conforme desenho.', '060', 'Jackson Queiroz', '2025-04-11 21:56:28', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `usuario` varchar(220) NOT NULL,
  `senha` varchar(220) NOT NULL,
  `imagem` varchar(220) NOT NULL,
  `tipo` enum('admin','usuario') DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `senha`, `imagem`, `tipo`) VALUES
(1, 'Jackson Queiroz', 'jackson.oqueiroz@gmail.com', '$2y$10$f.LrBA08y8t9.ZpVVJVSZOGYWwflwYVBFZ94m5o3DuW/FzAWE9Mle', 'semfoto.png', 'admin'),
(2, 'Administrador', 'administrador@gmail.com', '$2y$10$q3pHMUz.oBmqgLj44IGOAOM.9cBPedqzzDig683.wq..NNkMzRWey', 'semfoto.png', 'admin'),
(3, 'Usuario', 'usuario@gmail.com', '$2y$10$y2tQcIwMgZ.o9S7v8E3yE.gliMjmaMTQzfgrfHXsJNMICkHQ4lJw.', '', 'usuario'),
(4, 'Produção', 'producao@gmail.com', '$2y$10$/agSLff1dviPdXTltLuTq.HkFJX28HfM4NRTviqfAuBAhV2cZRvdO', '', 'usuario');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `encerra_operacao`
--
ALTER TABLE `encerra_operacao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fornecedor_itens`
--
ALTER TABLE `fornecedor_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fornecedor_id` (`fornecedor_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Índices de tabela `fornecedor_materiais`
--
ALTER TABLE `fornecedor_materiais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fornecedor_id` (`fornecedor_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Índices de tabela `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `inserir_op`
--
ALTER TABLE `inserir_op`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `inserir_ordem`
--
ALTER TABLE `inserir_ordem`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `itens`
--
ALTER TABLE `itens`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `local_itens`
--
ALTER TABLE `local_itens`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ordem_producao`
--
ALTER TABLE `ordem_producao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `peca`
--
ALTER TABLE `peca`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pedidos_compra`
--
ALTER TABLE `pedidos_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tipo_operacao`
--
ALTER TABLE `tipo_operacao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `encerra_operacao`
--
ALTER TABLE `encerra_operacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `fornecedor_itens`
--
ALTER TABLE `fornecedor_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fornecedor_materiais`
--
ALTER TABLE `fornecedor_materiais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de tabela `inserir_op`
--
ALTER TABLE `inserir_op`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `inserir_ordem`
--
ALTER TABLE `inserir_ordem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `local_itens`
--
ALTER TABLE `local_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `ordem_producao`
--
ALTER TABLE `ordem_producao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `peca`
--
ALTER TABLE `peca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `pedidos_compra`
--
ALTER TABLE `pedidos_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tipo_operacao`
--
ALTER TABLE `tipo_operacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `fornecedor_itens`
--
ALTER TABLE `fornecedor_itens`
  ADD CONSTRAINT `fornecedor_itens_ibfk_1` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`),
  ADD CONSTRAINT `fornecedor_itens_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `itens` (`id`);

--
-- Restrições para tabelas `fornecedor_materiais`
--
ALTER TABLE `fornecedor_materiais`
  ADD CONSTRAINT `fornecedor_materiais_ibfk_1` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`),
  ADD CONSTRAINT `fornecedor_materiais_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `material` (`id`);

--
-- Restrições para tabelas `pedidos_compra`
--
ALTER TABLE `pedidos_compra`
  ADD CONSTRAINT `pedidos_compra_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `itens` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
