<?php
    include '../../connessione.php';

class Quesito{

    function ottieniTipologiaQuesito($titoloTest, $numeroProgressivoQuesito){
        if ($this->verificaTipologiaRispostaChiusa($titoloTest, $numeroProgressivoQuesito)){
            return "Risposta Chiusa";
        } else if ($this->verificaTipologiaCodice($titoloTest, $numeroProgressivoQuesito)){
            return "Codice";
        }
    }
    
    function verificaTipologiaRispostaChiusa($titoloTest, $numeroProgressivo){
        $sql_verificaTipologia = "SELECT * FROM QUESITORISPOSTACHIUSA WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
        $result_verificaTipologia = $_SESSION['conn']->query($sql_verificaTipologia);
        $_SESSION['conn']->next_result();
        if ($result_verificaTipologia->num_rows > 0){
            return true;
        } else {
            return false;
        }
    }

    function verificaTipologiaCodice($titoloTest, $numeroProgressivo){
        $sql_verificaTipologia = "SELECT * FROM QUESITOCODICE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
        $result_verificaTipologia = $_SESSION['conn']->query($sql_verificaTipologia);
        $_SESSION['conn']->next_result();
        if ($result_verificaTipologia->num_rows > 0){
            return true;
        } else {
            return false;
        }
    }

    function ottieniRispostaCorrettaCodice($titoloTest, $numeroProgressivo){
        $rispostaCorretta = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
        $rispostaCorretta = $_SESSION['conn'] -> query($rispostaCorretta);
        $rispostaCorretta = $rispostaCorretta -> fetch_assoc();
        $rispostaCorretta = $rispostaCorretta['TestoSoluzione'];
        return $rispostaCorretta;

    }

    function verificaRispostaCodice($rispostaData, $rispostaCorretta) {
        try {
            // Esegue la query della soluzione corretta
            $resultSoluzione = $_SESSION['conn']->query($rispostaCorretta);
            if (!$resultSoluzione) {
                throw new Exception("Errore nell'esecuzione della query della soluzione: " . $_SESSION['conn']->error);
            }
            $soluzioneResults = $resultSoluzione->fetch_all(MYSQLI_ASSOC);
    
            // Esegue la query della risposta data dall'utente
            $resultRispostaData = $_SESSION['conn']->query($rispostaData);
            if (!$resultRispostaData) {
                throw new Exception("Errore nell'esecuzione della query della risposta data: " . $_SESSION['conn']->error);
            }
            $rispostaDataResults = $resultRispostaData->fetch_all(MYSQLI_ASSOC);
    
          
            if ($soluzioneResults == $rispostaDataResults) {
                return 1;  
            } else {
                return 0;  
            }
        } catch (Exception $e) {
            // Gestisce eventuali errori di database
            echo "Eccezione nella verifica della risposta: " . $e->getMessage() . "<br>";
            return 0;  
        }
    }

    function controllaSoluzioneQuesitoCodice($conn, $idQuestion, $titleTest, $queryAnswer) {
        try {
            // Prepara e esegui la query per ottenere una sola soluzione dal database
            $sql = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = ? AND TitoloTest = ? ORDER BY NumeroProgressivo DESC LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $idQuestion, $titleTest);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($row = $result->fetch_assoc()) {
                $querySolution = $row['TestoSoluzione'];
            } else {
                echo "Nessuna soluzione trovata per il quesito specificato.<br>";
                return false;
            }
        } catch (Exception $e) {
            echo "Errore durante il recupero della soluzione: " . $e->getMessage() . "<br>";
            return false;
        }
    
        try {
            // Esegui la query della soluzione
            $resultSolution = $conn->query($querySolution);
            $solutionData = $resultSolution->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            echo "Errore durante l'esecuzione della query della soluzione: " . $e->getMessage() . "<br>";
            return false;
        }
    
        try {
            // Esegui la query data dall'utente
            $resultAnswer = $conn->query($queryAnswer);
            $answerData = $resultAnswer->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            echo "Errore durante l'esecuzione della query dell'utente: " . $e->getMessage() . "<br>";
            return false;
        }
    
        // Confronta i risultati delle due query
        if ($answerData == $solutionData) {
            return true; // Le risposte sono uguali
        } else {
            return false; // Le risposte sono diverse
        }
    }
    
    

    function creaQuesitoRispostaChiusa($titoloTest, $livDifficolta, $descrizione){
        $sql_creaQuesitoQuery = "CALL CreazioneQuesitoRispostaChiusa('$titoloTest', '$livDifficolta', '$descrizione', @numeroProgressivoQuesito)";
        $risultato = $_SESSION['conn']->query($sql_creaQuesitoQuery);
        $_SESSION['conn']->next_result();
        if ($risultato === FALSE || mysqli_affected_rows($_SESSION['conn']) == 0) {
            echo "<p>Errore nella creazione del quesito: " . $_SESSION['conn']->error . "</p>";
        } else {
            $sql_numeroProgressivo = "SELECT @NumeroProgressivoQuesito AS NumeroProgressivo";
            $result = $_SESSION['conn']->query($sql_numeroProgressivo);
            $row = $result->fetch_assoc();
            $numeroProgressivoQuesito = $row['NumeroProgressivo'];
            return $numeroProgressivoQuesito;
        }
    }

    function creaQuesitoCodice($titoloTest, $livDifficolta, $descrizione){
        $sql_creaQuesitoQuery = "CALL CreazioneQuesitoCodice('$titoloTest', '$livDifficolta', '$descrizione', @numeroProgressivoQuesito)";
        $risultato = $_SESSION['conn']->query($sql_creaQuesitoQuery);
        $_SESSION['conn']->next_result();
        if ($risultato === FALSE || mysqli_affected_rows($_SESSION['conn']) == 0) {
            echo "<p>Errore nella creazione del quesito: " . $_SESSION['conn']->error . "</p>";
        } else {
            $sql_numeroProgressivo = "SELECT @NumeroProgressivoQuesito AS NumeroProgressivo";
            $result = $_SESSION['conn']->query($sql_numeroProgressivo);
            $row = $result->fetch_assoc();
            $numeroProgressivoQuesito = $row['NumeroProgressivo'];
            return $numeroProgressivoQuesito;
        }
    }

    function collegaTabella($numeroProgressivoQuesito, $titoloTest, $TabDaCollegare){
        $sql_creaCostituzioneQuery = "CALL CreazioneCostituzione('$numeroProgressivoQuesito', '$titoloTest', '$TabDaCollegare')";
        $risultato = $_SESSION['conn']->query($sql_creaCostituzioneQuery);
        $_SESSION['conn']->next_result();
        return $risultato;
    }

    function verificaPresenzaCollegamento($titoloTest, $numeroProgressivoQuesito){
        $sql_verificaPresenzaCollegamento = "CALL verificaPresenzaCollegamento('$titoloTest', '$numeroProgressivoQuesito', @risultato)";
        $_SESSION['conn']->query($sql_verificaPresenzaCollegamento);
        $_SESSION['conn']->next_result();
        $sql = "SELECT @risultato AS Risultato";
        $result = $_SESSION['conn']->query($sql);
        $row = $result->fetch_assoc();
        $risultato = $row['Risultato'];
        $_SESSION['conn']->next_result();
        if ($risultato >= 1){
            return true;
        } else {
            return false;
        }

    }

    function setOpzioneRispostaCorretta($titoloTest, $numeroProgressivoQuesito, $rispostaSelezionata){
        $sql_inserisciRispostaCorretta = "CALL setOpzioneRispostaCorretta('$titoloTest', '$numeroProgressivoQuesito', '$rispostaSelezionata')";
        $risultato = $_SESSION['conn']->query($sql_inserisciRispostaCorretta);
        $_SESSION['conn']->next_result();
        return $risultato;
    }
    
    function inserimentoOpzioneRisposta($titoloTest, $numeroProgressivoQuesito, $campoTesto){
        $sql_queryNuovaOpzioneOSoluzione = "CALL InserimentoOpzioneRisposta('$titoloTest',$numeroProgressivoQuesito, '$campoTesto',false)";
        $risultato = $_SESSION['conn']->query($sql_queryNuovaOpzioneOSoluzione);
        $_SESSION['conn']->next_result();
        if ($risultato === FALSE || mysqli_affected_rows($_SESSION['conn']) == 0) {
            return false;
        } else {
            return $risultato;
        }
    }

    function inserimentoSoluzione($titoloTest, $numeroProgressivoQuesito, $testoSoluzione){
        $sql_queryNuovaOpzioneOSoluzione = "CALL InserimentoSoluzione('$titoloTest',$numeroProgressivoQuesito, '$testoSoluzione')";
        $risultato = $_SESSION['conn']->query($sql_queryNuovaOpzioneOSoluzione);
        $_SESSION['conn']->next_result();
        if ($risultato === FALSE || mysqli_affected_rows($_SESSION['conn']) == 0) {
            return false;
        } else {
            return $risultato;
        }

    }

    function ottieniCampoTesto($titoloTest, $domanda){
        $ottieniCampoTesto = "SELECT * FROM opzionerisposta WHERE TitoloTest = '$titoloTest' AND NumeroProgressivoQuesito = '$domanda'";
        $risultato = $_SESSION['conn']->query($ottieniCampoTesto);
        $_SESSION['conn']->next_result();
        if (!$risultato || $risultato->num_rows == 0) {
            return false;
        } else {
            return $risultato;
        }
    }

    function ottieniEsitoCodice($idCompletamento){
        $sql = "SELECT Esito FROM RISPOSTAQUESITOCODICE WHERE NumeroProgressivoCompletamento = $idCompletamento";
        $risultato = $_SESSION['conn']->query($sql);
        $_SESSION['conn']->next_result();
        return $risultato;
    }

}
?>