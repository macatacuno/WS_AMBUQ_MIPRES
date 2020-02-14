--Modelo Asociado: OperationAuthorization
select NumberId, LocationId,"Date", Reference
from Operation OP
join WEBSERV_PRES_PRES pp on pp.NOPRESCRIPCION=OP.Reference
join WEBSERV_PRES_SERV_COMP SC ON SC.ID_PRES=PP.ID_PRES
where "Date">='09/01/2020' 
and DocumentId = 'NP';



select * from GeographicLocation where GEOGRAPHICLOCATIONID='47001';

select * from location l where l.GEOGRAPHICLOCATIONID='47001';


