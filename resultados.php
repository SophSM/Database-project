<html>
<head>
        <title> Results </title>
        <link rel="stylesheet" type="text/css" href="/PROYECTO/mystyle.css">
        <form id="form" name="form" method="get" action="resultados.php">
        
        <nav class="topnav">
        <div class="logo">
        <a href="/PROYECTO/home.php"><img src="/PROYECTO/peppa.png"/></a>
        </div>
          <b><a href="/PROYECTO/home.php">Home</a></b>
          <b><a href="/PROYECTO/about.php">About</a></b>
          <b><a class="active" href="/PROYECTO/formularioDB.php">Search</a></b>
          <input name="search" type="text" id="search" size="15" placeholder="Search..."/>
        </nav>
    </head>
    <body>
      <br><br>
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        $gene_req = escapeshellcmd( $_GET["search"] );
        $mysqli = new mysqli("132.248.248.121:3306", "lcgej", "Genoma123#$", "LCGEJ");
        if ($mysqli->connect_errno) 
        {
            echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            die();
        }
        // verificar el nombre de la variable de gene_id
        $result_gene = $mysqli->query("SELECT * FROM GENE g WHERE gene_name like '%".$gene_req."%' OR gene_ID = '" . $gene_req . "'");
        $result_operon = $mysqli->query("SELECT * FROM OPERON g WHERE operon_name like '%".$gene_req."%' OR operon_ID = '" . $gene_req . "'");
        if (($result_gene->num_rows > 0) or ($result_operon->num_rows > 0)) {
            ?>
            <?php 
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
                  <?php  for ($num_fila = 1;  $num_fila <= $result_gene->num_rows; $num_fila++) {
                  // obtener objeto 
                  $campos = $result_gene->fetch_object();
                    ?>
                  <tr>
                <?php echo '<td> <a href="info_gene.php?question='.$campos->gene_id.'&Submit=Buscar"> '; ?><?= $campos->gene_id; ?> </a> </td>
                <?php echo '<td> <a href="info_gene.php?question='.$campos->gene_name.'&Submit=Buscar"> '; ?><?= $campos->gene_name; ?> </a> </td>
                  </tr>
                  <?php } ?>
                  </tbody>
      
                </table>
                <br><br>
            <?php } ?>
            <?php if ($result_operon->num_rows > 0) { ?>
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
                  // obtener objeto 
                  $campos = $result_operon->fetch_object();
                    ?>
                  <tr>
                  <?php echo '<td> <a href="info_operon.php?question='.$campos->operon_id.'&Submit=Buscar"> '; ?><?= $campos->operon_id; ?> </a> </td>
                  <?php echo '<td> <a href="info_operon.php?question='.$campos->operon_name.'&Submit=Buscar"> '; ?><?= $campos->operon_name; ?> </a> </td>
                  </tr>
                  <?php } ?>
                  </tbody>
      
                </table>
                <br><br>
      
              <?php }
              $result_gene->close();
              $result_operon->close();
             ?>
           <?php
            } else {
              ?> <br><h1> <?= $gene_req; ?> is not registered in promotedb :( <br><h1>
          <?php }?>
    
        </body>
      </html>
      