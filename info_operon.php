<html>
    <head>
        <title> Results </title>

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
              <TABLE BORDER="5"    WIDTH="50%"   CELLPADDING="4" CELLSPACING="3">
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
                <TABLE BORDER="5"    WIDTH="50%"   CELLPADDING="4" CELLSPACING="3">
                  <tr>
                      <th>Name</th>
                      <th>Synonym(s)</th>
                      <th>Gene(s)</th>
                  </tr>
                  <tr>
                    <td>
                      <?php $trans_u = $mysqli->query("SELECT * FROM TRANSCRIPTION_UNIT g WHERE operon_ID = '" . $campos->operon_id . "'"); 
                      $tu = array();
                      for ($num_fila = 1;  $num_fila <= $trans_u->num_rows; $num_fila++) {
                        // obtener objeto 
                        $transcription = $trans_u->fetch_object();
                        array_push($tu, $transcription->transcription_unit_id);
                        }
                        echo $transcription->transcription_unit_name; ?>
                    </td>
                    <td>
                    <?php 
                    $filas = count($tu);
                    for ($i = 0; $i<$filas; $i++){
                      $synonyms = $mysqli->query("SELECT * FROM OBJECT_SYNONYM g WHERE object_ID = '" . $tu[$i] . "'");
                      for ($num_fila = 1;  $num_fila <= $synonyms->num_rows; $num_fila++) {
                        $sinonimos = $synonyms->fetch_object();
                        echo $sinonimos->object_synonym_name."<br>";
                      }
                    }
                        ?>
                    </td>
                    <td>
                    <?php $genes = $mysqli->query("SELECT * FROM GENE g JOIN TU_GENE_LINK tul ON g.gene_id = tul.gene_id JOIN TRANSCRIPTION_UNIT tu ON tul.transcription_unit_id = tu.transcription_unit_id AND tu.transcription_unit_ID = '" . $transcription->transcription_unit_id . "'"); 
                      for ($num_fila = 1;  $num_fila <= $genes->num_rows; $num_fila++) {
                        // obtener objeto 
                        $gen = $genes->fetch_object();
                        echo $gen->gene_name. "<br> ";
                        }
                    ?>
                    </td>
                  </tr>
                </table>
                <br><br>
                <h3>Promoter</h3>
                <TABLE BORDER="5"    WIDTH="50%"   CELLPADDING="4" CELLSPACING="3">
                <tr>
                      <th>Name</th>
                      <th>+1</th>
                      <th>Sigma factor</th>
                      <th>Sequence</th>
                </tr>
                <tr>
                      <td>
                      <?php 
                      $pro = array();
                      $promoter= $mysqli->query("SELECT * FROM PROMOTER pro JOIN TRANSCRIPTION_UNIT tu ON pro.promoter_id=tu.promoter_id AND tu.operon_id= '" . $campos->operon_id . "'");
                      for ($num_fila = 1;  $num_fila <= $promoter->num_rows; $num_fila++) {
                        // obtener objeto 
                        $promo = $promoter->fetch_object();
                        }
                        echo $promo->promoter_name;
                      ?>
                      </td>
                      <td>
                      <?php
                      $promoter= $mysqli->query("SELECT * FROM PROMOTER pro JOIN TRANSCRIPTION_UNIT tu ON pro.promoter_id=tu.promoter_id AND tu.operon_id= '" . $campos->operon_id . "'");
                      for ($numero_fila = 1;  $numero_fila <= $promoter->num_rows; $numero_fila++) {
                      $promo2 = $promoter->fetch_object();
                      if (!(is_NULL($promo2->pos_1)));
                      echo $promo2->pos_1;
                      break;
                      }
                      ?>
                      </td>
                      <td>
                      <?php
                      
                      for ($numero_fila = 1;  $numero_fila <= $promoter->num_rows; $numero_fila++) {
                      if (!(is_NULL($promo2->sigma_factor)));
                      echo $promo2->sigma_factor;
                      break;
                      }
                      ?>
                      </td>
                      <td>
                      <?php
                      
                      for ($numero_fila = 1;  $numero_fila <= $promoter->num_rows; $numero_fila++) {
                      if (!(is_NULL($promo2->promoter_sequence)));
                      echo $promo2->promoter_sequence;
                      break;
                      }
                      ?>
                      </td>
                </tr>
                      </table>
                      <br><br>
                      <h3>Terminator</h3>
                      <TABLE BORDER="5"    WIDTH="50%"   CELLPADDING="4" CELLSPACING="3">
                  <tr>
                    <th>Type</th>
                    <th>Sequence</th>
                  </tr>
                  <tr>
                  <?php $terminator = $mysqli->query("SELECT * FROM TERMINATOR ter JOIN TU_TERMINATOR_LINK tute ON ter.terminator_id = tute.terminator_id JOIN TRANSCRIPTION_UNIT tu ON tute.transcription_unit_id = tu.transcription_unit_id AND tu.operon_id = '" . $campos->operon_id . "'");
                  ?>
                    <?php if ($terminator->num_rows>0){ ?>
                    <?php for ($num_fila = 1;  $num_fila <= $terminator->num_rows; $num_fila++) {
                      // obtener objeto 
                      $ter = $terminator->fetch_object();
                      }?>
                      <td><?= $ter->terminator_class?></td>
                      <td> <?= $ter->terminator_sequence ?> </td>
                  <?php }
                  else {
                    echo "<td> No data </td>";
                    echo "<td> No data</td>";
                  } ?>
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