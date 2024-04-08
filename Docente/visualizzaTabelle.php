<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: #f9acac;
        }

        .container {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f9acac;
            border-radius: 5px;

        }
    </style>
</head>

<body>
    <div class="container">
        <ul class="test-list">
            <?php



            include 'navbarDocente.php';
            include '../connessione.php';

            if (!isset($_SESSION['email']) || !isset($_SESSION['ruolo'])) {
                // Redirect a una pagina di login se l'utente non è autenticato
                header("Location: ../");
                exit();
            }
            ?>

        <script>
            
        </script>
    </div>

</body>

</html>