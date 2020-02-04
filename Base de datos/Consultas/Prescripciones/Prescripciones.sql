--Reporte general y de prescripciones

SELECT 
ID_PRES,
NOPRESCRIPCION,
PP.REPO_PERIODO AS PERIODO_WEBSERVICE,
to_char(FPRESCRIPCION,'DD/MM/YYYY') FPRESCRIPCION,
DECODE(HPRESCRIPCION, NULL,'NO EXISTE',HPRESCRIPCION) HPRESCRIPCION,
DECODE(CODHABIPS, NULL,'NO EXISTE',CODHABIPS) CODHABIPS,
DECODE(TIPOIDIPS, NULL,'NO EXISTE',TIPOIDIPS) TIPOIDIPS,
DECODE(NROIDIPS, NULL,'NO EXISTE',NROIDIPS) NROIDIPS,
DECODE(CODDANEMUNIPS, NULL,'NO EXISTE',CODDANEMUNIPS) CODDANEMUNIPS,
DECODE(DIRSEDEIPS, NULL,'NO EXISTE',DIRSEDEIPS) DIRSEDEIPS,
DECODE(TELSEDEIPS, NULL,'NO EXISTE',TELSEDEIPS) TELSEDEIPS,
DECODE(TIPOIDPROF, NULL,'NO EXISTE',TIPOIDPROF) TIPOIDPROF,
DECODE(NUMIDPROF, NULL,'NO EXISTE',NUMIDPROF) NUMIDPROF,
DECODE(PNPROFS, NULL,'NO EXISTE',PNPROFS) PNPROFS,
DECODE(SNPROFS, NULL,'NO EXISTE',SNPROFS) SNPROFS,
DECODE(PAPROFS, NULL,'NO EXISTE',PAPROFS) PAPROFS,
DECODE(SAPROFS, NULL,'NO EXISTE',SAPROFS) SAPROFS,
DECODE(REGPROFS, NULL,'NO EXISTE',REGPROFS) REGPROFS,
DECODE(TIPOIDPACIENTE, NULL,'NO EXISTE',TIPOIDPACIENTE) TIPOIDPACIENTE,
DECODE(NROIDPACIENTE, NULL,'NO EXISTE',NROIDPACIENTE) NROIDPACIENTE,
DECODE(PNPACIENTE, NULL,'.',PNPACIENTE) PNPACIENTE,
DECODE(SNPACIENTE, NULL,'.',SNPACIENTE)SNPACIENTE,
DECODE(PAPACIENTE, NULL,'.',PAPACIENTE) PAPACIENTE,
DECODE(SAPACIENTE, NULL,'.',SAPACIENTE) SAPACIENTE,
DECODE (UB.NOM_MPIO,NULL,'NO EXISTE',UB.NOM_MPIO) MUNICIPIO,
DECODE(UB.NOM_DPTO,NULL,'NO EXISTE',UB.NOM_DPTO)DEPARTAMENTO,

DECODE(PP.CODAMBATE,null,'NO EXISTE',CODAMBATE) AS CODAMBATE,
DECODE(PAA.DESCRIPCION,NULL,'NO EXISTE',PAA.DESCRIPCION) AS DESC_CODAMBATE,

DECODE(PP.REFAMBATE,null,'NO EXISTE',REFAMBATE) AS REFAMBATE,
DECODE(REFAMBATE,0,'NO',1,'SI','NO EXISTE') as DESC_REFAMBATE,

DECODE(PP.ENFHUERFANA,null,'NO EXISTE',ENFHUERFANA) AS ENFHUERFANA,
DECODE(ENFHUERFANA,0,'NO',1,'SI','NO EXISTE') as DESC_ENFHUERFANA,

DECODE(CODENFHUERFANA,NULL,'NO EXISTE',CODENFHUERFANA) AS CODENFHUERFANA,
DECODE( EH.DESCRIPCION,NULL,'NO EXISTE',EH.DESCRIPCION) AS DESC_CODENFHUERFANA,

DECODE(ENFHUERFANADX,NULL,'NO EXISTE',ENFHUERFANADX) AS ENFHUERFANADX,
DECODE(ENFHUERFANADX,0,'NO',1,'SI','NO EXISTE') as DESC_ENFHUERFANADX,

DECODE(CODDXPPAL,NULL,'NO EXISTE',CODDXPPAL) AS CODDXPPAL,--4. Lista(Prec-CIE-10)
DECODE(CIE_CODDXPPAL.DESCRIPCION,NULL,'NO EXISTE',CIE_CODDXPPAL.DESCRIPCION) AS DESC_CODDXPPAL, 

DECODE(CODDXREL1,NULL,'NO EXISTE',CODDXREL1) AS CODDXREL1,--4. Lista(Prec-CIE-10)
DECODE(CIE_CODDXREL1.DESCRIPCION,NULL,'NO EXISTE',CIE_CODDXREL1.DESCRIPCION) AS DESC_CODDXREL1,

DECODE(CODDXREL2,NULL,'NO EXISTE',CODDXREL2) AS CODDXREL2,--4. Lista(Prec-CIE-10)
DECODE(CIE_CODDXREL2.DESCRIPCION,NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXREL2.DESCRIPCION)) AS DESC_CODDXREL2,

DECODE(SOPNUTRICIONAL, NULL,'NO EXISTE',SOPNUTRICIONAL) SOPNUTRICIONAL,
DECODE(CODEPS, NULL,'NO EXISTE',CODEPS) CODEPS,
DECODE(PE.DESCRIPCION,NULL,'NO EXISTE',PE.DESCRIPCION) DESC_CODEPS,
DECODE(TIPOIDMADREPACIENTE, NULL,'NO EXISTE',TIPOIDMADREPACIENTE) TIPOIDMADREPACIENTE,
DECODE(NROIDMADREPACIENTE, NULL,'NO EXISTE',NROIDMADREPACIENTE) NROIDMADREPACIENTE,

DECODE(TIPOTRANSC, NULL,'NO EXISTE',TIPOTRANSC) TIPOTRANSC,--5 Lista(Presc-TipoTransc)
DECODE(TT.DESCRIPCION,NULL,'NO EXISTE',TT.DESCRIPCION) AS DESC_TIPOTRANSC,

DECODE(TIPOIDDONANTEVIVO, NULL,'NO EXISTE',TIPOIDDONANTEVIVO) TIPOIDDONANTEVIVO,
DECODE(NROIDDONANTEVIVO, NULL,'NO EXISTE',NROIDDONANTEVIVO) NROIDDONANTEVIVO,
DECODE(ESTPRES, NULL,'NO EXISTE',ESTPRES) ESTPRES

FROM WEBSERV_PRES_PRES pp
LEFT JOIN WEBSERV_REF_PRE_AMB_ATE paa
ON paa.CODIGO=pp.CODAMBATE
LEFT JOIN WEBSERV_REF_PRE_ENF_UER EH ON EH.CODIGO=pp.CODENFHUERFANA
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXPPAL ON CIE_CODDXPPAL.CODIGO=PP.CODDXPPAL
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXREL1 ON CIE_CODDXREL1.CODIGO=PP.CODDXREL1
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXREL2 ON CIE_CODDXREL2.CODIGO=PP.CODDXREL2
LEFT JOIN WEBSERV_REF_PRE_TI_TR TT ON PP.TIPOTRANSC=TT.CODIGO
LEFT JOIN WEBSERV_REF_PRE_EPS PE ON PP.CODEPS=PE.CODIGO
LEFT JOIN (SELECT B.ESTADO,B.TIDPODOCUMENTO,B.DOCUMENTO, B.DEPARTAMENTO, B.MUNICIPIO,B.NOM_MPIO,B.NOM_DPTO,B.MES 
           FROM ZZZ_BDUAHISSUB@PDBLCSTBY01 B 
           WHERE B.MES IN (SELECT MAX(MES) 
                           FROM ZZZ_BDUAHISSUB@PDBLCSTBY01)) UB ON UB.TIDPODOCUMENTO=PP.TIPOIDPACIENTE AND UB.DOCUMENTO=PP.NROIDPACIENTE
where  pp.REPO_SERV_ID=3 and pp.REPO_TIRE_ID=2 and pp.REPO_PERIODO BETWEEN '01/01/2020' AND '05/01/2020' ;

/*
22434608	BARANOA	ATLANTICO
802449	SOLEDAD	ATLANTICO
802449	SOLEDAD	ATLANTICO
3834692	SOLEDAD	ATLANTICO*/

----------------------------------------------------------------------------------------------------------------------------
/*Consulta anterior con CLOB
SELECT 
  ID_PRES,
  NOPRESCRIPCION,
  FPRESCRIPCION,
  HPRESCRIPCION,
  CODHABIPS,
  TIPOIDIPS,
  NROIDIPS,
  CODDANEMUNIPS,
  DIRSEDEIPS,
  TELSEDEIPS,
  TIPOIDPROF,
  NUMIDPROF,
  PNPROFS,
  SNPROFS,
  PAPROFS,
  SAPROFS,
  REGPROFS,
  TIPOIDPACIENTE,
  NROIDPACIENTE,
  PNPACIENTE,
  SNPACIENTE,
  PAPACIENTE,
  SAPACIENTE,

  DECODE(PP.CODAMBATE,null,'NO EXISTE',CODAMBATE) AS CODAMBATE,
  DECODE( PAA.DESCRIPCION,NULL,'NO EXISTE',PAA.DESCRIPCION) AS DESC_CODAMBATE,
  
  DECODE(PP.REFAMBATE,null,'NO EXISTE',REFAMBATE) AS REFAMBATE,
  DECODE(REFAMBATE,0,'NO',1,'SI','NO EXISTE') as DESC_REFAMBATE,
  
  DECODE(PP.ENFHUERFANA,null,'NO EXISTE',ENFHUERFANA) AS ENFHUERFANA,
  DECODE(ENFHUERFANA,0,'NO',1,'SI','NO EXISTE') as DESC_ENFHUERFANA,
  
  DECODE(CODENFHUERFANA,NULL,'NO EXISTE',CODENFHUERFANA) AS CODENFHUERFANA,
  DECODE( EH.DESCRIPCION,NULL,'NO EXISTE',EH.DESCRIPCION) AS DESC_CODENFHUERFANA,
  --DECODE(dbms_lob.substr( EH.DESCRIPCION, 4000, 1 ) ,NULL,'NO EXISTE') AS DESC_CODENFHUERFANA,
  
  DECODE(ENFHUERFANADX,NULL,'NO EXISTE',ENFHUERFANADX) AS ENFHUERFANADX,
  DECODE(ENFHUERFANADX,0,'NO',1,'SI','NO EXISTE') as DESC_ENFHUERFANADX,
  
  DECODE(CODDXPPAL,NULL,'NO EXISTE',CODDXPPAL) AS CODDXPPAL,--4. Lista(Prec-CIE-10)
  DECODE(dbms_lob.substr(CIE_CODDXPPAL.DESCRIPCION),NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXPPAL.DESCRIPCION)) AS DESC_CODDXPPAL, 
  --DECODE(dbms_lob.substr((SELECT DESCRIPCION FROM WEBSERV_REF_PRE_CIE_10 WHERE CODIGO=CODDXPPAL), 4000, 1 ),NULL,'NO EXISTE') AS DESC_CODDXPPAL,
  
  DECODE(CODDXREL1,NULL,'NO EXISTE',CODDXREL1) AS CODDXREL1,--4. Lista(Prec-CIE-10)
  DECODE(dbms_lob.substr(CIE_CODDXREL1.DESCRIPCION),NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXREL1.DESCRIPCION)) AS DESC_CODDXREL1,
  --DECODE(dbms_lob.substr((SELECT DESCRIPCION FROM WEBSERV_REF_PRE_CIE_10 WHERE CODIGO=CODDXREL1), 4000, 1 ),NULL,'NO EXISTE') AS DESC_CODDXREL1,
  
  DECODE(CODDXREL2,NULL,'NO EXISTE',CODDXREL2) AS CODDXREL2,--4. Lista(Prec-CIE-10)
  DECODE(dbms_lob.substr(CIE_CODDXREL2.DESCRIPCION),NULL,'NO EXISTE',dbms_lob.substr(CIE_CODDXREL2.DESCRIPCION)) AS DESC_CODDXREL2,
  --DECODE(dbms_lob.substr((SELECT DESCRIPCION FROM WEBSERV_REF_PRE_CIE_10 WHERE CODIGO=CODDXREL2), 4000, 1 ),NULL,'NO EXISTE') AS DESC_CODDXREL2,
  
  SOPNUTRICIONAL,
  CODEPS,
  TIPOIDMADREPACIENTE,
  NROIDMADREPACIENTE,
  TIPOTRANSC,--5 Lista(Presc-TipoTransc)
 -- DECODE(DBMS_LOB.SUBSTR(TT.DESCRIPCION),NULL,'NO EXISTE',DBMS_LOB.SUBSTR(TT.DESCRIPCION)) AS DESC_TIPOTRANSC,
  TIPOIDDONANTEVIVO,
  NROIDDONANTEVIVO,
  ESTPRES
FROM WEBSERV_PRES_PRES pp
LEFT JOIN WEBSERV_REF_PRE_AMB_ATE paa
ON paa.CODIGO=pp.CODAMBATE
LEFT JOIN WEBSERV_REF_PRE_ENF_UER EH ON EH.CODIGO=pp.CODENFHUERFANA
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXPPAL ON CIE_CODDXPPAL.CODIGO=PP.CODDXPPAL
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXREL1 ON CIE_CODDXREL1.CODIGO=PP.CODDXREL1
LEFT JOIN WEBSERV_REF_PRE_CIE_10 CIE_CODDXREL2 ON CIE_CODDXREL2.CODIGO=PP.CODDXREL2
LEFT JOIN WEBSERV_REF_PRE_TI_TR TT ON PP.TIPOTRANSC=TT.CODIGO
where  pp.REPO_SERV_ID=3 and pp.REPO_TISE_ID=2 and pp.REPO_PERIODO='01/01/2020';*/