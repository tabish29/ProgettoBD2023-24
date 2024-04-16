<?php
 include 'navbarStudente.php';
 include '../../connessione.php';
include '../../Condiviso/Messaggio.php';
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

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
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

        .btnInvia{
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
            <a href="../Messaggi/nuovoMessaggioStudente.php" class="btnInvia">Invia Nuovo Messaggio</a>
        </div>
        <?php
        
        $messaggio = new Messaggio();
        $email_login = $_SESSION['email'];
        echo "<h2 class='messH2'>Messaggi ricevuti: </h2>";

        // modificato ----> DA CONTROLLARE !
        $sql_messaggiRicevuti = $messaggio->getMessaggiRicevutiStudente($email_login);

        if ($sql_messaggiRicevuti!=false) {
            // Stampa i valori di tutti i messaggi
            echo "<ul class='mess-list'>";
            while ($mess = $sql_messaggiRicevuti->fetch_assoc()) {
                echo "<li class='mess-item'>";
                foreach ($mess as $key => $value) {
                    if ($key !== 'Id') {
                        echo "<p><Label class='labelBold'>" . ucfirst($key) . ":</label> " . $value . "</p><br>";
                    }
                }
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='pMess'>Non sono presenti messaggi ricevuti</p>";
        }

        echo "<h2 class ='messH2'>Messaggi inviati:</h2>";
        $messaggiInviati = $messaggio->getMessaggiInviatiStudente($email_login);


        if ($messaggiInviati!=false) {
            // Stampa i valori di tutti i messaggi
            echo "<ul class='mess-list'>";
            while ($mess = $messaggiInviati->fetch_assoc()) {
                echo "<li class='mess-item'>";
                foreach ($mess as $key => $value) {
                    if ($key !== 'Id') {
                        echo "<p><Label class='labelBold'>" . ucfirst($key) . ":</label> " . $value . "</p><br>";
                    }
                }
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class = 'pMess'>Non sono presenti messaggi inviati</p>";
        }

        
        ?>


    </div>
</body>

</html>