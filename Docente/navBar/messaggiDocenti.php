<?php
if (!isset($_SESSION)) {
    session_start();
}
include 'navbarDocente.php';
include '../../connessione.php';
include '../../Condiviso/Messaggio.php';

if ($_SESSION['ruolo'] != 'Docente') {
    echo "Accesso Negato";
    header('Location: ../../Accesso/Logout.php?message=Utente non autorizzato.');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=100%, initial-scale=1.0">
    <style>
        body {
            height: 100%;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9acac;
        }

        .container {
            width: 100%;
            min-height: 100%;
            background-color: #f9acac;
            border-radius: 5px;
            text-align: center;
            word-wrap: break-word;

        }

        .messH2 {
            text-align: center;
            margin-bottom: 20px;
            font: sans-serif;
            font-style: italic;
            font-weight: bold;
            font-size: medium;
        }

        .mess-list {
            list-style-type: none;
            padding: 0;
            width: 100%;
            margin: 0 auto;
            text-align: center;
            word-wrap: break-word;
        }

        .mess-item {
            width: auto;
            padding: 10px;
            margin-bottom: 5px;
            margin-right: 10pt;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            text-align: left;
            display: inline-block;
            word-wrap: break-word;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .button {
            color: black;
            background-color: #ffcc00;
            border: none;
            padding: 10px 20px;
            margin-right: 10px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .pMess {
            font: 1rem 'Fira Sans', ßß arial;
            font-size: 16px;
            border-width: auto;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }


        .labelBold {
            font-weight: bold;
        }

        .test-item p,
        .test-item label {
            font-size: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="btn-container">
            <a href="../Messaggi/nuovoMessaggioDocente.php" class="button">Invia Nuovo Messaggio</a>
        </div>
        <?php
        
        $messaggio = new Messaggio();
        echo "<h2 class='messH2'>Messaggi ricevuti: </h2>";
        $email_login = $_SESSION['email'];
        $messaggiRicevuti = $messaggio->getMessaggiRicevutiDocente($email_login);

        if ($messaggiRicevuti->num_rows > 0) {
            echo "<ul class='mess-list'>";
            while ($mess = $messaggiRicevuti->fetch_assoc()) {
                echo "<li class='mess-item'>";
                foreach ($mess as $key => $value) {
                    if ($key !== 'Id') {
                        echo "<p><Label class='labelBold'>" . ucfirst($key) . ":</label> " . $value . "</p>";
                    }
                }
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='pMess'>Non sono presenti messaggi ricevuti</p>";
        }

        echo "<h2 class='messH2'>Messaggi inviati:</h2>";
        $messaggiInviati = $messaggio->getMessaggiInviatiDocente($email_login);

        if ($messaggiInviati->num_rows > 0) {
            echo "<ul class='mess-list'>";
            while ($mess = $messaggiInviati->fetch_assoc()) {
                echo "<li class='mess-item'>";
                foreach ($mess as $key => $value) {
                    if ($key !== 'Id') {
                        echo "<p><Label class='labelBold'>" . ucfirst($key) . ":</label> " . $value . "</p>";
                    }
                }
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='pMess'>Non sono presenti messaggi inviati</p>";
        }

        ?>

    </div>
</body>

</html>