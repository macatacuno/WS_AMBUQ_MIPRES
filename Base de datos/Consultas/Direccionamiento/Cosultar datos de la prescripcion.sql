--1. Consultar los tipos de tecnologia de una prescripcion
select 
distinct(TIPOTEC) TIPOTEC,
decode(TIPOTEC,'P','PROCEDIMIENTO','M','MEDICAMENTO','N','PRODUCTOS NUTRICIONALES','S','SERVICIOS COMPEMENTARIOS','D','DISPOSITIVOS MEDICOS',TIPOTEC)DESC_TIPOTEC
from view_webserv_pres_info_direc
where  NOPRESCRIPCION='20200206186017293511';


--2. Consultar los codigos de las ordenes de una prescripcion(informacion de los tipos de tecnologia)
select conorden
from view_webserv_pres_info_direc
where  NOPRESCRIPCION='20200206186017293511'
and TIPOTEC='P';


--3. Consulta para cargar los datos principales en el formulario
select 
NOPRESCRIPCION,
TIPOTEC,
TIPOIDPACIENTE,
NROIDPACIENTE,
CodMunEnt,
DirPaciente
,max(conorden) CANTIDAD_TIPOTEC
from view_webserv_pres_info_direc
where  NOPRESCRIPCION='20200206186017293511' and TIPOTEC='P'
GROUP BY NOPRESCRIPCION,TIPOIDPACIENTE,NROIDPACIENTE,TIPOTEC,CodMunEnt,DirPaciente;



/* 
Prescripcion          cantidad 
20200113136016719474	3
20200116159016819092	3
20200117144016841578	3
*/

--Obtener prescripciones que tienen varios tipos de tecnologia
select  
NOPRESCRIPCION,
count(*) cantidad_tipotec,
max(cantidad_registros)cantidad_registros 
from(
     select NOPRESCRIPCION,tipotec, count(*) cantidad_registros 
     from view_webserv_pres_info_direc
     group by NOPRESCRIPCION,tipotec
    ) tb 
group by NOPRESCRIPCION
order by count(*) desc,cantidad_registros desc;
--having count(*)>1









/*

--Buscar las prescripciones que estan en el modulo de autorizaciones(EAUT)
select *
from Operation OP
where DocumentId = 'NP' and  Reference='20200120120016905269';



SELECT *
FROM ZZZ_BDUAHISSUB B 
WHERE B.MES IN (SELECT MAX(MES) FROM ZZZ_BDUAHISSUB)
and B.NOM_MPIO = 'BARRANQUILLA';
*/
