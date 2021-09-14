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
      $operon_req = escapeshellcmd( $_GET["question"] );
      $mysqli = new mysqli("132.248.248.121:3306", "lcgej", "Genoma123#$", "LCGEJ");
      if ($mysqli->connect_errno) 
      {
          echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
          die();
      }
      $result_operon = $mysqli->query("SELECT * FROM OPERON g WHERE operon_name like '%".$operon_req."%' OR operon_ID = '" . $operon_req . "'");
        ?>
      <?php 
            if ($result_operon->num_rows > 0) { ?>
            <?php  for ($num_fila = 1;  $num_fila <= $result_operon->num_rows; $num_fila++) {
            // obtener objeto 
            $campos = $result_operon->fetch_object();
            }?>
            <h2> Results for <?= $campos->operon_name; ?> in OPERON </h2>
              <h3>Operon</h3>
                <table>
                  <tr>
                      <th> OPERON ID </th> 
                      <th> OPERON Name </th>
                  </tr>    
                  <tr>
                    <td><?= $campos->operon_id;?> </td>
                    <td><?= $campos->operon_name; ?> </td>
                  </tr>
                </table>
                <br><br>
                <h3>Transcription Unit</h3>
                <table>
                  <tr>
                      <th>Name</th>
                      <th>Synonym(s)</th>
                      <th>Gene(s)</th>
                  </tr>
                  <tr>
                    <td>
                      <?php $trans_u = $mysqli->query("SELECT * FROM TRANSCRIPTION_UNIT g WHERE operon_ID = '" . $campos->operon_id . "'"); 
                      for ($num_fila = 1;  $num_fila <= $trans_u->num_rows; $num_fila++) {
                        // obtener objeto 
                        $transcription = $trans_u->fetch_object();
                        }
                        echo $transcription->transcription_unit_name;?>
                    </td>
                    <td>
                    <?php $synonyms = $mysqli->query("SELECT * FROM OBJECT_SYNONYM g WHERE object_ID = '" . $transcription->transcription_unit_id . "'"); 
                      for ($num_fila = 1;  $num_fila <= $synonyms->num_rows; $num_fila++) {
                        // obtener objeto 
                        $sinonimos = $synonyms->fetch_object();
                        echo $sinonimos->object_synonym_name. ", ";
                        }
                        ?>
                    </td>
                    <td>
                    <?php $genes = $mysqli->query("SELECT * FROM GENE g JOIN TU_GENE_LINK tul ON g.gene_id = tul.gene_id JOIN TRANSCRIPTION_UNIT tu ON tul.transcription_unit_id = tu.transcription_unit_id AND tu.transcription_unit_ID = '" . $transcription->transcription_unit_id . "'"); 
                      for ($num_fila = 1;  $num_fila <= $genes->num_rows; $num_fila++) {
                        // obtener objeto 
                        $gen = $genes->fetch_object();
                        echo $gen->gene_name. ", ";
                        }
                    ?>
                    </td>
                  </tr>
                </table>
                <br><br>
                <h3>Promoter</h3>
                <table>
                <tr>
                      <th>Name</th>
                      <th>+1</th>
                      <th>Sigma factor</th>
                      <th>Sequence</th>
                </tr>
                <tr>
                      <td>
                      <?php $promoter= $mysqli->query("SELECT * FROM PROMOTER pro JOIN TRANSCRIPTION_UNIT tu ON pro.promoter_id=tu.promoter_id AND tu.operon_id= '" . $campos->operon_id . "'");
                      for ($num_fila = 1;  $num_fila <= $promoter->num_rows; $num_fila++) {
                        // obtener objeto 
                        $promo = $promoter->fetch_object();
                        }
                        echo $promo->promoter_name;
                      ?>
                      </td>
                      <td><?= $promo->pos_1 ?></td>
                      <td><?=$promo->sigma_factor ?></td>
                      <td><?= $promo->promoter_sequence?></td>
                </tr>
                      </table>
                      <br><br>
                      <h3>Terminator</h3>
                <table>
                  <tr>
                    <th>Type</th>
                    <th>Sequence</th>
                  </tr>
                  <tr>
                    <td><?php $terminator = $mysqli->query("SELECT * FROM TERMINATOR ter JOIN TU_TERMINATOR_LINK tute ON ter.terminator_id = tute.terminator_id JOIN TRANSCRIPTION_UNIT tu ON tute.transcription_unit_id = tu.transcription_unit_id AND tu.operon_id = '" . $campos->operon_id . "'");
                    for ($num_fila = 1;  $num_fila <= $terminator->num_rows; $num_fila++) {
                      // obtener objeto 
                      $ter = $terminator->fetch_object();
                      }echo $ter->terminator_class?></td>
                      <td> <?= $ter->terminator_sequence ?> </td>
                  </tr>
                </table>
            <?php
              $result_operon->close();
              $trans_u->close();
              $synonyms->close();
              $genes->close();
              $promoter->close();
              $terminator->close();
            }?>
    </body>
</html>