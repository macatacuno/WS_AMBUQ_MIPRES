/*
DROP TABLE WEBSERV_JUNTA_PROFECIONAL;
DROP SEQUENCE SEQ_WEBSERV_JUNTA_PROFECIONAL;
*/
/*
delete from WEBSERV_JUNTA_PROFECIONAL where repo_periodo='15/01/20';


delete from webserv_reportes_json rj
where  RJ.SERV_ID=9 and RJ.TIRE_ID=2;

*/
SELECT * FROM WEBSERV_JUNTA_PROFESIONAL;
SELECT * FROM webserv_reportes_json rj
WHERE RJ.SERV_ID=9
AND RJ.TIRE_ID  =1
AND dbms_lob.substr( rj.JSON)='SI'
--AND rj.PERIODO<'01/01/2019'
ORDER BY PERIODO DESC;



