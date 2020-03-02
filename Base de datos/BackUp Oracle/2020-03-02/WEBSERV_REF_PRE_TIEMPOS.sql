--------------------------------------------------------
-- Archivo creado  - lunes-marzo-02-2020   
--------------------------------------------------------
--------------------------------------------------------
--  DDL for Table WEBSERV_REF_PRE_TIEMPOS
--------------------------------------------------------

  CREATE TABLE "OASIS4"."WEBSERV_REF_PRE_TIEMPOS" 
   (	"ID_TIEMPO" NUMBER, 
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

   COMMENT ON TABLE "OASIS4"."WEBSERV_REF_PRE_TIEMPOS"  IS 'Frecuencias Y duraciones';
REM INSERTING into OASIS4.WEBSERV_REF_PRE_TIEMPOS
SET DEFINE OFF;
Insert into OASIS4.WEBSERV_REF_PRE_TIEMPOS (ID_TIEMPO,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('1','1','Minuto(s)',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_TIEMPOS (ID_TIEMPO,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('2','2','Hora(s)',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_TIEMPOS (ID_TIEMPO,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('3','3','D�a(s)',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_TIEMPOS (ID_TIEMPO,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('4','4','Semana(s)',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_TIEMPOS (ID_TIEMPO,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('5','5','Mes(es)',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_TIEMPOS (ID_TIEMPO,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('6','6','A�o',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_TIEMPOS (ID_TIEMPO,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('7','7','Seg�n respuesta al tratamiento',null,null,null);
Insert into OASIS4.WEBSERV_REF_PRE_TIEMPOS (ID_TIEMPO,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('8','8','�nica',null,null,null);
--------------------------------------------------------
--  DDL for Index WEBSERV_REF_PRE_TIEMPOS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OASIS4"."WEBSERV_REF_PRE_TIEMPOS_PK" ON "OASIS4"."WEBSERV_REF_PRE_TIEMPOS" ("ID_TIEMPO") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4" ;
--------------------------------------------------------
--  DDL for Index REF_PRE_TIEMPOS_CODIGO_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "OASIS4"."REF_PRE_TIEMPOS_CODIGO_UK1" ON "OASIS4"."WEBSERV_REF_PRE_TIEMPOS" ("CODIGO") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4" ;
--------------------------------------------------------
--  Constraints for Table WEBSERV_REF_PRE_TIEMPOS
--------------------------------------------------------

  ALTER TABLE "OASIS4"."WEBSERV_REF_PRE_TIEMPOS" MODIFY ("ID_TIEMPO" NOT NULL ENABLE);
  ALTER TABLE "OASIS4"."WEBSERV_REF_PRE_TIEMPOS" ADD CONSTRAINT "WEBSERV_REF_PRE_TIEMPOS_PK" PRIMARY KEY ("ID_TIEMPO")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4"  ENABLE;
  ALTER TABLE "OASIS4"."WEBSERV_REF_PRE_TIEMPOS" ADD CONSTRAINT "REF_PRE_TIEMPOS_CODIGO_UK1" UNIQUE ("CODIGO")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4"  ENABLE;
