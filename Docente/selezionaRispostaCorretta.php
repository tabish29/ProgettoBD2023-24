<?php
include "../connessione.php";
if (!isset($_SESSION)) {
    session_start();
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
        }
        .container {
            text-align: center;
            width: 70%;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9acac;
            border-radius: 5px;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <?php
        $titoloTest = "";
        $domanda = "";
        if (isset($_GET['id'])){
            $campiSchermataPrecedente = explode(";",$_GET['id']);
            $titoloTest = $campiSchermataPrecedente[0];
            $domanda = $campiSchermataPrecedente[1];
        }
        
        
        $ottieniCampoTesto = "SELECT * FROM opzionerisposta WHERE TitoloTest = '$titoloTest' AND NumeroProgressivoQuesito = '$domanda'";
        $risultato = $conn->query($ottieniCampoTesto);
        if (!$risultato || $risultato->num_rows == 0) {
            echo "Errore nella query";
        }
        $campiTesto = array();
        while ($riga = $risultato->fetch_assoc()) {
            echo "Campo test:" . $riga['CampoTesto'];
            $campiTesto[] = $riga['CampoTesto'];
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
        <input type="submit" value="Salva" class="salvaBtn">
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
            $inserisciRispostaCorretta = "UPDATE opzionerisposta SET RispostaCorretta = 1 WHERE CampoTesto = '$rispostaSelezionata' AND TitoloTest = '$titoloTest' AND NumeroProgressivoQuesito = '$domanda'";
            if ($conn->query($inserisciRispostaCorretta) === TRUE) {
                echo "Risposta corretta inserita correttamente";
                header("Location: modificaTest.php?id=$titoloTest");
                exit;
            } else {
                echo "Errore: " . $inserisciRispostaCorretta . "<br>" . $conn->error;
            }
        }
    ?>
</body>