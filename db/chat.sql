-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-11-2024 a las 01:43:36
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
-- Base de datos: `chat`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `groups`
--

INSERT INTO `groups` (`id`, `group_name`) VALUES
(1, 'FRONTEND'),
(2, 'BACKEND'),
(3, 'DISEÑADORES UX/UI'),
(4, 'ANALISTAS DE DATOS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `username`, `message`, `created_at`, `group_id`) VALUES
(1, 1, 'Montse', 'hola', '2024-11-02 21:55:07', NULL),
(2, 1, 'Montse', 'holaa', '2024-11-02 22:48:49', NULL),
(3, 1, 'Montse', 'hola', '2024-11-05 00:20:49', NULL),
(4, 1, 'Montse', 'HOLA', '2024-11-05 17:19:03', NULL),
(5, 2, 'Carmen', 'hola', '2024-11-05 17:21:09', NULL),
(6, 2, 'Carmen', 'hola', '2024-11-05 21:47:16', NULL),
(7, 2, 'Carmen', 'hey', '2024-11-05 21:47:23', NULL),
(8, 2, 'Carmen', 'hola', '2024-11-05 21:47:29', NULL),
(9, 2, 'Carmen', 'hola', '2024-11-05 21:47:35', NULL),
(10, 2, 'Carmen', 'hola', '2024-11-05 21:47:39', NULL),
(11, 2, 'Carmen', 'hola', '2024-11-05 21:47:43', NULL),
(12, 2, 'Carmen', 'hola', '2024-11-05 23:07:44', NULL),
(13, 2, 'Carmen', 'hola', '2024-11-05 23:18:26', NULL),
(14, 2, 'Carmen', 'hola', '2024-11-05 23:18:39', NULL),
(15, 2, 'Carmen', 'hols', '2024-11-05 23:58:24', NULL),
(16, 2, 'Carmen', 'hola', '2024-11-05 23:58:27', NULL),
(17, 2, 'Carmen', 'hola', '2024-11-05 23:58:29', NULL),
(18, 2, 'Carmen', 'hola', '2024-11-05 23:58:30', NULL),
(19, 2, 'Carmen', 'hola', '2024-11-05 23:58:31', NULL),
(20, 2, 'Carmen', 'hola', '2024-11-05 23:58:32', NULL),
(21, 2, 'Carmen', 'hola', '2024-11-05 23:58:32', NULL),
(22, 2, 'Carmen', 'hola', '2024-11-05 23:58:32', NULL),
(23, 2, 'Carmen', 'hola', '2024-11-06 00:14:05', 0),
(24, 2, 'Carmen', 'hey', '2024-11-06 00:14:26', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `hobbies` varchar(255) DEFAULT NULL,
  `school` varchar(255) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `profile_pic`, `hobbies`, `school`, `about`, `group_id`) VALUES
(1, 'Montse', '$2y$10$iSrnByav5U47avTZ2Gh6AuuFXkyrVsJf61pWBZcETF.2.9aBMKYgW', 'imagenes_perfil/default.png', 'Escuchar musica', 'Uplc', 'Tengo 20 años', 2),
(2, 'Carmen', '$2y$10$0FnwgKK./m1417Dley3FQ.9NPt1tqfBd5XlZ7xICpz32VsQarVOzu', 'imagenes_perfil/Fotos de perfil de mi niña 3.jpeg', '', '', '', 1),
(3, 'Pablo', '$2y$10$9me4xQwRvhHqbV8NdTU4.eKKehachhqOKRRV1.bFQo.YPWso384qO', 'imagenes_perfil/default.png', NULL, NULL, NULL, NULL),
(4, 'Danixa', '$2y$10$iqLgdOb6IybDtnogiY53J.KsXnhHKGbUAgIE4fF3pyLcof.2ueD3O', 'imagenes_perfil/default.png', NULL, NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
