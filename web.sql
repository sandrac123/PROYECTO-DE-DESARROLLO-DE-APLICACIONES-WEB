-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-05-2025 a las 13:59:46
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `web`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enquisas`
--

CREATE TABLE `enquisas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `activa` enum('sí','no') DEFAULT 'sí'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opcions`
--

CREATE TABLE `opcions` (
  `id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `texto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `enquisa_id` int(11) NOT NULL,
  `texto` varchar(100) NOT NULL,
  `tipo` enum('abierta','opciones','escala') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respostas`
--

CREATE TABLE `respostas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `opcion_id` int(11) DEFAULT NULL,
  `texto_resposta` varchar(255) DEFAULT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contrasinal` varchar(60) DEFAULT NULL,
  `rol` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `enquisas`
--
ALTER TABLE `enquisas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `opcions`
--
ALTER TABLE `opcions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pregunta_id` (`pregunta_id`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enquisa_id` (`enquisa_id`);

--
-- Indices de la tabla `respostas`
--
ALTER TABLE `respostas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `pregunta_id` (`pregunta_id`),
  ADD KEY `opcion_id` (`opcion_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `enquisas`
--
ALTER TABLE `enquisas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opcions`
--
ALTER TABLE `opcions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respostas`
--
ALTER TABLE `respostas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `opcions`
--
ALTER TABLE `opcions`
  ADD CONSTRAINT `opcions_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`);

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`enquisa_id`) REFERENCES `enquisas` (`id`);

--
-- Filtros para la tabla `respostas`
--
ALTER TABLE `respostas`
  ADD CONSTRAINT `respostas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `respostas_ibfk_2` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`),
  ADD CONSTRAINT `respostas_ibfk_3` FOREIGN KEY (`opcion_id`) REFERENCES `opcions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



INSERT INTO enquisas (titulo, descripcion, activa) VALUES
('Encuesta de Hábitos de Consumo', 'Encuesta para conocer los hábitos de consumo de los usuarios', 'sí');



INSERT INTO preguntas (enquisa_id, texto, tipo) VALUES
(1, '¿Con qué frecuencia realizas tus compras?', 'abierta'),         -- id = 1
(1, '¿Dónde compra la mayoría de los productos que utiliza?', 'opciones'), -- id = 2
(1, '¿Le recomendaría nuestro producto/servicios a otros clientes?', 'opciones'), -- id = 3
(1, '¿Cómo calificarías la atención recibida?', 'opciones'),         -- id = 4
(1, '¿Qué métodos de pago prefiere?', 'opciones'),                   -- id = 5
(1, '¿Qué factores influyen en sus decisiones de compra?', 'opciones'), -- id = 6
(1, '¿Sabías qué productos o servicios estás consumiendo y por qué los eliges?', 'abierta'), -- id = 7
(1, '¿Cómo calificarías la presentación del producto o servicio?', 'opciones'), -- id = 8
(1, '¿Cómo fue tu relación con otros consumidores o usuarios?', 'opciones'), -- id = 9
(1, '¿Tienes algún comentario adicional?', 'abierta');               -- id = 10


INSERT INTO opcions (pregunta_id, texto) VALUES
(2, 'En tiendas oficiales'),
(2, 'Online'),
(2, 'Supermercados');

INSERT INTO opcions (pregunta_id, texto) VALUES
(3, 'Sí'),
(3, 'No'),
(3, 'No sé');


INSERT INTO opcions (pregunta_id, texto) VALUES
(4, '1'),
(4, '2'),
(4, '3'),
(4, '4'),
(4, '5');

INSERT INTO opcions (pregunta_id, texto) VALUES
(5, 'Tarjeta de crédito'),
(5, 'Transferencia bancaria'),
(5, 'PayPal'),
(5, 'Efectivo');

-- Pregunta 6
INSERT INTO opcions (pregunta_id, texto) VALUES
(6, 'Precio'),
(6, 'Calidad'),
(6, 'Recomendaciones'),
(6, 'Marca');

-- Pregunta 8
INSERT INTO opcions (pregunta_id, texto) VALUES
(8, 'mui mala'),
(8, 'mala'),
(8, 'regular'),
(8, 'ben'),
(8, 'excelente');

-- Pregunta 9
INSERT INTO opcions (pregunta_id, texto) VALUES
(9, 'mui mala'),
(9, 'mala'),
(9, 'regular'),
(9, 'ben'),
(9, 'excelente');
