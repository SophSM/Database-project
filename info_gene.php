<!--Pagina para resultados de gene-->
<!--Por Sofia Salazar-->
<!--15 nov 2021-->
<html>

<head>
  <title> Gene Results </title>
  <link rel="stylesheet" type="text/css" href="mystyle.css">
  <!-- para habilitar caja de busqueda en barra de nav-->
  <form id="form" name="form" method="get" action="resultados.php">
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
  <header>
    <!--foto de olas azul-->
    <img src="header_azul.png" alt="header logo">
  </header>
  <br><br>

  <?php
  //conexion al servidor
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  $gene_req = escapeshellcmd($_GET["question"]);
  $mysqli = new mysqli("132.248.248.121:3306", "lcgej", "Genoma123#$", "LCGEJ");
  if ($mysqli->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
  }
  //query del gene buscado
  $result_gene = $mysqli->query("SELECT * FROM GENE g WHERE gene_name like '%" . $gene_req . "%' OR gene_ID = '" . $gene_req . "'");
  ?>
  <?php
  if ($result_gene->num_rows > 0) { ?>
    <h2><?= $gene_req; ?> GENE in Escherichia coli K-12 genome </h2> <br><br>
    <!-- tabla para gene-->
    <h3>Gene</h3>
    <TABLE class="custom-table2">
      <thead>
        <tr>
          <th> Gene ID </th>
          <th> Gene Name </th>
          <th>Synonym(s)</th>
          <th>Strand</th>
          <th>Sequence</th>
        </tr>
      </thead>
      <?php for ($num_fila = 1; $num_fila <= $result_gene->num_rows; $num_fila++) {
        // obtener objeto 
        $campos = $result_gene->fetch_object();
      ?>
        <tr>
          <td><?= $campos->gene_id; ?> </td>
          <td><?= $campos->gene_name; ?> </td>
          <td>
            <?php
            //query de sinonimos
            $sinonimos = $mysqli->query("SELECT object_synonym_name FROM OBJECT_SYNONYM g WHERE object_id = '" . $campos->gene_id . "'");
            for ($num_fila = 1; $num_fila <= $sinonimos->num_rows; $num_fila++) {
              $campos2 = $sinonimos->fetch_object();
              echo $campos2->object_synonym_name . "<br> "; // imprimir cada sinonimo

            }
            ?>
          </td>
          <td><?= $campos->gene_strand; ?> </td>
          <!-- obtener secuencia, diciendo que es de tipo gene-->
          <?php echo '<td> <a href="secuencias.php?sequence=' . $campos->gene_id . '&type=gene"> '; ?> See gene sequence </a> </td>
        </tr>
      <?php } ?>
    </table>
    <br><br>
    <?php
    //query para el producto
    $product = $mysqli->query("SELECT * FROM PRODUCT g WHERE product_id IN(SELECT product_id FROM GENE_PRODUCT_LINK g WHERE gene_id= '" . $campos->gene_id . "')");
    if ($product->num_rows > 0) {
    ?>
      <!-- tabla para el producto-->
      <h3>Product</h3>
      <TABLE class="custom-table2" id="product">
        <thead>
          <!-- encabezados de la tabla-->
          <tr>
            <th> Name </th>
            <th>Synonym(s)</th>
            <th>Product</th>
            <th>Cellular Location</th>
            <th>Molecular weight</th>
            <th>Isoelectric point</th>
          </tr>
        </thead>
        <tr>
          <td>
            <!-- obtener objetos del query-->
            <?php for ($num_fila = 1; $num_fila <= $product->num_rows; $num_fila++) {
              $campos3 = $product->fetch_object();
              echo $campos3->product_name;
            }
            ?>
          </td>
          <td>
            <?php
            //sinonimos del producto
            $product_synonym = $mysqli->query("SELECT object_synonym_name FROM OBJECT_SYNONYM g WHERE object_id = '" . $campos3->product_id . "'");
            for ($num_fila = 1; $num_fila <= $product_synonym->num_rows; $num_fila++) {
              $campos4 = $product_synonym->fetch_object();
              echo $campos4->object_synonym_name . "<br> ";
            }
            ?>
          </td>
          <!-- imprimir secuencia diciendo que es de tipo producto-->
          <?php echo '<td> <a href="secuencias.php?sequence=' . $campos3->product_id . '&type=product"> '; ?> See aminoacid sequence </a> </td>
          <td><?php echo $campos3->location ?></td>
          <td><?php echo $campos3->molecular_weigth ?></td>
          <td><?php echo $campos3->isoelectric_point ?></td>
        </tr>
      </table>
      <br><br>
    <?php
      $product_synonym->close();
    } //if de si existe resultado para producto (product->num_rows)
    ?>
    <!-- tabla para el operon-->
    <h3>Operon</h3>
    <TABLE class="custom-table2">
      <!--encabezados de la tabla-->
      <thead>
        <tr>
          <th> Name</th>
          <th>Operon Arrangement</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <!-- query de operon-->
          <?php $operon = $mysqli->query("SELECT o.operon_name, o.operon_id FROM OPERON o JOIN TRANSCRIPTION_UNIT t ON o.operon_id=t.operon_id JOIN TU_GENE_LINK tu ON t.transcription_unit_id=tu.transcription_unit_id JOIN GENE g ON tu.gene_id=g.gene_id AND g.gene_id = '" . $campos->gene_id . "'");
          for ($num_fila = 1; $num_fila <= $operon->num_rows; $num_fila++) {
            $campos5 = $operon->fetch_object();
          }
          ?>

          <?php
          //hipervinculo para operon, volviendo a hacer el request al formulario pero con el id del operon
          echo '<td> <a class="nombre-operon" href="info_operon.php?question=' . $campos5->operon_id . '&Submit=Buscar"> '; ?><?= $campos5->operon_name; ?> </a> </td>
          <td>
            <!-- tabla para los transcription unit-->
            <TABLE class="custom-table3">
              <!--encabezados de la tabla-->
              <thead>
                <tr>
                  <th clsss='head'><b>Transcription unit</b></th>
                  <th class='head'><b>Promoter</b></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <!-- query para transcription unit-->
                  <?php $tu = $mysqli->query("SELECT * FROM TRANSCRIPTION_UNIT WHERE operon_id = '" . $campos5->operon_id . "'");
                  //recuperar objetos del query
                  for ($num_fila = 1; $num_fila <= $tu->num_rows; $num_fila++) {
                    $trans_u = $tu->fetch_object();
                    //imprimir info si es conocida
                    echo "<tr>";
                    if (!(is_null($trans_u->transcription_unit_name))) {
                      echo "<td>" . $trans_u->transcription_unit_name . "</td>";
                    } else {
                      //si no se conoce, imprimir mensaje
                      echo "<td> Unknown transcription unit name </td>";
                    }
                    //query del promotor
                    $promoter = $mysqli->query("SELECT * FROM PROMOTER WHERE promoter_id = '" . $trans_u->promoter_id . "'");
                    if ($promoter->num_rows > 0) {
                      //recuperar objetos del query
                      for ($numero_fila = 1; $numero_fila <= $promoter->num_rows; $numero_fila++) {
                        $promoter_tab = $promoter->fetch_object();
                      }
                      //imprimir nombre si se conoce
                      echo "<td>" . $promoter_tab->promoter_name . "</td>";
                    } else {
                      //imprimiendo mensaje si no se conoce
                      echo "<td> Unknown promoter name</td>";
                    }
                    echo "</tr>";
                  } //for para cada transcription unit

                  ?>
              </tbody>
        </tr>
    </TABLE> <!-- tabla de transcription unit y promotor-->
    </td>
    </tr>

    </TABLE>
    <!--tabla grande de operon -->
    <br><br>
    <!-- boton de anterior-->
    <form>
      <input class="button" id="anterior" type="button" value="Back" onclick="history.back()">
    </form>
    <br><br>
  <?php
    //cerrar querys
    $result_gene->close();
    $sinonimos->close();
    $product->close();
    $operon->close();
    $tu->close();
    $promoter->close();
  } //if de si el gen tiene mas de 0 filas (result_gene->num_rows)
  ?>
</body>

</html>