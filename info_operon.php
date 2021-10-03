<html>
    <head>
        <title> Operon Results </title>

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
                  <?php //primero checar si tiene transcription_unit
                  $trans_u = $mysqli->query("SELECT * FROM TRANSCRIPTION_UNIT g WHERE operon_ID = '" . $campos->operon_id . "'");
                  $tu_array = array();
                  for ($num_fila = 1;  $num_fila <= $trans_u->num_rows; $num_fila++) {
                    $transcription = $trans_u->fetch_object();
                     array_push($tu_array, $transcription->transcription_unit_id);
                     }
                  $anterior ='';
                  $filas_tu = count($tu_array);
                  if ($filas_tu>0) //hacer las tablas para cada trans_unit
                  {
                    $numero_de_tu=0;
                    $numero_de_promoter=0;
                    $numero_de_terminator=0;
                    for($unidad=0; $unidad<$filas_tu; $unidad++)
                    { 
                      ?>
                      
                      <?php 
                      $tu_names = $mysqli->query("SELECT * FROM TRANSCRIPTION_UNIT g WHERE transcription_unit_id = '" . $tu_array[$unidad]. "'");
                      for ($num_tu = 1;  $num_tu <= $tu_names->num_rows; $num_tu++) 
                      {
                        $transcription_name = $tu_names->fetch_object();
                      }
                      if ($transcription_name->transcription_unit_id != $anterior){ ?>
                        <h3>
                        <?php 
                      $numero_de_tu = $numero_de_tu + 1;
                      echo $numero_de_tu."° Transcription unit";
                        ?>
                      </h3>

                      <table BORDER="5"    WIDTH="50%"   CELLPADDING="4" CELLSPACING="3">
                      <?php
                      $synonyms = $mysqli->query("SELECT * FROM OBJECT_SYNONYM g WHERE object_ID = '" . $tu_array[$unidad] . "'");
                      ?>
                      <tr>
                        <th>Name</th>
                        <th>Gene(s)</th>
                        <?php
                        if ($synonyms->num_rows>0)
                        {
                          echo "<th> Synonyms(s)</th>";
                        } ?>
                        <?php
                        if (!(is_null($transcription_name->transcription_unit_note)))
                        {
                          echo "<th> Note(s)</th>";
                        } ?>
                      </tr>
                      <tr>
                        <?php //para nombre
                        if (!(is_null($transcription_name->transcription_unit_name)))
                        {
                          echo "<td>".$transcription_name->transcription_unit_name."</td>";
                        }
                        else
                        {
                          echo "<td>not known transcription unit name</td>";
                        }
                        ?>
                        <?php //para genes
                        $genes = $mysqli->query("SELECT * FROM GENE g JOIN TU_GENE_LINK tul ON g.gene_id = tul.gene_id JOIN TRANSCRIPTION_UNIT tu ON tul.transcription_unit_id = tu.transcription_unit_id AND tu.transcription_unit_ID = '" . $tu_array[$unidad] . "'");
                        if ($genes->num_rows>0)
                        {
                          echo "<td>";
                          for ($num_gen = 1;  $num_gen <= $genes->num_rows; $num_gen++)
                          {
                            $tu_genes = $genes->fetch_object();
                            echo $tu_genes->gene_name."<br>";
                          }
                          echo "</td>";
                        }
                        else
                        {
                          echo "<td> not known genes for this transcription_unit </td>";
                        }
                        ?>
                        <?php //para sinonimos
                        if($synonyms->num_rows>0)
                        {
                          echo "<td>";
                          for ($num_sy = 1;  $num_sy <= $synonyms->num_rows; $num_sy++)
                          {
                            $transcription_synonym = $synonyms->fetch_object();
                            echo $transcription_synonym->object_synonym_name."<br>";
                          }
                          echo "</td>";
                        }
                        ?>
                        <?php //para notas
                        if(!(is_null($transcription_name->transcription_unit_note)))
                        {
                          echo "<td>".$transcription_name->transcription_unit_note."</td>";
                        }
                        ?>
                      </tr>
                      </table> <!-- fin de tabla de transcription_unit -->

                      <?php //Para tabla de promoter
                      $numero_de_promoter = $numero_de_promoter + 1;
                      $promoter = $mysqli->query("SELECT * FROM PROMOTER pro JOIN TRANSCRIPTION_UNIT tu ON pro.promoter_id=tu.promoter_id AND tu.transcription_unit_id = '" . $tu_array[$unidad] . "'");
                      if ($promoter->num_rows>0)
                        { 
                          for ($num_pro = 1;  $num_pro <= $promoter->num_rows; $num_pro++)
                            {
                            // obtener objeto 
                            $promo = $promoter->fetch_object();
                            }
                            ?>
                            <h3>
                            <?php echo $numero_de_promoter."° Promoter"; ?></h3>
                            <table BORDER="5"    WIDTH="50%"   CELLPADDING="4" CELLSPACING="3">
                            <tr>
                            <th>Name</th>
                            <?php 
                            if(!(is_null($promo->pos_1)))
                            {
                            echo "<th> +1 </th>";
                            }
                            if(!(is_null($promo->sigma_factor)))
                            {
                            echo "<th> Sigma factor </th>";
                            }
                            if(!(is_null($promo->promoter_sequence)))
                            {
                            echo "<th> Sequence </th>";
                            }
                            ?>
                            </tr>
                            <tr>
                            <?php
                            if(!(is_null($promo->promoter_name)))
                            {
                            echo "<td>".$promo->promoter_name."</td>";
                            }
                            else
                            {
                            echo "<td> not known promoter name </td>";
                            }
                            if(!(is_null($promo->pos_1)))
                            {
                            echo "<td>".$promo->pos_1."</td>";
                            }
                            if(!(is_null($promo->sigma_factor)))
                            {
                            echo "<td>".$promo->sigma_factor."</td>";
                            }
                            if(!(is_null($promo->promoter_sequence)))
                            {
                            echo "<td>". $promo->promoter_sequence. "</td>";
                            }
                            ?>
                            </tr>
                            </table>
                        <?php }
                        $promoter->close(); //if de promoter  ?>
                      <?php //Tabla para terminator(s)
                      $numero_de_terminator = $numero_de_terminator + 1;
                        $terminator = $mysqli->query("SELECT * FROM TERMINATOR ter JOIN TU_TERMINATOR_LINK tute ON ter.terminator_id = tute.terminator_id JOIN TRANSCRIPTION_UNIT tu ON tute.transcription_unit_id = tu.transcription_unit_id AND tu.transcription_unit_id = '" . $tu_array[$unidad] . "'");
                        if ($terminator->num_rows>0)
                        {?>
                          <h3> <?php echo $numero_de_terminator."° Terminator(s)"; ?></h3>
                          <table BORDER="5"    WIDTH="50%"   CELLPADDING="4" CELLSPACING="3">
                            <tr>
                          <?php for($num_ter=1; $num_ter<= $terminator->num_rows; $num_ter++)
                          {?>
                            <th><?= $num_ter."° Type" ?></th>
                            <th><?= $num_ter."° Sequence" ?></th>
                          <?php }?>
                            </tr>
                            <tr>
                              <?php 
                              $ter_type_array = array();
                              $ter_seq_array = array();
                              $cantidad = 0;
                              for($num_ter=1; $num_ter<= $terminator->num_rows; $num_ter++)
                              {
                                $ter = $terminator->fetch_object();
                                array_push($ter_type_array, $ter->terminator_class);
                                array_push($ter_seq_array, $ter->terminator_sequence);
                                $cantidad ++;
                              }
                              for ($i=0; $i<$cantidad; $i++)
                              {
                              echo "<td>".$ter_type_array[$i]."</td>";
                              echo "<td>".$ter_seq_array[$i]."</td>";
                              }
                              ?>
                            </tr>
                          </table>
                        <?php
                        
                      } 
                      $terminator->close();//if de terminator?>
                      <br><br>
                      <br><br>
                    <?php
                    $anterior = $transcription_name->transcription_unit_id; 
                    }
                  }//for grande?> 
                    <br><br>
          <?php $trans_u ->close();
        $tu_names->close(); 
        $synonyms->close();
        $genes->close();
        } ?>
            <?php

              $result_operon->close();

            }?>
    </body>
</html>