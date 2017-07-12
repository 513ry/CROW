
    

<?php
session_start(); //rozpoczecie sesji, musi byc w kazdym pliku ktory tego urzywa



require_once "database.php"; // include danych potrzebnych do bazy danych
try {
  $connection = new mysqli($host, $db_user, $db_password, $db_name); // ustanawia polączenie


//wywala blad przy polączeniu problemie z baza danych
  if ($connection->connect_errno != 0) {
    throw new Exception($connection->connect_error);
  }
  else { // skrypt 

  //zapisanie zmiennych z sesji
    $user = $_POST['user'];
    $password = $_POST['password'];
  //anty SQL injection
    $user = htmlentities($user, ENT_QUOTES, "UTF-8");
  
  
  //kwerenda SQL do wybrania danych z bazy danch
  //$sql = "SELECT * FROM  loginbase WHERE user='$user'";

    if ($rezultat = @$connection->query(
      "SELECT * FROM  loginbase WHERE user='$user'"
    )) {
      $row_number = $rezultat->num_rows; // jak znajdzie 
      if ($row_number > 0) {

      //$hash_password = password_hash($password, PASSWORD_DEFAULT);
        $record = $rezultat->fetch_assoc();

        if (password_verify($password, $record['password'])) {

          $_SESSION['user'] = $record['user'];
          $_SESSION['premium'] = $record['premium'];
          $_SESSION['status'] = true;

          unset($_SESSION['error']);
          $rezultat->free();

          header("Location:form1.php");

        }
        else {
          $_SESSION['error'] = "Zła nazwa użytkownika lub hasło. Spróbuj ponownie.";
          header("Location:form.php");
          exit;
        }

      }
      else {
        $_SESSION['error'] = "Zła nazwa użytkownika lub hasło. Spróbuj ponownie.";
        header("Location:form.php");
      }

    }
    else {
      throw new Exception($rezultat->error);
    }









    $connection->close();

  }
} catch (Exception $e) {
  echo "Bląd Servera " . $e;
}

?>