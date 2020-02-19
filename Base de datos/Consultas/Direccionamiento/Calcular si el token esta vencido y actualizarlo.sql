--Actualizar token
UPDATE WEBSERV_TIPOREPORTES
SET TOKEN_TEMPORAL    = '',
    FECHA_TOKEN_TEMPORAL=''
WHERE TOKEN = '208F5DB1-95D0-446E-AAD7-2674C6360A46';

--consultar la fecha actual y la fecha en la que se registro en token
SELECT    
to_char(FECHA_TOKEN_TEMPORAL, 'DD/MM/YYYY HH24:MI:SS') FECHA_TOKEN_TEMPORAL
, to_char(SYSDATE, 'DD/MM/YYYY HH24:MI:SS') FECHA_ACTUAL
FROM WEBSERV_TIPOREPORTES tt ;

 
--Calcular las horas de diferencia entre la fecha actual y la fecha en la que se registro en token
select round(24 * (sysdate - to_date(to_char(FECHA_TOKEN_TEMPORAL, 'YYYY-MM-DD hh24:mi'), 'YYYY-MM-DD hh24:mi')),2) as horas_de_diferencia 
from WEBSERV_TIPOREPORTES;


SELECT TOKEN,NIT,
decode(TOKEN_TEMPORAL,null,'vacio',TOKEN_TEMPORAL) TOKEN_TEMPORAL, 
decode(round(24 * (sysdate - to_date(to_char(FECHA_TOKEN_TEMPORAL, 'YYYY-MM-DD hh24:mi'), 'YYYY-MM-DD hh24:mi')),2),null,-1,
       round(24 * (sysdate - to_date(to_char(FECHA_TOKEN_TEMPORAL, 'YYYY-MM-DD hh24:mi'), 'YYYY-MM-DD hh24:mi')),2))as HORAS_DE_DIFERENCIA 
FROM WEBSERV_TIPOREPORTES WHERE TIRE_ID=2;


select c.Regimen,c.* from client c
