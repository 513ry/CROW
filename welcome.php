<?php
session_start();

if (isset($_SESSION['valid_registration'])) {
    if ($_SESSION['valid_registration']) {

    }
    else {
        header("Location:form1.php");
        exit;
    }

    unset($_SESSION['valid_registration']);
}
else {
    header("Location:form1.php");
    exit;
}


?>


<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">

    <title>Welcome!</title>
</head>

<body>

    


    <h1>Rejestracja udana, prosimy o pierwsze zalogowanie</h1> <br /><br />
    <a href="form.php">Zaloguj teraz!</a>



</body>

</html>