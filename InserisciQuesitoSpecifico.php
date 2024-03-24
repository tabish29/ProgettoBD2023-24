<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creazione Quesito</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            width: 50%;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            border-radius: 3px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .add-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .add-button:hover {
            background-color: #45a049;
        }
        .btn {
            width: 100%;
            height: 40px;
            border: none;
            padding: 10px 15px;
            border-radius: 3px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .additional-fields {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Creazione Quesito</h2>
        <?php
            include 'connessione.php';
            if (!isset($_SESSION)){
                session_start();
            }
            

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $campiSchermataPrecedente = explode(";",$_GET['id']);
                $titoloTest = $campiSchermataPrecedente[0];
                $tipoQuesito = $campiSchermataPrecedente[1];
            }

                
            $sql_queryNuovaOpzioneOSoluzione = '';  
            

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $valoreInserito = $_POST['soluzioneT'];
                $tipoQuesito = $_POST['tipoQuesito'];
                $titoloTest = $_POST['titoloTest'];

                //Scommentare dopo la realizzazione delle query
                if ($tipoQuesito == "RC"){
                    //$sql_queryNuovaOpzioneOSoluzione = "CALL PROCEDURE InserimentoOpzioneRisposta(VALORI DA METTERE)";
                    
                } else if ($tipoQuesito == "COD"){
                    //$sql_queryNuovaOpzioneOSoluzione = "CALL PROCEDURE InserimentoSoluzione(VALORI DA METTERE)";
                    
                }

                //if ($conn->query($sql_queryNuovaOpzioneOSoluzione) === FALSE && mysqli_affected_rows($conn) == 0){
                //    echo "<p>Errore nell'inserimento: " . $conn->error . "</p>";
                //} 
                
            }
        ?>
        
            <form id="quesitoForm" method="post" action="inserisciQuesitoSpecifico.php">
                <input type="hidden" name="titoloTest" value="<?php echo $titoloTest; ?>">
                <label for="tipoQ">Titolo Test: <?php echo $titoloTest; ?></label>
                <input type="hidden" name="tipoQuesito" value="<?php echo $tipoQuesito; ?>">
                <label for="tipoQ">Tipo quesito: <?php echo $tipoQuesito; ?></label>
                <label for="testoSoluzione">Testo soluzione:</label>
                <input type="text" id="soluzioneT" name="soluzioneT">
                <input type="submit" class="btn" id="salvataggioSoluzione" value="Salva Soluzione">
            </form>
            

        
    </div>

    <script>
        

        function aggiungiCampo() {
            var soluzioneInput = document.getElementById('soluzioneT');
            soluzioneInput.value = ''; // Svuota il campo "soluzioneT"
            var campoTestoInput = document.getElementById('campoTestoT');
            campoTestoInput.value = ''; // Svuota il campo "campoTestoT"
        }

        
        function setSubmitAction(action) {
            document.getElementById('submitAction' + action).value = action;
        }


    </script>
