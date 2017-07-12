
<?php
session_start();

if(isset($_SESSION['status'])) {
    if($_SESSION['status']) {
        header("Location:form1.php");
        exit;
    }
}


?>


<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">

    <title>Login Form</title>
</head>

<body>

    


    <h1>Logowanie</h1>
        <form action="login.php" method="post">
            
                <p>Nazwa Użytkownika:</p>
                <input type="text" name="user"/> 
                <p>Hasło:</p>
                <input type="password" name="password"/>
                <input type="submit" value="Submit"/>
                    
               
        <p style = 'color: red'>
         <?php 

            if (isset($_SESSION['error'])) {


                echo $_SESSION['error'];
                unset($_SESSION['error']);
            } ?>
          </p>
    
    
 
               
               
               
               
                    

               

        </form>

        <a href="registration.php">Rejestracja </a>



</body>

</html>