<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>WS AMBUQ MIPRES</title>
  <link rel="shortcut icon" href="http://www.ambuq.org.co/wp-content/themes/wordpress-bootstrap-master/images/logito.png">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
  ?>
  <!--Borrar Cache(Inicio)-->
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <!--<link rel="stylesheet" href="css/master.css?n=1">-->
  <!--Borrar Cache(Fin)-->
  <!--/////////////////////////////////////////////////////////////////////////////////////////////////////-->
  <!--*******************Sección para cargar los archivos css de la plantilla (Inicio)**********************-->
  <!--/////////////////////////////////////////////////////////////////////////////////////////////////////-->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">


  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- alertifyjs -->
  <link rel="stylesheet" href="plugins/alertifyjs/css/alertify.min.css">

  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!--/////////////////////////////////////////////////////////////////////////////////////////////////////-->
  <!--*******************Sección para cargar los archivos css de la plantilla (Fin)**********************-->
  <!--/////////////////////////////////////////////////////////////////////////////////////////////////////-->
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <?php include("encabezado.php"); ?>

    <div id="div_menu">
      <?php include("menu.php"); ?>
    </div>

    <div id="contenido_principal">

    </div>

    <?php include("pie_pagina.php"); ?>

  </div>
  <!-- ./wrapper -->

  <!--/////////////////////////////////////////////////////////////////////////////////////////////////////-->
  <!--*******************Sección para cargar los archivos js de la plantilla (Inicio)**********************-->
  <!--/////////////////////////////////////////////////////////////////////////////////////////////////////-->
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Select2 -->
  <script src="plugins/select2/js/select2.full.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- Toastr -->

  <script src="plugins/toastr/toastr.min.js"></script>

  <!-- alertify -->
  <script src="plugins/alertifyjs/alertify.min.js"></script>

  <!-- ChartJS -->
  <script src="plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="plugins/sparklines/sparkline.js"></script>
  <!-- JQVMap -- Se comento este plugin para minimizar los errores ya que en dashboard.js se intenta usar pero como actualmente no hay ningun mapa sale erro 
  <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>-->
  <!-- jQuery Knob Chart -->
  <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>

  <!--/////////////////////////////////////////////////////////////////////////////////////////////////////-->
  <!--*******************Sección para cargar los archivos js de la plantilla (Fin)**********************-->
  <!--/////////////////////////////////////////////////////////////////////////////////////////////////////-->


  <!--/////////////////////////////////////////////////////////////////////////////////////////////////////-->
  <!--*******************Sección para cargar el contenido principal (Inicio)**********************-->
  <!--/////////////////////////////////////////////////////////////////////////////////////////////////////-->

  <script src="js/menu.js?241"></script>
  <script src="js/notificaciones.js?321"></script>
  <script src="js/direccionamiento.js?123"></script>
  <script>
    //Cargar procedieintos iniciales
    cargarPanel();
    //cargarNotificaciones();
  </script>
  <!--/////////////////////////////////////////////////////////////////////////////////////////////////////-->
  <!--*******************Sección para cargar el contenido principal (Fin)**********************-->
  <!--/////////////////////////////////////////////////////////////////////////////////////////////////////-->
</body>

</html>