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

   COMMENT ON TABLE "OASIS4"."WEBSERV_REF_PRE_IND_UNI"  IS 'Código Indicación';
REM INSERTING into OASIS4.WEBSERV_REF_PRE_IND_UNI
SET DEFINE OFF;
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('10','10','a) Uso en pacientes adultos con uveítis no infecciosa, intermedia, posterior y panuveítis refractaria a tratamiento convencional. b) Uso en pacientes adultos con enfermedad de Behcet refractaria a tratamiento convencional.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('11','11','a) Uso en pacientes pediátricos específicamente para el tratamiento de artritis idiopática juvenil');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('12','12','a) Uso pediátrico específicamente en linfoma no Hodgkin B estados avanzados (Riesgo 3 y 4 incluye el estadio III del grupo de riesgo 2) con el protocolo BFM-90/95 b) Uso pediátrico específicamente en  linfoma No Hodgkin anaplásico de célula grande en estados I y II con protocolo BFM 90/95.  c) Uso pediátrico específicamente en  linfoma anaplásico en estados avanzados con el protocolo BFM 90/95. d) Uso pediátrico específicamente en linfoma no Hodgkin linfoblástico');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('13','13','ADENOCARCINOMA HEPATOBILIAR AVANZADO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('14','14','ASOCIADO A OCTREOTIDE PARA HIPOGLICEMIA NEONATAL HIPERINSULINEMICA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('15','15','b) Terapia preventiva, profiláctica y de la infección por citomegalovirus en pacientes sometidos a trasplante alogénico de progenitores hematopoyéticos.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('16','16','b) Tratamiento preventivo de los trastornos linfoproliferativos postrasplante.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('17','17','b) Uso pediátrico específicamente en linfoma no Hodking');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('18','18','CÁNCER CABEZA Y CUELLO LOCALMENTE AVANZADO Y RECURRENTE METASTASICO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('19','19','CÁNCER DE CÉRVIX RECURRENTE');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('20','20','CÁNCER DE COLON ADULTOS EN SEGUNDA LINEA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('21','21','CÁNCER DE ENDOMETRIO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('22','22','CÁNCER DE ENDOMETRIO RECURRENTE E IRRESECABLE EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('23','23','CÁNCER DE MAMA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('24','24','CÁNCER DE MAMA ER POSITIVO, CON ALTO RIESGO DE RECURRENCIA LUEGO DE MANEJO ESTÁNDAR EN MUJERES PREMENOPÁUSICAS.  USO COMBINADO CON HORMONOTERAPIA ADYUVANTE');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('25','25','CÁNCER DE OVARIO RECURRENTE O PROGRESIÓN EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('26','26','CÁNCER DE PULMÓN DE CÉLULAS PEQUEÑAS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('27','27','CÁNCER DE VEJIGA METASTASICO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('28','28','CÁNCER DE VÍA BILIAR EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('29','29','CÁNCER ESÓFAGO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('30','30','CÁNCER GÁSTRICO SEGUNDA LÍNEA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('31','31','CÁNCER HEPATOBILIAR EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('32','32','CÁNCER OVÁRICO AVANZADO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('33','33','CÁNCER TESTICULAR  ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('34','34','CÁNCER TESTICULAR REFRACTARIO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('35','35','Condicionamiento BEAM (Carmustina - BCNU + Etoposido + Ara-C(citarabina) + Melfalan) para trasplante autólogo en linfomas');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('36','36','Condicionamiento Busulfan-Fludarabina para Leucemia mieloide aguda, linfoide aguda, síndrome mielodisplásico, leucemia mieloide crónica. El uso de Busulfan seguido de Fludarabina está indicado como tratamiento de acondicionamiento previo al trasplante de células progenitoras hematopoyéticas (TCPH) en pacientes adultos candidatos para un régimen de acondicionamiento de intensidad reducida.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('37','37','Condicionamiento Busulfan-Fludarabina previo al trasplante de células progenitoras hematopoyéticas (TCPH) en pacientes adultos candidatos para un régimen de acondicionamiento de intensidad reducida.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('38','38','CONDICIONAMIENTO PARA APLASIA MEDULAR, EN COMBINACIÓN CON FLUDARABINA Y TIMOGLOBULINA.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('39','39','CONDICIONAMIENTOS NO MIELOABLATIVOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('40','40','CONVULSIONES EN PEDIATRÍA Y ESTATUS EPILÉPTICO EN SEGUNDA LÍNEA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('41','41','E) TRATAMIENTO DE PACIENTES ADULTOS CON CÁNCER DE CABEZA Y CUELLO, EN COMBINACIÓN CON OTROS AGENTES QUIMIOTERAPÉUTICOS. SE RECOMIENDA ADMINISTRAR PREMEDICACIÓN ANTIEMÉTICA ASÍ COMO HIDRATACIÓN DEL PACIENTE. SE REQUIERE LA CONCOMITANCIA DE RADIOTERAPIA. SE DEBE EVALUAR LA FUNCIÓN RENAL DEL PACIENTE ANTES DE INICIAR UN TRATAMIENTO CON CISPLATINO, YA QUE ESTE MEDICAMENTO PRESENTA NEFROTOXICIDAD ACUMULATIVA. DEBE SER PREPARADO EN CENTRALES DE MEZCLAS QUE CUENTE CON LA CERTIFICACIÓN VIGENTE EN BUENAS PRÁCTICAS DE ELABORACIÓN EMITIDA POR INVIMA.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('42','42','En niños y adolescentes con alto riesgo de desarrollar el Síndrome de Lisis Tumoral Aguda SLTA (LLA, LNH y LMA).');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('43','43','En poblacion pediatrica en:  a) Leucemia linfoide aguda. b) Tumor de Wilms. c) Tumor de Ewing.  d) Reticulosarcoma.  e) Linfosarcoma.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('44','44','ENFERMEDAD DE BEHCET EN PACIENTES ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('45','45','ENFERMEDAD DE PARKINSON EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('46','46','ESCLERODERMA REFRACTARIA A TRATAMIENTO CONVENCIONAL EN PACIENTES ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('47','47','FASE DE INDUCCIÓN EN ADULTOS CON LEUCEMIA PROMIELOCÍTICA Y NIVELES DE RIESGO INTERMEDIO Y ALTO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('48','48','Fase de inducción en niños y adolescentes con Leucemia Mieloide Aguda - LMA Promielocítica y niveles de riesgo intermedio y alto');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('49','49','GLIOBLASTOMA MULTIFORME EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('50','50','HEMANGIOPERICITOMA/TUMOR FIBROSO SOLITARIO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('51','51','HIPERTENSIÓN PULMONAR SECUNDARIA EN POBLACIÓN PEDIÁTRICA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('52','52','HIPOGLICEMIA NEONATAL PERSISTENTE ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('53','53','ICTIOSIS GRAVES O REFRACTARIAS AL TRATAMIENTO EN ADULTOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('54','54','INDICADO COMO TERAPIA ALTERNATIVA EN PACIENTES CON TOXOPLASMOSIS CONGÉNITA CUANDO NO HAY DISPONIBILIDAD DE SULFADIAZINA -PIRIMETAMINA ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('55','55','INDICADO EN MENORES DE 2 MESES PARA INFECCIONES BACTERIANAS SUSCEPTIBLES, NEUMONÍA, VÍAS URINARIAS, TEJIDOS BLANDOS Y HUESO, SEPSIS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('56','56','Indicado en neonatos con infección congénita sintomática por citomegalovirus - CMV desde el nacimiento hasta los 2 meses. Prevención de los 4 meses a los 6 años de enfermedad por CMV en trasplante renal y cardiaco.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('57','57','LEUCEMIA LINFOBLÁSTICA AGUDA FILADELFIA POSITIVA EN PRIMERA O SEGUNDA LÍNEA EN COMBINACIÓN CON QUIMIOTERAPIA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('58','58','LEUCEMIA LINFOBLÁSTICA AGUDA FILADELFIA POSITIVA EN SEGUNDA LÍNEA EN COMBINACIÓN CON QUIMIOTERAPIA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('59','59','LEUCEMIA LINFOIDE AGUDA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('60','60','LEUCEMIA LINFOIDE AGUDA EN ADULTOS Y NIÑOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('61','61','LEUCEMIA LINFOIDE AGUDA EN PEDIATRIA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('62','62','LEUCEMIA LINFOIDE CRÓNICA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('63','63','LINFOMA HODGKIN');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('64','64','LINFOMAS EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('65','65','LINFOSARCOMA EN PEDIATRIA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('66','66','LUPUS ERITEMATOSO SISTÉMICO REFRACTARIO A TRATAMIENTO CONVENCIONAL EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('67','67','MALIGNIDAD TÍMICA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('68','68','MALIGNIDADES TIMICAS METASTASICAS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('69','69','MANEJO DE ANEMIA HEMOLÍTICA AUTOINMUNE REFRACTARIA O DEPENDIENTE DE ESTEROIDES EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('70','70','Manejo de pacientes pediátricos con nefritis lúpica.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('71','71','MENINGITIS EN NIÑOS SEGÚN AISLAMIENTO MICROBIOLÓGICO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('72','72','MIASTENIA GRAVE - MG, MODERADA A SEVERA , COMPROMISO BULBAR SEVERO O SÍNTOMAS RESPIRATORIOS SEVEROS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('73','73','MICOSIS FUNGOIDE EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('74','74','MIELOPROLIFERATIVOS EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('75','75','MIOPATÍAS INFLAMATORIAS REFRACTARIAS A TRATAMIENTO CONVENCIONAL EN ADULTOS. NO INCLUYE DERMATOMIOSITIS SISTÉMICA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('76','76','NÁUSEAS Y VÓMITO ASOCIADO CON QUIMIOTERAPIA EN ADULTOS. NO RECOMENDAD EN PACIENTES ANCIANOS CON PSICOSIS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('77','77','NECROLISIS EPIDÉRMICA TÓXICA EN PEDIATRÍA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('78','78','OSTEOPOROSIS PRIMARIA O SECUNDARIA EN PEDIATRÍA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('79','79','OSTEOSARCOMA REFRACTARIO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('80','80','PACIENTES CON ANEMIA APLÁSICA CONSTITUCIONAL O IDIOPÁTICA REFRACTARIOS O EN RECAÍDA POSTERIOR AL TRATAMIENTO CON GLOBULINA ANTITIMOCITO, CON ALTO REQUERIMIENTO TRANSFUSIONAL, SEVERAMENTE PRETRATADOS Y QUE NO SON CANDIDATOS A LA REALIZACIÓN DE UN TRASPLANTE ALOGÉNICO DE PROGENITORES HEMATOPOYÉTICOS EN ADULTOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('81','81','PREVENCIÓN DE MENOPAUSIA TEMPRANA DURANTE QUIMIOTERAPIA PARA CÁNCER DE MAMA CON RECEPTORES HORMONALES NEGATIVOS EN ESTADIO TEMPRANO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('82','82','PROFILAXIS DE ENFERMEDAD INJERTO CONTRA HUÉSPED (EICH) EN PACIENTES ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('83','83','PROFILAXIS DE ENFERMEDAD INJERTO CONTRA HUÉSPED AGUDA Y CRÓNICA EN TRASPLANTE DE PROGENITORES HEMATOPOYÉTICOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('84','84','PROFILAXIS DE LA ENFERMEDAD VENO OCLUSIVA EN PACIENTES SOMETIDOS A TRASPLANTE DE PROGENITORES HEMATOPOYÉTICOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('85','85','PROFILAXIS NEUTROPENIA FEBRIL EN ADULTOS SOMETIDOS A TRATAMIENTO ONCOLÓGICO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('86','86','Profilaxis post exposición B ántrax inhalado en pacientes pediátricos menores de 18 años.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('87','87','PROFILAXIS Y TRATAMIENTO DE ENFERMEDAD INJERTO CONTRA HUÉSPED (EICH) EN PACIENTES ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('88','88','RETICULOSARCOMA EN PEDIATRIA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('89','89','SARCOMA DE EWING');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('90','90','SARCOMA DE EWING, RECURRENTE O REFRACTARIO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('91','91','SARCOMA DE TEJIDOS BLANDOS METASTASICO O IRRESECABLE');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('92','92','SE RECOMIENDA COMO ESQUEMA ANTIBIÓTICO INICIAL TANTO EN RECIÉN NACIDOS PRETERMINOS Y A TÉRMINO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('93','93','SÍNDROME DE STEVENS- JOHNSON EN PEDIATRÍA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('94','94','SÍNDROME MIELODISPLÁSICOS EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('95','95','Suplencia mineralocorticoide en insuficiencia suprarrenal pediátrica.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('96','96','TRASTORNO AFECTIVO ORGÁNICO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('97','97','TRASTORNO DEPRESIVO ASOCIADO A ENFERMEDAD DE PARKINSON EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('98','98','TRASTORNO DEPRESIVO ASOCIADO A ENFERMEDAD DE PARKINSON EN ADULTOS. NO USAR CONCOMITANTEMENTE CON IMAOs');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('99','99','TRATAMIENTO COMO ULTIMA OPCIÓN PARA LA PSICOSIS (ALUCINACIONES Y DELIRIO) EN PACIENTES CON PARKINSON');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('100','100','TRATAMIENTO DE CÁNCER DE CANAL ANAL');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('101','101','TRATAMIENTO DE CÁNCER DE TROMPA DE FALOPIO, Y CARCINOMA PRIMARIO DE SEROSAS ESTADIOS III, IV Y EN RECAÍDA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('102','102','TRATAMIENTO DE CARCINOMA ADRENOCORTICAL AVANZADO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('103','103','TRATAMIENTO DE CARCINOMA BASOCELULAR MULTICÉNTRICO EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('104','104','TRATAMIENTO DE ENFERMEDAD INJERTO CONTRA HUÉSPED AGUDA Y CRÓNICA EN TRASPLANTE DE PROGENITORES HEMATOPOYÉTICOS EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('105','105','Tratamiento de infección citomegálica en pacientes sometidos a trasplante alogénico de progenitores hematopoyéticos.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('106','106','TRATAMIENTO DE LA ENFERMEDAD INJERTO CONTRA HUÉSPED REFRACTARIA A ESTEROIDES');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('107','107','TRATAMIENTO DE LA ENFERMEDAD INJERTO CONTRA HUESPED REFRACTARIA A ESTEROIDES EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('108','108','Tratamiento de la hipertensión severa durante el embarazo o inmediatamente después del parto.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('109','109','TRATAMIENTO DE LA INFECCIÓN POR CITOMEGALOVIRUS EN PACIENTES ADULTOS SOMETIDOS A TRASPLANTE ALOGÉNICO DE PROGENITORES HEMATOPOYÉTICOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('110','110','TRATAMIENTO DE LA NEUROMIELITIS ÓPTICA Y ESPECTRO DE NEUROMIELITIS ÓPTICA, USO EN PACIENTES PARA CONTROL DE RECAÍDAS EN ADULTOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('111','111','TRATAMIENTO DE LEUCEMIA LINFOBLÁSTICA AGUDA FILADELFIA POSITIVA EN PRIMERA LÍNEA EN COMBINACIÓN CON QUIMIOTERAPIA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('112','112','TRATAMIENTO DE PACIENTES ADULTOS CON CANCER DE CERVIX EN COMBINACION CON OTROS AGENTES QUIMIOTERAPEUTICOS Y RADIOTERAPIA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('113','113','TRATAMIENTO DE PACIENTES ADULTOS CON CANCER DE PULMON, EN COMBINACION CON OTROS AGENTES QUIMIOTERAPEUTICOS TANTO PARA EL TRATAMIENTO DE PACIENTES CON CANCER DE PULMON DE CELULAS PEQUEÑAS COMO PARA EL DE CANCER DE PULMON DE CELULAS NO PEQUEÑAS, AVANZADO O METASTASICO');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('114','114','TRATAMIENTO DE PACIENTES ADULTOS CON CANCER GASTRICO, EN COMBINACION CON OTROS AGENTES QUIMIOTERAPEUTICOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('115','115','Tratamiento de pacientes adultos con cáncer no musculo invasivo de vejiga.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('116','116','Tratamiento de pacientes adultos con cáncer uterino, tipo Leiomiosarcoma uterino - LMS inoperable, localmente avanzado, recurrente o metastásico.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('117','117','Tratamiento de pacientes adultos con degeneración macular.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('118','118','TRATAMIENTO DE PACIENTES ADULTOS CON MESOTELIOMA, EN COMBINACION CON OTROS AGENTES QUIMIOTERAPEUTICOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('119','119','TRATAMIENTO DE PACIENTES ADULTOS CON SARCOMA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('120','120','Tratamiento de pacientes adultos con Sarcoma de Ewing recurrente o progresivo. Está recomendado en combinación con Irinotecan, para el tratamiento de pacientes con Sarcoma de Ewing avanzado y refractario a terapia estándar. Se debe evaluar la función hepática de los pacientes que reciben temozolomida antes del inicio, durante y al terminar su uso. Se recomienda el uso de terapia antiemética como premedicación, debido a la potencia emetogénica moderada.  Debe ser preparado en centrales de mezclas que cuente con la Certificación vigente en Buenas Prácticas de Elaboración emitida por Invima.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('121','121','Tratamiento de pacientes con cáncer esofágico, en combinación con agentes quimioterapéuticos');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('122','122','TRATAMIENTO DE PACIENTES PEDIÁTRICOS CON DIAGNÓSTICO DE LUPUS ERITEMATOSO SISTÉMICO ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('123','123','TRATAMIENTO DE PACIENTES PEDIÁTRICOS CON INCONTINENCIA URINARIA POR CONTRACCIONES NO INHIBIDAS , VEJIGA HIPERACTIVA, TRASTORNOS NEUROLÓGICOS Y OTROS ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('124','124','Tratamiento de pacientes pediátricos con incontinencia urinaria por contracciones no inhibidas, vejiga hiperactiva, trastornos neurológicos y otros. No se recomienda el uso de oxibutinina en niños menores de 5 años debido a la falta de datos sobre seguridad y eficacia');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('125','125','Tratamiento de primera línea en VIH pediátrico a partir de los 6 años. Se recomienda siempre administrar en un esquema acompañado con ritonavir.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('126','126','TRATAMIENTO DE PRIMERA LÍNEA ENCEFALITIS AUTOINMUNE EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('127','127','TRATAMIENTO DE PRIMERA LÍNEA FEOCROMOCITOMA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('128','128','TRATAMIENTO DE PRIMERA LÍNEA PARAGANGLIOMA EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('129','129','TRATAMIENTO DEL CÁNCER DE CÉRVIX, PERSISTENTE O RECURRENTE EN PACIENTES ADULTOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('130','130','TRATAMIENTO DEL CÁNCER DE OVARIO RESISTENTE A PLATINOS EN PACIENTES ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('131','131','Tratamiento médico de la Enfermedad de Peyronie. Se debe informar al paciente que este medicamento puede causar dispepsia, náuseas, vómitos, mareos o dolor de cabeza.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('132','132','TRATAMIENTO PARA VERRUGAS CUTÁNEAS QUE NO RESPONDEN A TRATAMIENTO CONVENCIONAL EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('133','133','TRATAMIENTO PREVENTIVO DE LOS TRASTORNOS LINFOPROLIFERATIVOS POSTRASPLANTE EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('134','134','TUMOR DE EWING EN PEDIATRIA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('135','135','TUMOR DE WILMS EN PEDIATRIA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('136','136','TUMOR DESMOIDE O FIBROMATOSIS RECURRENTE O IRRESECABLE EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('137','137','TUMORES NEUROENDOCRINOS AVANZADOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('138','138','TUMORES NEUROENDOCRINOS EN ADULTOS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('139','139','USO EN EL MANEJO DE ESCLEROSIS LOCALIZADA (MORFEA) EN PEDIATRÍA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('140','140','USO EN EL MANEJO DE ESCLEROSIS SISTÉMICA EN PEDIATRÍA');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('141','141','USO EN EL MANEJO DE PACIENTES ADULTOS CON  ESCLERODERMA  REFRACTARIO A TRATAMIENTO CONVENCIONAL');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('142','142','USO EN EL MANEJO DE PACIENTES ADULTOS CON  LUPUS ERITEMATOSO REFRACTARIO A TRATAMIENTO CONVENCIONAL ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('143','143','USO EN EL MANEJO DE PACIENTES ADULTOS CON MIOPATÍAS INFLAMATORIAS REFRACTARIA A TRATAMIENTO CONVENCIONAL ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('144','144','USO EN EL MANEJO DE PACIENTES ADULTOS CON SÍNDROME ANTIFOSFOLÍPIDO REFRACTARIA A TRATAMIENTO CONVENCIONAL  O SÍNDROME ANTIFOSFOLÍPIDO CATASTRÓFICO  ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('145','145','USO EN EL MANEJO DE PACIENTES CON  ESCLERODERMA  REFRACTARIO A TRATAMIENTO CONVENCIONAL');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('146','146','USO EN EL MANEJO DE PACIENTES CON  LUPUS ERITEMATOSO REFRACTARIO A TRATAMIENTO CONVENCIONAL ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('147','147','USO EN EL MANEJO DE PACIENTES CON MIOPATÍAS INFLAMATORIAS REFRACTARIA A TRATAMIENTO CONVENCIONAL ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('148','148','USO EN EL MANEJO DE PACIENTES CON SÍNDROME ANTIFOSFOLÍPIDO REFRACTARIA A TRATAMIENTO CONVENCIONAL  O SÍNDROME ANTIFOSFOLÍPIDO CATASTRÓFICO  ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('149','149','Uso en pacientes adultos con enfermedad de Behcet refractaria a tratamiento convencional.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('150','150','USO EN PACIENTES PEDIÁTRICOS CON RAYNAUD SEVERO ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('151','151','USO EN PACIENTES PEDIÁTRICOS CON SÍNDROME HEMOFAGOCÍTICO EN EL CONTEXTO DE ENFERMEDADES AUTOINMUNES');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('152','152','USO EN PACIENTES PEDIÁTRICOS CON UVEÍTIS ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('153','153','Uso pediátrico de  formas farmacéuticas sólidas orales en las indicaciones de síndromes convulsivos, en desórden bipolar I y II como mantenimiento ,igualmente se sugiere emplear formas farmacéuticas liquidas orales en niños menores de 11 años. Debe tenerse cuidado en niños menores de 2 años por posibilidades de toxicidad hepática. NO UTILIZAR EN PROFILAXIS DE MIGRAÑA EN NIÑOS.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('154','154','Uso pediátrico en las indicaciones aprobadas por el INVIMA. Afecciones inflamatorias óticas producidas por gérmenes sensibles a la neomicina y colistina. La FDA autoriza el uso de colistina en combinaciones para el tratamiento de infecciones bacterianas superficiales del canal auditivo externo y tratamiento de mastoidectomias y cavidades fenestradas infectadas en mayores de 1 año de edad.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('155','155','USO PEDIÁTRICO EN LAS INDICACIONES APROBADAS POR INVIMA. LINFOMA NO HODGKIN, COMO: COADYUVANTE EN EL TRATAMIENTO DE PACIENTES CON LINFOMA NO HODGKIN DE CÉLULAS B "INDOLORO" (SIC), EN RECAÍDA O RESISTENTE A LA QUIMIOTERAPIA. EN COMBINACIÓN CON EL ESQUEMA CHOP PARA TRATAMIENTO DE PACIENTES CON LINFOMAS CON CÉLULAS B GRANDES. TRATAMIENTO DE PRIMERA LÍNEA EN PACIENTES CON LINFOMA NO HODGKIN INDOLENTE DE CÉLULAS B, EN COMBINACIÓN CON QUIMIOTERAPIA A BASE DE CVP. TERAPIA DE MANTENIMIENTO DEL LINFOMA NO HODGKIN FOLICULAR QUE HAYA RESPONDIDO AL TRATAMIENTO DE INDUCCIÓN. TRATAMIENTO EN PRIMERA LÍNEA DE LA LEUCEMIA LINFOCÍTICA CRÓNICA (LLC) EN ASOCIACIÓN CON QUIMIOTERAPIA. EN ASOCIACIÓN CON QUIMIOTERAPIA PARA EL TRATAMIENTO DE LEUCEMIA LINFOCÍTICA CRÓNICA (LLC) RECIDIVANTE O REFRACTARIA. TRATAMIENTO DE LA VASCULITIS ACTIVA GRAVE ASOCIADA A ANCA (ANTICUERPOS ANTICITOPLASMA DE LOS NEUTRÓFILOS) EN COMBINACIÓN CON GLUCOCORTICOIDES. EN ASOCIACIÓN CON METROTEXATE EN EL TRATAMIENTO DE LA ARTRITIS REUMATOIDEA ACTIVA.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('156','156','USO PEDIÁTRICO EN LAS INDICACIONES APROBADAS POR INVIMA. MONOTERAPIA EN CRISIS DE INICIO PARCIAL CON O SIN GENERALIZACIÓN SECUNDARIA EN PACIENTES DE 16 AÑOS DE EDAD CON EPILEPSIA RECIENTEMENTE DIAGNOSTICADA.  TERAPIA EN: CRISIS DE INICIO PARCIAL CON O SIN GENERALIZACIÓN SECUNDARIA EN ADULTOS Y NIÑOS DESDE LOS 4 AÑOS DE EDAD CON EPILEPSIA, CRISIS MIOCLÓNICA EN ADULTOS Y ADOLESCENTES DESDE 12 AÑOS DE EDAD CON EPILEPSIA MIOCLÓNICA JUVENIL, CONVULSIÓN TÓNICO CLÓNICA GENERALIZADA PRIMARIA EN ADULTOS Y ADOLESCENTES DESDE 12 AÑOS DE EDAD CON EPILEPSIA GENERALIZADA IDIOPÁTICA.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('157','157','Uso pediátrico en las indicaciones aprobadas por INVIMA:  Coadyuvante en el tratamiento de: a) Carcinoma de células escamosas. b) Carcinoma testicular. c) Linfoma.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('158','158','USO PEDIÁTRICO ESPECÍFICAMENTE EN EL TRATAMIENTO DE ARTRITIS JUVENIL');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('159','159','USO PEDIÁTRICO ESPECÍFICAMENTE EN EL TRATAMIENTO DE LINFOMAS');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('160','160','Uso sublingual para profilaxis durante el alumbramiento cuando la oxitocina no esté disponible.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('1','1','a) Condicionamiento BEAC (Carmustina - BCNU + Etopósido + Ara-C(citarabina) + Ciclofosfamida)  b) BEAM (Carmustina - BCNU + Etoposido + Ara-C(citarabina) + Melfalan) para linfoma no hodgkin o hodgkin');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('2','2','a) Indicado en neonatos con infección congénita sintomática por citomegalovirus - CMV desde el nacimiento hasta los 2 meses. Prevención de los 4 meses a los 6 años de enfermedad por CMV en trasplante renal y cardiaco. ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('3','3','a) Tratamiento de la enfermedad injerto contra huésped refractaria a esteroides.  ');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('4','4','a) Tratamiento de pacientes adultos con adenocarcinoma de primario desconocido. Está recomendado en combinación con carboplatino y etopósido oral para el tratamiento carcinomas de origen primario desconocido.  Antes del tratamiento con paclitaxel, los pacientes deben ser premedicados con corticosteroides y antihistamínicos. El paclitaxel tiene un potencial emetogénico bajo y es clasificado como sustancia irritante (potencial de extravasación). Se requieren evaluaciones hematológicas periódicas. Debe administrarse antes de cisplatino cuando se usa en combinación. Debe ser preparado en centrales de mezclas que cuente con la Certificación vigente en Buenas Prácticas de Elaboración emitida por Invima. b) Tratamiento de pacientes adultos con cáncer de cérvix. Antes del tratamiento con paclitaxel, los pacientes deben ser premedicados con corticosteroides y antihistamínicos. El paclitaxel tiene un potencial emetogénico bajo y es clasificado como sustancia irritante (potencial de extravasación). Se requieren evaluaciones hematológicas periódicas. Debe administrarse antes de cisplatino cuando se usa en combinación. Debe ser preparado en centrales de mezclas que cuente con la Certificación vigente en Buenas Prácticas de Elaboración emitida por Invima.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('5','5','a) Tratamiento de pacientes adultos con cáncer de cérvix en combinación con otros agentes quimioterapéuticos y radioterapia. Se recomienda administrar premedicación antiemética así como hidratación del paciente. Se debe evaluar la función renal del paciente antes de iniciar un tratamiento con cisplatino, ya que este medicamento presenta nefrotoxicidad acumulativa. Debe ser preparado en centrales de mezclas que cuente con la Certificación vigente en Buenas Prácticas de Elaboración emitida por Invima. b) Tratamiento de pacientes adultos con cáncer de pulmón. En combinación con otros agentes quimioterapéuticos tanto para el tratamiento de pacientes con cáncer de pulmón de células pequeñas como para el de cáncer de pulmón de células no pequeñas, avanzado o metastásico.  Se recomienda administrar premedicación antiemética así como hidratación del paciente. Se debe evaluar la función renal del paciente antes de iniciar un tratamiento con cisplatino, ya que este medicamento presenta nefrotoxicidad acumulativa. Debe ser preparado en centrales de mezclas que cuente con la Certificación vigente en Buenas Prácticas de Elaboración emitida por Invima. c) Tratamiento de pacientes adultos con mesotelioma, en combinación con otros agentes quimioterapéuticos. Se recomienda administrar premedicación antiemética así como hidratación del paciente. Se debe evaluar la función renal del paciente antes de iniciar un tratamiento con cisplatino, ya que este medicamento presenta nefrotoxicidad acumulativa. Debe ser preparado en centrales de mezclas que cuente con la Certificación vigente en Buenas Prácticas de Elaboración emitida por Invima. d) Tratamiento de pacientes adultos con cáncer gástrico y/o esofágico, en combinación con otros agentes quimioterapéuticos. Se recomienda administrar premedicación antiemética así como hidratación del paciente. Se requiere la concomitancia de radioterapia. Se debe evaluar la función renal del paciente antes de iniciar un tratamiento con cisplatino, ya que este medicamento presenta nefrotoxicidad acumulativa. Debe ser preparado en centrales de mezclas que cuente con la Certificación vigente en Buenas Prácticas de Elaboración emitida por Invima.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('6','6','a) Tratamiento de pacientes adultos con cancer gastrico. Debe usarse en combinación con otros agentes quimioterapéuticos, para el tratamiento de pacientes con cáncer de estómago avanzado.  Tiene un potencial emetogénico moderado, por lo tanto se recomienda administrar premedicación antiemética. Se debe evaluar la función renal, hematológica y neurológica del paciente antes de iniciar un tratamiento. Se debe evitar el uso concomitante con otros medicamentos nefrotóxicos y ototóxicos. Se han reportado reacciones anafilácticas, algunas fatales,  por lo tanto se recomienda el monitoreo continuo. En caso de una reacción de tipo anafiláctico al oxaliplatino, la infusión debe ser inmediatamente descontinuada y se debe iniciar el tratamiento sintomático apropiado. Debe ser preparado en centrales de mezclas que cuente con la Certificación vigente en Buenas Prácticas de Elaboración emitida por Invima. b) Tratamiento de pacientes adultos con cáncer pancreático avanzado. Debe usarse en combinación con otros agentes quimioterapéuticos (Irinotecan, Fluorouracilo, Ácido folínico). Tiene un potencial emetogénico moderado, por lo tanto se recomienda administrar premedicación antiemética.  Se debe evaluar la función renal, hematológica y neurológica del paciente antes de iniciar un tratamiento. Se debe evitar el uso concomitante con otros medicamentos nefrotóxicos y ototóxicos. Se han reportado reacciones anafilácticas, algunas fatales, por lo tanto se recomienda el monitoreo continuo. En caso de una reacción de tipo anafiláctico al oxaliplatino, la infusión debe ser inmediatamente descontinuada y se debe iniciar el tratamiento sintomático apropiado. Debe ser preparado en centrales de mezclas que cuente con la Certificación vigente en Buenas Prácticas de Elaboración emitida por Invima.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('7','7','a) Uso en pacientes adultos con artritis gotosa refractaria a tratamientos en los que este contraindicado el uso de AINES y colchicina; como tratamiento a demanda o como dosis única. b) Uso en pacientes pediátricos con síndrome periódico asociado a las criopirinopatías (CAPS).');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('8','8','a) Uso en pacientes adultos con enfermedad de Behcet refractaria a tratamiento convencional. b) Uso en pacientes adultos con enfermedad de Still refractario a tratamientos con metotrexate y esteroide.');
Insert into OASIS4.WEBSERV_REF_PRE_IND_UNI (ID_INUN,CODIGO,DESCRIPCION) values ('9','9','a) Uso en pacientes adultos con enfermedad de Behcet refractaria a tratamiento convencional. Se recomienda dosis entre 1 – 1,8mg al día y se puede administrar sin tener en cuenta las comidas. Debido a que es un medicamento con estrecho margen terapéutico, se debe informar al paciente que si presenta algún efecto secundario debe consultar a su médico e incluirse dentro de un programa de farmacovigilancia activa. b) Tratamiento médico de la Enfermedad de Peyronie. Debido a que es un medicamento con estrecho margen terapéutico, se debe informar al paciente que si presenta algún efecto secundario debe consultar a su médico e incluirse dentro de un programa de farmacovigilancia activa');
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
