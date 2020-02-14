--Actualizar token
UPDATE WEBSERV_TIPOREPORTES
SET TOKEN_TEMPORAL    = 'jj',
    FECHA_TOKEN_TEMPORAL=sysdate
WHERE TOKEN = '3858A1E4-E9BB-40D1-90E7-C127480363F2';

--consultar la fecha actual y la fecha en la que se registro en token
SELECT    
to_char(FECHA_TOKEN_TEMPORAL, 'DD/MM/YYYY HH24:MI:SS') FECHA_TOKEN_TEMPORAL
, to_char(SYSDATE, 'DD/MM/YYYY HH24:MI:SS') FECHA_ACTUAL
FROM WEBSERV_TIPOREPORTES tt ;

 
--Calcular las horas de diferencia entre la fecha actual y la fecha en la que se registro en token
select round(24 * (sysdate - to_date(to_char(FECHA_TOKEN_TEMPORAL, 'YYYY-MM-DD hh24:mi'), 'YYYY-MM-DD hh24:mi')),2) as horas_de_diferencia 
from WEBSERV_TIPOREPORTES;





