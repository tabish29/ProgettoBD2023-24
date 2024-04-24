<?php
    include '../../connessione.php';

class Quesito{

    function ottieniTipologiaQuesito($titoloTest, $numeroProgressivoQuesito){
        try{
            if ($this->verificaTipologiaRispostaChiusa($titoloTest, $numeroProgressivoQuesito)){
                return "Risposta Chiusa";
            } else if ($this->verificaTipologiaCodice($titoloTest, $numeroProgressivoQuesito)){
                return "Codice";
            }
        } catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }
    
    function verificaTipologiaRispostaChiusa($titoloTest, $numeroProgressivo){
        try{
            $sql_verificaTipologia = "SELECT * FROM QUESITORISPOSTACHIUSA WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
            $result_verificaTipologia = $_SESSION['conn']->query($sql_verificaTipologia);
            $_SESSION['conn']->next_result();
            if ($result_verificaTipologia->num_rows > 0){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

    function verificaTipologiaCodice($titoloTest, $numeroProgressivo){
        try{
            $sql_verificaTipologia = "SELECT * FROM QUESITOCODICE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
            $result_verificaTipologia = $_SESSION['conn']->query($sql_verificaTipologia);
            $_SESSION['conn']->next_result();
            if ($result_verificaTipologia->num_rows > 0){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

    function ottieniRispostaCorrettaCasualeCodice($titoloTest, $numeroProgressivo) {
        try{
            $sql = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = ? AND TitoloTest = ?";
            $stmt = $_SESSION['conn']->prepare($sql);
            $stmt->bind_param("is", $numeroProgressivo, $titoloTest);
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $risposte = [];

            foreach ($rows as $row) {
                $risposte[] = $row['TestoSoluzione'];
            }
        
            // Seleziona una risposta casuale se l'array non è vuoto
            if (!empty($risposte)) {
                $indexCasuale = array_rand($risposte);  
                return $risposte[$indexCasuale];  
            } else {
                return null;  
            }
        } catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

    function ottieniSingolaRispostaCorrettaCodice($titoloTest, $numeroProgressivo) {
        $sql = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = ? AND TitoloTest = ? ORDER BY RAND() LIMIT 1";
        $stmt = $_SESSION['conn']->prepare($sql);
        $stmt->bind_param("is", $numeroProgressivo, $titoloTest);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $row = $result->fetch_assoc(); // fetch_assoc() invece di fetch_all() poiché ci aspettiamo una sola riga
        
        // Controlla se abbiamo ottenuto una risposta e la restituisce, altrimenti restituisce null
        if ($row) {
            return $row['TestoSoluzione'];
        } else {
            return null;
        }
    }
    

    function verificaRispostaCodice($titoloTest, $numeroProgressivo, $rispostaData) {
        try {
            $rispostaCorretta = $this->ottieniRispostaCorrettaCasualeCodice($titoloTest, $numeroProgressivo);
            echo"la risposta corretta individuata è la seguente $rispostaCorretta";
            if (!$rispostaCorretta) {
                throw new Exception("Variabile rispostaCorretta non esiste");
            }
            // Esegue la query della soluzione corretta
            $resultSoluzione = $_SESSION['conn']->query($rispostaCorretta);
            
            $soluzioneResults = $resultSoluzione->fetch_all(MYSQLI_ASSOC);
    
            // Esegue la query della risposta data dall'utente
            $resultRispostaData = $_SESSION['conn']->query($rispostaData);
            
            $rispostaDataResults = $resultRispostaData->fetch_all(MYSQLI_ASSOC);
    
          
            if ($soluzioneResults == $rispostaDataResults) {
                return 1;  
            } else {
                return 0;  
            }
        } catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false; 
        }
    }
    

    function creaQuesitoRispostaChiusa($titoloTest, $livDifficolta, $descrizione){
        try{
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
        } catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

    function creaQuesitoCodice($titoloTest, $livDifficolta, $descrizione){
        try{
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
        catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

    function collegaTabella($numeroProgressivoQuesito, $titoloTest, $TabDaCollegare){
        try{
            $sql_creaCostituzioneQuery = "CALL CreazioneCostituzione('$numeroProgressivoQuesito', '$titoloTest', '$TabDaCollegare')";
            $risultato = $_SESSION['conn']->query($sql_creaCostituzioneQuery);
            $_SESSION['conn']->next_result();
            return $risultato;
        } catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

    function verificaPresenzaCollegamento($titoloTest, $numeroProgressivoQuesito){
        try{
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
        catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }

    }

    function setOpzioneRispostaCorretta($titoloTest, $numeroProgressivoQuesito, $rispostaSelezionata){
        try{
            $sql_inserisciRispostaCorretta = "CALL setOpzioneRispostaCorretta('$titoloTest', '$numeroProgressivoQuesito', '$rispostaSelezionata')";
            $risultato = $_SESSION['conn']->query($sql_inserisciRispostaCorretta);
            $_SESSION['conn']->next_result();
            return $risultato;
        } catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }
    
    function inserimentoOpzioneRisposta($titoloTest, $numeroProgressivoQuesito, $campoTesto){
        try{
            $sql_queryNuovaOpzioneOSoluzione = "CALL InserimentoOpzioneRisposta('$titoloTest',$numeroProgressivoQuesito, '$campoTesto',false)";
            $risultato = $_SESSION['conn']->query($sql_queryNuovaOpzioneOSoluzione);
            $_SESSION['conn']->next_result();
            if ($risultato === FALSE || mysqli_affected_rows($_SESSION['conn']) == 0) {
                return false;
            } else {
                return $risultato;
            }
        }catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

    function inserimentoSoluzione($titoloTest, $numeroProgressivoQuesito, $testoSoluzione){
        try{
            $sql_queryNuovaOpzioneOSoluzione = "CALL InserimentoSoluzione('$titoloTest',$numeroProgressivoQuesito, '$testoSoluzione')";
            $risultato = $_SESSION['conn']->query($sql_queryNuovaOpzioneOSoluzione);
            $_SESSION['conn']->next_result();
            if ($risultato === FALSE || mysqli_affected_rows($_SESSION['conn']) == 0) {
                return false;
            } else {
                return $risultato;
            }
        } 
        catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }

    }

    function ottieniCampoTesto($titoloTest, $domanda){
        try{
            $ottieniCampoTesto = "SELECT * FROM opzionerisposta WHERE TitoloTest = '$titoloTest' AND NumeroProgressivoQuesito = '$domanda'";
            $risultato = $_SESSION['conn']->query($ottieniCampoTesto);
            $_SESSION['conn']->next_result();
            if (!$risultato || $risultato->num_rows == 0) {
                return false;
            } else {
                return $risultato;
            }
        } catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

    function ottieniEsitoCodice($idCompletamento){
        try{
            $sql = "SELECT Esito FROM RISPOSTAQUESITOCODICE WHERE NumeroProgressivoCompletamento = $idCompletamento";
            $risultato = $_SESSION['conn']->query($sql);
            $_SESSION['conn']->next_result();
            return $risultato;
        } catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

    function ottieniRispostaDataRC($idCompletamento, $numeroProgressivo, $titoloTest){
        try{
            $sql = "SELECT OpzioneScelta FROM RISPOSTAQUESITORISPOSTACHIUSA WHERE NumeroProgressivoCompletamento = $idCompletamento AND NumeroProgressivoQuesito = $numeroProgressivo AND TitoloTest = '$titoloTest'";
            $risultato = $_SESSION['conn']->query($sql);
            $_SESSION['conn']->next_result();
            $risultato = $risultato->fetch_assoc();
            return $risultato['OpzioneScelta'];
        }
        catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

    function ottieniRispostaCorrettaRC($numeroProgressivo, $titoloTest){
        try{
            $sql = "SELECT CampoTesto FROM OPZIONERISPOSTA WHERE NumeroProgressivoQuesito = $numeroProgressivo AND TitoloTest = '$titoloTest' AND RispostaCorretta = 1";
            $risultato = $_SESSION['conn']->query($sql);
            $_SESSION['conn']->next_result();
            $risultato = $risultato->fetch_assoc();
            return $risultato['CampoTesto'];
        }
        catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

    function ottieniRispostaDataCodice($idCompletamento, $numeroProgressivo, $titoloTest){
        try{
            $sql = "SELECT Testo FROM RISPOSTAQUESITOCODICE WHERE NumeroProgressivoCompletamento = $idCompletamento AND NumeroProgressivoQuesito = $numeroProgressivo AND TitoloTest = '$titoloTest'";
            $risultato = $_SESSION['conn']->query($sql);
            $_SESSION['conn']->next_result();
            $risultato = $risultato->fetch_assoc();
            return $risultato['Testo'];
        } catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

    function ottieniRispostaCorrettaCodice($numeroProgressivo, $titoloTest){
        try{
            $sql = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
            $risultato = $_SESSION['conn']->query($sql);
            $_SESSION['conn']->next_result();
            $risultato = $risultato->fetch_assoc();
            return $risultato['TestoSoluzione'];
        } catch (Exception $e) {
            echo "Eccezione: " . $e->getMessage();
            return false;
        }
    }

}
?>