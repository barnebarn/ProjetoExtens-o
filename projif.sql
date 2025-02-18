-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/02/2025 às 18:07
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `projif`
--

DELIMITER $$
--
-- Funções
--
CREATE DEFINER=`root`@`localhost` FUNCTION `contar_projetos_ativos` (`usuario_id` INT) RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE qtd INT;
    SELECT COUNT(*) INTO qtd FROM projetos WHERE usuario_id = usuario_id AND status = 'A';
    RETURN qtd;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `is_cargo_superior` (`cargo_id` INT) RETURNS TINYINT(1) DETERMINISTIC BEGIN
    DECLARE nivel INT;
    SELECT nivel INTO nivel
    FROM cargos
    WHERE id = cargo_id;
    RETURN nivel = 3;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `obter_nivel_cargo` (`cargo_id` INT) RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE nivel INT;
    SELECT nivel INTO nivel
    FROM cargos
    WHERE id = cargo_id;
    RETURN nivel;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `obter_nome_cargo` (`usuario_id` INT) RETURNS VARCHAR(50) CHARSET utf8mb4 COLLATE utf8mb4_general_ci DETERMINISTIC BEGIN
    DECLARE cargo_nome VARCHAR(50);
    SELECT c.nome INTO cargo_nome
    FROM usuarios u
    JOIN cargos c ON u.cargo_id = c.id
    WHERE u.id = usuario_id;
    RETURN cargo_nome;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `obter_nome_cargo_por_id` (`cargo_id` INT) RETURNS VARCHAR(50) CHARSET utf8mb4 COLLATE utf8mb4_general_ci DETERMINISTIC BEGIN
    DECLARE nome_cargo VARCHAR(50);
    SELECT nome INTO nome_cargo
    FROM cargos
    WHERE id = cargo_id;
    RETURN nome_cargo;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `tem_participantes_aprovados` (`projeto_id` INT) RETURNS TINYINT(1) DETERMINISTIC BEGIN
    DECLARE aprovados INT;
    SELECT COUNT(*) INTO aprovados
    FROM projeto_participantes
    WHERE projeto_id = projeto_id AND status = 'aprovado';
    RETURN aprovados > 0;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargos`
--

CREATE TABLE `cargos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `nivel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cargos`
--

INSERT INTO `cargos` (`id`, `nome`, `nivel`) VALUES
(1, 'Administrador', 3),
(2, 'Gerente', 2),
(3, 'Usuário', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `projetos`
--

CREATE TABLE `projetos` (
  `id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `texto` text NOT NULL,
  `data_inicio` date DEFAULT NULL,
  `status` varchar(1) NOT NULL,
  `usuario_id` int(255) NOT NULL,
  `Tecnologias` varchar(255) NOT NULL,
  `banner` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `projetos`
--

INSERT INTO `projetos` (`id`, `title`, `description`, `texto`, `data_inicio`, `status`, `usuario_id`, `Tecnologias`, `banner`) VALUES
(1, 'InfoSports', 'SIte de noticias dos esportes do ifro', 'Site de noticia do IFRO', '2025-02-17', 'A', 1, 'Site de Noticia', NULL),
(2, 'Projif', 'Site para gerenciamento de Projeto de extensão', 'Site para Gerenciar Projetos de Extensão', '2025-02-17', 'A', 1, 'PHP, MySQL', 'uploads/banners/67b229efa7b79_campus.jpeg'),
(3, 'Nappa Suplementos TM', 'Loja de Suplementos', 'Criar uma loja para vender suplementos e outros produtos referentes a alimentação para academia', '2025-02-17', 'A', 1, 'PHP', 'uploads/banners/67b37cb10ce5e_images.jpg'),
(4, 'Projeto Gerente', 'Projeto Teste de Gerente', 'Testar funcionalidade', '2025-02-17', 'A', 1, 'Nenhum', NULL),
(39, 'Residuos solidos', 'Projeto sobre Reciclagem e cuidado do meio ambiente', 'Projeto sobre Recivlagem e cuidado do meio ambiente', '2025-02-18', 'P', 24, 'Instagram para divulgação', 'uploads/banners/67b4799351324_images (1).jpg'),
(40, 'musicfy.org', 'Um site de estudos com base em musica', 'Musicas', '2025-02-18', 'A', 1, 'Html', 'uploads/banners/67b4b2ad78526_472038941_918558443590850_537648824724304013_n.png');

--
-- Acionadores `projetos`
--
DELIMITER $$
CREATE TRIGGER `before_insert_projeto` BEFORE INSERT ON `projetos` FOR EACH ROW BEGIN
    -- Define a data de criação automaticamente no formato YYYY-MM-DD
    SET NEW.data_inicio = CURDATE();
    
    IF NEW.usuario_id IS NULL THEN
        SET NEW.usuario_id = (SELECT id FROM usuarios WHERE id = @session_user);
     END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevenir_titulo_duplicado` BEFORE INSERT ON `projetos` FOR EACH ROW BEGIN
    DECLARE qtd INT;
    -- Verifica se já existe um projeto com o mesmo título
    SELECT COUNT(*) INTO qtd
    FROM projetos
    WHERE title = NEW.title;
    
    IF qtd > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Já existe um projeto com esse título.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_status_ativo` BEFORE INSERT ON `projetos` FOR EACH ROW BEGIN
	if new.status is null then
    	SET NEW.status = 'A';
    end if;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `projeto_participantes`
--

CREATE TABLE `projeto_participantes` (
  `id` int(11) NOT NULL,
  `projeto_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `status` enum('pendente','aprovado') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `projeto_participantes`
--

INSERT INTO `projeto_participantes` (`id`, `projeto_id`, `usuario_id`, `status`) VALUES
(4, 2, 4, 'pendente'),
(5, 3, 23, 'pendente'),
(6, 39, 4, 'aprovado'),
(7, 40, 1, 'pendente');

--
-- Acionadores `projeto_participantes`
--
DELIMITER $$
CREATE TRIGGER `definir_status_padrao` BEFORE INSERT ON `projeto_participantes` FOR EACH ROW BEGIN
    IF NEW.status IS NULL THEN
        SET NEW.status = 'pendente';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorios`
--

CREATE TABLE `relatorios` (
  `id` int(11) NOT NULL,
  `projeto_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `conteudo` text NOT NULL,
  `tipo` enum('relatorio','atividade') NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `relatorios`
--

INSERT INTO `relatorios` (`id`, `projeto_id`, `usuario_id`, `titulo`, `conteudo`, `tipo`, `data_criacao`) VALUES
(6, 2, 4, 'Participar da produção', 'Participem da produção', 'relatorio', '2025-02-17 23:42:55'),
(7, 3, 23, 'Adicionar redes sociais', 'Criar redes sociais nappa', 'atividade', '2025-02-18 01:07:49'),
(8, 3, 23, 'Adicionar redes sociais', 'Criado as redes sociais', 'relatorio', '2025-02-18 01:08:19'),
(9, 39, 4, 'Adicionar redes sociais', 'adicionar redes sociais do projeto', 'relatorio', '2025-02-18 12:15:16'),
(10, 39, 4, 'Adicionar redes sociais', 'Redes sociais criada', 'atividade', '2025-02-18 12:15:32');

--
-- Acionadores `relatorios`
--
DELIMITER $$
CREATE TRIGGER `atualizar_status_projeto` AFTER INSERT ON `relatorios` FOR EACH ROW BEGIN
    DECLARE qtd INT;
    -- Conta os relatórios do tipo 'atividade' do projeto
    SELECT COUNT(*) INTO qtd
    FROM relatorios
    WHERE projeto_id = NEW.projeto_id AND tipo = 'atividade';
    
    -- Se houver pelo menos 1 relatório de atividade, muda o status do projeto
    IF qtd > 0 THEN
        UPDATE projetos
        SET status = 'P'
        WHERE id = NEW.projeto_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cargo_id` int(11) NOT NULL DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `cargo_id`) VALUES
(1, 'Neto', 'netojuarez333@gmail.com', '$2y$10$jjBAwAiLwFPtHdphdYglj.YnHzqSQOC0S73WcZHKbgdmWefs.xyAy', 1),
(2, 'ADMIN', 'ADMIN@root.com', '$2y$10$jjBAwAiLwFPtHdphdYglj.YnHzqSQOC0S73WcZHKbgdmWefs.xyAy', 1),
(3, 'Gerente', 'GERENTE@root.com', '$2y$10$jjBAwAiLwFPtHdphdYglj.YnHzqSQOC0S73WcZHKbgdmWefs.xyAy', 2),
(4, 'Aluno123', 'aluno123@gmail.com', '$2y$10$jjBAwAiLwFPtHdphdYglj.YnHzqSQOC0S73WcZHKbgdmWefs.xyAy', 3),
(5, 'Rafael', 'rafaelTeste@gmail.com', '$2y$10$jjBAwAiLwFPtHdphdYglj.YnHzqSQOC0S73WcZHKbgdmWefs.xyAy', 2),
(6, 'nomeTeste', 'emailTeste@gmail.com', '$2y$10$jjBAwAiLwFPtHdphdYglj.YnHzqSQOC0S73WcZHKbgdmWefs.xyAy', 2),
(7, 'TesteSenha', 'teste@gmail.com', '$2y$10$RAQao2At/XdlllQ1SH/4meyjYYCNA5EISq4NKG8Hn6kmVOCzEI/LW', 1),
(23, 'Alan', 'alanTeste@gmail.com', '$2y$10$6qSRHjyrnBpB7AbGiHx6WOddYw5kUa7zWv/GR2xRFOJaq0xU0/IK.', 2),
(24, 'Neemias', 'Neemias123@gmail.com', '$2y$10$GAQZH25Xw2tNzZE2FIm5sOkVMLkvHMybBopu4fwLQSLtmRpTro2Tu', 2);

--
-- Acionadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `definir_cargo_padrao` BEFORE INSERT ON `usuarios` FOR EACH ROW BEGIN
    IF NEW.cargo_id THEN
        SET NEW.cargo_id = 3;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `impedir_exclusao_usuario` BEFORE DELETE ON `usuarios` FOR EACH ROW BEGIN
    DECLARE qtd INT;
    SELECT COUNT(*) INTO qtd FROM projetos WHERE usuario_id = OLD.id AND status = 'A';
    IF qtd > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Não é possível excluir um usuário com projetos ativos.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_cargos_com_usuarios`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_cargos_com_usuarios` (
`id` int(11)
,`nome` varchar(50)
,`qtd_usuarios` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_cargos_superiores`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_cargos_superiores` (
`id` int(11)
,`nome` varchar(50)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_participantes_projetos`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_participantes_projetos` (
`projeto_id` int(11)
,`title` varchar(255)
,`participante` varchar(100)
,`status` enum('pendente','aprovado')
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_projetos_ativos`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_projetos_ativos` (
`id` int(255)
,`title` varchar(255)
,`status` varchar(1)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_projetos_qtde_participantes`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_projetos_qtde_participantes` (
`projeto_id` int(255)
,`title` varchar(255)
,`qtd_participantes` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_projetos_relatorios`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_projetos_relatorios` (
`projeto_id` int(255)
,`title` varchar(255)
,`description` varchar(255)
,`relatorio_titulo` varchar(255)
,`conteudo` text
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_projetos_usuarios`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_projetos_usuarios` (
`id` int(255)
,`title` varchar(255)
,`description` varchar(255)
,`data_inicio` date
,`status` varchar(1)
,`usuario` varchar(100)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_usuarios_superiores`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_usuarios_superiores` (
`id` int(11)
,`nome` varchar(100)
,`cargo_nome` varchar(50)
);

-- --------------------------------------------------------

--
-- Estrutura para view `vw_cargos_com_usuarios`
--
DROP TABLE IF EXISTS `vw_cargos_com_usuarios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_cargos_com_usuarios`  AS SELECT `c`.`id` AS `id`, `c`.`nome` AS `nome`, count(`u`.`id`) AS `qtd_usuarios` FROM (`cargos` `c` left join `usuarios` `u` on(`u`.`cargo_id` = `c`.`id`)) GROUP BY `c`.`id` ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_cargos_superiores`
--
DROP TABLE IF EXISTS `vw_cargos_superiores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_cargos_superiores`  AS SELECT `cargos`.`id` AS `id`, `cargos`.`nome` AS `nome` FROM `cargos` WHERE `cargos`.`nivel` = 3 ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_participantes_projetos`
--
DROP TABLE IF EXISTS `vw_participantes_projetos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_participantes_projetos`  AS SELECT `pp`.`projeto_id` AS `projeto_id`, `p`.`title` AS `title`, `u`.`nome` AS `participante`, `pp`.`status` AS `status` FROM ((`projeto_participantes` `pp` join `projetos` `p` on(`pp`.`projeto_id` = `p`.`id`)) join `usuarios` `u` on(`pp`.`usuario_id` = `u`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_projetos_ativos`
--
DROP TABLE IF EXISTS `vw_projetos_ativos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_projetos_ativos`  AS SELECT `projetos`.`id` AS `id`, `projetos`.`title` AS `title`, `projetos`.`status` AS `status` FROM `projetos` WHERE `projetos`.`status` = 'A' ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_projetos_qtde_participantes`
--
DROP TABLE IF EXISTS `vw_projetos_qtde_participantes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_projetos_qtde_participantes`  AS SELECT `p`.`id` AS `projeto_id`, `p`.`title` AS `title`, count(`pp`.`usuario_id`) AS `qtd_participantes` FROM (`projetos` `p` left join `projeto_participantes` `pp` on(`p`.`id` = `pp`.`projeto_id` and `pp`.`status` = 'aprovado')) GROUP BY `p`.`id` ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_projetos_relatorios`
--
DROP TABLE IF EXISTS `vw_projetos_relatorios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_projetos_relatorios`  AS SELECT `p`.`id` AS `projeto_id`, `p`.`title` AS `title`, `p`.`description` AS `description`, `r`.`titulo` AS `relatorio_titulo`, `r`.`conteudo` AS `conteudo` FROM (`projetos` `p` left join `relatorios` `r` on(`p`.`id` = `r`.`projeto_id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_projetos_usuarios`
--
DROP TABLE IF EXISTS `vw_projetos_usuarios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_projetos_usuarios`  AS SELECT `p`.`id` AS `id`, `p`.`title` AS `title`, `p`.`description` AS `description`, `p`.`data_inicio` AS `data_inicio`, `p`.`status` AS `status`, `u`.`nome` AS `usuario` FROM (`projetos` `p` join `usuarios` `u` on(`p`.`usuario_id` = `u`.`id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_usuarios_superiores`
--
DROP TABLE IF EXISTS `vw_usuarios_superiores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_usuarios_superiores`  AS SELECT `u`.`id` AS `id`, `u`.`nome` AS `nome`, `c`.`nome` AS `cargo_nome` FROM (`usuarios` `u` join `cargos` `c` on(`u`.`cargo_id` = `c`.`id`)) WHERE `c`.`nivel` = 3 ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `projetos`
--
ALTER TABLE `projetos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `projeto_participantes`
--
ALTER TABLE `projeto_participantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projeto_id` (`projeto_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `relatorios`
--
ALTER TABLE `relatorios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projeto_id` (`projeto_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `cargo_id` (`cargo_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `projetos`
--
ALTER TABLE `projetos`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `projeto_participantes`
--
ALTER TABLE `projeto_participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `relatorios`
--
ALTER TABLE `relatorios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `projeto_participantes`
--
ALTER TABLE `projeto_participantes`
  ADD CONSTRAINT `projeto_participantes_ibfk_1` FOREIGN KEY (`projeto_id`) REFERENCES `projetos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projeto_participantes_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `relatorios`
--
ALTER TABLE `relatorios`
  ADD CONSTRAINT `relatorios_ibfk_1` FOREIGN KEY (`projeto_id`) REFERENCES `projetos` (`id`),
  ADD CONSTRAINT `relatorios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
