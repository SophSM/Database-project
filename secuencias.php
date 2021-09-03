<html>
    <head>
        <title> Sequence </title>

    </head>
  <body>
        <?php
     
      error_reporting(E_ALL);
      ini_set('display_errors', '1');
      $gene_req = escapeshellcmd( $_GET["sequence"] );
      $mysqli = new mysqli("132.248.248.121:3306", "lcgej", "Genoma123#$", "LCGEJ");
      if ($mysqli->connect_errno) 
      {
          echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
          die();
      }
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
    </body>
</html>