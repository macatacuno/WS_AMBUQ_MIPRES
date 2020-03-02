--------------------------------------------------------
-- Archivo creado  - lunes-marzo-02-2020   
--------------------------------------------------------
--------------------------------------------------------
--  DDL for Table WEBSERV_REF_PRE_TD_ME
--------------------------------------------------------

  CREATE TABLE "OASIS4"."WEBSERV_REF_PRE_TD_ME" 
   (	"ID_TDME" NUMBER, 
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

   COMMENT ON TABLE "OASIS4"."WEBSERV_REF_PRE_TD_ME"  IS 'Tipos de dispositivo m�dico';
REM INSERTING into OASIS4.WEBSERV_REF_PRE_TD_ME
SET DEFINE OFF;
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('1','11','BOLSA>104 AL A�O; EN CANCER COLON O RECTO','0','1',to_date('03/01/18','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('2','12','CARALLA>104 AL A�O; EN CANCER COLON O RECTO','0','1',to_date('03/01/18','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('3','13','PINZA>104 AL A�O; EN CANCER COLON O RECTO','0','1',to_date('03/01/18','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('4','14','PEGANTE>104 AL A�O; EN CANCER COLON O RECTO','0','1',to_date('03/01/18','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('5','21','LANCETAS>100 X MES; DIABETES MELLITUS TIPO I MANEJO CON INSULINA','0','1',to_date('04/01/17','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('6','22','LANCETAS>50 X MES; DIABETES MELLITUS TIPO II MANEJO CON INSULINA','0','1',to_date('04/01/17','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('7','23','TIRILLAS >100 X MES; DIABETES MELLITUS TIPO I MANEJO CON INSULINA','0','1',to_date('04/01/17','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('8','24','TIRILLAS >50 X MES; DIABETES MELLITUS TIPO II MANEJO CON INSULINA','0','1',to_date('04/01/17','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('9','25','GLUCOMETRO>1 X A�O; DIABETES MELLITUS TIPO I O II MANEJO CON INSULINA','0','1',to_date('13/09/17','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('10','31','STENT MEDICADO; LESION >3 MILIMETROS DE DIAMETRO O LESION <15 MILIMETROS DE LONGITUD','0','1',to_date('01/03/17','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('11','32','STENT MEDICADO; VASOS PEQUE�OS MAYORES O IGUALES  A 3 MM DE DIAMETRO','0','1',to_date('03/01/18','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('12','33','STENT MEDICADO; LESIONES LARGAS MENORES O IGUALES  A 15 MM DE LONGITUD','0','1',to_date('03/01/18','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('13','41','FILTROS DE COLORES O PELICULAS PARA LENTES EXTERNOS','1','1',to_date('18/11/16','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('14','42','LENTES EXTERNOS FRECUENCIA >1 AL A�O, PARA MENORES DE EDAD 12 A�OS Y MENOS','1','1',to_date('18/11/16','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('15','43','LENTES EXTERNOS FRECUENCIA >1 EN CINCO A�OS  PARA PACIENTES >12 A�OS','1','1',to_date('18/11/16','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('16','44','LENTES EXTERNOS MATERIAL DIFERENTE A VIDRIO-PLASTICO O POLICARBONATO','1','1',to_date('18/11/16','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('17','45','LENTES DE CONTACTO','0','1',to_date('01/03/17','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('18','51','BOLSA DE KIT DE OSTOMIA; INDICACIONES DIFERENTES A CANCER COLON O RECTO','0','1',to_date('03/01/18','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('19','52','CARALLA DE KIT DE OSTOMIA; INDICACIONES DIFERENTES A CANCER COLON O RECTO','0','1',to_date('03/01/18','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('20','53','PINZA DE KIT DE OSTOMIA; INDICACIONES DIFERENTES A CANCER COLON O RECTO','0','1',to_date('03/01/18','DD/MM/RR'));
Insert into OASIS4.WEBSERV_REF_PRE_TD_ME (ID_TDME,CODIGO,DESCRIPCION,HABILITADO_MIPRES,VERSION_MIPRES,FECHA) values ('21','54','PEGANTE DE KIT DE OSTOMIA; INDICACIONES DIFERENTES A CANCER COLON O RECTO','0','1',to_date('03/01/18','DD/MM/RR'));
--------------------------------------------------------
--  DDL for Index WEBSERV_REF_PRE_TD_ME_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OASIS4"."WEBSERV_REF_PRE_TD_ME_PK" ON "OASIS4"."WEBSERV_REF_PRE_TD_ME" ("ID_TDME") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4" ;
--------------------------------------------------------
--  DDL for Index REF_PRE_TD_ME_CODIGO_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "OASIS4"."REF_PRE_TD_ME_CODIGO_UK1" ON "OASIS4"."WEBSERV_REF_PRE_TD_ME" ("CODIGO") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4" ;
--------------------------------------------------------
--  Constraints for Table WEBSERV_REF_PRE_TD_ME
--------------------------------------------------------

  ALTER TABLE "OASIS4"."WEBSERV_REF_PRE_TD_ME" MODIFY ("ID_TDME" NOT NULL ENABLE);
  ALTER TABLE "OASIS4"."WEBSERV_REF_PRE_TD_ME" ADD CONSTRAINT "WEBSERV_REF_PRE_TD_ME_PK" PRIMARY KEY ("ID_TDME")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4"  ENABLE;
  ALTER TABLE "OASIS4"."WEBSERV_REF_PRE_TD_ME" ADD CONSTRAINT "REF_PRE_TD_ME_CODIGO_UK1" UNIQUE ("CODIGO")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4"  ENABLE;
