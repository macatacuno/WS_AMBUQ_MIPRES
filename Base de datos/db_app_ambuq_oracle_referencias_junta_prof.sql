

--#1
-- Estructura de tabla para la tabla oasis4.WEBSERV_REF_JP_TI_TE
--

CREATE TABLE "OASIS4"."WEBSERV_REF_JP_TI_TE"
(
    "CODIGO" VARCHAR(50),
    "DESCRIPCION" VARCHAR2(4000),
    "HABILITADO_MIPRES" NUMBER,
    "VERSION_MIPRES" NUMBER(38,3),
    "FECHA" DATE,
    CONSTRAINT "WEBSERV_REF_JP_TI_TE_PK" PRIMARY KEY ("CODIGO")
);
COMMENT ON TABLE "OASIS4"."WEBSERV_REF_JP_TI_TE"  IS 'Tipo de Tecnología';



insert into WEBSERV_REF_JP_TI_TE(CODIGO,DESCRIPCION) values(
'M','Medicamentos incluidos en la lista UNIRS');
insert into WEBSERV_REF_JP_TI_TE(CODIGO,DESCRIPCION) values(
'N','Productos nutricionales de tipo Ambulatorio');
insert into WEBSERV_REF_JP_TI_TE(CODIGO,DESCRIPCION) values(
'S','Servicios Complementarios');


--#2
-- Estructura de tabla para la tabla oasis4.WEBSERV_REF_JP_MODAL
--

CREATE TABLE "OASIS4"."WEBSERV_REF_JP_MODAL"
(
    "CODIGO" NUMBER,
    "DESCRIPCION" VARCHAR2(4000),
    "HABILITADO_MIPRES" NUMBER,
    "VERSION_MIPRES" NUMBER(38,3),
    "FECHA" DATE,
    CONSTRAINT "WEBSERV_REF_JP_MODAL_PK" PRIMARY KEY ("CODIGO")
);
COMMENT ON TABLE "OASIS4"."WEBSERV_REF_JP_MODAL"  IS 'Tipo de Tecnología';



insert into WEBSERV_REF_JP_MODAL(CODIGO,DESCRIPCION) values(
1,'Presencial');
insert into WEBSERV_REF_JP_MODAL(CODIGO,DESCRIPCION) values(
2,'Virtual');