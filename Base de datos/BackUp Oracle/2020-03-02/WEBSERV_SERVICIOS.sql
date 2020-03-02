--------------------------------------------------------
-- Archivo creado  - lunes-marzo-02-2020   
--------------------------------------------------------
--------------------------------------------------------
--  DDL for Table WEBSERV_SERVICIOS
--------------------------------------------------------

  CREATE TABLE "OASIS4"."WEBSERV_SERVICIOS" 
   (	"SERV_ID" NUMBER(11,0), 
	"TISE_ID" NUMBER(11,0), 
	"NOMBRE" VARCHAR2(250 BYTE), 
	"DESCRIPCION" VARCHAR2(1200 BYTE), 
	"URL" VARCHAR2(1200 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4" ;
REM INSERTING into OASIS4.WEBSERV_SERVICIOS
SET DEFINE OFF;
Insert into OASIS4.WEBSERV_SERVICIOS (SERV_ID,TISE_ID,NOMBRE,DESCRIPCION,URL) values ('1','2','ReporteEntregaXFecha','Retorna la información suministrada por fecha por cada prescripción que se ingresó.','https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXFecha');
Insert into OASIS4.WEBSERV_SERVICIOS (SERV_ID,TISE_ID,NOMBRE,DESCRIPCION,URL) values ('2','2','GenerarToken','Genera un token temporal para los informes de contributivo','https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken');
Insert into OASIS4.WEBSERV_SERVICIOS (SERV_ID,TISE_ID,NOMBRE,DESCRIPCION,URL) values ('3','1','Prescripcion','Retorna la lista de prescripciones, con la lista de los medicamentos asociados a la prescripción, con la lista de procedimientos asociados a la prescripción, la lista de dispositivos médicos asociados a la prescripción, con la lista de los productos nutricionales asociados a la prescripción y con la lista de los servicios complementarios asociados a la prescripción','https://wsmipres.sispro.gov.co/WSMIPRESNOPBS/api/Prescripcion');
Insert into OASIS4.WEBSERV_SERVICIOS (SERV_ID,TISE_ID,NOMBRE,DESCRIPCION,URL) values ('4','2','PrescripcionXNumero','Retorna la lista de los medicamentos asociados a la prescripción, la lista de procedimientos asociados a la prescripción, la lista de dispositivos médicos asociados a la prescripción, la lista de los productos nutricionales asociados a la prescripción y la lista de servicios complementarios asociados a la prescripción que se ingresó','https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXPrescripcion');
Insert into OASIS4.WEBSERV_SERVICIOS (SERV_ID,TISE_ID,NOMBRE,DESCRIPCION,URL) values ('5','2','ReporteEntregaXPacienteFecha','Retorna la información suministrada por fecha por cada prescripción que se ingresó.','https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXPacienteFecha');
Insert into OASIS4.WEBSERV_SERVICIOS (SERV_ID,TISE_ID,NOMBRE,DESCRIPCION,URL) values ('6','1','PrescripcionPaciente','Retorna la lista de prescripciones, con la lista de los medicamentos asociados a la prescripción, con la lista de procedimientos asociados a la prescripción, la lista de dispositivos médicos asociados a la prescripción, con la lista de los productos nutricionales asociados a la prescripción y con la lista de los servicios complementarios asociados a la prescripción. Para un paciente','https://wsmipres.sispro.gov.co/WSMIPRESNOPBS/api/PrescripcionPaciente');
Insert into OASIS4.WEBSERV_SERVICIOS (SERV_ID,TISE_ID,NOMBRE,DESCRIPCION,URL) values ('7','1','PrescripcionXNumero','Retorna la lista de los medicamentos asociados a la prescripción, la lista de procedimientos asociados a la prescripción, la lista de dispositivos médicos asociados a la prescripción, la lista de los productos nutricionales asociados a la prescripción y la lista de servicios complementarios asociados a la prescripción que se ingresó','https://wsmipres.sispro.gov.co/WSMIPRESNOPBS/api/PrescripcionXNumero');
Insert into OASIS4.WEBSERV_SERVICIOS (SERV_ID,TISE_ID,NOMBRE,DESCRIPCION,URL) values ('8','1','NovedadesPrescripcion','Retorna la lista de novedades asociadas a la prescripción.','https://wsmipres.sispro.gov.co/WSMIPRESNOPBS/api/NovedadesPrescripcion');
Insert into OASIS4.WEBSERV_SERVICIOS (SERV_ID,TISE_ID,NOMBRE,DESCRIPCION,URL) values ('9','3','JuntaProfesionalXFecha','Retorna el estado de la Junta de Profesionales para la prescripción que se ingresó','https://wsmipres.sispro.gov.co/WSMIPRESNOPBS/api/JuntaProfesionalXFecha');
Insert into OASIS4.WEBSERV_SERVICIOS (SERV_ID,TISE_ID,NOMBRE,DESCRIPCION,URL) values ('10','3','Direccionamiento','Direcciona las tecnologías de las prescripciones','https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/Direccionamiento');
--------------------------------------------------------
--  DDL for Index PK_WEBSERV_SERVICIOS
--------------------------------------------------------

  CREATE UNIQUE INDEX "OASIS4"."PK_WEBSERV_SERVICIOS" ON "OASIS4"."WEBSERV_SERVICIOS" ("SERV_ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4" ;
--------------------------------------------------------
--  Constraints for Table WEBSERV_SERVICIOS
--------------------------------------------------------

  ALTER TABLE "OASIS4"."WEBSERV_SERVICIOS" MODIFY ("SERV_ID" NOT NULL ENABLE);
  ALTER TABLE "OASIS4"."WEBSERV_SERVICIOS" MODIFY ("TISE_ID" NOT NULL ENABLE);
  ALTER TABLE "OASIS4"."WEBSERV_SERVICIOS" MODIFY ("NOMBRE" NOT NULL ENABLE);
  ALTER TABLE "OASIS4"."WEBSERV_SERVICIOS" MODIFY ("URL" NOT NULL ENABLE);
  ALTER TABLE "OASIS4"."WEBSERV_SERVICIOS" ADD CONSTRAINT "PK_WEBSERV_SERVICIOS" PRIMARY KEY ("SERV_ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4"  ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table WEBSERV_SERVICIOS
--------------------------------------------------------

  ALTER TABLE "OASIS4"."WEBSERV_SERVICIOS" ADD CONSTRAINT "FK_WEBSERV_SERVICIOS1" FOREIGN KEY ("TISE_ID")
	  REFERENCES "OASIS4"."WEBSERV_TIPOSERVICIOS" ("TISE_ID") ENABLE;
