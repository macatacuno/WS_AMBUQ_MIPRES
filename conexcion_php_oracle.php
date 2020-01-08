 <?php
  $conn = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');

  /////Insertar prescripcion (Inicio)
  $sql_exc = "INSERT INTO WEBSERV_PRES_PRES 
     (ID_PRES,NOPRESCRIPCION, FPRESCRIPCION,HPRESCRIPCION,CODHABIPS,TIPOIDIPS,NROIDIPS,CODDANEMUNIPS,DIRSEDEIPS,TELSEDEIPS,TIPOIDPROF,NUMIDPROF,PNPROFS,SNPROFS,PAPROFS,SAPROFS,REGPROFS,TIPOIDPACIENTE,NROIDPACIENTE,PNPACIENTE,SNPACIENTE,PAPACIENTE,SAPACIENTE,CODAMBATE,REFAMBATE,ENFHUERFANA,CODENFHUERFANA,ENFHUERFANADX,CODDXPPAL,CODDXREL1,CODDXREL2,SOPNUTRICIONAL,CODEPS,TIPOIDMADREPACIENTE,NROIDMADREPACIENTE,TIPOTRANSC,TIPOIDDONANTEVIVO,NROIDDONANTEVIVO,ESTPRES
  )  VALUES (SEQ_WEBSERV_PRES_PRES.nextval,concat('201941283780012888',SEQ_WEBSERV_PRES_PRES.nextval),:FPRESCRIPCION, :HPRESCRIPCION, '080010003701', 'NI', '890102768', '08001', 'CARRERA 48 # 70-38', '3091999', 'CC', '8755608', 'SAUL', 'ALFREDO', 'CHRISTIANSEN', 'MARTELO', '1572', 'CC', '3754775', 'HERNANDO', '', 'ESTRADA', 'GOMEZ',22, null,0, null, null, 'R579', null, null, null, 'ESS076', null, null, null, null, null,4)";
  $st = oci_parse($conn, $sql_exc);
  $FPRESCRIPCION = '25-01-0219';
  $HPRESCRIPCION = "11:30:33";
  oci_bind_by_name($st, ":FPRESCRIPCION", $FPRESCRIPCION);
  oci_bind_by_name($st, ":HPRESCRIPCION", $HPRESCRIPCION);
  $result = oci_execute($st);
  oci_free_statement($st);
  if ($result) {
    echo "<br>Insercion Correcta ";
  } else {
    echo "<br>Insercion Incorrecta ";
  }
  /////Insertar prescripcion (Fin)


  ////Consultar Tabla (Inicio)
  $query = "SELECT ID_PRES,NOPRESCRIPCION,FPRESCRIPCION FROM oasis4.WEBSERV_PRES_PRES order by id_pres";
  $stid = oci_parse($conn, $query);
  oci_execute($stid, OCI_DEFAULT);

  echo "<table>";
  echo "<tr align='center'>
  <td>ID_PRES</td>
  <td>NOPRESCRIPCION</td>
  <td>FPRESCRIPCION</td>
 </tr>";
  while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {

    // Usar nombres de columna en mayúsculas para los índices del array asociativo
    echo "<tr>";
    echo "<td align='center'> " . $row[0]  . " </td>";
    echo "<td align='center'> " . $row[1]  . " </td>";
    echo "<td align='center'> " . $row['FPRESCRIPCION']  . " </td>";
    echo "</tr>";
  }
  echo "</table>";

  /* Otra forma de recorrer la tabla
  while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
    echo "<tr>";
    foreach ($row as $item) {
      echo "<td> " . $item . " </td>";
    }
    echo "</tr>";
  }
  echo "</table>";*/


  oci_free_statement($stid);
  ////Consultar Tabla (Fin)

  oci_close($conn);
  ?>