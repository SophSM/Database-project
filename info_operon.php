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
            <h2> Results for <?= $operon_req; ?> in OPERON </h2> <br><br>
                <table border="1">
                <thead>
                  <tr>
                      <th> OPERON ID </th> 
                      <th> OPERON Name </th>
                  </tr>    
                  </thead>
                  <tbody>
                  <?php  for ($num_fila = 1;  $num_fila <= $result_operon->num_rows; $num_fila++) {
                  // obtener objeto 
                  $campos = $result_operon->fetch_object();
                    ?>
                  <tr>
                    <td><?= $campos->operon_id;?> </td>
                    <td><?= $campos->operon_name; ?> </td>
                  </tr>
                  <?php } ?>
                  </tbody>
      
                </table>
                <br><br>
            <?php
              $result_operon->close();
            }?>
    </body>
</html>