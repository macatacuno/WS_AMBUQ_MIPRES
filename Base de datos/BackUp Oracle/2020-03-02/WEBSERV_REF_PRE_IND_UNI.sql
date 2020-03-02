--------------------------------------------------------
-- Archivo creado  - lunes-marzo-02-2020   
--------------------------------------------------------
--------------------------------------------------------
--  DDL for Table WEBSERV_REF_PRE_IND_UNI
--------------------------------------------------------

  CREATE TABLE "OASIS4"."WEBSERV_REF_PRE_IND_UNI" 
   (	"ID_INUN" NUMBER, 
	"CODIGO" NUMBER, 
	"DESCRIPCION" VARCHAR2(4000 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4" ;

   COMMENT ON TABLE "OASIS4"."WEBSERV_REF_PRE_IND_UNI"  IS 'C�digo Indicaci�n';
REM INSERTING into OASIS4.WEBSERV_REF_PRE_IND_UNI
SET DEFINE OFF;
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('10','10','a) Uso en pacientes adultos con uve�tis no infecciosa, intermedia, posterior y panuve�tis refractaria a tratamiento convencional. b) Uso en pacientes adultos con enfermedad de Behcet refractaria a tratamiento convencional.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('11','11','a) Uso en pacientes pedi�tricos espec�ficamente para el tratamiento de artritis idiop�tica juvenil');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('12','12','a) Uso pedi�trico espec�ficamente en linfoma no Hodgkin B estados avanzados (Riesgo 3 y 4 incluye el estadio III del grupo de riesgo 2) con el protocolo BFM-90/95 b) Uso pedi�trico espec�ficamente en  linfoma No Hodgkin anapl�sico de c�lula grande en estados I y II con protocolo BFM 90/95.  c) Uso pedi�trico espec�ficamente en  linfoma anapl�sico en estados avanzados con el protocolo BFM 90/95. d) Uso pedi�trico espec�ficamente en linfoma no Hodgkin linfobl�stico');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('13','13','ADENOCARCINOMA HEPATOBILIAR AVANZADO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('14','14','ASOCIADO A OCTREOTIDE PARA HIPOGLICEMIA NEONATAL HIPERINSULINEMICA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('15','15','b) Terapia preventiva, profil�ctica y de la infecci�n por citomegalovirus en pacientes sometidos a trasplante alog�nico de progenitores hematopoy�ticos.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('16','16','b) Tratamiento preventivo de los trastornos linfoproliferativos postrasplante.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('17','17','b) Uso pedi�trico espec�ficamente en linfoma no Hodking');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('18','18','C�NCER CABEZA Y CUELLO LOCALMENTE AVANZADO Y RECURRENTE METASTASICO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('19','19','C�NCER DE C�RVIX RECURRENTE');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('20','20','C�NCER DE COLON ADULTOS EN SEGUNDA LINEA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('21','21','C�NCER DE ENDOMETRIO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('22','22','C�NCER DE ENDOMETRIO RECURRENTE E IRRESECABLE EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('23','23','C�NCER DE MAMA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('24','24','C�NCER DE MAMA ER POSITIVO, CON ALTO RIESGO DE RECURRENCIA LUEGO DE MANEJO EST�NDAR EN MUJERES PREMENOP�USICAS.  USO COMBINADO CON HORMONOTERAPIA ADYUVANTE');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('25','25','C�NCER DE OVARIO RECURRENTE O PROGRESI�N EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('26','26','C�NCER DE PULM�N DE C�LULAS PEQUE�AS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('27','27','C�NCER DE VEJIGA METASTASICO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('28','28','C�NCER DE V�A BILIAR EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('29','29','C�NCER ES�FAGO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('30','30','C�NCER G�STRICO SEGUNDA L�NEA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('31','31','C�NCER HEPATOBILIAR EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('32','32','C�NCER OV�RICO AVANZADO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('33','33','C�NCER TESTICULAR  ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('34','34','C�NCER TESTICULAR REFRACTARIO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('35','35','Condicionamiento BEAM (Carmustina - BCNU + Etoposido + Ara-C(citarabina) + Melfalan) para trasplante aut�logo en linfomas');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('36','36','Condicionamiento Busulfan-Fludarabina para Leucemia mieloide aguda, linfoide aguda, s�ndrome mielodispl�sico, leucemia mieloide cr�nica. El uso de Busulfan seguido de Fludarabina est� indicado como tratamiento de acondicionamiento previo al trasplante de c�lulas progenitoras hematopoy�ticas (TCPH) en pacientes adultos candidatos para un r�gimen de acondicionamiento de intensidad reducida.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('37','37','Condicionamiento Busulfan-Fludarabina previo al trasplante de c�lulas progenitoras hematopoy�ticas (TCPH) en pacientes adultos candidatos para un r�gimen de acondicionamiento de intensidad reducida.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('38','38','CONDICIONAMIENTO PARA APLASIA MEDULAR, EN COMBINACI�N CON FLUDARABINA Y TIMOGLOBULINA.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('39','39','CONDICIONAMIENTOS NO MIELOABLATIVOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('40','40','CONVULSIONES EN PEDIATR�A Y ESTATUS EPIL�PTICO EN SEGUNDA L�NEA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('41','41','E) TRATAMIENTO DE PACIENTES ADULTOS CON C�NCER DE CABEZA Y CUELLO, EN COMBINACI�N CON OTROS AGENTES QUIMIOTERAP�UTICOS. SE RECOMIENDA ADMINISTRAR PREMEDICACI�N ANTIEM�TICA AS� COMO HIDRATACI�N DEL PACIENTE. SE REQUIERE LA CONCOMITANCIA DE RADIOTERAPIA. SE DEBE EVALUAR LA FUNCI�N RENAL DEL PACIENTE ANTES DE INICIAR UN TRATAMIENTO CON CISPLATINO, YA QUE ESTE MEDICAMENTO PRESENTA NEFROTOXICIDAD ACUMULATIVA. DEBE SER PREPARADO EN CENTRALES DE MEZCLAS QUE CUENTE CON LA CERTIFICACI�N VIGENTE EN BUENAS PR�CTICAS DE ELABORACI�N EMITIDA POR INVIMA.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('42','42','En ni�os y adolescentes con alto riesgo de desarrollar el S�ndrome de Lisis Tumoral Aguda SLTA (LLA, LNH y LMA).');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('43','43','En poblacion pediatrica en:  a) Leucemia linfoide aguda. b) Tumor de Wilms. c) Tumor de Ewing.  d) Reticulosarcoma.  e) Linfosarcoma.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('44','44','ENFERMEDAD DE BEHCET EN PACIENTES ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('45','45','ENFERMEDAD DE PARKINSON EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('46','46','ESCLERODERMA REFRACTARIA A TRATAMIENTO CONVENCIONAL EN PACIENTES ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('47','47','FASE DE INDUCCI�N EN ADULTOS CON LEUCEMIA PROMIELOC�TICA Y NIVELES DE RIESGO INTERMEDIO Y ALTO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('48','48','Fase de inducci�n en ni�os y adolescentes con Leucemia Mieloide Aguda - LMA Promieloc�tica y niveles de riesgo intermedio y alto');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('49','49','GLIOBLASTOMA MULTIFORME EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('50','50','HEMANGIOPERICITOMA/TUMOR FIBROSO SOLITARIO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('51','51','HIPERTENSI�N PULMONAR SECUNDARIA EN POBLACI�N PEDI�TRICA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('52','52','HIPOGLICEMIA NEONATAL PERSISTENTE ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('53','53','ICTIOSIS GRAVES O REFRACTARIAS AL TRATAMIENTO EN ADULTOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('54','54','INDICADO COMO TERAPIA ALTERNATIVA EN PACIENTES CON TOXOPLASMOSIS CONG�NITA CUANDO NO HAY DISPONIBILIDAD DE SULFADIAZINA -PIRIMETAMINA ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('55','55','INDICADO EN MENORES DE 2 MESES PARA INFECCIONES BACTERIANAS SUSCEPTIBLES, NEUMON�A, V�AS URINARIAS, TEJIDOS BLANDOS Y HUESO, SEPSIS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('56','56','Indicado en neonatos con infecci�n cong�nita sintom�tica por citomegalovirus - CMV desde el nacimiento hasta los 2 meses. Prevenci�n de los 4 meses a los 6 a�os de enfermedad por CMV en trasplante renal y cardiaco.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('57','57','LEUCEMIA LINFOBL�STICA AGUDA FILADELFIA POSITIVA EN PRIMERA O SEGUNDA L�NEA EN COMBINACI�N CON QUIMIOTERAPIA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('58','58','LEUCEMIA LINFOBL�STICA AGUDA FILADELFIA POSITIVA EN SEGUNDA L�NEA EN COMBINACI�N CON QUIMIOTERAPIA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('59','59','LEUCEMIA LINFOIDE AGUDA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('60','60','LEUCEMIA LINFOIDE AGUDA EN ADULTOS Y NI�OS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('61','61','LEUCEMIA LINFOIDE AGUDA EN PEDIATRIA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('62','62','LEUCEMIA LINFOIDE CR�NICA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('63','63','LINFOMA HODGKIN');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('64','64','LINFOMAS EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('65','65','LINFOSARCOMA EN PEDIATRIA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('66','66','LUPUS ERITEMATOSO SIST�MICO REFRACTARIO A TRATAMIENTO CONVENCIONAL EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('67','67','MALIGNIDAD T�MICA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('68','68','MALIGNIDADES TIMICAS METASTASICAS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('69','69','MANEJO DE ANEMIA HEMOL�TICA AUTOINMUNE REFRACTARIA O DEPENDIENTE DE ESTEROIDES EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('70','70','Manejo de pacientes pedi�tricos con nefritis l�pica.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('71','71','MENINGITIS EN NI�OS SEG�N AISLAMIENTO MICROBIOL�GICO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('72','72','MIASTENIA GRAVE - MG, MODERADA A SEVERA , COMPROMISO BULBAR SEVERO O S�NTOMAS RESPIRATORIOS SEVEROS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('73','73','MICOSIS FUNGOIDE EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('74','74','MIELOPROLIFERATIVOS EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('75','75','MIOPAT�AS INFLAMATORIAS REFRACTARIAS A TRATAMIENTO CONVENCIONAL EN ADULTOS. NO INCLUYE DERMATOMIOSITIS SIST�MICA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('76','76','N�USEAS Y V�MITO ASOCIADO CON QUIMIOTERAPIA EN ADULTOS. NO RECOMENDAD EN PACIENTES ANCIANOS CON PSICOSIS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('77','77','NECROLISIS EPID�RMICA T�XICA EN PEDIATR�A');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('78','78','OSTEOPOROSIS PRIMARIA O SECUNDARIA EN PEDIATR�A');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('79','79','OSTEOSARCOMA REFRACTARIO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('80','80','PACIENTES CON ANEMIA APL�SICA CONSTITUCIONAL O IDIOP�TICA REFRACTARIOS O EN RECA�DA POSTERIOR AL TRATAMIENTO CON GLOBULINA ANTITIMOCITO, CON ALTO REQUERIMIENTO TRANSFUSIONAL, SEVERAMENTE PRETRATADOS Y QUE NO SON CANDIDATOS A LA REALIZACI�N DE UN TRASPLANTE ALOG�NICO DE PROGENITORES HEMATOPOY�TICOS EN ADULTOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('81','81','PREVENCI�N DE MENOPAUSIA TEMPRANA DURANTE QUIMIOTERAPIA PARA C�NCER DE MAMA CON RECEPTORES HORMONALES NEGATIVOS EN ESTADIO TEMPRANO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('82','82','PROFILAXIS DE ENFERMEDAD INJERTO CONTRA HU�SPED (EICH) EN PACIENTES ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('83','83','PROFILAXIS DE ENFERMEDAD INJERTO CONTRA HU�SPED AGUDA Y CR�NICA EN TRASPLANTE DE PROGENITORES HEMATOPOY�TICOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('84','84','PROFILAXIS DE LA ENFERMEDAD VENO OCLUSIVA EN PACIENTES SOMETIDOS A TRASPLANTE DE PROGENITORES HEMATOPOY�TICOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('85','85','PROFILAXIS NEUTROPENIA FEBRIL EN ADULTOS SOMETIDOS A TRATAMIENTO ONCOL�GICO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('86','86','Profilaxis post exposici�n B �ntrax inhalado en pacientes pedi�tricos menores de 18 a�os.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('87','87','PROFILAXIS Y TRATAMIENTO DE ENFERMEDAD INJERTO CONTRA HU�SPED (EICH) EN PACIENTES ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('88','88','RETICULOSARCOMA EN PEDIATRIA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('89','89','SARCOMA DE EWING');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('90','90','SARCOMA DE EWING, RECURRENTE O REFRACTARIO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('91','91','SARCOMA DE TEJIDOS BLANDOS METASTASICO O IRRESECABLE');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('92','92','SE RECOMIENDA COMO ESQUEMA ANTIBI�TICO INICIAL TANTO EN RECI�N NACIDOS PRETERMINOS Y A T�RMINO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('93','93','S�NDROME DE STEVENS- JOHNSON EN PEDIATR�A');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('94','94','S�NDROME MIELODISPL�SICOS EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('95','95','Suplencia mineralocorticoide en insuficiencia suprarrenal pedi�trica.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('96','96','TRASTORNO AFECTIVO ORG�NICO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('97','97','TRASTORNO DEPRESIVO ASOCIADO A ENFERMEDAD DE PARKINSON EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('98','98','TRASTORNO DEPRESIVO ASOCIADO A ENFERMEDAD DE PARKINSON EN ADULTOS. NO USAR CONCOMITANTEMENTE CON IMAOs');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('99','99','TRATAMIENTO COMO ULTIMA OPCI�N PARA LA PSICOSIS (ALUCINACIONES Y DELIRIO) EN PACIENTES CON PARKINSON');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('100','100','TRATAMIENTO DE C�NCER DE CANAL ANAL');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('101','101','TRATAMIENTO DE C�NCER DE TROMPA DE FALOPIO, Y CARCINOMA PRIMARIO DE SEROSAS ESTADIOS III, IV Y EN RECA�DA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('102','102','TRATAMIENTO DE CARCINOMA ADRENOCORTICAL AVANZADO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('103','103','TRATAMIENTO DE CARCINOMA BASOCELULAR MULTIC�NTRICO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('104','104','TRATAMIENTO DE ENFERMEDAD INJERTO CONTRA HU�SPED AGUDA Y CR�NICA EN TRASPLANTE DE PROGENITORES HEMATOPOY�TICOS EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('105','105','Tratamiento de infecci�n citomeg�lica en pacientes sometidos a trasplante alog�nico de progenitores hematopoy�ticos.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('106','106','TRATAMIENTO DE LA ENFERMEDAD INJERTO CONTRA HU�SPED REFRACTARIA A ESTEROIDES');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('107','107','TRATAMIENTO DE LA ENFERMEDAD INJERTO CONTRA HUESPED REFRACTARIA A ESTEROIDES EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('108','108','Tratamiento de la hipertensi�n severa durante el embarazo o inmediatamente despu�s del parto.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('109','109','TRATAMIENTO DE LA INFECCI�N POR CITOMEGALOVIRUS EN PACIENTES ADULTOS SOMETIDOS A TRASPLANTE ALOG�NICO DE PROGENITORES HEMATOPOY�TICOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('110','110','TRATAMIENTO DE LA NEUROMIELITIS �PTICA Y ESPECTRO DE NEUROMIELITIS �PTICA, USO EN PACIENTES PARA CONTROL DE RECA�DAS EN ADULTOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('111','111','TRATAMIENTO DE LEUCEMIA LINFOBL�STICA AGUDA FILADELFIA POSITIVA EN PRIMERA L�NEA EN COMBINACI�N CON QUIMIOTERAPIA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('112','112','TRATAMIENTO DE PACIENTES ADULTOS CON CANCER DE CERVIX EN COMBINACION CON OTROS AGENTES QUIMIOTERAPEUTICOS Y RADIOTERAPIA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('113','113','TRATAMIENTO DE PACIENTES ADULTOS CON CANCER DE PULMON, EN COMBINACION CON OTROS AGENTES QUIMIOTERAPEUTICOS TANTO PARA EL TRATAMIENTO DE PACIENTES CON CANCER DE PULMON DE CELULAS PEQUE�AS COMO PARA EL DE CANCER DE PULMON DE CELULAS NO PEQUE�AS, AVANZADO O METASTASICO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('114','114','TRATAMIENTO DE PACIENTES ADULTOS CON CANCER GASTRICO, EN COMBINACION CON OTROS AGENTES QUIMIOTERAPEUTICOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('115','115','Tratamiento de pacientes adultos con c�ncer no musculo invasivo de vejiga.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('116','116','Tratamiento de pacientes adultos con c�ncer uterino, tipo Leiomiosarcoma uterino - LMS inoperable, localmente avanzado, recurrente o metast�sico.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('117','117','Tratamiento de pacientes adultos con degeneraci�n macular.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('118','118','TRATAMIENTO DE PACIENTES ADULTOS CON MESOTELIOMA, EN COMBINACION CON OTROS AGENTES QUIMIOTERAPEUTICOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('119','119','TRATAMIENTO DE PACIENTES ADULTOS CON SARCOMA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('120','120','Tratamiento de pacientes adultos con Sarcoma de Ewing recurrente o progresivo. Est� recomendado en combinaci�n con Irinotecan, para el tratamiento de pacientes con Sarcoma de Ewing avanzado y refractario a terapia est�ndar. Se debe evaluar la funci�n hep�tica de los pacientes que reciben temozolomida antes del inicio, durante y al terminar su uso. Se recomienda el uso de terapia antiem�tica como premedicaci�n, debido a la potencia emetog�nica moderada.  Debe ser preparado en centrales de mezclas que cuente con la Certificaci�n vigente en Buenas Pr�cticas de Elaboraci�n emitida por Invima.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('121','121','Tratamiento de pacientes con c�ncer esof�gico, en combinaci�n con agentes quimioterap�uticos');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('122','122','TRATAMIENTO DE PACIENTES PEDI�TRICOS CON DIAGN�STICO DE LUPUS ERITEMATOSO SIST�MICO ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('123','123','TRATAMIENTO DE PACIENTES PEDI�TRICOS CON INCONTINENCIA URINARIA POR CONTRACCIONES NO INHIBIDAS , VEJIGA HIPERACTIVA, TRASTORNOS NEUROL�GICOS Y OTROS ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('124','124','Tratamiento de pacientes pedi�tricos con incontinencia urinaria por contracciones no inhibidas, vejiga hiperactiva, trastornos neurol�gicos y otros. No se recomienda el uso de oxibutinina en ni�os menores de 5 a�os debido a la falta de datos sobre seguridad y eficacia');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('125','125','Tratamiento de primera l�nea en VIH pedi�trico a partir de los 6 a�os. Se recomienda siempre administrar en un esquema acompa�ado con ritonavir.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('126','126','TRATAMIENTO DE PRIMERA L�NEA ENCEFALITIS AUTOINMUNE EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('127','127','TRATAMIENTO DE PRIMERA L�NEA FEOCROMOCITOMA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('128','128','TRATAMIENTO DE PRIMERA L�NEA PARAGANGLIOMA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('129','129','TRATAMIENTO DEL C�NCER DE C�RVIX, PERSISTENTE O RECURRENTE EN PACIENTES ADULTOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('130','130','TRATAMIENTO DEL C�NCER DE OVARIO RESISTENTE A PLATINOS EN PACIENTES ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('131','131','Tratamiento m�dico de la Enfermedad de Peyronie. Se debe informar al paciente que este medicamento puede causar dispepsia, n�useas, v�mitos, mareos o dolor de cabeza.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('132','132','TRATAMIENTO PARA VERRUGAS CUT�NEAS QUE NO RESPONDEN A TRATAMIENTO CONVENCIONAL EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('133','133','TRATAMIENTO PREVENTIVO DE LOS TRASTORNOS LINFOPROLIFERATIVOS POSTRASPLANTE EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('134','134','TUMOR DE EWING EN PEDIATRIA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('135','135','TUMOR DE WILMS EN PEDIATRIA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('136','136','TUMOR DESMOIDE O FIBROMATOSIS RECURRENTE O IRRESECABLE EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('137','137','TUMORES NEUROENDOCRINOS AVANZADOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('138','138','TUMORES NEUROENDOCRINOS EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('139','139','USO EN EL MANEJO DE ESCLEROSIS LOCALIZADA (MORFEA) EN PEDIATR�A');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('140','140','USO EN EL MANEJO DE ESCLEROSIS SIST�MICA EN PEDIATR�A');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('141','141','USO EN EL MANEJO DE PACIENTES ADULTOS CON  ESCLERODERMA  REFRACTARIO A TRATAMIENTO CONVENCIONAL');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('142','142','USO EN EL MANEJO DE PACIENTES ADULTOS CON  LUPUS ERITEMATOSO REFRACTARIO A TRATAMIENTO CONVENCIONAL ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('143','143','USO EN EL MANEJO DE PACIENTES ADULTOS CON MIOPAT�AS INFLAMATORIAS REFRACTARIA A TRATAMIENTO CONVENCIONAL ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('144','144','USO EN EL MANEJO DE PACIENTES ADULTOS CON S�NDROME ANTIFOSFOL�PIDO REFRACTARIA A TRATAMIENTO CONVENCIONAL  O S�NDROME ANTIFOSFOL�PIDO CATASTR�FICO  ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('145','145','USO EN EL MANEJO DE PACIENTES CON  ESCLERODERMA  REFRACTARIO A TRATAMIENTO CONVENCIONAL');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('146','146','USO EN EL MANEJO DE PACIENTES CON  LUPUS ERITEMATOSO REFRACTARIO A TRATAMIENTO CONVENCIONAL ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('147','147','USO EN EL MANEJO DE PACIENTES CON MIOPAT�AS INFLAMATORIAS REFRACTARIA A TRATAMIENTO CONVENCIONAL ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('148','148','USO EN EL MANEJO DE PACIENTES CON S�NDROME ANTIFOSFOL�PIDO REFRACTARIA A TRATAMIENTO CONVENCIONAL  O S�NDROME ANTIFOSFOL�PIDO CATASTR�FICO  ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('149','149','Uso en pacientes adultos con enfermedad de Behcet refractaria a tratamiento convencional.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('150','150','USO EN PACIENTES PEDI�TRICOS CON RAYNAUD SEVERO ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('151','151','USO EN PACIENTES PEDI�TRICOS CON S�NDROME HEMOFAGOC�TICO EN EL CONTEXTO DE ENFERMEDADES AUTOINMUNES');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('152','152','USO EN PACIENTES PEDI�TRICOS CON UVE�TIS ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('153','153','Uso pedi�trico de  formas farmac�uticas s�lidas orales en las indicaciones de s�ndromes convulsivos, en des�rden bipolar I y II como mantenimiento ,igualmente se sugiere emplear formas farmac�uticas liquidas orales en ni�os menores de 11 a�os. Debe tenerse cuidado en ni�os menores de 2 a�os por posibilidades de toxicidad hep�tica. NO UTILIZAR EN PROFILAXIS DE MIGRA�A EN NI�OS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('154','154','Uso pedi�trico en las indicaciones aprobadas por el INVIMA. Afecciones inflamatorias �ticas producidas por g�rmenes sensibles a la neomicina y colistina. La FDA autoriza el uso de colistina en combinaciones para el tratamiento de infecciones bacterianas superficiales del canal auditivo externo y tratamiento de mastoidectomias y cavidades fenestradas infectadas en mayores de 1 a�o de edad.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('155','155','USO PEDI�TRICO EN LAS INDICACIONES APROBADAS POR INVIMA. LINFOMA NO HODGKIN, COMO: COADYUVANTE EN EL TRATAMIENTO DE PACIENTES CON LINFOMA NO HODGKIN DE C�LULAS B "INDOLORO" (SIC), EN RECA�DA O RESISTENTE A LA QUIMIOTERAPIA. EN COMBINACI�N CON EL ESQUEMA CHOP PARA TRATAMIENTO DE PACIENTES CON LINFOMAS CON C�LULAS B GRANDES. TRATAMIENTO DE PRIMERA L�NEA EN PACIENTES CON LINFOMA NO HODGKIN INDOLENTE DE C�LULAS B, EN COMBINACI�N CON QUIMIOTERAPIA A BASE DE CVP. TERAPIA DE MANTENIMIENTO DEL LINFOMA NO HODGKIN FOLICULAR QUE HAYA RESPONDIDO AL TRATAMIENTO DE INDUCCI�N. TRATAMIENTO EN PRIMERA L�NEA DE LA LEUCEMIA LINFOC�TICA CR�NICA (LLC) EN ASOCIACI�N CON QUIMIOTERAPIA. EN ASOCIACI�N CON QUIMIOTERAPIA PARA EL TRATAMIENTO DE LEUCEMIA LINFOC�TICA CR�NICA (LLC) RECIDIVANTE O REFRACTARIA. TRATAMIENTO DE LA VASCULITIS ACTIVA GRAVE ASOCIADA A ANCA (ANTICUERPOS ANTICITOPLASMA DE LOS NEUTR�FILOS) EN COMBINACI�N CON GLUCOCORTICOIDES. EN ASOCIACI�N CON METROTEXATE EN EL TRATAMIENTO DE LA ARTRITIS REUMATOIDEA ACTIVA.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('156','156','USO PEDI�TRICO EN LAS INDICACIONES APROBADAS POR INVIMA. MONOTERAPIA EN CRISIS DE INICIO PARCIAL CON O SIN GENERALIZACI�N SECUNDARIA EN PACIENTES DE 16 A�OS DE EDAD CON EPILEPSIA RECIENTEMENTE DIAGNOSTICADA.  TERAPIA EN: CRISIS DE INICIO PARCIAL CON O SIN GENERALIZACI�N SECUNDARIA EN ADULTOS Y NI�OS DESDE LOS 4 A�OS DE EDAD CON EPILEPSIA, CRISIS MIOCL�NICA EN ADULTOS Y ADOLESCENTES DESDE 12 A�OS DE EDAD CON EPILEPSIA MIOCL�NICA JUVENIL, CONVULSI�N T�NICO CL�NICA GENERALIZADA PRIMARIA EN ADULTOS Y ADOLESCENTES DESDE 12 A�OS DE EDAD CON EPILEPSIA GENERALIZADA IDIOP�TICA.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('157','157','Uso pedi�trico en las indicaciones aprobadas por INVIMA:  Coadyuvante en el tratamiento de: a) Carcinoma de c�lulas escamosas. b) Carcinoma testicular. c) Linfoma.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('158','158','USO PEDI�TRICO ESPEC�FICAMENTE EN EL TRATAMIENTO DE ARTRITIS JUVENIL');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('159','159','USO PEDI�TRICO ESPEC�FICAMENTE EN EL TRATAMIENTO DE LINFOMAS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('160','160','Uso sublingual para profilaxis durante el alumbramiento cuando la oxitocina no est� disponible.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('1','1','a) Condicionamiento BEAC (Carmustina - BCNU + Etop�sido + Ara-C(citarabina) + Ciclofosfamida)  b) BEAM (Carmustina - BCNU + Etoposido + Ara-C(citarabina) + Melfalan) para linfoma no hodgkin o hodgkin');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('2','2','a) Indicado en neonatos con infecci�n cong�nita sintom�tica por citomegalovirus - CMV desde el nacimiento hasta los 2 meses. Prevenci�n de los 4 meses a los 6 a�os de enfermedad por CMV en trasplante renal y cardiaco. ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('3','3','a) Tratamiento de la enfermedad injerto contra hu�sped refractaria a esteroides.  ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('4','4','a) Tratamiento de pacientes adultos con adenocarcinoma de primario desconocido. Est� recomendado en combinaci�n con carboplatino y etop�sido oral para el tratamiento carcinomas de origen primario desconocido.  Antes del tratamiento con paclitaxel, los pacientes deben ser premedicados con corticosteroides y antihistam�nicos. El paclitaxel tiene un potencial emetog�nico bajo y es clasificado como sustancia irritante (potencial de extravasaci�n). Se requieren evaluaciones hematol�gicas peri�dicas. Debe administrarse antes de cisplatino cuando se usa en combinaci�n. Debe ser preparado en centrales de mezclas que cuente con la Certificaci�n vigente en Buenas Pr�cticas de Elaboraci�n emitida por Invima. b) Tratamiento de pacientes adultos con c�ncer de c�rvix. Antes del tratamiento con paclitaxel, los pacientes deben ser premedicados con corticosteroides y antihistam�nicos. El paclitaxel tiene un potencial emetog�nico bajo y es clasificado como sustancia irritante (potencial de extravasaci�n). Se requieren evaluaciones hematol�gicas peri�dicas. Debe administrarse antes de cisplatino cuando se usa en combinaci�n. Debe ser preparado en centrales de mezclas que cuente con la Certificaci�n vigente en Buenas Pr�cticas de Elaboraci�n emitida por Invima.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('5','5','a) Tratamiento de pacientes adultos con c�ncer de c�rvix en combinaci�n con otros agentes quimioterap�uticos y radioterapia. Se recomienda administrar premedicaci�n antiem�tica as� como hidrataci�n del paciente. Se debe evaluar la funci�n renal del paciente antes de iniciar un tratamiento con cisplatino, ya que este medicamento presenta nefrotoxicidad acumulativa. Debe ser preparado en centrales de mezclas que cuente con la Certificaci�n vigente en Buenas Pr�cticas de Elaboraci�n emitida por Invima. b) Tratamiento de pacientes adultos con c�ncer de pulm�n. En combinaci�n con otros agentes quimioterap�uticos tanto para el tratamiento de pacientes con c�ncer de pulm�n de c�lulas peque�as como para el de c�ncer de pulm�n de c�lulas no peque�as, avanzado o metast�sico.  Se recomienda administrar premedicaci�n antiem�tica as� como hidrataci�n del paciente. Se debe evaluar la funci�n renal del paciente antes de iniciar un tratamiento con cisplatino, ya que este medicamento presenta nefrotoxicidad acumulativa. Debe ser preparado en centrales de mezclas que cuente con la Certificaci�n vigente en Buenas Pr�cticas de Elaboraci�n emitida por Invima. c) Tratamiento de pacientes adultos con mesotelioma, en combinaci�n con otros agentes quimioterap�uticos. Se recomienda administrar premedicaci�n antiem�tica as� como hidrataci�n del paciente. Se debe evaluar la funci�n renal del paciente antes de iniciar un tratamiento con cisplatino, ya que este medicamento presenta nefrotoxicidad acumulativa. Debe ser preparado en centrales de mezclas que cuente con la Certificaci�n vigente en Buenas Pr�cticas de Elaboraci�n emitida por Invima. d) Tratamiento de pacientes adultos con c�ncer g�strico y/o esof�gico, en combinaci�n con otros agentes quimioterap�uticos. Se recomienda administrar premedicaci�n antiem�tica as� como hidrataci�n del paciente. Se requiere la concomitancia de radioterapia. Se debe evaluar la funci�n renal del paciente antes de iniciar un tratamiento con cisplatino, ya que este medicamento presenta nefrotoxicidad acumulativa. Debe ser preparado en centrales de mezclas que cuente con la Certificaci�n vigente en Buenas Pr�cticas de Elaboraci�n emitida por Invima.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('6','6','a) Tratamiento de pacientes adultos con cancer gastrico. Debe usarse en combinaci�n con otros agentes quimioterap�uticos, para el tratamiento de pacientes con c�ncer de est�mago avanzado.  Tiene un potencial emetog�nico moderado, por lo tanto se recomienda administrar premedicaci�n antiem�tica. Se debe evaluar la funci�n renal, hematol�gica y neurol�gica del paciente antes de iniciar un tratamiento. Se debe evitar el uso concomitante con otros medicamentos nefrot�xicos y otot�xicos. Se han reportado reacciones anafil�cticas, algunas fatales,  por lo tanto se recomienda el monitoreo continuo. En caso de una reacci�n de tipo anafil�ctico al oxaliplatino, la infusi�n debe ser inmediatamente descontinuada y se debe iniciar el tratamiento sintom�tico apropiado. Debe ser preparado en centrales de mezclas que cuente con la Certificaci�n vigente en Buenas Pr�cticas de Elaboraci�n emitida por Invima. b) Tratamiento de pacientes adultos con c�ncer pancre�tico avanzado. Debe usarse en combinaci�n con otros agentes quimioterap�uticos (Irinotecan, Fluorouracilo, �cido fol�nico). Tiene un potencial emetog�nico moderado, por lo tanto se recomienda administrar premedicaci�n antiem�tica.  Se debe evaluar la funci�n renal, hematol�gica y neurol�gica del paciente antes de iniciar un tratamiento. Se debe evitar el uso concomitante con otros medicamentos nefrot�xicos y otot�xicos. Se han reportado reacciones anafil�cticas, algunas fatales, por lo tanto se recomienda el monitoreo continuo. En caso de una reacci�n de tipo anafil�ctico al oxaliplatino, la infusi�n debe ser inmediatamente descontinuada y se debe iniciar el tratamiento sintom�tico apropiado. Debe ser preparado en centrales de mezclas que cuente con la Certificaci�n vigente en Buenas Pr�cticas de Elaboraci�n emitida por Invima.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('7','7','a) Uso en pacientes adultos con artritis gotosa refractaria a tratamientos en los que este contraindicado el uso de AINES y colchicina; como tratamiento a demanda o como dosis �nica. b) Uso en pacientes pedi�tricos con s�ndrome peri�dico asociado a las criopirinopat�as (CAPS).');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('8','8','a) Uso en pacientes adultos con enfermedad de Behcet refractaria a tratamiento convencional. b) Uso en pacientes adultos con enfermedad de Still refractario a tratamientos con metotrexate y esteroide.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('9','9','a) Uso en pacientes adultos con enfermedad de Behcet refractaria a tratamiento convencional. Se recomienda dosis entre 1 � 1,8mg al d�a y se puede administrar sin tener en cuenta las comidas. Debido a que es un medicamento con estrecho margen terap�utico, se debe informar al paciente que si presenta alg�n efecto secundario debe consultar a su m�dico e incluirse dentro de un programa de farmacovigilancia activa. b) Tratamiento m�dico de la Enfermedad de Peyronie. Debido a que es un medicamento con estrecho margen terap�utico, se debe informar al paciente que si presenta alg�n efecto secundario debe consultar a su m�dico e incluirse dentro de un programa de farmacovigilancia activa');
--------------------------------------------------------
--  DDL for Index WEBSERV_REF_PRE_IND_UNI_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OASIS4"."WEBSERV_REF_PRE_IND_UNI_PK" ON "OASIS4"."WEBSERV_REF_PRE_IND_UNI" ("ID_INUN") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4" ;
--------------------------------------------------------
--  DDL for Index REF_PRE_IND_UNI_CODIGO_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "OASIS4"."REF_PRE_IND_UNI_CODIGO_UK1" ON "OASIS4"."WEBSERV_REF_PRE_IND_UNI" ("CODIGO") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4" ;
--------------------------------------------------------
--  Constraints for Table WEBSERV_REF_PRE_IND_UNI
--------------------------------------------------------

  ALTER TABLE "OASIS4"."WEBSERV_REF_PRE_IND_UNI" MODIFY ("ID_INUN" NOT NULL ENABLE);
  ALTER TABLE "OASIS4"."WEBSERV_REF_PRE_IND_UNI" ADD CONSTRAINT "WEBSERV_REF_PRE_IND_UNI_PK" PRIMARY KEY ("ID_INUN")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4"  ENABLE;
  ALTER TABLE "OASIS4"."WEBSERV_REF_PRE_IND_UNI" ADD CONSTRAINT "REF_PRE_IND_UNI_CODIGO_UK1" UNIQUE ("CODIGO")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OASIS4"  ENABLE;
