--------------------------------------------------------
-- Archivo creado  - lunes-marzo-02-2020   
--------------------------------------------------------
--------------------------------------------------------
--  DDL for Table WEBSERV_REF_PRE_IN_ES
--------------------------------------------------------

  CREATE TABLE "OASIS4"."WEBSERV_REF_PRE_IN_ES" 
   (	"ID_INES" NUMBER, 
	"CODIGO" NUMBER, 
	"DESCRIPCION" VARCHAR2(4000 BYTE), 
	"HABILITADO_MIPRES" NUMBER, 
	"VERSION_MIPRES" NUMBER(38,3), 
	"FECHA" DATE
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4" ;

   COMMENT ON TABLE "OASIS4"."WEBSERV_REF_PRE_IN_ES"  IS 'Indicaciones Especiales';
REM INSERTING into OASIS4.WEBSERV_REF_PRE_IN_ES
SET DEFINE OFF;
Insert into OASIS4.WEBSERV_REF_PRE_IN_ES (ID_INES,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('1','1','Administraci�n en dosis �nica',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_IN_ES (ID_INES,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('2','2','Administraci�n inmediata',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_IN_ES (ID_INES,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('3','3','Administrar en Bolo',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_IN_ES (ID_INES,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('4','4','Administrar en Goteo',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_IN_ES (ID_INES,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('5','5','Infusi�n continua',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_IN_ES (ID_INES,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('6','6','Infusi�n intermitente',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_IN_ES (ID_INES,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('7','7','Infusi�n intermitente simult�nea con perfusi�n de otra soluci�n',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_IN_ES (ID_INES,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('8','8','Microgoteo',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_IN_ES (ID_INES,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('9','9','Perfusi�n',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_IN_ES (ID_INES,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('10','10','Sin indicaci�n especial',null,null,null);
--------------------------------------------------------
--  DDL for Index WEBSERV_REF_PRE_IN_ES_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OASIS4"."WEBSERV_REF_PRE_IN_ES_PK" ON "OASIS4"."WEBSERV_REF_PRE_IN_ES" ("ID_INES") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4" ;
--------------------------------------------------------
--  DDL for Index REF_PRE_IN_ES_CODIGO_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "OASIS4"."REF_PRE_IN_ES_CODIGO_UK1" ON "OASIS4"."WEBSERV_REF_PRE_IN_ES" ("CODIGO") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4" ;
--------------------------------------------------------
--  Constraints for Table WEBSERV_REF_PRE_IN_ES
--------------------------------------------------------

  ALTER TABLE "OASIS4"."WEBSERV_REF_PRE_IN_ES" MODIFY ("ID_INES" NOT NULL ENABLE);
  ALTER TABLE "OASIS4"."WEBSERV_REF_PRE_IN_ES" ADD CONSTRAINT "WEBSERV_REF_PRE_IN_ES_PK" PRIMARY KEY ("ID_INES")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4"  ENABLE;
  ALTER TABLE "OASIS4"."WEBSERV_REF_PRE_IN_ES" ADD CONSTRAINT "REF_PRE_IN_ES_CODIGO_UK1" UNIQUE ("CODIGO")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4"  ENABLE;
