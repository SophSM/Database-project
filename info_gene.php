<!--Nombre
Sinonimo
Strand
secuencia

Producto
…
Operon-->
<html>
    <head>
        <title> Gene Results </title>
        <link rel="stylesheet" type="text/css" href="/PROYECTO/mystyle.css">
        <form id="form" name="form" method="get" action="resultados.php">
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
                        echo $campos2->object_synonym_name. "<br> ";
                      }
                    ?>
                    </td>
                    <td><?= $campos->gene_strand;?> </td>
                    <?php echo '<td> <a href="secuencias.php?sequence='.$campos->gene_id.'&type=gene"> '; ?> See gene sequence </a> </td>
                  </tr>
                  <?php } ?>
                </table>
                <br><br>
            <?php $product= $mysqli->query("SELECT * FROM PRODUCT g WHERE product_id IN(SELECT product_id FROM GENE_PRODUCT_LINK g WHERE gene_id= '" . $campos->gene_id. "')");
            if ($product->num_rows>0){
            ?>
            <h3>Product</h3>
            <TABLE class="custom-table2" id="product">
              <thead>
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
                  <?php for ($num_fila = 1;  $num_fila <= $product->num_rows; $num_fila++) {
                        $campos3 = $product->fetch_object();
                        echo $campos3->product_name;
                      }
                      ?>
                  </td>
                  <td>
                    <?php $product_synonym= $mysqli->query("SELECT object_synonym_name FROM OBJECT_SYNONYM g WHERE object_id = '" . $campos3->product_id . "'");
                      for ($num_fila = 1;  $num_fila <= $product_synonym->num_rows; $num_fila++) {
                        $campos4 = $product_synonym->fetch_object();
                        echo $campos4->object_synonym_name. "<br> ";
                      }
                    ?>
                  </td>
                  <?php echo '<td> <a href="secuencias.php?sequence='.$campos3->product_id.'&type=product"> '; ?> See aminoacid sequence </a> </td>
                  <td><?php echo $campos3->location ?></td>
                  <td><?php echo $campos3->molecular_weigth?></td>
                  <td><?php echo $campos3->isoelectric_point?></td>
                </tr>
              </table>
                <br><br>
                    <?php 
                  $product_synonym->close();
                  } ?>
                <h3>Operon</h3>
              <TABLE class = "custom-table2">
                <thead>
                <tr>
                      <th> Name</th>
                      <th>Operon Arrangement</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    
                  <?php $operon= $mysqli->query("SELECT o.operon_name, o.operon_id FROM OPERON o JOIN TRANSCRIPTION_UNIT t ON o.operon_id=t.operon_id JOIN TU_GENE_LINK tu ON t.transcription_unit_id=tu.transcription_unit_id JOIN GENE g ON tu.gene_id=g.gene_id AND g.gene_id = '" . $campos->gene_id. "'");
                      for ($num_fila = 1;  $num_fila <= $operon->num_rows; $num_fila++) {
                        $campos5 = $operon->fetch_object();
                      }
                    ?>
                  <?php echo '<td> <a class="nombre-operon" href="info_operon.php?question='.$campos5->operon_id.'&Submit=Buscar"> '; ?><?= $campos5->operon_name; ?> </a> </td>
                  <td>
                  <TABLE class="custom-table3">
                    <thead>
                    <tr>
                      <th clsss='head'><b>Transcription unit</b></th>
                      <th class='head'><b>Promoter</b></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <?php $tu= $mysqli->query("SELECT * FROM TRANSCRIPTION_UNIT WHERE operon_id = '" . $campos5->operon_id. "'");
                      for ($num_fila = 1;  $num_fila <= $tu->num_rows; $num_fila++) {
                        $trans_u = $tu->fetch_object();
                        echo "<tr>";
                        if (!(is_null($trans_u->transcription_unit_name))){
                        echo "<td>".$trans_u->transcription_unit_name."</td>";
                        }
                        else {
                          echo "<td> not known transcription unit </td>";
                        }
                        $promoter = $mysqli->query("SELECT * FROM PROMOTER WHERE promoter_id = '" . $trans_u->promoter_id. "'");
                        if ($promoter->num_rows >0){
                          for ($numero_fila = 1;  $numero_fila <= $promoter->num_rows; $numero_fila++) {
                            $promoter_tab = $promoter->fetch_object();
                          } 
                          echo "<td>".$promoter_tab->promoter_name."</td>";
                          
                        }
                        else{
                          echo "<td> not known promoters</td>"; 
                        }
                        echo "</tr>";
                    }
                  
                     ?> 
                     </tbody>
                    </tr>
                  </TABLE>
                  </td>
                </tr>

</TABLE>
<br><br>
<form>
        <input class="button" id="anterior" type="button" value="Back" onclick="history.back()">
    </form>
    <br><br>
            <?php
              $result_gene->close();
              $sinonimos->close();
              $product->close();
              $operon->close();
              $tu->close();;
              $promoter->close();
            }?>
    </body>
</html>