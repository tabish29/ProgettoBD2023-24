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
                $datiTest = []; // NumProgressivo, Tipo, TitoloTest, NumeroRisposte, NumeroRisposteInserite 
                $campiSchermataPrecedente = explode(";",$_GET['id']);
                array_push($datiTest, $campiSchermataPrecedente[0]);   
                array_push($datiTest, $campiSchermataPrecedente[1]);             

                $sql_ottieniDatiQuesito = "SELECT TitoloTest, NumeroRisposte FROM Quesito WHERE NumeroProgressivo = $datiTest[0]";
                $result = $conn->query($sql_ottieniDatiQuesito);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $titoloTest = $row['TitoloTest'];
                    $numRisposte = $row['NumeroRisposte'];
                    array_push($datiTest, $titoloTest);
                    array_push($datiTest, $numRisposte);
                }
                array_push($datiTest, 0); 
                
                $_SESSION['datiTestAttuale'] = $datiTest;
                $conn->next_result();
                //echo "titolo: " . $titoloTest . " tipo: " . $tipoQuesito . " num: " . $numQuesito;
            }


            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['datiTestAttuale']) && !empty($_SESSION['datiTestAttuale'])) {
                $datiTest = $_SESSION['datiTestAttuale'];
                $soluzioneCorretta = "";
                if ($datiTest[1]=="RC"){
                    $soluzioneCorretta = isset($_POST['soluzioneCorretta']) ? $_POST['soluzioneCorretta'] : 0;

                }
                $valoreInserito = $_POST['soluzioneT'];
                $_SESSION['datiTestAttuale'][4] = $datiTest[4] + 1;
                $datiTest = $_SESSION['datiTestAttuale'];
                
                //Scommentare dopo la realizzazione delle query
                $sql_queryNuovaOpzioneOSoluzione = ''; 
                if ($datiTest[3] - $datiTest[4] > 0){
                    echo "Devi inserire ancora " . ($datiTest[3] - $datiTest[4]) . " risposte";

                    if ($datiTest[1] == "RC"){
                        $sql_queryNuovaOpzioneOSoluzione = "CALL InserimentoOpzioneRisposta('$datiTest[2]',$datiTest[0], '$valoreInserito',$soluzioneCorretta)";

                        
                    } else if ($datiTest[1] == "COD"){
                        $sql_queryNuovaOpzioneOSoluzione = "CALL InserimentoSoluzione('$datiTest[2]',$datiTest[0], '$valoreInserito')";
                        
                    }

                    if ($conn->query($sql_queryNuovaOpzioneOSoluzione) === FALSE || mysqli_affected_rows($conn) == 0){
                        echo "<p>Errore nell'inserimento: " . $conn->error . "</p>";
                    } else {
                        echo "<p>Inserimento avvenuto con successo</p>";
                        $datiTest[4]++;
                    }
                } else if ($datiTest[3] - $datiTest[4] == 0){
                    if ($datiTest[1] == "RC"){
                        $sql_queryNuovaOpzioneOSoluzione = "CALL InserimentoOpzioneRisposta('$datiTest[2]',$datiTest[0], '$valoreInserito',$soluzioneCorretta)";

                        
                    } else if ($datiTest[1] == "COD"){
                        $sql_queryNuovaOpzioneOSoluzione = "CALL InserimentoSoluzione('$datiTest[2]',$datiTest[0], '$valoreInserito')";
                        
                    }
                    $conn->query($sql_queryNuovaOpzioneOSoluzione);
                    $conn->next_result();
                    $datiTest[4]++;
                    echo "<p>Numero massimo di risposte raggiunto</p>";
                    $titolo = $datiTest[2];
                    unset($_SESSION['datiTestAttuale']);
                    echo "<a href='modificaTest.php?id=$titolo' class='btn'>Torna alla modifica del test</a>";
                } 
                
            }
        ?>
        
        <form id="quesitoForm" method="post" action="inserisciQuesitoSpecifico.php">
            <div class="form-group">
                <label for="soluzioneT">Testo soluzione:</label>
                <input type="text" id="soluzioneT" name="soluzioneT">
            </div>
            <?php
            if ($datiTest[1] == "RC") { // Se il tipo di quesito è a risposta chiusa
                echo '<div class="form-group">';
                echo '<label for="soluzioneCorretta">Soluzione corretta:</label>';
                echo '<input type="checkbox" id="soluzioneCorretta" name="soluzioneCorretta" value="1">';
                echo '<label> Attenzione, inserisci solo un\'opzione corretta</label>';
                echo '</div>';
            }
            ?>
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
