<html>
    <head>
        <title> Sequence </title>

    </head>
  <body>
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
            if ($result_gene->num_rows > 0) { ?>
            <h2> Sequence for <?= $gene_req; ?> </h2> <br><br>
                <?php
                  $campos = $result_gene->fetch_object();
                  echo $campos->gene_sequence;
                ?>
                
            <?php
              $result_gene->close();
            }?>
      <?php } 
      if ($tipo == 'product')
      {
   $result_product = $mysqli->query("SELECT * FROM PRODUCT g WHERE product_id = '" . $gene_req . "'");
        ?>
      <?php 
            if ($result_product->num_rows > 0) { ?>
            <h2> Sequence of aminoacids for <?= $gene_req; ?> Product </h2> <br><br>
                <?php
                  $campos2 = $result_product->fetch_object();
                  echo $campos2->product_sequence;
                ?>
                
            <?php
              $result_product->close();
            }?>
      <?php } ?>
    </body>
</html>