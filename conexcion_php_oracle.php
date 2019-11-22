 <?php
  $conn = oci_connect('oasis4', 'sybase11', '10.244.9.229:1521/ambuqQA');
  $query = "select companyid from operation where rownum<=1";
  $stid = oci_parse($conn, $query);
  oci_execute($stid, OCI_DEFAULT);


  echo "<table>";
  while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
    echo "<tr>";
    foreach ($row as $item) {
      echo "<td> " . $item . " </td>";
    }
    echo "</tr>";
  }
  echo "</table>";

  oci_free_statement($stid);
  oci_close($conn);
  ?>