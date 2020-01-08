
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla oasis4.webserv_tiporeportes
--
/*
drop table webserv_log_errores;
drop table webserv_reportes_json;
drop table webserv_servicios;
drop table webserv_tiposervicios;
drop table webserv_webservices;
drop table webserv_tiporeportes;
*/
CREATE TABLE oasis4.webserv_tiporeportes (
  tire_id number(11) NOT NULL,
  nombre varchar2(250) NOT NULL,
  descripcion varchar2(1200),
  token varchar2(250) NOT NULL,
  nit varchar2(250) NOT NULL,
  CONSTRAINT PK_webserv_tiporeportes PRIMARY KEY(tire_id)
);

 create sequence sec_webserv_tiporeportes
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;

INSERT INTO webserv_tiporeportes (tire_id, nombre,descripcion,token,nit) VALUES
(sec_webserv_tiporeportes.nextval, 'Contributivo', 'usuarios afiliados por el régimen contributivo', '3858A1E4-E9BB-40D1-90E7-C127480363F2', '818000140');
INSERT INTO webserv_tiporeportes (tire_id, nombre,descripcion,token,nit) VALUES
(sec_webserv_tiporeportes.nextval, 'Subsidiado', 'usuarios afiliados por el régimen subsidiado ', '208F5DB1-95D0-446E-AAD7-2674C6360A46', '818000140');

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

 create sequence sec_webserv_webservices
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;


INSERT INTO webserv_webservices (ws_id,nombre,descripcion) VALUES
(sec_webserv_webservices.nextval, 'WSPRESCRIPCIÓN', '');
INSERT INTO webserv_webservices (ws_id, nombre, descripcion) VALUES
(sec_webserv_webservices.nextval, 'WSSUMINISTROAPI', '');
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

create sequence sec_webserv_tiposervicios
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;

INSERT INTO webserv_tiposervicios (tise_id,ws_id,nombre,descripcion) VALUES
(sec_webserv_tiposervicios.nextval,1, 'Prescripcion', '');
INSERT INTO webserv_tiposervicios (tise_id,ws_id,nombre,descripcion) VALUES
(sec_webserv_tiposervicios.nextval,2, 'ReporteEntrega', '');
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

create sequence sec_webserv_servicios
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla oasis4.webserv_reportesws
--

CREATE TABLE oasis4.webserv_reportes_json (
  serv_id number(11) NOT NULL,
  tire_id number(11) NOT NULL,
  periodo DATE NOT NULL,
  json long NOT NULL,
  fecha_actualizacion DATE DEFAULT sysdate,
  fecha_registro DATE DEFAULT sysdate,
  CONSTRAINT PK_webserv_reportes_json PRIMARY KEY(serv_id,tire_id,periodo),
  CONSTRAINT FK_webserv_reportes_json1 FOREIGN KEY (serv_id) REFERENCES webserv_servicios(serv_id),
  CONSTRAINT FK_webserv_reportes_json2 FOREIGN KEY (tire_id) REFERENCES webserv_tiporeportes(tire_id)
);


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