<?php

set_time_limit(9999999);
$servername = "localhost";
$database = "db_app_ambuq";
$username = "root";
$password = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}



///////Declaracion de Variables Generales(Inicio)/////////
$peri_error = "";
$peri_error_conteo = 0;
$periodos_cargados = "";
$periodos_cargados_conteo = 0;
//pamemetros de entrada
//$tipo_get = $_GET['tipo'];
$tipo_get = "subsidiado";
$tipo_id = 2;
$servicio_id = 3; // Se asigna el codigo del servicio Prescripcion
//Parametros de la api
$nit = "";
$url_bd = "";
$token = "";
$Webservice = "";
$serv_nombre = "";
$url_api_generar_token = "";
$json = "";
$count_report = 0;
///////Declaracion de Variables Generales(Fin)/////////

//obtener los parametros la url(inicio)

$consulta = "SELECT serv_url,ws_id,serv_nombre FROM servicios where serv_id=" . $servicio_id;
if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) {
    $url_bd = $fila["serv_url"];
    $Webservice = $fila["ws_id"];
    $serv_nombre = $fila["serv_nombre"];
  }
}
//obtener los parametros la url(fin)

//obtener el nit y el token(inicio)
$consulta = "SELECT tire_nit,tire_token FROM tiposreportes where tire_id=" . $tipo_id;

if ($resultado = $conn->query($consulta)) {
  while ($fila = $resultado->fetch_assoc()) {
    $nit = $fila["tire_nit"];
    $token = $fila["tire_token"];
  }
}
//obtener el nit y el token(fin)





/*
$periodo_inicial ="17-01-01"; 
$periodo_final =(string)date("y-m-d",strtotime(date('y-m-d')."- 1 day")); 
*/
$periodo_inicial = "19-10-29";
$periodo_final = $periodo_inicial;






//$periodo_final =   (string)date('y-m-d');


$date1 = new DateTime($periodo_inicial);
$date2 = new DateTime($periodo_final);
$diff = $date1->diff($date2);
$cant_dias = $diff->days + 1;
echo "//////////////////////////////////////////////////////////////////////////////////////////////";
echo "<br> Servicio cargado: WSPRESCRIPCION-> " . $serv_nombre . "-> " . $tipo_get . "<br>";
echo ' dia(s) consultado(s): ' . $cant_dias;

echo "<br>";
$periodo_conteo = $periodo_inicial;
echo "Periodo consultado: 20" . $periodo_inicial . " - 20" . $periodo_final;
echo "<br>";


for ($i = 0; $i <= $cant_dias - 1; $i++) {
  $periodo_conteo = date("y-m-d", strtotime($periodo_inicial . "+ " . $i . " day"));

  //Codico para validar si existe el registro antes de insertarlo

  $consulta = "SELECT repo_periodo, repo_json FROM reportesws  where serv_id=" . $servicio_id . " and tire_id=" . $tipo_id . " and  repo_periodo = '20" . $periodo_conteo . "'";
  //echo "<br>Consulta json: ".$consulta."<br>";
  if ($resultado = $conn->query($consulta)) { //se valida que la consulta se ejecute correctamente
    //$número_filas = mysql_num_rows($resultado);
    //echo "Numero de filas: ".$número_filas;
    $json_anterior = false;
    while ($fila = $resultado->fetch_assoc()) { //Se valida que el periodo no existe en la tabla
      $json_anterior = true;
    }
    if ($json_anterior == false) {

      $url = $url_bd . "/" . $nit . '/' . "20" . $periodo_conteo . '/' . $token;

      /*********************************************************Json De Ejemplo ****************************************/
      /*********************************************************Json De Ejemplo ****************************************/
      /*********************************************************Json De Ejemplo ****************************************/
      /*********************************************************Json De Ejemplo ****************************************/
      /*********************************************************Json De Ejemplo ****************************************/
      /*********************************************************Json De Ejemplo ****************************************/
      /*********************************************************Json De Ejemplo ****************************************/

      $json = '{"prescripcion":{"NoPrescripcion":"20190125179010128886","FPrescripcion":"2019-01-25T00:00:00","HPrescripcion":"11:30:33","CodHabIPS":"080010003701","TipoIDIPS":"NI","NroIDIPS":"890102768","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 48 # 70-38","TelSedeIPS":"3091999","TipoIDProf":"CC","NumIDProf":"8755608","PNProfS":"SAUL","SNProfS":"ALFREDO","PAProfS":"CHRISTIANSEN","SAProfS":"MARTELO","RegProfS":"1572","TipoIDPaciente":"CC","NroIDPaciente":"3754775","PNPaciente":"HERNANDO","SNPaciente":"","PAPaciente":"ESTRADA","SAPaciente":"GOMEZ","CodAmbAte":22,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R579","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":3,"TipoMed":1,"TipoPrest":1,"CausaS1":1,"CausaS2":1,"CausaS3":1,"MedPBSUtilizado":"samplestring4","RznCausaS31":5,"DescRzn31":"samplestring6","RznCausaS32":7,"DescRzn32":"samplestring8","CausaS4":1,"MedPBSDescartado":"samplestring9","RznCausaS41":10,"DescRzn41":"samplestring11","RznCausaS42":12,"DescRzn42":"samplestring13","RznCausaS43":14,"DescRzn43":"samplestring15","RznCausaS44":16,"DescRzn44":"samplestring17","CausaS5":1,"RznCausaS5":1,"CausaS6":1,"DescMedPrinAct":"samplestring18","CodFF":"samplestring19","CodVA":"samplestring20","JustNoPBS":"samplestring21","Dosis":"samplestring22","DosisUM":"samplestring23","NoFAdmon":"samplestring24","CodFreAdmon":1,"IndEsp":1,"CanTrat":"samplestring25","DurTrat":1,"CantTotalF":"samplestring26","UFCantTotal":"samplestring27","IndRec":"samplestring28","EstJM":1,"PrincipiosActivos":[{"ConOrden":4,"CodPriAct":"samplestring5","ConcCant":"samplestring6","UMedConc":"samplestring7","CantCont":"samplestring8","UMedCantCont":"samplestring9"},{"ConOrden":4,"CodPriAct":"samplestring5","ConcCant":"samplestring6","UMedConc":"samplestring7","CantCont":"samplestring8","UMedCantCont":"samplestring9"}],"IndicacionesUNIRS":[{"ConOrden":4,"CodIndicacion":"samplestring5"},{"ConOrden":4,"CodIndicacion":"samplestring5"}]},{"ConOrden":4,"TipoMed":1,"TipoPrest":1,"CausaS1":1,"CausaS2":1,"CausaS3":1,"MedPBSUtilizado":"samplestring4","RznCausaS31":5,"DescRzn31":"samplestring6","RznCausaS32":7,"DescRzn32":"samplestring8","CausaS4":1,"MedPBSDescartado":"samplestring9","RznCausaS41":10,"DescRzn41":"samplestring11","RznCausaS42":12,"DescRzn42":"samplestring13","RznCausaS43":14,"DescRzn43":"samplestring15","RznCausaS44":16,"DescRzn44":"samplestring17","CausaS5":1,"RznCausaS5":1,"CausaS6":1,"DescMedPrinAct":"samplestring18","CodFF":"samplestring19","CodVA":"samplestring20","JustNoPBS":"samplestring21","Dosis":"samplestring22","DosisUM":"samplestring23","NoFAdmon":"samplestring24","CodFreAdmon":1,"IndEsp":1,"CanTrat":"samplestring25","DurTrat":1,"CantTotalF":"samplestring26","UFCantTotal":"samplestring27","IndRec":"samplestring28","EstJM":1,"PrincipiosActivos":[{"ConOrden":4,"CodPriAct":"samplestring5","ConcCant":"samplestring6","UMedConc":"samplestring7","CantCont":"samplestring8","UMedCantCont":"samplestring9"},{"ConOrden":4,"CodPriAct":"samplestring5","ConcCant":"samplestring6","UMedConc":"samplestring7","CantCont":"samplestring8","UMedCantCont":"samplestring9"}],"IndicacionesUNIRS":[{"ConOrden":4,"CodIndicacion":"samplestring5"},{"ConOrden":4,"CodIndicacion":"samplestring5"}]},{"ConOrden":4,"TipoMed":1,"TipoPrest":1,"CausaS1":1,"CausaS2":1,"CausaS3":1,"MedPBSUtilizado":"samplestring4","RznCausaS31":5,"DescRzn31":"samplestring6","RznCausaS32":7,"DescRzn32":"samplestring8","CausaS4":1,"MedPBSDescartado":"samplestring9","RznCausaS41":10,"DescRzn41":"samplestring11","RznCausaS42":12,"DescRzn42":"samplestring13","RznCausaS43":14,"DescRzn43":"samplestring15","RznCausaS44":16,"DescRzn44":"samplestring17","CausaS5":1,"RznCausaS5":1,"CausaS6":1,"DescMedPrinAct":"samplestring18","CodFF":"samplestring19","CodVA":"samplestring20","JustNoPBS":"samplestring21","Dosis":"samplestring22","DosisUM":"samplestring23","NoFAdmon":"samplestring24","CodFreAdmon":1,"IndEsp":1,"CanTrat":"samplestring25","DurTrat":1,"CantTotalF":"samplestring26","UFCantTotal":"samplestring27","IndRec":"samplestring28","EstJM":1,"PrincipiosActivos":[{"ConOrden":4,"CodPriAct":"samplestring5","ConcCant":"samplestring6","UMedConc":"samplestring7","CantCont":"samplestring8","UMedCantCont":"samplestring9"},{"ConOrden":4,"CodPriAct":"samplestring5","ConcCant":"samplestring6","UMedConc":"samplestring7","CantCont":"samplestring8","UMedCantCont":"samplestring9"}],"IndicacionesUNIRS":[{"ConOrden":4,"CodIndicacion":"samplestring5"},{"ConOrden":4,"CodIndicacion":"samplestring5"}]}],"procedimientos":[{"ConOrden":3,"TipoPrest":4,"CausaS11":1,"CausaS12":1,"CausaS2":1,"CausaS3":1,"CausaS4":1,"ProPBSUtilizado":"samplestring5","CausaS5":1,"ProPBSDescartado":"samplestring6","RznCausaS51":7,"DescRzn51":"samplestring8","RznCausaS52":9,"DescRzn52":"samplestring10","CausaS6":1,"CausaS7":1,"CodCUPS":"samplestring11","CanForm":"samplestring12","CadaFreUso":"samplestring13","CodFreUso":14,"Cant":"samplestring15","CantTotal":"samplestring16","CodPerDurTrat":1,"JustNoPBS":"samplestring17","IndRec":"samplestring18","EstJM":19},{"ConOrden":3,"TipoPrest":4,"CausaS11":1,"CausaS12":1,"CausaS2":1,"CausaS3":1,"CausaS4":1,"ProPBSUtilizado":"samplestring5","CausaS5":1,"ProPBSDescartado":"samplestring6","RznCausaS51":7,"DescRzn51":"samplestring8","RznCausaS52":9,"DescRzn52":"samplestring10","CausaS6":1,"CausaS7":1,"CodCUPS":"samplestring11","CanForm":"samplestring12","CadaFreUso":"samplestring13","CodFreUso":14,"Cant":"samplestring15","CantTotal":"samplestring16","CodPerDurTrat":1,"JustNoPBS":"samplestring17","IndRec":"samplestring18","EstJM":19}],"dispositivos":[{"ConOrden":3,"TipoPrest":1,"CausaS1":1,"CodDisp":"samplestring4","CanForm":"samplestring5","CadaFreUso":"samplestring6","CodFreUso":7,"Cant":"samplestring8","CodPerDurTrat":1,"CantTotal":"samplestring9","JustNoPBS":"samplestring10","IndRec":"samplestring11","EstJM":12},{"ConOrden":3,"TipoPrest":1,"CausaS1":1,"CodDisp":"samplestring4","CanForm":"samplestring5","CadaFreUso":"samplestring6","CodFreUso":7,"Cant":"samplestring8","CodPerDurTrat":1,"CantTotal":"samplestring9","JustNoPBS":"samplestring10","IndRec":"samplestring11","EstJM":12}],"productosnutricionales":[{"ConOrden":3,"TipoPrest":4,"CausaS1":1,"CausaS2":1,"CausaS3":1,"CausaS4":1,"ProNutUtilizado":"samplestring5","RznCausaS41":1,"DescRzn41":"samplestring6","RznCausaS42":1,"DescRzn42":"samplestring7","CausaS5":1,"ProNutDescartado":"samplestring8","RznCausaS51":1,"DescRzn51":"samplestring9","RznCausaS52":1,"DescRzn52":"samplestring10","RznCausaS53":1,"DescRzn53":"samplestring11","RznCausaS54":1,"DescRzn54":"samplestring12","DXEnfHuer":1,"DXVIH":1,"DXCaPal":1,"DXEnfRCEV":1,"DXDesPro":1,"TippProNut":"samplestring13","DescProdNutr":"samplestring14","CodForma":"samplestring15","CodViaAdmon":1,"JustNoPBS":"samplestring16","Dosis":"samplestring17","DosisUM":"samplestring18","NoFAdmon":"samplestring19","CodFreAdmon":1,"IndEsp":1,"CanTrat":"samplestring20","DurTrat":1,"CantTotalF":"samplestring21","UFCantTotal":"samplestring22","IndRec":"samplestring23","NoPrescAso":"samplestring24","EstJM":1},{"ConOrden":3,"TipoPrest":4,"CausaS1":1,"CausaS2":1,"CausaS3":1,"CausaS4":1,"ProNutUtilizado":"samplestring5","RznCausaS41":1,"DescRzn41":"samplestring6","RznCausaS42":1,"DescRzn42":"samplestring7","CausaS5":1,"ProNutDescartado":"samplestring8","RznCausaS51":1,"DescRzn51":"samplestring9","RznCausaS52":1,"DescRzn52":"samplestring10","RznCausaS53":1,"DescRzn53":"samplestring11","RznCausaS54":1,"DescRzn54":"samplestring12","DXEnfHuer":1,"DXVIH":1,"DXCaPal":1,"DXEnfRCEV":1,"DXDesPro":1,"TippProNut":"samplestring13","DescProdNutr":"samplestring14","CodForma":"samplestring15","CodViaAdmon":1,"JustNoPBS":"samplestring16","Dosis":"samplestring17","DosisUM":"samplestring18","NoFAdmon":"samplestring19","CodFreAdmon":1,"IndEsp":1,"CanTrat":"samplestring20","DurTrat":1,"CantTotalF":"samplestring21","UFCantTotal":"samplestring22","IndRec":"samplestring23","NoPrescAso":"samplestring24","EstJM":1}],"serviciosComplementarios":[{"ConOrden":3,"TipoPrest":1,"CausaS1":1,"CausaS2":1,"CausaS3":1,"CausaS4":1,"DescCausaS4":"samplestring4","CausaS5":1,"CodSerComp":"samplestring5","DescSerComp":"samplestring6","CanForm":"samplestring7","CadaFreUso":"samplestring8","CodFreUso":1,"Cant":"samplestring9","CantTotal":"samplestring10","CodPerDurTrat":1,"TipoTrans":1,"ReqAcom":1,"TipoIDAcomAlb":"samplestring11","NroIDAcomAlb":"samplestring12","ParentAcomAlb":1,"NombAlb":"samplestring13","CodMunOriAlb":"samplestring14","CodMunDesAlb":"samplestring15","JustNoPBS":"samplestring16","IndRec":"samplestring17","EstJM":1},{"ConOrden":3,"TipoPrest":1,"CausaS1":1,"CausaS2":1,"CausaS3":1,"CausaS4":1,"DescCausaS4":"samplestring4","CausaS5":1,"CodSerComp":"samplestring5","DescSerComp":"samplestring6","CanForm":"samplestring7","CadaFreUso":"samplestring8","CodFreUso":1,"Cant":"samplestring9","CantTotal":"samplestring10","CodPerDurTrat":1,"TipoTrans":1,"ReqAcom":1,"TipoIDAcomAlb":"samplestring11","NroIDAcomAlb":"samplestring12","ParentAcomAlb":1,"NombAlb":"samplestring13","CodMunOriAlb":"samplestring14","CodMunDesAlb":"samplestring15","JustNoPBS":"samplestring16","IndRec":"samplestring17","EstJM":1}]}';
      /********************************************************Json 2019-01-25 ****************************************/
      /********************************************************Json 2019-01-25 ****************************************/
      /********************************************************Json 2019-01-25 ****************************************/
      /********************************************************Json 2019-01-25 ****************************************/
      /********************************************************Json 2019-01-25 ****************************************/
      /********************************************************Json 2019-01-25 ****************************************/

     /* $json = '[{"prescripcion":{"NoPrescripcion":"20190125179010128886","FPrescripcion":"2019-01-25T00:00:00","HPrescripcion":"11:30:33","CodHabIPS":"080010003701","TipoIDIPS":"NI","NroIDIPS":"890102768","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 48 # 70-38","TelSedeIPS":"3091999","TipoIDProf":"CC","NumIDProf":"8755608","PNProfS":"SAUL","SNProfS":"ALFREDO","PAProfS":"CHRISTIANSEN","SAProfS":"MARTELO","RegProfS":"1572","TipoIDPaciente":"CC","NroIDPaciente":"3754775","PNPaciente":"HERNANDO","SNPaciente":"","PAPaciente":"ESTRADA","SAPaciente":"GOMEZ","CodAmbAte":22,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R579","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"NOREPINEFRINA","RznCausaS31":1,"DescRzn31":"PERSISTE CON DATOS DE CHOQUE ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[VASOPRESINA] 20UI/1ml","CodFF":"COLFF004","CodVA":"042","JustNoPBS":"PACIENTE MASCULINO DE 81 AÑOS DE EDAD CON DIAGNOSTICOS DE CABAG X 4 PUENTES CON CEC 58 MINUT Y TIEMPO DE CLAMPP DE 39 MINUT, TORACOSTOMIA CERRADA POR NEUMOTORAX IZQUIERDO AHORA CON DATOS DE HIPOTENSION SOSTENIDA CHOQUE VASOPLEJICO POR LO QUE SE REQUIERE ADICIONAR SEGUNDO SOPORTE VASOPRESOR CON ESTE MEDICAMENTO CON EL FIN DE MEJORAR SINTOMAS Y PREVENIR COMPLICACIONES ASOCIADAS.","Dosis":"40","DosisUM":"0072","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"10","DurTrat":3,"CantTotalF":"40","UFCantTotal":"01","IndRec":"ADMINISTRAR 40 UI CADA 12 HORAS IV ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50813","ConcCant":"20","UMedConc":"0072","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20190125151010129518","FPrescripcion":"2019-01-25T00:00:00","HPrescripcion":"11:47:40","CodHabIPS":"080010054401","TipoIDIPS":"NI","NroIDIPS":"800194798","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 49 C NO 82-70","TelSedeIPS":null,"TipoIDProf":"CC","NumIDProf":"8734493","PNProfS":"ANGEL","SNProfS":"LUIS","PAProfS":"HERNANDEZ","SAProfS":"LASTRA","RegProfS":"2915","TipoIDPaciente":"CC","NroIDPaciente":"22409874","PNPaciente":"MARIA","SNPaciente":"ISABEL","PAPaciente":"CABARCAS","SAPaciente":"DE FUENTES","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"C504","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":0,"CausaS6":null,"DescMedPrinAct":"[ANASTROZOL] 1mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"CANCER DE MAMA A IZQUIERDA T2p,N0.MO EC IIA . CON PATOLOGIA Nº 120150046 DE 6/4/2015 QUE REPORTA CARCINOMA DUCTAL INFILTRANTE MODERADMENTE DIFERENCIADO CON RECEPTORES HORMONALES POSITIVOS , HER2 NEGATIVO Y KI 67 10%, MRM (24/2/2015) + VA (NOTIENE PATOLOGIA ) TRATADA ASILADAMENTE CON RADIOTERAPIA Y LUEGO CON HORMIONIOTERAPIA EN TRTAMIENTO CON ANASTRAZOL DESDE 3/2015. HASTA 2/2020, ESTUDIOS DE EXTENSION NEGATIVOS PARA METASTASIS, SE INDICA CONTINUIDAD CON INHIBIDOR DE AROMATASA TIPO ANASTRAZOL ","Dosis":"1","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"DAR 1 TABLETA DE 1 MILIGRAMO VIA ORAL CADA 24 HORAS POR 90 DIAS DE TRATAMIENTO ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07274","ConcCant":"1","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20190125165010129775","FPrescripcion":"2019-01-25T00:00:00","HPrescripcion":"11:55:17","CodHabIPS":"080010054401","TipoIDIPS":"NI","NroIDIPS":"800194798","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 49 C NO 82-70","TelSedeIPS":null,"TipoIDProf":"CC","NumIDProf":"72008149","PNProfS":"HAROLD","SNProfS":"","PAProfS":"LLANO","SAProfS":"CONRADO","RegProfS":"4323008","TipoIDPaciente":"CC","NroIDPaciente":"36557428","PNPaciente":"MARLENE","SNPaciente":"","PAPaciente":"CRESPO","SAPaciente":"PAREJO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R522","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[ACETAMINOFEN] 325mg/1U ; [TRAMADOL CLORHIDRATO] 37,5mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"DOLOR LUMBAR HACE 3 AÑOS PATOLOGIA: HTA CRONCA REFIERE DOLOR REGION LUMBAR - AXIAL - INTENSIDAD MODERADA IRRADIADA PIERNA DERECHA - INTERVENCIONISMO: SI - BLOQUEO - NEUROCX ( HACE 2 AÑOS) AL EXAMEN FISICO SIGNOS VITALES ACEPTABLES , OBESIDAD - DOLOR A LA PALPACION REGION LUMBAR - LASSEGUE POSITIVO IRRITACION L5 DERECHA., RNM LUMBAR : NO LA TRAJO IK: ECOG: A: DOLOR CRONICO - RADICULOPATIA LUMBOSACRA - SE DECIDE AJUSTES DEL TRATAMIENTO CON ACETAMINOFEN MAS TRAMADOL ","Dosis":"1","DosisUM":"0247","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"60","DurTrat":3,"CantTotalF":"120","UFCantTotal":"66","IndRec":"DAR 1 TABLETA VIA ORAL CADA 12 HORAS POR 60 DIAS DE TRATAMIENTO ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00626","ConcCant":"325","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"02722","ConcCant":"37,5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]}]';*/
      /********************************************************Json 2019-10-29 ****************************************/
      /********************************************************Json 2019-10-29 ****************************************/
      /********************************************************Json 2019-10-29 ****************************************/
      /********************************************************Json 2019-10-29 ****************************************/
      /********************************************************Json 2019-10-29 ****************************************/
      /********************************************************Json 2019-10-29 ****************************************/
      //$json = (string)file_get_contents($url);
       /*$json = '[{"prescripcion":{"NoPrescripcion":"20191029146015264609","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"06:46:27","CodHabIPS":"134300290801","TipoIDIPS":"NI","NroIDIPS":"800033723","CodDANEMunIPS":"13430","DirSedeIPS":"CALLE 16 # 10a-143","TelSedeIPS":"3187117423","TipoIDProf":"CC","NumIDProf":"73578467","PNProfS":"GABRIEL","SNProfS":"ANTONIO","PAProfS":"NAVARRO","SAProfS":"BARRIOS","RegProfS":"130403","TipoIDPaciente":"CC","NroIDPaciente":"925169","PNPaciente":"ADALBERTO","SNPaciente":"","PAPaciente":"ARRIETA","SAPaciente":"JIMENEZ","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"I490","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"WARFARINA","RznCausaS31":1,"DescRzn31":"sangrados","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[RIVAROXABAN] 15mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"guias aha , chadvasc mayor de 2 ","Dosis":"1","DosisUM":"9000","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"1 ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"08428","ConcCant":"15","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029123015265007","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"07:16:44","CodHabIPS":"470010065001","TipoIDIPS":"NI","NroIDIPS":"891780185","CodDANEMunIPS":"47001","DirSedeIPS":"Calle 23 CARRERA 14 ESQUINA","TelSedeIPS":"4346262 EXT 201, 233","TipoIDProf":"CC","NumIDProf":"36718222","PNProfS":"DANITH","SNProfS":"","PAProfS":"MEDINA","SAProfS":"BORNACHERA","RegProfS":"0427","TipoIDPaciente":"CC","NroIDPaciente":"12585027","PNPaciente":"JOSE","SNPaciente":"RAFAEL","PAPaciente":"PEREZ","SAPaciente":"SALGADO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E790","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"ALOPURINOL","RznCausaS31":0,"DescRzn31":null,"RznCausaS32":1,"DescRzn32":"contraindicado en enfermedad renal cronica","CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[FEBUXOSTAT] 40mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"indicado en artropatia cristalica por acido urico. en paciente en con enfermedad renal cronica","Dosis":"1","DosisUM":"9000","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"66","IndRec":"ninguna","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"08140","ConcCant":"40","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029138015267260","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"08:32:47","CodHabIPS":"134300028101","TipoIDIPS":"NI","NroIDIPS":"890480363","CodDANEMunIPS":"13430","DirSedeIPS":"calle 14a numero 3-28","TelSedeIPS":"6876043","TipoIDProf":"CC","NumIDProf":"60266677","PNProfS":"KELY","SNProfS":"LUZ","PAProfS":"PEREZ","SAProfS":"ORTEGA","RegProfS":"02521","TipoIDPaciente":"CC","NroIDPaciente":"12580853","PNPaciente":"ELIGIO","SNPaciente":"MIGUEL","PAPaciente":"MENCO","SAPaciente":"AGAMEZ","CodAmbAte":22,"RefAmbAte":0,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E440","CodDxRel1":"I679","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":1,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":null,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1501","DescProdNutr":"150101","CodForma":"3","CodViaAdmon":2,"JustNoPBS":"PACIENTE ESTABLE HEMODINAMICAMENTE HOSPITALIZADO EN LA UNIDAD DE CUIDADO CRÓNICO, POR EL SERVICIO DE MEDICINA INTERNA CURSANDO CON HIDROCEFALIA COMUNICANTE, HSA FISHER IV HUNT AND HESS V, ANEURISMA FUSIFORME EN EL SEGMENTO CARPTIDEO SUPRACLINOIDEO DERECHO ROTO SECUNDARIO A4, ANEURISMA DE LA BASILAR. - ANEURISMA DE PICA,HSA FII SECUNDARIA A ROTURA DE 3 3. ANEURISMA FUSIFORME EN EL SEGMENTO CARPTIDE,SUPRACLINOIDEO DERECHO ROTO SECUNDARIO A 4,PORTADOR DE GASTROSTOMIA,\n. ","Dosis":"1","DosisUM":"0247","NoFAdmon":"4","CodFreAdmon":2,"IndEsp":3,"CanTrat":"20","DurTrat":3,"CantTotalF":"120","UFCantTotal":"3","IndRec":"SUMINISTRAR TERAPIA NUTRICIONAL POR SONDA DE GASTROSTOMIA EN BOLO CADA 4 HORAS.","NoPrescAso":null,"EstJM":1},{"ConOrden":2,"TipoPrest":1,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":null,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1201","DescProdNutr":"120101","CodForma":"9","CodViaAdmon":2,"JustNoPBS":"PACIENTE ESTABLE HEMODINAMICAMENTE HOSPITALIZADO EN LA UNIDAD DE CUIDADO CRÓNICO, POR EL SERVICIO DE MEDICINA INTERNA CURSANDO CON HIDROCEFALIA COMUNICANTE, HSA FISHER IV HUNT AND HESS V, ANEURISMA FUSIFORME EN EL SEGMENTO CARPTIDEO SUPRACLINOIDEO DERECHO ROTO SECUNDARIO A4, ANEURISMA DE LA BASILAR. - ANEURISMA DE PICA,HSA FII SECUNDARIA A ROTURA DE 3 3. ANEURISMA FUSIFORME EN EL SEGMENTO CARPTIDE,SUPRACLINOIDEO DERECHO ROTO SECUNDARIO A 4,PORTADOR DE GASTROSTOMIA, .","Dosis":"1","DosisUM":"0247","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":3,"CanTrat":"30","DurTrat":3,"CantTotalF":"60","UFCantTotal":"9","IndRec":"SUMINISTRAR TERAPIA NUTRICIONAL ENTERAL POR SONDA DE GASTROSTOMIA DILUIR EN 50 CC AGUA ","NoPrescAso":null,"EstJM":1}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029157015267614","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"08:41:00","CodHabIPS":"080010003601","TipoIDIPS":"NI","NroIDIPS":"802000955","CodDANEMunIPS":"08001","DirSedeIPS":"CRA. 51B # 84-150","TelSedeIPS":null,"TipoIDProf":"CC","NumIDProf":"22582206","PNProfS":"ANA","SNProfS":"TATIANA","PAProfS":"PERALTA","SAProfS":"CONCHA","RegProfS":"08156202","TipoIDPaciente":"CC","NroIDPaciente":"22628176","PNPaciente":"ANA","SNPaciente":"","PAPaciente":"IGLESIA","SAPaciente":"DE FONTALVO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"Z961","CodDxRel1":"H185","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[CARBOXIMETILCELULOSA SODICA] 5mg/1ml ; [GLICEROL] 10mg/1ml ; [POLISORBATO] 5mg/1ml","CodFF":"COLFF005","CodVA":"047","JustNoPBS":"PACIENTE QUE REQUIERE LUBRICACIÓN Y HUMECTACIÓN POR PRESENTAR ENFERMEDAD DE OJO SECO. ADEMAS DISMINUYE EL TRAUMA DEL PARPADEO, LO QUE PERMITE UNA MEJOR ADHERENCIA Y CICATRIZACIÓN EPITELIAL PROPORCIONANDO A SU VEZ ALIVIO DE LOS SÍNTOMAS. PROMUEVE LA PROLIFERACIÓN CELULAR Y LA MIGRACIÓN EPITELIAL CORNEAL\n","Dosis":"2","DosisUM":"0046","NoFAdmon":"4","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"4","UFCantTotal":"13","IndRec":"Emulsion Oftalmica Frasco x 15 mL, 1 GOTA cada 4 Hora(s) en Ambos ojos por 3 Mes(es) Suministar: 4 Frasco(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00444","ConcCant":"10","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"},{"ConOrden":1,"CodPriAct":"03764","ConcCant":"5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"},{"ConOrden":1,"CodPriAct":"04950","ConcCant":"5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[ACIDO POLIACRILICO] 250mg/100g","CodFF":"C42948","CodVA":"047","JustNoPBS":"PACIENTE QUE REQUIERE LUBRICACIÓN Y HUMECTACIÓN POR PRESENTAR ENFERMEDAD DE OJO SECO. ADEMAS DISMINUYE EL TRAUMA DEL PARPADEO, LO QUE PERMITE UNA MEJOR ADHERENCIA Y CICATRIZACIÓN EPITELIAL PROPORCIONANDO A SU VEZ ALIVIO DE LOS SÍNTOMAS. PROMUEVE LA PROLIFERACIÓN CELULAR Y LA MIGRACIÓN EPITELIAL CORNEAL\n","Dosis":"1","DosisUM":"0046","NoFAdmon":"8","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"2","UFCantTotal":"13","IndRec":"Gel Oftalmico 0.25g/100g) Frasco x 10 g, 1 GOTA cada 8 Hora(s) en Ojo Izquierdo por 3 Mes(es) Suministar: 2 Frasco(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"50262","ConcCant":"250","UMedConc":"0168","CantCont":"100","UMedCantCont":"0062"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029132015268171","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"08:53:25","CodHabIPS":"130010118701","TipoIDIPS":"NI","NroIDIPS":"890480135","CodDANEMunIPS":"13001","DirSedeIPS":"Transversal 36 No 36-33","TelSedeIPS":"(095)6475420","TipoIDProf":"CC","NumIDProf":"1098676928","PNProfS":"MARIA","SNProfS":"LUISA","PAProfS":"BALLEN","SAProfS":"BARRERA","RegProfS":"MND04464","TipoIDPaciente":"RC","NroIDPaciente":"1052738352","PNPaciente":"VEYLEEN","SNPaciente":"NAIA","PAPaciente":"TORRES","SAPaciente":"OLIVO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"C910","CodDxRel1":"E441","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":2,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":null,"DXCaPal":1,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1701","DescProdNutr":"170184","CodForma":"5","CodViaAdmon":1,"JustNoPBS":"PACIENTE FEMENINA DE 5 AÑOS Y 9 MESES DE EDAD, CON DIAGNOSTICO DE LEUCEMIA LINFOBLÁSTICA AGUDA EN FASE DE MANTENIMIENTO, SE ENCUENTRA RECIBIENDO QUIMIOTERAPIA AMBULATORIA. SIN GANANCIA DE PESO Y CON INAPETENCIA Y DECAIMIENTO SECUNDARIO A PROCESO RESPIRATORIO. CUENTA CON ANTROPOMETRIA CON PESO: 17 KG // T:109.5 // IMC:14.4 // T/E:-0.9 // IMC/E:-1.1 DIAGNOSTICO NUTRICIONAL SEGÚN RESOLUCIÓN 2465: RIESGO DE DELGADEZ // SE REALIZA COMPLEMENTACION NUTRICIONAL QUE CUBRE EL 30 POR CIENTO DEL REQUERIMI","Dosis":"60","DosisUM":"0062","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":3,"CanTrat":"60","DurTrat":3,"CantTotalF":"17","UFCantTotal":"5","IndRec":"SUMINISTRAR 2 TOMAS DIARIAS DE 240CC AL DIA : SE PREPARA 5 MEDIDAS DE SUPLEMENTO MAS 200CC DE AGUA ","NoPrescAso":null,"EstJM":1}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029158015268786","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:07:31","CodHabIPS":"087580106601","TipoIDIPS":"NI","NroIDIPS":"802009778","CodDANEMunIPS":"08758","DirSedeIPS":"CALLE 21 # 19-29 piso 2","TelSedeIPS":"3183766379","TipoIDProf":"CC","NumIDProf":"45761704","PNProfS":"CARMEN","SNProfS":"AMADA","PAProfS":"REDONDO","SAProfS":"PEREZ","RegProfS":"","TipoIDPaciente":"RC","NroIDPaciente":"1042354901","PNPaciente":"LUIS","SNPaciente":"ALBERTO","PAPaciente":"RANGEL","SAPaciente":"GONZALEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E440","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":2,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":null,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1701","DescProdNutr":"170147","CodForma":"3","CodViaAdmon":1,"JustNoPBS":"es un una formula complementaria balanceada en caloriaS Y PRODUCTOS PROTEINAS APTA PARA PACIENTES CON DESNUTRICION DENTRO DE LOS PROTOCOLOS DE RECUOERACION NUTRICIONAL","Dosis":"1","DosisUM":"0247","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"180","UFCantTotal":"3","IndRec":"PACIENTE CON PESO TALL EN DZ 2 CON DESNUTRICION ODERADA CON ALTO RIESGO DE PROGRESION A DESNUTRICION SEVERA","NoPrescAso":null,"EstJM":3}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029133015268918","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:10:33","CodHabIPS":"134300077501","TipoIDIPS":"NI","NroIDIPS":"823002800","CodDANEMunIPS":"13430","DirSedeIPS":"Cra 14 n 3-48 piso 1","TelSedeIPS":"3107315890","TipoIDProf":"CC","NumIDProf":"92510692","PNProfS":"JOSE","SNProfS":"IGNACIO","PAProfS":"RESTOM","SAProfS":"MERLANO","RegProfS":"","TipoIDPaciente":"CC","NroIDPaciente":"9130901","PNPaciente":"CESAR","SNPaciente":"TULIO","PAPaciente":"CLAVIJO","SAPaciente":"ARRIETA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"Z961","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[CARBOXIMETILCELULOSA SODICA] 5mg/1ml","CodFF":"COLFF004","CodVA":"047","JustNoPBS":"PACIENTE 67 AÑOS\n\nACUDE A CONTROL ACUSA NO SE LE ESTA ENTRAGANDO TRATAMIENTO PARA GLAUCOMA TRAE REFRACCION QUE CORRIGE ODI 20/20 \n\nANTEC: HTA (+ ) DM (- ) ALERGIA (-) \nUSA LENTES CORRECTORES\nCIRUGIA DE CATARATA DEAO NIEGA TRAUMA OCULARES \n\nBMC: CONJUNTIVA SIN LESIONES, CORNEA CLARA, CAMARA FORMADA, PUPILA ISOCORICA REACTIVA A LUZ, NO SIGNOS DE INFECCION, NO CELULARIDAD, LIOS IN SITU EN AO \n \n PIO: 15/15 MMHG\n\nFONDO DE OJO (NO DILATADO): NERVIO OPTICO REDONDO, EXC CONCEN","Dosis":"1","DosisUM":"0046","NoFAdmon":"4","CodFreAdmon":7,"IndEsp":1,"CanTrat":"2","DurTrat":5,"CantTotalF":"2","UFCantTotal":"13","IndRec":"Este medicamento se utiliza para el alivio sintomático de la irritación y la sequedad ocular.","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"04950","ConcCant":"5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029152015269003","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:12:14","CodHabIPS":"080010022301","TipoIDIPS":"NI","NroIDIPS":"72125229","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 51B N94-334 CONS 206","TelSedeIPS":"3781924","TipoIDProf":"CC","NumIDProf":"72125229","PNProfS":"RODOLFO","SNProfS":"","PAProfS":"JALLER","SAProfS":"RAAD","RegProfS":"","TipoIDPaciente":"CC","NroIDPaciente":"1048224645","PNPaciente":"OSNAIDER","SNPaciente":"ENRIQUE","PAPaciente":"GUERRA","SAPaciente":"FANDIÑO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J304","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[{"ConOrden":1,"TipoPrest":2,"CausaS11":1,"CausaS12":null,"CausaS2":null,"CausaS3":0,"CausaS4":0,"ProPBSUtilizado":null,"CausaS5":1,"ProPBSDescartado":null,"RznCausaS51":1,"DescRzn51":"N.A.","RznCausaS52":0,"DescRzn52":null,"CausaS6":null,"CausaS7":null,"CodCUPS":"991202","CanForm":"1","CadaFreUso":"30","CodFreUso":3,"Cant":"3","CantTotal":"3","CodPerDurTrat":5,"JustNoPBS":"PACIENTE MASCULINO DE 19 AÑOS DE EDAD REMITIDO DEL SERVICIO DE OTORRINO SIN ANTECEDENTE FAMILIAR DE ALERGIAS NIEGA ALERGIA A LOS ALIMENTOS NI MEDICAMENTOS CON DX DE RINOSINUSITIS DE 3 AÑOS DE EVOLUCIÓN SENSIBLE AL POLVO, PICADURAS DE INSECTOS EN ESPECIAL LAS ABEJAS HA RECIBIDO TRATAMIENTO CON LORATADINA CON ESCASA MEJORÍA TEST POSITIVO PARA ÁCAROS DEL POLVO, MOSQUITO","IndRec":"APLICAR UNA DOSIS MENSUAL SIN PERDER CONTINUIDAD","EstJM":1}],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029116015269037","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:13:03","CodHabIPS":"080010054401","TipoIDIPS":"NI","NroIDIPS":"800194798","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 49 C NO 82-70","TelSedeIPS":null,"TipoIDProf":"CC","NumIDProf":"72157558","PNProfS":"CARLOS","SNProfS":"ALBERTO","PAProfS":"RODRIGUEZ","SAProfS":"GROSSER","RegProfS":"08288","TipoIDPaciente":"CC","NroIDPaciente":"39030837","PNPaciente":"ROSA","SNPaciente":"CECILIA","PAPaciente":"PEREZ","SAPaciente":"DE MEJIA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"C509","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":0,"CausaS6":null,"DescMedPrinAct":"[ANASTROZOL] 1mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"CANCER DE MAMA IZDA EIIIB , LUMINAL A , CON RECEPTORES HORMONALES POSITIVOS Y HER 2 NEGATIVO, EN TRATAMIENTO CON TAMOXIFENO 20 MG VO ,CON PROGRESION EN ENFERMEDAD ,.FUE EVALUADA POR RADIOTERAPIA QUIEN LA ENVIA A CIRUGIA ONCOLOGICA , QUIEN CONSIDERA QUE DEBE SER OPERADA , PERO HOY ME PIDE QUE LA REMITA A OTRO CIRUJANO, LA EVALUO DR ALVARO DAZA Y LE DIO ORDENES DE CIRUGIA,SIN EMBARGO CONTINUA CON MUCHAS EXCUSAS Y NO SE A OPERADO.ESTA CON LESION TUMORALULCERADA Y PROGRESADA.","Dosis":"1","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"DAR 1 TABLETA DE 1 MILIGRAMO VIA ORAL CADA 24 HORAS POR 90 DIAS DE TRATAMIENTO ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07274","ConcCant":"1","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029146015269571","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:22:55","CodHabIPS":"080010022301","TipoIDIPS":"NI","NroIDIPS":"72125229","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 51B N94-334 CONS 206","TelSedeIPS":"3781924","TipoIDProf":"CC","NumIDProf":"72125229","PNProfS":"RODOLFO","SNProfS":"","PAProfS":"JALLER","SAProfS":"RAAD","RegProfS":"","TipoIDPaciente":"RC","NroIDPaciente":"1201230321","PNPaciente":"DILAN","SNPaciente":"ESTEBAN","PAPaciente":"RADA","SAPaciente":"LORDUY","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J450","CodDxRel1":"J304","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[{"ConOrden":1,"TipoPrest":2,"CausaS11":1,"CausaS12":null,"CausaS2":null,"CausaS3":0,"CausaS4":0,"ProPBSUtilizado":null,"CausaS5":1,"ProPBSDescartado":null,"RznCausaS51":1,"DescRzn51":"N.A.","RznCausaS52":0,"DescRzn52":null,"CausaS6":null,"CausaS7":null,"CodCUPS":"991202","CanForm":"1","CadaFreUso":"30","CodFreUso":3,"Cant":"3","CantTotal":"3","CodPerDurTrat":5,"JustNoPBS":"PACIENTE MASCULINO DE 7 AÑOS DE EDAD REMITIDO DEL SERVICIO DE NEUMOLOGIA ACUDE CON TOS SECA PERSISTENTE CON DX DE ASMA, RINOSINUSITIS Y URTICARIA DE 2 AÑOS DE EVOLUCIÓN SENSIBLE AL POLVO, OLORES FUERTES, CAMBIO DE CLIMA SIN ALERGIA A LOS ALIMENTOS NI MEDICAMENTOS SIN ANTECEDENTES FAMILIARES DE ALERGIA IGE 865 NORMAL 90 SE REALIZA TEST DE ALERGIA POSITIVO A ÁCAROS E INSECTOS ","IndRec":"APLICAR UNA DOSIS MENSUAL SIN PERDER CONTINUIDAD","EstJM":1}],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029194015270004","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:32:32","CodHabIPS":"080010054401","TipoIDIPS":"NI","NroIDIPS":"800194798","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 49 C NO 82-70","TelSedeIPS":null,"TipoIDProf":"CC","NumIDProf":"92532661","PNProfS":"ROGELIO","SNProfS":"CARLOS","PAProfS":"BRAVO","SAProfS":"MERCADO","RegProfS":"7029103","TipoIDPaciente":"CC","NroIDPaciente":"22395301","PNPaciente":"MYRIAM","SNPaciente":"DEL SOCORRO","PAPaciente":"VIZCAINO","SAPaciente":"UMAÑA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"C509","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":0,"CausaS6":null,"DescMedPrinAct":"[ANASTROZOL] 1mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"CA DE MAMA IZQUIERDA RECEPTORES HORMONALES POSITIVOS, HER 2 NEGATIVOS, Ki67: 8%, ypT2 ypN1 M0, EIIB, LUMINAL A, EN SEGUIMIENTO. OSTEOPOROSIS, QT NEOADYUVANTE AC-T, CCM MAS VAC 13/4/15 RADIOTERAPIA ADYUVANTE TOMA TAMOXIFENO 20MG/DIA DESDE SEPT/2015, EN LA CIITA ANTERIOR SE ROTO A ANASTRAZOL POR HIPERPLASIIA ENDOMETRIAL, REFIERE QUE EN SU EPS NO SE LA HAN ENTREGADO POR LO QUE CONTINUA TOMANDO TAMOXIFENO, SE INDICA USO DE ANASTRAZOL","Dosis":"1","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"180","DurTrat":3,"CantTotalF":"180","UFCantTotal":"66","IndRec":"DAR 1 TABLETA DE 1 MILIGRAMOS VIA ORAL CADA 24 HORAS POR 180 DIAS DE TRATAMIENTO","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07274","ConcCant":"1","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029146015270333","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:38:31","CodHabIPS":"761470851601","TipoIDIPS":"NI","NroIDIPS":"900472731","CodDANEMunIPS":"76147","DirSedeIPS":"CARRERA 1 #16-107","TelSedeIPS":"2146686","TipoIDProf":"CC","NumIDProf":"10110853","PNProfS":"JUAN","SNProfS":"CARLOS","PAProfS":"MEJIA","SAProfS":"RUBIANO","RegProfS":"1217","TipoIDPaciente":"CC","NroIDPaciente":"6109947","PNPaciente":"JORGE","SNPaciente":"","PAPaciente":"OSPINA","SAPaciente":"SIERRA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M478","CodDxRel1":"M481","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"NAPROXENO","RznCausaS31":1,"DescRzn31":"Fallo","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[LIDOCAINA] 700mg/1U","CodFF":"C42968","CodVA":"061","JustNoPBS":"Dolor lumbar CRÓNICO refractario a tratamiento con AINES ORALES ","Dosis":"700","DosisUM":"0168","NoFAdmon":"2","CodFreAdmon":3,"IndEsp":10,"CanTrat":"180","DurTrat":3,"CantTotalF":"90","UFCantTotal":"49","IndRec":"USAR UN PARCHE CADA DÍA DE POR MEDIO","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"04202","ConcCant":"700","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029198015270360","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:39:02","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"56078839","PNProfS":"TATIANA","SNProfS":"ELENA","PAProfS":"MENDOZA","SAProfS":"SALINA","RegProfS":"7093","TipoIDPaciente":"CC","NroIDPaciente":"49739631","PNPaciente":"MARIA","SNPaciente":"PATROCINIA","PAPaciente":"MEZA","SAPaciente":"RINCON","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J303","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"BECLOMETASONA","RznCausaS31":1,"DescRzn31":"SIN MEJORIA","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[TRIAMCINOLONA] 55µg/1Dosis","CodFF":"C42994","CodVA":"502","JustNoPBS":"\nPACIENETE CON CUADRO CLINICO DE LARGO PERIODO DE EVOLUCION CARACTERIZADO POR OBSTRUCCION NASAL, RINORREA HIALINA CON MUCOSA CONGESTIVA, SECRESION , ESTORNUDOS RECURRENTE CON HIPERTROFIA DE CORNETES EN TRATAMIENTO SIN MEJORIA POR LO QUE SOLICITO NASACORT TRIAMCINOLONA SPRAY CORTICOIDE LOCAL PARA DESINFLAMAR MUCOSA NASAL Y ERRADICAR PROCESO INFLAMATORIO Y ALERGICO\n","Dosis":"2","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"3","UFCantTotal":"13","IndRec":" ENTREGAR NASACORT SPRAY NASAL","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00755","ConcCant":"55","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029150015270414","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:40:12","CodHabIPS":"700010096901","TipoIDIPS":"NI","NroIDIPS":"900118990","CodDANEMunIPS":"70001","DirSedeIPS":"CARRERA 20 No. 13-31","TelSedeIPS":"2761605","TipoIDProf":"CC","NumIDProf":"1104010331","PNProfS":"SAYRA","SNProfS":"YAMILE","PAProfS":"VERGARA","SAProfS":"CASTILLO","RegProfS":"02580","TipoIDPaciente":"CC","NroIDPaciente":"23132549","PNPaciente":"LUCILA","SNPaciente":"ESTHER","PAPaciente":"BOHORQUEZ","SAPaciente":"DE TOVAR","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E441","CodDxRel1":"F009","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":1,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":0,"DXVIH":0,"DXCaPal":0,"DXEnfRCEV":0,"DXDesPro":null,"TippProNut":"1501","DescProdNutr":"150101","CodForma":"3","CodViaAdmon":1,"JustNoPBS":"PACIENTE CON DIAGNOSTICO PRINCIPAL CONFIRMADO, QUIEN REQUIERE ASISTENCIA INTRAHOSPITALARIA POR NUTRICIÓN Y DIETETICA, CON ALIMENTO HIPERPROTEICO 24.3, DENSAMENTE CALORICO 1.5KCAL ML, HMB, Y ALTO CONTENIDO DE VITAMINA D, INDICADO EN DESNUTRICIÓN, VÍA ORAL QUE PERMITA CUBRIR REQUERIMIENTOS NUTRICIONALES, COMO PARTE INTEGRAL DEL TRATAMIENTO PREVENIR COMPLICACIONES QUE AUMENTAN LA ESTANCIA HOSPITALARIA Y REDUCIR LA MORBIMORTALIDAD ","Dosis":"2","DosisUM":"9000","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":1,"CanTrat":"60","DurTrat":3,"CantTotalF":"120","UFCantTotal":"3","IndRec":"ALIMENTA HIPERPROTEICO, DENSAMENTE CALORICO CON HMB, Y ALTO CONTENIDO DE VITAMINA D, PARA USO ESPECIAL EN ADULTO MAYOR CON DESNUTRICION. ","NoPrescAso":null,"EstJM":3}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029140015270429","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:40:34","CodHabIPS":"134300290801","TipoIDIPS":"NI","NroIDIPS":"800033723","CodDANEMunIPS":"13430","DirSedeIPS":"CALLE 16 # 10a-143","TelSedeIPS":"3187117423","TipoIDProf":"CC","NumIDProf":"33307877","PNProfS":"KARINA","SNProfS":"ROSA","PAProfS":"PORTELA","SAProfS":"BALLESTEROS","RegProfS":"2832","TipoIDPaciente":"CC","NroIDPaciente":"22928327","PNPaciente":"YOLANDA","SNPaciente":"","PAPaciente":"PAYARES","SAPaciente":"VASQUEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R32X","CodDxRel1":"R15X","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[{"ConOrden":1,"TipoPrest":2,"CausaS1":0,"CausaS2":1,"CausaS3":null,"CausaS4":1,"DescCausaS4":"PCIENT CON SECUELAS DE ECV ISQUEMICO , INCONTINENCIA URINARIA Y VESICAL ","CausaS5":null,"CodSerComp":"139","DescSerComp":"TIPO ADULTO TALLA M ","CanForm":"270","CadaFreUso":"8","CodFreUso":2,"Cant":"90","CantTotal":"270","CodPerDurTrat":3,"TipoTrans":null,"ReqAcom":null,"TipoIDAcomAlb":null,"NroIDAcomAlb":null,"ParentAcomAlb":null,"NombAlb":null,"CodMunOriAlb":null,"CodMunDesAlb":null,"JustNoPBS":"INCONTINENCIA URINARIA Y RECTAL ","IndRec":"A NECESIDAD ","EstJM":1}]},{"prescripcion":{"NoPrescripcion":"20191029190015270672","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:45:15","CodHabIPS":"134300290801","TipoIDIPS":"NI","NroIDIPS":"800033723","CodDANEMunIPS":"13430","DirSedeIPS":"CALLE 16 # 10a-143","TelSedeIPS":"3187117423","TipoIDProf":"CC","NumIDProf":"73578467","PNProfS":"GABRIEL","SNProfS":"ANTONIO","PAProfS":"NAVARRO","SAProfS":"BARRIOS","RegProfS":"130403","TipoIDPaciente":"CC","NroIDPaciente":"22928327","PNPaciente":"YOLANDA","SNPaciente":"","PAPaciente":"PAYARES","SAPaciente":"VASQUEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R32X","CodDxRel1":"R15X","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[{"ConOrden":1,"TipoPrest":2,"CausaS1":0,"CausaS2":1,"CausaS3":null,"CausaS4":1,"DescCausaS4":"PAC CON SECUELSA DE ECV , INCONTINENCIA URIANRIA Y RECTAL","CausaS5":null,"CodSerComp":"139","DescSerComp":"TALLA M","CanForm":"270","CadaFreUso":"8","CodFreUso":2,"Cant":"90","CantTotal":"270","CodPerDurTrat":3,"TipoTrans":null,"ReqAcom":null,"TipoIDAcomAlb":null,"NroIDAcomAlb":null,"ParentAcomAlb":null,"NombAlb":null,"CodMunOriAlb":null,"CodMunDesAlb":null,"JustNoPBS":"INCONTINENCIA URINARIA Y RECTAL ","IndRec":"NO ","EstJM":1}]},{"prescripcion":{"NoPrescripcion":"20191029170015270841","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:48:45","CodHabIPS":"761470851601","TipoIDIPS":"NI","NroIDIPS":"900472731","CodDANEMunIPS":"76147","DirSedeIPS":"CARRERA 1 #16-107","TelSedeIPS":"2146686","TipoIDProf":"CC","NumIDProf":"10110853","PNProfS":"JUAN","SNProfS":"CARLOS","PAProfS":"MEJIA","SAProfS":"RUBIANO","RegProfS":"1217","TipoIDPaciente":"CC","NroIDPaciente":"6109947","PNPaciente":"JORGE","SNPaciente":"","PAPaciente":"OSPINA","SAPaciente":"SIERRA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M478","CodDxRel1":"M481","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"NAPROXENO","RznCausaS31":1,"DescRzn31":"fallo","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[ACETAMINOFEN] 325mg/1U ; [HIDROCODONA BITARTRATO] 5mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"dolor refractario a aines orales ","Dosis":"5","DosisUM":"0168","NoFAdmon":"8","CodFreAdmon":2,"IndEsp":10,"CanTrat":"180","DurTrat":3,"CantTotalF":"540","UFCantTotal":"66","IndRec":"Tomar 1 cada 8 horas","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00626","ConcCant":"325","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"01700","ConcCant":"5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029186015270844","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:48:58","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"56078839","PNProfS":"TATIANA","SNProfS":"ELENA","PAProfS":"MENDOZA","SAProfS":"SALINA","RegProfS":"7093","TipoIDPaciente":"CC","NroIDPaciente":"1065655663","PNPaciente":"MARIA","SNPaciente":"ANGELICA","PAPaciente":"MARTINEZ","SAPaciente":"TORREZ","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J303","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"BECLOMETASONA","RznCausaS31":1,"DescRzn31":"SIN MEJORIA ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[AZELASTINA CLORHIDRATO] 137µg/1Dosis ; [FLUTICASONA PROPIONATO] 50µg/1Dosis","CodFF":"C42994","CodVA":"045","JustNoPBS":"\nPACIENETE CON CUADRO CLINICO DE LARGO PERIODO DE EVOLUCION CARACTERIZADO POR OBSTRUCCION NASAL, RINORREA HIALINA CON MUCOSA CONGESTIVA, SECRESION , ESTORNUDOS RECURRENTE CON HIPERTROFIA DE CORNETES EN TRATAMIENTO SIN MEJORIA POR LO QUE SOLICITO ALERXY C SPRAY CORTICOIDE LOCAL PARA DESINFLAMAR MUCOSA NASAL Y ERRADICAR PROCESO INFLAMATORIO Y ALERGICO\n","Dosis":"2","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"3","UFCantTotal":"13","IndRec":"RECOMENDACION GENERAL","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"04074","ConcCant":"137","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"},{"ConOrden":1,"CodPriAct":"05636","ConcCant":"50","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029113015271031","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:53:15","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"56078839","PNProfS":"TATIANA","SNProfS":"ELENA","PAProfS":"MENDOZA","SAProfS":"SALINA","RegProfS":"7093","TipoIDPaciente":"CC","NroIDPaciente":"1065655663","PNPaciente":"MARIA","SNPaciente":"ANGELICA","PAPaciente":"MARTINEZ","SAPaciente":"TORREZ","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J450","CodDxRel1":"J303","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"LORATADINA","RznCausaS31":1,"DescRzn31":"SIN MEJORIA","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[MONTELUKAST] 10mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"\nPACIENETE CON CUADRO CLINICO DE LARGO PERIODO DE EVOLUCION CARACTERIZADO POR ASMA CON RINITIS ALERGICA OBSTRUCCION NASAL, RINORREA HIALINA CON MUCOSA CONGESTIVA, SECRESION , ESTORNUDOS RECURRENTE CON HIPERTROFIA DE CORNETES EN TRATAMIENTO SIN MEJORIA POR LO QUE SOLICITO MONTELUKAST CORTICOIDE LOCAL PARA DESINFLAMAR MUCOSA NASAL Y ERRADICAR PROCESO INFLAMATORIO Y ALERGICO\n","Dosis":"10","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"66","IndRec":"ENTREGAR SINGULAIR TAB","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07388","ConcCant":"10","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029116015271230","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:57:01","CodHabIPS":"087580106601","TipoIDIPS":"NI","NroIDIPS":"802009778","CodDANEMunIPS":"08758","DirSedeIPS":"CALLE 21 # 19-29 piso 2","TelSedeIPS":"3183766379","TipoIDProf":"CC","NumIDProf":"1129526150","PNProfS":"JORGE","SNProfS":"LUIS","PAProfS":"DUARTE","SAProfS":"PEÑALOZA","RegProfS":"1599-2011","TipoIDPaciente":"CC","NroIDPaciente":"22688991","PNPaciente":"MARIA","SNPaciente":"EULOGIA","PAPaciente":"ZAMBRANO","SAPaciente":"DE CHARRIS","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R32X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[{"ConOrden":1,"TipoPrest":2,"CausaS1":0,"CausaS2":1,"CausaS3":null,"CausaS4":1,"DescCausaS4":"PACIENTE QUE VIENE POR CUADRO CLINICO DE DIABETES Y HTA Y ECV QUE VIENE PARA CONTROL DE RCV POSTERIO A ESO PORTADORA DE SONDA DE GASTROCTOMIA, INGRESA CON REPOR","CausaS5":null,"CodSerComp":"139","DescSerComp":"1 PAÑAL DESECHABLE TALLA M CADA 8 HORAS POR 90 DIAS ","CanForm":"1","CadaFreUso":"8","CodFreUso":2,"Cant":"90","CantTotal":"270","CodPerDurTrat":3,"TipoTrans":null,"ReqAcom":null,"TipoIDAcomAlb":null,"NroIDAcomAlb":null,"ParentAcomAlb":null,"NombAlb":null,"CodMunOriAlb":null,"CodMunDesAlb":null,"JustNoPBS":"PACIENTE QUE VIENE POR CUADRO CLINICO DE DIABETES Y HTA Y ECV QUE VIENE PARA CONTROL DE RCV POSTERIO A ESO PORTADORA DE SONDA DE GASTROCTOMIA, INGRESA CON REPORTE DE H GLICOSILADA EN 6, CREATININA 1.6, GLUCOSA 93, HEMOGRAMA HB 10.3, PARCAIL DE ORINA CONTAMINADO, SE ORDENA MEIDCACION . INGRESA CON REPORTE DE HB GLICOSILADA 5.9, ELECTROLITOS NORMALES, PARCIAL DE ORINA NORMAL, HEMOGRAMA NORMAL, CREATININA 1.8, GLUCOSA 78, PACIENTE CON INCONTINENCIA URINARIA POR LO QUE SE ORDENAN PAÑALES DESECHABLES","IndRec":"1 PAÑAL DESECHABLE TALLA M CADA 8 HORAS POR 90 DIAS ","EstJM":1}]},{"prescripcion":{"NoPrescripcion":"20191029155015271287","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:58:04","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"1065920745","PNProfS":"JOSE","SNProfS":"ANTONIO","PAProfS":"ALVAREZ","SAProfS":"CHACON","RegProfS":"1065920745","TipoIDPaciente":"CC","NroIDPaciente":"26768170","PNPaciente":"ANA","SNPaciente":"DILIA","PAPaciente":"CAÑA","SAPaciente":"CASTRILLO","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E119","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"METFORMINA|GLIBENCLAMIDA","RznCausaS31":1,"DescRzn31":"PACIENTE CON ESCASA RESPUESTA TERAPEUTICA PARA CONTROL METABOLICO.","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[METFORMINA CLORHIDRATO] 1000mg/1U ; [SITAGLIPTINA] 50mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"PACIENTE CON ESCASA RESPUESTA TERAPEUTICA PARA CONTROL METABOLICO, AMERITA MEDICACION NO POS PARA MANTENER ADECUADO CONTROL Y REDUCIR RIESGO CARDIOVASCULAR","Dosis":"1","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"84","DurTrat":3,"CantTotalF":"168","UFCantTotal":"66","IndRec":"TOMAR UNA CADA 12 HORAS","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00965","ConcCant":"1000","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"08692","ConcCant":"50","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029180015271344","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"09:59:07","CodHabIPS":"200010222801","TipoIDIPS":"NI","NroIDIPS":"901243826","CodDANEMunIPS":"20001","DirSedeIPS":"Carrera 16 No. 14-98 primer piso","TelSedeIPS":"5808545 - 3157250241 - 3017553599","TipoIDProf":"CC","NumIDProf":"8703687","PNProfS":"JOSE","SNProfS":"DE JESUS","PAProfS":"RODRIGUEZ","SAProfS":"ANGULO","RegProfS":"2582","TipoIDPaciente":"CC","NroIDPaciente":"77028643","PNPaciente":"TOMAS","SNPaciente":"ENRIQUE","PAPaciente":"MOLINA","SAPaciente":"PABON","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"A048","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[CLARITROMICINA] 500mg/1U","CodFF":"COLFF008","CodVA":"048","JustNoPBS":"SE REALIZA PROCEDIMIENTO DE EGD GASTRITIS H. PILORY POSITIVA SE LE INDICA TTO CON CLARITROMICINA 500 MG se usa para tratar ciertas infecciones bacterianas Se usa en combinación con otros medicamentos para eliminar el H. pylori, una bacteria que causa úlceras. La claritromicina pertenece a una clase de medicamentos llamados antibióticos macrólidos. Su acción consiste en detener el crecimiento de las bacterias","Dosis":"500","DosisUM":"0168","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"14","DurTrat":3,"CantTotalF":"28","UFCantTotal":"66","IndRec":"TOMAR 1 TABLETA VIA ORAL CADA 12 HORAS TTO DURANTE 14 DIAS","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"06252","ConcCant":"500","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029164015271556","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:03:23","CodHabIPS":"080010112201","TipoIDIPS":"NI","NroIDIPS":"890108597","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 42F No.75B-18","TelSedeIPS":"3565109","TipoIDProf":"CC","NumIDProf":"32860670","PNProfS":"MARGARITA","SNProfS":"MARIA","PAProfS":"JIMENEZ","SAProfS":"UTRIA","RegProfS":"01650","TipoIDPaciente":"RC","NroIDPaciente":"1047347317","PNPaciente":"SHAYRA","SNPaciente":"","PAPaciente":"MANGA","SAPaciente":"BARRAZA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E45X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":1,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":1,"DXVIH":null,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1701","DescProdNutr":"170147","CodForma":"3","CodViaAdmon":1,"JustNoPBS":"PACIENTE DE SEXO FEMENINO CON DX Retardo del desarrollo debido a DESNUTRICIÓN proteicocalorica,DISFAGIA MODERADA, QUE DESDE SU ALIMENTICIO CONVENCIONAL NO CUBRE CON LAS REQUERIMIENTOS DE MACRO MICRO NUTRIENTES REQUIERE SOPORTE LIQUIDO DE MACRO Y MICRO NUTRIENTES COMPLETO Y BALANCEADOS AJUSTADO A SUS NECESIDADES Y PATOLOGÍA ACTUAL ","Dosis":"1","DosisUM":"9000","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":1,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"3","IndRec":"CONSUMIR 1 DOSIS VIA A TEMPERATURA AMBIENTE UNA VEZ ABIERTA CONSUMIR ","NoPrescAso":null,"EstJM":1}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029130015271600","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:04:07","CodHabIPS":"080010122201","TipoIDIPS":"NI","NroIDIPS":"890116783","CodDANEMunIPS":"08001","DirSedeIPS":"CRA 52 No 84 - 98","TelSedeIPS":"3781220","TipoIDProf":"CC","NumIDProf":"17065305","PNProfS":"OSCAR","SNProfS":"DANIEL","PAProfS":"ALVIS","SAProfS":"GONZALEZ","RegProfS":"707","TipoIDPaciente":"RC","NroIDPaciente":"1066892004","PNPaciente":"LUIS","SNPaciente":"JOSE","PAPaciente":"MENDEZ","SAPaciente":"TORRES","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"H103","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[KETOTIFENO] 0,5mg/1ml","CodFF":"COLFF004","CodVA":"068","JustNoPBS":"PACIENTE REQUIERE DEL MEDICAMENTO PARA CONTROLAR ALERGIA OCULAR ","Dosis":"1","DosisUM":"0046","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"3","UFCantTotal":"13","IndRec":"1 GOTA CADA 12 HORAS","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"03993","ConcCant":"0,5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029118015271637","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:04:47","CodHabIPS":"760200165701","TipoIDIPS":"NI","NroIDIPS":"891900438","CodDANEMunIPS":"76020","DirSedeIPS":"SEDE BARRIO VILLA FERNANDA","TelSedeIPS":"2004120","TipoIDProf":"CC","NumIDProf":"1088299161","PNProfS":"PABLO","SNProfS":"","PAProfS":"BOTERO","SAProfS":"MORENO","RegProfS":"1088299161","TipoIDPaciente":"CC","NroIDPaciente":"22242688","PNPaciente":"NEIRA","SNPaciente":"LUZ","PAPaciente":"RENTERIA","SAPaciente":"MATURANA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E109","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[EMPAGLIFLOZINA] 12,5mg/1U ; [METFORMINA CLORHIDRATO] 850mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"paciente diabetica sin adecuado control con medicamentos de primera linea ","Dosis":"1","DosisUM":"9000","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"66","IndRec":"tomar 1 cada dia con el almuerzo por 3 meses","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00965","ConcCant":"850","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"09398","ConcCant":"12,5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[METFORMINA CLORHIDRATO] 1000mg/1U ; [SITAGLIPTINA] 100mg/1U","CodFF":"COLFF008","CodVA":"048","JustNoPBS":"PACIENTE DIABETICA SIN ADECUADO CONTROL CON MEDICAMENTOS DE PRIMERA LINEA ","Dosis":"1","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":1,"CantTotalF":"180","UFCantTotal":"66","IndRec":"tomar 1 tableta con el desayuno y con el almuerzo","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"00965","ConcCant":"1000","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":2,"CodPriAct":"08692","ConcCant":"100","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029157015271841","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:08:40","CodHabIPS":"130010178101","TipoIDIPS":"NI","NroIDIPS":"900042103","CodDANEMunIPS":"13001","DirSedeIPS":"CALLE 29 No 50-50","TelSedeIPS":"6726017","TipoIDProf":"CC","NumIDProf":"7920060","PNProfS":"JUAN","SNProfS":"CARLOS","PAProfS":"VELEZ","SAProfS":"ROMAN","RegProfS":"23104505","TipoIDPaciente":"CC","NroIDPaciente":"9127596","PNPaciente":"PLINIO","SNPaciente":"MIGUEL","PAPaciente":"VASQUEZ","SAPaciente":"PARDO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R32X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[{"ConOrden":1,"TipoPrest":2,"CausaS1":0,"CausaS2":1,"CausaS3":null,"CausaS4":1,"DescCausaS4":"paciente con incontinencia urinaria, posterior a prostatectomia radical por cancer de prostata ","CausaS5":null,"CodSerComp":"139","DescSerComp":"pañales, adulto talla m. ","CanForm":"1","CadaFreUso":"6","CodFreUso":2,"Cant":"90","CantTotal":"360","CodPerDurTrat":3,"TipoTrans":null,"ReqAcom":null,"TipoIDAcomAlb":null,"NroIDAcomAlb":null,"ParentAcomAlb":null,"NombAlb":null,"CodMunOriAlb":null,"CodMunDesAlb":null,"JustNoPBS":"incontinencia urinaria, posterior a prostatectomia radical ","IndRec":"un pañal cada 4 horas. ","EstJM":1}]},{"prescripcion":{"NoPrescripcion":"20191029127015272368","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:18:47","CodHabIPS":"080010003601","TipoIDIPS":"NI","NroIDIPS":"802000955","CodDANEMunIPS":"08001","DirSedeIPS":"CRA. 51B # 84-150","TelSedeIPS":null,"TipoIDProf":"CC","NumIDProf":"1045754222","PNProfS":"JOHANNA","SNProfS":"MARIA","PAProfS":"ROMERO","SAProfS":"VALENCIA","RegProfS":"1045754222","TipoIDPaciente":"CC","NroIDPaciente":"32863312","PNPaciente":"MARIA","SNPaciente":"ELENA","PAPaciente":"SANDOVAL","SAPaciente":"DE LA HOZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"H400","CodDxRel1":"H101","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[CONDROITINA SULFATO SODICA] 1,8mg/1ml ; [HIALURONATO DE SODIO] 1mg/1ml","CodFF":"COLFF004","CodVA":"047","JustNoPBS":"PACIENTE QUE REQUIERE LUBRICACIÓN Y HUMECTACIÓN POR PRESENTAR ENFERMEDAD DE OJO SECO. ADEMAS DISMINUYE EL TRAUMA DEL PARPADEO, LO QUE PERMITE UNA MEJOR ADHERENCIA Y CICATRIZACIÓN EPITELIAL PROPORCIONANDO A SU VEZ ALIVIO DE LOS SÍNTOMAS. PROMUEVE LA PROLIFERACIÓN CELULAR Y LA MIGRACIÓN EPITELIAL CORNEAL","Dosis":"2","DosisUM":"0046","NoFAdmon":"8","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"2","UFCantTotal":"13","IndRec":"Solucion Oftalmica Frasco x 15 mL, 1 GOTA cada 8 Hora(s) en Ambos ojos por 3 Mes(es) Suministar: 2 Frasco(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50057","ConcCant":"1,8","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"},{"ConOrden":1,"CodPriAct":"50096","ConcCant":"1","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[KETOTIFENO] 0,25mg/1ml","CodFF":"COLFF004","CodVA":"068","JustNoPBS":"PACIENTE QUE REQUIERE MEDICAMENTO ANTIALERGICO PARA DISMINUIR SINTOMATOLOGIA DE PRURITO Y SENSACION DE CUERPO EXTRAÑO Y ASI PROPORCIONAR \nMEJORIA CLINICA. INDICADO EN EL ALIVIO DE LOS SÍNTOMAS Y SIGNOS DE LA CONJUNTIVITIS ALÉRGICA.\n \t","Dosis":"2","DosisUM":"0046","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"1","DurTrat":5,"CantTotalF":"2","UFCantTotal":"13","IndRec":"Solucion Oftalmica Frasco X 5 mL, 1 GOTA cada 12 Hora(s) en Ambos ojos por 1 Mes(es) Suministar: 2 Frasco(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"03993","ConcCant":"0,25","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029169015272418","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:19:44","CodHabIPS":"700010146401","TipoIDIPS":"NI","NroIDIPS":"900581036","CodDANEMunIPS":"70001","DirSedeIPS":"CR 22 CL 18 - 109 P1","TelSedeIPS":"2807683","TipoIDProf":"CC","NumIDProf":"92508651","PNProfS":"APOLINAR","SNProfS":"ANTONIO","PAProfS":"OSPINA","SAProfS":"CAMACHO","RegProfS":"4525-94","TipoIDPaciente":"CC","NroIDPaciente":"22836560","PNPaciente":"ELISA","SNPaciente":"ISABEL","PAPaciente":"MONTIEL","SAPaciente":"CARDOZO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"K295","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":0,"CausaS6":null,"DescMedPrinAct":"[LEVOFLOXACINO] 500mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"PX PRESENTA RESULTADO ENDOSCOPIA: GASTRITIS ERITOMATOSA ANTRAL. PATOLOGIA: GASTRITIS CRONICA ASOCIADA A H PYLORI. SE FORMULA LEVOFLOXACINA ESOMEPRAZOL Y AMOXACILINA COMO TERAPIA DE ERRADICACION DE LA BACTERIA H.PYLORY ","Dosis":"1","DosisUM":"9000","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"14","DurTrat":3,"CantTotalF":"14","UFCantTotal":"66","IndRec":"TOMAR UNA TABLETA DIARIA POR 14 DIAS.","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"06708","ConcCant":"500","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029156015272755","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:26:02","CodHabIPS":"700010151301","TipoIDIPS":"NI","NroIDIPS":"830510991","CodDANEMunIPS":"70001","DirSedeIPS":"CALLE 38 N° 52-249","TelSedeIPS":"2806901","TipoIDProf":"CC","NumIDProf":"72272753","PNProfS":"LUIS","SNProfS":"RODRIGO","PAProfS":"TABOADA","SAProfS":"GONZALEZ","RegProfS":"7014","TipoIDPaciente":"CC","NroIDPaciente":"23196983","PNPaciente":"JOSEFINA","SNPaciente":"DEL CARMEN","PAPaciente":"VILARO","SAPaciente":"MONROY","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"H813","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"DIMENHIDRINATO","RznCausaS31":1,"DescRzn31":"mala respuesta al medicamento, ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[BETAHISTINA DICLORHIDRATO] 24mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"paciente en seguimiento por ottorruinolaringologia por vrtigo periférico refractario a tratamientos, asociados a nauseas cuando se presentan, se definio por parte de su especialidad debe recibir manejo con betahistina para mejorar los sintomas y por ende calidad de vida de la paciente.","Dosis":"24","DosisUM":"0168","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"30","DurTrat":3,"CantTotalF":"60","UFCantTotal":"66","IndRec":"tomar una tableta vo cada 12 horas por un mes","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"01502","ConcCant":"24","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"DIMENHIDRINATO","RznCausaS31":1,"DescRzn31":"vertigo refractario a este manejo","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[MECLOZINA CLORHIDRATO] 25mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"paciente en seguimiento por otorrinolaringologia por vertigo periférico refractario a tratamientos, asociados a nauseas cuando se presentan, se definio por parte de su especialidad debe recibir manejo con betahistina para mejorar los sintmas y por ende calidad de vida de la paciente.","Dosis":"25","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"30","DurTrat":3,"CantTotalF":"30","UFCantTotal":"66","IndRec":"tomar una tableta vo noche por 10 días, y posterior por mareos muy fuertes","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"50387","ConcCant":"25","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029193015272951","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:30:35","CodHabIPS":"080010030801","TipoIDIPS":"NI","NroIDIPS":"800218024","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 50 No. 80-216","TelSedeIPS":"3852808","TipoIDProf":"CC","NumIDProf":"19345831","PNProfS":"MIGUEL","SNProfS":"ALBERTO","PAProfS":"URINA","SAProfS":"TRIANA","RegProfS":"7070/83","TipoIDPaciente":"CC","NroIDPaciente":"3746340","PNPaciente":"LUCIANO","SNPaciente":"","PAPaciente":"GERONIMO","SAPaciente":"CASTRO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"I480","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[RIVAROXABAN] 20mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"paciente con fibrilacion auricular paroxitica con alto riesgo cardioembolico ","Dosis":"20","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"74","IndRec":"una vez al dia ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"08428","ConcCant":"20","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029133015272989","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:31:07","CodHabIPS":"134300290801","TipoIDIPS":"NI","NroIDIPS":"800033723","CodDANEMunIPS":"13430","DirSedeIPS":"CALLE 16 # 10a-143","TelSedeIPS":"3187117423","TipoIDProf":"CC","NumIDProf":"73578467","PNProfS":"GABRIEL","SNProfS":"ANTONIO","PAProfS":"NAVARRO","SAProfS":"BARRIOS","RegProfS":"130403","TipoIDPaciente":"CC","NroIDPaciente":"33211183","PNPaciente":"MARITZA","SNPaciente":"","PAPaciente":"NIÑO","SAPaciente":"ITURRIAGO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E119","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"METFORMINA","RznCausaS31":1,"DescRzn31":"SIN CONTROL METABOLICO ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[METFORMINA CLORHIDRATO] 850mg/1U ; [VILDAGLIPTINA] 50mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"PACIENTE DIABETICO CON CONTROL MEATOBLICO ","Dosis":"1","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"180","UFCantTotal":"66","IndRec":"NO","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00965","ConcCant":"850","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"08399","ConcCant":"50","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029160015273298","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:35:23","CodHabIPS":"130010118701","TipoIDIPS":"NI","NroIDIPS":"890480135","CodDANEMunIPS":"13001","DirSedeIPS":"Transversal 36 No 36-33","TelSedeIPS":"(095)6475420","TipoIDProf":"CC","NumIDProf":"60336528","PNProfS":"CLAUDIA","SNProfS":"PATRICIA","PAProfS":"DURAN","SAProfS":"BOTELLO","RegProfS":"","TipoIDPaciente":"TI","NroIDPaciente":"1193228141","PNPaciente":"JESUS","SNPaciente":"DANIEL","PAPaciente":"REYES","SAPaciente":"BERTEL","CodAmbAte":30,"RefAmbAte":0,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M321","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[{"ConOrden":1,"TipoPrest":1,"CausaS11":1,"CausaS12":null,"CausaS2":null,"CausaS3":0,"CausaS4":0,"ProPBSUtilizado":null,"CausaS5":1,"ProPBSDescartado":null,"RznCausaS51":1,"DescRzn51":"N.A.","RznCausaS52":0,"DescRzn52":null,"CausaS6":null,"CausaS7":null,"CodCUPS":"906841","CanForm":"1","CadaFreUso":"1","CodFreUso":3,"Cant":"1","CantTotal":"1","CodPerDurTrat":4,"JustNoPBS":"Paciente remitido de clinica la concepcion con diagnostico de lupus eritematoso sistemico mas sindrome febril prolongado,se solicitan paraclinicospara evidenciar funcion renal ","IndRec":"se realizar paraclinco procalcitonina","EstJM":1}],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029113015273452","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:38:07","CodHabIPS":"700010111401","TipoIDIPS":"NI","NroIDIPS":"900217343","CodDANEMunIPS":"70001","DirSedeIPS":"CALLE 14 NUMERO 20 - 53","TelSedeIPS":"2714280","TipoIDProf":"CC","NumIDProf":"71339047","PNProfS":"SERGIO","SNProfS":"ANDRES","PAProfS":"VELEZ","SAProfS":"OSORIO","RegProfS":"574004","TipoIDPaciente":"CC","NroIDPaciente":"6796841","PNPaciente":"RAMON","SNPaciente":"DEMETRIO","PAPaciente":"VARELA","SAPaciente":"LOPEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"H401","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"LATANOPROST","RznCausaS31":1,"DescRzn31":"en uso","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[BRIMONIDINA TARTRATO] 2mg/1ml ; [DORZOLAMIDA] 20mg/1ml ; [TIMOLOL] 5mg/1ml","CodFF":"COLFF004","CodVA":"047","JustNoPBS":"PCTE QUE ASISTE A CONSULTA POR PRESENTAR ARDOR EN AO, CON ANT DE GLAUCOMA CRONICO, EN TTO CON KRYTANTEK Y LATANOPROST. BIO: CORNEA CLARA EN AO, C.A FORMADA EN AO, PIO: 17/12 MMGH AO, FDO: RETINA APLICADA EN AO.DX; GLAUCMA CRONICO EN AO, PLAN: KRYTANTEK APLICAR 1 GOTA CADA 12 HORAS EN AO ( NO SUSPENDER ) , LATANOPROST 0,005% APLICAR 1 GOTA CADA NOCHE EN AO ( NO SUSPENDER ), SE ORDENA ANTIGLAUCOMATOSO CONJUGADO PARA LOS CUALES LA MONOTERAPIA NO SUMINISTRA LA SUFICIENTE REDUCCIÓN DE LA PIO","Dosis":"2","DosisUM":"0046","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"6","DurTrat":5,"CantTotalF":"6","UFCantTotal":"13","IndRec":"APLICAR EN AMBOS OJOS CADA 12 HORAS, USAROPORTUNAMENTE, SE LE FORMULA KRYTANTEK PARA MEJOR CONTROL PATOLOGICO","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"03415","ConcCant":"5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"},{"ConOrden":1,"CodPriAct":"06863","ConcCant":"2","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"},{"ConOrden":1,"CodPriAct":"06960","ConcCant":"20","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029157015273622","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:41:27","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"18937856","PNProfS":"JORGE","SNProfS":"LUIS","PAProfS":"MURGAS","SAProfS":"GOMEZ","RegProfS":"0776","TipoIDPaciente":"CC","NroIDPaciente":"39086244","PNPaciente":"DENIS","SNPaciente":"MARIA","PAPaciente":"MORON","SAPaciente":"HERNANDEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M542","CodDxRel1":"M549","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"PARACETAMOL","RznCausaS31":1,"DescRzn31":"FALLA TERAPEUTICA","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[ACETAMINOFEN] 325mg/1U ; [CODEINA FOSFATO] 30mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"PACIENTE CON DOLOR DE TIPO MECÁNICO A NIVEL DE COLUMNA CERVICAL DORSAL Y LUMBAR, TRATADA CON MEDICAMENTOS DEL PBS SIN PRESENTAR MEJORÍA. SE INDICA ACETAMINOFEN CON CODEINA COMO ALTERNATIVA TERAPÉUTICA PARA EL MANEJO DEL DOLOR","Dosis":"1","DosisUM":"0247","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"4","DurTrat":5,"CantTotalF":"240","UFCantTotal":"66","IndRec":"TOMAR 1 TAB CADA 12 HORAS","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00626","ConcCant":"325","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"50055","ConcCant":"30","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029193015273862","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:45:28","CodHabIPS":"134300290801","TipoIDIPS":"NI","NroIDIPS":"800033723","CodDANEMunIPS":"13430","DirSedeIPS":"CALLE 16 # 10a-143","TelSedeIPS":"3187117423","TipoIDProf":"CC","NumIDProf":"73578467","PNProfS":"GABRIEL","SNProfS":"ANTONIO","PAProfS":"NAVARRO","SAProfS":"BARRIOS","RegProfS":"130403","TipoIDPaciente":"CC","NroIDPaciente":"33196796","PNPaciente":"KATIA","SNPaciente":"DEL CARMEN","PAPaciente":"RODRIGUEZ","SAPaciente":"CORREA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J459","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"SALBUTAMOL","RznCausaS31":1,"DescRzn31":"NO CONTROL ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[FLUTICASONA PROPIONATO] 250µg/1Dosis ; [SALMETEROL] 50µg/1Dosis","CodFF":"COLFF002","CodVA":"055","JustNoPBS":"ASMA NO CONTROLADA ","Dosis":"2","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"3","UFCantTotal":"37","IndRec":"NO","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"05578","ConcCant":"50","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"},{"ConOrden":1,"CodPriAct":"05636","ConcCant":"250","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"KETOTIFENO","RznCausaS31":1,"DescRzn31":"NO CONTROL ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[MONTELUKAST] 10mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"ASM A DE PREDOMINIO ALERGICO ","Dosis":"1","DosisUM":"9000","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"NO","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"07388","ConcCant":"10","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029129015273877","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:45:45","CodHabIPS":"134300077501","TipoIDIPS":"NI","NroIDIPS":"823002800","CodDANEMunIPS":"13430","DirSedeIPS":"Cra 14 n 3-48 piso 1","TelSedeIPS":"3107315890","TipoIDProf":"CC","NumIDProf":"33205215","PNProfS":"MARIA","SNProfS":"ISABEL","PAProfS":"BARRIOS","SAProfS":"TRESPALACIOS","RegProfS":"159196","TipoIDPaciente":"CC","NroIDPaciente":"26905588","PNPaciente":"GIL","SNPaciente":"MARIA","PAPaciente":"DIAZ","SAPaciente":"CANO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"H544","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[RANIBIZUMAB] 10mg/1ml","CodFF":"COLFF004","CodVA":"031","JustNoPBS":"MOTIVO DE CONSULTA: CONTROL \n\nANTECEDENTES PERSONALES: SDI \n\nANTECEDENTES FAMILIARES : SIN DATOS DE IMPORTANCIA \n\nBIO\nOD: CONJUNTIVA HIPEREMIA MODERADA HIFEMA 3.0 % PTOSI PALPEBRAL TINDAL HEMATICO\n\n\n\nOI: CONJUNTIVA SANA, CORNEA CLARA, CAMARA ANTERIOR FORMADA +, PUPILA DISCORICA ATROFIA DEL IRIS , AFAQUIA \n\nPIO\nODI:12/10 MMHG\n\nFONDO DE OJO\nOD : NO VALORABLE \nOI EXC 0,2 MM ,RETINA APLICADA ALTERACIONES DEL EPR ","Dosis":"1","DosisUM":"9000","NoFAdmon":"1","CodFreAdmon":5,"IndEsp":1,"CanTrat":"1","DurTrat":5,"CantTotalF":"1","UFCantTotal":"01","IndRec":"se usa para tratar hinchazón en la retina causada por la diabetes, o por un bloqueo en los vasos sanguíneos.","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"08313","ConcCant":"10","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029112015274462","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:57:49","CodHabIPS":"080010030801","TipoIDIPS":"NI","NroIDIPS":"800218024","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 50 No. 80-216","TelSedeIPS":"3852808","TipoIDProf":"CC","NumIDProf":"19345831","PNProfS":"MIGUEL","SNProfS":"ALBERTO","PAProfS":"URINA","SAProfS":"TRIANA","RegProfS":"7070/83","TipoIDPaciente":"CC","NroIDPaciente":"22338082","PNPaciente":"ANA","SNPaciente":"","PAPaciente":"CELIS","SAPaciente":"ROCHA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"I480","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[RIVAROXABAN] 15mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"paciente con alto riesgo cardioembolico con indicacion de anticoagulacion permanente ","Dosis":"15","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"74","IndRec":"tmar una al dia ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"08428","ConcCant":"15","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029179015274493","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"10:58:21","CodHabIPS":"130010178101","TipoIDIPS":"NI","NroIDIPS":"900042103","CodDANEMunIPS":"13001","DirSedeIPS":"CALLE 29 No 50-50","TelSedeIPS":"6726017","TipoIDProf":"CC","NumIDProf":"73080662","PNProfS":"ALFONSO","SNProfS":"LUIS","PAProfS":"MARTINEZ","SAProfS":"VISBAL","RegProfS":"85794","TipoIDPaciente":"CC","NroIDPaciente":"23190772","PNPaciente":"AMPARO","SNPaciente":"","PAPaciente":"UTRIA","SAPaciente":"PAYARES","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M069","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"PREDNISOLONA","RznCausaS31":0,"DescRzn31":null,"RznCausaS32":1,"DescRzn32":"Intolerancia al medicamento, hiperglucemia. ","CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[DEFLAZACORT] 6mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"Paciente con artritis reumatoide, requiere manejo con deflazacort para control de sintomas y evitar erosiones oseas, no toleró prednisolona. ","Dosis":"6","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"Tomar una tableta cada dia por 90 dias ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"04323","ConcCant":"6","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029185015274695","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:01:49","CodHabIPS":"130010314101","TipoIDIPS":"NI","NroIDIPS":"901031682","CodDANEMunIPS":"13001","DirSedeIPS":"CALLE 30 AV. PEDRO DE HEREDIA No. 35-119","TelSedeIPS":"6448161","TipoIDProf":"CC","NumIDProf":"32850937","PNProfS":"TANIA","SNProfS":"MARGARITA","PAProfS":"BUSTILLO","SAProfS":"NAVARRO","RegProfS":"0893701","TipoIDPaciente":"RC","NroIDPaciente":"1043320998","PNPaciente":"JOSHUA","SNPaciente":"ANTONIO","PAPaciente":"BONILLA","SAPaciente":"DE ARCO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"K590","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"MAGNESIO HIDROXIDO","RznCausaS31":1,"DescRzn31":"persistencia de estreñimiento cronico con encopresis.","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[POLIETILENGLICOL] 100g/100g","CodFF":"COLFF003","CodVA":"048","JustNoPBS":"preescolar con historia de estreñimiento de mas de 2 años de evolucion manejado con dieta y de forma intermitente con hidroxido de magnesio sin lograr mejoria, presentando habito fecal cada 4 a 7 dias y con episodios de encopresis que afectan su calidad de vida.","Dosis":"17","DosisUM":"0062","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"20","DurTrat":3,"CantTotalF":"2","UFCantTotal":"13","IndRec":"dar 17 gramos cada noche disueltos en medio vaso de agua.","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50022","ConcCant":"100","UMedConc":"0062","CantCont":"100","UMedCantCont":"0062"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029117015274724","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:02:14","CodHabIPS":"200010001801","TipoIDIPS":"NI","NroIDIPS":"900008328","CodDANEMunIPS":"20001","DirSedeIPS":"CARRERA 19 NÚMERO 14-47","TelSedeIPS":"PBX.5803535","TipoIDProf":"CC","NumIDProf":"43979036","PNProfS":"LAURA","SNProfS":"ROSA","PAProfS":"MENDOZA","SAProfS":"ROSADO","RegProfS":"1652008","TipoIDPaciente":"NV","NroIDPaciente":"155254262","PNPaciente":"LILIANA","SNPaciente":"","PAPaciente":"RIVERO","SAPaciente":"CARDENAS","CodAmbAte":22,"RefAmbAte":0,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"P351","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":"CC","NroIDMadrePaciente":"1002277192","TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[GANCICLOVIR] 50mg/1ml","CodFF":"COLFF003","CodVA":"042","JustNoPBS":"PACIENTE CON ANTECEDENTE DE PREMATUREZ. SE DOCUMENTA: AFECTACION HEMATOLOGICA Y GASTROINTESTINAL.PCR EN ORINA CMV CONFIRMANDO INFECCION CONGENITA.","Dosis":"7,2","DosisUM":"0168","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"14","DurTrat":3,"CantTotalF":"28","UFCantTotal":"01","IndRec":"APLICAR 7.2 MG CADA 12 HORAS IV","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"06023","ConcCant":"50","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029162015274930","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:05:54","CodHabIPS":"080010122201","TipoIDIPS":"NI","NroIDIPS":"890116783","CodDANEMunIPS":"08001","DirSedeIPS":"CRA 52 No 84 - 98","TelSedeIPS":"3781220","TipoIDProf":"CC","NumIDProf":"17065305","PNProfS":"OSCAR","SNProfS":"DANIEL","PAProfS":"ALVIS","SAProfS":"GONZALEZ","RegProfS":"707","TipoIDPaciente":"TI","NroIDPaciente":"1041693436","PNPaciente":"DANIELA","SNPaciente":"YULIZA","PAPaciente":"MANJARREZ","SAPaciente":"MENESES","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"H104","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[OLOPATADINA] 2mg/1ml","CodFF":"COLFF004","CodVA":"068","JustNoPBS":"PACIENTE REQUIERE DEL MEDICAMENTO PARA CONTROLAR ALERGIA OCULAR","Dosis":"1","DosisUM":"0046","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"3","UFCantTotal":"13","IndRec":"1 GOTA CADA 24 HORAS","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07257","ConcCant":"2","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029189015275058","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:07:39","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"56078839","PNProfS":"TATIANA","SNProfS":"ELENA","PAProfS":"MENDOZA","SAProfS":"SALINA","RegProfS":"7093","TipoIDPaciente":"CC","NroIDPaciente":"49609048","PNPaciente":"MONICA","SNPaciente":"PATRICIA","PAPaciente":"VERGARA","SAPaciente":"ESPAÑA","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J303","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"BECLOMETASONA","RznCausaS31":1,"DescRzn31":"SIN MEJORIA ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[AZELASTINA CLORHIDRATO] 137µg/1Dosis ; [FLUTICASONA PROPIONATO] 50µg/1Dosis","CodFF":"C42994","CodVA":"045","JustNoPBS":"\nPACIENETE CON CUADRO CLINICO DE LARGO PERIODO DE EVOLUCION CARACTERIZADO POR OBSTRUCCION NASAL, RINORREA HIALINA CON MUCOSA CONGESTIVA, SECRESION , ESTORNUDOS RECURRENTE CON HIPERTROFIA DE CORNETES EN TRATAMIENTO SIN MEJORIA POR LO QUE SOLICITO ALERXY C SPRAY CORTICOIDE LOCAL PARA DESINFLAMAR MUCOSA NASAL Y ERRADICAR PROCESO INFLAMATORIO Y ALERGICO\n","Dosis":"2","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"2","DurTrat":5,"CantTotalF":"2","UFCantTotal":"13","IndRec":"ENTREGAR ALERXY C SPRAY NASAL ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"04074","ConcCant":"137","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"},{"ConOrden":1,"CodPriAct":"05636","ConcCant":"50","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029115015275150","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:09:21","CodHabIPS":"080010112201","TipoIDIPS":"NI","NroIDIPS":"890108597","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 42F No.75B-18","TelSedeIPS":"3565109","TipoIDProf":"CC","NumIDProf":"32860670","PNProfS":"MARGARITA","SNProfS":"MARIA","PAProfS":"JIMENEZ","SAProfS":"UTRIA","RegProfS":"01650","TipoIDPaciente":"CC","NroIDPaciente":"72246389","PNPaciente":"JOSE","SNPaciente":"OMAR","PAPaciente":"GONZALEZ","SAPaciente":"CORONADO","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E43X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":2,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":1,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1301","DescProdNutr":"130109","CodForma":"9","CodViaAdmon":1,"JustNoPBS":"PACIENTE CON SÍNDROME INMUNODEFICIENCIA ADQUIRIDA, PERDIDA DE PESO,MASA,TEJIDO MUSCULAR QUE DESDE SU ALIMENTACIÓN CONVENCIONAL NO CUBRE LOS REQUERIMIENTO DE MACRO Y MICRO NUTRIENTES NECESARIOS PARA SU PATOLOGÍA BASE.","Dosis":"3","DosisUM":"0247","NoFAdmon":"8","CodFreAdmon":2,"IndEsp":1,"CanTrat":"3","DurTrat":5,"CantTotalF":"270","UFCantTotal":"9","IndRec":"CONSUMIR 3 SOBRES DÍA FRACCIONADOS UNA VEZ ABIERTO CONSUMIR ","NoPrescAso":null,"EstJM":1}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029172015275199","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:10:13","CodHabIPS":"086380050301","TipoIDIPS":"NI","NroIDIPS":"900008600","CodDANEMunIPS":"08638","DirSedeIPS":"CARRERA 19 No 24-118","TelSedeIPS":"8782000 - 8783092","TipoIDProf":"CC","NumIDProf":"79409992","PNProfS":"FARID","SNProfS":"","PAProfS":"FERNANDEZ","SAProfS":"PONTON","RegProfS":"RM 34899-0","TipoIDPaciente":"CC","NroIDPaciente":"22637598","PNPaciente":"LILIA","SNPaciente":"ESTER","PAPaciente":"AHUMADA","SAPaciente":"CABARCAS","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"H360","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[RANIBIZUMAB] 10mg/1ml","CodFF":"COLFF004","CodVA":"031","JustNoPBS":"paciente con diagnostico de retinopatia diabetica quien requiere tratamiento ","Dosis":"1","DosisUM":"9000","NoFAdmon":"1","CodFreAdmon":5,"IndEsp":10,"CanTrat":"1","DurTrat":5,"CantTotalF":"1","UFCantTotal":"01","IndRec":"bajo indicaciones del medico","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"08313","ConcCant":"10","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029110015275211","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:10:24","CodHabIPS":"080010445438","TipoIDIPS":"NI","NroIDIPS":"901139193","CodDANEMunIPS":"08001","DirSedeIPS":"CALLE 50 No.20-91","TelSedeIPS":"3781483","TipoIDProf":"CC","NumIDProf":"1064979833","PNProfS":"OLGA","SNProfS":"YAMILE","PAProfS":"BERNAL","SAProfS":"ABIANTUN","RegProfS":"03113","TipoIDPaciente":"CC","NroIDPaciente":"7453866","PNPaciente":"HERIBERTO","SNPaciente":"ANTONIO","PAPaciente":"ROHLOFF","SAPaciente":"PEREZ","CodAmbAte":22,"RefAmbAte":0,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"F509","CodDxRel1":"I219","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":2,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":null,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1503","DescProdNutr":"150301","CodForma":"3","CodViaAdmon":2,"JustNoPBS":"paciente bajo vetilacion mecanica EN REGULAR ESTADO VALORADO POR METODO GLOBAL SUBJETIVO ENCONTRABDOLO AJO VENTILACION MECANICA CON SONDA OORGASTRICA PARA RECIBIR ALIMENETACION POR LO QUE REQUIERE SOPORTE NUTRICIONAL PARA ASEGURAR UN ADEUAO APORTE CALORICO Y EVITAR UN MAYOR GRADO DE DPELCION \n\nBUN: 15.3\n\nCRETATININA: 1.1 \n\nHB: 11.2","Dosis":"5","DosisUM":"0247","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":3,"CanTrat":"8","DurTrat":3,"CantTotalF":"40","UFCantTotal":"3","IndRec":"PASAR A RAZON DE 79 CC POR HORA","NoPrescAso":"","EstJM":1}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029164015275342","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:12:41","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"56078839","PNProfS":"TATIANA","SNProfS":"ELENA","PAProfS":"MENDOZA","SAProfS":"SALINA","RegProfS":"7093","TipoIDPaciente":"CC","NroIDPaciente":"49609048","PNPaciente":"MONICA","SNPaciente":"PATRICIA","PAPaciente":"VERGARA","SAPaciente":"ESPAÑA","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J450","CodDxRel1":"J303","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"LORATADINA","RznCausaS31":1,"DescRzn31":"SIN MEJORIA ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[MONTELUKAST] 10mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"\nPACIENETE CON CUADRO CLINICO DE LARGO PERIODO DE EVOLUCION CARACTERIZADO POR ASMAS ALERIGOC POSN RINITIS ALERGICA OBSTRUCCION NASAL, RINORREA HIALINA CON MUCOSA CONGESTIVA, SECRESION , ESTORNUDOS RECURRENTE CON HIPERTROFIA DE CORNETES EN TRATAMIENTO SIN MEJORIA POR LO QUE SOLICITO MONTELUKAST SINGULAIR CORTICOIDE LOCAL PARA DESINFLAMAR MUCOSA NASAL Y ERRADICAR PROCESO INFLAMATORIO Y ALERGICO\n","Dosis":"10","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"66","IndRec":"ENTREGAR SINGULAIR TAB 10 MG ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07388","ConcCant":"10","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029164015275464","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:14:48","CodHabIPS":"080010226001","TipoIDIPS":"NI","NroIDIPS":"802021171","CodDANEMunIPS":"08001","DirSedeIPS":"Carrera 45 84-153","TelSedeIPS":"3781288","TipoIDProf":"CC","NumIDProf":"72276644","PNProfS":"FREDDY","SNProfS":"RAFAEL","PAProfS":"NEIRA","SAProfS":"DONADO","RegProfS":"72276644","TipoIDPaciente":"TI","NroIDPaciente":"1043456724","PNPaciente":"RAFAEL","SNPaciente":"ANDRES","PAPaciente":"BOHORQUEZ","SAPaciente":"NAVARRO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R32X","CodDxRel1":"R15X","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[{"ConOrden":1,"TipoPrest":2,"CausaS1":0,"CausaS2":1,"CausaS3":null,"CausaS4":1,"DescCausaS4":"paciente mielomeningocele con incontinencia urinaria y fecal","CausaS5":null,"CodSerComp":"139","DescSerComp":"etapa 5 winny","CanForm":"5","CadaFreUso":"24","CodFreUso":2,"Cant":"90","CantTotal":"450","CodPerDurTrat":3,"TipoTrans":null,"ReqAcom":null,"TipoIDAcomAlb":null,"NroIDAcomAlb":null,"ParentAcomAlb":null,"NombAlb":null,"CodMunOriAlb":null,"CodMunDesAlb":null,"JustNoPBS":"PACIENTE MIELOMENINGOCELE CON INCONTINENCIA URINARIA Y FECAL","IndRec":"usar 5 pañales por dia","EstJM":2}]},{"prescripcion":{"NoPrescripcion":"20191029175015275511","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:15:36","CodHabIPS":"700010177001","TipoIDIPS":"NI","NroIDIPS":"901082722","CodDANEMunIPS":"70001","DirSedeIPS":"CARRERA 19 A # 14 A - 72","TelSedeIPS":"3205690149 2714020","TipoIDProf":"CC","NumIDProf":"72188057","PNProfS":"JOSE","SNProfS":"LUIS","PAProfS":"BENITEZ","SAProfS":"VERGARA","RegProfS":"24542/70","TipoIDPaciente":"CC","NroIDPaciente":"33167934","PNPaciente":"NORELLY","SNPaciente":"","PAPaciente":"GONZALEZ","SAPaciente":"VDA DE VEGA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E107","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"METFORMINA","RznCausaS31":1,"DescRzn31":"no control adecuado de glucosa en sangre","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[METFORMINA CLORHIDRATO] 850mg/1U ; [VILDAGLIPTINA] 50mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"control optimo de glucosa","Dosis":"50","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":1,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"14","IndRec":"tomar una cada dia","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00965","ConcCant":"850","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"08399","ConcCant":"50","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029143015275759","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:19:54","CodHabIPS":"270010104901","TipoIDIPS":"NI","NroIDIPS":"900815727","CodDANEMunIPS":"27001","DirSedeIPS":"CRA 6 No. 30-63 piso 1","TelSedeIPS":"3226530585-3113045431","TipoIDProf":"CC","NumIDProf":"52889767","PNProfS":"GIPTZY","SNProfS":"YANITZA","PAProfS":"CASAS","SAProfS":"CORDOBA","RegProfS":"271100","TipoIDPaciente":"CC","NroIDPaciente":"26269131","PNPaciente":"CARLINA","SNPaciente":"","PAPaciente":"MENA","SAPaciente":"DE VALENCIA","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E119","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":"METFORMINA|GLIBENCLAMIDA","RznCausaS41":1,"DescRzn41":"NO CONTROL DE CIFRAS DE GLICEMIA ","RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[METFORMINA CLORHIDRATO] 1000mg/1U ; [VILDAGLIPTINA] 50mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"PCTE CON DM TIPO 2 INSULINOREQUIRIENTE DE DIFICIL CONTROL SE INICIO TTO CON GALVUSMET CON BUENOS RESULTADOS POR LO QUE DECIDO CONTINUAR IGUAL TTO ","Dosis":"1","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"180","UFCantTotal":"66","IndRec":"1 TAB CADA 12 HORAS VIA ORAL ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00965","ConcCant":"1000","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"08399","ConcCant":"50","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029116015275952","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:23:27","CodHabIPS":"086380074501","TipoIDIPS":"NI","NroIDIPS":"900080150","CodDANEMunIPS":"08638","DirSedeIPS":"CARRERA 19 A NÚMERO 24-46","TelSedeIPS":"8783805","TipoIDProf":"CC","NumIDProf":"32847775","PNProfS":"LUZ","SNProfS":"MARINA","PAProfS":"CONRADO","SAProfS":"ESTRADA","RegProfS":"080687","TipoIDPaciente":"CC","NroIDPaciente":"7407772","PNPaciente":"EDGARDO","SNPaciente":"ENRIQUE","PAPaciente":"MOLINA","SAPaciente":"TEJERA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E440","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":2,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":null,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1102","DescProdNutr":"110202","CodForma":"3","CodViaAdmon":1,"JustNoPBS":"PACIENTE MASCULINO DE 78 AÑOS, PESO ACTUAL: 59 KG, TALLA, 176 IMC: 18.6 D X NUTRICIONAL D N T MODERADA,D X MEDICO: A C V HEMORRAGICO: , H TA NO CONTROLADA,SE SOLICITA SUPLEMENTO NUTRICIONAL ENSURE ADVANCE BOTELLA LIQUIDA DAR CADA 12 HORAS POR 90 DÍAS, TOTAL 180 BOTELLAS,","Dosis":"1","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"180","UFCantTotal":"3","IndRec":"SE RECOMIENDA SUPLEMENTO NUTRICIONAL ENSURE ADVANCE BOTELLA LIQUIDA DAR CADA 12 HORAS POR 90 DÍAS, TOTAL 180 BOTELLAS","NoPrescAso":null,"EstJM":3}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029110015276305","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:30:15","CodHabIPS":"200010018301","TipoIDIPS":"NI","NroIDIPS":"824002277","CodDANEMunIPS":"20001","DirSedeIPS":"carrera 15 # 14-36","TelSedeIPS":"5806494","TipoIDProf":"CC","NumIDProf":"80166387","PNProfS":"CESAR","SNProfS":"GONZALO","PAProfS":"MODERA","SAProfS":"HERNANDEZ","RegProfS":"506018","TipoIDPaciente":"CC","NroIDPaciente":"85165970","PNPaciente":"YOMAIRO","SNPaciente":"","PAPaciente":"HERRERA","SAPaciente":"ALFARO","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R529","CodDxRel1":"Z981","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"NAPROXENO","RznCausaS31":1,"DescRzn31":"paciente con persistencia del cuadro clinico","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[PREGABALINA] 75mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"PACIENTE DE 42 AÑOS DE EDAD CON ANTECEDENTES DE ARTRODESIS INTERFALANGICA DEL PULGAR EN MANO DERECHA, REFIRIENDO UN MES DE EVOLUCION DE DOLOR EN PRIMER DEDO LUEGO DE TRAUMA, SE INDICA TRATAMIENTO CON PREGABALINA","Dosis":"75","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"30","DurTrat":3,"CantTotalF":"30","UFCantTotal":"66","IndRec":"ADMINISTRAR UNA TABLETA VIA ORAL CADA NOCHE POR 30 DIAS","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07643","ConcCant":"75","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029193015276414","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:32:20","CodHabIPS":"760010306601","TipoIDIPS":"NI","NroIDIPS":"890300513","CodDANEMunIPS":"76001","DirSedeIPS":"CALLE 18 NORTE # 5N-34","TelSedeIPS":"6603000","TipoIDProf":"CC","NumIDProf":"79791647","PNProfS":"MILTON","SNProfS":"ALBERTO","PAProfS":"LOMBANA","SAProfS":"QUIÑONEZ","RegProfS":"523709","TipoIDPaciente":"CC","NroIDPaciente":"6481705","PNPaciente":"JOSE","SNPaciente":"CLARET","PAPaciente":"AMAYA","SAPaciente":"AGUDELO","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"C859","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[BENDAMUSTINA CLORHIDRATO] 100mg/1U","CodFF":"COLFF003","CodVA":"042","JustNoPBS":"Linfoma del Manto, variedad NE, Estadio IV, MIPI NR, Riesgo Alto. Se ordena continuar quimioterapia con Bendamustina+rituximab con el objetivo de mejorar supervivencia libre de progresion ","Dosis":"131,4","DosisUM":"0168","NoFAdmon":"28","CodFreAdmon":3,"IndEsp":10,"CanTrat":"28","DurTrat":3,"CantTotalF":"4","UFCantTotal":"74","IndRec":"APLICAR 131,4 MG DIAS 1 Y 2 CADA 28 DIAS","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"05228","ConcCant":"100","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029159015276480","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:33:29","CodHabIPS":"080010112201","TipoIDIPS":"NI","NroIDIPS":"890108597","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 42F No.75B-18","TelSedeIPS":"3565109","TipoIDProf":"CC","NumIDProf":"32860670","PNProfS":"MARGARITA","SNProfS":"MARIA","PAProfS":"JIMENEZ","SAProfS":"UTRIA","RegProfS":"01650","TipoIDPaciente":"CC","NroIDPaciente":"72246389","PNPaciente":"JOSE","SNPaciente":"OMAR","PAPaciente":"GONZALEZ","SAPaciente":"CORONADO","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E43X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":2,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":1,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1102","DescProdNutr":"110202","CodForma":"3","CodViaAdmon":1,"JustNoPBS":"PACIENTE MASCULINO CON DX VIRUS INMUNODEFICIENCIAS ADQUIRIDA DESNUTRICIÓN PROTEICA severa, no especificada,PERDIDA DE MASA,TEJIDO Y FIBRA ,HIPOREXIA MODERADA QUE DESDE SU ALIMENTICION CONVENCIONAL NO CUBRE LAS NECESIDADES DE MACRO Y MICRO NUTRIENTES REQUIERE SOPORTE ALIMETO LIQUIDO ,COMPLETO Y BALANCEADO CON HMB,PROTEINA,VITAMINA D AJUSTADOA A SUS NECESIDADES DE SU PATOLOGIA ","Dosis":"3","DosisUM":"0247","NoFAdmon":"8","CodFreAdmon":2,"IndEsp":1,"CanTrat":"3","DurTrat":5,"CantTotalF":"270","UFCantTotal":"3","IndRec":"CONSUMIR 3 DOSIS VÍAS ORAL FRACCIONADAS UNA VEZ ABIERTA CONSUMIR ","NoPrescAso":null,"EstJM":1}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029144015276503","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:33:55","CodHabIPS":"700010016301","TipoIDIPS":"NI","NroIDIPS":"823002800","CodDANEMunIPS":"70001","DirSedeIPS":"CARRERA 25B No. 25 -152","TelSedeIPS":"3126692716","TipoIDProf":"CC","NumIDProf":"92520392","PNProfS":"RICARDO","SNProfS":"LUIS","PAProfS":"RODRIGUEZ","SAProfS":"HERNANDEZ","RegProfS":"282","TipoIDPaciente":"CC","NroIDPaciente":"23221492","PNPaciente":"ERIKA","SNPaciente":"ROSA","PAPaciente":"ALVIS","SAPaciente":"AGUIRRE","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M350","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[CONDROITINA SULFATO SODICA] 1,8mg/1ml ; [HIALURONATO DE SODIO] 1mg/1ml","CodFF":"COLFF004","CodVA":"068","JustNoPBS":"POP Liberacion de simblefaron + reseccion de pterigion + queratectomia + plastia de conjuntiva ojo izquierdo 17/09/19Refiere sentirse bien\nAP: reduccion abierta de fractura de femur derecho, resto okExamen oftalmologico:Enoftalmos marcado a expensas de atrofia grasa\nPtosis leve parpados superiores ambos ojosPterigion grado II ojo derechoCicatriz conjuntiva nasal y temporal ojo izquierdoSegmento anterior normalPIO: 12/12Fondo de ojo:Exc: 0.2/0.2Retina aplicadaDx:1. Pterigion ojo d","Dosis":"1","DosisUM":"0046","NoFAdmon":"4","CodFreAdmon":2,"IndEsp":10,"CanTrat":"1","DurTrat":5,"CantTotalF":"1","UFCantTotal":"13","IndRec":"PACIENTE REQUIERE MEDICAMENTO POR 15 ML ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50057","ConcCant":"1,8","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"},{"ConOrden":1,"CodPriAct":"50096","ConcCant":"1","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029141015276794","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:39:08","CodHabIPS":"700010016301","TipoIDIPS":"NI","NroIDIPS":"823002800","CodDANEMunIPS":"70001","DirSedeIPS":"CARRERA 25B No. 25 -152","TelSedeIPS":"3126692716","TipoIDProf":"CC","NumIDProf":"92520392","PNProfS":"RICARDO","SNProfS":"LUIS","PAProfS":"RODRIGUEZ","SAProfS":"HERNANDEZ","RegProfS":"282","TipoIDPaciente":"CC","NroIDPaciente":"22693281","PNPaciente":"YESENIA","SNPaciente":"ROSA","PAPaciente":"CASTRO","SAPaciente":"FONTALVO","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M350","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[CONDROITINA SULFATO SODICA] 1,8mg/1ml ; [HIALURONATO DE SODIO] 1mg/1ml","CodFF":"COLFF004","CodVA":"068","JustNoPBS":"Refiere ardor, prurito, lagrimeo y sensacion de cuerpo extraño en ambos ojos desde hace varios meses.AP: HTA tratada con losartan, clonidina, carvedilol y amlodipino, DM Tipo II tratada con metformina, glibenclamida, herniorrafia umbilical, resto okExamen oftalmologico:\nXTAParpados en adecuada posicionSegmento anterior normalPIO: 12/12Fondo de ojo:Exc: 0.4/0.4Retina aplicadaDx:1. Exotropia alternante\n2. Sindrome de ojo seco3. Ametropia","Dosis":"1","DosisUM":"0046","NoFAdmon":"4","CodFreAdmon":2,"IndEsp":10,"CanTrat":"1","DurTrat":5,"CantTotalF":"1","UFCantTotal":"13","IndRec":"PACIENTE REQUIERE MEDICAMENTO POR 15 ML ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50057","ConcCant":"1,8","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"},{"ConOrden":1,"CodPriAct":"50096","ConcCant":"1","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029112015276980","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:43:06","CodHabIPS":"700010096901","TipoIDIPS":"NI","NroIDIPS":"900118990","CodDANEMunIPS":"70001","DirSedeIPS":"CARRERA 20 No. 13-31","TelSedeIPS":"2761605","TipoIDProf":"CC","NumIDProf":"15041623","PNProfS":"GERMAN","SNProfS":"","PAProfS":"OTERO","SAProfS":"MARRUGO","RegProfS":"294","TipoIDPaciente":"CC","NroIDPaciente":"34980173","PNPaciente":"PETRONA","SNPaciente":"DE LAS MERCEDES","PAPaciente":"MARTINEZ","SAPaciente":"URANGO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J450","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"BECLOMETASONA|SALBUTAMOL|BROMURO DE IPRATROPIO","RznCausaS31":1,"DescRzn31":"pcte no responde a ttos pos anteriormente ordenados","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[BUDESONIDA] 200µg/1Dosis ; [FORMOTEROL FUMARATO] 6µg/1U","CodFF":"COLFF006","CodVA":"002","JustNoPBS":"pcte femenina de 56 años de edad quien viene con dx: asma alérgica, actualmente manifiesta que ha venido con tos y expectoración, sin disnea, no fiebre, niega edema, rinorrea con obstrucción nasal, no hta, no dm, al examen fisico: no crepitos, no sibilantes, corazón ritmico, orl: hipertrofia de cornetes. continúa igual tto con aerovial y montelukast.","Dosis":"200","DosisUM":"0137","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"120","DurTrat":3,"CantTotalF":"240","UFCantTotal":"14","IndRec":"inhalar 1 caps cada 12 hrs durante 4 meses","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"04145","ConcCant":"200","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"},{"ConOrden":1,"CodPriAct":"04935","ConcCant":"6","UMedConc":"0137","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"BECLOMETASONA|SALBUTAMOL|BROMURO DE IPRATROPIO","RznCausaS31":1,"DescRzn31":"pcte no responde a ttos pos anteriormente ordenados","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[MONTELUKAST] 10mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"PCTE FEMENINA DE 56 AÑOS DE EDAD QUIEN VIENE CON DX: ASMA ALÉRGICA, ACTUALMENTE MANIFIESTA QUE HA VENIDO CON TOS Y EXPECTORACIÓN, SIN DISNEA, NO FIEBRE, NIEGA EDEMA, RINORREA CON OBSTRUCCIÓN NASAL, NO HTA, NO DM, AL EXAMEN FISICO: NO CREPITOS, NO SIBILANTES, CORAZÓN RITMICO, ORL: HIPERTROFIA DE CORNETES. CONTINÚA IGUAL TTO CON AEROVIAL Y MONTELUKAST.","Dosis":"10","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":3,"IndEsp":10,"CanTrat":"120","DurTrat":3,"CantTotalF":"120","UFCantTotal":"66","IndRec":"tomar 1 tb al dia durante 4 meses","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"07388","ConcCant":"10","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029110015277224","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:48:17","CodHabIPS":"700010111401","TipoIDIPS":"NI","NroIDIPS":"900217343","CodDANEMunIPS":"70001","DirSedeIPS":"CALLE 14 NUMERO 20 - 53","TelSedeIPS":"2714280","TipoIDProf":"CC","NumIDProf":"55231988","PNProfS":"MADELEINE","SNProfS":"","PAProfS":"NAVARRO","SAProfS":"REYES","RegProfS":"161322009","TipoIDPaciente":"CC","NroIDPaciente":"9310962","PNPaciente":"WILLIAM","SNPaciente":"RAFAEL","PAPaciente":"PEREZ","SAPaciente":"HERAZO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M350","CodDxRel1":"H401","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[CARBOXIMETILCELULOSA SODICA] 5mg/1ml","CodFF":"COLFF004","CodVA":"047","JustNoPBS":"PACIENTE REFIERE MEJORIA DE LAS MOLESTIAS OCULARES . REFIERE DOLOR OCASIONAL EN AMBOS OJOS . BIO ODI : CORNEA CLARA CA ESTRECHA , CRISTALINO CLARO , IRIDOTOMIAS PERMEABLES . PIO: 14/14 MMHG FO : RETINA APLIACDA EXC 0.25. DX CIERRE ANGULAR PRIMARIO ( SOSPECHA DE GLAUCOMA ) PLAN : LUBRICANTE OCULAR ( CARMELUB 1 GOTA CADA 8 HE N AMBOS OJOS ) TRATAMIENTO PARA EVITAR LA SENSACION DE ARDOR Y RESEQUEDAD QUE CAUSAN LOS MEDICAMENTOS ANTIGLAUCOMATOSO","Dosis":"2","DosisUM":"0046","NoFAdmon":"8","CodFreAdmon":2,"IndEsp":10,"CanTrat":"6","DurTrat":5,"CantTotalF":"6","UFCantTotal":"13","IndRec":"aplicar en ambos ojos cada 8 horas, usar oportunamente, se le fromula carmelub ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"04950","ConcCant":"5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029165015277506","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:53:58","CodHabIPS":"470010094101","TipoIDIPS":"NI","NroIDIPS":"900078907","CodDANEMunIPS":"47001","DirSedeIPS":"CALLE 26 No 16-74","TelSedeIPS":"4203473","TipoIDProf":"CC","NumIDProf":"32668730","PNProfS":"MERCEDES","SNProfS":"CECILIA","PAProfS":"FONSECA","SAProfS":"PALOMINO","RegProfS":"","TipoIDPaciente":"RC","NroIDPaciente":"1080672725","PNPaciente":"MARIA","SNPaciente":"LUISA","PAPaciente":"PAREJO","SAPaciente":"ALTAMAR","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E45X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":2,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":null,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1701","DescProdNutr":"170147","CodForma":"3","CodViaAdmon":1,"JustNoPBS":"PACiente DE 3 AÑOS DE EDAD CON RETARDO del CRECIMIENTO y desarrollo debido a DESNUTRICIÓN PROTEICO CALO-RICA, POR INADECUADA INGESTA DE CALORÍAS Y NUTRIENTES Y REQUIERE FORMULA ALIMENTICIA COMPLETA Y BALANCEADA CON PROTEÍNAS , VITAMINAS Y MINERALES Y FIBRAS COn EXCELENTE TOLERANCIA Y ADHERENCIA AL TRATAMIENTO EVITANDO La APARICIÓN DE COMPLICACIONES SECUNDARIA A LA DESNUTRICIÓN..","Dosis":"237","DosisUM":"0176","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"120","DurTrat":3,"CantTotalF":"120","UFCantTotal":"3","IndRec":"SIN INDICACIÓN ESPECIAL ","NoPrescAso":null,"EstJM":3}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029152015277725","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"11:58:27","CodHabIPS":"080010121201","TipoIDIPS":"NI","NroIDIPS":"860013779","CodDANEMunIPS":"08001","DirSedeIPS":"Calle 59 No 50-17","TelSedeIPS":"(5) 3197928 - 3682886","TipoIDProf":"CC","NumIDProf":"8692636","PNProfS":"JOSE","SNProfS":"DOMINGO","PAProfS":"MONSALVE","SAProfS":"TAPIAS","RegProfS":"1716","TipoIDPaciente":"CC","NroIDPaciente":"4029938","PNPaciente":"ALFONSO","SNPaciente":"","PAPaciente":"BARRETO","SAPaciente":"MELGAREJO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R32X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[{"ConOrden":1,"TipoPrest":2,"CausaS1":0,"CausaS2":1,"CausaS3":null,"CausaS4":1,"DescCausaS4":"paciente añoso con incontinencia de orina usa pañales talla m para calidad de vida se envian por 6 meses","CausaS5":null,"CodSerComp":"139","DescSerComp":"PACIENTE AÑOSO CON INCONTINENCIA DE ORINA USA PAÑALES TALLA M PARA CALIDAD DE VIDA SE ENVIAN POR 6 MESES","CanForm":"1","CadaFreUso":"8","CodFreUso":2,"Cant":"6","CantTotal":"540","CodPerDurTrat":5,"TipoTrans":null,"ReqAcom":null,"TipoIDAcomAlb":null,"NroIDAcomAlb":null,"ParentAcomAlb":null,"NombAlb":null,"CodMunOriAlb":null,"CodMunDesAlb":null,"JustNoPBS":"PACIENTE AÑOSO CON INCONTINENCIA DE ORINA USA PAÑALES TALLA M PARA CALIDAD DE VIDA SE ENVIAN POR 6 MESES","IndRec":"PACIENTE AÑOSO CON INCONTINENCIA DE ORINA USA PAÑALES TALLA M PARA CALIDAD DE VIDA SE ENVIAN POR 6 MESES","EstJM":1}]},{"prescripcion":{"NoPrescripcion":"20191029117015277956","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"12:03:26","CodHabIPS":"700010151301","TipoIDIPS":"NI","NroIDIPS":"830510991","CodDANEMunIPS":"70001","DirSedeIPS":"CALLE 38 N° 52-249","TelSedeIPS":"2806901","TipoIDProf":"CC","NumIDProf":"64585650","PNProfS":"ZAIDA","SNProfS":"PATRICIA","PAProfS":"BERTEL","SAProfS":"HERNANDEZ","RegProfS":"704372005","TipoIDPaciente":"CC","NroIDPaciente":"23196983","PNPaciente":"JOSEFINA","SNPaciente":"DEL CARMEN","PAPaciente":"VILARO","SAPaciente":"MONROY","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"H813","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"NIMODIPINO","RznCausaS31":1,"DescRzn31":"SIN MEJROIA DE CUADRO VERIGINOSO , PERSITENCIA DE LATERCIONES DE POLIGONO DE SUSTENTACION ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[BETAHISTINA DICLORHIDRATO] 24mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"MEJORAR ESTADO DE SENSACIÓN VERTIGINOSA PERIFÉRICA CON COMPROMISO DE ALTERACIONES VASOVAGALES , CEFALEA Y ANSIEDAD ASOCIADA ","Dosis":"24","DosisUM":"0168","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"30","DurTrat":3,"CantTotalF":"60","UFCantTotal":"14","IndRec":"1 CADA 12 HORAS PRO 1 MES ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"01502","ConcCant":"24","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029160015278064","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"12:05:53","CodHabIPS":"702150038001","TipoIDIPS":"NI","NroIDIPS":"890480113","CodDANEMunIPS":"70215","DirSedeIPS":"CALLE 33 # 31 - 65","TelSedeIPS":"2840011","TipoIDProf":"CC","NumIDProf":"92500391","PNProfS":"GABRIEL","SNProfS":"OLIMPO","PAProfS":"ESPINOSA","SAProfS":"OLIVER","RegProfS":"3920","TipoIDPaciente":"CC","NroIDPaciente":"92551116","PNPaciente":"CARLOS","SNPaciente":"MANUEL","PAPaciente":"PEÑA","SAPaciente":"MERCADO","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"I110","CodDxRel1":"I159","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[SACUBITRILO] 48,6mg/1U ; [VALSARTAN] 51,4mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"El Sacubitril bloquea la degradación de los péptidos natriuréticos que se producen en el organismo, manteniendo su efecto. Estos péptidos tienen un efecto beneficioso en los pacientes con insuficiencia cardiaca al hacer que el sodio y el agua pasen a la orina, reduciendo así la carga de líquido para el corazón. Los péptidos natriuréticos también reducen la tensión arterial y protegen al corazón del desarrollo de fibrosis que se genera en los pacientes con insuficiencia cardiaca\n\n ","Dosis":"100","DosisUM":"0168","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"180","UFCantTotal":"66","IndRec":"TOMAR UNA TABLETA CADA 12 HORAS ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07016","ConcCant":"51,4","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"09819","ConcCant":"48,6","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029144015278337","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"12:11:59","CodHabIPS":"761470851601","TipoIDIPS":"NI","NroIDIPS":"900472731","CodDANEMunIPS":"76147","DirSedeIPS":"CARRERA 1 #16-107","TelSedeIPS":"2146686","TipoIDProf":"CC","NumIDProf":"10110853","PNProfS":"JUAN","SNProfS":"CARLOS","PAProfS":"MEJIA","SAProfS":"RUBIANO","RegProfS":"1217","TipoIDPaciente":"CC","NroIDPaciente":"1112618240","PNPaciente":"MARIA","SNPaciente":"EUGENIA","PAPaciente":"HOLGUIN","SAPaciente":"LOPEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M059","CodDxRel1":"M804","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"NAPROXENO","RznCausaS31":1,"DescRzn31":"fallo","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":0,"CausaS6":null,"DescMedPrinAct":"[ADALIMUMAB] 50mg/1ml","CodFF":"COLFF004","CodVA":"058","JustNoPBS":"artritis reumatoide refractaria a fame","Dosis":"40","DosisUM":"0168","NoFAdmon":"15","CodFreAdmon":3,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"6","UFCantTotal":"01","IndRec":"aplicar una Ampolla subcutánea cada 15 días ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07860","ConcCant":"50","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029111015278397","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"12:13:29","CodHabIPS":"134300028101","TipoIDIPS":"NI","NroIDIPS":"890480363","CodDANEMunIPS":"13430","DirSedeIPS":"calle 14a numero 3-28","TelSedeIPS":"6876043","TipoIDProf":"CC","NumIDProf":"9194947","PNProfS":"LORENZO","SNProfS":"","PAProfS":"URBINA","SAProfS":"VANEGAS","RegProfS":"9194947","TipoIDPaciente":"CC","NroIDPaciente":"23205067","PNPaciente":"GRACIELA","SNPaciente":"","PAPaciente":"MEDINA","SAPaciente":"DE MEZA","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M150","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"PARACETAMOL","RznCausaS31":1,"DescRzn31":"no mejoria","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[PREGABALINA] 75mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"modulacion del dolor ","Dosis":"1","DosisUM":"9000","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"180","DurTrat":3,"CantTotalF":"180","UFCantTotal":"66","IndRec":"mejorar calidad de vida por dolor ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07643","ConcCant":"75","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"PARACETAMOL","RznCausaS31":1,"DescRzn31":"no mejoria","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[DIACEREINA] 50mg/1U","CodFF":"COLFF006","CodVA":"048","JustNoPBS":"antiinflamatorio control del dolor ","Dosis":"1","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"180","DurTrat":3,"CantTotalF":"360","UFCantTotal":"14","IndRec":"mejorar calidad de vida ","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"05371","ConcCant":"50","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029159015278548","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"12:16:50","CodHabIPS":"761470851601","TipoIDIPS":"NI","NroIDIPS":"900472731","CodDANEMunIPS":"76147","DirSedeIPS":"CARRERA 1 #16-107","TelSedeIPS":"2146686","TipoIDProf":"CC","NumIDProf":"10110853","PNProfS":"JUAN","SNProfS":"CARLOS","PAProfS":"MEJIA","SAProfS":"RUBIANO","RegProfS":"1217","TipoIDPaciente":"CC","NroIDPaciente":"1112618240","PNPaciente":"MARIA","SNPaciente":"EUGENIA","PAPaciente":"HOLGUIN","SAPaciente":"LOPEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M059","CodDxRel1":"M804","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"PREDNISOLONA","RznCausaS31":1,"DescRzn31":"fallo","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[METILPREDNISOLONA] 4mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"artritis reumatoide con actividad grave de la enfermedad ","Dosis":"4","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"TOMAR 1 TABLETA DIARIA ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00732","ConcCant":"4","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029166015278701","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"12:20:50","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"32769391","PNProfS":"SIGRID","SNProfS":"","PAProfS":"BLANCO","SAProfS":"BERMUDEZ","RegProfS":"1919","TipoIDPaciente":"TI","NroIDPaciente":"1003252146","PNPaciente":"DAIRITH","SNPaciente":"PAOLA","PAPaciente":"BARRIOS","SAPaciente":"VASQUEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E078","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[{"ConOrden":1,"TipoPrest":2,"CausaS11":1,"CausaS12":null,"CausaS2":null,"CausaS3":0,"CausaS4":0,"ProPBSUtilizado":null,"CausaS5":1,"ProPBSDescartado":null,"RznCausaS51":1,"DescRzn51":"N.A.","RznCausaS52":0,"DescRzn52":null,"CausaS6":null,"CausaS7":null,"CodCUPS":"906492","CanForm":"1","CadaFreUso":"6","CodFreUso":5,"Cant":"6","CantTotal":"1","CodPerDurTrat":5,"JustNoPBS":"pACIENTE CON BOCIO DE LARGA DATA , E HIPERTIROIDISMO CLiNICO, con antecedentes de episodio de tormenta tiroidea, por lo cual recibio tratamiento intrahospitalario, con ANTICUERPOS ANTITIROGLOBULINA Y ANTIPEROXIDASA POSITIVOS, solicito anticuerpos antireceptor de la tsh para diagnostico diferencial de enfermedad de graves vS HIPERTIROIDISMO POR TIROIDITIS AUTOINMUNE EN FASE HIPERTIROIDEA.","IndRec":"realizar dosaje de anticuerpos antireceptor de tsh, TRAB.","EstJM":1}],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029127015278718","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"12:21:12","CodHabIPS":"134300049201","TipoIDIPS":"NI","NroIDIPS":"900196347","CodDANEMunIPS":"13430","DirSedeIPS":"Avenida Colombia Nº13-146","TelSedeIPS":"3017573836-3017446503","TipoIDProf":"CC","NumIDProf":"8647273","PNProfS":"JORGE","SNProfS":"LUIS","PAProfS":"PALMERA","SAProfS":"HERNANDEZ","RegProfS":"128081","TipoIDPaciente":"CC","NroIDPaciente":"33202732","PNPaciente":"ROQUELINA","SNPaciente":"","PAPaciente":"LARIOS","SAPaciente":"GOMEZ","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E119","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"GLIBENCLAMIDA","RznCausaS31":1,"DescRzn31":"no mejoria clinica ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[LINAGLIPTINA] 2,5mg/1U ; [METFORMINA CLORHIDRATO] 1000mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"MC : CONTROL DE DMTS\n\nEEA: PACIENTE CON ANTECEDENTES DE DMTS , QUIEN REFIERE CUADRO CLINICO CARACTERIZADO POR DOLOR EN EL PECHO TIPO PUNZANDA TRAE REPORTE DE PARACLINICOS : TRIGLICERIDOS : 376 COLESTEROL TOTAL : 311 GLUCOSA : 306 CREATININA : 0.8 BUN : 11.6 HEMOGRAMA : HG : 13.5 HTO : 40.5\n\nAP :DISPEPSIA CRONICA. -DM: TRAYENTA DUO TAB X2 - ATORVASTATINA TAB 40X1\n-NODULO RENAL IZQ DESCARTADO-\n\n-NIEGA HTA ASMA ERC -\n\n-ALERGICOS: NEG -\n\n\nEXAMEN FISICO : FC: 82 FR: 18 TA: 120/80\n\nPACIENTE EN APARENT","Dosis":"1","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"180","UFCantTotal":"66","IndRec":"tomar 1 tableta cada 12 horas por 90 dias ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00965","ConcCant":"1000","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"09039","ConcCant":"2,5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029140015278911","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"12:26:18","CodHabIPS":"087580001301","TipoIDIPS":"NI","NroIDIPS":"890112801","CodDANEMunIPS":"08758","DirSedeIPS":"CALLE 30 AUTOP AL AEROPUERTO AL LADO DEL PARQUE MUVDI","TelSedeIPS":"3715562","TipoIDProf":"CC","NumIDProf":"72310350","PNProfS":"JORGE","SNProfS":"LUIS","PAProfS":"QUINTERO","SAProfS":"BARRIOS","RegProfS":"4740/2003","TipoIDPaciente":"CC","NroIDPaciente":"3769272","PNPaciente":"CARLOS","SNPaciente":"ARTURO","PAPaciente":"BARRIOS","SAPaciente":"GUERRERO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J449","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"SALBUTAMOL","RznCausaS31":1,"DescRzn31":"inadecuado control de sintomas","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[GLICOPIRRONIO] 50µg/1U ; [INDACATEROL] 110µg/1U","CodFF":"COLFF006","CodVA":"055","JustNoPBS":"paciente con epon muy severo requiere manejo con laba lama baso en guias gold","Dosis":"160","DosisUM":"0137","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"180","DurTrat":3,"CantTotalF":"180","UFCantTotal":"14","IndRec":"1 capsula inhalada al dia","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"01038","ConcCant":"50","UMedConc":"0137","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"08457","ConcCant":"110","UMedConc":"0137","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029171015279008","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"12:28:57","CodHabIPS":"087580001301","TipoIDIPS":"NI","NroIDIPS":"890112801","CodDANEMunIPS":"08758","DirSedeIPS":"CALLE 30 AUTOP AL AEROPUERTO AL LADO DEL PARQUE MUVDI","TelSedeIPS":"3715562","TipoIDProf":"CC","NumIDProf":"8721390","PNProfS":"CARLOS","SNProfS":"VINICIO","PAProfS":"CABALLERO","SAProfS":"URIBE","RegProfS":"3149","TipoIDPaciente":"CC","NroIDPaciente":"22646321","PNPaciente":"MARILIS","SNPaciente":"","PAPaciente":"VILLAFAÑE","SAPaciente":"PATIÑO","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M329","CodDxRel1":"I272","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":0,"CausaS6":null,"DescMedPrinAct":"[SILDENAFILO] 50mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"paciente con hpertension pulmonar y lupus ","Dosis":"50","DosisUM":"0168","NoFAdmon":"8","CodFreAdmon":2,"IndEsp":10,"CanTrat":"30","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"1 tab cada 8 horas ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07374","ConcCant":"50","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029127015280328","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"13:15:41","CodHabIPS":"050010217501","TipoIDIPS":"NI","NroIDIPS":"890900518","CodDANEMunIPS":"05001","DirSedeIPS":"CALLE 64 CON CARRERA 51 D","TelSedeIPS":"4441333","TipoIDProf":"CC","NumIDProf":"43265229","PNProfS":"NORA","SNProfS":"ALEJANDRA","PAProfS":"ZULUAGA","SAProfS":"ESPINOSA","RegProfS":"5234505","TipoIDPaciente":"RC","NroIDPaciente":"1020312540","PNPaciente":"DAYANARA","SNPaciente":"","PAPaciente":"URRUTIA","SAPaciente":"MOSQUERA","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E301","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":0,"CausaS6":null,"DescMedPrinAct":"[TRIPTORELINA PAMOATO] 5,625mg/1ml","CodFF":"COLFF003","CodVA":"030","JustNoPBS":"paciente con antecedente de pubertad precoz central quien requiere continuar freno con pamoato de triptorelina para eviatr complicaciones endocrinas y metabolicas. ","Dosis":"11,25","DosisUM":"0168","NoFAdmon":"84","CodFreAdmon":3,"IndEsp":10,"CanTrat":"180","DurTrat":3,"CantTotalF":"2","UFCantTotal":"01","IndRec":"aplicar 1 ampolla de 11,25 mg im cada 84 dias. ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"05711","ConcCant":"5,625","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029177015280437","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"13:19:10","CodHabIPS":"084330205401","TipoIDIPS":"NI","NroIDIPS":"900488067","CodDANEMunIPS":"08433","DirSedeIPS":"CLL 12 N° 14- 35","TelSedeIPS":"3207404550","TipoIDProf":"CC","NumIDProf":"1048267790","PNProfS":"OSVALDO","SNProfS":"RUBEN","PAProfS":"MIRANDA","SAProfS":"HERNANDEZ","RegProfS":"1048267790","TipoIDPaciente":"CC","NroIDPaciente":"5112551","PNPaciente":"NESTOR","SNPaciente":"CARLOS","PAPaciente":"MONTAÑO","SAPaciente":"","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R15X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[{"ConOrden":1,"TipoPrest":2,"CausaS1":0,"CausaS2":1,"CausaS3":null,"CausaS4":1,"DescCausaS4":"PACIENTE MASCULINO DE 86 AÑOS DE EDAD con antecedentes de acv , actualmente con incontinencia fecal y urinaria","CausaS5":null,"CodSerComp":"139","DescSerComp":"pañales desechables tena tipo slip talla m","CanForm":"1","CadaFreUso":"8","CodFreUso":2,"Cant":"1","CantTotal":"90","CodPerDurTrat":5,"TipoTrans":null,"ReqAcom":null,"TipoIDAcomAlb":null,"NroIDAcomAlb":null,"ParentAcomAlb":null,"NombAlb":null,"CodMunOriAlb":null,"CodMunDesAlb":null,"JustNoPBS":"PACIENTE MASCULINO DE 86 AÑOS DE EDAD CON ANTECEDENTES DE ACV , ACTUALMENTE CON INCONTINENCIA FECAL Y URINARIA por lo cual se ordena PAÑALES DESECHABLES TENA TIPO SLIP TALLA M","IndRec":"PAÑALES DESECHABLES TENA TIPO SLIP TALLA M , usar 1 pañal cada 8 horas por 1 mes ","EstJM":1}]},{"prescripcion":{"NoPrescripcion":"20191029144015280607","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"13:25:00","CodHabIPS":"080010445401","TipoIDIPS":"NI","NroIDIPS":"901139193","CodDANEMunIPS":"08001","DirSedeIPS":"CALLE 33 No.33-09","TelSedeIPS":"3781483","TipoIDProf":"CC","NumIDProf":"55238090","PNProfS":"DIANA","SNProfS":"PATRICIA","PAProfS":"ARZUZAR","SAProfS":"MARTINEZ","RegProfS":"55238090","TipoIDPaciente":"CC","NroIDPaciente":"1010012785","PNPaciente":"JOMAR","SNPaciente":"","PAPaciente":"PEREZ","SAPaciente":"MARTINEZ","CodAmbAte":22,"RefAmbAte":0,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E43X","CodDxRel1":"E162","CodDxRel2":"G408","SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":2,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":null,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1503","DescProdNutr":"150310","CodForma":"10","CodViaAdmon":2,"JustNoPBS":"PACIENTE EN MALAS CONDICIONES MUSCULO.-NUTRICIONALES, PRESENTO UN ESTATUS CONVULSION,CON UNA HIPOGLICEMIA SOSTENIDA POR LO CUAL ESTA CON INFUSIONES DE SOLUCIONES GLUCOSADAS, PACIENTE PERSISTE CON HIPOGLICEMIA, QUIEN POR SU CONDICION neurológica PRESENTA IMPOSIBILIDAD PARA LA ADECUADA INGESTA DE NUTRIENTES. INCAPAZ DE ALIMENTARSE VOLUNTARIAMENTE Y, POR LO TANTO, LA TERAPIA NUTRICIONAL DEBE LLEVARSE A CABO POR VÍA ENteral a través de sonda nasogástrica.","Dosis":"250","DosisUM":"0176","NoFAdmon":"6","CodFreAdmon":2,"IndEsp":3,"CanTrat":"3","DurTrat":3,"CantTotalF":"12","UFCantTotal":"10","IndRec":"250 ml a través de sonda nasogastrica","NoPrescAso":"","EstJM":1}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029140015281860","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"14:04:46","CodHabIPS":"761470851601","TipoIDIPS":"NI","NroIDIPS":"900472731","CodDANEMunIPS":"76147","DirSedeIPS":"CARRERA 1 #16-107","TelSedeIPS":"2146686","TipoIDProf":"CC","NumIDProf":"10110853","PNProfS":"JUAN","SNProfS":"CARLOS","PAProfS":"MEJIA","SAProfS":"RUBIANO","RegProfS":"1217","TipoIDPaciente":"CC","NroIDPaciente":"66752366","PNPaciente":"DIANA","SNPaciente":"FERNANDA","PAPaciente":"FRANCO","SAPaciente":"ORTIZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M059","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"PREDNISOLONA","RznCausaS31":1,"DescRzn31":"Fallo","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[METILPREDNISOLONA] 4mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"Artritis reumatoide refactaria a fame y corticoides dosis moderada ","Dosis":"4","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"Tomar 1 tableta diaria ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00732","ConcCant":"4","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029182015282118","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"14:12:55","CodHabIPS":"080010003601","TipoIDIPS":"NI","NroIDIPS":"802000955","CodDANEMunIPS":"08001","DirSedeIPS":"CRA. 51B # 84-150","TelSedeIPS":null,"TipoIDProf":"CC","NumIDProf":"73007097","PNProfS":"CESAR","SNProfS":"RAMON","PAProfS":"TORRES","SAProfS":"TORRES","RegProfS":"5679","TipoIDPaciente":"CC","NroIDPaciente":"32668040","PNPaciente":"BEATRIZ","SNPaciente":"","PAPaciente":"CONSUEGRA","SAPaciente":"OSORIO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M350","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[ACIDO POLIACRILICO] 250mg/100g","CodFF":"C42948","CodVA":"047","JustNoPBS":"PACIENTE QUE REQUIERE LUBRICACIÓN Y HUMECTACIÓN POR PRESENTAR ENFERMEDAD DE OJO SECO. ","Dosis":"2","DosisUM":"0046","NoFAdmon":"8","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"4","UFCantTotal":"13","IndRec":"Gel Oftalmico 0.25g/100g Frasco x 10 g, 1 GOTA cada 8 Hora(s) en Ambos ojos por 3 Mes(es) Suministar: 4 Frasco(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50262","ConcCant":"250","UMedConc":"0168","CantCont":"100","UMedCantCont":"0062"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[HIALURONATO DE SODIO] 4mg/1ml","CodFF":"COLFF004","CodVA":"047","JustNoPBS":"PACIENTE QUE REQUIERE LUBRICACIÓN Y HUMECTACIÓN POR PRESENTAR ENFERMEDAD DE OJO SECO. ADEMAS DISMINUYE EL TRAUMA DEL PARPADEO, LO QUE PERMITE UNA MEJOR ADHERENCIA Y CICATRIZACIÓN EPITELIAL PROPORCIONANDO A SU VEZ ALIVIO DE LOS SÍNTOMAS. PROMUEVE LA PROLIFERACIÓN CELULAR Y LA MIGRACIÓN EPITELIAL CORNEAL\n","Dosis":"2","DosisUM":"0046","NoFAdmon":"6","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"01","IndRec":"Solucion Oftalmica. Ampolletas x 0.5 mL c/u, 1 DOSIS cada 6 Hora(s) en Ambos ojos por 3 Mes(es) Suministar: 90 Ampolla(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"50096","ConcCant":"4","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029126015282138","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"14:13:24","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"18937856","PNProfS":"JORGE","SNProfS":"LUIS","PAProfS":"MURGAS","SAProfS":"GOMEZ","RegProfS":"0776","TipoIDPaciente":"CC","NroIDPaciente":"36501051","PNPaciente":"AMPARO","SNPaciente":"","PAPaciente":"ZABALA","SAPaciente":"LOBO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M810","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"CALCIO","RznCausaS31":0,"DescRzn31":null,"RznCausaS32":1,"DescRzn32":"MALA TOLERANCIA GASTROINTESTINAL AL CALCIO EN COMPRIMIDOS","CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[CALCIO CARBONATO] 500mg/1U ; [VITAMINA D3] 200UI/1U","CodFF":"COLFF003","CodVA":"048","JustNoPBS":"PACIENTE CON DIAGNÓSTICO DE OSTEOPOROSIS POST MENOPÁUSICA CON MALA TOLERANCIA GASTROINTESTINAL A CALCIO EN COMPRIMIDOS POR LO QUE SE INDICA CALCIO 500 MG MAS VITAMINA D3 200 UI EN POLVO, COMO ALTERNATIVA PARA EL MANEJO DE SU ENFERMEDAD","Dosis":"1","DosisUM":"0247","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"4","DurTrat":5,"CantTotalF":"120","UFCantTotal":"78","IndRec":"TOMAR 1 SOBRE AL DÍA EN UN VASO DE AGUA","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"01497","ConcCant":"200","UMedConc":"0072","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"50312","ConcCant":"500","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029176015282455","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"14:22:03","CodHabIPS":"080010003601","TipoIDIPS":"NI","NroIDIPS":"802000955","CodDANEMunIPS":"08001","DirSedeIPS":"CRA. 51B # 84-150","TelSedeIPS":null,"TipoIDProf":"CC","NumIDProf":"73007097","PNProfS":"CESAR","SNProfS":"RAMON","PAProfS":"TORRES","SAProfS":"TORRES","RegProfS":"5679","TipoIDPaciente":"CC","NroIDPaciente":"7456235","PNPaciente":"GULFRAN","SNPaciente":"","PAPaciente":"ARIZA","SAPaciente":"ALVAREZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"H408","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[BRIMONIDINA TARTRATO] 2mg/1ml ; [BRINZOLAMIDA] 10mg/1ml","CodFF":"C42994","CodVA":"068","JustNoPBS":"Paciente que presenta presion intraocular elevada que requiere de tratamiento para controlar presion.\n","Dosis":"2","DosisUM":"0046","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"4","UFCantTotal":"13","IndRec":"Suspension Oftalmica. Frasco x 5 mL, 1 GOTA cada 12 Hora(s) en Ambos ojos por 3 Mes(es) Suministar: 4 Frasco(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"06863","ConcCant":"2","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"},{"ConOrden":1,"CodPriAct":"07532","ConcCant":"10","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[CONDROITINA SULFATO SODICA] 1,8mg/1ml ; [HIALURONATO DE SODIO] 1mg/1ml","CodFF":"COLFF004","CodVA":"047","JustNoPBS":"PACIENTE QUE REQUIERE LUBRICACIÓN Y HUMECTACIÓN POR PRESENTAR ENFERMEDAD DE OJO SECO. ADEMAS DISMINUYE EL TRAUMA DEL PARPADEO, LO QUE PERMITE UNA MEJOR ADHERENCIA Y CICATRIZACIÓN EPITELIAL PROPORCIONANDO A SU VEZ ALIVIO DE LOS SÍNTOMAS. PROMUEVE LA PROLIFERACIÓN CELULAR Y LA MIGRACIÓN EPITELIAL CORNEAL","Dosis":"2","DosisUM":"0046","NoFAdmon":"6","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"4","UFCantTotal":"13","IndRec":" Solucion Oftalmica LIBRE DE CONSERVADORES. Frasco x 10 mL, 1 GOTA cada 6 Hora(s) en Ambos ojos por 3 Mes(es) Suministar: 4 Frasco(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"50057","ConcCant":"1,8","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"},{"ConOrden":2,"CodPriAct":"50096","ConcCant":"1","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029110015282663","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"14:27:02","CodHabIPS":"080010003601","TipoIDIPS":"NI","NroIDIPS":"802000955","CodDANEMunIPS":"08001","DirSedeIPS":"CRA. 51B # 84-150","TelSedeIPS":null,"TipoIDProf":"CC","NumIDProf":"73007097","PNProfS":"CESAR","SNProfS":"RAMON","PAProfS":"TORRES","SAProfS":"TORRES","RegProfS":"5679","TipoIDPaciente":"CC","NroIDPaciente":"72123003","PNPaciente":"EDILBERTO","SNPaciente":"MANUEL","PAPaciente":"ESCORCIA","SAPaciente":"CAÑATE","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M350","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[HIALURONATO DE SODIO] 2mg/1ml","CodFF":"COLFF004","CodVA":"068","JustNoPBS":"PACIENTE QUE REQUIERE LUBRICACIÓN Y HUMECTACIÓN POR PRESENTAR ENFERMEDAD DE OJO SECO. ADEMAS DISMINUYE EL TRAUMA DEL PARPADEO, LO QUE PERMITE UNA MEJOR ADHERENCIA Y CICATRIZACIÓN EPITELIAL PROPORCIONANDO A SU VEZ ALIVIO DE LOS SÍNTOMAS. PROMUEVE LA PROLIFERACIÓN CELULAR Y LA MIGRACIÓN EPITELIAL CORNEAL","Dosis":"2","DosisUM":"0046","NoFAdmon":"6","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"4","UFCantTotal":"13","IndRec":"Solucion Oftalmica 2 mg/mL Frasco x 10 mL, 1 GOTA cada 6 Hora(s) en Ambos ojos por 3 Mes(es) Suministar: 4 Frasco(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50096","ConcCant":"2","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029153015282803","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"14:30:47","CodHabIPS":"080010003601","TipoIDIPS":"NI","NroIDIPS":"802000955","CodDANEMunIPS":"08001","DirSedeIPS":"CRA. 51B # 84-150","TelSedeIPS":null,"TipoIDProf":"CC","NumIDProf":"73007097","PNProfS":"CESAR","SNProfS":"RAMON","PAProfS":"TORRES","SAProfS":"TORRES","RegProfS":"5679","TipoIDPaciente":"CC","NroIDPaciente":"72042177","PNPaciente":"ALEJANDRO","SNPaciente":"ENRIQUE","PAPaciente":"MEZA","SAPaciente":"MONSALVO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"H108","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[HIALURONATO DE SODIO] 2mg/1ml","CodFF":"COLFF004","CodVA":"068","JustNoPBS":"PACIENTE QUE REQUIERE LUBRICACIÓN Y HUMECTACIÓN POR PRESENTAR ENFERMEDAD DE OJO SECO. ADEMAS DISMINUYE EL TRAUMA DEL PARPADEO, LO QUE PERMITE UNA MEJOR ADHERENCIA Y CICATRIZACIÓN EPITELIAL PROPORCIONANDO A SU VEZ ALIVIO DE LOS SÍNTOMAS. PROMUEVE LA PROLIFERACIÓN CELULAR Y LA MIGRACIÓN EPITELIAL CORNEAL","Dosis":"2","DosisUM":"0046","NoFAdmon":"6","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"4","UFCantTotal":"13","IndRec":"Solucion Oftalmica 2 mg/mL Frasco x 10 mL, 1 GOTA cada 6 Hora(s) en Ambos ojos por 3 Mes(es) Suministar: 4 Frasco(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50096","ConcCant":"2","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[OLOPATADINA] 2mg/1ml","CodFF":"COLFF004","CodVA":"068","JustNoPBS":"PACIENTE QUE REQUIERE MEDICAMENTO ANTIALERGICO PARA DISMINUIR SINTOMATOLOGIA DE PRURITO Y SENSACION DE CUERPO EXTRAÑO Y ASI PROPORCIONAR \nMEJORIA CLINICA. INDICADO EN EL ALIVIO DE LOS SÍNTOMAS Y SIGNOS DE LA CONJUNTIVITIS ALÉRGICA.\n \t","Dosis":"2","DosisUM":"0046","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"2","UFCantTotal":"13","IndRec":"Solucion Oftalmica. Frasco x 10 mL + bomba oftalmica OSD, 1 GOTA cada 12 Hora(s) en Ambos ojos por 3 Mes(es) Suministar: 2 Frasco(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"07257","ConcCant":"2","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029177015282872","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"14:32:15","CodHabIPS":"080010445415","TipoIDIPS":"NI","NroIDIPS":"901139193","CodDANEMunIPS":"08001","DirSedeIPS":"CALLE 99C No.9C-33","TelSedeIPS":"3781483","TipoIDProf":"CC","NumIDProf":"1045668608","PNProfS":"DALLANA","SNProfS":"PATRICIA","PAProfS":"PELUFFO","SAProfS":"DIAZ","RegProfS":"MND 03555","TipoIDPaciente":"CC","NroIDPaciente":"1740685","PNPaciente":"RUBEN","SNPaciente":"DARIO","PAPaciente":"BARRIOS","SAPaciente":"JIMENEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E441","CodDxRel1":"I10X","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":2,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":null,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1410","DescProdNutr":"141001","CodForma":"5","CodViaAdmon":1,"JustNoPBS":"SE OBSERVA PACIENTE ORIENTADO -- CON CABELLOS BIEN PLANTADOS –– PIEL SECA – DEPLECIÓN MUSCULAR – POCA MASA MUSCULAR - SIN EDEMAS – SIN DENTADURA EN LA PARTE INFERIOR. PESO: 39.5 KG TALLA: 1.47 CM IMC: 18.3 CLASF. NUTRICIONAL: A RIESGO DE DELGADEZ - EPOC – CARDIOPATIA – REFLUJO G.E - HTA -OCT. 24 -2019: CREATININA EN SANGRE: 1.78","Dosis":"237","DosisUM":"0176","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"180","UFCantTotal":"5","IndRec":" TOMAR 1 LATA CADA 12 HORAS (2 VECES AL DIA) – DURANTE 3 MESES (180 UNID) ","NoPrescAso":"","EstJM":3}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029128015282999","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"14:35:16","CodHabIPS":"761470728201","TipoIDIPS":"NI","NroIDIPS":"900247710","CodDANEMunIPS":"76147","DirSedeIPS":"CL 15 # 1N-55","TelSedeIPS":"2108988","TipoIDProf":"CC","NumIDProf":"18615571","PNProfS":"LUIS","SNProfS":"MARCELO","PAProfS":"VALENCIA","SAProfS":"LONDOÑO","RegProfS":"2196-08","TipoIDPaciente":"CC","NroIDPaciente":"1112905288","PNPaciente":"DIEGO","SNPaciente":"FERNANDO","PAPaciente":"DIAZ","SAPaciente":"SILVA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"H160","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":3,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[NATAMICINA] 5mg/5ml","CodFF":"C42994","CodVA":"047","JustNoPBS":"PACIENTE CON ULCERA CORNEAL INFECCIOSA OJO DERECHO SE FORMULA NATAMICINA. PREPARACIÓN MAGISTRAL NO HAY ALTERNATIVA EN EL PBS QUE REEMPLACE O SUSTITUYA LA FORMULACIÓN.\t\n","Dosis":"1","DosisUM":"0046","NoFAdmon":"2","CodFreAdmon":2,"IndEsp":10,"CanTrat":"1","DurTrat":5,"CantTotalF":"2","UFCantTotal":"13","IndRec":"APLICAR UNA GOTA CADA DOS HORAS EN OJO DERECHO \n","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50122","ConcCant":"5","UMedConc":"0168","CantCont":"5","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029160015283782","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"14:53:21","CodHabIPS":"760200165701","TipoIDIPS":"NI","NroIDIPS":"891900438","CodDANEMunIPS":"76020","DirSedeIPS":"SEDE BARRIO VILLA FERNANDA","TelSedeIPS":"2004120","TipoIDProf":"CC","NumIDProf":"1088299161","PNProfS":"PABLO","SNProfS":"","PAProfS":"BOTERO","SAProfS":"MORENO","RegProfS":"1088299161","TipoIDPaciente":"CC","NroIDPaciente":"24556275","PNPaciente":"AURA","SNPaciente":"ROSA","PAPaciente":"MUNERA","SAPaciente":"DE BERMUDEZ","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M815","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[ACIDO IBANDRONICO] 150mg/1U ; [COLECALCIFEROL] 12000UI/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"PACIENTE CON REQUERIMIENTOS DE BISFOSFONATO PARA MANEJO DE OSTEOPOROSIS SEVERA","Dosis":"1","DosisUM":"9000","NoFAdmon":"1","CodFreAdmon":5,"IndEsp":10,"CanTrat":"6","DurTrat":5,"CantTotalF":"6","UFCantTotal":"66","IndRec":"tomar 1 cada mes por 6 meses ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"01497","ConcCant":"12000","UMedConc":"0072","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"07189","ConcCant":"150","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029161015283945","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"14:56:53","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"18937856","PNProfS":"JORGE","SNProfS":"LUIS","PAProfS":"MURGAS","SAProfS":"GOMEZ","RegProfS":"0776","TipoIDPaciente":"CC","NroIDPaciente":"26725491","PNPaciente":"HILBA","SNPaciente":"DE JESUS","PAPaciente":"GARCIA","SAPaciente":"LOPEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M059","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"PREDNISOLONA","RznCausaS31":0,"DescRzn31":null,"RznCausaS32":1,"DescRzn32":"MALA TOLERANCIA GASTROINTESTINAL","CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[METILPREDNISOLONA] 4mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"SE INDICA METILPREDNISOLONA, COMO ALTERNATIVA PAR EL MANEJO DE LA ARTRITIS REUMATOIDE, POR MALA TOLERANCIA GASTROINTESTINAL A LA CLOROQUINA","Dosis":"4","DosisUM":"0168","NoFAdmon":"2","CodFreAdmon":3,"IndEsp":10,"CanTrat":"4","DurTrat":5,"CantTotalF":"60","UFCantTotal":"66","IndRec":"TOMAR 1 TABLETA DIA POR MEDIOA","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00732","ConcCant":"4","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029185015284173","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"15:02:02","CodHabIPS":"086380015401","TipoIDIPS":"NI","NroIDIPS":"890103127","CodDANEMunIPS":"08638","DirSedeIPS":"CL 25 Nº 8 - 25","TelSedeIPS":"8781338 - 8780382","TipoIDProf":"CC","NumIDProf":"8638895","PNProfS":"RAFAEL","SNProfS":"ANTONIO","PAProfS":"BORGE","SAProfS":"SALAZAR","RegProfS":"002074","TipoIDPaciente":"CC","NroIDPaciente":"22452000","PNPaciente":"TERESA","SNPaciente":"","PAPaciente":"SIERRA","SAPaciente":"HERNANDEZ","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"I679","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[{"ConOrden":1,"TipoPrest":1,"CausaS1":0,"CausaS2":1,"CausaS3":null,"CausaS4":1,"DescCausaS4":"femenina en condición de discapacidad, con antecedente de enfermedad cerebrovascular, que no deambula, que no puede valerse por si mismo ","CausaS5":null,"CodSerComp":"139","DescSerComp":"pañales tena talla l ","CanForm":"1","CadaFreUso":"8","CodFreUso":2,"Cant":"3","CantTotal":"270","CodPerDurTrat":5,"TipoTrans":null,"ReqAcom":null,"TipoIDAcomAlb":null,"NroIDAcomAlb":null,"ParentAcomAlb":null,"NombAlb":null,"CodMunOriAlb":null,"CodMunDesAlb":null,"JustNoPBS":"femenina en condicion de discapacidad producto de secuelas de enfermedad cerebrovascular , no deambula, ni tampoco puede valerse por si misma para hacer sus actividades diarias, motivo por el cual requiere uso de pañales de manera domiciliaria ","IndRec":"usar un pañal tena talla l y cada 8 horas y luego desechar ","EstJM":1}]},{"prescripcion":{"NoPrescripcion":"20191029127015284217","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"15:02:57","CodHabIPS":"761470851601","TipoIDIPS":"NI","NroIDIPS":"900472731","CodDANEMunIPS":"76147","DirSedeIPS":"CARRERA 1 #16-107","TelSedeIPS":"2146686","TipoIDProf":"CC","NumIDProf":"10110853","PNProfS":"JUAN","SNProfS":"CARLOS","PAProfS":"MEJIA","SAProfS":"RUBIANO","RegProfS":"1217","TipoIDPaciente":"CC","NroIDPaciente":"31403557","PNPaciente":"BERTHA","SNPaciente":"LIBIA","PAPaciente":"MENDOZA","SAPaciente":"DE SUAREZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M350","CodDxRel1":"M158","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"CLOROQUINA","RznCausaS31":1,"DescRzn31":"fallo","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":0,"CausaS6":null,"DescMedPrinAct":"[HIDROXICLOROQUINA SULFATO] 200mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"Menores efectos secundarios y mayor efecto farmacológico","Dosis":"200","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"TOMAR 1 TABLETA DIARIA ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00796","ConcCant":"200","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029185015284420","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"15:07:15","CodHabIPS":"760010379901","TipoIDIPS":"NI","NroIDIPS":"890303461","CodDANEMunIPS":"76001","DirSedeIPS":"CALLE 5 # 36 - 08","TelSedeIPS":"6206000 Extension 1010","TipoIDProf":"CC","NumIDProf":"1026251475","PNProfS":"MARYI","SNProfS":"JULIETH","PAProfS":"SORZA","SAProfS":"GONZALEZ","RegProfS":"MND02432","TipoIDPaciente":"CC","NroIDPaciente":"2558277","PNPaciente":"JOSE","SNPaciente":"TULIO","PAPaciente":"MARTINEZ","SAPaciente":"RIVAS","CodAmbAte":22,"RefAmbAte":0,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"S064","CodDxRel1":"E43X","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":1,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":null,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1504","DescProdNutr":"150420","CodForma":"7","CodViaAdmon":2,"JustNoPBS":"PACIENTE CON DIAGNÓSTICO PRINCIPAL CONFIRMADO, CON ALTOS REQUERIMIENTOS DE ENERGÍA Y PROTEÍNAS, EN ESTRÉS METABÓLICO, QUIEN REQUIERE ASISTENCIA INTRAHOSPITALARIA POR NUTRICIÓN Y DIETÉTICA, CON ALIMENTO COMPLETO Y BALANCEADO, DE FÁCIL DIGESTIÓN, ABSORCIÓN, Y TOLERANCIA PARA FAVORECER REPARACIÓN TISULAR, SUMINISTRADO VÍA ENTERAL PARA MEJORAR ADHERENCIA A TRATAMIENTO NUTRICIONAL COMO PARTE INTEGRAL DEL CUIDADO ASISTENCIAL, PREVENIR COMPLICACIONES Y REDUCIR LA MORBIMORTALIDAD.","Dosis":"1550","DosisUM":"0176","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":5,"CanTrat":"30","DurTrat":3,"CantTotalF":"31","UFCantTotal":"7","IndRec":"10 cc hora a trofismo","NoPrescAso":"","EstJM":1}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029196015285254","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"15:25:13","CodHabIPS":"761470851601","TipoIDIPS":"NI","NroIDIPS":"900472731","CodDANEMunIPS":"76147","DirSedeIPS":"CARRERA 1 #16-107","TelSedeIPS":"2146686","TipoIDProf":"CC","NumIDProf":"10110853","PNProfS":"JUAN","SNProfS":"CARLOS","PAProfS":"MEJIA","SAProfS":"RUBIANO","RegProfS":"1217","TipoIDPaciente":"CC","NroIDPaciente":"31419575","PNPaciente":"DORA","SNPaciente":"MERCEDES","PAPaciente":"GONZALEZ","SAPaciente":"ESCALANTE","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M059","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"PREDNISOLONA","RznCausaS31":1,"DescRzn31":"fallo","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[METILPREDNISOLONA] 4mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"Artritis reumatoide con fallo a fame, actualmente pcr y vsg altas.","Dosis":"4","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"tomar 1 tableta diaria","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00732","ConcCant":"4","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029128015285612","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"15:33:07","CodHabIPS":"270010077002","TipoIDIPS":"NI","NroIDIPS":"900520293","CodDANEMunIPS":"27001","DirSedeIPS":"CARRERA 2 No. 26-20 B/. ROMA","TelSedeIPS":"094-6712254 - 094-6719091","TipoIDProf":"CC","NumIDProf":"4803351","PNProfS":"JONATHAN","SNProfS":"","PAProfS":"ARGUELLO","SAProfS":"MOYA","RegProfS":"270309","TipoIDPaciente":"TI","NroIDPaciente":"1077432798","PNPaciente":"ANGELICA","SNPaciente":"","PAPaciente":"ECHEVERRY","SAPaciente":"TELLO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"L708","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"DOXICICLINA","RznCausaS31":1,"DescRzn31":"POCA RESPUESTA CLINICA","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":0,"CausaS6":null,"DescMedPrinAct":"[ISOTRETINOINA] 20mg/1U","CodFF":"COLFF006","CodVA":"048","JustNoPBS":"PACIENTE CON \"GRANOS\" EN PIEL DE APROX. 4 AÑOS DE EVOLUCION ASOCIADOS A PRURITO Y \"MOLESTIAS\" PARA LO CUAL HA REALIZADO MULTIPLES TRATAMIENTOS CASEROS Y AUTOMEDICADOS CON EMPEORAMIENTO DEL CUADRO,SE SOLICITA USAR ISOTRETINOINA Y EVALUAR EN 4 MESES","Dosis":"20","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"4","DurTrat":5,"CantTotalF":"120","UFCantTotal":"14","IndRec":"NO ALCOHOL NO OTROS MEDICAMENTOS ANTICONCEPCION EXTREMA ANTES, DURANTE Y 1 MES DESPUES DE TERMINADO EL TRATAMIENTO ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"04617","ConcCant":"20","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029159015285953","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"15:41:15","CodHabIPS":"700010018501","TipoIDIPS":"NI","NroIDIPS":"823002342","CodDANEMunIPS":"70001","DirSedeIPS":"CARRERA 22 No. 14-55","TelSedeIPS":"2820285","TipoIDProf":"CC","NumIDProf":"17975458","PNProfS":"BELTRAN","SNProfS":"MANUEL","PAProfS":"DANGOND","SAProfS":"HINOJOZA","RegProfS":"17975458","TipoIDPaciente":"CC","NroIDPaciente":"22929475","PNPaciente":"ALGIMIRA","SNPaciente":"","PAPaciente":"COLEY","SAPaciente":"DE RAMIREZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"I839","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[DIOSMINA] 450mg/1U ; [HESPERIDINA] 50mg/1U","CodFF":"COLFF006","CodVA":"048","JustNoPBS":"PACIENTE CON CUADRO CLINICO DE VARIOS MESES DE EVOLUCION CONSISTENTE EN DOLOR EDEMA PRURITO SESACION DE CANASANCIO, CALAMBRES ,DILATACIONES VENOSAS EN MSIS, SE LE ORDENA MEDICAMENTO CON EL FIN DE AUMENTAR EL TONO VENOSO, Y POR LO TANTO PUEDE REDUCIR LA CAPACITANCIA VENOSA, LA DISTENSIBILIDAD Y LA EXTASIS, ESTO AUMENTA EL RETORNO VENOSO Y REDUCE LA PRESIÓN VENOSA. MEJORA EL DRENAJE LINFÁTICO","Dosis":"500","DosisUM":"0168","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"180","UFCantTotal":"14","IndRec":"HIPERSENSIBILIDAD A ALGUNOS DE SUS COMPONENTES","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"04370","ConcCant":"450","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"50078","ConcCant":"50","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029163015286362","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"15:49:24","CodHabIPS":"200010090101","TipoIDIPS":"NI","NroIDIPS":"900016598","CodDANEMunIPS":"20001","DirSedeIPS":"CARRERA 16 N°16A-42","TelSedeIPS":"5898632","TipoIDProf":"CC","NumIDProf":"17975458","PNProfS":"BELTRAN","SNProfS":"MANUEL","PAProfS":"DANGOND","SAProfS":"HINOJOZA","RegProfS":"17975458","TipoIDPaciente":"CC","NroIDPaciente":"22861991","PNPaciente":"ANA","SNPaciente":"MANUELA","PAPaciente":"CORREA","SAPaciente":"ALVAREZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"I839","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[HIDROSMINA] 200mg/1U","CodFF":"COLFF006","CodVA":"048","JustNoPBS":"PACIENTE CON CUADRO CLINICO DE VARIOS MESES DE EVOLUCION CONSISTENTE EN DOLOR EDEMA PRURITO SENANACION DE CANASANCIO, CALAMBRES ,DILATACIONES VENOSAS EN MSIS, SE LE ORDENA MEDICAMENTO USADO EXCLUSIVAMENTE PARA PROTEGER DEL TODO LOS DISTINTOS VASOS SANGUINEOS; ESTE PARTICULARMENTE ACTUA SOBRE LOS VASOS SANGUINEOS PEQUEÑOS, DE ESTE MODO PROTEGE REDUCIENDO EL RIESGO DE FRAGILIDAD Y A SU VEZ LA PERMEABILIDAD DE ESTOS.","Dosis":"200","DosisUM":"0168","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"180","UFCantTotal":"14","IndRec":"HIPERSENSIBILIDAD A ALGUNOS DE SUS COMPONENTES.\nSE PUEDEN PRESENTAN NÁUSEAS, MAREOS, DOLOR DE CABEZA (CEFALEA), CIERTA ACIDEZ, PÉRDIDA DEL APETITO","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50488","ConcCant":"200","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029194015286654","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"15:55:46","CodHabIPS":"134300092701","TipoIDIPS":"NI","NroIDIPS":"900638867","CodDANEMunIPS":"13430","DirSedeIPS":"CARRERA 13 #16-45","TelSedeIPS":"3012521066 - 3215453690","TipoIDProf":"CC","NumIDProf":"9142459","PNProfS":"ELIAS","SNProfS":"JOSE","PAProfS":"HERNANDEZ","SAProfS":"GARCIA","RegProfS":"13003094","TipoIDPaciente":"CC","NroIDPaciente":"23205173","PNPaciente":"NELLY","SNPaciente":"DEL ROSARIO","PAPaciente":"ACUÑA","SAPaciente":"DE MENDOZA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E119","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"GLIBENCLAMIDA","RznCausaS31":1,"DescRzn31":"NO HAY CONTROL METABOLICO ALTO RIESGO CARDIOVASCULAR ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[EMPAGLIFLOZINA] 10mg/1U ; [LINAGLIPTINA] 5mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"MEJOR CONTROL METABOLICO REDUCCION DEL RIESGO CARDIOVASCULAR ","Dosis":"1","DosisUM":"9000","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"66","IndRec":"TOMAR 1 TAB DIARIA PARA 3 MESES ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"09039","ConcCant":"5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"09398","ConcCant":"10","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029130015286729","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"15:57:25","CodHabIPS":"080010430501","TipoIDIPS":"NI","NroIDIPS":"900206215","CodDANEMunIPS":"08001","DirSedeIPS":"CALLE 47 NUMERO 19 - 47","TelSedeIPS":"3174342880","TipoIDProf":"CC","NumIDProf":"32833623","PNProfS":"ALBA","SNProfS":"LUZ","PAProfS":"BARRAZA","SAProfS":"SOLANO","RegProfS":"32833623","TipoIDPaciente":"CC","NroIDPaciente":"8525641","PNPaciente":"JUAN","SNPaciente":"","PAPaciente":"PARRA","SAPaciente":"TOVAR","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"B24X","CodDxRel1":"E441","CodDxRel2":"Z724","SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":2,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":1,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1503","DescProdNutr":"150314","CodForma":"3","CodViaAdmon":1,"JustNoPBS":"paciente masculino 42 años control B24X,, en regulares condiciones musculo -nutricionales ,debuto en urgencias síntomas diarrea,SÍNDROME convulsivo,Dx nutricional delgadez , IMC 17,9 , deplecion de masa muscular, perdida de 10 kg de peso, circunferencia dela pantorrilla 27 cm,nsuficiente ingesta de calorico- proteica,prefiere preparaciones LIQUIDAS,se recomienda terapia nutricional a para cubrir requerimientos y evitar avance de catabolismo","Dosis":"1","DosisUM":"0247","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"60","DurTrat":3,"CantTotalF":"60","UFCantTotal":"3","IndRec":"se indica alimento alimento hiperproteico densamente calorico ,vía oral, cada 24 horas por 60 días","NoPrescAso":null,"EstJM":1}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029179015286836","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"15:59:30","CodHabIPS":"700010018501","TipoIDIPS":"NI","NroIDIPS":"823002342","CodDANEMunIPS":"70001","DirSedeIPS":"CARRERA 22 No. 14-55","TelSedeIPS":"2820285","TipoIDProf":"CC","NumIDProf":"17975458","PNProfS":"BELTRAN","SNProfS":"MANUEL","PAProfS":"DANGOND","SAProfS":"HINOJOZA","RegProfS":"17975458","TipoIDPaciente":"CC","NroIDPaciente":"22861991","PNPaciente":"ANA","SNPaciente":"MANUELA","PAPaciente":"CORREA","SAPaciente":"ALVAREZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"I839","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[HIDROSMINA] 200mg/1U","CodFF":"COLFF006","CodVA":"048","JustNoPBS":"PACIENTE CON CUADRO CLINICO DE VARIOS MESES DE EVOLUCION CONSISTENTE EN DOLOR EDEMA PRURITO SENANACION DE CANASANCIO, CALAMBRES ,DILATACIONES VENOSAS EN MSIS, SE LE ORDENA MEDICAMENTO USADO EXCLUSIVAMENTE PARA PROTEGER DEL TODO LOS DISTINTOS VASOS SANGUINEOS; ESTE PARTICULARMENTE ACTUA SOBRE LOS VASOS SANGUINEOS PEQUEÑOS, DE ESTE MODO PROTEGE REDUCIENDO EL RIESGO DE FRAGILIDAD Y A SU VEZ LA PERMEABILIDAD DE ESTOS","Dosis":"200","DosisUM":"0168","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"180","UFCantTotal":"14","IndRec":"HIPERSENSIBILIDAD A ALGUNOS DE SUS COMPONENTES.\nSE PUEDEN PRESENTAN NÁUSEAS, MAREOS, DOLOR DE CABEZA (CEFALEA), CIERTA ACIDEZ, PÉRDIDA DEL APETITO","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50488","ConcCant":"200","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029113015287022","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"16:04:00","CodHabIPS":"080010226001","TipoIDIPS":"NI","NroIDIPS":"802021171","CodDANEMunIPS":"08001","DirSedeIPS":"Carrera 45 84-153","TelSedeIPS":"3781288","TipoIDProf":"CC","NumIDProf":"3953431","PNProfS":"HECTOR","SNProfS":"JOSE","PAProfS":"STAVE","SAProfS":"SERRANO","RegProfS":"004736","TipoIDPaciente":"RC","NroIDPaciente":"1052188693","PNPaciente":"JIRETH","SNPaciente":"","PAPaciente":"PIMIENTA","SAPaciente":"SANCHEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"D570","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[POLISACARIDO NEUMOCOCCICO SEROTIPO 9V CONJUGADO CON PROTEÍNA D PROTEÍNA PORTADORA 1µg&POLISACARIDO NEUMOCOCCICO SEROTIPO 19F CONJUGADO CON PROTEÍNA PORTADORA DE TOXOIDE DIFTÉRICO 3µg&POLISACARIDO NEUMOCOCCICO SEROTIPO 23F CONJUGADO CON PROTEÍNA D PROTEÍNA PORTADORA 1µg&POLISACARIDO NEUMOCOCCICO SEROTIPO 1 CONJUGADO CON PROTEÍNA D PROTEÍNA PORTADORA 1µg&POLISACARIDO NEUMOCOCCICO SEROTIPO 18C CONJUGADO CON PROTEÍNA PORTADORA DE TOXOIDE TETÁNICO 3µg&POLISACARIDO NEUMOCOCCICO SEROTIPO 5 CONJUGADO CON PROTEÍNA D PROTEÍNA PORTADORA 1µg&POLISACARIDO NEUMOCOCCICO SEROTIPO 7F CONJUGADO CON PROTEÍNA D PROTEÍNA PORTADORA 1µg&POLISACARIDO NEUMOCOCCICO SEROTIPO 14 CONJUGADO CON PROTEÍNA D PROTEÍNA PORTADORA 1µg&POLISACARIDO NEUMOCOCCICO SEROTIPO 4 CONJUGADO CON PROTEÍNA D PROTEÍNA PORTADORA 3µg&POLISACARIDO NEUMOCOCCICO SEROTIPO 6B CONJUGADO CON PROTEÍNA D PROTEÍNA PORTADORA 1µg] 1Dosis/0,5ml","CodFF":"C42994","CodVA":"030","JustNoPBS":"PACIENTE DE 7 AÑOS DE EDAD CON ANEMIA DE CELULAS FALCIFORME CON CRISIS","Dosis":"1","DosisUM":"9000","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"1","DurTrat":3,"CantTotalF":"1","UFCantTotal":"01","IndRec":"APLICAR UNA AMPOLLA INTRAMUSCULAR ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50182","ConcCant":"1","UMedConc":"9000","CantCont":"0,5","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029192015287205","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"16:08:15","CodHabIPS":"475550026601","TipoIDIPS":"NI","NroIDIPS":"819002363","CodDANEMunIPS":"47555","DirSedeIPS":"CL 14 KR 23 ESQUINA","TelSedeIPS":"4851895","TipoIDProf":"CC","NumIDProf":"32768740","PNProfS":"PIEDAD","SNProfS":"DEL SOCORRO","PAProfS":"GONZALEZ","SAProfS":"MARENCO","RegProfS":"08-000433","TipoIDPaciente":"CC","NroIDPaciente":"1081904448","PNPaciente":"GLORIA","SNPaciente":"MARCELA","PAPaciente":"PAZ","SAPaciente":"SALAZAR","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E441","CodDxRel1":"E46X","CodDxRel2":"D508","SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":1,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":0,"DXVIH":null,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1504","DescProdNutr":"150415","CodForma":"5","CodViaAdmon":1,"JustNoPBS":"ACTUALMENTE NO SE ENCUENTRA EN EL PLAN OBLIGATORIO DE SALUD PACIENTE DE EDAD 25 AÑOS DE SEXO FEMENINO CON UN PESO DE 44KG Y TALLA 166 CM IMC 16 CON DELGADEZ PERDIDA DE MASA CORPORAL NO TOLERA LOS ALIMENTOS SÓLIDOS INAPETENCIA NECESITA SOPORTE NUTRICIONAL PARA CONTROL Y MANEJO DE SU ENFERMEDAD","Dosis":"2","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"60","DurTrat":3,"CantTotalF":"3","UFCantTotal":"5","IndRec":"TOMAR 2 PORCIONES DIARIAS CON DIETA HIPERPROTEICA","NoPrescAso":null,"EstJM":3}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029111015287278","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"16:09:49","CodHabIPS":"764970165001","TipoIDIPS":"NI","NroIDIPS":"891901041","CodDANEMunIPS":"76497","DirSedeIPS":"CALLE 6 # 2-90","TelSedeIPS":"2053111","TipoIDProf":"CC","NumIDProf":"1112764666","PNProfS":"CAROLINA","SNProfS":"","PAProfS":"MARIN","SAProfS":"MONTOYA","RegProfS":"1112764666","TipoIDPaciente":"CC","NroIDPaciente":"2587016","PNPaciente":"JESUS","SNPaciente":"ANTONIO","PAPaciente":"BEDOYA","SAPaciente":"OSORIO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R32X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[{"ConOrden":1,"TipoPrest":2,"CausaS1":0,"CausaS2":1,"CausaS3":null,"CausaS4":1,"DescCausaS4":"PACIENTE EN LA OCTAVA DÉCADA DE LA VIDA, EN EL CONTEXTO DE INCONTINENCIA URINARIA, ADEMÁS CON CRTHEL DE 40, EN EL EXAMNE FÍSICO SE ENCUENTRA ME","CausaS5":null,"CodSerComp":"139","DescSerComp":"PACIENTE EN LA OCTAVA DÉCADA DE LA VIDA CON PS DESCRITOS, EN EL CONTEXTO DE INCONTINENCIA URINARIA, ADEMÁS CON BATHEL DE 40, EN EL EXAMNE FÍSICO SE ENCUENTRA ME","CanForm":"90","CadaFreUso":"1","CodFreUso":5,"Cant":"3","CantTotal":"270","CodPerDurTrat":5,"TipoTrans":null,"ReqAcom":null,"TipoIDAcomAlb":null,"NroIDAcomAlb":null,"ParentAcomAlb":null,"NombAlb":null,"CodMunOriAlb":null,"CodMunDesAlb":null,"JustNoPBS":"PACIENTE EN LA OCTAVA DÉCADA DE LA VIDA CON PS DESCRITOS, EN EL CONTEXTO DE INCONTINENCIA URINARIA, ADEMÁS CON BARTHEL DE 40, EN EL EXAMNE FÍSICO SE ENCUENTRA MEMORIA RECIENTE Y REMOTA ALTERADA","IndRec":"PAÑAL TENA PANTALÓN TALLA L","EstJM":1}]},{"prescripcion":{"NoPrescripcion":"20191029161015287314","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"16:10:44","CodHabIPS":"080010226001","TipoIDIPS":"NI","NroIDIPS":"802021171","CodDANEMunIPS":"08001","DirSedeIPS":"Carrera 45 84-153","TelSedeIPS":"3781288","TipoIDProf":"CC","NumIDProf":"3953431","PNProfS":"HECTOR","SNProfS":"JOSE","PAProfS":"STAVE","SAProfS":"SERRANO","RegProfS":"004736","TipoIDPaciente":"RC","NroIDPaciente":"1052188693","PNPaciente":"JIRETH","SNPaciente":"","PAPaciente":"PIMIENTA","SAPaciente":"SANCHEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"D570","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[VESICULAS PURIFICADAS DE MEMBRANA EXTERNA DE MENINGOCOCO SEROGRUPO B 50µg&POLISACARIDO CAPSULAR PURIFICADO DE MENINGOCOCO SEROGRUPO C 50µg] 1Dosis/0,5ml","CodFF":"C42994","CodVA":"030","JustNoPBS":"PACIETE DE 7 AÑOS CON ANEMINA DE CELULAS FALCIFORME CON CRISIS","Dosis":"1","DosisUM":"9000","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"1","DurTrat":3,"CantTotalF":"1","UFCantTotal":"01","IndRec":"APLICAR 1 AMPOLLA INTRAMUSCULAR","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50179","ConcCant":"1","UMedConc":"9000","CantCont":"0,5","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029122015287345","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"16:11:29","CodHabIPS":"700010006601","TipoIDIPS":"NI","NroIDIPS":"892280033","CodDANEMunIPS":"70001","DirSedeIPS":"KR 14 16B 100","TelSedeIPS":"2823869","TipoIDProf":"CC","NumIDProf":"92497293","PNProfS":"JULIO","SNProfS":"RAFAEL","PAProfS":"GONZALEZ","SAProfS":"SILVA","RegProfS":"14966","TipoIDPaciente":"CC","NroIDPaciente":"33081635","PNPaciente":"CLAUDELLYS","SNPaciente":"","PAPaciente":"DIAZ","SAPaciente":"DAVILA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"G513","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"CARBAMAZEPINA","RznCausaS31":1,"DescRzn31":"NO CONTROLO ESPASMO ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[TOXINA BOTULINICA] 100U/1U","CodFF":"COLFF003","CodVA":"023","JustNoPBS":"PACIENTE DE 56 AÑOS DE EDAD QUE ASISTE A CONTROL CON NEUROLOGIA CLINICA, QUIEN PRESENTA ESPASMO HEMIFACIAL CLONICO, REQUIERE TRATAMIENTO CON TOXINA BOTULINICA TIPO A BOTOX POR 100 UNIDADES PRESENTANDO BUENA ADHERENCIA AL TRATAMIENTO ","Dosis":"100","DosisUM":"0247","NoFAdmon":"3","CodFreAdmon":5,"IndEsp":10,"CanTrat":"1","DurTrat":3,"CantTotalF":"1","UFCantTotal":"01","IndRec":"APLICAR UNA AMPOLLA DE TOXINA BOTULINICA TIPO A BOTOX POR 100 UND CADA TRES MESES VIA INTRADERMICA ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50137","ConcCant":"100","UMedConc":"0247","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029118015287407","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"16:13:05","CodHabIPS":"764970165001","TipoIDIPS":"NI","NroIDIPS":"891901041","CodDANEMunIPS":"76497","DirSedeIPS":"CALLE 6 # 2-90","TelSedeIPS":"2053111","TipoIDProf":"CC","NumIDProf":"1112764666","PNProfS":"CAROLINA","SNProfS":"","PAProfS":"MARIN","SAProfS":"MONTOYA","RegProfS":"1112764666","TipoIDPaciente":"CC","NroIDPaciente":"2587016","PNPaciente":"JESUS","SNPaciente":"ANTONIO","PAPaciente":"BEDOYA","SAPaciente":"OSORIO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"R32X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[OXIDO DE ZINC] 40g/100g","CodFF":"C28944","CodVA":"061","JustNoPBS":"PACIENTE EN LA OCTAVA DÉCADA DE LA VIDA CON PS DESCRITOS, EN EL CONTEXTO DE INCONTINENCIA URINARIA, ADEMÁS CON BARTHEL DE 40, EN EL EXAMNE FÍSICO SE ENCUENTRA MEMORIA RECIENTE Y REMOTA ALTERADA","Dosis":"200","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":5,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"6","UFCantTotal":"73","IndRec":"USAR EN CADA CAMBIO DE PAÑAL","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50095","ConcCant":"40","UMedConc":"0062","CantCont":"100","UMedCantCont":"0062"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029117015287440","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"16:13:52","CodHabIPS":"080010003601","TipoIDIPS":"NI","NroIDIPS":"802000955","CodDANEMunIPS":"08001","DirSedeIPS":"CRA. 51B # 84-150","TelSedeIPS":null,"TipoIDProf":"CC","NumIDProf":"85472859","PNProfS":"JUAN","SNProfS":"JOSE","PAProfS":"BERNAL","SAProfS":"DIAZ GRANADOS","RegProfS":"R.M. 47981","TipoIDPaciente":"CC","NroIDPaciente":"1143464270","PNPaciente":"ANDRES","SNPaciente":"FELIPE","PAPaciente":"CARRANZA","SAPaciente":"MIER","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"H108","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[CARBOXIMETILCELULOSA SODICA] 5mg/1ml","CodFF":"COLFF004","CodVA":"047","JustNoPBS":"PACIENTE QUE REQUIERE LUBRICACIÓN Y HUMECTACIÓN POR PRESENTAR ENFERMEDAD DE OJO SECO. ADEMAS DISMINUYE EL TRAUMA DEL PARPADEO, LO QUE PERMITE UNA MEJOR ADHERENCIA Y CICATRIZACIÓN EPITELIAL PROPORCIONANDO A SU VEZ ALIVIO DE LOS SÍNTOMAS. PROMUEVE LA PROLIFERACIÓN CELULAR Y LA MIGRACIÓN EPITELIAL CORNEAL","Dosis":"2","DosisUM":"0046","NoFAdmon":"8","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"2","UFCantTotal":"13","IndRec":"Solucion Oftalmica Frasco X 15 mL, 1 GOTA cada 8 Hora(s) en Ambos ojos por 3 Mes(es) Suministar: 2 Frasco(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"04950","ConcCant":"5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[OLOPATADINA] 2mg/1ml","CodFF":"COLFF004","CodVA":"068","JustNoPBS":"PACIENTE QUE REQUIERE MEDICAMENTO ANTIALERGICO PARA DISMINUIR SINTOMATOLOGIA DE PRURITO Y SENSACION DE CUERPO EXTRAÑO Y ASI PROPORCIONAR \nMEJORIA CLINICA. INDICADO EN EL ALIVIO DE LOS SÍNTOMAS Y SIGNOS DE LA CONJUNTIVITIS ALÉRGICA.","Dosis":"2","DosisUM":"0046","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"2","UFCantTotal":"13","IndRec":"Soluciòn Oftalmica (Libre de Conservantes) Frasco x 5 mL, 1 GOTA cada 24 Hora(s) en Ambos ojos por 3 Mes(es) Suministar: 2 Frasco(s)","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"07257","ConcCant":"2","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029173015287614","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"16:17:52","CodHabIPS":"080010409201","TipoIDIPS":"NI","NroIDIPS":"900448414","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 47 Nº 84 - 157","TelSedeIPS":"3545674","TipoIDProf":"CC","NumIDProf":"85470076","PNProfS":"ERNESTO","SNProfS":"AGUSTIN","PAProfS":"SANTIAGO","SAProfS":"HENRIQUEZ","RegProfS":"2110","TipoIDPaciente":"CC","NroIDPaciente":"3683188","PNPaciente":"ABEL","SNPaciente":"ENRIQUE","PAPaciente":"CABRERA","SAPaciente":"VILLALOBO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J449","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[UMECLIDINIO] 55µg/1Dosis ; [VILANTEROL] 22µg/1Dosis","CodFF":"COLFF002","CodVA":"055","JustNoPBS":"SE REFORMULA MEDICACIÓN DE USO CRÓNICO EN EPOC SEVERO ","Dosis":"1","DosisUM":"9000","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"3","UFCantTotal":"37","IndRec":"1 INH CADA 24 HRS. FORMULA PARA 3 MESES ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"09198","ConcCant":"22","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"},{"ConOrden":1,"CodPriAct":"09551","ConcCant":"55","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029166015287678","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"16:19:21","CodHabIPS":"080010409201","TipoIDIPS":"NI","NroIDIPS":"900448414","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 47 Nº 84 - 157","TelSedeIPS":"3545674","TipoIDProf":"CC","NumIDProf":"8736587","PNProfS":"LUIS","SNProfS":"FRANCISCO","PAProfS":"ALTAMAR","SAProfS":"OROZCO","RegProfS":"6254/1991","TipoIDPaciente":"CC","NroIDPaciente":"22400400","PNPaciente":"ISABEL","SNPaciente":"MARIA","PAPaciente":"VARELA","SAPaciente":"DE MOVILLA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"I10X","CodDxRel1":"E780","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[AMLODIPINO] 10mg/1U ; [VALSARTAN] 320mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"TOMAR 1 TABLETA CADA 24 HORAS POR 90 DIAS ","Dosis":"1","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"TOMAR 1 TABLETA CADA 24 HORAS POR 90 DIAS ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"05729","ConcCant":"10","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"07016","ConcCant":"320","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029193015288342","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"16:35:48","CodHabIPS":"270010027901","TipoIDIPS":"NI","NroIDIPS":"900132422","CodDANEMunIPS":"27001","DirSedeIPS":"CARRERA 5 N° 24-79 P2","TelSedeIPS":"6721615","TipoIDProf":"CC","NumIDProf":"79671956","PNProfS":"JHONNY","SNProfS":"DE JESUS","PAProfS":"GRACIA","SAProfS":"ARBOLEDA","RegProfS":"1145","TipoIDPaciente":"CC","NroIDPaciente":"26347227","PNPaciente":"MARIA","SNPaciente":"DOLORES","PAPaciente":"MATURANA","SAPaciente":"ARIAS","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J329","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"AMOXICILINA","RznCausaS31":1,"DescRzn31":"paciente con sinusitis cronica rebelde con tratamiento medico pos sin mejoria","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[ACIDO CLAVULANICO] 125mg/1U ; [AMOXICILINA] 875mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"PACIENTE CON SINUSITIS CRÓNICA REBELDE CON TRATAMIENTO MEDICO POS SIN MEJORÍA","Dosis":"1","DosisUM":"0247","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":2,"CanTrat":"15","DurTrat":3,"CantTotalF":"30","UFCantTotal":"66","IndRec":"PACIENTE CON SINUSITIS CRÓNICA REBELDE CON TRATAMIENTO MEDICO POS SIN MEJORÍA","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"03230","ConcCant":"875","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"04948","ConcCant":"125","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029149015288658","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"16:43:49","CodHabIPS":"080010409201","TipoIDIPS":"NI","NroIDIPS":"900448414","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 47 Nº 84 - 157","TelSedeIPS":"3545674","TipoIDProf":"CC","NumIDProf":"85470076","PNProfS":"ERNESTO","SNProfS":"AGUSTIN","PAProfS":"SANTIAGO","SAProfS":"HENRIQUEZ","RegProfS":"2110","TipoIDPaciente":"CC","NroIDPaciente":"32729242","PNPaciente":"LUZ","SNPaciente":"MARY","PAPaciente":"SILVERA","SAPaciente":"CABARCAS","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J459","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"BECLOMETASONA|SALBUTAMOL","RznCausaS31":1,"DescRzn31":"Resultados clínicos no satisfactorios","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[BUDESONIDA] 200µg/1U ; [FORMOTEROL FUMARATO] 6µg/1U","CodFF":"COLFF006","CodVA":"055","JustNoPBS":"ASMA NO CONTROLADA CON USO DE BECLOMETASONA SALBUTAMOL POR LO QUE SE INDICA USO DE LABA ICS ","Dosis":"1","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"180","UFCantTotal":"14","IndRec":"1 CAP INH CADA 12 HRS. DISPOSITIVO AEROLIZER. FORMULA PARA 3 MESES ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"04145","ConcCant":"200","UMedConc":"0137","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"04935","ConcCant":"6","UMedConc":"0137","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029179015288904","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"16:50:24","CodHabIPS":"707080033101","TipoIDIPS":"NI","NroIDIPS":"800191643","CodDANEMunIPS":"70708","DirSedeIPS":"CALLE 20 CARRERA 20 Y 22","TelSedeIPS":"2954800","TipoIDProf":"CC","NumIDProf":"7151155","PNProfS":"JOSE","SNProfS":"IGNACIO","PAProfS":"FERNANDEZ","SAProfS":"MARTINEZ","RegProfS":"0512304","TipoIDPaciente":"CC","NroIDPaciente":"959032","PNPaciente":"PRIMITIVO","SNPaciente":"ARTURO","PAPaciente":"HERRERA","SAPaciente":"VIDAL","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"I490","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"ACIDO ACETIL SALICILICO","RznCausaS31":1,"DescRzn31":"NO CONTROL DE ANTIAGREGACION","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[RIVAROXABAN] 15mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"CON EL USO DE RVAROXABAN 15 MG COMO ANTICUGULANTE SE EVITA LA EMBOLIZACION SECUNDARIA DE FA A NIVEL CEREBRAL CENTRAL EVITAN DOCE ASI UN NUEVO EVENTO CEREBRO VASCULAR O IAM","Dosis":"15","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"TOMAR 1 CADA DIA POR 90 DIAS","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"08428","ConcCant":"15","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029175015289299","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"17:01:01","CodHabIPS":"230010113401","TipoIDIPS":"NI","NroIDIPS":"830504734","CodDANEMunIPS":"23001","DirSedeIPS":"CL 28 #7-34 Edificio Somec","TelSedeIPS":"57477910608","TipoIDProf":"CC","NumIDProf":"79470036","PNProfS":"MANUEL","SNProfS":"","PAProfS":"SALAMANCA","SAProfS":"PALACIOS","RegProfS":"50116-98","TipoIDPaciente":"CC","NroIDPaciente":"35005580","PNPaciente":"KELLYS","SNPaciente":"","PAPaciente":"GULFO","SAPaciente":"VILLADIEGO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M350","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[HIALURONATO DE SODIO] 1,5mg/1ml ; [POLIETILENGLICOL] 4mg/1ml ; [PROPILENGLICOL] 3mg/1ml","CodFF":"COLFF004","CodVA":"047","JustNoPBS":"ES UN TRATAMIENTO DEL OJO SECO PARA EL ALIVIO TEMPORAL DEL ARDOR E IRRITACIÓN DEBIDOS A LA SEQUEDAD OCULAR. SE PUEDE UTILIZAR TANTAS VECES COMO SEA NECESARIO DURANTE EL DÍA PARA TRATAR EL OJO SECO","Dosis":"1","DosisUM":"9000","NoFAdmon":"6","CodFreAdmon":2,"IndEsp":4,"CanTrat":"4","DurTrat":5,"CantTotalF":"4","UFCantTotal":"13","IndRec":"SYSTANE HA","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"50022","ConcCant":"4","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"},{"ConOrden":1,"CodPriAct":"50024","ConcCant":"3","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"},{"ConOrden":1,"CodPriAct":"50096","ConcCant":"1,5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029199015289580","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"17:08:33","CodHabIPS":"230010011601","TipoIDIPS":"NI","NroIDIPS":"812005522","CodDANEMunIPS":"23001","DirSedeIPS":"CARRERA 11 # 27 34","TelSedeIPS":"7917725","TipoIDProf":"CC","NumIDProf":"84103553","PNProfS":"NICOLAS","SNProfS":"FELIPE","PAProfS":"MOSCOTE","SAProfS":"RIVERA","RegProfS":"5861","TipoIDPaciente":"CC","NroIDPaciente":"73243913","PNPaciente":"DONALDO","SNPaciente":"","PAPaciente":"LARA","SAPaciente":"DIAZ","CodAmbAte":30,"RefAmbAte":0,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"Z951","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[{"ConOrden":1,"TipoPrest":2,"CausaS11":1,"CausaS12":null,"CausaS2":null,"CausaS3":0,"CausaS4":1,"ProPBSUtilizado":"902210","CausaS5":null,"ProPBSDescartado":null,"RznCausaS51":0,"DescRzn51":null,"RznCausaS52":0,"DescRzn52":null,"CausaS6":null,"CausaS7":null,"CodCUPS":"906841","CanForm":"01","CadaFreUso":"1","CodFreUso":8,"Cant":null,"CantTotal":"1","CodPerDurTrat":null,"JustNoPBS":"paciente pop de revascularizacion miocardica con cuadro de chqoue. sospecha de infeccion subyacente.","IndRec":"se tomara muestra en sangre venosa.","EstJM":1}],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029139015289816","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"17:15:41","CodHabIPS":"080010476101","TipoIDIPS":"NI","NroIDIPS":"901290414","CodDANEMunIPS":"08001","DirSedeIPS":"calle 71 # 41 - 46 3 piso consultorio 305","TelSedeIPS":"3564016","TipoIDProf":"CC","NumIDProf":"8745354","PNProfS":"OMAR","SNProfS":"ANTONIO","PAProfS":"PEREZ","SAProfS":"ALVAREZ","RegProfS":"3141","TipoIDPaciente":"RC","NroIDPaciente":"1043181150","PNPaciente":"BLEINER","SNPaciente":"ANDRES","PAPaciente":"SIERRA","SAPaciente":"CAMARGO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E46X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[{"ConOrden":1,"TipoPrest":2,"CausaS1":null,"CausaS2":1,"CausaS3":null,"CausaS4":null,"ProNutUtilizado":null,"RznCausaS41":null,"DescRzn41":null,"RznCausaS42":null,"DescRzn42":null,"CausaS5":null,"ProNutDescartado":null,"RznCausaS51":null,"DescRzn51":null,"RznCausaS52":null,"DescRzn52":null,"RznCausaS53":null,"DescRzn53":null,"RznCausaS54":null,"DescRzn54":null,"DXEnfHuer":null,"DXVIH":null,"DXCaPal":null,"DXEnfRCEV":null,"DXDesPro":null,"TippProNut":"1503","DescProdNutr":"150313","CodForma":"5","CodViaAdmon":1,"JustNoPBS":"PACIENTE DE 5 AÑOS DE EDAD, CON BAJO PESO Y TALLA QUE HA VENIDO CON ACTITUD ALIMENTARIA IRREGULAR COMIENDO POCO Y MUY SELECTIVO, PACIENTE QUE NO HA RESPODIDO HA RECOMENDACIONES NUTRICIONALES POR LO QUE SE INDICA SUPLEMENTO NUTRICIONAL HIPERCALORICO PESO ACTUAL 13,5 KG Y TALLA 96 CM ","Dosis":"53,3","DosisUM":"0062","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"12","UFCantTotal":"5","IndRec":"DAR 53,3 GRAMOS 1 VEZ AL DIA POR VIA ORAL DURANTE 3 MESES","NoPrescAso":null,"EstJM":3}],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029160015289882","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"17:17:35","CodHabIPS":"080010409201","TipoIDIPS":"NI","NroIDIPS":"900448414","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 47 Nº 84 - 157","TelSedeIPS":"3545674","TipoIDProf":"CC","NumIDProf":"85470076","PNProfS":"ERNESTO","SNProfS":"AGUSTIN","PAProfS":"SANTIAGO","SAProfS":"HENRIQUEZ","RegProfS":"2110","TipoIDPaciente":"CC","NroIDPaciente":"39006427","PNPaciente":"JULIANA","SNPaciente":"","PAPaciente":"PEDROZO","SAPaciente":"MORENO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J47X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[ACETILCISTEINA] 600mg/1U","CodFF":"C42938","CodVA":"048","JustNoPBS":"anti oxidante Y barredor de radicales libres de oxigeno para el tto de las bronquiectasias ","Dosis":"600","DosisUM":"0168","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"180","UFCantTotal":"78","IndRec":"1 sobre vo cada 12 hrs. formula para 90 dias ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"01535","ConcCant":"600","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029195015289937","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"17:19:14","CodHabIPS":"080010476101","TipoIDIPS":"NI","NroIDIPS":"901290414","CodDANEMunIPS":"08001","DirSedeIPS":"calle 71 # 41 - 46 3 piso consultorio 305","TelSedeIPS":"3564016","TipoIDProf":"CC","NumIDProf":"8745354","PNProfS":"OMAR","SNProfS":"ANTONIO","PAProfS":"PEREZ","SAProfS":"ALVAREZ","RegProfS":"3141","TipoIDPaciente":"RC","NroIDPaciente":"1043181150","PNPaciente":"BLEINER","SNPaciente":"ANDRES","PAPaciente":"SIERRA","SAPaciente":"CAMARGO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M892","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[CALCIO CARBONATO] 750mg/5ml ; [OXIDO DE ZINC] 7,5mg/5ml ; [VITAMINA D3] 0,0025mg/5ml","CodFF":"C42994","CodVA":"048","JustNoPBS":"PACIENTE DE 5 AÑOS DE EDAD, CON BAJO PESO Y TALLA QUE HA VENIDO CON ACTITUD ALIMENTARIA IRREGULAR COMIENDO POCO Y MUY SELECTIVO, PACIENTE QUE NO HA RESPODIDO HA RECOMENDACIONES NUTRICIONALES POR LO QUE SE INDICA POLIVITAMINCO PESO ACTUAL 13,5 KG Y TALLA 96 CM PARA EVITAR DETERIORO EN SU CRECIMIENTO OSEO ","Dosis":"5","DosisUM":"0176","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"3","UFCantTotal":"13","IndRec":"DAR 5 CC 1 VEZ AL DIA POR VIA ORAL DURANTE 3 MESES","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"01497","ConcCant":"0,0025","UMedConc":"0168","CantCont":"5","UMedCantCont":"0176"},{"ConOrden":1,"CodPriAct":"50018","ConcCant":"750","UMedConc":"0168","CantCont":"5","UMedCantCont":"0176"},{"ConOrden":1,"CodPriAct":"50095","ConcCant":"7,5","UMedConc":"0168","CantCont":"5","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029123015290371","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"17:32:29","CodHabIPS":"200010023201","TipoIDIPS":"NI","NroIDIPS":"77028533","CodDANEMunIPS":"20001","DirSedeIPS":"calle 16 b 12 - 24","TelSedeIPS":"5804242","TipoIDProf":"CC","NumIDProf":"77028533","PNProfS":"WAKIS","SNProfS":"","PAProfS":"MAYORCA","SAProfS":"CASTILLA","RegProfS":"","TipoIDPaciente":"RC","NroIDPaciente":"1137874582","PNPaciente":"DYLAN","SNPaciente":"DAVID","PAPaciente":"HOYOS","SAPaciente":"VALENCIA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"L303","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[ACIDO FUSIDICO] 2g/100g","CodFF":"C28944","CodVA":"061","JustNoPBS":"PACIENTE QUIEN REFIERE LA MAMA PERSISTE CON UN BROTE EN LOS MIEMBROS INFERIORES EL CUAL SE LE INDICA TRATAMIENTO CON ACIDO FUSIDICO PARA CONTROLAR LOS SINTOMAS ","Dosis":"1","DosisUM":"9000","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"30","DurTrat":3,"CantTotalF":"3","UFCantTotal":"73","IndRec":"APLICAR UNA DOSIS CADA 24 HORAS PARA CONTROLAR LOS SINTOMAS ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"01431","ConcCant":"2","UMedConc":"0062","CantCont":"100","UMedCantCont":"0062"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029195015290543","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"17:38:11","CodHabIPS":"080010409201","TipoIDIPS":"NI","NroIDIPS":"900448414","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 47 Nº 84 - 157","TelSedeIPS":"3545674","TipoIDProf":"CC","NumIDProf":"85470076","PNProfS":"ERNESTO","SNProfS":"AGUSTIN","PAProfS":"SANTIAGO","SAProfS":"HENRIQUEZ","RegProfS":"2110","TipoIDPaciente":"CC","NroIDPaciente":"22341280","PNPaciente":"EUCARIS","SNPaciente":"CENITH","PAPaciente":"SARMIENTO","SAPaciente":"DE FONTALVO","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J449","CodDxRel1":"J47X","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"SALBUTAMOL|BROMURO DE IPRATROPIO","RznCausaS31":1,"DescRzn31":"Resultados clínicos no satisfactorios","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[GLICOPIRRONIO] 50µg/1Dosis","CodFF":"COLFF006","CodVA":"055","JustNoPBS":"OBSTRUCCIÓN SEVERA CON ESCASA MEJORÍA DE SÍNTOMAS CON TTO CONVENCIONAL ","Dosis":"50","DosisUM":"0137","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"14","IndRec":"1 CAP INH CADA 24 HRS. DISPOSITIVO BREEZHALER. FORMULA PARA 3 MESES ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"01038","ConcCant":"50","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[ACETILCISTEINA] 600mg/1U","CodFF":"C42938","CodVA":"048","JustNoPBS":"ANTI OXIDANTE Y BARREDOR DE RADICALES LIBRES PARA EVITAR EXACERBACIONES ","Dosis":"600","DosisUM":"0168","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"180","UFCantTotal":"78","IndRec":"1 SOBRE VO CADA 12 HRS ","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"01535","ConcCant":"600","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029168015291350","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"18:15:04","CodHabIPS":"200010090101","TipoIDIPS":"NI","NroIDIPS":"900016598","CodDANEMunIPS":"20001","DirSedeIPS":"CARRERA 16 N°16A-42","TelSedeIPS":"5898632","TipoIDProf":"CC","NumIDProf":"77191041","PNProfS":"HEBER","SNProfS":"JOSE","PAProfS":"GUERRA","SAProfS":"CABANA","RegProfS":"77191041","TipoIDPaciente":"CC","NroIDPaciente":"77010081","PNPaciente":"RAFAEL","SNPaciente":"ENRIQUE","PAPaciente":"PEÑARANDA","SAPaciente":"MEJIA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E785","CodDxRel1":"I255","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"ATORVASTATINA","RznCausaS31":1,"DescRzn31":"FALLA TERAPEUTICA","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[EVOLOCUMAB] 140mg/1ml","CodFF":"COLFF004","CodVA":"058","JustNoPBS":"PACIENTE MASCULINO DE 59 AÑOS DE EDAD CON ANTECEDENTES DE ENFERMEDAD CORONARIA (IAM A LOS 53 AÑOS DE EDAD), REVASCULARIZACION MIOCARDICA HACE 6 AÑOS POR CRM Y POSTERIORMENTE CON IMPLANTE DE STENT EN ARTERIA CORONARIA DERECHA CON EXITO QUEDANDO FLUJO TIMI 3, IMPLANTADO HACE MAS DE 24 MESES, AHORA EN ESTUDIO POR MASA EN REGION DE PANCREAS , NO CONTROL DE CO,LESTEROL Y TRIGLICERIDOS CON ATORVASTAINA SE INDICA EVOLOCUMAB 140 MG CADA 15 DIAS","Dosis":"140","DosisUM":"0168","NoFAdmon":"15","CodFreAdmon":3,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"6","UFCantTotal":"74","IndRec":"EVOLOCUMAD POR CONTINUIDAD DEL TRATAMIENTO","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"09643","ConcCant":"140","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029144015291469","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"18:19:51","CodHabIPS":"080010054401","TipoIDIPS":"NI","NroIDIPS":"800194798","CodDANEMunIPS":"08001","DirSedeIPS":"CARRERA 49 C NO 82-70","TelSedeIPS":null,"TipoIDProf":"CC","NumIDProf":"8498411","PNProfS":"EMILIO","SNProfS":"DE JESUS","PAProfS":"BERMUDEZ","SAProfS":"MERCADO","RegProfS":"08-2079-07","TipoIDPaciente":"CC","NroIDPaciente":"73231977","PNPaciente":"JUAN","SNPaciente":"LUIS","PAPaciente":"HERRERA","SAPaciente":"PEÑALOZA","CodAmbAte":22,"RefAmbAte":0,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"I10X","CodDxRel1":"I679","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[LABETALOL CLORHIDRATO] 5mg/1ml","CodFF":"COLFF004","CodVA":"042","JustNoPBS":"cRIsIS HIPERTENSIVA TIPO EMERGENCIA CON AFECTACION A ORGANO BLANCO CEREBRO CON ENFERMEDAD CEREBROVASCUALR\nISQUEMICA DE POSIBLE ORIGEN CARDIOEMBOLICO SIN DESCARTAR ATEROTROMBOTICO, ORDENO MANEJO ANTI ISQUEMICO, REALIZACION DE\nTAC DE CRANEO SIMPLE CONTROL EN 24 HORAS , ECO TT, ECODOPPLER CAROTIDEO","Dosis":"20","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":1,"IndEsp":2,"CanTrat":"1","DurTrat":1,"CantTotalF":"4","UFCantTotal":"01","IndRec":"crisis hipertensiva tipo emergencia con organo blanco cerebro.","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"03941","ConcCant":"5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029172015291514","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"18:22:13","CodHabIPS":"200010090101","TipoIDIPS":"NI","NroIDIPS":"900016598","CodDANEMunIPS":"20001","DirSedeIPS":"CARRERA 16 N°16A-42","TelSedeIPS":"5898632","TipoIDProf":"CC","NumIDProf":"77191041","PNProfS":"HEBER","SNProfS":"JOSE","PAProfS":"GUERRA","SAProfS":"CABANA","RegProfS":"77191041","TipoIDPaciente":"CC","NroIDPaciente":"77010081","PNPaciente":"RAFAEL","SNPaciente":"ENRIQUE","PAPaciente":"PEÑARANDA","SAPaciente":"MEJIA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E785","CodDxRel1":"I255","CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"ATORVASTATINA","RznCausaS31":1,"DescRzn31":"falla terapeutica","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[EZETIMIBA] 10mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"PACIENTE MASCULINO DE 59 AÑOS DE EDAD CON ANTECEDENTES DE ENFERMEDAD CORONARIA (IAM A LOS 53 AÑOS DE EDAD), REVASCULARIZACION MIOCARDICA HACE 6 AÑOS POR CRM Y POSTERIORMENTE CON IMPLANTE DE STENT EN ARTERIA CORONARIA DERECHA CON EXITO QUEDANDO FLUJO TIMI 3, IMPLANTADO HACE MAS DE 24 MESES, AHORA EN ESTUDIO POR MASA EN REGION DE PANCREAS , NO CONTROL DE COLESTEROL Y TRIGLICERIDOS CON Atorvastatina, por los factores de riesgos del paciente se indica ezetimiba 10 mg al dia por 3 meses","Dosis":"10","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"66","IndRec":"ezetibime por continuidad del tratamiento","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"08010","ConcCant":"10","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029180015291944","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"18:49:31","CodHabIPS":"134300049201","TipoIDIPS":"NI","NroIDIPS":"900196347","CodDANEMunIPS":"13430","DirSedeIPS":"Avenida Colombia Nº13-146","TelSedeIPS":"3017573836-3017446503","TipoIDProf":"CC","NumIDProf":"42401575","PNProfS":"SAMIRA","SNProfS":"","PAProfS":"VIRGUEZ","SAProfS":"RIOS","RegProfS":"42401575","TipoIDPaciente":"RC","NroIDPaciente":"1051680602","PNPaciente":"ISAAC","SNPaciente":"DE JESUS","PAPaciente":"HERNANDEZ","SAPaciente":"ARIAS","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"P073","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[PALIVIZUMAB] 100mg/1ml","CodFF":"COLFF004","CodVA":"030","JustNoPBS":"PACIENTE CON ANTECDENTES DE PREMATUREZ EXTREMA DE 31 SEMANAS CON ANTECDENTES DE VENTILACION MECANICA CON FORAMEN OVAL PERMEABLE QUIEN TIEN ALTO RIESGO DE INFECCIONES RESPIRATORIA POR VSR POR LO CUAL SE INDICA MANEJO CON PALIVIZUMAB","Dosis":"1","DosisUM":"0176","NoFAdmon":"2","CodFreAdmon":5,"IndEsp":10,"CanTrat":"10","DurTrat":5,"CantTotalF":"5","UFCantTotal":"01","IndRec":"APLICAR 1ML CADA 2 MESES X 5 DOSIS","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07753","ConcCant":"100","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029142015292018","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"18:55:16","CodHabIPS":"087580053305","TipoIDIPS":"NI","NroIDIPS":"800033723","CodDANEMunIPS":"08758","DirSedeIPS":"CALLE 21 # 19-29 piso 1","TelSedeIPS":"3858037-3877970","TipoIDProf":"CC","NumIDProf":"1129526150","PNProfS":"JORGE","SNProfS":"LUIS","PAProfS":"DUARTE","SAProfS":"PEÑALOZA","RegProfS":"1599-2011","TipoIDPaciente":"CC","NroIDPaciente":"32626088","PNPaciente":"ELINA","SNPaciente":"GRACIELA","PAPaciente":"FONSECA","SAPaciente":"MARIMON","CodAmbAte":11,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E119","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"METFORMINA","RznCausaS31":1,"DescRzn31":"MALA RESPUESTA CLINICA","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[METFORMINA CLORHIDRATO] 850mg/1U ; [SITAGLIPTINA] 50mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"PACIENTE CON DIABETES MELLITUS DE DIFÍCIL MANEJO QUE NO MEJORO CON DOSIS ALTA DE METFORMINA Y GLIBLENCLAMIDA, LA CUAL SE CONSIGUIÓ CONTROL METABÓLICO CON METFORMINASITAPGLIPTINA","Dosis":"1","DosisUM":"9000","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"90","DurTrat":3,"CantTotalF":"90","UFCantTotal":"66","IndRec":"TOMAR UNA TABLETA DIARIA ANTES DEL DESAYUNO VIA ORAL, TRATAMIENTO POR TRES MESES","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00965","ConcCant":"850","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"08692","ConcCant":"50","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029121015292030","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"18:56:30","CodHabIPS":"134300028101","TipoIDIPS":"NI","NroIDIPS":"890480363","CodDANEMunIPS":"13430","DirSedeIPS":"calle 14a numero 3-28","TelSedeIPS":"6876043","TipoIDProf":"CC","NumIDProf":"73578467","PNProfS":"GABRIEL","SNProfS":"ANTONIO","PAProfS":"NAVARRO","SAProfS":"BARRIOS","RegProfS":"130403","TipoIDPaciente":"CC","NroIDPaciente":"45770205","PNPaciente":"ANA","SNPaciente":"LUZ","PAPaciente":"ANAYA","SAPaciente":"SERPA","CodAmbAte":22,"RefAmbAte":0,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J46X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[MONTELUKAST] 10mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"PACIENTE BAJO DIAGNOSTICO DE CRISIS ASMATICA, QUIEN A LA REVISION SE ENCUENTRA HEMODINAMICAMENTE ESTABLE, SIN EVIDENCIA DE SIGNOS DE BRONCOESPASMO, CON SATURACION OPTIMA, HEMODINAMICAMENTE ESTABLE. CONTINUA EN MANEJO CON MONTELUKAST Y SERETIDE NO DISPONIBLE EN FARMACIA, SE INICIA BETA-AGONISTAS DE ACCIÓN PROLONGADA. POR EPIGASTRALGIA SE REAJUSTA PROTECTOR GASTRICO.","Dosis":"10","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"30","DurTrat":3,"CantTotalF":"30","UFCantTotal":"66","IndRec":"TAB 10 MG CADA NOCHE","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07388","ConcCant":"10","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]},{"ConOrden":2,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":0,"MedPBSUtilizado":null,"RznCausaS31":0,"DescRzn31":null,"RznCausaS32":0,"DescRzn32":null,"CausaS4":1,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":1,"DescRzn43":"N.A.","RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[FLUTICASONA] 50µg/1Dosis ; [SALMETEROL] 25µg/1Dosis","CodFF":"C42887","CodVA":"055","JustNoPBS":"PACIENTE BAJO DIAGNOSTICO DE CRISIS ASMATICA, QUIEN A LA REVISION SE ENCUENTRA HEMODINAMICAMENTE ESTABLE, SIN EVIDENCIA DE SIGNOS DE BRONCOESPASMO, CON SATURACION OPTIMA, HEMODINAMICAMENTE ESTABLE. CONTINUA EN MANEJO CON MONTELUKAST Y SERETIDE NO DISPONIBLE EN FARMACIA, SE INICIA BETA-AGONISTAS DE ACCIÓN PROLONGADA. POR EPIGASTRALGIA SE REAJUSTA PROTECTOR GASTRICO.","Dosis":"2","DosisUM":"9000","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"30","DurTrat":3,"CantTotalF":"3","UFCantTotal":"37","IndRec":"2 PUFF CADA 12 HORAS ","EstJM":1,"PrincipiosActivos":[{"ConOrden":2,"CodPriAct":"05578","ConcCant":"25","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"},{"ConOrden":2,"CodPriAct":"05636","ConcCant":"50","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029170015292283","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"19:31:02","CodHabIPS":"130010210201","TipoIDIPS":"NI","NroIDIPS":"900269029","CodDANEMunIPS":"13001","DirSedeIPS":"CLL 1a EL EDEN DG 32 No. 82-40","TelSedeIPS":"6810300","TipoIDProf":"CC","NumIDProf":"73194785","PNProfS":"ALFONSO","SNProfS":"ISAAC","PAProfS":"PACHECO","SAProfS":"HERNANDEZ","RegProfS":"23244","TipoIDPaciente":"CC","NroIDPaciente":"9078918","PNPaciente":"JESUS","SNPaciente":"OCTAVIO","PAPaciente":"HENAO","SAPaciente":"GIL","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M511","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":1,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"DICLOFENACO","RznCausaS31":1,"DescRzn31":"sin mejoria","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[ETOFENAMATO] 500mg/1ml","CodFF":"COLFF004","CodVA":"030","JustNoPBS":"requiere de un antiinflamatorio para control del dolor por inflamacion del piramidal y del nervio peronero . con mejor efecto sobre prostaglandinas antiinflamatorias","Dosis":"500","DosisUM":"0168","NoFAdmon":"48","CodFreAdmon":2,"IndEsp":10,"CanTrat":"30","DurTrat":3,"CantTotalF":"4","UFCantTotal":"01","IndRec":"1 ampolla intramuscular cada 2 dias por 4 dosis","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"03359","ConcCant":"500","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029153015292538","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"20:25:13","CodHabIPS":"134300028101","TipoIDIPS":"NI","NroIDIPS":"890480363","CodDANEMunIPS":"13430","DirSedeIPS":"calle 14a numero 3-28","TelSedeIPS":"6876043","TipoIDProf":"CC","NumIDProf":"73578467","PNProfS":"GABRIEL","SNProfS":"ANTONIO","PAProfS":"NAVARRO","SAProfS":"BARRIOS","RegProfS":"130403","TipoIDPaciente":"CC","NroIDPaciente":"12580853","PNPaciente":"ELIGIO","SNPaciente":"MIGUEL","PAPaciente":"MENCO","SAPaciente":"AGAMEZ","CodAmbAte":22,"RefAmbAte":0,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"G919","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[{"ConOrden":1,"TipoPrest":1,"CausaS1":0,"CausaS2":1,"CausaS3":null,"CausaS4":1,"DescCausaS4":"paciente con evento cerebrovascular hemorragico, con secuelas neurológicas completamente dependiente para sus necesidades básicas barthell 0","CausaS5":null,"CodSerComp":"139","DescSerComp":"pañales talla m","CanForm":"1","CadaFreUso":"4","CodFreUso":2,"Cant":"30","CantTotal":"180","CodPerDurTrat":3,"TipoTrans":null,"ReqAcom":null,"TipoIDAcomAlb":null,"NroIDAcomAlb":null,"ParentAcomAlb":null,"NombAlb":null,"CodMunOriAlb":null,"CodMunDesAlb":null,"JustNoPBS":"paciente con enfermedad cerebro vascular hemorragica, secuelar, barthell 0","IndRec":"cambio de pañal cada 4 horas","EstJM":2}]},{"prescripcion":{"NoPrescripcion":"20191029182015293062","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"23:28:04","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"91506875","PNProfS":"JOSE","SNProfS":"DE JESUS","PAProfS":"ALONSO","SAProfS":"GONZALEZ","RegProfS":"5156","TipoIDPaciente":"CC","NroIDPaciente":"26765524","PNPaciente":"LUZ","SNPaciente":"ELENA","PAPaciente":"VERGARA","SAPaciente":"RAMOS","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"I10X","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"LOSARTAN","RznCausaS31":1,"DescRzn31":"SIN CONTROL EN MONOTERAPIA","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[TELMISARTAN] 80mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"MEJOR BIODISPONIBILIDAD CON MAYOR REDUCCION DE CIFRAS TENSIONALES","Dosis":"80","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"6","DurTrat":5,"CantTotalF":"180","UFCantTotal":"66","IndRec":"1 TABLETA AL DÍA","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07144","ConcCant":"80","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029110015293069","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"23:33:59","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"91506875","PNProfS":"JOSE","SNProfS":"DE JESUS","PAProfS":"ALONSO","SAProfS":"GONZALEZ","RegProfS":"5156","TipoIDPaciente":"CC","NroIDPaciente":"23035231","PNPaciente":"MARLENYS","SNPaciente":"MARIA","PAPaciente":"CONTRERAS","SAPaciente":"DE OBREGON","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M329","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"NAPROXENO","RznCausaS31":1,"DescRzn31":"SIN CONTROL DEL DOLOR ARTICULAR ","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[CONDROITINA SULFATO SODICA] 1200mg/1U ; [GLUCOSAMINA SULFATO] 1500mg/1U","CodFF":"COLFF003","CodVA":"048","JustNoPBS":"MEJOR RECONSTITUCION DEL CARTILAGO ARTICULAR","Dosis":"1200","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"78","IndRec":"1 SOBRE DILUIDO EN AGUA AL DIA ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"03120","ConcCant":"1500","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"50057","ConcCant":"1200","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029190015293073","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"23:37:56","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"91506875","PNProfS":"JOSE","SNProfS":"DE JESUS","PAProfS":"ALONSO","SAProfS":"GONZALEZ","RegProfS":"5156","TipoIDPaciente":"CC","NroIDPaciente":"36585497","PNPaciente":"VIRGELINA","SNPaciente":"","PAPaciente":"TORRES","SAPaciente":"DE LEON","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"B181","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"ACICLOVIR","RznCausaS31":1,"DescRzn31":"SIN ACTIVIDAD NI INDICACION EN INFECCION POR HEPATITIS B","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[ENTECAVIR] 0,5mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"MEJOR ACTIVIDAD CONTRA VIRUS DE HEPATITIS B","Dosis":"0,5","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"66","IndRec":"1 TABLETA AL DIA ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"07904","ConcCant":"0,5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029115015293086","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"23:45:23","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"91506875","PNProfS":"JOSE","SNProfS":"DE JESUS","PAProfS":"ALONSO","SAProfS":"GONZALEZ","RegProfS":"5156","TipoIDPaciente":"CC","NroIDPaciente":"26916386","PNPaciente":"MARIA","SNPaciente":"FARIDES","PAPaciente":"HERNANDEZ","SAPaciente":"RODRIGUEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"M179","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"NAPROXENO","RznCausaS31":1,"DescRzn31":"SIN CONTROL DEL DOLOR ARTICULAR","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[DIACEREINA] 50mg/1U","CodFF":"COLFF006","CodVA":"048","JustNoPBS":"MAYOR CONTROL DEL DOLOR ARTICULAR ","Dosis":"50","DosisUM":"0168","NoFAdmon":"24","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"90","UFCantTotal":"14","IndRec":"1 CAPSULA AL DIA ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"05371","ConcCant":"50","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029124015293090","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"23:49:35","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"91506875","PNProfS":"JOSE","SNProfS":"DE JESUS","PAProfS":"ALONSO","SAProfS":"GONZALEZ","RegProfS":"5156","TipoIDPaciente":"CC","NroIDPaciente":"26917773","PNPaciente":"LUZ","SNPaciente":"MARINA","PAPaciente":"TORRES","SAPaciente":"SANCHEZ","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E106","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"INSULINA GLARGINA","RznCausaS31":1,"DescRzn31":"RIESGO DE HIPOGLUCEMIA CON MÍNIMA REDUCCION DE A1C","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[LIRAGLUTIDA] 6mg/1ml","CodFF":"COLFF004","CodVA":"058","JustNoPBS":"MAYOR REDUCCION DE A1C CON MÍNIMO RIESGO DE HIPOGLICEMIA","Dosis":"1,8","DosisUM":"0168","NoFAdmon":"1","CodFreAdmon":3,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"9","UFCantTotal":"79","IndRec":"0,6 MG VSC AL DIA DURANTE LA PRIMERA SEMANA. 1,2 MG VSC AL DIA DURANTE LA SEGUNDA SEMANA. LUEGO 1,8 MG VSC AL DIA ","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"08208","ConcCant":"6","UMedConc":"0168","CantCont":"1","UMedCantCont":"0176"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029195015293099","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"23:53:15","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"91506875","PNProfS":"JOSE","SNProfS":"DE JESUS","PAProfS":"ALONSO","SAProfS":"GONZALEZ","RegProfS":"5156","TipoIDPaciente":"CC","NroIDPaciente":"4984212","PNPaciente":"JOSE","SNPaciente":"TRINIDAD","PAPaciente":"AFANADOR","SAPaciente":"GUEVARA","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E106","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"METFORMINA","RznCausaS31":1,"DescRzn31":"MENOR CONTROL DE A1C EN MONOTERAPIA","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[EMPAGLIFLOZINA] 12,5mg/1U ; [METFORMINA CLORHIDRATO] 1000mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"MEJOR CONTROL GLUCEMICO","Dosis":"12,5","DosisUM":"0168","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"180","UFCantTotal":"66","IndRec":"1 TABLETA 7 AM Y 7 PM","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00965","ConcCant":"1000","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"09398","ConcCant":"12,5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029116015293105","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"23:56:38","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"91506875","PNProfS":"JOSE","SNProfS":"DE JESUS","PAProfS":"ALONSO","SAProfS":"GONZALEZ","RegProfS":"5156","TipoIDPaciente":"CC","NroIDPaciente":"49625004","PNPaciente":"MANUELA","SNPaciente":"","PAPaciente":"CARVAJAL","SAPaciente":"CONTRERAS","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"J449","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"SALBUTAMOL","RznCausaS31":0,"DescRzn31":null,"RznCausaS32":1,"DescRzn32":"MAYOR TAQUICARDIA, MENOR CONTROL DE SINTOMAS","CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[FLUTICASONA PROPIONATO] 250µg/1Dosis ; [SALMETEROL] 50µg/1Dosis","CodFF":"COLFF002","CodVA":"055","JustNoPBS":"MEJOR CONTROL DE SINTOMAS ","Dosis":"50","DosisUM":"0137","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"3","DurTrat":5,"CantTotalF":"180","UFCantTotal":"14","IndRec":"INHALAR 1 CAPSULA DE POLVO SECO A TRAVES DE DISPOSITIVO CADA 12 HORAS SERECOR","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"05578","ConcCant":"50","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"},{"ConOrden":1,"CodPriAct":"05636","ConcCant":"250","UMedConc":"0137","CantCont":"1","UMedCantCont":"9000"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]},{"prescripcion":{"NoPrescripcion":"20191029147015293111","FPrescripcion":"2019-10-29T00:00:00","HPrescripcion":"23:59:35","CodHabIPS":"200010205401","TipoIDIPS":"NI","NroIDIPS":"901058547","CodDANEMunIPS":"20001","DirSedeIPS":"cra 16 n 13 c 39","TelSedeIPS":"3173683259 - 3137437203","TipoIDProf":"CC","NumIDProf":"91506875","PNProfS":"JOSE","SNProfS":"DE JESUS","PAProfS":"ALONSO","SAProfS":"GONZALEZ","RegProfS":"5156","TipoIDPaciente":"CC","NroIDPaciente":"63459175","PNPaciente":"YOLADIS","SNPaciente":"","PAPaciente":"FLOREZ","SAPaciente":"PALLARES","CodAmbAte":12,"RefAmbAte":null,"EnfHuerfana":0,"CodEnfHuerfana":null,"EnfHuerfanaDX":null,"CodDxPpal":"E106","CodDxRel1":null,"CodDxRel2":null,"SopNutricional":null,"CodEPS":"ESS076","TipoIDMadrePaciente":null,"NroIDMadrePaciente":null,"TipoTransc":null,"TipoIDDonanteVivo":null,"NroIDDonanteVivo":null,"EstPres":4},"medicamentos":[{"ConOrden":1,"TipoMed":1,"TipoPrest":2,"CausaS1":0,"CausaS2":0,"CausaS3":1,"MedPBSUtilizado":"METFORMINA","RznCausaS31":1,"DescRzn31":"MENOR CONTROL GLUCEMICO EN MONOTERAPIA","RznCausaS32":0,"DescRzn32":null,"CausaS4":null,"MedPBSDescartado":null,"RznCausaS41":0,"DescRzn41":null,"RznCausaS42":0,"DescRzn42":null,"RznCausaS43":0,"DescRzn43":null,"RznCausaS44":0,"DescRzn44":null,"CausaS5":1,"RznCausaS5":null,"CausaS6":null,"DescMedPrinAct":"[LINAGLIPTINA] 2,5mg/1U ; [METFORMINA CLORHIDRATO] 1000mg/1U","CodFF":"COLFF001","CodVA":"048","JustNoPBS":"MEJOR CONTROL GLUCEMICO","Dosis":"2,5","DosisUM":"0168","NoFAdmon":"12","CodFreAdmon":2,"IndEsp":10,"CanTrat":"6","DurTrat":5,"CantTotalF":"360","UFCantTotal":"66","IndRec":"1 TABLETA 7 AM Y 7 PM","EstJM":1,"PrincipiosActivos":[{"ConOrden":1,"CodPriAct":"00965","ConcCant":"1000","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"},{"ConOrden":1,"CodPriAct":"09039","ConcCant":"2,5","UMedConc":"0168","CantCont":"1","UMedCantCont":"0247"}],"IndicacionesUNIRS":[]}],"procedimientos":[],"dispositivos":[],"productosnutricionales":[],"serviciosComplementarios":[]}]';*/
      if ($json == "" || $json=='{"Message":"Error."}') {

        /*
    $peri_error= $peri_error."20".$periodo_conteo."(Error al consumir la API)<br>";
    $peri_error_conteo=$peri_error_conteo+1;
    $sql="INSERT INTO log_errores(serv_id, tire_id, logErr_periodo, log_Err_nombre, logErr_descripcion) 
    VALUES (".$servicio_id.",".$tipo_id.",'20".$periodo_conteo."', 'WSPRESCRIPCION: Error al consumir la API','No se cargó ".$serv_nombre." ".$tipo_get." 20".$periodo_conteo."')";
    mysqli_query($conn, $sql);
    */

        /////////////////////////////////////////////////////////////////////////////////////insertar en el log de errores

      } else {
        /*Nota 1:Al remplazar los valores se debe hacer con comillas dobles, 
    ya que con commillas simples la funcion str_replace no encuentra los datos buscados*/
        $json = str_replace("\n", "", $json); //quitar \n
        $json = str_replace("\t", "", $json); //quitar \t
        $json = str_replace("\\\"", "\\\\\"", $json); //Colocrale un \ adicional a los cometarios que tengan \"


        /**************************************************************************************************/
        /************(Inicio)Bloque para separar el json en diferentes prescripciones que se retornan******/
        /**************************************************************************************************/
        /////////////////////////////////////////General////////////////////////////////////////////////////
        /*
$cadena = "abqcjhgygabcsabzc";
echo "<br> Cadena: ".$cadena;
$subCadenaBuscadaInicial   = 'cj';
echo "<br> sub Cadena Buscada Inicial: ".$subCadenaBuscadaInicial;
$subCadenaBuscadaFinal   = 'sabz';
echo "<br> sub Cadena Buscada Final: ".$subCadenaBuscadaFinal;
*/
        $subCadenaBuscadaInicial   = '{"prescripcion"';
        $cadena = $json;
        $posInicial = strpos($cadena, $subCadenaBuscadaInicial);
        $count_report = 0;

        unset($array); //Vaciar el vector
        // Notese el uso de ===. Puesto que == simple no funcionará como se espera
        while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

          /***************************Se separan las 5 subcadenas de la prescripcion (Inicio)************************************/
          ////////(Prescripcion,Medicamentos, procedimientos, dispositivos, productosnutricionales,serviciosComplementarios)//////
          /**********************************************************************************************************************/
          ///////////////////////////////////////////Prescripcion/////////////////////////////////////////
          $subCadenaBuscadaInicial   = '{"prescripcion"';
          // echo "<br> sub Cadena Buscada Inicial: " . $subCadenaBuscadaInicial;
          $subCadenaBuscadaFinal   = ',{"prescripcion"';
          //echo "<br> sub Cadena Buscada Final: " . $subCadenaBuscadaFinal;
          $cadena = $json;

          //$posInicial = strpos('cadena completa', 'Subcadena buscada','se especifica si se buscara la primera o la segunda coinsidencia (Este ultimo parametro es mejor no usarlo porque no funciona bien)');
          //$posInicial = strpos($cadena, $subCadenaBuscadaInicial,0);
          $posInicial = strpos($cadena, $subCadenaBuscadaInicial);
          //echo "<br> pos Inicial: " . $posInicial;
          $posFinal = strpos($cadena, $subCadenaBuscadaFinal);
          //echo "<br> pos Final: " . $posFinal;
          if ($posFinal == "") {
            $posFinal = strlen($cadena) - 2; //Sera igual a la última posición de la cadena
            //echo "<br> pos Final no encontrado: " . $posFinal;
          }



          //$subCadenaFinal = substr($cadena, posicion Inicial,cantidad de caracteres despues de la pocicion inicial);
          $subCadenaFinal = substr($cadena, $posInicial, $posFinal - $posInicial + 1);
          // echo "<br> Sub Cadena: " . $subCadenaFinal;
          if ($subCadenaFinal != '[' && $subCadenaFinal != '') {

            $array[$count_report] = $subCadenaFinal;
            $count_report++;
          }


          ////////////////////////////////////////////medicamentos////////////////////////////////////////


          $json = str_replace($subCadenaFinal, "", $json);

          // echo "<br><h1 style='color:#FF0000'>-----------------------------------------------------------------------------------------------------------------------------------</h1>";
        }
        /**************************************************************************************************/
        /************(Inicio)Bloque para separar el json en diferentes prescripciones que se retornan******/
        /**************************************************************************************************/
        /*****Notas:
        //Nota 1: Las cadenas de búsqueda deben ser únicas en la cadena en donde se están buscando para que no hayan inconsistencias Ej: la cadena de búsqueda que esta guardada en las variables $cad_pres_busc_ini y  $cad_pres_busc_fin solo debe existir una sola vez en la cadena $cadena_presc.
         **************************************************************************************************/

        //saco el numero de elementos
        $longitud = count($array);

        //Recorro todos los elementos
        for ($i = 0; $i < $longitud; $i++) {
          //////////////////////////////////////////////////////////////////////////////////////////////////////////Prescripcion
          //Obtener cadena general de prescripcion
          $cad_pres_busc_ini = '{"prescripcion"';
          $cad_pres_busc_fin = ',"medicamentos"';
          $cadena_presc = $array[$i];
          $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini);
          $posPresFinal = strpos($cadena_presc, $cad_pres_busc_fin);
          $subCadenaPresGene = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
          echo "<br><br>-------------------------------------------------------------------------prescripcion";

          if ($cadena_presc != '') {
            //echo "<br> cadena_presc: " . $cadena_presc;
            //NoPrescripcion
            $NoPrescripcion_busc_ini = '"NoPrescripcion":';
            $NoPrescripcion_busc_fin = ',"FPrescripcion"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NoPrescripcion_busc_ini) + strlen($NoPrescripcion_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NoPrescripcion_busc_fin);
            $noPrescripcion = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> NoPrescripcion: " . $noPrescripcion;
            //FPrescripcion
            $FPrescripcion_busc_ini = '"FPrescripcion":';
            $FPrescripcion_busc_fin = ',"HPrescripcion"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $FPrescripcion_busc_ini) + strlen($FPrescripcion_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $FPrescripcion_busc_fin);
            $FPrescripcion = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> FPrescripcion: " . $FPrescripcion;
            //HPrescripcion
            $HPrescripcion_busc_ini = '"HPrescripcion":';
            $HPrescripcion_busc_fin = ',"CodHabIPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $HPrescripcion_busc_ini) + strlen($HPrescripcion_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $HPrescripcion_busc_fin);
            $HPrescripcion = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> HPrescripcion: " . $HPrescripcion;
            //CodHabIPS
            $CodHabIPS_busc_ini = '"CodHabIPS":';
            $CodHabIPS_busc_fin = ',"TipoIDIPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodHabIPS_busc_ini) + strlen($CodHabIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodHabIPS_busc_fin);
            $CodHabIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> CodHabIPS: " . $CodHabIPS;
            //TipoIDIPS
            $TipoIDIPS_busc_ini = '"TipoIDIPS":';
            $TipoIDIPS_busc_fin = ',"NroIDIPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoIDIPS_busc_ini) + strlen($TipoIDIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoIDIPS_busc_fin);
            $TipoIDIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> TipoIDIPS: " . $TipoIDIPS;
            //NroIDIPS
            $NroIDIPS_busc_ini = '"NroIDIPS":';
            $NroIDIPS_busc_fin = ',"CodDANEMunIPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NroIDIPS_busc_ini) + strlen($NroIDIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NroIDIPS_busc_fin);
            $NroIDIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> NroIDIPS: " . $NroIDIPS;
            //CodDANEMunIPS
            $CodDANEMunIPS_busc_ini = '"CodDANEMunIPS":';
            $CodDANEMunIPS_busc_fin = ',"DirSedeIPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodDANEMunIPS_busc_ini) + strlen($CodDANEMunIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodDANEMunIPS_busc_fin);
            $CodDANEMunIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> CodDANEMunIPS: " . $CodDANEMunIPS;
            //DirSedeIPS
            $DirSedeIPS_busc_ini = '"DirSedeIPS":';
            $DirSedeIPS_busc_fin = ',"TelSedeIPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $DirSedeIPS_busc_ini) + strlen($DirSedeIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $DirSedeIPS_busc_fin);
            $DirSedeIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> DirSedeIPS: " . $DirSedeIPS;
            //TelSedeIPS
            $TelSedeIPS_busc_ini = '"TelSedeIPS":';
            $TelSedeIPS_busc_fin = ',"TipoIDProf"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TelSedeIPS_busc_ini) + strlen($TelSedeIPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TelSedeIPS_busc_fin);
            $TelSedeIPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> TelSedeIPS: " . $TelSedeIPS;
            //TipoIDProf
            $TipoIDProf_busc_ini = '"TipoIDProf":';
            $TipoIDProf_busc_fin = ',"NumIDProf"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoIDProf_busc_ini) + strlen($TipoIDProf_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoIDProf_busc_fin);
            $TipoIDProf = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> TipoIDProf: " . $TipoIDProf;
            //NumIDProf
            $NumIDProf_busc_ini = '"NumIDProf":';
            $NumIDProf_busc_fin = ',"PNProfS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NumIDProf_busc_ini) + strlen($NumIDProf_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NumIDProf_busc_fin);
            $NumIDProf = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> NumIDProf: " . $NumIDProf;
            //PNProfS
            $PNProfS_busc_ini = '"PNProfS":';
            $PNProfS_busc_fin = ',"SNProfS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $PNProfS_busc_ini) + strlen($PNProfS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $PNProfS_busc_fin);
            $PNProfS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> PNProfS: " . $PNProfS;
            //SNProfS
            $SNProfS_busc_ini = '"SNProfS":';
            $SNProfS_busc_fin = ',"PAProfS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $SNProfS_busc_ini) + strlen($SNProfS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $SNProfS_busc_fin);
            $SNProfS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> SNProfS: " . $SNProfS;
            //PAProfS
            $PAProfS_busc_ini = '"PAProfS":';
            $PAProfS_busc_fin = ',"SAProfS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $PAProfS_busc_ini) + strlen($PAProfS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $PAProfS_busc_fin);
            $PAProfS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> PAProfS: " . $PAProfS;
            //SAProfS
            $SAProfS_busc_ini = '"SAProfS":';
            $SAProfS_busc_fin = ',"RegProfS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $SAProfS_busc_ini) + strlen($SAProfS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $SAProfS_busc_fin);
            $SAProfS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> SAProfS: " . $SAProfS;
            //RegProfS
            $RegProfS_busc_ini = '"RegProfS":';
            $RegProfS_busc_fin = ',"TipoIDPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $RegProfS_busc_ini) + strlen($RegProfS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $RegProfS_busc_fin);
            $RegProfS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> RegProfS: " . $RegProfS;
            //TipoIDPaciente
            $TipoIDPaciente_busc_ini = '"TipoIDPaciente":';
            $TipoIDPaciente_busc_fin = ',"NroIDPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoIDPaciente_busc_ini) + strlen($TipoIDPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoIDPaciente_busc_fin);
            $TipoIDPaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> TipoIDPaciente: " . $TipoIDPaciente;
            //NroIDPaciente
            $NroIDPaciente_busc_ini = '"NroIDPaciente":';
            $NroIDPaciente_busc_fin = ',"PNPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NroIDPaciente_busc_ini) + strlen($NroIDPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NroIDPaciente_busc_fin);
            $NroIDPaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> NroIDPaciente: " . $NroIDPaciente;
            //PNPaciente
            $PNPaciente_busc_ini = '"PNPaciente":';
            $PNPaciente_busc_fin = ',"SNPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $PNPaciente_busc_ini) + strlen($PNPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $PNPaciente_busc_fin);
            $PNPaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> PNPaciente: " . $PNPaciente;
            //SNPaciente
            $SNPaciente_busc_ini = '"SNPaciente":';
            $SNPaciente_busc_fin = ',"PAPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $SNPaciente_busc_ini) + strlen($SNPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $SNPaciente_busc_fin);
            $SNPaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> SNPaciente: " . $SNPaciente;
            //PAPaciente
            $PAPaciente_busc_ini = '"PAPaciente":';
            $PAPaciente_busc_fin = ',"SAPaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $PAPaciente_busc_ini) + strlen($PAPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $PAPaciente_busc_fin);
            $PAPaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> PAPaciente: " . $PAPaciente;
            //SAPaciente
            $SAPaciente_busc_ini = '"SAPaciente":';
            $SAPaciente_busc_fin = ',"CodAmbAte"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $SAPaciente_busc_ini) + strlen($SAPaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $SAPaciente_busc_fin);
            $SAPaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> SAPaciente: " . $SAPaciente;
            //CodAmbAte
            $CodAmbAte_busc_ini = '"CodAmbAte":';
            $CodAmbAte_busc_fin = ',"RefAmbAte"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodAmbAte_busc_ini) + strlen($CodAmbAte_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodAmbAte_busc_fin);
            $CodAmbAte = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> CodAmbAte: " . $CodAmbAte;
            //RefAmbAte
            $RefAmbAte_busc_ini = '"RefAmbAte":';
            $RefAmbAte_busc_fin = ',"EnfHuerfana"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $RefAmbAte_busc_ini) + strlen($RefAmbAte_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $RefAmbAte_busc_fin);
            $RefAmbAte = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> RefAmbAte: " . $RefAmbAte;
            //EnfHuerfana
            $EnfHuerfana_busc_ini = '"EnfHuerfana":';
            $EnfHuerfana_busc_fin = ',"CodEnfHuerfana"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $EnfHuerfana_busc_ini) + strlen($EnfHuerfana_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $EnfHuerfana_busc_fin);
            $EnfHuerfana = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> EnfHuerfana: " . $EnfHuerfana;
            //CodEnfHuerfana
            $CodEnfHuerfana_busc_ini = '"CodEnfHuerfana":';
            $CodEnfHuerfana_busc_fin = ',"EnfHuerfanaDX"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodEnfHuerfana_busc_ini) + strlen($CodEnfHuerfana_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodEnfHuerfana_busc_fin);
            $CodEnfHuerfana = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> CodEnfHuerfana: " . $CodEnfHuerfana;
            //EnfHuerfanaDX
            $EnfHuerfanaDX_busc_ini = '"EnfHuerfanaDX":';
            $EnfHuerfanaDX_busc_fin = ',"CodDxPpal"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $EnfHuerfanaDX_busc_ini) + strlen($EnfHuerfanaDX_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $EnfHuerfanaDX_busc_fin);
            $EnfHuerfanaDX = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> EnfHuerfanaDX: " . $EnfHuerfanaDX;
            //CodDxPpal
            $CodDxPpal_busc_ini = '"CodDxPpal":';
            $CodDxPpal_busc_fin = ',"CodDxRel1"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodDxPpal_busc_ini) + strlen($CodDxPpal_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodDxPpal_busc_fin);
            $CodDxPpal = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> CodDxPpal: " . $CodDxPpal;
            //CodDxRel1
            $CodDxRel1_busc_ini = '"CodDxRel1":';
            $CodDxRel1_busc_fin = ',"CodDxRel2"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodDxRel1_busc_ini) + strlen($CodDxRel1_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodDxRel1_busc_fin);
            $CodDxRel1 = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> CodDxRel1: " . $CodDxRel1;
            //CodDxRel2
            $CodDxRel2_busc_ini = '"CodDxRel2":';
            $CodDxRel2_busc_fin = ',"SopNutricional"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodDxRel2_busc_ini) + strlen($CodDxRel2_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodDxRel2_busc_fin);
            $CodDxRel2 = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> CodDxRel2: " . $CodDxRel2;
            //SopNutricional
            $SopNutricional_busc_ini = '"SopNutricional":';
            $SopNutricional_busc_fin = ',"CodEPS"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $SopNutricional_busc_ini) + strlen($SopNutricional_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $SopNutricional_busc_fin);
            $SopNutricional = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> SopNutricional: " . $SopNutricional;
            //CodEPS
            $CodEPS_busc_ini = '"CodEPS":';
            $CodEPS_busc_fin = ',"TipoIDMadrePaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $CodEPS_busc_ini) + strlen($CodEPS_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $CodEPS_busc_fin);
            $CodEPS = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> CodEPS: " . $CodEPS;
            //TipoIDMadrePaciente
            $TipoIDMadrePaciente_busc_ini = '"TipoIDMadrePaciente":';
            $TipoIDMadrePaciente_busc_fin = ',"NroIDMadrePaciente"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoIDMadrePaciente_busc_ini) + strlen($TipoIDMadrePaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoIDMadrePaciente_busc_fin);
            $TipoIDMadrePaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> TipoIDMadrePaciente: " . $TipoIDMadrePaciente;
            //NroIDMadrePaciente
            $NroIDMadrePaciente_busc_ini = '"NroIDMadrePaciente":';
            $NroIDMadrePaciente_busc_fin = ',"TipoTransc"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NroIDMadrePaciente_busc_ini) + strlen($NroIDMadrePaciente_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NroIDMadrePaciente_busc_fin);
            $NroIDMadrePaciente = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> NroIDMadrePaciente: " . $NroIDMadrePaciente;
            //TipoTransc
            $TipoTransc_busc_ini = '"TipoTransc":';
            $TipoTransc_busc_fin = ',"TipoIDDonanteVivo"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoTransc_busc_ini) + strlen($TipoTransc_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoTransc_busc_fin);
            $TipoTransc = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> TipoTransc: " . $TipoTransc;
            //TipoIDDonanteVivo
            $TipoIDDonanteVivo_busc_ini = '"TipoIDDonanteVivo":';
            $TipoIDDonanteVivo_busc_fin = ',"NroIDDonanteVivo"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $TipoIDDonanteVivo_busc_ini) + strlen($TipoIDDonanteVivo_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $TipoIDDonanteVivo_busc_fin);
            $TipoIDDonanteVivo = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> TipoIDDonanteVivo: " . $TipoIDDonanteVivo;
            //NroIDDonanteVivo
            $NroIDDonanteVivo_busc_ini = '"NroIDDonanteVivo":';
            $NroIDDonanteVivo_busc_fin = ',"EstPres"';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $NroIDDonanteVivo_busc_ini) + strlen($NroIDDonanteVivo_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $NroIDDonanteVivo_busc_fin);
            $NroIDDonanteVivo = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> NroIDDonanteVivo: " . $NroIDDonanteVivo;
            //EstPres
            $EstPres_busc_ini = '"EstPres":';
            $EstPres_busc_fin = '}';
            $cadena_NoPrescripcion = $subCadenaPresGene;
            $posPresInicial = strpos($cadena_NoPrescripcion, $EstPres_busc_ini) + strlen($EstPres_busc_ini);
            $posPresFinal = strpos($cadena_NoPrescripcion, $EstPres_busc_fin);
            $EstPres = substr($cadena_NoPrescripcion, $posPresInicial, $posPresFinal - $posPresInicial);
            echo "<br> EstPres: " . $EstPres;




            $NoPrescripcion;
            $FPrescripcion;
            $HPrescripcion;
            $CodHabIPS;
            $TipoIDIPS;
            $NroIDIPS;
            $CodDANEMunIPS;
            $DirSedeIPS;
            $TelSedeIPS;
            $TipoIDProf;
            $NumIDProf;
            $PNProfS;
            $SNProfS;
            $PAProfS;
            $SAProfS;
            $RegProfS;
            $TipoIDPaciente;
            $NroIDPaciente;
            $PNPaciente;
            $SNPaciente;
            $PAPaciente;
            $SAPaciente;
            $CodAmbAte;
            $RefAmbAte;
            $EnfHuerfana;
            $CodEnfHuerfana;
            $EnfHuerfanaDX;
            $CodDxPpal;
            $CodDxRel1;
            $CodDxRel2;
            $SopNutricional;
            $CodEPS;
            $TipoIDMadrePaciente;
            $NroIDMadrePaciente;
            $TipoTransc;
            $TipoIDDonanteVivo;
            $NroIDDonanteVivo;
            $EstPres;
          }

          ///////////////////////////////////////////////////////////////////////////////////////////medicamentos
          ///////////////////////////////////////////////////////////////////////////////////////////medicamentos
          ///////////////////////////////////////////////////////////////////////////////////////////medicamentos
          ///////////////////////////////////////////////////////////////////////////////////////////medicamentos
          echo "<br><br>-------------------------------------------------------------------------medicamentos";
          //Obtener cadena general de prescripcion
          $cad_pres_busc_ini = '},"medicamentos"';
          $cad_pres_busc_fin = ',"procedimientos"';
          $cadena_presc = $array[$i];
          $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini);
          $posPresFinal = strpos($cadena_presc, $cad_pres_busc_fin);
          $subCadenaPresMedi = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
          //echo "<br> subCadenaPresMedi: " . $subCadenaPresMedi . "<br>";
          /****************************************************************************************************** */
          ////Crear un ciclo con el while para recorrer todos los medicamentos////////////////

          $cadenaMedi = $subCadenaPresMedi;
          $cadenaMedi = str_replace('},"medicamentos":[', 'inicio', $cadenaMedi);
          $cadenaMedi = str_replace(']},{"ConOrden"', ']},inicio{"ConOrden"', $cadenaMedi);
          $subcadenaMediBuscadaInicial   = 'inicio{"ConOrden"';
          //echo "<br> cadenaMedi: " . $cadenaMedi . "<br>";
          $posInicial = strpos($cadenaMedi, $subcadenaMediBuscadaInicial);
          $count_report = 0;

          $vector_medicamentos[0] = "";
          // Notese el uso de ===. Puesto que == simple no funcionará como se espera
          while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

            ///////////////////////////////Se separan Cada uno de los medicamentos (Inicio)/////////////////
            ///////////////////////////////////////////Prescripcion/////////////////////////////////////////

            $subcadenaMediBuscadaInicial   = 'inicio{"ConOrden"';
            // echo "<br> sub cadenaMedi Buscada Inicial: " . $subcadenaMediBuscadaInicial;
            $subcadenaMediBuscadaFinal   = ']}';
            //echo "<br> sub cadenaMedi Buscada Final: " . $subcadenaMediBuscadaFinal;
            $posInicial = strpos($cadenaMedi, $subcadenaMediBuscadaInicial);
            // echo "<br> pos Inicial: " . $posInicial;
            $posFinal = strpos($cadenaMedi, $subcadenaMediBuscadaFinal) + 2;
            //echo "<br> pos Final: " . $posFinal;
            if ($posFinal == "") {
              $posFinal = strlen($cadenaMedi) - 2; //Sera igual a la última posición de la cadenaMedi
              // echo "<br> pos Final no encontrado: " . $posFinal;
            }
            $subcadenaMediFinal = substr($cadenaMedi, $posInicial, $posFinal - $posInicial + 1);
            //echo "<br> Sub cadenaMedi: " . $subcadenaMediFinal;
            if ($subcadenaMediFinal != '[' && $subcadenaMediFinal != '') {
              $vector_medicamentos[$count_report] = str_replace('inicio', '},"medicamentos":[', $subcadenaMediFinal);
              $count_report++;
            }
            $cadenaMedi = str_replace($subcadenaMediFinal, "", $cadenaMedi);
          }
          /****************************************************************************************************** */


          /*******************Leer cada uno de los medicamentos(Inicio)********************************/
          $longitud_vec_medi = count($vector_medicamentos);
          for ($count_vec_medi = 0; $count_vec_medi < $longitud_vec_medi; $count_vec_medi++) {
            echo "<br><br>-------------------------------------medicamento# " . ($count_vec_medi + 1);
            //echo "<br> Cadena#" . $count_vec_medi . ": " . $vector_medicamentos[$count_vec_medi];
            $subCadenaPresMedi = $vector_medicamentos[$count_vec_medi];
            //Guardar en un vector cada una de las variables de la cadena de medicamentos
            $vector_parametros[0] = $subCadenaPresMedi;
            $vector_parametros[1] = '},"medicamentos":[{'; //$subcadena_inicial_a_quitar;
            $vector_parametros[2] = ',"PrincipiosActivos"'; //$subcadena_final_a_quitar;
            $vector_parametros[3] = ',"'; //$subcadena_buscada_inicial;
            $vector_parametros[4] = '":'; //$subcadena_buscada_final;
            $vector_subCadenaPresMedi = obtener_columnas_json($vector_parametros);
            $longitud_vector_subCadenaPresMedi = count($vector_subCadenaPresMedi);

            //obtener los datos de cada una de las variables del json
            // echo "<br> subCadenaPresMedi: " . $subCadenaPresMedi;
            if ($subCadenaPresMedi !== '},"medicamentos":[]') {
              for ($a = 0; $a < $longitud_vector_subCadenaPresMedi; $a++) {
                $parametro_inicial = '"' . $vector_subCadenaPresMedi[$a] . '":';
                if ($a + 1 < $longitud_vector_subCadenaPresMedi) {
                  $parametro_final = ',"' . $vector_subCadenaPresMedi[$a + 1] . '"';
                } else {
                  $parametro_final = ',"PrincipiosActivos"';
                }
                $nombre_dato = $vector_subCadenaPresMedi[$a];
                if ($nombre_dato != '') {
                  obtener_dato_json($parametro_inicial, $parametro_final, $subCadenaPresMedi, $nombre_dato);
                }
              }
              unset($vector_subCadenaPresMedi); //Vaciar el vector


              /////////////////////////////////PrincipiosActivos (Inicio)//////////////////////////////////////////
              /////////////////////////////////PrincipiosActivos (Inicio)//////////////////////////////////////////
              /////////////////////////////////PrincipiosActivos (Inicio)//////////////////////////////////////////

              //PrincipiosActivos
              $PrincipiosActivos_busc_ini = '"PrincipiosActivos":';
              $PrincipiosActivos_busc_fin = '}],"IndicacionesUNIRS"';
              $cadena_PrincipiosActivos = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_PrincipiosActivos, $PrincipiosActivos_busc_ini) + strlen($PrincipiosActivos_busc_ini);
              $posPresFinal = strpos($cadena_PrincipiosActivos, $PrincipiosActivos_busc_fin);
              $PrincipiosActivos = substr($cadena_PrincipiosActivos, $posPresInicial, $posPresFinal - $posPresInicial + 2); //Se le suman dos caracteres para incluir el }] en el final de la cadena

              if ($PrincipiosActivos != "") {
                echo "<br>---------------------------------";
                //Guardar en un vector cada uno de los principios activos
                $subCadenaBuscadaInicial   = '[{"ConOrden":';
                $cadena = $PrincipiosActivos;
                $posInicial = strpos($cadena, $subCadenaBuscadaInicial);

                $count_report = 0;

                $vector_PrincipiosActivos[0] = '';
                $count = 0;
                while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada
                  $count = $count + 1;
                  $subCadenaBuscadaInicial   = '[{"ConOrden":';
                  $subCadenaBuscadaFinal   = ',{"ConOrden"';
                  $cadena = $PrincipiosActivos;
                  $posInicial = strpos($cadena, $subCadenaBuscadaInicial);
                  $posFinal = strpos($cadena, $subCadenaBuscadaFinal);
                  if ($posFinal == "") {
                    $posFinal = strlen($cadena) - 2;
                  }
                  $subCadenaFinal = substr($cadena, $posInicial, $posFinal - $posInicial + 1);
                  if ($subCadenaFinal !== '') {
                    $vector_PrincipiosActivos[$count_report] = $subCadenaFinal;

                    $count_report++;
                  }
                  $PrincipiosActivos = str_replace($subCadenaFinal, "", $PrincipiosActivos);
                }


                //saco el numero de elementos
                $longitud_vector_PrincipiosActivos = count($vector_PrincipiosActivos);
                echo "<br> [PrincipiosActivos]: Cantidad:" . $longitud_vector_PrincipiosActivos; //. $PrincipiosActivos;
                //echo "<br>longitud_vector_PrincipiosActivos: " . $longitud_vector_PrincipiosActivos;
                //Recorro todos los elementos
                for ($e = 0; $e < $longitud_vector_PrincipiosActivos; $e++) {
                  echo "<br>-------";
                  $cadena_principio_activo = $vector_PrincipiosActivos[$e];
                  if ($cadena_principio_activo != '') {
                    //Obtener la cadena de cada uno de los Principios Activos

                    //ConOrden
                    $parametro_inicial = '"ConOrden":';
                    $parametro_final = ',"CodPriAct"';
                    $nombre_dato = 'ConOrden';
                    obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);

                    //CodPriAct
                    $parametro_inicial = '"CodPriAct":';
                    $parametro_final = ',"ConcCant"';
                    $nombre_dato = 'CodPriAct';
                    obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);

                    //ConcCant
                    $parametro_inicial = '"ConcCant":';
                    $parametro_final = ',"UMedConc"';
                    $nombre_dato = 'ConcCant';
                    obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);

                    //UMedConc
                    $parametro_inicial = '"UMedConc":';
                    $parametro_final = ',"CantCont"';
                    $nombre_dato = 'UMedConc';
                    obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);

                    //CantCont
                    $parametro_inicial = '"CantCont":';
                    $parametro_final = ',"UMedCantCont"';
                    $nombre_dato = 'CantCont';
                    obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);

                    //UMedCantCont
                    $parametro_inicial = '"UMedCantCont":';
                    $parametro_final = '}';
                    $nombre_dato = 'UMedCantCont';
                    obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);
                  }
                }
                unset($vector_PrincipiosActivos); //Vaciar el vector

                $ConOrden;
                $CodPriAct;
                $ConcCant;
                $UMedConc;
                $CantCont;
                $UMedCantCont;
              }


              /////////////////////////////////PrincipiosActivos (Fin)//////////////////////////////////////////
              /////////////////////////////////PrincipiosActivos (Fin)//////////////////////////////////////////
              /////////////////////////////////PrincipiosActivos (Fin)//////////////////////////////////////////


              /////////////////////////////////IndicacionesUNIRS (Inicio)/////////////////////////////////////////
              /////////////////////////////////IndicacionesUNIRS (Inicio)/////////////////////////////////////////
              /////////////////////////////////IndicacionesUNIRS (Inicio)/////////////////////////////////////////
              //IndicacionesUNIRS
              $IndicacionesUNIRS_busc_ini = '"IndicacionesUNIRS":';
              $IndicacionesUNIRS_busc_fin = '}]}';
              $cadena_IndicacionesUNIRS = $subCadenaPresMedi;
              $posPresInicial = strpos($cadena_IndicacionesUNIRS, $IndicacionesUNIRS_busc_ini) + strlen($IndicacionesUNIRS_busc_ini);
              $posPresFinal = strpos($cadena_IndicacionesUNIRS, $IndicacionesUNIRS_busc_fin);
              $IndicacionesUNIRS = substr($cadena_IndicacionesUNIRS, $posPresInicial, $posPresFinal - $posPresInicial + 2); //Se le suman dos caracteres  para incluir el }] en el final de la cadena
              if ($IndicacionesUNIRS != '') {
                echo "<br>---------------------------------";
                //Guardar en un vector cada uno de los IndicacionesUNIRS
                $subCadenaBuscadaInicial   = '[{"ConOrden":';
                $cadena = $IndicacionesUNIRS;
                $posInicial = strpos($cadena, $subCadenaBuscadaInicial);

                $count_report = 0;

                $vector_IndicacionesUNIRS[0] = '';
                $count = 0;
                while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada
                  $count = $count + 1;
                  $subCadenaBuscadaInicial   = '[{"ConOrden":';
                  $subCadenaBuscadaFinal   = ',{"ConOrden"';
                  $cadena = $IndicacionesUNIRS;
                  $posInicial = strpos($cadena, $subCadenaBuscadaInicial);
                  $posFinal = strpos($cadena, $subCadenaBuscadaFinal);
                  if ($posFinal == "") {
                    $posFinal = strlen($cadena) - 2;
                  }
                  $subCadenaFinal = substr($cadena, $posInicial, $posFinal - $posInicial + 1);
                  if ($subCadenaFinal !== '') {
                    $vector_IndicacionesUNIRS[$count_report] = $subCadenaFinal;

                    $count_report++;
                  }
                  $IndicacionesUNIRS = str_replace($subCadenaFinal, "", $IndicacionesUNIRS);
                }


                //saco el numero de elementos
                $longitud_vector_IndicacionesUNIRS = count($vector_IndicacionesUNIRS);

                echo "<br> [IndicacionesUNIRS]: Cantidad: " . $longitud_vector_IndicacionesUNIRS; // . $IndicacionesUNIRS;
                //echo "<br>longitud_vector_IndicacionesUNIRS: " . $longitud_vector_IndicacionesUNIRS;
                //Recorro todos los elementos
                for ($e = 0; $e < $longitud_vector_IndicacionesUNIRS; $e++) {
                  echo "<br>-------";
                  $cadena_principio_activo = $vector_IndicacionesUNIRS[$e];
                  if ($cadena_principio_activo != '') {
                    //Obtener la cadena de cada uno de los IndicacionesUNIRS

                    //ConOrden
                    $parametro_inicial = '"ConOrden":';
                    $parametro_final = ',"CodIndicacion"';
                    $nombre_dato = 'ConOrden';
                    obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);

                    //UMedCantCont
                    $parametro_inicial = '"CodIndicacion":';
                    $parametro_final = '}';
                    $nombre_dato = 'CodIndicacion';
                    obtener_dato_json($parametro_inicial, $parametro_final, $cadena_principio_activo, $nombre_dato);
                  }
                }
                unset($vector_IndicacionesUNIRS); //Vaciar el vector

                $ConOrden;
                $CodIndicacion;
              }


              /////////////////////////////////IndicacionesUNIRS (Fin)/////////////////////////////////////////
              /////////////////////////////////IndicacionesUNIRS (Fin)/////////////////////////////////////////
              /////////////////////////////////IndicacionesUNIRS (Fin)/////////////////////////////////////////


              //Columnas de medicamentos
              $ConOrden;
              $TipoMed;
              $TipoPrest;
              $CausaS1;
              $CausaS2;
              $CausaS3;
              $MedPBSUtilizado;
              $RznCausaS31;
              $DescRzn31;
              $RznCausaS32;
              $DescRzn32;
              $CausaS4;
              $MedPBSDescartado;
              $RznCausaS41;
              $DescRzn41;
              $RznCausaS42;
              $DescRzn42;
              $RznCausaS43;
              $DescRzn43;
              $RznCausaS44;
              $DescRzn44;
              $CausaS5;
              $RznCausaS5;
              $CausaS6;
              $DescMedPrinAct;
              $CodFF;
              $CodVA;
              $JustNoPBS;
              $Dosis;
              $DosisUM;
              $NoFAdmon;
              $CodFreAdmon;
              $IndEsp;
              $CanTrat;
              $DurTrat;
              $CantTotalF;
              $UFCantTotal;
              $IndRec;
              $EstJM;
            }
          }
          unset($vector_medicamentos); //Vaciar el vector

          /*******************Leer cada uno de los medicamentos(Fin)********************************/


          ///////////////////////////////////////////////////////////////////////////////////////////procedimientos
          echo "<br><br>-------------------------------------------------------------------------procedimientos";


          //Obtener cadena general de prescripcion
          $cad_pres_busc_ini = '],"procedimientos"';
          $cad_pres_busc_fin = ',"dispositivos"';
          $cadena_presc = $array[$i];
          $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini);
          $posPresFinal = strpos($cadena_presc, $cad_pres_busc_fin);
          $subCadenaPresProc = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
          echo "<br> subCadenaPresProc: " . $subCadenaPresProc . "<br>";
          /****************************************************************************************************** */
          ////Crear un ciclo con el while para recorrer todos los procedimientos////////////////

          $cadenaProc = $subCadenaPresProc;
          // $cadenaProc = str_replace('},"procedimientos":[', 'inicio', $cadenaProc);
          // $cadenaProc = str_replace('},{"ConOrden"', '},inicio{"ConOrden"', $cadenaProc);
          $subcadenaProcBuscadaInicial   = '{"ConOrden"';
          //echo "<br> cadenaProc: " . $cadenaProc . "<br>";
          $posInicial = strpos($cadenaProc, $subcadenaProcBuscadaInicial);
          $count_report = 0;

          $vector_procedimientos[0] = "";
          // Notese el uso de ===. Puesto que == simple no funcionará como se espera
          while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

            ///////////////////////////////////////////Prescripcion///////////////////////////////////////////
            ///////////////////////////////Se separan Cada uno de los procedimientos (Inicio)/////////////////
            ///////////////////////////////////////////Prescripcion///////////////////////////////////////////

            $subcadenaProcBuscadaInicial   = '{"ConOrden"';
            // echo "<br> sub cadenaProc Buscada Inicial: " . $subcadenaProcBuscadaInicial;
            $subcadenaProcBuscadaFinal   = '}';
            //echo "<br> sub cadenaProc Buscada Final: " . $subcadenaProcBuscadaFinal;
            $posInicial = strpos($cadenaProc, $subcadenaProcBuscadaInicial);
            // echo "<br> pos Inicial: " . $posInicial;
            $posFinal = strpos($cadenaProc, $subcadenaProcBuscadaFinal) + 2;
            //echo "<br> pos Final: " . $posFinal;
            if ($posFinal == "") {
              $posFinal = strlen($cadenaProc) - 2; //Sera igual a la última posición de la cadenaProc
              // echo "<br> pos Final no encontrado: " . $posFinal;
            }
            $subcadenaProcFinal = substr($cadenaProc, $posInicial, $posFinal - $posInicial);
            echo "<br> Sub cadenaProc: " . $subcadenaProcFinal;
            if ($subcadenaProcFinal != '[' && $subcadenaProcFinal != '') {
              //$vector_procedimientos[$count_report] = str_replace('inicio', '},"procedimientos":[', $subcadenaProcFinal);
              $vector_procedimientos[$count_report] =  $subcadenaProcFinal;
              $count_report++;
            }
            $cadenaProc = str_replace($subcadenaProcFinal, "", $cadenaProc);
          }
          /****************************************************************************************************** */


          /*******************Leer cada uno de los procedimientos(Inicio)********************************/
          $longitud_vec_Proc = count($vector_procedimientos);
          for ($count_vec_Proc = 0; $count_vec_Proc < $longitud_vec_Proc; $count_vec_Proc++) {
            //echo "<br> Cadena#" . $count_vec_Proc . ": " . $vector_procedimientos[$count_vec_Proc];
            $subCadenaPresProc = $vector_procedimientos[$count_vec_Proc];


            if (strlen($subCadenaPresProc) > 5) {//Si la cadena tiene mas de 5 caracteres entonces se entiende que hay datos para leer
              echo "<br><br>-------------------------------------procedimiento# " . ($count_vec_Proc + 1);
              echo "<br> subCadenaPresProc: " . $subCadenaPresProc;
              //ConOrden
              $ConOrden_busc_ini = '"ConOrden":';
              $ConOrden_busc_fin = ',"TipoPrest"';
              $cadena_ConOrden = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_ConOrden, $ConOrden_busc_ini) + strlen($ConOrden_busc_ini);
              $posPresFinal = strpos($cadena_ConOrden, $ConOrden_busc_fin);
              $ConOrden = substr($cadena_ConOrden, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> ConOrden: " . $ConOrden;
              //TipoPrest
              $TipoPrest_busc_ini = '"TipoPrest":';
              $TipoPrest_busc_fin = ',"CausaS11"';
              $cadena_TipoPrest = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_TipoPrest, $TipoPrest_busc_ini) + strlen($TipoPrest_busc_ini);
              $posPresFinal = strpos($cadena_TipoPrest, $TipoPrest_busc_fin);
              $TipoPrest = substr($cadena_TipoPrest, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> TipoPrest: " . $TipoPrest;
              //CausaS11
              $CausaS11_busc_ini = '"CausaS11":';
              $CausaS11_busc_fin = ',"CausaS12"';
              $cadena_CausaS11 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS11, $CausaS11_busc_ini) + strlen($CausaS11_busc_ini);
              $posPresFinal = strpos($cadena_CausaS11, $CausaS11_busc_fin);
              $CausaS11 = substr($cadena_CausaS11, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS11: " . $CausaS11;
              //CausaS12
              $CausaS12_busc_ini = '"CausaS12":';
              $CausaS12_busc_fin = ',"CausaS2"';
              $cadena_CausaS12 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS12, $CausaS12_busc_ini) + strlen($CausaS12_busc_ini);
              $posPresFinal = strpos($cadena_CausaS12, $CausaS12_busc_fin);
              $CausaS12 = substr($cadena_CausaS12, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS12: " . $CausaS12;
              //CausaS2
              $CausaS2_busc_ini = '"CausaS2":';
              $CausaS2_busc_fin = ',"CausaS3"';
              $cadena_CausaS2 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS2, $CausaS2_busc_ini) + strlen($CausaS2_busc_ini);
              $posPresFinal = strpos($cadena_CausaS2, $CausaS2_busc_fin);
              $CausaS2 = substr($cadena_CausaS2, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS2: " . $CausaS2;
              //CausaS3
              $CausaS3_busc_ini = '"CausaS3":';
              $CausaS3_busc_fin = ',"CausaS4"';
              $cadena_CausaS3 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS3, $CausaS3_busc_ini) + strlen($CausaS3_busc_ini);
              $posPresFinal = strpos($cadena_CausaS3, $CausaS3_busc_fin);
              $CausaS3 = substr($cadena_CausaS3, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS3: " . $CausaS3;
              //CausaS4
              $CausaS4_busc_ini = '"CausaS4":';
              $CausaS4_busc_fin = ',"ProPBSUtilizado"';
              $cadena_CausaS4 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS4, $CausaS4_busc_ini) + strlen($CausaS4_busc_ini);
              $posPresFinal = strpos($cadena_CausaS4, $CausaS4_busc_fin);
              $CausaS4 = substr($cadena_CausaS4, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS4: " . $CausaS4;
              //ProPBSUtilizado
              $ProPBSUtilizado_busc_ini = '"ProPBSUtilizado":';
              $ProPBSUtilizado_busc_fin = ',"CausaS5"';
              $cadena_ProPBSUtilizado = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_ProPBSUtilizado, $ProPBSUtilizado_busc_ini) + strlen($ProPBSUtilizado_busc_ini);
              $posPresFinal = strpos($cadena_ProPBSUtilizado, $ProPBSUtilizado_busc_fin);
              $ProPBSUtilizado = substr($cadena_ProPBSUtilizado, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> ProPBSUtilizado: " . $ProPBSUtilizado;
              //CausaS5
              $CausaS5_busc_ini = '"CausaS5":';
              $CausaS5_busc_fin = ',"ProPBSDescartado"';
              $cadena_CausaS5 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS5, $CausaS5_busc_ini) + strlen($CausaS5_busc_ini);
              $posPresFinal = strpos($cadena_CausaS5, $CausaS5_busc_fin);
              $CausaS5 = substr($cadena_CausaS5, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS5: " . $CausaS5;
              //ProPBSDescartado
              $ProPBSDescartado_busc_ini = '"ProPBSDescartado":';
              $ProPBSDescartado_busc_fin = ',"RznCausaS51"';
              $cadena_ProPBSDescartado = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_ProPBSDescartado, $ProPBSDescartado_busc_ini) + strlen($ProPBSDescartado_busc_ini);
              $posPresFinal = strpos($cadena_ProPBSDescartado, $ProPBSDescartado_busc_fin);
              $ProPBSDescartado = substr($cadena_ProPBSDescartado, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> ProPBSDescartado: " . $ProPBSDescartado;
              //RznCausaS51
              $RznCausaS51_busc_ini = '"RznCausaS51":';
              $RznCausaS51_busc_fin = ',"DescRzn51"';
              $cadena_RznCausaS51 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_RznCausaS51, $RznCausaS51_busc_ini) + strlen($RznCausaS51_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS51, $RznCausaS51_busc_fin);
              $RznCausaS51 = substr($cadena_RznCausaS51, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> RznCausaS51: " . $RznCausaS51;
              //DescRzn51
              $DescRzn51_busc_ini = '"DescRzn51":';
              $DescRzn51_busc_fin = ',"RznCausaS52"';
              $cadena_DescRzn51 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_DescRzn51, $DescRzn51_busc_ini) + strlen($DescRzn51_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn51, $DescRzn51_busc_fin);
              $DescRzn51 = substr($cadena_DescRzn51, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DescRzn51: " . $DescRzn51;
              //RznCausaS52
              $RznCausaS52_busc_ini = '"RznCausaS52":';
              $RznCausaS52_busc_fin = ',"DescRzn52"';
              $cadena_RznCausaS52 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_RznCausaS52, $RznCausaS52_busc_ini) + strlen($RznCausaS52_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS52, $RznCausaS52_busc_fin);
              $RznCausaS52 = substr($cadena_RznCausaS52, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> RznCausaS52: " . $RznCausaS52;
              //DescRzn52
              $DescRzn52_busc_ini = '"DescRzn52":';
              $DescRzn52_busc_fin = ',"CausaS6"';
              $cadena_DescRzn52 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_DescRzn52, $DescRzn52_busc_ini) + strlen($DescRzn52_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn52, $DescRzn52_busc_fin);
              $DescRzn52 = substr($cadena_DescRzn52, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DescRzn52: " . $DescRzn52;
              //CausaS6
              $CausaS6_busc_ini = '"CausaS6":';
              $CausaS6_busc_fin = ',"CausaS7"';
              $cadena_CausaS6 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS6, $CausaS6_busc_ini) + strlen($CausaS6_busc_ini);
              $posPresFinal = strpos($cadena_CausaS6, $CausaS6_busc_fin);
              $CausaS6 = substr($cadena_CausaS6, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS6: " . $CausaS6;
              //CausaS7
              $CausaS7_busc_ini = '"CausaS7":';
              $CausaS7_busc_fin = ',"CodCUPS"';
              $cadena_CausaS7 = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CausaS7, $CausaS7_busc_ini) + strlen($CausaS7_busc_ini);
              $posPresFinal = strpos($cadena_CausaS7, $CausaS7_busc_fin);
              $CausaS7 = substr($cadena_CausaS7, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS7: " . $CausaS7;
              //CodCUPS
              $CodCUPS_busc_ini = '"CodCUPS":';
              $CodCUPS_busc_fin = ',"CanForm"';
              $cadena_CodCUPS = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CodCUPS, $CodCUPS_busc_ini) + strlen($CodCUPS_busc_ini);
              $posPresFinal = strpos($cadena_CodCUPS, $CodCUPS_busc_fin);
              $CodCUPS = substr($cadena_CodCUPS, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodCUPS: " . $CodCUPS;
              //CanForm
              $CanForm_busc_ini = '"CanForm":';
              $CanForm_busc_fin = ',"CadaFreUso"';
              $cadena_CanForm = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CanForm, $CanForm_busc_ini) + strlen($CanForm_busc_ini);
              $posPresFinal = strpos($cadena_CanForm, $CanForm_busc_fin);
              $CanForm = substr($cadena_CanForm, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CanForm: " . $CanForm;
              //CadaFreUso
              $CadaFreUso_busc_ini = '"CadaFreUso":';
              $CadaFreUso_busc_fin = ',"CodFreUso"';
              $cadena_CadaFreUso = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CadaFreUso, $CadaFreUso_busc_ini) + strlen($CadaFreUso_busc_ini);
              $posPresFinal = strpos($cadena_CadaFreUso, $CadaFreUso_busc_fin);
              $CadaFreUso = substr($cadena_CadaFreUso, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CadaFreUso: " . $CadaFreUso;
              //CodFreUso
              $CodFreUso_busc_ini = '"CodFreUso":';
              $CodFreUso_busc_fin = ',"Cant"';
              $cadena_CodFreUso = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CodFreUso, $CodFreUso_busc_ini) + strlen($CodFreUso_busc_ini);
              $posPresFinal = strpos($cadena_CodFreUso, $CodFreUso_busc_fin);
              $CodFreUso = substr($cadena_CodFreUso, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodFreUso: " . $CodFreUso;
              //Cant
              $Cant_busc_ini = '"Cant":';
              $Cant_busc_fin = ',"CantTotal"';
              $cadena_Cant = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_Cant, $Cant_busc_ini) + strlen($Cant_busc_ini);
              $posPresFinal = strpos($cadena_Cant, $Cant_busc_fin);
              $Cant = substr($cadena_Cant, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> Cant: " . $Cant;
              //CantTotal
              $CantTotal_busc_ini = '"CantTotal":';
              $CantTotal_busc_fin = ',"CodPerDurTrat"';
              $cadena_CantTotal = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CantTotal, $CantTotal_busc_ini) + strlen($CantTotal_busc_ini);
              $posPresFinal = strpos($cadena_CantTotal, $CantTotal_busc_fin);
              $CantTotal = substr($cadena_CantTotal, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CantTotal: " . $CantTotal;
              //CodPerDurTrat
              $CodPerDurTrat_busc_ini = '"CodPerDurTrat":';
              $CodPerDurTrat_busc_fin = ',"JustNoPBS"';
              $cadena_CodPerDurTrat = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_CodPerDurTrat, $CodPerDurTrat_busc_ini) + strlen($CodPerDurTrat_busc_ini);
              $posPresFinal = strpos($cadena_CodPerDurTrat, $CodPerDurTrat_busc_fin);
              $CodPerDurTrat = substr($cadena_CodPerDurTrat, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodPerDurTrat: " . $CodPerDurTrat;
              //JustNoPBS
              $JustNoPBS_busc_ini = '"JustNoPBS":';
              $JustNoPBS_busc_fin = ',"IndRec"';
              $cadena_JustNoPBS = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_JustNoPBS, $JustNoPBS_busc_ini) + strlen($JustNoPBS_busc_ini);
              $posPresFinal = strpos($cadena_JustNoPBS, $JustNoPBS_busc_fin);
              $JustNoPBS = substr($cadena_JustNoPBS, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> JustNoPBS: " . $JustNoPBS;
              //IndRec
              $IndRec_busc_ini = '"IndRec":';
              $IndRec_busc_fin = ',"EstJM"';
              $cadena_IndRec = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_IndRec, $IndRec_busc_ini) + strlen($IndRec_busc_ini);
              $posPresFinal = strpos($cadena_IndRec, $IndRec_busc_fin);
              $IndRec = substr($cadena_IndRec, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> IndRec: " . $IndRec;
              //EstJM
              $EstJM_busc_ini = '"EstJM":';
              $EstJM_busc_fin = '}';
              $cadena_EstJM = $subCadenaPresProc;
              $posPresInicial = strpos($cadena_EstJM, $EstJM_busc_ini) + strlen($EstJM_busc_ini);
              $posPresFinal = strpos($cadena_EstJM, $EstJM_busc_fin);
              $EstJM = substr($cadena_EstJM, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> EstJM: " . $EstJM;


              $ConOrden;
              $TipoPrest;
              $CausaS11;
              $CausaS12;
              $CausaS2;
              $CausaS3;
              $CausaS4;
              $ProPBSUtilizado;
              $CausaS5;
              $ProPBSDescartado;
              $RznCausaS51;
              $DescRzn51;
              $RznCausaS52;
              $DescRzn52;
              $CausaS6;
              $CausaS7;
              $CodCUPS;
              $CanForm;
              $CadaFreUso;
              $CodFreUso;
              $Cant;
              $CantTotal;
              $CodPerDurTrat;
              $JustNoPBS;
              $IndRec;
              $EstJM;
            }
          }
          unset($vector_procedimientos); //Vaciar el vector




          ///////////////////////////////////////////////////////////////////////////////////////////dispositivos (Inicio)
          echo "<br><br>-------------------------------------------------------------------------dispositivos";


          //Obtener cadena general de dispositivos
          $cad_pres_busc_ini = '],"dispositivos"';
          $cad_pres_busc_fin = ',"productosnutricionales"';
          $cadena_presc = $array[$i];
          $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini);
          $posPresFinal = strpos($cadena_presc, $cad_pres_busc_fin);
          $subCadenaPresDisp = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
          echo "<br> subCadenaPresDisp: " . $subCadenaPresDisp . "<br>";
          /****************************************************************************************************** */
          ////Crear un ciclo con el while para recorrer todos los dispositivos////////////////

          $cadenaDisp = $subCadenaPresDisp;
          // $cadenaDisp = str_replace('},"dispositivos":[', 'inicio', $cadenaDisp);
          // $cadenaDisp = str_replace('},{"ConOrden"', '},inicio{"ConOrden"', $cadenaDisp);
          $subcadenaDispBuscadaInicial   = '{"ConOrden"';
          //echo "<br> cadenaDisp: " . $cadenaDisp . "<br>";
          $posInicial = strpos($cadenaDisp, $subcadenaDispBuscadaInicial);
          $count_report = 0;

          $vector_dispositivos[0] = "";
          // Notese el uso de ===. Puesto que == simple no funcionará como se espera
          while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

            ///////////////////////////////////////////dispositivos///////////////////////////////////////////
            ///////////////////////////////Se separan Cada uno de los dispositivos (Inicio)/////////////////
            ///////////////////////////////////////////dispositivos///////////////////////////////////////////

            $subcadenaDispBuscadaInicial   = '{"ConOrden"';
            // echo "<br> sub cadenaDisp Buscada Inicial: " . $subcadenaDispBuscadaInicial;
            $subcadenaDispBuscadaFinal   = '}';
            //echo "<br> sub cadenaDisp Buscada Final: " . $subcadenaDispBuscadaFinal;
            $posInicial = strpos($cadenaDisp, $subcadenaDispBuscadaInicial);
            // echo "<br> pos Inicial: " . $posInicial;
            $posFinal = strpos($cadenaDisp, $subcadenaDispBuscadaFinal) + 2;
            //echo "<br> pos Final: " . $posFinal;
            if ($posFinal == "") {
              $posFinal = strlen($cadenaDisp) - 2; //Sera igual a la última posición de la cadenaDisp
              // echo "<br> pos Final no encontrado: " . $posFinal;
            }
            $subcadenaDispFinal = substr($cadenaDisp, $posInicial, $posFinal - $posInicial);
            echo "<br> Sub cadenaDisp: " . $subcadenaDispFinal;
            if ($subcadenaDispFinal != '[' && $subcadenaDispFinal != '') {
              //$vector_dispositivos[$count_report] = str_replace('inicio', '},"dispositivos":[', $subcadenaDispFinal);
              $vector_dispositivos[$count_report] =  $subcadenaDispFinal;
              $count_report++;
            }
            $cadenaDisp = str_replace($subcadenaDispFinal, "", $cadenaDisp);
          }
          /****************************************************************************************************** */


          /*******************Leer cada uno de los dispositivos(Inicio)********************************/
          $longitud_vec_Disp = count($vector_dispositivos);
          for ($count_vec_Disp = 0; $count_vec_Disp < $longitud_vec_Disp; $count_vec_Disp++) {
            //echo "<br> Cadena#" . $count_vec_Disp . ": " . $vector_dispositivos[$count_vec_Disp];
            $subCadenaPresDisp = $vector_dispositivos[$count_vec_Disp];


            if (strlen($subCadenaPresDisp) > 5) {//Si la cadena tiene mas de 5 caracteres entonces se entiende que hay datos para leer
              echo "<br><br>-------------------------------------dispositivo# " . ($count_vec_Disp + 1);
              echo "<br> subCadenaPresDisp: " . $subCadenaPresDisp;

              //ConOrden
              $ConOrden_busc_ini = '"ConOrden":';
              $ConOrden_busc_fin = ',"TipoPrest"';
              $cadena_ConOrden = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_ConOrden, $ConOrden_busc_ini) + strlen($ConOrden_busc_ini);
              $posPresFinal = strpos($cadena_ConOrden, $ConOrden_busc_fin);
              $ConOrden = substr($cadena_ConOrden, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> ConOrden: " . $ConOrden;
              //TipoPrest
              $TipoPrest_busc_ini = '"TipoPrest":';
              $TipoPrest_busc_fin = ',"CausaS1"';
              $cadena_TipoPrest = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_TipoPrest, $TipoPrest_busc_ini) + strlen($TipoPrest_busc_ini);
              $posPresFinal = strpos($cadena_TipoPrest, $TipoPrest_busc_fin);
              $TipoPrest = substr($cadena_TipoPrest, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> TipoPrest: " . $TipoPrest;
              //CausaS1
              $CausaS1_busc_ini = '"CausaS1":';
              $CausaS1_busc_fin = ',"CodDisp"';
              $cadena_CausaS1 = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CausaS1, $CausaS1_busc_ini) + strlen($CausaS1_busc_ini);
              $posPresFinal = strpos($cadena_CausaS1, $CausaS1_busc_fin);
              $CausaS1 = substr($cadena_CausaS1, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS1: " . $CausaS1;
              //CodDisp
              $CodDisp_busc_ini = '"CodDisp":';
              $CodDisp_busc_fin = ',"CanForm"';
              $cadena_CodDisp = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CodDisp, $CodDisp_busc_ini) + strlen($CodDisp_busc_ini);
              $posPresFinal = strpos($cadena_CodDisp, $CodDisp_busc_fin);
              $CodDisp = substr($cadena_CodDisp, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodDisp: " . $CodDisp;
              //CanForm
              $CanForm_busc_ini = '"CanForm":';
              $CanForm_busc_fin = ',"CadaFreUso"';
              $cadena_CanForm = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CanForm, $CanForm_busc_ini) + strlen($CanForm_busc_ini);
              $posPresFinal = strpos($cadena_CanForm, $CanForm_busc_fin);
              $CanForm = substr($cadena_CanForm, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CanForm: " . $CanForm;
              //CadaFreUso
              $CadaFreUso_busc_ini = '"CadaFreUso":';
              $CadaFreUso_busc_fin = ',"CodFreUso"';
              $cadena_CadaFreUso = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CadaFreUso, $CadaFreUso_busc_ini) + strlen($CadaFreUso_busc_ini);
              $posPresFinal = strpos($cadena_CadaFreUso, $CadaFreUso_busc_fin);
              $CadaFreUso = substr($cadena_CadaFreUso, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CadaFreUso: " . $CadaFreUso;
              //CodFreUso
              $CodFreUso_busc_ini = '"CodFreUso":';
              $CodFreUso_busc_fin = ',"Cant"';
              $cadena_CodFreUso = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CodFreUso, $CodFreUso_busc_ini) + strlen($CodFreUso_busc_ini);
              $posPresFinal = strpos($cadena_CodFreUso, $CodFreUso_busc_fin);
              $CodFreUso = substr($cadena_CodFreUso, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodFreUso: " . $CodFreUso;
              //Cant
              $Cant_busc_ini = '"Cant":';
              $Cant_busc_fin = ',"CodPerDurTrat"';
              $cadena_Cant = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_Cant, $Cant_busc_ini) + strlen($Cant_busc_ini);
              $posPresFinal = strpos($cadena_Cant, $Cant_busc_fin);
              $Cant = substr($cadena_Cant, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> Cant: " . $Cant;
              //CodPerDurTrat
              $CodPerDurTrat_busc_ini = '"CodPerDurTrat":';
              $CodPerDurTrat_busc_fin = ',"CantTotal"';
              $cadena_CodPerDurTrat = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CodPerDurTrat, $CodPerDurTrat_busc_ini) + strlen($CodPerDurTrat_busc_ini);
              $posPresFinal = strpos($cadena_CodPerDurTrat, $CodPerDurTrat_busc_fin);
              $CodPerDurTrat = substr($cadena_CodPerDurTrat, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodPerDurTrat: " . $CodPerDurTrat;
              //CantTotal
              $CantTotal_busc_ini = '"CantTotal":';
              $CantTotal_busc_fin = ',"JustNoPBS"';
              $cadena_CantTotal = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_CantTotal, $CantTotal_busc_ini) + strlen($CantTotal_busc_ini);
              $posPresFinal = strpos($cadena_CantTotal, $CantTotal_busc_fin);
              $CantTotal = substr($cadena_CantTotal, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CantTotal: " . $CantTotal;
              //JustNoPBS
              $JustNoPBS_busc_ini = '"JustNoPBS":';
              $JustNoPBS_busc_fin = ',"IndRec"';
              $cadena_JustNoPBS = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_JustNoPBS, $JustNoPBS_busc_ini) + strlen($JustNoPBS_busc_ini);
              $posPresFinal = strpos($cadena_JustNoPBS, $JustNoPBS_busc_fin);
              $JustNoPBS = substr($cadena_JustNoPBS, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> JustNoPBS: " . $JustNoPBS;
              //IndRec
              $IndRec_busc_ini = '"IndRec":';
              $IndRec_busc_fin = ',"EstJM"';
              $cadena_IndRec = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_IndRec, $IndRec_busc_ini) + strlen($IndRec_busc_ini);
              $posPresFinal = strpos($cadena_IndRec, $IndRec_busc_fin);
              $IndRec = substr($cadena_IndRec, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> IndRec: " . $IndRec;
              //EstJM
              $EstJM_busc_ini = '"EstJM":';
              $EstJM_busc_fin = '}';
              $cadena_EstJM = $subCadenaPresDisp;
              $posPresInicial = strpos($cadena_EstJM, $EstJM_busc_ini) + strlen($EstJM_busc_ini);
              $posPresFinal = strpos($cadena_EstJM, $EstJM_busc_fin);
              $EstJM = substr($cadena_EstJM, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> EstJM: " . $EstJM;


              $ConOrden;
              $TipoPrest;
              $CausaS1;
              $CodDisp;
              $CanForm;
              $CadaFreUso;
              $CodFreUso;
              $Cant;
              $CodPerDurTrat;
              $CantTotal;
              $JustNoPBS;
              $IndRec;
              $EstJM;
            }
          }
          unset($vector_dispositivos); //Vaciar el vector

          ///////////////////////////////////////////////////////////////////////////////////////////dispositivos (Fin)

          ///////////////////////////////////////////////////////////////////////////////////productosnutricionales (Inicio)
          echo "<br><br>-------------------------------------------------------------------------productosnutricionales";

          //Obtener cadena general de productosnutricionales
          $cad_pres_busc_ini = '],"productosnutricionales"';
          $cad_pres_busc_fin = ',"serviciosComplementarios"';
          $cadena_presc = $array[$i];
          $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini);
          $posPresFinal = strpos($cadena_presc, $cad_pres_busc_fin);
          $subCadenaPresProdNutr = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
          echo "<br> subCadenaPresProdNutr: " . $subCadenaPresProdNutr . "<br>";
          /****************************************************************************************************** */
          ////Crear un ciclo con el while para recorrer todos los productosnutricionales////////////////

          $cadenaProdNutr = $subCadenaPresProdNutr;
          // $cadenaProdNutr = str_replace('},"productosnutricionales":[', 'inicio', $cadenaProdNutr);
          // $cadenaProdNutr = str_replace('},{"ConOrden"', '},inicio{"ConOrden"', $cadenaProdNutr);
          $subcadenaProdNutrBuscadaInicial   = '{"ConOrden"';
          //echo "<br> cadenaProdNutr: " . $cadenaProdNutr . "<br>";
          $posInicial = strpos($cadenaProdNutr, $subcadenaProdNutrBuscadaInicial);
          $count_report = 0;

          $vector_productosnutricionales[0] = "";
          // Notese el uso de ===. Puesto que == simple no funcionará como se espera
          while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

            ///////////////////////////////////////////productosnutricionales///////////////////////////////////////////
            ///////////////////////////////Se separan Cada uno de los productosnutricionales (Inicio)/////////////////
            ///////////////////////////////////////////productosnutricionales///////////////////////////////////////////

            $subcadenaProdNutrBuscadaInicial   = '{"ConOrden"';
            // echo "<br> sub cadenaProdNutr Buscada Inicial: " . $subcadenaProdNutrBuscadaInicial;
            $subcadenaProdNutrBuscadaFinal   = '}';
            //echo "<br> sub cadenaProdNutr Buscada Final: " . $subcadenaProdNutrBuscadaFinal;
            $posInicial = strpos($cadenaProdNutr, $subcadenaProdNutrBuscadaInicial);
            // echo "<br> pos Inicial: " . $posInicial;
            $posFinal = strpos($cadenaProdNutr, $subcadenaProdNutrBuscadaFinal) + 2;
            //echo "<br> pos Final: " . $posFinal;
            if ($posFinal == "") {
              $posFinal = strlen($cadenaProdNutr) - 2; //Sera igual a la última posición de la cadenaProdNutr
              // echo "<br> pos Final no encontrado: " . $posFinal;
            }
            $subcadenaProdNutrFinal = substr($cadenaProdNutr, $posInicial, $posFinal - $posInicial);
            echo "<br> Sub cadenaProdNutr: " . $subcadenaProdNutrFinal;
            if ($subcadenaProdNutrFinal != '[' && $subcadenaProdNutrFinal != '') {
              //$vector_productosnutricionales[$count_report] = str_replace('inicio', '},"productosnutricionales":[', $subcadenaProdNutrFinal);
              $vector_productosnutricionales[$count_report] =  $subcadenaProdNutrFinal;
              $count_report++;
            }
            $cadenaProdNutr = str_replace($subcadenaProdNutrFinal, "", $cadenaProdNutr);
          }
          /****************************************************************************************************** */


          /*******************Leer cada uno de los productosnutricionales(Inicio)********************************/
          $longitud_vec_ProdNutr = count($vector_productosnutricionales);
          for ($count_vec_ProdNutr = 0; $count_vec_ProdNutr < $longitud_vec_ProdNutr; $count_vec_ProdNutr++) {
            //echo "<br> Cadena#" . $count_vec_ProdNutr . ": " . $vector_productosnutricionales[$count_vec_ProdNutr];
            $subCadenaPresProdNutr = $vector_productosnutricionales[$count_vec_ProdNutr];


            if (strlen($subCadenaPresProdNutr) > 5) {//Si la cadena tiene mas de 5 caracteres entonces se entiende que hay datos para leer
              echo "<br><br>-------------------------------------producto Nutricional# " . ($count_vec_ProdNutr + 1);
              echo "<br> subCadenaPresProdNutr: " . $subCadenaPresProdNutr;

              //ConOrden
              $ConOrden_busc_ini = '"ConOrden":';
              $ConOrden_busc_fin = ',"TipoPrest"';
              $cadena_ConOrden = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_ConOrden, $ConOrden_busc_ini) + strlen($ConOrden_busc_ini);
              $posPresFinal = strpos($cadena_ConOrden, $ConOrden_busc_fin);
              $ConOrden = substr($cadena_ConOrden, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> ConOrden: " . $ConOrden;
              //TipoPrest
              $TipoPrest_busc_ini = '"TipoPrest":';
              $TipoPrest_busc_fin = ',"CausaS1"';
              $cadena_TipoPrest = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_TipoPrest, $TipoPrest_busc_ini) + strlen($TipoPrest_busc_ini);
              $posPresFinal = strpos($cadena_TipoPrest, $TipoPrest_busc_fin);
              $TipoPrest = substr($cadena_TipoPrest, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> TipoPrest: " . $TipoPrest;
              //CausaS1
              $CausaS1_busc_ini = '"CausaS1":';
              $CausaS1_busc_fin = ',"CausaS2"';
              $cadena_CausaS1 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CausaS1, $CausaS1_busc_ini) + strlen($CausaS1_busc_ini);
              $posPresFinal = strpos($cadena_CausaS1, $CausaS1_busc_fin);
              $CausaS1 = substr($cadena_CausaS1, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS1: " . $CausaS1;
              //CausaS2
              $CausaS2_busc_ini = '"CausaS2":';
              $CausaS2_busc_fin = ',"CausaS3"';
              $cadena_CausaS2 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CausaS2, $CausaS2_busc_ini) + strlen($CausaS2_busc_ini);
              $posPresFinal = strpos($cadena_CausaS2, $CausaS2_busc_fin);
              $CausaS2 = substr($cadena_CausaS2, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS2: " . $CausaS2;
              //CausaS3
              $CausaS3_busc_ini = '"CausaS3":';
              $CausaS3_busc_fin = ',"CausaS4"';
              $cadena_CausaS3 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CausaS3, $CausaS3_busc_ini) + strlen($CausaS3_busc_ini);
              $posPresFinal = strpos($cadena_CausaS3, $CausaS3_busc_fin);
              $CausaS3 = substr($cadena_CausaS3, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS3: " . $CausaS3;
              //CausaS4
              $CausaS4_busc_ini = '"CausaS4":';
              $CausaS4_busc_fin = ',"ProNutUtilizado"';
              $cadena_CausaS4 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CausaS4, $CausaS4_busc_ini) + strlen($CausaS4_busc_ini);
              $posPresFinal = strpos($cadena_CausaS4, $CausaS4_busc_fin);
              $CausaS4 = substr($cadena_CausaS4, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS4: " . $CausaS4;
              //ProNutUtilizado
              $ProNutUtilizado_busc_ini = '"ProNutUtilizado":';
              $ProNutUtilizado_busc_fin = ',"RznCausaS41"';
              $cadena_ProNutUtilizado = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_ProNutUtilizado, $ProNutUtilizado_busc_ini) + strlen($ProNutUtilizado_busc_ini);
              $posPresFinal = strpos($cadena_ProNutUtilizado, $ProNutUtilizado_busc_fin);
              $ProNutUtilizado = substr($cadena_ProNutUtilizado, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> ProNutUtilizado: " . $ProNutUtilizado;
              //RznCausaS41
              $RznCausaS41_busc_ini = '"RznCausaS41":';
              $RznCausaS41_busc_fin = ',"DescRzn41"';
              $cadena_RznCausaS41 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_RznCausaS41, $RznCausaS41_busc_ini) + strlen($RznCausaS41_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS41, $RznCausaS41_busc_fin);
              $RznCausaS41 = substr($cadena_RznCausaS41, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> RznCausaS41: " . $RznCausaS41;
              //DescRzn41
              $DescRzn41_busc_ini = '"DescRzn41":';
              $DescRzn41_busc_fin = ',"RznCausaS42"';
              $cadena_DescRzn41 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescRzn41, $DescRzn41_busc_ini) + strlen($DescRzn41_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn41, $DescRzn41_busc_fin);
              $DescRzn41 = substr($cadena_DescRzn41, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DescRzn41: " . $DescRzn41;
              //RznCausaS42
              $RznCausaS42_busc_ini = '"RznCausaS42":';
              $RznCausaS42_busc_fin = ',"DescRzn42"';
              $cadena_RznCausaS42 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_RznCausaS42, $RznCausaS42_busc_ini) + strlen($RznCausaS42_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS42, $RznCausaS42_busc_fin);
              $RznCausaS42 = substr($cadena_RznCausaS42, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> RznCausaS42: " . $RznCausaS42;
              //DescRzn42
              $DescRzn42_busc_ini = '"DescRzn42":';
              $DescRzn42_busc_fin = ',"CausaS5"';
              $cadena_DescRzn42 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescRzn42, $DescRzn42_busc_ini) + strlen($DescRzn42_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn42, $DescRzn42_busc_fin);
              $DescRzn42 = substr($cadena_DescRzn42, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DescRzn42: " . $DescRzn42;
              //CausaS5
              $CausaS5_busc_ini = '"CausaS5":';
              $CausaS5_busc_fin = ',"ProNutDescartado"';
              $cadena_CausaS5 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CausaS5, $CausaS5_busc_ini) + strlen($CausaS5_busc_ini);
              $posPresFinal = strpos($cadena_CausaS5, $CausaS5_busc_fin);
              $CausaS5 = substr($cadena_CausaS5, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS5: " . $CausaS5;
              //ProNutDescartado
              $ProNutDescartado_busc_ini = '"ProNutDescartado":';
              $ProNutDescartado_busc_fin = ',"RznCausaS51"';
              $cadena_ProNutDescartado = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_ProNutDescartado, $ProNutDescartado_busc_ini) + strlen($ProNutDescartado_busc_ini);
              $posPresFinal = strpos($cadena_ProNutDescartado, $ProNutDescartado_busc_fin);
              $ProNutDescartado = substr($cadena_ProNutDescartado, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> ProNutDescartado: " . $ProNutDescartado;
              //RznCausaS51
              $RznCausaS51_busc_ini = '"RznCausaS51":';
              $RznCausaS51_busc_fin = ',"DescRzn51"';
              $cadena_RznCausaS51 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_RznCausaS51, $RznCausaS51_busc_ini) + strlen($RznCausaS51_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS51, $RznCausaS51_busc_fin);
              $RznCausaS51 = substr($cadena_RznCausaS51, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> RznCausaS51: " . $RznCausaS51;
              //DescRzn51
              $DescRzn51_busc_ini = '"DescRzn51":';
              $DescRzn51_busc_fin = ',"RznCausaS52"';
              $cadena_DescRzn51 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescRzn51, $DescRzn51_busc_ini) + strlen($DescRzn51_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn51, $DescRzn51_busc_fin);
              $DescRzn51 = substr($cadena_DescRzn51, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DescRzn51: " . $DescRzn51;
              //RznCausaS52
              $RznCausaS52_busc_ini = '"RznCausaS52":';
              $RznCausaS52_busc_fin = ',"DescRzn52"';
              $cadena_RznCausaS52 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_RznCausaS52, $RznCausaS52_busc_ini) + strlen($RznCausaS52_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS52, $RznCausaS52_busc_fin);
              $RznCausaS52 = substr($cadena_RznCausaS52, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> RznCausaS52: " . $RznCausaS52;
              //DescRzn52
              $DescRzn52_busc_ini = '"DescRzn52":';
              $DescRzn52_busc_fin = ',"RznCausaS53"';
              $cadena_DescRzn52 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescRzn52, $DescRzn52_busc_ini) + strlen($DescRzn52_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn52, $DescRzn52_busc_fin);
              $DescRzn52 = substr($cadena_DescRzn52, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DescRzn52: " . $DescRzn52;
              //RznCausaS53
              $RznCausaS53_busc_ini = '"RznCausaS53":';
              $RznCausaS53_busc_fin = ',"DescRzn53"';
              $cadena_RznCausaS53 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_RznCausaS53, $RznCausaS53_busc_ini) + strlen($RznCausaS53_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS53, $RznCausaS53_busc_fin);
              $RznCausaS53 = substr($cadena_RznCausaS53, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> RznCausaS53: " . $RznCausaS53;
              //DescRzn53
              $DescRzn53_busc_ini = '"DescRzn53":';
              $DescRzn53_busc_fin = ',"RznCausaS54"';
              $cadena_DescRzn53 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescRzn53, $DescRzn53_busc_ini) + strlen($DescRzn53_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn53, $DescRzn53_busc_fin);
              $DescRzn53 = substr($cadena_DescRzn53, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DescRzn53: " . $DescRzn53;
              //RznCausaS54
              $RznCausaS54_busc_ini = '"RznCausaS54":';
              $RznCausaS54_busc_fin = ',"DescRzn54"';
              $cadena_RznCausaS54 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_RznCausaS54, $RznCausaS54_busc_ini) + strlen($RznCausaS54_busc_ini);
              $posPresFinal = strpos($cadena_RznCausaS54, $RznCausaS54_busc_fin);
              $RznCausaS54 = substr($cadena_RznCausaS54, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> RznCausaS54: " . $RznCausaS54;
              //DescRzn54
              $DescRzn54_busc_ini = '"DescRzn54":';
              $DescRzn54_busc_fin = ',"DXEnfHuer"';
              $cadena_DescRzn54 = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescRzn54, $DescRzn54_busc_ini) + strlen($DescRzn54_busc_ini);
              $posPresFinal = strpos($cadena_DescRzn54, $DescRzn54_busc_fin);
              $DescRzn54 = substr($cadena_DescRzn54, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DescRzn54: " . $DescRzn54;
              //DXEnfHuer
              $DXEnfHuer_busc_ini = '"DXEnfHuer":';
              $DXEnfHuer_busc_fin = ',"DXVIH"';
              $cadena_DXEnfHuer = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DXEnfHuer, $DXEnfHuer_busc_ini) + strlen($DXEnfHuer_busc_ini);
              $posPresFinal = strpos($cadena_DXEnfHuer, $DXEnfHuer_busc_fin);
              $DXEnfHuer = substr($cadena_DXEnfHuer, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DXEnfHuer: " . $DXEnfHuer;
              //DXVIH
              $DXVIH_busc_ini = '"DXVIH":';
              $DXVIH_busc_fin = ',"DXCaPal"';
              $cadena_DXVIH = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DXVIH, $DXVIH_busc_ini) + strlen($DXVIH_busc_ini);
              $posPresFinal = strpos($cadena_DXVIH, $DXVIH_busc_fin);
              $DXVIH = substr($cadena_DXVIH, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DXVIH: " . $DXVIH;
              //DXCaPal
              $DXCaPal_busc_ini = '"DXCaPal":';
              $DXCaPal_busc_fin = ',"DXEnfRCEV"';
              $cadena_DXCaPal = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DXCaPal, $DXCaPal_busc_ini) + strlen($DXCaPal_busc_ini);
              $posPresFinal = strpos($cadena_DXCaPal, $DXCaPal_busc_fin);
              $DXCaPal = substr($cadena_DXCaPal, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DXCaPal: " . $DXCaPal;
              //DXEnfRCEV
              $DXEnfRCEV_busc_ini = '"DXEnfRCEV":';
              $DXEnfRCEV_busc_fin = ',"DXDesPro"';
              $cadena_DXEnfRCEV = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DXEnfRCEV, $DXEnfRCEV_busc_ini) + strlen($DXEnfRCEV_busc_ini);
              $posPresFinal = strpos($cadena_DXEnfRCEV, $DXEnfRCEV_busc_fin);
              $DXEnfRCEV = substr($cadena_DXEnfRCEV, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DXEnfRCEV: " . $DXEnfRCEV;
              //DXDesPro
              $DXDesPro_busc_ini = '"DXDesPro":';
              $DXDesPro_busc_fin = ',"TippProNut"';
              $cadena_DXDesPro = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DXDesPro, $DXDesPro_busc_ini) + strlen($DXDesPro_busc_ini);
              $posPresFinal = strpos($cadena_DXDesPro, $DXDesPro_busc_fin);
              $DXDesPro = substr($cadena_DXDesPro, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DXDesPro: " . $DXDesPro;
              //TippProNut
              $TippProNut_busc_ini = '"TippProNut":';
              $TippProNut_busc_fin = ',"DescProdNutr"';
              $cadena_TippProNut = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_TippProNut, $TippProNut_busc_ini) + strlen($TippProNut_busc_ini);
              $posPresFinal = strpos($cadena_TippProNut, $TippProNut_busc_fin);
              $TippProNut = substr($cadena_TippProNut, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> TippProNut: " . $TippProNut;
              //DescProdNutr
              $DescProdNutr_busc_ini = '"DescProdNutr":';
              $DescProdNutr_busc_fin = ',"CodForma"';
              $cadena_DescProdNutr = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DescProdNutr, $DescProdNutr_busc_ini) + strlen($DescProdNutr_busc_ini);
              $posPresFinal = strpos($cadena_DescProdNutr, $DescProdNutr_busc_fin);
              $DescProdNutr = substr($cadena_DescProdNutr, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DescProdNutr: " . $DescProdNutr;
              //CodForma
              $CodForma_busc_ini = '"CodForma":';
              $CodForma_busc_fin = ',"CodViaAdmon"';
              $cadena_CodForma = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CodForma, $CodForma_busc_ini) + strlen($CodForma_busc_ini);
              $posPresFinal = strpos($cadena_CodForma, $CodForma_busc_fin);
              $CodForma = substr($cadena_CodForma, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodForma: " . $CodForma;
              //CodViaAdmon
              $CodViaAdmon_busc_ini = '"CodViaAdmon":';
              $CodViaAdmon_busc_fin = ',"JustNoPBS"';
              $cadena_CodViaAdmon = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CodViaAdmon, $CodViaAdmon_busc_ini) + strlen($CodViaAdmon_busc_ini);
              $posPresFinal = strpos($cadena_CodViaAdmon, $CodViaAdmon_busc_fin);
              $CodViaAdmon = substr($cadena_CodViaAdmon, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodViaAdmon: " . $CodViaAdmon;
              //JustNoPBS
              $JustNoPBS_busc_ini = '"JustNoPBS":';
              $JustNoPBS_busc_fin = ',"Dosis"';
              $cadena_JustNoPBS = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_JustNoPBS, $JustNoPBS_busc_ini) + strlen($JustNoPBS_busc_ini);
              $posPresFinal = strpos($cadena_JustNoPBS, $JustNoPBS_busc_fin);
              $JustNoPBS = substr($cadena_JustNoPBS, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> JustNoPBS: " . $JustNoPBS;
              //Dosis
              $Dosis_busc_ini = '"Dosis":';
              $Dosis_busc_fin = ',"DosisUM"';
              $cadena_Dosis = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_Dosis, $Dosis_busc_ini) + strlen($Dosis_busc_ini);
              $posPresFinal = strpos($cadena_Dosis, $Dosis_busc_fin);
              $Dosis = substr($cadena_Dosis, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> Dosis: " . $Dosis;
              //DosisUM
              $DosisUM_busc_ini = '"DosisUM":';
              $DosisUM_busc_fin = ',"NoFAdmon"';
              $cadena_DosisUM = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DosisUM, $DosisUM_busc_ini) + strlen($DosisUM_busc_ini);
              $posPresFinal = strpos($cadena_DosisUM, $DosisUM_busc_fin);
              $DosisUM = substr($cadena_DosisUM, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DosisUM: " . $DosisUM;
              //NoFAdmon
              $NoFAdmon_busc_ini = '"NoFAdmon":';
              $NoFAdmon_busc_fin = ',"CodFreAdmon"';
              $cadena_NoFAdmon = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_NoFAdmon, $NoFAdmon_busc_ini) + strlen($NoFAdmon_busc_ini);
              $posPresFinal = strpos($cadena_NoFAdmon, $NoFAdmon_busc_fin);
              $NoFAdmon = substr($cadena_NoFAdmon, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> NoFAdmon: " . $NoFAdmon;
              //CodFreAdmon
              $CodFreAdmon_busc_ini = '"CodFreAdmon":';
              $CodFreAdmon_busc_fin = ',"IndEsp"';
              $cadena_CodFreAdmon = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CodFreAdmon, $CodFreAdmon_busc_ini) + strlen($CodFreAdmon_busc_ini);
              $posPresFinal = strpos($cadena_CodFreAdmon, $CodFreAdmon_busc_fin);
              $CodFreAdmon = substr($cadena_CodFreAdmon, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodFreAdmon: " . $CodFreAdmon;
              //IndEsp
              $IndEsp_busc_ini = '"IndEsp":';
              $IndEsp_busc_fin = ',"CanTrat"';
              $cadena_IndEsp = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_IndEsp, $IndEsp_busc_ini) + strlen($IndEsp_busc_ini);
              $posPresFinal = strpos($cadena_IndEsp, $IndEsp_busc_fin);
              $IndEsp = substr($cadena_IndEsp, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> IndEsp: " . $IndEsp;
              //CanTrat
              $CanTrat_busc_ini = '"CanTrat":';
              $CanTrat_busc_fin = ',"DurTrat"';
              $cadena_CanTrat = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CanTrat, $CanTrat_busc_ini) + strlen($CanTrat_busc_ini);
              $posPresFinal = strpos($cadena_CanTrat, $CanTrat_busc_fin);
              $CanTrat = substr($cadena_CanTrat, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CanTrat: " . $CanTrat;
              //DurTrat
              $DurTrat_busc_ini = '"DurTrat":';
              $DurTrat_busc_fin = ',"CantTotalF"';
              $cadena_DurTrat = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_DurTrat, $DurTrat_busc_ini) + strlen($DurTrat_busc_ini);
              $posPresFinal = strpos($cadena_DurTrat, $DurTrat_busc_fin);
              $DurTrat = substr($cadena_DurTrat, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DurTrat: " . $DurTrat;
              //CantTotalF
              $CantTotalF_busc_ini = '"CantTotalF":';
              $CantTotalF_busc_fin = ',"UFCantTotal"';
              $cadena_CantTotalF = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_CantTotalF, $CantTotalF_busc_ini) + strlen($CantTotalF_busc_ini);
              $posPresFinal = strpos($cadena_CantTotalF, $CantTotalF_busc_fin);
              $CantTotalF = substr($cadena_CantTotalF, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CantTotalF: " . $CantTotalF;
              //UFCantTotal
              $UFCantTotal_busc_ini = '"UFCantTotal":';
              $UFCantTotal_busc_fin = ',"IndRec"';
              $cadena_UFCantTotal = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_UFCantTotal, $UFCantTotal_busc_ini) + strlen($UFCantTotal_busc_ini);
              $posPresFinal = strpos($cadena_UFCantTotal, $UFCantTotal_busc_fin);
              $UFCantTotal = substr($cadena_UFCantTotal, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> UFCantTotal: " . $UFCantTotal;
              //IndRec
              $IndRec_busc_ini = '"IndRec":';
              $IndRec_busc_fin = ',"NoPrescAso"';
              $cadena_IndRec = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_IndRec, $IndRec_busc_ini) + strlen($IndRec_busc_ini);
              $posPresFinal = strpos($cadena_IndRec, $IndRec_busc_fin);
              $IndRec = substr($cadena_IndRec, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> IndRec: " . $IndRec;
              //NoPrescAso
              $NoPrescAso_busc_ini = '"NoPrescAso":';
              $NoPrescAso_busc_fin = ',"EstJM"';
              $cadena_NoPrescAso = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_NoPrescAso, $NoPrescAso_busc_ini) + strlen($NoPrescAso_busc_ini);
              $posPresFinal = strpos($cadena_NoPrescAso, $NoPrescAso_busc_fin);
              $NoPrescAso = substr($cadena_NoPrescAso, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> NoPrescAso: " . $NoPrescAso;
              //EstJM
              $EstJM_busc_ini = '"EstJM":';
              $EstJM_busc_fin = '}';
              $cadena_EstJM = $subCadenaPresProdNutr;
              $posPresInicial = strpos($cadena_EstJM, $EstJM_busc_ini) + strlen($EstJM_busc_ini);
              $posPresFinal = strpos($cadena_EstJM, $EstJM_busc_fin);
              $EstJM = substr($cadena_EstJM, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> EstJM: " . $EstJM;


              $ConOrden;
              $TipoPrest;
              $CausaS1;
              $CausaS2;
              $CausaS3;
              $CausaS4;
              $ProNutUtilizado;
              $RznCausaS41;
              $DescRzn41;
              $RznCausaS42;
              $DescRzn42;
              $CausaS5;
              $ProNutDescartado;
              $RznCausaS51;
              $DescRzn51;
              $RznCausaS52;
              $DescRzn52;
              $RznCausaS53;
              $DescRzn53;
              $RznCausaS54;
              $DescRzn54;
              $DXEnfHuer;
              $DXVIH;
              $DXCaPal;
              $DXEnfRCEV;
              $DXDesPro;
              $TippProNut;
              $DescProdNutr;
              $CodForma;
              $CodViaAdmon;
              $JustNoPBS;
              $Dosis;
              $DosisUM;
              $NoFAdmon;
              $CodFreAdmon;
              $IndEsp;
              $CanTrat;
              $DurTrat;
              $CantTotalF;
              $UFCantTotal;
              $IndRec;
              $NoPrescAso;
              $EstJM;
            }
          }
          unset($vector_productosnutricionales); //Vaciar el vector

          ////////////////////////////////////////////////////////////////////////////////////productosnutricionales (Fin)

          //////////////////////////////////////////////////////////////////////////////serviciosComplementarios (Inicio)
          echo "<br><br>-------------------------------------------------------------------------serviciosComplementarios";

          //Obtener cadena general de serviciosComplementarios
          $cad_pres_busc_ini = '],"serviciosComplementarios"';
         // $cad_pres_busc_fin = '';
          $cadena_presc = $array[$i];
          //echo "<br> cadena_presc: " . $cadena_presc . "<br>";
          $posPresInicial = strpos($cadena_presc, $cad_pres_busc_ini);
          $posPresFinal = strlen($cadena_presc);//strpos($cadena_presc, $cad_pres_busc_fin);
          $subCadenaPresServComp = substr($cadena_presc, $posPresInicial, $posPresFinal - $posPresInicial);
          echo "<br> subCadenaPresServComp: " . $subCadenaPresServComp . "<br>";
          /****************************************************************************************************** */
          ////Crear un ciclo con el while para recorrer todos los serviciosComplementarios////////////////

          $cadenaServComp = $subCadenaPresServComp;
          // $cadenaServComp = str_replace('},"serviciosComplementarios":[', 'inicio', $cadenaServComp);
          // $cadenaServComp = str_replace('},{"ConOrden"', '},inicio{"ConOrden"', $cadenaServComp);
          $subcadenaServCompBuscadaInicial   = '{"ConOrden"';
          //echo "<br> cadenaServComp: " . $cadenaServComp . "<br>";
          $posInicial = strpos($cadenaServComp, $subcadenaServCompBuscadaInicial);
          $count_report = 0;

          $vector_serviciosComplementarios[0] = "";
          // Notese el uso de ===. Puesto que == simple no funcionará como se espera
          while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada

            ////////////////////////////////////serviciosComplementarios////////////////////////////////////////
            ///////////////////////Se separan Cada uno de los serviciosComplementarios (Inicio)/////////////////
            //////////////////////////////////serviciosComplementarios//////////////////////////////////////////

            $subcadenaServCompBuscadaInicial   = '{"ConOrden"';
            // echo "<br> sub cadenaServComp Buscada Inicial: " . $subcadenaServCompBuscadaInicial;
            $subcadenaServCompBuscadaFinal   = '}';
            //echo "<br> sub cadenaServComp Buscada Final: " . $subcadenaServCompBuscadaFinal;
            $posInicial = strpos($cadenaServComp, $subcadenaServCompBuscadaInicial);
            // echo "<br> pos Inicial: " . $posInicial;
            $posFinal = strpos($cadenaServComp, $subcadenaServCompBuscadaFinal) + 2;
            //echo "<br> pos Final: " . $posFinal;
            if ($posFinal == "") {
              $posFinal = strlen($cadenaServComp) - 2; //Sera igual a la última posición de la cadenaServComp
              // echo "<br> pos Final no encontrado: " . $posFinal;
            }
            $subcadenaServCompFinal = substr($cadenaServComp, $posInicial, $posFinal - $posInicial);
            echo "<br> Sub cadenaServComp: " . $subcadenaServCompFinal;
            if ($subcadenaServCompFinal != '[' && $subcadenaServCompFinal != '') {
              //$vector_serviciosComplementarios[$count_report] = str_replace('inicio', '},"serviciosComplementarios":[', $subcadenaServCompFinal);
              $vector_serviciosComplementarios[$count_report] =  $subcadenaServCompFinal;
              $count_report++;
            }
            $cadenaServComp = str_replace($subcadenaServCompFinal, "", $cadenaServComp);
          }
          /****************************************************************************************************** */


          /*******************Leer cada uno de los serviciosComplementarios(Inicio)********************************/
          $longitud_vec_ServComp = count($vector_serviciosComplementarios);
          for ($count_vec_ServComp = 0; $count_vec_ServComp < $longitud_vec_ServComp; $count_vec_ServComp++) {
            //echo "<br> Cadena#" . $count_vec_ServComp . ": " . $vector_serviciosComplementarios[$count_vec_ServComp];
            $subCadenaPresServComp = $vector_serviciosComplementarios[$count_vec_ServComp];


            if (strlen($subCadenaPresServComp) > 5) {//Si la cadena tiene mas de 5 caracteres entonces se entiende que hay datos para leer
              echo "<br><br>-------------------------------------servicio Complementario # " . ($count_vec_ServComp + 1);
              echo "<br> subCadenaPresServComp: " . $subCadenaPresServComp;

              //ConOrden
              $ConOrden_busc_ini = '"ConOrden":';
              $ConOrden_busc_fin = ',"TipoPrest"';
              $cadena_ConOrden = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_ConOrden, $ConOrden_busc_ini) + strlen($ConOrden_busc_ini);
              $posPresFinal = strpos($cadena_ConOrden, $ConOrden_busc_fin);
              $ConOrden = substr($cadena_ConOrden, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> ConOrden: " . $ConOrden;
              //TipoPrest
              $TipoPrest_busc_ini = '"TipoPrest":';
              $TipoPrest_busc_fin = ',"CausaS1"';
              $cadena_TipoPrest = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_TipoPrest, $TipoPrest_busc_ini) + strlen($TipoPrest_busc_ini);
              $posPresFinal = strpos($cadena_TipoPrest, $TipoPrest_busc_fin);
              $TipoPrest = substr($cadena_TipoPrest, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> TipoPrest: " . $TipoPrest;
              //CausaS1
              $CausaS1_busc_ini = '"CausaS1":';
              $CausaS1_busc_fin = ',"CausaS2"';
              $cadena_CausaS1 = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CausaS1, $CausaS1_busc_ini) + strlen($CausaS1_busc_ini);
              $posPresFinal = strpos($cadena_CausaS1, $CausaS1_busc_fin);
              $CausaS1 = substr($cadena_CausaS1, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS1: " . $CausaS1;
              //CausaS2
              $CausaS2_busc_ini = '"CausaS2":';
              $CausaS2_busc_fin = ',"CausaS3"';
              $cadena_CausaS2 = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CausaS2, $CausaS2_busc_ini) + strlen($CausaS2_busc_ini);
              $posPresFinal = strpos($cadena_CausaS2, $CausaS2_busc_fin);
              $CausaS2 = substr($cadena_CausaS2, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS2: " . $CausaS2;
              //CausaS3
              $CausaS3_busc_ini = '"CausaS3":';
              $CausaS3_busc_fin = ',"CausaS4"';
              $cadena_CausaS3 = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CausaS3, $CausaS3_busc_ini) + strlen($CausaS3_busc_ini);
              $posPresFinal = strpos($cadena_CausaS3, $CausaS3_busc_fin);
              $CausaS3 = substr($cadena_CausaS3, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS3: " . $CausaS3;
              //CausaS4
              $CausaS4_busc_ini = '"CausaS4":';
              $CausaS4_busc_fin = ',"DescCausaS4"';
              $cadena_CausaS4 = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CausaS4, $CausaS4_busc_ini) + strlen($CausaS4_busc_ini);
              $posPresFinal = strpos($cadena_CausaS4, $CausaS4_busc_fin);
              $CausaS4 = substr($cadena_CausaS4, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS4: " . $CausaS4;
              //DescCausaS4
              $DescCausaS4_busc_ini = '"DescCausaS4":';
              $DescCausaS4_busc_fin = ',"CausaS5"';
              $cadena_DescCausaS4 = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_DescCausaS4, $DescCausaS4_busc_ini) + strlen($DescCausaS4_busc_ini);
              $posPresFinal = strpos($cadena_DescCausaS4, $DescCausaS4_busc_fin);
              $DescCausaS4 = substr($cadena_DescCausaS4, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DescCausaS4: " . $DescCausaS4;
              //CausaS5
              $CausaS5_busc_ini = '"CausaS5":';
              $CausaS5_busc_fin = ',"CodSerComp"';
              $cadena_CausaS5 = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CausaS5, $CausaS5_busc_ini) + strlen($CausaS5_busc_ini);
              $posPresFinal = strpos($cadena_CausaS5, $CausaS5_busc_fin);
              $CausaS5 = substr($cadena_CausaS5, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CausaS5: " . $CausaS5;
              //CodSerComp
              $CodSerComp_busc_ini = '"CodSerComp":';
              $CodSerComp_busc_fin = ',"DescSerComp"';
              $cadena_CodSerComp = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CodSerComp, $CodSerComp_busc_ini) + strlen($CodSerComp_busc_ini);
              $posPresFinal = strpos($cadena_CodSerComp, $CodSerComp_busc_fin);
              $CodSerComp = substr($cadena_CodSerComp, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodSerComp: " . $CodSerComp;
              //DescSerComp
              $DescSerComp_busc_ini = '"DescSerComp":';
              $DescSerComp_busc_fin = ',"CanForm"';
              $cadena_DescSerComp = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_DescSerComp, $DescSerComp_busc_ini) + strlen($DescSerComp_busc_ini);
              $posPresFinal = strpos($cadena_DescSerComp, $DescSerComp_busc_fin);
              $DescSerComp = substr($cadena_DescSerComp, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> DescSerComp: " . $DescSerComp;
              //CanForm
              $CanForm_busc_ini = '"CanForm":';
              $CanForm_busc_fin = ',"CadaFreUso"';
              $cadena_CanForm = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CanForm, $CanForm_busc_ini) + strlen($CanForm_busc_ini);
              $posPresFinal = strpos($cadena_CanForm, $CanForm_busc_fin);
              $CanForm = substr($cadena_CanForm, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CanForm: " . $CanForm;
              //CadaFreUso
              $CadaFreUso_busc_ini = '"CadaFreUso":';
              $CadaFreUso_busc_fin = ',"CodFreUso"';
              $cadena_CadaFreUso = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CadaFreUso, $CadaFreUso_busc_ini) + strlen($CadaFreUso_busc_ini);
              $posPresFinal = strpos($cadena_CadaFreUso, $CadaFreUso_busc_fin);
              $CadaFreUso = substr($cadena_CadaFreUso, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CadaFreUso: " . $CadaFreUso;
              //CodFreUso
              $CodFreUso_busc_ini = '"CodFreUso":';
              $CodFreUso_busc_fin = ',"Cant"';
              $cadena_CodFreUso = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CodFreUso, $CodFreUso_busc_ini) + strlen($CodFreUso_busc_ini);
              $posPresFinal = strpos($cadena_CodFreUso, $CodFreUso_busc_fin);
              $CodFreUso = substr($cadena_CodFreUso, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodFreUso: " . $CodFreUso;
              //Cant
              $Cant_busc_ini = '"Cant":';
              $Cant_busc_fin = ',"CantTotal"';
              $cadena_Cant = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_Cant, $Cant_busc_ini) + strlen($Cant_busc_ini);
              $posPresFinal = strpos($cadena_Cant, $Cant_busc_fin);
              $Cant = substr($cadena_Cant, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> Cant: " . $Cant;
              //CantTotal
              $CantTotal_busc_ini = '"CantTotal":';
              $CantTotal_busc_fin = ',"CodPerDurTrat"';
              $cadena_CantTotal = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CantTotal, $CantTotal_busc_ini) + strlen($CantTotal_busc_ini);
              $posPresFinal = strpos($cadena_CantTotal, $CantTotal_busc_fin);
              $CantTotal = substr($cadena_CantTotal, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CantTotal: " . $CantTotal;
              //CodPerDurTrat
              $CodPerDurTrat_busc_ini = '"CodPerDurTrat":';
              $CodPerDurTrat_busc_fin = ',"TipoTrans"';
              $cadena_CodPerDurTrat = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CodPerDurTrat, $CodPerDurTrat_busc_ini) + strlen($CodPerDurTrat_busc_ini);
              $posPresFinal = strpos($cadena_CodPerDurTrat, $CodPerDurTrat_busc_fin);
              $CodPerDurTrat = substr($cadena_CodPerDurTrat, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodPerDurTrat: " . $CodPerDurTrat;
              //TipoTrans
              $TipoTrans_busc_ini = '"TipoTrans":';
              $TipoTrans_busc_fin = ',"ReqAcom"';
              $cadena_TipoTrans = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_TipoTrans, $TipoTrans_busc_ini) + strlen($TipoTrans_busc_ini);
              $posPresFinal = strpos($cadena_TipoTrans, $TipoTrans_busc_fin);
              $TipoTrans = substr($cadena_TipoTrans, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> TipoTrans: " . $TipoTrans;
              //ReqAcom
              $ReqAcom_busc_ini = '"ReqAcom":';
              $ReqAcom_busc_fin = ',"TipoIDAcomAlb"';
              $cadena_ReqAcom = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_ReqAcom, $ReqAcom_busc_ini) + strlen($ReqAcom_busc_ini);
              $posPresFinal = strpos($cadena_ReqAcom, $ReqAcom_busc_fin);
              $ReqAcom = substr($cadena_ReqAcom, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> ReqAcom: " . $ReqAcom;
              //TipoIDAcomAlb
              $TipoIDAcomAlb_busc_ini = '"TipoIDAcomAlb":';
              $TipoIDAcomAlb_busc_fin = ',"NroIDAcomAlb"';
              $cadena_TipoIDAcomAlb = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_TipoIDAcomAlb, $TipoIDAcomAlb_busc_ini) + strlen($TipoIDAcomAlb_busc_ini);
              $posPresFinal = strpos($cadena_TipoIDAcomAlb, $TipoIDAcomAlb_busc_fin);
              $TipoIDAcomAlb = substr($cadena_TipoIDAcomAlb, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> TipoIDAcomAlb: " . $TipoIDAcomAlb;
              //NroIDAcomAlb
              $NroIDAcomAlb_busc_ini = '"NroIDAcomAlb":';
              $NroIDAcomAlb_busc_fin = ',"ParentAcomAlb"';
              $cadena_NroIDAcomAlb = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_NroIDAcomAlb, $NroIDAcomAlb_busc_ini) + strlen($NroIDAcomAlb_busc_ini);
              $posPresFinal = strpos($cadena_NroIDAcomAlb, $NroIDAcomAlb_busc_fin);
              $NroIDAcomAlb = substr($cadena_NroIDAcomAlb, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> NroIDAcomAlb: " . $NroIDAcomAlb;
              //ParentAcomAlb
              $ParentAcomAlb_busc_ini = '"ParentAcomAlb":';
              $ParentAcomAlb_busc_fin = ',"NombAlb"';
              $cadena_ParentAcomAlb = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_ParentAcomAlb, $ParentAcomAlb_busc_ini) + strlen($ParentAcomAlb_busc_ini);
              $posPresFinal = strpos($cadena_ParentAcomAlb, $ParentAcomAlb_busc_fin);
              $ParentAcomAlb = substr($cadena_ParentAcomAlb, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> ParentAcomAlb: " . $ParentAcomAlb;
              //NombAlb
              $NombAlb_busc_ini = '"NombAlb":';
              $NombAlb_busc_fin = ',"CodMunOriAlb"';
              $cadena_NombAlb = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_NombAlb, $NombAlb_busc_ini) + strlen($NombAlb_busc_ini);
              $posPresFinal = strpos($cadena_NombAlb, $NombAlb_busc_fin);
              $NombAlb = substr($cadena_NombAlb, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> NombAlb: " . $NombAlb;
              //CodMunOriAlb
              $CodMunOriAlb_busc_ini = '"CodMunOriAlb":';
              $CodMunOriAlb_busc_fin = ',"CodMunDesAlb"';
              $cadena_CodMunOriAlb = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CodMunOriAlb, $CodMunOriAlb_busc_ini) + strlen($CodMunOriAlb_busc_ini);
              $posPresFinal = strpos($cadena_CodMunOriAlb, $CodMunOriAlb_busc_fin);
              $CodMunOriAlb = substr($cadena_CodMunOriAlb, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodMunOriAlb: " . $CodMunOriAlb;
              //CodMunDesAlb
              $CodMunDesAlb_busc_ini = '"CodMunDesAlb":';
              $CodMunDesAlb_busc_fin = ',"JustNoPBS"';
              $cadena_CodMunDesAlb = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_CodMunDesAlb, $CodMunDesAlb_busc_ini) + strlen($CodMunDesAlb_busc_ini);
              $posPresFinal = strpos($cadena_CodMunDesAlb, $CodMunDesAlb_busc_fin);
              $CodMunDesAlb = substr($cadena_CodMunDesAlb, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> CodMunDesAlb: " . $CodMunDesAlb;
              //JustNoPBS
              $JustNoPBS_busc_ini = '"JustNoPBS":';
              $JustNoPBS_busc_fin = ',"IndRec"';
              $cadena_JustNoPBS = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_JustNoPBS, $JustNoPBS_busc_ini) + strlen($JustNoPBS_busc_ini);
              $posPresFinal = strpos($cadena_JustNoPBS, $JustNoPBS_busc_fin);
              $JustNoPBS = substr($cadena_JustNoPBS, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> JustNoPBS: " . $JustNoPBS;
              //IndRec
              $IndRec_busc_ini = '"IndRec":';
              $IndRec_busc_fin = ',"EstJM"';
              $cadena_IndRec = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_IndRec, $IndRec_busc_ini) + strlen($IndRec_busc_ini);
              $posPresFinal = strpos($cadena_IndRec, $IndRec_busc_fin);
              $IndRec = substr($cadena_IndRec, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> IndRec: " . $IndRec;
              //EstJM
              $EstJM_busc_ini = '"EstJM":';
              $EstJM_busc_fin = '}';
              $cadena_EstJM = $subCadenaPresServComp;
              $posPresInicial = strpos($cadena_EstJM, $EstJM_busc_ini) + strlen($EstJM_busc_ini);
              $posPresFinal = strpos($cadena_EstJM, $EstJM_busc_fin);
              $EstJM = substr($cadena_EstJM, $posPresInicial, $posPresFinal - $posPresInicial);
              echo "<br> EstJM: " . $EstJM;

              $ConOrden;
              $TipoPrest;
              $CausaS1;
              $CausaS2;
              $CausaS3;
              $CausaS4;
              $DescCausaS4;
              $CausaS5;
              $CodSerComp;
              $DescSerComp;
              $CanForm;
              $CadaFreUso;
              $CodFreUso;
              $Cant;
              $CantTotal;
              $CodPerDurTrat;
              $TipoTrans;
              $ReqAcom;
              $TipoIDAcomAlb;
              $NroIDAcomAlb;
              $ParentAcomAlb;
              $NombAlb;
              $CodMunOriAlb;
              $CodMunDesAlb;
              $JustNoPBS;
              $IndRec;
              $EstJM;
            }
          }
          unset($vector_serviciosComplementarios); //Vaciar el vector
          ///////////////////////////////////////////////////////////////////////////////////serviciosComplementarios (Fin)

          //Obtener cadena general de prescripcion
          //echo "<br> Sub Cadena: " . $array[$i];
          echo "<br><h1 style='color:#FF0000'>-----------------------------------------------------------------------------------------------------------------------------------</h1>";
        }
        echo "Cantidad de prescripciones: " . $longitud;

        /*
    $sql="INSERT INTO reportesws (serv_id,tire_id,repo_periodo, repo_json) VALUES (".$servicio_id.",".$tipo_id.",'20".$periodo_conteo."', '".$json."');";
  if (mysqli_query($conn, $sql)) {
        $periodos_cargados=$periodos_cargados."20".$periodo_conteo."<br>";
        $periodos_cargados_conteo=$periodos_cargados_conteo+1;
        $sql="delete from log_errores where serv_id=".$servicio_id." and tire_id=".$tipo_id." and  logErr_periodo = '20".$periodo_conteo."'";
        mysqli_query($conn, $sql);
  }else{
    $peri_error= $peri_error."20".$periodo_conteo."(Error al insertar el registro)<br>";
    $peri_error_conteo=$peri_error_conteo+1;
    $sql="INSERT INTO log_errores(serv_id, tire_id, logErr_periodo, log_Err_nombre, logErr_descripcion) 
    VALUES (".$servicio_id.",".$tipo_id.",'20".$periodo_conteo."', 'WSPRESCRIPCION: Error al insertar el registro','No se cargó ".$serv_nombre." ".$tipo_get." 20".$periodo_conteo."')";
    mysqli_query($conn, $sql);
    
/////////////////////////////////////////////////////////////////////////////////////insertar en el log de errores

  }
  */
      }
    }
  }
}
mysqli_close($conn);
echo "<br><br>";
/*echo "<h3>Dias cargados</h3> <br> cantidad: ".$periodos_cargados_conteo."<br>".$periodos_cargados."<br>";
echo "<h3>Dias no cargados</h3> <br>cantidad: ".$peri_error_conteo."<br>".$peri_error;*/
echo "<br> restante de la cadena: " . $json;


function obtener_dato_json($parametro_inicial, $parametro_final, $cadena, $nombre_dato)
{
  $posicion_inicial = strpos($cadena, $parametro_inicial) + strlen($parametro_inicial);
  $posicion_final = strpos($cadena, $parametro_final);
  $dato = substr($cadena, $posicion_inicial, $posicion_final - $posicion_inicial);
  echo   "<br> " . $nombre_dato . ": " . $dato;
  //return "<br>. " . $nombre_dato . ": " . $dato . "<br>";
}


function obtener_columnas_json($vector_parametros)
{
  $subCadenaPresMedi = $vector_parametros[0];
  $subcadena_inicial_a_quitar = $vector_parametros[1];
  $subcadena_final_a_quitar = $vector_parametros[2];
  $subCadenaBuscadaInicial = $vector_parametros[3];
  $subCadenaBuscadaFinal = $vector_parametros[4];

  $cadena_nombres_pres_medi = $subCadenaPresMedi;
  $cadena_nombres_pres_medi = str_replace($subcadena_inicial_a_quitar, ",", $cadena_nombres_pres_medi);

  $pos_inicial_nomb = strpos($cadena_nombres_pres_medi, $subcadena_final_a_quitar);
  $cadena_a_borrar = substr($cadena_nombres_pres_medi, $pos_inicial_nomb, strlen($cadena_nombres_pres_medi));
  $cadena_nombres_pres_medi = str_replace($cadena_a_borrar, "", $cadena_nombres_pres_medi);
  //echo "<br> dato: ".$cadena_nombres_pres_medi ."<br>";
  // echo "cadena_nombres_pres_medi: " . $cadena_nombres_pres_medi . "<br>";
  // $subCadenaBuscadaInicial   = ',"';
  $posInicial = strpos($cadena_nombres_pres_medi, $subCadenaBuscadaInicial);
  $count_vector_medi = 0;
  $count = 0;
  $vector_subCadenaPresMedi[0] = "";
  while ($posInicial !== false) { //Mientras que se encuentre la palabra buscada
    $count = $count + 1;
    // $subCadenaBuscadaInicial   = ',"';
    //$subCadenaBuscadaFinal   = '":';
    //$cadena_nombres_pres_medi = $subCadenaPresMedi;
    $posInicial = strpos($cadena_nombres_pres_medi, $subCadenaBuscadaInicial);
    $posFinal = strpos($cadena_nombres_pres_medi, $subCadenaBuscadaFinal);
    // echo "<br> posFinal# " . $count_vector_medi . "posFinal: " . $posFinal . "<br>";
    if ($posFinal != "") {
      $subCadenaFinal = substr($cadena_nombres_pres_medi, $posInicial, $posFinal + 2 - $posInicial);
      $vector_subCadenaPresMedi[$count_vector_medi] = $subCadenaFinal;
      //Se quitan los caracteres que sobran en el nombre de los campos
      $vector_subCadenaPresMedi[$count_vector_medi] = str_replace($subCadenaBuscadaInicial, "", $vector_subCadenaPresMedi[$count_vector_medi]);
      $vector_subCadenaPresMedi[$count_vector_medi] = str_replace($subCadenaBuscadaFinal, "", $vector_subCadenaPresMedi[$count_vector_medi]);
      $count_vector_medi++;
    }

    $cadena_nombres_pres_medi = str_replace($subCadenaFinal, "", $cadena_nombres_pres_medi);
    // echo "<br> sub cadena dato# " . $count_vector_medi . "sub_cadena_dato: " . $subCadenaFinal . "<br>";
    //echo "<br> cadena dato# " . $count_vector_medi . "cadena_dato: " . $cadena_nombres_pres_medi . "<br>";
  }
  //saco el numero de elementos

  return $vector_subCadenaPresMedi;
  // echo "<br>vector_subCadenaPresMedi: " . $longitud_vector_subCadenaPresMedi;
}
