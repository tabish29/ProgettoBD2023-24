<?php
include "../../connessione.php";
include '../../Condiviso/Quesito.php';
if (!isset($_SESSION)) {
    session_start();
}
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creazione Quesito</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9acac;
            line-height: 1.4;
        }
        .container {
            text-align: center;
            width: 70%;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9acac;
            border-radius: 5px;
        }
        .salvaBtn {
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
        
    </style>
</head>
<body>
    <div class="container">

        <?php
         $mongoDBManager = connessioneMongoDB();
        $quesito = new Quesito();
        $titoloTest = "";
        $domanda = "";
        if (isset($_GET['id'])){
            $campiSchermataPrecedente = explode(";",$_GET['id']);
            $titoloTest = $campiSchermataPrecedente[0];
            $domanda = $campiSchermataPrecedente[1];
        }
        
        $ottieniCampoTesto = $quesito->ottieniCampoTesto($titoloTest, $domanda);
        
        if ($ottieniCampoTesto == false) {
            echo "Errore nella query";
        } else {
            $campiTesto = array();
            while ($riga = $ottieniCampoTesto->fetch_assoc()) {
                $campiTesto[] = $riga['CampoTesto'];
            }
        }
        ?>

        <h1>Seleziona la risposta corretta</h1>
        <form action="selezionaRispostaCorretta.php" method="POST">
            
        <label for="rispostaCorretta">Risposta Corretta</label>
        <select name="rispostaCorretta" id="rispostaCorretta" required onchange="updateRispostaSelezionata()">
            <option value="">Seleziona una risposta</option>
            <?php
            foreach ($campiTesto as $campo) {
                echo "<option value='$campo'>$campo</option>";
            }
            ?>
        </select>
        <input type="hidden" name="titoloTest" value="<?php echo $titoloTest; ?>">
        <input type="hidden" name="domanda" value="<?php echo $domanda; ?>">
        <input type="hidden" name="rispostaSelezionata" id="rispostaSelezionata" value=""> <!-- Aggiunto un ID -->
        <input type="submit" class="salvaBtn" value="Salva">
        </form>
        
    </div>
    <script>
        function updateRispostaSelezionata() {
            var select = document.getElementById("rispostaCorretta");
            var selectedValue = select.options[select.selectedIndex].value;
            document.getElementById("rispostaSelezionata").value = selectedValue;
        }
    </script>
    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' ){
            $domanda = $_POST['domanda'];
            $rispostaSelezionata = $_POST['rispostaSelezionata'];
            $titoloTest = $_POST['titoloTest'];
            $quesito = new Quesito();
            $sql_inserisciRispostaCorretta = $quesito->setOpzioneRispostaCorretta($titoloTest, $domanda, $rispostaSelezionata); 
            // se l'update avviene con successo questa variabile diventa = 1, quindi proseguo
            if ($sql_inserisciRispostaCorretta = 1) {
                $document = ['Tipologia Evento' => 'Aggiornamento', 'Evento' => 'Selezionata Opzione risposta correta: '.$rispostaSelezionata.' ,per il quesito risposta chiusa con id: '.$domanda.' del test:'.$titoloTest, 'Orario' => date('Y-m-d H:i:s')];
                writeLog($mongoDBManager, $document); 
                echo "Risposta corretta inserita con successo.";
                header("Location: modificaTest.php?id=$titoloTest");
                exit;
            } else {
                echo "Errore: " . $sql_inserisciRispostaCorretta . "<br>" . $conn->error;
            }
        }
    ?>
</body>