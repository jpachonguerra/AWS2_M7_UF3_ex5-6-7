<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ex5.2</title>
</head>
<body>
<h1>ex5.2 Filtre de llengües per nom de país</h1>

<form action="ex5.2.php" method="post">
    <input type="text" name="country">
    <input type="submit" value="Tramet la consulta">
</form>

<?php
	$country = "";
    if (isset($_POST["country"])) {
        $country = $_POST["country"];
    }

  //connexió dins block try-catch:
  //  prova d'executar el contingut del try
  //  si falla executa el catch
  try {
    $hostname = "localhost";
    $dbname = "mundo";
    $username = "admin";
    $pw = "admin123";
    $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
  } catch (PDOException $e) {
    echo "Error connectant a la BD: " . $e->getMessage() . "<br>\n";
    exit;
  }
 
  try {
    //preparem i executem la consulta
    $query = NULL;
    if (isset($_POST["country"])) {
        $query = $pdo->prepare("SELECT co.Name 'country', cl.Language 'language', cl.IsOfficial 'IsOfficial', cl.Percentage 'percentage' FROM country co INNER JOIN countrylanguage cl ON co.Code = cl.CountryCode WHERE co.Name LIKE '%$country%'");
    } else {
        $query = $pdo->prepare("SELECT co.Name 'country',cl.Language 'language', cl.IsOfficial 'IsOfficial', cl.Percentage 'percentage' FROM country co INNER JOIN countrylanguage cl ON co.Code = cl.CountryCode");
    }
    if ($query != NULL) {
        $query->execute();
    }
  } catch (PDOException $e) {
    echo "Error de SQL<br>\n";
    //comprovo errors:
    $e = $query->errorInfo();
    if ($e[0]!='00000') {
      echo "\nPDO::errorInfo():\n";
      die("Error accedint a dades: " . $e[2]);
    }  
  }
 
  //anem agafant les fileres d'amb una amb una
  $row = $query->fetch();
  echo "<ul>";
  while ( $row ) {
    echo "<li>";
    echo $row['country']." - " . $row['language']. " - ";
    if ($row['IsOfficial'] == 'T') {
        echo "oficial - ";
    } else {
        echo "no oficial - ";
    }
    echo $row['percentage']. "<br/>";
    echo "</li>";
	  $row = $query->fetch();
  }
  echo "</ul>";
 
  //versió alternativa amb foreach
  /*foreach ($query as $row) {
    echo $row['i']." - " . $row['a']. "<br/>";
  }*/
 
  //eliminem els objectes per alliberar memòria 
  unset($pdo); 
  unset($query)
 
?>

</body>
</html>