<!--Nombre
Sinonimo
Strand
secuencia

Producto
…
Operon-->
<html>
    <head>
        <title> Results </title>

    </head>
  <body>
        <?php
     
      error_reporting(E_ALL);
      ini_set('display_errors', '1');
      $gene_req = escapeshellcmd( $_GET["question"] );
      $mysqli = new mysqli("132.248.248.121:3306", "lcgej", "Genoma123#$", "LCGEJ");
      if ($mysqli->connect_errno) 
      {
          echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
          die();
      }
      $result_gene = $mysqli->query("SELECT * FROM GENE g WHERE gene_name like '%".$gene_req."%' OR gene_ID = '" . $gene_req . "'");
        ?>
      <?php 
            if ($result_gene->num_rows > 0) { ?>
            <h2> Results for <?= $gene_req; ?> in GENE </h2> <br><br>
                <table border="1">
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
                    <td><?= $campos->gene_id;?> </td>
                    <td><?= $campos->gene_name; ?> </td>
                  </tr>
                  <?php } ?>
                  </tbody>
      
                </table>
                <br><br>
            <?php
              $result_gene->close();
            }?>
    </body>
</html>