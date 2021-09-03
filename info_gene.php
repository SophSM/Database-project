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
      <style>
        tr { display: block; float: left; }
th, td { display: block; border: 1px solid black; }

/* border-collapse */
tr>*:not(:first-child) { border-top: 0; }
tr:not(:first-child)>* { border-left:0; }
      </style>
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
            <h2><?= $gene_req; ?> GENE in Escherichia coli K-12 genome </h2> <br><br>
            <h3>Gene</h3>
                <table border="1">
                  <tr>
                      <th> Gene ID </th> 
                      <th> Gene Name </th>
                      <th>Synonym(s)</th>
                      <th>Strand</th>
                      <th>Sequence</th>
                  </tr>    
                  <?php  for ($num_fila = 1;  $num_fila <= $result_gene->num_rows; $num_fila++) {
                  // obtener objeto 
                  $campos = $result_gene->fetch_object();
                    ?>
                  <tr>
                    <td><?= $campos->gene_id;?> </td>
                    <td><?= $campos->gene_name; ?> </td>
                    <td>
                      <?php $sinonimos= $mysqli->query("SELECT object_synonym_name FROM OBJECT_SYNONYM g WHERE object_id = '" . $campos->gene_id . "'");
                      for ($num_fila = 1;  $num_fila <= $sinonimos->num_rows; $num_fila++) {
                        $campos2 = $sinonimos->fetch_object();
                        echo $campos2->object_synonym_name. " ";
                      }
                    ?>
                    </td>
                    <td><?= $campos->gene_strand;?> </td>
                    <?php echo '<td> <a href="secuencias.php?sequence='.$campos->gene_id.'&Submit=Buscar"> '; ?> Ver secuencia completa </a> </td>
                  </tr>
                  <?php } ?>
                </table>
                <br><br>
            <?php
              $result_gene->close();
            }?>
    </body>
</html>