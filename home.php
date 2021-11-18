<!--Pagina principal "HOME"-->
<!--Por Sofia Salazar-->
<!--15 nov 2021-->
<html>

<head>
  <title> Home </title>
  <link rel="stylesheet" type="text/css" href="mystyle.css">
  <!-- para habilitar caja de busqueda en barra de nav-->
  <form id="form" name="form" method="get" action="resultados.php">
</head>

<body>
  <!-- elementos del barra de nav-->
  <nav class="topnav">
    <div class="logo">
      <a href="home.php"><img src="logo2.png" /></a>
    </div>
    <div class="tabs">
      <b><a class="active" href="home.php">Home</a></b>
      <b><a href="about.php">About</a></b>
      <b><a href="formularioDB.php">Search</a></b>
      <!--caja de busqueda de la barra de nav-->
      <input name="search" type="text" id="search" size="15" placeholder="Search..." />
    </div>
  </nav>
  <header>
    <!--foto de olas azul-->
    <img src="header_azul.png" alt="header logo">
  </header>
  <br><br>
  <!--imagen del logo grande-->
  <img class='imagen' src="logo_blanco2.png" />
</body>

</html>