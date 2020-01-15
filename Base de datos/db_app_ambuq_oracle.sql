
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla oasis4.webserv_tiporeportes
--

CREATE TABLE oasis4.webserv_tiporeportes (
  tire_id number(11) NOT NULL,
  nombre varchar2(250) NOT NULL,
  descripcion varchar2(1200),
  token varchar2(250) NOT NULL,
  nit varchar2(250) NOT NULL,
  CONSTRAINT PK_webserv_tiporeportes PRIMARY KEY(tire_id)
);

 create sequence seq_webserv_tiporeportes
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;

INSERT INTO webserv_tiporeportes (tire_id, nombre,descripcion,token,nit) VALUES
(seq_webserv_tiporeportes.nextval, 'Contributivo', 'usuarios afiliados por el régimen contributivo', '3858A1E4-E9BB-40D1-90E7-C127480363F2', '818000140');
INSERT INTO webserv_tiporeportes (tire_id, nombre,descripcion,token,nit) VALUES
(seq_webserv_tiporeportes.nextval, 'Subsidiado', 'usuarios afiliados por el régimen subsidiado ', '208F5DB1-95D0-446E-AAD7-2674C6360A46', '818000140');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla oasis4.webserv_webservices
--

CREATE TABLE oasis4.webserv_webservices (
  ws_id number(11) NOT NULL,
  nombre varchar2(250) NOT NULL,
  descripcion varchar2(1200),
  CONSTRAINT PK_webserv_webservices PRIMARY KEY(ws_id)
);

 create sequence seq_webserv_webservices
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;


INSERT INTO webserv_webservices (ws_id,nombre,descripcion) VALUES
(seq_webserv_webservices.nextval, 'WSPRESCRIPCIÓN', '');
INSERT INTO webserv_webservices (ws_id, nombre, descripcion) VALUES
(seq_webserv_webservices.nextval, 'WSSUMINISTROAPI', '');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla oasis4.webserv_tiposervicios
--

CREATE TABLE oasis4.webserv_tiposervicios (
  tise_id number(11) NOT NULL,
  ws_id number(11) NOT NULL,
  nombre varchar2(250) NOT NULL,
  descripcion varchar2(1200),
  CONSTRAINT PK_webserv_tiposervicios PRIMARY KEY(tise_id),
  CONSTRAINT FK_webserv_tiposervicios1 FOREIGN KEY (ws_id) REFERENCES webserv_webservices(ws_id)
);

create sequence seq_webserv_tiposervicios
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;



INSERT INTO webserv_tiposervicios (tise_id,ws_id,nombre,descripcion) VALUES
(seq_webserv_tiposervicios.nextval,1, 'Prescripcion', '');
INSERT INTO webserv_tiposervicios (tise_id,ws_id,nombre,descripcion) VALUES
(seq_webserv_tiposervicios.nextval,2, 'ReporteEntrega', '');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla oasis4.webserv_servicios
--

CREATE TABLE oasis4.webserv_servicios (
  serv_id number(11) NOT NULL,
  tise_id number(11) NOT NULL,
  nombre varchar2(250) NOT NULL,
  descripcion varchar2(1200),
  url varchar2(1200) NOT NULL,
  CONSTRAINT PK_webserv_servicios PRIMARY KEY(serv_id),
  CONSTRAINT FK_webserv_servicios1 FOREIGN KEY (tise_id) REFERENCES webserv_tiposervicios(tise_id)
);

create sequence seq_webserv_servicios
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;



  INSERT INTO webserv_servicios (serv_id, tise_id, nombre, descripcion, url) VALUES
(seq_webserv_servicios.nextval, 2, 'ReporteEntregaXFecha', 'Retorna la información suministrada por fecha por cada prescripción que se ingresó.', 'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXFecha');
  INSERT INTO webserv_servicios (serv_id, tise_id, nombre, descripcion, url) VALUES
(seq_webserv_servicios.nextval, 2, 'GenerarToken', 'Genera un token temporal para los informes de contributivo', 'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken');
  INSERT INTO webserv_servicios (serv_id, tise_id, nombre, descripcion, url) VALUES
(seq_webserv_servicios.nextval, 1, 'Prescripcion', 'Retorna la lista de prescripciones, con la lista de los medicamentos asociados a la prescripción, con la lista de procedimientos asociados a la prescripción, la lista de dispositivos médicos asociados a la prescripción, con la lista de los productos nutricionales asociados a la prescripción y con la lista de los servicios complementarios asociados a la prescripción', 'https://wsmipres.sispro.gov.co/WSMIPRESNOPBS/api/Prescripcion');
  INSERT INTO webserv_servicios (serv_id, tise_id, nombre, descripcion, url) VALUES
(seq_webserv_servicios.nextval, 2, 'PrescripcionXNumero', 'Retorna la lista de los medicamentos asociados a la prescripción, la lista de procedimientos asociados a la prescripción, la lista de dispositivos médicos asociados a la prescripción, la lista de los productos nutricionales asociados a la prescripción y la lista de servicios complementarios asociados a la prescripción que se ingresó', 'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXPrescripcion');
  INSERT INTO webserv_servicios (serv_id, tise_id, nombre, descripcion, url) VALUES
(seq_webserv_servicios.nextval, 2, 'ReporteEntregaXPacienteFecha', 'Retorna la información suministrada por fecha por cada prescripción que se ingresó.', 'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXPacienteFecha');
  INSERT INTO webserv_servicios (serv_id, tise_id, nombre, descripcion, url) VALUES
(seq_webserv_servicios.nextval, 1, 'PrescripcionPaciente', 'Retorna la lista de prescripciones, con la lista de los medicamentos asociados a la prescripción, con la lista de procedimientos asociados a la prescripción, la lista de dispositivos médicos asociados a la prescripción, con la lista de los productos nutricionales asociados a la prescripción y con la lista de los servicios complementarios asociados a la prescripción. Para un paciente', 'https://wsmipres.sispro.gov.co/WSMIPRESNOPBS/api/PrescripcionPaciente');
  INSERT INTO webserv_servicios (serv_id, tise_id, nombre, descripcion, url) VALUES
(seq_webserv_servicios.nextval, 1, 'PrescripcionXNumero', 'Retorna la lista de los medicamentos asociados a la prescripción, la lista de procedimientos asociados a la prescripción, la lista de dispositivos médicos asociados a la prescripción, la lista de los productos nutricionales asociados a la prescripción y la lista de servicios complementarios asociados a la prescripción que se ingresó', 'https://wsmipres.sispro.gov.co/WSMIPRESNOPBS/api/PrescripcionXNumero');
  INSERT INTO webserv_servicios (serv_id, tise_id, nombre, descripcion, url) VALUES
(seq_webserv_servicios.nextval, 1, 'NovedadesPrescripcion', 'Retorna la lista de novedades asociadas a la prescripción.', 'https://wsmipres.sispro.gov.co/WSMIPRESNOPBS/api/NovedadesPrescripcion');


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla oasis4.webserv_reportesws
--

CREATE TABLE oasis4.webserv_reportes_json (
  serv_id number(11) NOT NULL,
  tire_id number(11) NOT NULL,
  periodo DATE NOT NULL,
  json CLOB,
  fecha_actualizacion DATE DEFAULT sysdate,
  fecha_registro DATE DEFAULT sysdate,
  CONSTRAINT PK_webserv_reportes_json PRIMARY KEY(serv_id,tire_id,periodo),
  CONSTRAINT FK_webserv_reportes_json1 FOREIGN KEY (serv_id) REFERENCES webserv_servicios(serv_id),
  CONSTRAINT FK_webserv_reportes_json2 FOREIGN KEY (tire_id) REFERENCES webserv_tiporeportes(tire_id)
);

  /*      
INSERT INTO webserv_reportes_json (serv_id, tire_id,periodo, json, fecha_actualizacion, fecha_registro) 
VALUES ( 1, 1,'2017-01-01 00:00:00', '[]', '2019-10-25 21:38:43', '2019-10-25 21:38:43');
*/
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla oasis4.webserv_log_errores
--

CREATE TABLE oasis4.webserv_log_errores (
  serv_id number(11) NOT NULL,
  tire_id number(11) NOT NULL,
  periodo date NOT NULL,
  nombre varchar2(1200) NOT NULL,
  descripcion varchar2(1200),
  CONSTRAINT PK_webserv_log_errores PRIMARY KEY(serv_id,tire_id,periodo),
  CONSTRAINT FK_webserv_log_errores1 FOREIGN KEY (serv_id) REFERENCES webserv_servicios(serv_id),
  CONSTRAINT FK_webserv_log_errores2 FOREIGN KEY (tire_id) REFERENCES webserv_tiporeportes(tire_id)
);
/*
INSERT INTO webserv_log_errores (serv_id, tire_id,periodo, nombre, descripcion) 
VALUES ( 1, 1,'2017-01-01 00:00:00', '','');
*/