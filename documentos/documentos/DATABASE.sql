CREATE DATABASE EasyCode;
USE EasyCode;


CREATE TABLE `Usuario` (
  `IdUsuario` int(11)  AUTO_INCREMENT PRIMARY KEY,
  `Documento` varchar(250) DEFAULT NULL,
  `Nombre` varchar(250) DEFAULT NULL,
  `Email` varchar(250) DEFAULT NULL,
  `Celular` varchar(250) DEFAULT NULL
);

CREATE TABLE `Rol` (
  `IdRol` int(11) DEFAULT NULL AUTO_INCREMENT PRIMARY KEY,
  `Rol` varchar(250) DEFAULT NULL
);

CREATE TABLE `UsuarioRol` (
  `IdUsuarioRol` int(11)  AUTO_INCREMENT PRIMARY KEY,
  `IdUsuario` int(11) ,
  `IdRol` int(11) ,
   FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`IdUsuario`),
   FOREIGN KEY (`IdRol`) REFERENCES `Rol` (`IdRol`)
);

CREATE TABLE `Aprendiz` (
  `IdAprendiz` int(11) DEFAULT NULL AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(250) DEFAULT NULL,
  `Documento` int DEFAULT NULL,
  `RH` varchar(10) DEFAULT NULL
);

CREATE TABLE `TipoPrograma` (
  `IdTipoPrograma` int(11)  AUTO_INCREMENT PRIMARY KEY,
  `TipoPrograma` varchar(45) 
);

CREATE TABLE `Programa` (
  `IdPrograma` int(11)  AUTO_INCREMENT PRIMARY KEY,
  `Nombre` varchar(250) ,
  `Version` varchar(250) ,
  `Fecha` date,
  `IdTipoPrograma` int(11),
   FOREIGN KEY (`IdTipoPrograma`) REFERENCES `TipoPrograma` (`IdTipoPrograma`)
);


CREATE TABLE `Ficha` (
  `IdFicha` int(11)  AUTO_INCREMENT PRIMARY KEY,
  `Numficha` int ,
  `FechaIinicio` date ,
  `FechaFinal` date,
  `Jornada` varchar(250),
  `IdPrograma` INT(11) ,
  FOREIGN KEY (`IdPrograma`) REFERENCES `Programa` (`IdPrograma`)
);

CREATE TABLE `FichaAprendiz` (
  `IdFichaAprendiz` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `IdFicha` INT(11) ,
  `IdAprendiz` INT(11) ,
  FOREIGN KEY (`IdFicha`) REFERENCES `Ficha` (`IdFicha`),
  FOREIGN KEY (`IdAprendiz`) REFERENCES `Aprendiz` (`IdAprendiz`)
);


CREATE TABLE `Movimiento` (
  `IdMovimiento` int(11)  AUTO_INCREMENT PRIMARY KEY,
  `FechaHora` datetime ,
  `Movimiento` varchar(45),
  `IdUsuario` int(11) ,
  `IdAprendiz` int(11) ,
   FOREIGN KEY (`IdUsuario`) REFERENCES `Usuario` (`IdUsuario`),
   FOREIGN KEY (`IdAprendiz`) REFERENCES `Aprendiz` (`IdAprendiz`)
);

CREATE TABLE `TipoMaterial` (
  `IdTipoMaterial` int(11) AUTO_INCREMENT PRIMARY KEY,
  `Tipo` varchar(45) DEFAULT NULL
);

CREATE TABLE `Material` (
  `IdMaterial` int(11)  AUTO_INCREMENT PRIMARY KEY,
  `Referencia` varchar(250) ,
  `Marca` varchar(250) ,
  `Observaciones` varchar(100) ,
  `IdTipoMaterial` int(11) ,
   FOREIGN KEY (`IdTipoMaterial`) REFERENCES `TipoMaterial` (`IdTipoMaterial`)
);

CREATE TABLE `MovimientoMaterial` (
  `IdMovimientoMaterial` int(11)  AUTO_INCREMENT PRIMARY KEY,
  `Estado` varchar(250) ,
  `IdMovimiento` int(11) ,
  `IdMaterial` int(11) ,
  FOREIGN KEY (`IdMovimiento`) REFERENCES `Movimiento` (`IdMovimiento`),
  FOREIGN KEY (`IdMaterial`) REFERENCES `Material` (`IdMaterial`)
);


CREATE TABLE `TipoVehiculo` (
  `IdTipoVehiculo` int(11)  AUTO_INCREMENT PRIMARY KEY,
  `Tipo` varchar(45) DEFAULT NULL
);

CREATE TABLE `Vehiculo` (
  `IdVehiculo` int(11)  AUTO_INCREMENT PRIMARY KEY,
  `Placa` varchar(250) ,
  `IdTipoVehiculo` int(11) ,
   FOREIGN KEY (`IdTipoVehiculo`) REFERENCES `TipoVehiculo` (`IdTipoVehiculo`)
);


CREATE TABLE `MovimientoVehiculo` (
  `IdMovimientoVehiculo` int(11) AUTO_INCREMENT PRIMARY KEY,
  `Estado` varchar(250) ,
  `IdMovimiento` int(11) ,
  `IdVehiculo` int(11) ,
   FOREIGN KEY (`IdMovimiento`) REFERENCES `Movimiento` (`IdMovimiento`),
   FOREIGN KEY (`IdVehiculo`) REFERENCES `Vehiculo` (`IdVehiculo`)
);









