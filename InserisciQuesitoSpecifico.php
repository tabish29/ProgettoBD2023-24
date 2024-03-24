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
            $titoloTest = "";
            $tipoQuesito = "";

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $campiSchermataPrecedente = explode(";",$_GET['id']);
                $titoloTest = $campiSchermataPrecedente[0];
                $tipoQuesito = $campiSchermataPrecedente[1];
                echo "<h3>Test: $titoloTest</h3>";
                echo "<h3>Tipo Quesito: $tipoQuesito</h3>";
            }

                
            $sql_queryNuovaOpzioneOSoluzione = '';  
            $valoreInserito = $_POST['soluzioneT'];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($tipoQuesito === "RC"){
                    echo "RC: " . $valoreInserito;
                    //$sql_queryNuovaOpzioneOSoluzione = "CALL PROCEDURE InserimentoOpzioneRisposta(VALORI DA METTERE)";
                    
                } else if ($tipoQuesito === "COD"){
                    echo "COD: " . $valoreInserito;
                    //$sql_queryNuovaOpzioneOSoluzione = "CALL PROCEDURE InserimentoSoluzione(VALORI DA METTERE)";
                    
                }

                //if ($conn->query($sql_queryNuovaOpzioneOSoluzione) === FALSE && mysqli_affected_rows($conn) == 0){
                //    echo "<p>Errore nell'inserimento: " . $conn->error . "</p>";
                //} 
                
            }
        ?>
        
            <form id="quesitoForm" method="post" action="inserisciQuesitoSpecifico.php">
                
                <label for="testoSoluzione">Testo soluzione:</label>
                <input type="text" id="soluzioneT" name="soluzioneT">
                <input type="hidden" name="submitAction" id="submitAction" value="">
                <input type="submit" class="btn" id="salvataggioSoluzione" value="Salva Soluzione">
                <button type="button" class="add-button" onclick="aggiungiCampo()">Inserisci una nuova Soluzione</button>
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
