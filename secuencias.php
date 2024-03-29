<!-- Pagina para secuencias-->
<!--Por Sofia Salazar-->
<!--15 nov 2021-->
<html>

<head>
  <link rel="stylesheet" type="text/css" href="mystyle.css">
  <!-- para habilitar caja de busqueda en barra de nav-->
  <form id="form" name="form" method="get" action="resultados.php">
    <title> Sequence </title>

</head>

<body>
  <!-- elementos de barra de nav-->
  <nav class="topnav">
    <div class="logo">
      <a href="home.php"><img src="logo2.png" /></a>
    </div>
    <div class="tabs">
      <b><a href="home.php">Home</a></b>
      <b><a href="about.php">About</a></b>
      <b><a class="active" href="formularioDB.php">Search</a></b>
      <!-- caja de busqueda de la barra de nav-->
      <input name="search" type="text" id="search" size="15" placeholder="Search..." />
    </div>
  </nav>
  <!--foto de olas azul-->
  <header>
    <img src="header_azul.png" alt="header logo">
  </header>
  <br><br>
  <?php
  //conexion al servidor
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  $gene_req = escapeshellcmd($_GET["sequence"]);
  $tipo = escapeshellcmd($_GET["type"]);
  $mysqli = new mysqli("132.248.248.121:3306", "lcgej", "Genoma123#$", "LCGEJ");

  if ($mysqli->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
  }
  //recibir el tipo de secuencia deseada (gene/producto)
  if ($tipo == 'gene') {
    //query si se quiere secuencia del gene
    $result_gene = $mysqli->query("SELECT * FROM GENE g WHERE gene_id = '" . $gene_req . "'");
  ?>
    <?php
    if ($result_gene->num_rows > 0) {
      for ($row = 1; $row <= $result_gene->num_rows; $row++) {
        $campos = $result_gene->fetch_object();
      }
    ?>

      <h2> Sequence for <?= $campos->gene_name; ?> gene </h2> <br><br>
      <?php

      $sec = $campos->gene_sequence;
      $sec_doblada = chunk_split($sec, 50, "\n"); //saltos de linea para la secuencia
      echo $sec_doblada;
      ?>

    <?php
      //cerrar query
      $result_gene->close();
    } else {
      //imprimir mensaje si no se encuentra la secuencia
      echo "Sequence is not available in database";
    } ?>

  <?php }
  //en caso de que sea secuencia para producto
  if ($tipo == 'product') {
    //query para secuencia de producto
    $result_product = $mysqli->query("SELECT * FROM PRODUCT g WHERE product_id = '" . $gene_req . "'");
  ?>
    <?php

    if ($result_product->num_rows > 0) {
      for ($row = 1; $row <= $result_product->num_rows; $row++) {
        $campos2 = $result_product->fetch_object();
      }
    ?>
      <h2> Sequence of aminoacids for <?= $campos2->product_name; ?> </h2> <br><br>
      <?php
      $sec2 = $campos2->product_sequence;
      $sec2_doblada = chunk_split($sec2, 50, "\n"); //salto de linea
      echo $sec2_doblada;
      ?>

    <?php
      //cerrar query
      $result_product->close();
    } else {
      //error si no se encuentra la secuencia
      echo "Sequence of aminoacids for this product is not available in the database";
    } ?>
  <?php } ?>
  <br><br>
  <!--boton de anterior-->
  <form>
    <input id="anterior" type="button" value="Back" onclick="history.back()">
  </form>
  <br><br>
</body>

</html>