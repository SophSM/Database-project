<html>
<head>
  <!-- Pagina para los resultados de busqueda-->
        <title> Results </title>
        <!-- Elementos del encabezado -->
        <link rel="stylesheet" type="text/css" href="/PROYECTO/mystyle.css">
        <form id="form" name="form" method="get" action="resultados.php"><!-- para habilitar caja de busqueda en barra de nav-->
    </head>
    <body>
    <nav class="topnav">
        <div class="logo">
        <a href="/PROYECTO/home.php"><img src="/PROYECTO/logo2.png"/></a>
        </div>
        <div class="tabs">
          <b><a href="/PROYECTO/home.php">Home</a></b>
          <b><a href="/PROYECTO/about.php">About</a></b>
          <!-- barra de busqueda extra -->
          <b><a class="active" href="/PROYECTO/formularioDB.php">Search</a></b>
          <!--caja de busqueda de la barra de nav-->
          <input name="search" type="text" id="search" size="15" placeholder="Search..."/>
        </div>
        </nav>
        <header>
          <img src="/PROYECTO/header_azul.png" alt="header logo">
        </header>
      <br><br>
      <!-- boton de anterior -->
      <form>
        <input id="anterior" type="button" value="Back" onclick="history.back()">
    </form>
        <?php
        // conectarse al servidor
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        $gene_req = escapeshellcmd( $_GET["search"] );
        $mysqli = new mysqli("132.248.248.121:3306", "lcgej", "Genoma123#$", "LCGEJ");
        if ($mysqli->connect_errno) 
        {
            echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            die();
        }
        $result_gene = $mysqli->query("SELECT * FROM GENE g WHERE gene_name like '%".$gene_req."%' OR gene_ID = '" . $gene_req . "'");
        $result_operon = $mysqli->query("SELECT * FROM OPERON g WHERE operon_name like '%".$gene_req."%' OR operon_ID = '" . $gene_req . "'");
        if (($result_gene->num_rows > 0) or ($result_operon->num_rows > 0)) {
            ?>
            <?php //Resultados de genes si es que hay mas de 0 resultados
            if ($result_gene->num_rows > 0) { ?>
            <h2> Results for <?= $gene_req; ?> in GENE </h2> <br><br>
                <table class='custom-table'>
                <thead>
                  <tr>
                      <th> GENE ID </th> 
                      <th> GENE Name </th>
                  </tr>    
                  </thead>
                  <tbody>
                  <?php  
                  // obtener los resultados del query
                  for ($num_fila = 1;  $num_fila <= $result_gene->num_rows; $num_fila++) {
                  // obtener objeto 
                  $campos = $result_gene->fetch_object();
                    ?>
                  <tr>
                    <!-- celdas con el nombre e id de cada gene-->
                <?php echo '<td> <a href="info_gene.php?question='.$campos->gene_id.'&Submit=Buscar"> '; ?><?= $campos->gene_id; ?> </a> </td>
                <?php echo '<td> <a href="info_gene.php?question='.$campos->gene_name.'&Submit=Buscar"> '; ?><?= $campos->gene_name; ?> </a> </td>
                  </tr>
                  <?php } ?>
                  </tbody>
      
                </table>
                <br><br>
            <?php } ?>
            <?php 
            // tabla para el operon solo si hay mas de 0 filas
            if ($result_operon->num_rows > 0) { ?>
            <h2> Results for <?= $gene_req; ?> in OPERON </h2> <br><br>
                <table class='custom-table'>
                <thead>
                  <tr>
                      <th> OPERON ID </th> 
                      <th> OPERON Name </th>
                  </tr>    
                  </thead>
                  <tbody>
                  <?php  for ($num_fila = 1;  $num_fila <= $result_operon->num_rows; $num_fila++) {
                  // obtener resultados del query de operon
                  $campos = $result_operon->fetch_object();
                    ?>
                  <tr>
                    <!-- celdas con id y nombre de operon-->
                  <?php echo '<td> <a href="info_operon.php?question='.$campos->operon_id.'&Submit=Buscar"> '; ?><?= $campos->operon_id; ?> </a> </td>
                  <?php echo '<td> <a href="info_operon.php?question='.$campos->operon_name.'&Submit=Buscar"> '; ?><?= $campos->operon_name; ?> </a> </td>
                  </tr>
                  <?php } ?>
                  </tbody>
      
                </table>
                <br><br>
      
              <?php }
              //cerrar resultados
              $result_gene->close();
              $result_operon->close();
             ?>
           <?php
            } else {
              ?> <br><h1> <?= $gene_req; ?> is not registered in promotedb :( <br><h1>
          <?php }?>
          <br><br>

        </body>
      </html>
      