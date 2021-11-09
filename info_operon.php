<html>
    <head>
      <!-- pagina para resultados de operon -->
        <title> Operon Results </title>
        <link rel="stylesheet" type="text/css" href="/PROYECTO/mystyle.css">
        <form id="form" name="form" method="get" action="resultados.php">
    </head>
  <body>
    <!-- elementos de barra de nav -->
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
     // conexion al servidor
      error_reporting(E_ALL);
      ini_set('display_errors', '1');
      $operon_req = escapeshellcmd( $_GET["question"] );
      $mysqli = new mysqli("132.248.248.121:3306", "lcgej", "Genoma123#$", "LCGEJ");
      if ($mysqli->connect_errno) 
      {
          echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
          die();
      }
      //query de la tabla operon
      $result_operon = $mysqli->query("SELECT * FROM OPERON g WHERE operon_name like '%".$operon_req."%' OR operon_ID = '" . $operon_req . "'");
        ?>
      <?php 
            if ($result_operon->num_rows > 0) { ?>
            <?php  for ($num_fila = 1;  $num_fila <= $result_operon->num_rows; $num_fila++) {
            // obtener filas del query si son mas de 0
            $campos = $result_operon->fetch_object();
            }?>
            <h2> Results for <?= $campos->operon_name; ?> in OPERON </h2>
              <h3>Operon</h3>
              <TABLE class="custom-table2">
                <!--Encabezados de la tabla -->
                <thead>
                  <tr>
                      <th class="head"> OPERON ID </th> 
                      <th class="head"> OPERON Name </th>
                  </tr>
                    </thead>
                  <!--celdas con id y nombre del operon -->
                  <tbody>
                  <tr>
                    <td><?= $campos->operon_id;?> </td>
                    <td><?= $campos->operon_name; ?> </td>
                  </tr>
                  </tbody>
                </table>
                  <?php //primero checar si tiene transcription_unit
                  $trans_u = $mysqli->query("SELECT * FROM TRANSCRIPTION_UNIT g WHERE operon_ID = '" . $campos->operon_id . "'");
                  // array para todos los ID de trascription units de cada operon para hacer los querys de las
                  // tablas de promoter y terminator
                  $tu_array = array();
                  for ($num_fila = 1;  $num_fila <= $trans_u->num_rows; $num_fila++) {
                    $transcription = $trans_u->fetch_object();
                    // se llena el array con los ID de los transcription units para cada operon
                     array_push($tu_array, $transcription->transcription_unit_id);
                     }
                    // la variable anterior se inicia vacia, es para verificar que no se repitan los
                    // transcription units
                  $anterior ='';
                  $filas_tu = count($tu_array); // se cuentan los transcription units
                  if ($filas_tu>0) //hacer las tablas para cada trans_unit
                  {?>
                  <?php 
             
                    for($unidad=0; $unidad<$filas_tu; $unidad++) // for para cada transcription unit
                    { 
                      ?>
                      
                      <?php 
                      //sacar los nombres de los transcription units
                      $tu_names = $mysqli->query("SELECT * FROM TRANSCRIPTION_UNIT g WHERE transcription_unit_id = '" . $tu_array[$unidad]. "'");
                      for ($num_tu = 1;  $num_tu <= $tu_names->num_rows; $num_tu++) 
                      {
                        $transcription_name = $tu_names->fetch_object();
                      }
                      // verificar que no se repita el nombre
                      if ($transcription_name->transcription_unit_id != $anterior){ ?>
                      <!-- separacion para cada transcription unit -->
                      <img class= "linea" src="/PROYECTO/dna.png">
                        <h3>
                        <?php 
                    
                      // Tabla para transcription unit
                      echo "Transcription unit";
                        ?>
                      </h3>
                      <table class="custom-table2">
                      <?php
                      //query para los sinonimos
                      $synonyms = $mysqli->query("SELECT * FROM OBJECT_SYNONYM g WHERE object_ID = '" . $tu_array[$unidad] . "'");
                      ?>
                      <!--encabezados de tabla de transcription unit -->
                      <thead>
                      <tr>
                        <th class="head">Name</th>
                        <th class = "head">Gene(s)</th>
                        
                        <?php
                        if ($synonyms->num_rows>0) //se hace encabezado de sinonimos solo si los hay
                        {
                          echo "<th class='head'> Synonyms(s)</th>";
                        } ?>
                        <?php
                       ?>
                      </tr>
                      </thead>
                      <tr>
                        <!--impresion de las celdas-->
                        <?php //para nombre, solo si no es nulo
                        if (!(is_null($transcription_name->transcription_unit_name)))
                        {
                          echo "<td>".$transcription_name->transcription_unit_name."</td>";
                        }
                        else
                        {
                          //si no existe el nombre
                          echo "<td>Unknown transcription unit name</td>";
                        }
                        ?>
                        <?php //para genes
                        $genes = $mysqli->query("SELECT * FROM GENE g JOIN TU_GENE_LINK tul ON g.gene_id = tul.gene_id JOIN TRANSCRIPTION_UNIT tu ON tul.transcription_unit_id = tu.transcription_unit_id AND tu.transcription_unit_ID = '" . $tu_array[$unidad] . "'");
                        if ($genes->num_rows>0)
                        // se hace el encabezado de genes solo si los hay
                        {
                          echo "<td>";
                          for ($num_gen = 1;  $num_gen <= $genes->num_rows; $num_gen++)
                          {
                            //se sacan los nombres de genes
                            $tu_genes = $genes->fetch_object();
                            echo $tu_genes->gene_name."<br>";
                          }
                          echo "</td>";
                        }
                        else
                        {
                          //si no se saben los genes
                          echo "<td> Unknown genes for this transcription_unit </td>";
                        }
                        ?>
                        <?php //para sinonimos, solo si hay mas de 0
                        if($synonyms->num_rows>0)
                        {
                          echo "<td>";
                          for ($num_sy = 1;  $num_sy <= $synonyms->num_rows; $num_sy++)
                          {
                            //se imprimen todos los que hay con el for
                            $transcription_synonym = $synonyms->fetch_object();
                            echo $transcription_synonym->object_synonym_name."<br>";
                          }
                          echo "</td>";
                        }
                        ?>
                    
                      </tr>
                      </table> <!-- fin de tabla de transcription_unit -->

                      <?php //Para tabla de promoter
                      // query para el promoter
                      $promoter = $mysqli->query("SELECT * FROM PROMOTER pro JOIN TRANSCRIPTION_UNIT tu ON pro.promoter_id=tu.promoter_id AND tu.transcription_unit_id = '" . $tu_array[$unidad] . "'");
                      if ($promoter->num_rows>0) // si si hay promotor se hace la tabla
                        { 
                          for ($num_pro = 1;  $num_pro <= $promoter->num_rows; $num_pro++)
                            {
                            // obtener objeto del query
                            $promo = $promoter->fetch_object();
                            }
                            
                            $check=FALSE; // bandera para sacar la distance from start of gene
                            $left_array = array(); // arreglo para las distance pos_left
                            $right_array = array(); // arreglo para las distance pos_right
                            //query para obtener las pos_right y pos_left
                            $pos_query = $mysqli->query("SELECT * FROM GENE g JOIN TU_GENE_LINK tul ON g.gene_id = tul.gene_id JOIN TRANSCRIPTION_UNIT tu ON tul.transcription_unit_id = tu.transcription_unit_id AND tu.transcription_unit_ID = '" . $tu_array[$unidad] . "'");
                            for ($num_pos = 1;  $num_pos <= $pos_query->num_rows; $num_pos++)
                            {
                              //guardar los elementos en los array
                              $positions= $pos_query->fetch_object();
                              array_push($left_array, $positions->gene_posleft);
                              array_push($right_array, $positions->gene_posright);
                            }
                            //sacar el maximo del right y el minimo del left
                            $mayor_right = max($right_array);
                            $menor_left = min($left_array);
                            //obtener el tipo de strand (reverse / forward) solo si se no es nulo
                            // el pos_1 y hay mas de un gene
                            if (!(is_null($promo->pos_1)) and $genes->num_rows>0)
                            {

                              for ($num_gen = 1;  $num_gen <= $genes->num_rows; $num_gen++)
                              { 
                                //forma del strand
                                $forma = $tu_genes->gene_strand;

                              }
                              $check=TRUE; //si se cumplen las condiciones y se obtiene el strand cambia bandera
                              //obtener la distance from start of gene, dependiendo de si es forward o reverse
                              if ($forma == 'forward'){
                              $distance = abs(($promo->pos_1)-$menor_left);
                              }
                              else
                              {
                                $distance = abs(($promo->pos_1)-$mayor_right);
                              }
                            }

                            ?>
                            <h3>
                            <?php 
                            //Tabla para promotor
                            echo "Promoter"?></h3>
                            <table class="custom-table2">
                            <thead>
                            <tr>
                            <th>Name</th>
                            <?php 
                            // encabezado para +1 solo si no es nulo
                            if(!(is_null($promo->pos_1)))
                            {
                            echo "<th class='head'> +1 </th>";
                            }
                            // encabezado para sigma factor solo si no es nulo
                            if(!(is_null($promo->sigma_factor)))
                            {
                            echo "<th class='head'> Sigma factor </th>";
                            }
                            // Distance from start of gene
                            if($check==TRUE) // si se cumplen las condiciones para la distancia
                            {
                              // se hace el encabezado
                              echo "<th class='head'> Distance from start of gene </th>";
                            }
                            // encabezado de secuencia solo si la secuencia no es nula
                            if(!(is_null($promo->promoter_sequence)))
                            {
                            echo "<th class='head'> Sequence </th>";
                            }
                            ?>
                            </tr>
                            </thead>
                            <!-- celdas-->
                            <tr>
                            <?php
                            //rellenar las celdas de los encabezados anteriores, siempre verificando que no sean nulos
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
                            if($check==TRUE)
                            {
                              echo "<td>".$distance."</td>";
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
                      //$numero_de_terminator = $numero_de_terminator + 1;
                        $terminator = $mysqli->query("SELECT * FROM TERMINATOR ter JOIN TU_TERMINATOR_LINK tute ON ter.terminator_id = tute.terminator_id JOIN TRANSCRIPTION_UNIT tu ON tute.transcription_unit_id = tu.transcription_unit_id AND tu.transcription_unit_id = '" . $tu_array[$unidad] . "'");
                        if ($terminator->num_rows>0) //si si hay terminator se hace la tabla
                        {?>
                          <h3> <?php 
                          //tabla para terminator
                          echo "Terminator(s)" ?></h3>
                          <table class="custom-table2">
                            <thead>
                            <tr>

                          <?php 
                          
                          for($num_ter=1; $num_ter<= $terminator->num_rows; $num_ter++)
                          {?>
                          <!--encabezados de cada tabla-->
                            <th class='head'><?= $num_ter."° Type" ?></th>
                            <th class='head'> <?= $num_ter."° Sequence" ?></th>
                          <?php }?>
                            </tr>
                            </thead>
                            <tr>
                              <?php 
                              // arrays para tipo de terminator y secuencia
                              $ter_type_array = array();
                              $ter_seq_array = array();
                              $cantidad = 0; //cantidad de terminators
                              for($num_ter=1; $num_ter<= $terminator->num_rows; $num_ter++)
                              {
                                $ter = $terminator->fetch_object();
                                //rellenar arrays
                                array_push($ter_type_array, $ter->terminator_class);
                                array_push($ter_seq_array, $ter->terminator_sequence);
                                $cantidad ++;
                              }
                              for ($i=0; $i<$cantidad; $i++)
                              {
                                //imprimir celdas
                              echo "<td>".$ter_type_array[$i]."</td>";
                              echo "<td>".$ter_seq_array[$i]."</td>";
                              }
                              ?>
                            </tr>
                          </table>
                        <?php
                        
                      } 
                      $terminator->close();//if de terminator?>
                    <?php
                    //guardar en anterior el transcription unit del cual ya se hizo tabla
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
            <!-- boton de anterior-->
            <form>
        <input id="anterior" type="button" value="Back" onclick="history.back()">
    </form>
    <br><br>
    </body>
</html>