<?php
session_start();
//echo "skrypt działa";
if (isset($_POST['email'])) {
    //echo "emial jest ustawiony";
    $everything_ok = true;
//walidacja nazwy uzytkownika
    $userName = $_POST['userName'];

    if (strlen($userName) < 3 || strlen($userName) > 20) {
        $everything_ok = false;
        $_SESSION['e_userName'] = " Nazwa uzytkownika musi miec od 3 do 20 znaków. ";
    }

    if (!ctype_alnum($userName)) {
        $everything_ok = false;
        $_SESSION['e_userName'] = " Nazwa uzytkownika może składać się jedynie z znaków alfanumerycznych. ";
    }

//walidacja emial
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (!filter_var($emailB, FILTER_VALIDATE_EMAIL) || $emailB != $email) {
        $everything_ok = false;
        $_SESSION['e_email'] = "Wystąpił problem z Emailem.";
    }

//sprawdzanie hasla

    $password1 = $_POST['pass1'];
    $password2 = $_POST['pass2'];

    if (strlen($password1) < 8 || strlen($password1) > 20) {
        $everything_ok = false;
        $_SESSION['e_passWord'] = " Hasło musi miec od 8 do 20 znaków. ";
    }

    if ($password1 != $password2) {
        $everything_ok = false;
        $_SESSION['e_passWord'] = "Hasła nie są jednakowe.";
    }

    $hash_password = password_hash($password1,PASSWORD_DEFAULT);

   //sprawdzanie czeck boxa
    if (!isset($_POST['agree'])) {
        $everything_ok = false;
        $_SESSION['e_checkBox'] = "Nie zaakceptowales regulaminu.";
    }

    //scprawdzanie capczy
    $secret_key = "6Le7HygUAAAAAEonO5UxK2FKZ7xrhmWvdYMpgElu";
    $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response']);
    $answer = json_decode($check);
    if ($answer->success == false) {
       // echo $_POST['g-recaptcha-response'];
        $everything_ok = false;
        $_SESSION['e_captcha'] = "spadaj robocie!!!1!1!1";
    }


    //Sprawdzanie zgodnosci z baza danych

    require_once "database.php"; // include danych potrzebnych do bazy danych


    try {
        $connection = @new mysqli($host, $db_user, $db_password, $db_name); // ustanawia polączenie


        if ($connection->connect_errno != 0) {
            throw Exception(mysqli_connect_errno);
        }
        else {
            $rezultat = @$connection->query("SELECT id FROM loginbase WHERE user='$userName'");
            if (!$rezultat) throw new Exception($rezultat->error);
            $row_number = $rezultat->num_rows; // jak znajdzie 
            if ($row_number > 0) {
                $everything_ok = false;
                $_SESSION['e_sqlName'] = "Imie znalezione w bazie.";
            }



            $rezultat = @$connection->query("SELECT id FROM loginbase WHERE email='$email'"); // zapytanie o maila
            if (!$rezultat) throw new Exception($rezultat->error);
            $row_number = $rezultat->num_rows; // jak znajdzie 
            if ($row_number > 0) {
                $everything_ok = false;
                $_SESSION['e_email'] = "Na podany email juz jest zalozone konto";
            }

        }

        if ($everything_ok == TRUE) {
            if($connection->query("INSERT INTO loginbase VALUES (NULL, '$userName',  '$hash_password','$email', 0)")) {
                $_SESSION['valid_registration'] = true;
                header("Location:welcome.php");
            } else {
                throw new Exception($connection->error);
            }
        }


        $connection->close();



    } catch (Exception $e) {
        echo "Bląd Servera " . $e;
    }

/*
    try {
        $connection = @new mysqli($host, $db_user, $db_password, $db_name); // ustanawia polączenie


        if ($connection->connect_errno != 0) {  //wyrzucenie kodu bledu
            throw Exception(mysqli_connect_errno);
        }
        else {
            $rezultat = @$connection->query("SELECT id FROM loginbase WHERE email='$email'"); // zapytanie o maila

            if (!$rezultat) throw new Exception($rezultat->error);



            $row_number = $rezultat->num_rows; // jak znajdzie 
            if ($row_number > 0) {
                $everything_ok = false;
                $_SESSION['e_email'] = "Na podany email juz jest zalozone konto";
            }
        }


        $connection->close();



    } catch (Exception $e) {
        echo "Bląd Servera " . $e;
    }


     */



    //dodanie wszytkiego o bazy


}
?>


<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">

    <title>Login Form</title>
    <script src='https://www.google.com/recaptcha/api.js'></script>


    <style>

    .error {
         color: red;
        margin-top: 10px;
     margin-bottom: 10px;
    }
    </style>



</head>

<body>


    <form method="POST">
    
        Nazwa użytkownika: <br/>
        <input type="text" name="userName"><br />

        <?php
        //nazwa uzytkownika
        if (isset($_SESSION['e_userName'])) {
            echo '<div class="error">' . $_SESSION['e_userName'] . '</div>';
            unset($_SESSION['e_userName']);
        }

        if (isset($_SESSION['e_sqlName'])) {
            echo '<div class="error">' . $_SESSION['e_sqlName'] . '</div>';
            unset($_SESSION['e_sqlName']);
        }

        ?>
         
        E-mail: <br/>
        <input type="text" name="email"> <br />

        <?php
        //emial uzytkownika
        if (isset($_SESSION['e_email'])) {
            echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
            unset($_SESSION['e_email']);
        }

        ?>
         Hasło: <br/>
        <input type="password" name="pass1"> <br />

        <?php
        //pierwsze haslo uzytkownika
        if (isset($_SESSION['e_passWord'])) {
            echo '<div class="error">' . $_SESSION['e_passWord'] . '</div>';
            unset($_SESSION['e_passWord']);
        }

        ?>

         Powtórz hasło: <br/>
        <input type="password" name="pass2"> <br />

        <label>
            <input type="checkbox" name="agree">Akceptuje Regulamin. <br/>
        </label>

        <?php
        //ccheckbox
        if (isset($_SESSION['e_checkBox'])) {
            echo '<div class="error">' . $_SESSION['e_checkBox'] . '</div>';
            unset($_SESSION['e_checkBox']);
        }

        ?>


        <div class="g-recaptcha" data-sitekey="6Le7HygUAAAAADWioph5AqTTC3mj_jZRcz-542Oz"></div>


        <?php
        //captcha
        if (isset($_SESSION['e_captcha'])) {
            echo '<div class="error">' . $_SESSION['e_captcha'] . '</div>';
            unset($_SESSION['e_captcha']);
        }


        //przycisk optrwierdzenia
        ?>

        <input type="submit" value="Rejestracja"/>
        



    </form>



</body>

</html>

