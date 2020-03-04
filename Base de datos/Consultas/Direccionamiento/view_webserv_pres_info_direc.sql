 CREATE OR REPLACE VIEW  view_webserv_pres_info_direc  AS 
 --Al momento de direccionar se debe insertar un 1 en el campo "dieccionado"
select pp.NOPRESCRIPCION,pp.TIPOTEC,pp.CONORDEN,PP.TIPOIDPACIENTE,PP.NROIDPACIENTE,PP.CODAMBATE,DIR_IDDIRECCIONAMIENTO,DIR_ID,PAA.DESCRIPCION DESC_CODAMBATE,
       PP.CODSERTECAENTREGAR,DESC_CODSERTECAENTREGAR,c.GEOGRAPHICLOCATIONID CodMunEnt,c.Address DirPaciente,c.Regimen 
from (
--MEDICAMENTOS
select PP.NOPRESCRIPCION,'M' as TIPOTEC, PM.CONORDEN,PP.TIPOIDPACIENTE,PP.NROIDPACIENTE,PP.CODAMBATE,DIR_IDDIRECCIONAMIENTO,DIR_ID,
'' CODSERTECAENTREGAR,
DBMS_LOB.SUBSTR(DESCMEDPRINACT) DESC_CODSERTECAENTREGAR
from WEBSERV_PRES_PRES PP
join WEBSERV_PRES_MEDI PM ON PM.ID_PRES=PP.ID_PRES
--where DIR_IDDIRECCIONAMIENTO is null AND DIR_ID is null

union
--PROCEDIMIENTOS
select PP.NOPRESCRIPCION,'P' as TIPO_TEC, PPR.CONORDEN,PP.TIPOIDPACIENTE,PP.NROIDPACIENTE,PP.CODAMBATE,DIR_IDDIRECCIONAMIENTO,DIR_ID,
PPR.CODCUPS CODSERTECAENTREGAR,--16. Lista(CUPS)
CP.DESCRIPCION DESC_CODSERTECAENTREGAR
from WEBSERV_PRES_PRES PP
join WEBSERV_PRES_PROC PPR ON PPR.ID_PRES=PP.ID_PRES
LEFT JOIN WEBSERV_REF_PRE_CUPS CP ON CP.CODIGO=PPR.CODCUPS  
--where DIR_IDDIRECCIONAMIENTO is null AND DIR_ID is null

union
--PRODUCTOS NUTRICIONALES
select PP.NOPRESCRIPCION,'N' as TIPO_TEC, PPN.CONORDEN,PP.TIPOIDPACIENTE,PP.NROIDPACIENTE,PP.CODAMBATE,DIR_IDDIRECCIONAMIENTO,DIR_ID,
PPN.DESCPRODNUTR CODSERTECAENTREGAR,--20.Lista(productos nutricion)
PN.NOMBRE_COMERCIAL ||/*' - '|| PN.DESCR_GRUPO_NIVEL_1 ||*/' - '|| PN.PRESENTACION_COMERCIAL || PN.UNIDADES AS DESC_CODSERTECAENTREGAR
from WEBSERV_PRES_PRES PP
join WEBSERV_PRES_PROD_NUTR PPN ON PPN.ID_PRES=PP.ID_PRES
LEFT JOIN WEBSERV_REF_PRE_PR_NU PN ON PPN.DESCPRODNUTR=PN.CODIGO
--where DIR_IDDIRECCIONAMIENTO is null AND DIR_ID is null

union
--SERVICIOS COMPEMENTARIOS
select PP.NOPRESCRIPCION,'S' as TIPO_TEC, SC.CONORDEN,PP.TIPOIDPACIENTE,PP.NROIDPACIENTE,PP.CODAMBATE,DIR_IDDIRECCIONAMIENTO,DIR_ID,
SC.CODSERCOMP CODSERTECAENTREGAR,--23. Listas(Serv complem PR)  --(MIPRES)17. Listas Servicios complementarios PR
SP.DESCRIPCION ||' - '|| SC.DESCSERCOMP DESC_CODSERTECAENTREGAR
from WEBSERV_PRES_PRES PP
join WEBSERV_PRES_SERV_COMP SC ON SC.ID_PRES=PP.ID_PRES
LEFT JOIN WEBSERV_REF_PRE_SC_PR SP ON SC.CODSERCOMP=SP.CODIGO
--where DIR_IDDIRECCIONAMIENTO is null AND DIR_ID is null

union
--DISPOSITIVOS MEDICOS
select PP.NOPRESCRIPCION,'D' as TIPO_TEC, PD.CONORDEN,PP.TIPOIDPACIENTE,PP.NROIDPACIENTE,PP.CODAMBATE,DIR_IDDIRECCIONAMIENTO,DIR_ID,
PD.CODDISP CODSERTECAENTREGAR,--17.Lista(Tipos disp m�di)
TM.DESCRIPCION DESC_CODSERTECAENTREGAR
from WEBSERV_PRES_PRES PP
join WEBSERV_PRES_DISP PD ON PD.ID_PRES=PP.ID_PRES
LEFT JOIN WEBSERV_REF_PRE_TD_ME TM ON PD.CODDISP=TM.CODIGO
--where DIR_IDDIRECCIONAMIENTO is null AND DIR_ID is null
) pp
LEFT JOIN client c on c.clientid=PP.NROIDPACIENTE  and c.TypeDocumentId=PP.TIPOIDPACIENTE
LEFT JOIN WEBSERV_REF_PRE_AMB_ATE paa ON paa.CODIGO=pp.CODAMBATE;