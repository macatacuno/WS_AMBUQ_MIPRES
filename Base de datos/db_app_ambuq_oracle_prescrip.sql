--
-- Estructura de tabla para la tabla oasis4.WEBSERV_PRES_PRES
--
CREATE TABLE "OASIS4"."WEBSERV_PRES_PRES"
(
	
	"ID_PRES" NUMBER NOT NULL,
	"REPO_PERIODO" DATE, 
	"REPO_SERV_ID" NUMBER(11,0), 
	"REPO_TISE_ID" NUMBER(11,0), 
	"NOPRESCRIPCION" VARCHAR2(25) NOT NULL,
	"FPRESCRIPCION" DATE,
	"HPRESCRIPCION" VARCHAR2(15),
	"CODHABIPS" VARCHAR2(25),
	"TIPOIDIPS" VARCHAR2(7),
	"NROIDIPS" VARCHAR2(15),
	"CODDANEMUNIPS" VARCHAR2(10),
	"DIRSEDEIPS" VARCHAR2(305),
	"TELSEDEIPS" VARCHAR2(75),
	"TIPOIDPROF" VARCHAR2(25),
	"NUMIDPROF" VARCHAR2(22),
	"PNPROFS" VARCHAR2(65),
	"SNPROFS" VARCHAR2(65),
	"PAPROFS" VARCHAR2(65),
	"SAPROFS" VARCHAR2(65),
	"REGPROFS" VARCHAR2(65),
	"TIPOIDPACIENTE" VARCHAR2(35),
	"NROIDPACIENTE" VARCHAR2(25),
	"PNPACIENTE" VARCHAR2(100),
	"SNPACIENTE" VARCHAR2(65),
	"PAPACIENTE" VARCHAR2(65),
	"SAPACIENTE" VARCHAR2(65),
	"CODAMBATE" NUMBER,
	"REFAMBATE" NUMBER,
	"ENFHUERFANA" NUMBER,
	"CODENFHUERFANA" VARCHAR2(9),
	"ENFHUERFANADX" NUMBER,
	"CODDXPPAL" VARCHAR2(9),
	"CODDXREL1" VARCHAR2(9),
	"CODDXREL2" VARCHAR2(9),
	"SOPNUTRICIONAL" NUMBER,
	"CODEPS" VARCHAR2(25),
	"TIPOIDMADREPACIENTE" VARCHAR2(25),
	"NROIDMADREPACIENTE" VARCHAR2(22),
	"TIPOTRANSC" NUMBER,
	"TIPOIDDONANTEVIVO" VARCHAR2(25),
	"NROIDDONANTEVIVO" VARCHAR2(22),
	"ESTPRES" NUMBER,
	CONSTRAINT "WEBSERV_PRES_PRES_PK" PRIMARY KEY ("ID_PRES"),
	CONSTRAINT "PRES_PRES_REPORTES_JSON_FK" FOREIGN KEY ("REPO_SERV_ID", "REPO_TISE_ID", "REPO_PERIODO")
	  REFERENCES "OASIS4"."WEBSERV_REPORTES_JSON" ("SERV_ID", "TIRE_ID", "PERIODO") 
);
CREATE UNIQUE INDEX "OASIS4"."WEBSERV_PRES_PRES_INDEX1" ON "OASIS4"."WEBSERV_PRES_PRES"
("NOPRESCRIPCION");

create sequence SEQ_WEBSERV_PRES_PRES
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;


--
-- Estructura de tabla para la tabla oasis4.WEBSERV_PRES_MEDI
--



  CREATE TABLE "OASIS4"."WEBSERV_PRES_MEDI" 
   (	
	"ID_MEDI" NUMBER NOT NULL, 
	"ID_PRES" NUMBER, 
	"CONORDEN" NUMBER, 
	"TIPOMED" NUMBER, 
	"TIPOPREST" NUMBER, 
	"CAUSAS1" NUMBER, 
	"CAUSAS2" NUMBER, 
	"CAUSAS3" NUMBER, 
	"MEDPBSUTILIZADO" VARCHAR2(305), 
	"RZNCAUSAS31" NUMBER, 
	"DESCRZN31" VARCHAR2(165), 
	"RZNCAUSAS32" NUMBER, 
	"DESCRZN32" VARCHAR2(165), 
	"CAUSAS4" NUMBER , 
	"MEDPBSDESCARTADO" VARCHAR2(305), 
	"RZNCAUSAS41" NUMBER, 
	"DESCRZN41" VARCHAR2(165), 
	"RZNCAUSAS42" NUMBER, 
	"DESCRZN42" VARCHAR2(165), 
	"RZNCAUSAS43" NUMBER, 
	"DESCRZN43" VARCHAR2(165), 
	"RZNCAUSAS44" NUMBER, 
	"DESCRZN44" VARCHAR2(165), 
	"CAUSAS5" NUMBER, 
	"RZNCAUSAS5" NUMBER, 
	"CAUSAS6" NUMBER, 
	"DESCMEDPRINACT" CLOB, 
	"CODFF" VARCHAR2(105), 
	"CODVA" VARCHAR2(15), 
	"JUSTNOPBS" VARCHAR2(2000), 
	"DOSIS" VARCHAR2(15), 
	"DOSISUM" VARCHAR2(15), 
	"NOFADMON" VARCHAR2(10), 
	"CODFREADMON" NUMBER, 
	"INDESP" NUMBER, 
	"CANTRAT" VARCHAR2(10), 
	"DURTRAT" NUMBER, 
	"CANTTOTALF" VARCHAR2(15), 
	"UFCANTTOTAL" VARCHAR2(15), 
	"INDREC" VARCHAR2(2000), 
	"ESTJM" NUMBER, 
	 CONSTRAINT "WEBSERV_PRES_MEDI_PK" PRIMARY KEY ("ID_MEDI"),
	 CONSTRAINT "PRES_MEDI_ID_PRES_FK" FOREIGN KEY ("ID_PRES")
	  REFERENCES "OASIS4"."WEBSERV_PRES_PRES" ("ID_PRES")
   );
create sequence SEQ_WEBSERV_PRES_MEDI
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;
--
-- Estructura de tabla para la tabla oasis4.WEBSERV_PRES_PRIN_ACTI
--

  CREATE TABLE "OASIS4"."WEBSERV_PRES_PRIN_ACTI" 
   (	
	"ID_PRAC" NUMBER NOT NULL, 
	"ID_MEDI" NUMBER, 
	"CONORDEN" NUMBER, 
	"CODPRIACT" VARCHAR2(15), 
	"CONCCANT" CLOB, 
	"UMEDCONC" VARCHAR2(10), 
	"CANTCONT" CLOB, 
	"UMEDCANTCONT" VARCHAR2(15), 
	 CONSTRAINT "PRES_PRIN_ACTI_PK" PRIMARY KEY ("ID_PRAC"), 
	 CONSTRAINT "PRES_PRIN_ACTI_ID_MEDI_FK" FOREIGN KEY ("ID_MEDI")
	  REFERENCES "OASIS4"."WEBSERV_PRES_MEDI" ("ID_MEDI"));
create sequence SEQ_WEBSERV_PRES_PRIN_ACTI
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;

--
-- Estructura de tabla para la tabla oasis4.WEBSERV_PRES_PRIN_ACTI
--

  CREATE TABLE "OASIS4"."WEBSERV_PRES_INDI_UNIRS" 
   (	
	"ID_IMUN" NUMBER NOT NULL, 
	"ID_MEDI" NUMBER, 
	"CONORDEN" NUMBER, 
	"CODINDICACION" VARCHAR2(15),
	 CONSTRAINT "PRES_INDI_UNIRS_PK" PRIMARY KEY ("ID_IMUN"), 
	 CONSTRAINT "PRES_INDI_UNIRS_ID_MEDI_FK" FOREIGN KEY ("ID_MEDI")
	  REFERENCES "OASIS4"."WEBSERV_PRES_MEDI" ("ID_MEDI")
   );

create sequence SEQ_WEBSERV_PRES_INDI_UNIRS
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;

--
-- Estructura de tabla para la tabla oasis4.WEBSERV_PRES_PROC
--

  CREATE TABLE "OASIS4"."WEBSERV_PRES_PROC" 
   (	
	"ID_PROC" NUMBER NOT NULL, 
	"ID_PRES" NUMBER, 
	"CONORDEN" NUMBER, 
	"TIPOPREST" NUMBER, 
	"CAUSAS11" NUMBER, 
	"CAUSAS12" NUMBER, 
	"CAUSAS2" NUMBER, 
	"CAUSAS3" NUMBER, 
	"CAUSAS4" NUMBER, 
	"PROPBSUTILIZADO" VARCHAR2(11), 
	"CAUSAS5" NUMBER, 
	"PROPBSDESCARTADO" VARCHAR2(11), 
	"RZNCAUSAS51" NUMBER, 
	"DESCRZN51" VARCHAR2(165), 
	"RZNCAUSAS52" NUMBER, 
	"DESCRZN52" VARCHAR2(165), 
	"CAUSAS6" NUMBER, 
	"CAUSAS7" NUMBER, 
	"CODCUPS" VARCHAR2(11), 
	"CANFORM" VARCHAR2(10), 
	"CADAFREUSO" VARCHAR2(10), 
	"CODFREUSO" NUMBER, 
	"CANT" VARCHAR2(10), 
	"CANTTOTAL" VARCHAR2(15), 
	"CODPERDURTRAT" NUMBER, 
	"JUSTNOPBS" VARCHAR2(505), 
	"INDREC" VARCHAR2(165), 
	"ESTJM" NUMBER, 
	 CONSTRAINT "WEBSERV_PRES_PROC_PK" PRIMARY KEY ("ID_PROC"), 
	 CONSTRAINT "PRES_PROC_ID_PREC_FK" FOREIGN KEY ("ID_PRES")
	  REFERENCES "OASIS4"."WEBSERV_PRES_PRES" ("ID_PRES")
   );

create sequence SEQ_WEBSERV_PRES_PROC
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;

--
-- Estructura de tabla para la tabla oasis4.WEBSERV_PRES_DISP
--

  CREATE TABLE "OASIS4"."WEBSERV_PRES_DISP" 
   (	
	"ID_DISP" NUMBER NOT NULL, 
	"ID_PRES" NUMBER, 
	"CONORDEN" NUMBER, 
	"TIPOPREST" NUMBER, 
	"CAUSAS1" NUMBER, 
	"CODDISP" VARCHAR2(15), 
	"CANFORM" VARCHAR2(10), 
	"CADAFREUSO" VARCHAR2(10), 
	"CODFREUSO" VARCHAR2(10), 
	"CANT" VARCHAR2(10), 
	"CODPERDURTRAT" NUMBER, 
	"CANTTOTAL" VARCHAR2(15), 
	"JUSTNOPBS" VARCHAR2(505), 
	"INDREC" VARCHAR2(165), 
	"ESTJM" NUMBER, 
	 CONSTRAINT "WEBSERV_PRES_DISP_PK" PRIMARY KEY ("ID_DISP"), 
	 CONSTRAINT "PRES_DISP_ID_PRES_FK" FOREIGN KEY ("ID_PRES")
	  REFERENCES "OASIS4"."WEBSERV_PRES_PRES" ("ID_PRES")
   );
  create sequence SEQ_WEBSERV_PRES_DISP
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;
--
-- Estructura de tabla para la tabla oasis4.WEBSERV_PRES_PROD_NUTR
--



  CREATE TABLE "OASIS4"."WEBSERV_PRES_PROD_NUTR" 
   (	
	"ID_PRNU" NUMBER NOT NULL, 
	"ID_PRES" NUMBER, 
	"CONORDEN" NUMBER, 
	"TIPOPREST" NUMBER, 
	"CAUSAS1" NUMBER, 
	"CAUSAS2" NUMBER, 
	"CAUSAS3" NUMBER, 
	"CAUSAS4" NUMBER, 
	"PRONUTUTILIZADO" VARCHAR2(165), 
	"RZNCAUSAS41" NUMBER, 
	"DESCRZN41" VARCHAR2(165), 
	"RZNCAUSAS42" NUMBER, 
	"DESCRZN42" VARCHAR2(165), 
	"CAUSAS5" NUMBER, 
	"PRONUTDESCARTADO" VARCHAR2(165), 
	"RZNCAUSAS51" NUMBER, 
	"DESCRZN51" VARCHAR2(165), 
	"RZNCAUSAS52" NUMBER, 
	"DESCRZN52" VARCHAR2(165), 
	"RZNCAUSAS53" NUMBER, 
	"DESCRZN53" VARCHAR2(165), 
	"RZNCAUSAS54" NUMBER, 
	"DESCRZN54" VARCHAR2(165), 
	"DXENFHUER" NUMBER, 
	"DXVIH" NUMBER, 
	"DXCAPAL" NUMBER, 
	"DXENFRCEV" NUMBER, 
	"DXDESPRO" NUMBER, 
	"TIPPPRONUT" VARCHAR2(35), 
	"DESCPRODNUTR" VARCHAR2(35), 
	"CODFORMA" VARCHAR2(105), 
	"CODVIAADMON" NUMBER, 
	"JUSTNOPBS" VARCHAR2(505), 
	"DOSIS" VARCHAR2(15), 
	"DOSISUM" VARCHAR2(15), 
	"NOFADMON" VARCHAR2(10), 
	"CODFREADMON" NUMBER, 
	"INDESP" NUMBER, 
	"CANTRAT" VARCHAR2(10), 
	"DURTRAT" NUMBER, 
	"CANTTOTALF" VARCHAR2(15), 
	"UFCANTTOTAL" VARCHAR2(105), 
	"INDREC" VARCHAR2(165), 
	"NOPRESCASO" VARCHAR2(25), 
	"ESTJM" NUMBER, 
	 CONSTRAINT "WEBSERV_PRES_PROD_NUTR_PK" PRIMARY KEY ("ID_PRNU"), 
	 CONSTRAINT "PRES_PROD_NUTR_ID_PRES_FK" FOREIGN KEY ("ID_PRES")
	  REFERENCES "OASIS4"."WEBSERV_PRES_PRES" ("ID_PRES")
   );

  create sequence SEQ_WEBSERV_PRES_PROD_NUTR
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;
--
-- Estructura de tabla para la tabla oasis4.WEBSERV_PRES_SERV_COMP
--


  CREATE TABLE "OASIS4"."WEBSERV_PRES_SERV_COMP" 
   (	
	"ID_SECO" NUMBER NOT NULL, 
	"ID_PRES" NUMBER, 
	"CONORDEN" NUMBER, 
	"TIPOPREST" NUMBER, 
	"CAUSAS1" NUMBER, 
	"CAUSAS2" NUMBER, 
	"CAUSAS3" NUMBER, 
	"CAUSAS4" NUMBER, 
	"DESCCAUSAS4" VARCHAR2(165), 
	"CAUSAS5" NUMBER, 
	"CODSERCOMP" VARCHAR2(10), 
	"DESCSERCOMP" VARCHAR2(165), 
	"CANFORM" VARCHAR2(10), 
	"CADAFREUSO" VARCHAR2(10), 
	"CODFREUSO" NUMBER, 
	"CANT" VARCHAR2(10), 
	"CANTTOTAL" VARCHAR2(15), 
	"CODPERDURTRAT" NUMBER, 
	"TIPOTRANS" NUMBER, 
	"REQACOM" NUMBER, 
	"TIPOIDACOMALB" VARCHAR2(25), 
	"NROIDACOMALB" VARCHAR2(22), 
	"PARENTACOMALB" NUMBER, 
	"NOMBALB" VARCHAR2(165), 
	"CODMUNORIALB" VARCHAR2(10), 
	"CODMUNDESALB" VARCHAR2(10), 
	"JUSTNOPBS" VARCHAR2(505), 
	"INDREC" VARCHAR2(165), 
	"ESTJM" NUMBER, 
	 CONSTRAINT "WEBSERV_PRES_SERV_COMP_PK" PRIMARY KEY ("ID_SECO"), 
	 CONSTRAINT "PRES_SERV_COMP_ID_PRES_FK" FOREIGN KEY ("ID_PRES")
	  REFERENCES "OASIS4"."WEBSERV_PRES_PRES" ("ID_PRES")
   );
  create sequence SEQ_WEBSERV_PRES_SERV_COMP
  start with 1
  increment by 1
  maxvalue 9999999
  minvalue 1;