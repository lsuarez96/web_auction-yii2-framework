/*
 Navicat Premium Data Transfer

 Source Server         : mysql_connection
 Source Server Type    : MySQL
 Source Server Version : 50719
 Source Host           : localhost:3306
 Source Schema         : subasta_new

 Target Server Type    : MySQL
 Target Server Version : 50719
 File Encoding         : 65001

 Date: 10/03/2018 12:01:45
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment`  (
  `item_name` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_id` int(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`, `user_id`) USING BTREE,
  UNIQUE INDEX `item_name`(`item_name`, `user_id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE,
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_assignment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('Usuario', 28, NULL);
INSERT INTO `auth_assignment` VALUES ('Usuario', 29, NULL);

-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item`  (
  `name` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `rule_name` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `data` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`) USING BTREE,
  INDEX `rule_name`(`rule_name`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('Administrador', 0, 'Administra las cuentas', 'admin', NULL, NULL, NULL);
INSERT INTO `auth_item` VALUES ('Usuario', 0, 'Usuario comun', 'user', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child`  (
  `parent` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `child` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`parent`, `child`) USING BTREE,
  INDEX `child`(`child`) USING BTREE,
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule`  (
  `name` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `data` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------
INSERT INTO `auth_rule` VALUES ('admin', 'puede administrar', NULL, NULL);
INSERT INTO `auth_rule` VALUES ('user', 'puede usar', NULL, NULL);

-- ----------------------------
-- Table structure for comentario
-- ----------------------------
DROP TABLE IF EXISTS `comentario`;
CREATE TABLE `comentario`  (
  `id_comentario` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `comentario` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id_comentario`) USING BTREE,
  INDEX `id_producto`(`id_producto`) USING BTREE,
  INDEX `id_usuario`(`id_usuario`) USING BTREE,
  CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for deshabilitado
-- ----------------------------
DROP TABLE IF EXISTS `deshabilitado`;
CREATE TABLE `deshabilitado`  (
  `id_deshabilitado` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` int(11) NOT NULL,
  `tiempo` datetime(0) NOT NULL,
  `razon` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_deshabilitado`) USING BTREE,
  INDEX `usuario`(`usuario`) USING BTREE,
  CONSTRAINT `deshabilitado_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for foto
-- ----------------------------
DROP TABLE IF EXISTS `foto`;
CREATE TABLE `foto`  (
  `id_foto` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `producto` int(11) NOT NULL,
  PRIMARY KEY (`id_foto`) USING BTREE,
  INDEX `producto`(`producto`) USING BTREE,
  CONSTRAINT `foto_ibfk_1` FOREIGN KEY (`producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for historial_subasta
-- ----------------------------
DROP TABLE IF EXISTS `historial_subasta`;
CREATE TABLE `historial_subasta`  (
  `id_historial_subasta` int(11) NOT NULL AUTO_INCREMENT,
  `id_subasta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `precio` double NOT NULL,
  PRIMARY KEY (`id_historial_subasta`) USING BTREE,
  UNIQUE INDEX `id_subasta`(`id_subasta`, `precio`) USING BTREE,
  INDEX `id_usuario`(`id_usuario`) USING BTREE,
  CONSTRAINT `historial_subasta_ibfk_1` FOREIGN KEY (`id_subasta`) REFERENCES `subasta` (`id_subasta`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `historial_subasta_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for notificaciones
-- ----------------------------
DROP TABLE IF EXISTS `notificaciones`;
CREATE TABLE `notificaciones`  (
  `id_notificaciones` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `nota` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nuevo` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id_notificaciones`) USING BTREE,
  INDEX `usuario_id`(`usuario_id`) USING BTREE,
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for pais
-- ----------------------------
DROP TABLE IF EXISTS `pais`;
CREATE TABLE `pais`  (
  `id_pais` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_pais` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_pais`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 215 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pais
-- ----------------------------
INSERT INTO `pais` VALUES (1, 'Afganistán');
INSERT INTO `pais` VALUES (2, 'Albania');
INSERT INTO `pais` VALUES (3, 'Alemania');
INSERT INTO `pais` VALUES (4, 'Algeria');
INSERT INTO `pais` VALUES (5, 'Andorra');
INSERT INTO `pais` VALUES (6, 'Angola');
INSERT INTO `pais` VALUES (7, 'Anguilla');
INSERT INTO `pais` VALUES (8, 'Antigua y Barbuda');
INSERT INTO `pais` VALUES (9, 'Antillas Holandesas');
INSERT INTO `pais` VALUES (10, 'Arabia Saudita');
INSERT INTO `pais` VALUES (11, 'Argentina');
INSERT INTO `pais` VALUES (12, 'Armenia');
INSERT INTO `pais` VALUES (13, 'Aruba');
INSERT INTO `pais` VALUES (14, 'Australia');
INSERT INTO `pais` VALUES (15, 'Austria');
INSERT INTO `pais` VALUES (16, 'Azerbaiyán');
INSERT INTO `pais` VALUES (17, 'Bahamas');
INSERT INTO `pais` VALUES (18, 'Bahrein');
INSERT INTO `pais` VALUES (19, 'Bangladesh');
INSERT INTO `pais` VALUES (20, 'Barbados');
INSERT INTO `pais` VALUES (21, 'Bélgica');
INSERT INTO `pais` VALUES (22, 'Belice');
INSERT INTO `pais` VALUES (23, 'Benín');
INSERT INTO `pais` VALUES (24, 'Bermudas');
INSERT INTO `pais` VALUES (25, 'Bielorrusia');
INSERT INTO `pais` VALUES (26, 'Bolivia');
INSERT INTO `pais` VALUES (27, 'Bosnia y Herzegovina');
INSERT INTO `pais` VALUES (28, 'Botsuana');
INSERT INTO `pais` VALUES (29, 'Brasil');
INSERT INTO `pais` VALUES (30, 'Brunéi');
INSERT INTO `pais` VALUES (31, 'Bulgaria');
INSERT INTO `pais` VALUES (32, 'Burkina Faso');
INSERT INTO `pais` VALUES (33, 'Burundi');
INSERT INTO `pais` VALUES (34, 'Bután');
INSERT INTO `pais` VALUES (35, 'Cabo Verde');
INSERT INTO `pais` VALUES (36, 'Camboya');
INSERT INTO `pais` VALUES (37, 'Camerún');
INSERT INTO `pais` VALUES (38, 'Canadá');
INSERT INTO `pais` VALUES (39, 'Chad');
INSERT INTO `pais` VALUES (40, 'Chile');
INSERT INTO `pais` VALUES (41, 'China');
INSERT INTO `pais` VALUES (42, 'Chipre');
INSERT INTO `pais` VALUES (43, 'Colombia');
INSERT INTO `pais` VALUES (44, 'Comores');
INSERT INTO `pais` VALUES (45, 'Congo (Brazzaville );  ');
INSERT INTO `pais` VALUES (46, 'Congo (Kinshasa );  ');
INSERT INTO `pais` VALUES (47, 'Cook;  Islas');
INSERT INTO `pais` VALUES (48, 'Corea del Norte');
INSERT INTO `pais` VALUES (49, 'Corea del Sur');
INSERT INTO `pais` VALUES (50, 'Costa de Marfil');
INSERT INTO `pais` VALUES (51, 'Costa Rica');
INSERT INTO `pais` VALUES (52, 'Croacia');
INSERT INTO `pais` VALUES (53, 'Cuba');
INSERT INTO `pais` VALUES (54, 'Dinamarca');
INSERT INTO `pais` VALUES (55, 'Djibouti;  Yibuti');
INSERT INTO `pais` VALUES (56, 'Ecuador');
INSERT INTO `pais` VALUES (57, 'Egipto');
INSERT INTO `pais` VALUES (58, 'El Salvador');
INSERT INTO `pais` VALUES (59, 'Emiratos árabes Unidos');
INSERT INTO `pais` VALUES (60, 'Eritrea');
INSERT INTO `pais` VALUES (61, 'Eslovaquia');
INSERT INTO `pais` VALUES (62, 'Eslovenia');
INSERT INTO `pais` VALUES (63, 'España');
INSERT INTO `pais` VALUES (64, 'Estados Unidos');
INSERT INTO `pais` VALUES (65, 'Estonia');
INSERT INTO `pais` VALUES (66, 'Etiopía');
INSERT INTO `pais` VALUES (67, 'Feroe;  Islas');
INSERT INTO `pais` VALUES (68, 'Filipinas');
INSERT INTO `pais` VALUES (69, 'Finlandia');
INSERT INTO `pais` VALUES (70, 'Fiyi');
INSERT INTO `pais` VALUES (71, 'Francia');
INSERT INTO `pais` VALUES (72, 'Gabón');
INSERT INTO `pais` VALUES (73, 'Gambia');
INSERT INTO `pais` VALUES (74, 'Georgia');
INSERT INTO `pais` VALUES (75, 'Ghana');
INSERT INTO `pais` VALUES (76, 'Gibraltar');
INSERT INTO `pais` VALUES (77, 'Granada');
INSERT INTO `pais` VALUES (78, 'Grecia');
INSERT INTO `pais` VALUES (79, 'Groenlandia');
INSERT INTO `pais` VALUES (80, 'Guadalupe');
INSERT INTO `pais` VALUES (81, 'Guatemala');
INSERT INTO `pais` VALUES (82, 'Guernsey');
INSERT INTO `pais` VALUES (83, 'Guinea');
INSERT INTO `pais` VALUES (84, 'Guinea-Bissau');
INSERT INTO `pais` VALUES (85, 'Guinea Ecuatorial');
INSERT INTO `pais` VALUES (86, 'Guyana');
INSERT INTO `pais` VALUES (87, 'Haiti');
INSERT INTO `pais` VALUES (88, 'Honduras');
INSERT INTO `pais` VALUES (89, 'Hong Kong');
INSERT INTO `pais` VALUES (90, 'Hungría');
INSERT INTO `pais` VALUES (91, 'India');
INSERT INTO `pais` VALUES (92, 'Indonesia');
INSERT INTO `pais` VALUES (93, 'Irak');
INSERT INTO `pais` VALUES (94, 'Irán');
INSERT INTO `pais` VALUES (95, 'Irlanda');
INSERT INTO `pais` VALUES (96, 'Isla Pitcairn');
INSERT INTO `pais` VALUES (97, 'Islandia');
INSERT INTO `pais` VALUES (98, 'Islas Salomón');
INSERT INTO `pais` VALUES (99, 'Islas Turcas y Caicos');
INSERT INTO `pais` VALUES (100, 'Islas Virgenes Británicas');
INSERT INTO `pais` VALUES (101, 'Israel');
INSERT INTO `pais` VALUES (102, 'Italia');
INSERT INTO `pais` VALUES (103, 'Jamaica');
INSERT INTO `pais` VALUES (104, 'Japón');
INSERT INTO `pais` VALUES (105, 'Jersey');
INSERT INTO `pais` VALUES (106, 'Jordania');
INSERT INTO `pais` VALUES (107, 'Kazajstán');
INSERT INTO `pais` VALUES (108, 'Kenia');
INSERT INTO `pais` VALUES (109, 'Kirguistán');
INSERT INTO `pais` VALUES (110, 'Kiribati');
INSERT INTO `pais` VALUES (111, 'Kuwait');
INSERT INTO `pais` VALUES (112, 'Laos');
INSERT INTO `pais` VALUES (113, 'Lesotho');
INSERT INTO `pais` VALUES (114, 'Letonia');
INSERT INTO `pais` VALUES (115, 'Líbano');
INSERT INTO `pais` VALUES (116, 'Liberia');
INSERT INTO `pais` VALUES (117, 'Libia');
INSERT INTO `pais` VALUES (118, 'Liechtenstein');
INSERT INTO `pais` VALUES (119, 'Lituania');
INSERT INTO `pais` VALUES (120, 'Luxemburgo');
INSERT INTO `pais` VALUES (121, 'Macedonia');
INSERT INTO `pais` VALUES (122, 'Madagascar');
INSERT INTO `pais` VALUES (123, 'Malasia');
INSERT INTO `pais` VALUES (124, 'Malawi');
INSERT INTO `pais` VALUES (125, 'Maldivas');
INSERT INTO `pais` VALUES (126, 'Malí');
INSERT INTO `pais` VALUES (127, 'Malta');
INSERT INTO `pais` VALUES (128, 'Man;  Isla de');
INSERT INTO `pais` VALUES (129, 'Marruecos');
INSERT INTO `pais` VALUES (130, 'Martinica');
INSERT INTO `pais` VALUES (131, 'Mauricio');
INSERT INTO `pais` VALUES (132, 'Mauritania');
INSERT INTO `pais` VALUES (133, 'México');
INSERT INTO `pais` VALUES (134, 'Moldavia');
INSERT INTO `pais` VALUES (135, 'Mónaco');
INSERT INTO `pais` VALUES (136, 'Mongolia');
INSERT INTO `pais` VALUES (137, 'Mozambique');
INSERT INTO `pais` VALUES (138, 'Myanmar');
INSERT INTO `pais` VALUES (139, 'Namibia');
INSERT INTO `pais` VALUES (140, 'Nauru');
INSERT INTO `pais` VALUES (141, 'Nepal');
INSERT INTO `pais` VALUES (142, 'Nicaragua');
INSERT INTO `pais` VALUES (143, 'Níger');
INSERT INTO `pais` VALUES (144, 'Nigeria');
INSERT INTO `pais` VALUES (145, 'Norfolk Island');
INSERT INTO `pais` VALUES (146, 'Noruega');
INSERT INTO `pais` VALUES (147, 'Nueva Caledonia');
INSERT INTO `pais` VALUES (148, 'Nueva Zelanda');
INSERT INTO `pais` VALUES (149, 'Omán');
INSERT INTO `pais` VALUES (150, 'Países Bajos;  Holanda');
INSERT INTO `pais` VALUES (151, 'Pakistán');
INSERT INTO `pais` VALUES (152, 'Panamá');
INSERT INTO `pais` VALUES (153, 'Papúa-Nueva Guinea');
INSERT INTO `pais` VALUES (154, 'Paraguay');
INSERT INTO `pais` VALUES (155, 'Perú');
INSERT INTO `pais` VALUES (156, 'Polinesia Francesa');
INSERT INTO `pais` VALUES (157, 'Polonia');
INSERT INTO `pais` VALUES (158, 'Portugal');
INSERT INTO `pais` VALUES (159, 'Puerto Rico');
INSERT INTO `pais` VALUES (160, 'Qatar');
INSERT INTO `pais` VALUES (161, 'Reino Unido');
INSERT INTO `pais` VALUES (162, 'República Checa');
INSERT INTO `pais` VALUES (163, 'República Dominicana');
INSERT INTO `pais` VALUES (164, 'Reunión');
INSERT INTO `pais` VALUES (165, 'Ruanda');
INSERT INTO `pais` VALUES (166, 'Rumanía');
INSERT INTO `pais` VALUES (167, 'Rusia');
INSERT INTO `pais` VALUES (168, 'Sáhara Occidental');
INSERT INTO `pais` VALUES (169, 'Samoa');
INSERT INTO `pais` VALUES (170, 'San Cristobal y Nevis');
INSERT INTO `pais` VALUES (171, 'San Marino');
INSERT INTO `pais` VALUES (172, 'San Pedro y Miquelón');
INSERT INTO `pais` VALUES (173, 'San Tomé y Príncipe');
INSERT INTO `pais` VALUES (174, 'San Vincente y Granadinas');
INSERT INTO `pais` VALUES (175, 'Santa Elena');
INSERT INTO `pais` VALUES (176, 'Santa Lucía');
INSERT INTO `pais` VALUES (177, 'Senegal');
INSERT INTO `pais` VALUES (178, 'Serbia y Montenegro');
INSERT INTO `pais` VALUES (179, 'Seychelles');
INSERT INTO `pais` VALUES (180, 'Sierra Leona');
INSERT INTO `pais` VALUES (181, 'Singapur');
INSERT INTO `pais` VALUES (182, 'Siria');
INSERT INTO `pais` VALUES (183, 'Somalia');
INSERT INTO `pais` VALUES (184, 'Sri Lanka');
INSERT INTO `pais` VALUES (185, 'Sudáfrica');
INSERT INTO `pais` VALUES (186, 'Sudán');
INSERT INTO `pais` VALUES (187, 'Suecia');
INSERT INTO `pais` VALUES (188, 'Suiza');
INSERT INTO `pais` VALUES (189, 'Surinam');
INSERT INTO `pais` VALUES (190, 'Swazilandia');
INSERT INTO `pais` VALUES (191, 'Tadjikistan');
INSERT INTO `pais` VALUES (192, 'Tailandia');
INSERT INTO `pais` VALUES (193, 'Taiwan');
INSERT INTO `pais` VALUES (194, 'Tanzania');
INSERT INTO `pais` VALUES (195, 'Timor Oriental');
INSERT INTO `pais` VALUES (196, 'Togo');
INSERT INTO `pais` VALUES (197, 'Tokelau');
INSERT INTO `pais` VALUES (198, 'Tonga');
INSERT INTO `pais` VALUES (199, 'Trinidad y Tobago');
INSERT INTO `pais` VALUES (200, 'Túnez');
INSERT INTO `pais` VALUES (201, 'Turkmenistan');
INSERT INTO `pais` VALUES (202, 'Turquía');
INSERT INTO `pais` VALUES (203, 'Tuvalu');
INSERT INTO `pais` VALUES (204, 'Ucrania');
INSERT INTO `pais` VALUES (205, 'Uganda');
INSERT INTO `pais` VALUES (206, 'Uruguay');
INSERT INTO `pais` VALUES (207, 'Uzbekistán');
INSERT INTO `pais` VALUES (208, 'Vanuatu');
INSERT INTO `pais` VALUES (209, 'Venezuela');
INSERT INTO `pais` VALUES (210, 'Vietnam');
INSERT INTO `pais` VALUES (211, 'Wallis y Futuna');
INSERT INTO `pais` VALUES (212, 'Yemen');
INSERT INTO `pais` VALUES (213, 'Zambia');
INSERT INTO `pais` VALUES (214, 'Zimbabwe');

-- ----------------------------
-- Table structure for producto
-- ----------------------------
DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto`  (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `anuncio` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `descripcion` varchar(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `precio` double NOT NULL,
  `sub_tipo` int(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `fecha_limite` datetime(0) NOT NULL,
  PRIMARY KEY (`id_producto`) USING BTREE,
  INDEX `id_usuario`(`id_usuario`) USING BTREE,
  INDEX `producto_ibfk_1`(`sub_tipo`, `tipo`) USING BTREE,
  CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`sub_tipo`, `tipo`) REFERENCES `sub_tipo` (`id_sub_tipo`, `id_tipo`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for sub_tipo
-- ----------------------------
DROP TABLE IF EXISTS `sub_tipo`;
CREATE TABLE `sub_tipo`  (
  `id_sub_tipo` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo` int(11) NOT NULL,
  `sub_tipo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_sub_tipo`, `id_tipo`) USING BTREE,
  UNIQUE INDEX `id_tipo`(`id_tipo`, `sub_tipo`) USING BTREE,
  CONSTRAINT `sub_tipo_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `tipo` (`id_tipo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of sub_tipo
-- ----------------------------
INSERT INTO `sub_tipo` VALUES (34, 1, 'Cocina');
INSERT INTO `sub_tipo` VALUES (33, 1, 'Limpieza');
INSERT INTO `sub_tipo` VALUES (3, 2, 'Aseo y perfumería');
INSERT INTO `sub_tipo` VALUES (4, 2, 'Casas');
INSERT INTO `sub_tipo` VALUES (1, 2, 'Muebles y decoración');
INSERT INTO `sub_tipo` VALUES (2, 2, 'Para bebes y niños');
INSERT INTO `sub_tipo` VALUES (5, 2, 'Terrenos/Garajes');
INSERT INTO `sub_tipo` VALUES (6, 3, 'Almacenamiento externo');
INSERT INTO `sub_tipo` VALUES (8, 3, 'Componentes de PC');
INSERT INTO `sub_tipo` VALUES (14, 3, 'Computadoras');
INSERT INTO `sub_tipo` VALUES (11, 3, 'Impresoras e insumos');
INSERT INTO `sub_tipo` VALUES (7, 3, 'Memorias USB/Tarjetas');
INSERT INTO `sub_tipo` VALUES (12, 3, 'Protección y Backup');
INSERT INTO `sub_tipo` VALUES (10, 3, 'Redes y Modems');
INSERT INTO `sub_tipo` VALUES (15, 4, 'Celulares');
INSERT INTO `sub_tipo` VALUES (16, 4, 'Piezas/Accesorios');
INSERT INTO `sub_tipo` VALUES (17, 5, 'Cámaras Foto y Videos');
INSERT INTO `sub_tipo` VALUES (19, 5, 'Instrumentos musicales');
INSERT INTO `sub_tipo` VALUES (18, 5, 'TV/Audio/Video');
INSERT INTO `sub_tipo` VALUES (22, 6, 'Camiones/Barcos');
INSERT INTO `sub_tipo` VALUES (20, 6, 'Carros');
INSERT INTO `sub_tipo` VALUES (21, 6, 'Motos/Scooters');
INSERT INTO `sub_tipo` VALUES (23, 6, 'Partes/Piezas');
INSERT INTO `sub_tipo` VALUES (25, 7, 'Accesorios de consolas');
INSERT INTO `sub_tipo` VALUES (24, 7, 'Consolas');
INSERT INTO `sub_tipo` VALUES (27, 8, 'Componentes de portátiles');
INSERT INTO `sub_tipo` VALUES (26, 8, 'Portátiles');
INSERT INTO `sub_tipo` VALUES (28, 8, 'Tables/Ipad');
INSERT INTO `sub_tipo` VALUES (32, 9, 'Otros');
INSERT INTO `sub_tipo` VALUES (29, 9, 'Relojes y joyas');
INSERT INTO `sub_tipo` VALUES (30, 9, 'Ropa y accesorios');

-- ----------------------------
-- Table structure for subasta
-- ----------------------------
DROP TABLE IF EXISTS `subasta`;
CREATE TABLE `subasta`  (
  `id_subasta` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `precio_actual` double NOT NULL,
  `actividad` int(11) NOT NULL DEFAULT 0 COMMENT 'Muestra cuantas veces ha sido actualizada una subasta',
  `terminada` bit(1) NOT NULL DEFAULT b'0' COMMENT '0-No terminada, 1-Terminada',
  `notificada` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id_subasta`) USING BTREE,
  UNIQUE INDEX `id_producto`(`id_producto`) USING BTREE,
  INDEX `id_usuario`(`id_usuario`) USING BTREE,
  CONSTRAINT `subasta_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subasta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for subasta_seguida
-- ----------------------------
DROP TABLE IF EXISTS `subasta_seguida`;
CREATE TABLE `subasta_seguida`  (
  `id_subasta_seguida` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` int(11) NOT NULL,
  `subasta_id` int(11) NOT NULL,
  PRIMARY KEY (`id_subasta_seguida`) USING BTREE,
  UNIQUE INDEX `usuario`(`usuario`, `subasta_id`) USING BTREE,
  INDEX `subasta_id`(`subasta_id`) USING BTREE,
  CONSTRAINT `subasta_seguida_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subasta_seguida_ibfk_2` FOREIGN KEY (`subasta_id`) REFERENCES `subasta` (`id_subasta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tipo
-- ----------------------------
DROP TABLE IF EXISTS `tipo`;
CREATE TABLE `tipo`  (
  `id_tipo` int(11) NOT NULL AUTO_INCREMENT,
  `nom_tipo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id_tipo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tipo
-- ----------------------------
INSERT INTO `tipo` VALUES (1, 'Electrodomésticos');
INSERT INTO `tipo` VALUES (2, 'Viviendas');
INSERT INTO `tipo` VALUES (3, 'Computadoras');
INSERT INTO `tipo` VALUES (4, 'Celulares');
INSERT INTO `tipo` VALUES (5, 'Electrónicos');
INSERT INTO `tipo` VALUES (6, 'Carros');
INSERT INTO `tipo` VALUES (7, 'Consolas');
INSERT INTO `tipo` VALUES (8, 'Portátiles');
INSERT INTO `tipo` VALUES (9, 'Otros');

-- ----------------------------
-- Table structure for usuario
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario`  (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nom_user` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `apellido` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `correo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pais` int(11) DEFAULT NULL,
  `clave` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `borr_log` bit(1) NOT NULL DEFAULT b'0',
  `auth_key` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id_usuario`) USING BTREE,
  UNIQUE INDEX `nom_user`(`nom_user`) USING BTREE,
  UNIQUE INDEX `correo`(`correo`) USING BTREE,
  INDEX `pais`(`pais`) USING BTREE,
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`pais`) REFERENCES `pais` (`id_pais`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of usuario
-- ----------------------------
INSERT INTO `usuario` VALUES (28, 'Admin', 'Administrador', 'Administrador', 'admin@dealer.com', 53, '$2y$13$d003E5L.1fDCbgsOSO5IIeaanXCP8srEHVl1W.rByI2k6whPnsgwS', b'0', '5eg7cdge1782458b3edd5fded4g4ffdb4c3a4gbfedb5f98age2798a57a482a69g86dd1e9acf5269g6c629f29945eb199c87f3dgeb634ea17dc186f5f8g27g3bbf99ef8fdc12a915b9b787a97g6fedfc765145adac36g111d984e16ec98c224d5e5476c72');
INSERT INTO `usuario` VALUES (29, 'lsuarez', 'Luis', 'Suarez', 'luis.suarez@gmail.com', 53, '$2y$13$80Ao9MYKa4bbwphEiSiL5OF6kroPp6oROnHU.jjiZdMJo/qmcOHVe', b'0', 'agg99d94g938g32e17d6fg7a786acb9d98bc9agb86ae7f6dbe8a6bb8g5g95b718c952a9331g28aeefc5cb747bbdge2d3d7753b686ccge967e25dd35264b8ce646a1ged89ec35e4a284b95e6af15815g3ef4e226c7be37ca679gdcd16ffgd6dc26cd84a9f');

-- ----------------------------
-- Function structure for asd
-- ----------------------------
DROP FUNCTION IF EXISTS `asd`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` FUNCTION `asd`() RETURNS int(11)
BEGIN
	DECLARE
		asd INTEGER;

	SELECT tipo.id_tipo INTO asd
  FROM tipo
  WHERE tipo.id_tipo = 1;

	RETURN asd;

END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for asd
-- ----------------------------
DROP PROCEDURE IF EXISTS `asd`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` PROCEDURE `asd`()
BEGIN
DECLARE msg VARCHAR(128);

set msg = 'MyTriggerError: Trying to insert a negative value in trigger_test: ';
        signal sqlstate '45000' set message_text = msg;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for calcular_precio_total
-- ----------------------------
DROP FUNCTION IF EXISTS `calcular_precio_total`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` FUNCTION `calcular_precio_total`(`id_usuario` int) RETURNS double
BEGIN
	DECLARE precio DOUBLE DEFAULT 0;
 
	SELECT SUM(subasta.precio_actual) INTO precio
	FROM usuario INNER JOIN producto ON producto.id_usuario = usuario.id_usuario INNER JOIN subasta ON 
  subasta.id_producto = producto.id_producto
	WHERE usuario.id_usuario = `id_usuario` AND subasta.terminada = 1;

  IF precio IS NULL THEN
		SET precio = 0;
  END IF;

	RETURN precio;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for cambio_puja
-- ----------------------------
DROP PROCEDURE IF EXISTS `cambio_puja`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` PROCEDURE `cambio_puja`(`old_user` int,`prod_id` int, `precio` double)
BEGIN
  DECLARE
    nom_producto VARCHAR(255);
  DECLARE
    noticia VARCHAR(255);

  SELECT producto.anuncio INTO nom_producto
  FROM producto
  WHERE producto.id_producto = prod_id;

  SET noticia = CONCAT("La puja del producto ",nom_producto," ahora presenta un nuevo precio $", precio);

  INSERT INTO notificaciones(`usuario_id`, `nota`)
  VALUES(old_user, noticia);
END
;;
delimiter ;

-- ----------------------------
-- Function structure for cant_productos_por_pais
-- ----------------------------
DROP FUNCTION IF EXISTS `cant_productos_por_pais`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` FUNCTION `cant_productos_por_pais`(`id_producto` int) RETURNS int(11)
BEGIN
	DECLARE cant INTEGER DEFAULT 0;

	SELECT COUNT(producto.id_usuario) INTO cant
	FROM pais INNER JOIN usuario ON usuario.pais = pais.id_pais INNER JOIN 
    producto ON producto.id_usuario = usuario.id_usuario
	WHERE pais.id_pais = `id_producto`;

	RETURN cant;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for cant_subastas_cerradas
-- ----------------------------
DROP FUNCTION IF EXISTS `cant_subastas_cerradas`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` FUNCTION `cant_subastas_cerradas`(`id_usuario` int) RETURNS int(11)
BEGIN
	DECLARE cant INTEGER DEFAULT 0;
 
	SELECT Count(producto.id_usuario) INTO cant
	FROM usuario INNER JOIN producto ON producto.id_usuario = usuario.id_usuario INNER JOIN subasta ON 
  subasta.id_producto = producto.id_producto
	WHERE usuario.id_usuario = `id_usuario` AND subasta.terminada = 1;

	RETURN cant;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for cant_usuarios_por_pais
-- ----------------------------
DROP FUNCTION IF EXISTS `cant_usuarios_por_pais`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` FUNCTION `cant_usuarios_por_pais`(`id_pais` int) RETURNS int(11)
BEGIN 
	DECLARE cant INTEGER DEFAULT 0;

	SELECT COUNT(usuario.id_usuario) INTO cant
	FROM pais INNER JOIN usuario ON usuario.pais = pais.id_pais
	WHERE pais.id_pais = `id_pais`;

	RETURN cant;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for contar_deshabilitados
-- ----------------------------
DROP FUNCTION IF EXISTS `contar_deshabilitados`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` FUNCTION `contar_deshabilitados`(`id_usuario` int) RETURNS int(11)
BEGIN
  DECLARE cant INTEGER DEFAULT 0;
 
	SELECT Count(deshabilitado.usuario) INTO cant
	FROM usuario INNER JOIN deshabilitado ON deshabilitado.usuario = usuario.id_usuario
	WHERE usuario.id_usuario = `id_usuario`;

	RETURN cant;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for contar_productos
-- ----------------------------
DROP FUNCTION IF EXISTS `contar_productos`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` FUNCTION `contar_productos`(`id_usuario` int) RETURNS int(11)
BEGIN
  DECLARE cant INTEGER DEFAULT 0;
 
	SELECT Count(producto.id_usuario) INTO cant
	FROM usuario INNER JOIN producto ON producto.id_usuario = usuario.id_usuario
	WHERE usuario.id_usuario = `id_usuario`;

	RETURN cant;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for ddddd
-- ----------------------------
DROP PROCEDURE IF EXISTS `ddddd`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` PROCEDURE `ddddd`(in_errortext varchar(255))
BEGIN
	SET @sql=CONCAT('UPDATE `', in_errortext, '` SET x=1');
    PREPARE my_signal_stmt FROM @sql;
    EXECUTE my_signal_stmt;
    DEALLOCATE PREPARE my_signal_stmt;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for mirar
-- ----------------------------
DROP PROCEDURE IF EXISTS `mirar`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` PROCEDURE `mirar`(`puja_id` int, `producto_id` int, `precio` double)
BEGIN
  #DECLARE
   # nom_producto VARCHAR(255);

  #SELECT producto.anuncio INTO nom_producto
  #FROM producto
  #WHERE producto.id_producto = `producto_id`;

  #SELECT usuario.id_usuario
  #FROM usuario JOIN subasta_seguida ON usuario.id_usuario = subasta_seguida.id_subasta_seguida
  #WHERE subasta_seguida.subasta_id = `puja`;

	#INSERT INTO notificaciones(`usuario_id`, `nota`)
  #VALUES(,CONCAT("El precio del producto ", nom_producto, "ahora es de $", `precio`))
END
;;
delimiter ;

-- ----------------------------
-- Function structure for qwe
-- ----------------------------
DROP FUNCTION IF EXISTS `qwe`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` FUNCTION `qwe`() RETURNS int(11)
BEGIN
	DECLARE
    user_id INTEGER;
	DECLARE
		user_nom VARCHAR(255);

  SELECT
		usuario.id_usuario INTO user_id       
  FROM
    usuario
  WHERE 
    usuario.id_usuario = 1;

	SELECT
		usuario.nom_user INTO user_nom       
  FROM
    usuario
  WHERE 
    usuario.id_usuario = 1;
	

	RETURN user_id;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for qwe
-- ----------------------------
DROP PROCEDURE IF EXISTS `qwe`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` PROCEDURE `qwe`(`ssd` varchar(255))
BEGIN
	IF UPPER(ssd) LIKE UPPER('asd') THEN
         CALL ddddd('Error: invalid_id_test; Id must be a positive integer');
    ELSE
        INSERT INTO tipo(nom_tipo) VALUES ('asddd');
    END IF;

END
;;
delimiter ;

-- ----------------------------
-- Function structure for reputacion
-- ----------------------------
DROP FUNCTION IF EXISTS `reputacion`;
delimiter ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `reputacion`(`id` int) RETURNS double
BEGIN
	#Routine body goes here...
DECLARE terminadas int;
DECLARE pujadas int;
DECLARE porcientoCompra DOUBLE;
DECLARE compradas int;
DECLARE puestas int;
DECLARE porcientoVenta DOUBLE;
SELECT COUNT(id_subasta) INTO terminadas FROM subasta JOIN usuario on subasta.id_usuario=usuario.id_usuario WHERE subasta.id_usuario=`id`;
SELECT COUNT(subasta_id) INTO pujadas from subasta_seguida JOIN subasta on subasta.id_subasta=subasta_seguida.subasta_id WHERE subasta.terminada=true AND subasta.id_usuario!=`id`;
Select COUNT(subasta.id_subasta) into compradas from subasta join producto on subasta.id_producto=producto.id_producto WHERE producto.id_usuario= id and subasta.terminada=true and subasta.id_usuario!=`id`;
SELECT COUNT(producto.id_producto) INTO puestas from producto WHERE producto.fecha_limite<NOW() and producto.id_usuario=`id`;
Select(terminadas/pujadas)*100 INTO porcientoCompra;
SELECT(compradas/puestas)*100 INTO porcientoVenta; 	
RETURN (porcientoCompra+porcientoVenta)/2;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for seguir_automatico
-- ----------------------------
DROP PROCEDURE IF EXISTS `seguir_automatico`;
delimiter ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `seguir_automatico`(`user_id` int,`subasta_id` int)
BEGIN
  DECLARE
		subasta_seguida_id INTEGER DEFAULT -1;

	SELECT subasta_seguida.id_subasta_seguida INTO subasta_seguida_id
	FROM subasta_seguida
	WHERE subasta_seguida.subasta_id = `subasta_id` AND subasta_seguida.usuario = `user_id`;

	IF subasta_seguida_id = -1 THEN
		INSERT INTO subasta_seguida(`usuario`,`subasta_id`)
		VALUES(`user_id`,`subasta_id`);
	END IF;
	
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for subasta_seguida_cambiada
-- ----------------------------
DROP PROCEDURE IF EXISTS `subasta_seguida_cambiada`;
delimiter ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `subasta_seguida_cambiada`(`puja_id` int, `producto_id` int, `precio` double)
BEGIN
  DECLARE
    nom_producto VARCHAR(255);
	DECLARE
    user_actual INTEGER(11);
  DECLARE
    parar SMALLINT DEFAULT 0;

  DECLARE cur_usuarios CURSOR FOR
    SELECT subasta_seguida.usuario
		FROM subasta_seguida
		WHERE subasta_seguida.subasta_id = `puja_id`;

	DECLARE CONTINUE HANDLER FOR
    SQLSTATE '02000'
		SET parar = 1;		

	SELECT producto.anuncio INTO nom_producto
  FROM producto
  WHERE producto.id_producto = `producto_id`;
  
	OPEN cur_usuarios;
    RECORRE: LOOP		
			FETCH cur_usuarios INTO user_actual;	

			IF parar = 1 THEN
				LEAVE RECORRE;
			END IF;

      
    END LOOP;
  CLOSE cur_usuarios;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for test
-- ----------------------------
DROP PROCEDURE IF EXISTS `test`;
delimiter ;;
CREATE DEFINER=`Alejandro`@`localhost` PROCEDURE `test`()
BEGIN
  DECLARE count INTEGER DEFAULT 0;

	WHILE count < 2 DO
		INSERT INTO `comentario` (`id_usuario`, `id_producto`, `comentario`) 
    VALUES ('1', '1', CONCAT('Este es el valor de count ',RAND()));

    SET count = count + 1;
	END WHILE;

END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table producto
-- ----------------------------
DROP TRIGGER IF EXISTS `insert_producto_subasta_ai`;
delimiter ;;
CREATE DEFINER = `root`@`localhost` TRIGGER `insert_producto_subasta_ai` AFTER INSERT ON `producto` FOR EACH ROW BEGIN
    INSERT INTO subasta (`id_producto`, `precio_actual`) 
    VALUES (NEW.id_producto, NEW.precio);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table subasta
-- ----------------------------
DROP TRIGGER IF EXISTS `auto_seguir_ai`;
delimiter ;;
CREATE DEFINER = `root`@`localhost` TRIGGER `auto_seguir_ai` BEFORE UPDATE ON `subasta` FOR EACH ROW BEGIN
  IF NEW.id_usuario IS NOT NULL THEN
       CALL seguir_automatico(NEW.id_usuario, OLD.id_subasta);
           
  END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table subasta
-- ----------------------------
DROP TRIGGER IF EXISTS `insertar_historial`;
delimiter ;;
CREATE DEFINER = `root`@`localhost` TRIGGER `insertar_historial` AFTER UPDATE ON `subasta` FOR EACH ROW BEGIN
     IF OLD.id_usuario IS NOT NULL AND OLD.precio_actual < NEW.Precio_actual THEN
          INSERT INTO historial_subasta(`id_subasta`, `id_usuario`, `precio`)
          VALUES(OLD.id_subasta,OLD.id_usuario,OLD.precio_actual);
     END IF;
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
