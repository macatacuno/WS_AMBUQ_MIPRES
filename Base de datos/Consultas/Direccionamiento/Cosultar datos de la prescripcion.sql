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
CONORDEN,
TIPOIDPACIENTE,
NROIDPACIENTE,
CODMUNENT,
DIRPACIENTE,
REGIMEN,
decode(CODAMBATE,null,'NO EXISTE',CODAMBATE)CODAMBATE,
decode(DESC_CODAMBATE,null,'NO EXISTE',DESC_CODAMBATE)DESC_CODAMBATE,
decode(TIPOTEC,'P',to_date(sysdate+90, 'YYYY-MM-DD'),
decode(CODAMBATE,11,decode((select 
                            count(1) cantidad_entregas
                            from  WEBSERV_PRES_DIRECCIONADOS pd 
                            where  pd.NOPRESCRIPCION=pi.NOPRESCRIPCION
                            and pd.TIPOTEC=pi.TIPOTEC
                            and pd.CONORDEN=pi.CONORDEN),0,to_date(sysdate+15, 'YYYY-MM-DD'),
                              to_date(sysdate+30, 'YYYY-MM-DD')),
                 12,to_date(sysdate+30, 'YYYY-MM-DD'),
                 21,to_date(sysdate+30, 'YYYY-MM-DD'),
                   '11-11-1111')) FECHA_MAXIMA_DE_ENTREGA,
decode(CODSERTECAENTREGAR,null,'NO EXISTE',CODSERTECAENTREGAR)CODSERTECAENTREGAR,
decode(DESC_CODSERTECAENTREGAR,null,'NO EXISTE',DESC_CODSERTECAENTREGAR)DESC_CODSERTECAENTREGAR,
DECODE(DIR_IDDIRECCIONAMIENTO,NULL,0,DIR_IDDIRECCIONAMIENTO)DIR_IDDIRECCIONAMIENTO,
DECODE(DIR_ID,NULL,0,DIR_ID)DIR_ID
from view_webserv_pres_info_direc pi
where  NOPRESCRIPCION='20190104182009821673' and TIPOTEC='M' and conorden=1;
--where  NOPRESCRIPCION='20200206186017293511' and TIPOTEC='P' and conorden=3;


--3. Consulta para cargar los datos de las prescripciones direccionadas
select 
pi.NOPRESCRIPCION,
pi.TIPOTEC,
pi.CONORDEN,
pd.NOENTREGA,




decode(CODAMBATE,11,decode((select 
                            count(1) cantidad_entregas
                            from  WEBSERV_PRES_DIRECCIONADOS pd 
                            where  pd.NOPRESCRIPCION=pi.NOPRESCRIPCION
                            and pd.TIPOTEC=pi.TIPOTEC
                            and pd.CONORDEN=pi.CONORDEN),0,to_date(pd.FECMAXENT+15, 'YYYY-MM-DD'),
                              to_date(pd.FECMAXENT+30, 'YYYY-MM-DD')),
                 12,to_date(pd.FECMAXENT+30, 'YYYY-MM-DD'),
                 21,to_date(pd.FECMAXENT+30, 'YYYY-MM-DD'),
                   '11-11-1111')) FECMAXENT,




DECODE(pd.DIR_IDDIRECCIONAMIENTO,NULL,0,pd.DIR_IDDIRECCIONAMIENTO)DIR_IDDIRECCIONAMIENTO,
DECODE(pd.DIR_ID,NULL,0,pd.DIR_ID)DIR_ID
from view_webserv_pres_info_direc pi
left join WEBSERV_PRES_DIRECCIONADOS pd on pd.NOPRESCRIPCION=pi.NOPRESCRIPCION and pi.TIPOTEC=pd.TIPOTEC and pi.CONORDEN=pd.CONORDEN
where  pd.NOPRESCRIPCION='20190118184010019347' and pd.TIPOTEC='P' and pd.CONORDEN=1 and pd.NOENTREGA=1;


--3. Consulta para cargar los datos de las prescripciones direccionadas
select 
count(1) cantidad_entregas
from  WEBSERV_PRES_DIRECCIONADOS pd 
where  pd.NOPRESCRIPCION='20190104182009821673' 
 and pd.TIPOTEC='M' 
 and pd.CONORDEN=1;

--NOPRESCIPCION

select 
pi.NOPRESCRIPCION,
pi.TIPOTEC,
pi.CONORDEN,
pd.NOENTREGA,
DECODE(pd.DIR_IDDIRECCIONAMIENTO,NULL,0,pd.DIR_IDDIRECCIONAMIENTO)DIR_IDDIRECCIONAMIENTO,
DECODE(pd.DIR_ID,NULL,0,pd.DIR_ID)DIR_ID
from view_webserv_pres_info_direc pi
left join WEBSERV_PRES_DIRECCIONADOS pd on pd.NOPRESCRIPCION=pi.NOPRESCRIPCION and pi.TIPOTEC=pd.TIPOTEC and pi.CONORDEN=pd.CONORDEN
where  pd.NOPRESCRIPCION='20190118184010019347' 
and pd.TIPOTEC='P' 
and pd.CONORDEN=1 
and pd.NOENTREGA=6

/*
Ambulatorio priorizado: 15 dias  11
Ambulatorio No priorizado: 30 dias 12 
Hospitalario domiciliario 30 dias 21
*/


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
     where NOPRESCRIPCION like '201901%'--Los primeros numeros de las prescriciones son la fecha de la prescripcion 
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
