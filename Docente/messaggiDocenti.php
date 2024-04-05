<?php
    if (!isset($_SESSION)){
        session_start();
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
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            min-height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f9acac;
            border-radius: 5px;
            text-align: center;
            word-wrap: break-word; /* Imposta il wrapping del testo */

            
        }
        .messH2{
            text-align: center;
            margin-bottom: 20px;
            font:  sans-serif;
            font-style: italic;
            font-size: medium;
        }

        .test-list {
            list-style-type: none;
            padding: 0;
            width: 100%;
            margin: 0 auto; /* Centra la lista */
            text-align: center; /* Allinea il testo a sinistra all'interno della lista */
        }
        .test-item {
            width: 50%;
            padding: 10px;
            margin-bottom: 5px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            text-align: left; /* Allinea il testo a sinistra all'interno degli elementi della lista */
            display: inline-block; /* Imposta gli elementi della lista come blocchi inline */
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
        .pMess{
            font:
            1rem 'Fira Sans',
            arial;
            font-size: 16px;
            
            border-width: auto;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        .buttonInvia{
            margin-top: 20px;
            background-color: greenyellow;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .labelBold{
            font-weight: bold;
        }
        .test-item p, .test-item label{
            font-size: 15px;
        }
    </style>
</head>
<body>
    <div class="container">

        
        <?php
        
            include 'navbarDocente.php';
            include '../connessione.php';
        ?>
        <div class="btn-container">
            <a href="nuovoMessaggioDocente.php" class="buttonInvia">Invia Nuovo Messaggio</a>
        </div>
        <?php 
            // Verifica se l'utente è autenticato
            if (!isset($_SESSION['email']) || !isset($_SESSION['ruolo'])) {
                // Redirect a una pagina di login se l'utente non è autenticato
                header("Location: index.html");
                exit();
            }
            

            $email_login = $_SESSION['email'];
            $ruolo_login = $_SESSION['ruolo'];

            echo "<h2 class='messH2'>Messaggi ricevuti: </h2>";

            $sql_all_messagesRicevuti = "SELECT * FROM ricezionedocente as RD, messaggio as M, inviostudente as S WHERE ((EmailDocenteDestinatario = '$email_login') AND (RD.TitoloTest = M.TitoloTest) AND (RD.TitoloTest = S.TitoloTest) AND (M.id = RD.Id))";
                
            $result_all_messagesRicevuti = $conn->query($sql_all_messagesRicevuti);

            if ($result_all_messagesRicevuti->num_rows > 0) {
                // Stampa i valori di tutti i messaggi
                echo "<ul class='test-list'>";
                while ($row = $result_all_messagesRicevuti->fetch_assoc()) {
                    echo "<li class='test-item'>";
                    foreach ($row as $key => $value) {
                        if ($key !== 'Id'){
                            echo "<p><Label class='labelBold'>" . ucfirst($key) . ":</label> " . $value . "</p><br>";
                        }                    
                    }
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='pMess'>Non sono presenti messaggi ricevuti</p>";
            }

            echo "<h2 class='messH2'>Messaggi inviati:</h2>";
            $sql_all_messagesInviati = "SELECT * FROM messaggio as M, inviodocente as D WHERE (M.Id = D.Id) AND (D.emailDocenteMittente = '$email_login')";
                
            $result_all_messagesInviati = $conn->query($sql_all_messagesInviati);

            if ($result_all_messagesInviati->num_rows > 0) {
                // Stampa i valori di tutti i messaggi
                echo "<ul class='test-list'>";
                while ($row = $result_all_messagesInviati->fetch_assoc()) {
                    echo "<li class='test-item'>";
                    foreach ($row as $key => $value) {
                        if ($key !== 'Id'){
                            echo "<p><Label class='labelBold'>" . ucfirst($key) . ":</label> " . $value . "</p><br>";
                        }
                    }
                    echo "</li><br>";
                }
                echo "</ul>";
            } else {
                echo "<p class='pMess'>Non sono presenti messaggi inviati</p>";
            }

            // Chiusura della connessione
            //$conn->close();
        ?>
        
        
    </div>
</body>
</html>
