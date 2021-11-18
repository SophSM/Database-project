  <!-- Pagina para el formulario principal "SEARCH" -->
  <!--Por Sofia Salazar-->
  <!--15 nov 2021-->
<html>

<head>
  <title> Consulta DB </title>
  <link rel="stylesheet" type="text/css" href="mystyle.css">
  <form id="form" name="form" method="get" action="resultados.php">
    <!-- para habilitar caja de busqueda en barra de nav-->
</head>

<body>
  <!-- elementos de barra de nav -->
  <nav class="topnav">
    <div class="logo">
      <a href="home.php"><img src="logo2.png" /></a>
    </div>
    <div class="tabs">
      <b><a href="home.php">Home</a></b>
      <b><a href="about.php">About</a></b>
      <b><a class="active" href="formularioDB.php">Search</a></b>
      <!-- caja de busqueda de la barra de nav-->
      <input name="search" type="text" id="search" size="15" placeholder="Search..." />
    </div>
  </nav>
  <header>
    <!--foto de olas azul-->
    <img src="header_azul.png" alt="header logo">
  </header>
  <!-- seccion del formulario -->
  <div class="formulario">
    <form id="form" name="form" method="get" action="resultados.php">

      <h1>Search in LCGEJ DATA BASE</h1>
      Insert ID or search by name: <br><br>
      <div class="buscador">
        <input name="search" type="text" id="search" size="15" placeholder="Search..." />
      </div>
      <br>

      <!-- botones de buscar y de limpiar -->
      <input class="button" type="submit" name="Submit" value="Buscar" />
      <input class="button" type="reset" name="Limpiar" value="Limpiar" />
    </form>
  </div>
</body>

</html>