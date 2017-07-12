<?php
session_start();

if(!isset($_SESSION['status'])) {
    
        header("Location:form.php");
        exit;
    
}


$userName = $_SESSION['user'];
$userPremium = $_SESSION['premium'];

?>


<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">

    <title>Login Form</title>
</head>

<body>

    


    <h1>Witaj!,  <?php echo " " . $userName . ". Dniu Premium: " . $userPremium; ?> </h1>
            
    <?php 
        echo "<a href='logout.php'>Wyloguj</a>";
    ?>
    
    
 
               
               
               
               
                    

               






</body>

</html>