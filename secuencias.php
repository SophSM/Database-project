<html>
    <head>
    <link rel="stylesheet" type="text/css" href="/PROYECTO/mystyle.css">
        <form id="form" name="form" method="get" action="resultados.php">
        <title> Sequence </title>

    </head>
  <body>
  <nav class="topnav">
        <div class="logo">
        <a href="/PROYECTO/home.php"><img src="/PROYECTO/logo2.png"/></a>
        </div>
        <div class="tabs">
          <b><a href="/PROYECTO/home.php">Home</a></b>
          <b><a href="/PROYECTO/about.php">About</a></b>
          <b><a class="active" href="/PROYECTO/formularioDB.php">Search</a></b>
          <input name="search" type="text" id="search" size="15" placeholder="Search..."/>
        </div>
        </nav>
        <header>
          <img src="/PROYECTO/header_azul.png" alt="header logo">
        </header>
        <br><br>
        <?php
     
      error_reporting(E_ALL);
      ini_set('display_errors', '1');
      $gene_req = escapeshellcmd( $_GET["sequence"] );
      $tipo = escapeshellcmd( $_GET["type"] );
      $mysqli = new mysqli("132.248.248.121:3306", "lcgej", "Genoma123#$", "LCGEJ");
      if ($mysqli->connect_errno) 
      {
          echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
          die();
      }
      if ($tipo == 'gene'){
      $result_gene = $mysqli->query("SELECT * FROM GENE g WHERE gene_id = '" . $gene_req . "'");
        ?>
      <?php 
            if ($result_gene->num_rows > 0) { 
            for ($row=1; $row<=$result_gene->num_rows; $row++){
            $campos = $result_gene->fetch_object();
            }
            ?>
            <h2> Sequence for <?= $campos->gene_name; ?> gene </h2> <br><br>
                <?php
                  
                  $sec= $campos->gene_sequence;
                  $sec_doblada = chunk_split($sec, 50, "\n");
                  echo $sec_doblada;
                ?>
                
            <?php
              $result_gene->close();
            }else{
              echo "Sequence is not available in database";
            }?>
              
      <?php } 
      if ($tipo == 'product')
      {
   $result_product = $mysqli->query("SELECT * FROM PRODUCT g WHERE product_id = '" . $gene_req . "'");
        ?>
      <?php 
            if ($result_product->num_rows > 0) { 
              for ($row=1; $row<=$result_product->num_rows; $row++){
                $campos2 = $result_product->fetch_object();
                }
              ?>
            <h2> Sequence of aminoacids for <?= $campos2->product_name; ?>  </h2> <br><br>
                <?php
                  $sec2= $campos2->product_sequence;
                  $sec2_doblada = chunk_split($sec2, 50, "\n");
                  echo $sec2_doblada;
                ?>
                
            <?php
              $result_product->close();
            }else{
              echo "Sequence of aminoacids for this product is not available in the database";
            }?>
      <?php } ?>
      <br><br>
      <form>
        <input id="anterior" type="button" value="Back" onclick="history.back()">
    </form>
    <br><br>
    </body>
</html>