<?php
include '../connessione.php';
include 'Quesito.php';

    class Test{


        function trovaIdCompletamento($testId, $emailStudente) {
             
            // Cerca l'ID del completamento per il test e lo studente specificati
            $sql_trovaCompletamento = "SELECT NumeroProgressivo FROM COMPLETAMENTO WHERE TitoloTest = '$testId' AND EmailStudente = '$emailStudente'";
            $result_trovaCompletamento = $_SESSION['conn']->query($sql_trovaCompletamento);
    
            if ($result_trovaCompletamento->num_rows > 0) {
                // Se trova un completamento, restituisce il suo ID
                $completamento = $result_trovaCompletamento->fetch_assoc();
                return $completamento['NumeroProgressivo'];
            } else {
                // Se non trova un completamento, restituisce false o gestisce la situazione in base alle tue esigenze
                return false;
            }
        }

        function creaOApriCompletamento($testId, $emailStudente) {
             
    
            // Controlla se esiste già un completamento per questo test e questo studente
            $sql_cercaCompletamento = "SELECT * FROM COMPLETAMENTO WHERE TitoloTest = '$testId' AND EmailStudente = '$emailStudente'";
            $result_cercaCompletamento = $_SESSION['conn']->query($sql_cercaCompletamento);
    
            if ($result_cercaCompletamento->num_rows == 0) {
                // Se non esiste, crea un nuovo completamento
                $sql_creaCompletamento = "INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente) VALUES ('Aperto', '$testId', '$emailStudente')";
                $_SESSION['conn']->query($sql_creaCompletamento);
                $_SESSION['conn']->next_result();
            } else {
                // Se esiste, apri il completamento se è stato concluso
                $stato = $result_cercaCompletamento->fetch_assoc()['Stato'];
                if ($stato == 'Concluso') {
                    $sql_apriCompletamento = "UPDATE COMPLETAMENTO SET Stato = 'Aperto' WHERE TitoloTest = '$testId' AND EmailStudente = '$emailStudente'";
                    $_SESSION['conn']->query($sql_apriCompletamento);
                    $_SESSION['conn']->next_result();
                }
            }
        }

        function ottieniTest($titoloTest) {
             
            $sql_select_test = "SELECT * FROM TEST WHERE Titolo = ?";
            $stmt = $_SESSION['conn']->prepare($sql_select_test);
            $stmt->bind_param("s", $titoloTest);
            $stmt->execute();
            $result = $stmt->get_result();
            $test = $result->fetch_assoc();
            $stmt->close();
            return $test;
        }
    
        function ottieniQuesitiPerTest($titoloTest) {
             
            $sql_quesiti_test = "CALL VisualizzaQuesitiPerTest(?)";
            $stmt = $_SESSION['conn']->prepare($sql_quesiti_test);
            $stmt->bind_param("s", $titoloTest);
            $stmt->execute();
            $result = $stmt->get_result();
            $quesiti = [];
            while ($row = $result->fetch_assoc()) {
                $quesiti[] = $row;
            }
            $stmt->close();
            return $quesiti;
        }
    
        function inserisciRispostaQuesitoRispostaChiusa($idCompletamento, $testId, $rispostaData, $numQuesito) {
             
            $sql = "CALL inserisciRispostaQuesitoRispostaChiusa(?, ?, ?, ?)";
            $stmt = $_SESSION['conn']->prepare($sql);
            $stmt->bind_param("issi", $idCompletamento, $testId, $rispostaData, $numQuesito);
            $stmt->execute();
            $stmt->close();
        }
         
        function inserisciRispostaQuesitoCodice($idCompletamento, $testId, $rispostaData, $numQuesito) {
             
            $quesitoOgg = new Quesito();
            $rispostaCorretta = $quesitoOgg->ottieniRispostaCorrettaCodice($testId, $numQuesito);
            $esito = $quesitoOgg->verificaRispostaCodice($testId, $numQuesito, $rispostaData, $rispostaCorretta);
            $sql = "CALL inserisciRispostaQuesitoCodice(?, ?, ?, ?, ?)";
            $stmt = $_SESSION['conn']->prepare($sql);
            $stmt->bind_param("issib", $idCompletamento, $testId, $rispostaData, $numQuesito, $esito);
            $stmt->execute();
            $stmt->close();
        }
       
        function visualizzaEsitoRisposta($idCompletamento, $testId, $numQuesito) {
             
            $esito = false;
            $sql = "CALL visualizzaEsitoRisposta(?, ?, ?, @esito)";
            $stmt = $_SESSION['conn']->prepare($sql);
            $stmt->bind_param("isi", $idCompletamento, $testId, $numQuesito);
            $stmt->execute();
            $stmt->close();
    
            // Ora esegui una query separata per recuperare il valore del parametro di output
            $sql_output = "SELECT @esito AS esito";
            $result = $_SESSION['conn']->query($sql_output);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $esito = $row['esito'];
            }
            return $esito;
        }
    
        function ottieniQuesiti($titoloTest) {
            echo "sono in ottieniQuesiti". "<br>";
            $datiQuesiti = array();
            $sql_quesiti_test = "CALL VisualizzaQuesitiPerTest('$titoloTest')";
            echo "call eseguita". "<br>";
            $result_quesiti_test = $_SESSION['conn']->query($sql_quesiti_test);
            echo "Errore: " . $result_quesiti_test->error;
            $_SESSION['conn']->next_result();
            echo "next result eseguito". "<br>";
            echo "ciao 1". "<br>";
            if ($result_quesiti_test->num_rows > 0) {
                echo "ciao 2". "<br>";
                while ($row = $result_quesiti_test->fetch_assoc()) {
                    echo "ciao 3". "<br>";
                    $datiQuesiti[] = $row;
                    $QuesitoOggetto = new Quesito();
                    if ($QuesitoOggetto->verificaTipologiaRispostaChiusa($titoloTest, $row['NumeroProgressivo'])) {
                        $datiQuesiti[count($datiQuesiti) - 1]['Tipologia'] = "Risposta Chiusa";
                    } else if ($QuesitoOggetto->verificaTipologiaCodice($titoloTest, $row['NumeroProgressivo'])) {
                        $datiQuesiti[count($datiQuesiti) - 1]['Tipologia'] = "Codice";
                    }
                    echo "ciao 4". "<br>";
                }
            } else {
                echo "Nessun quesito presente";
            }
            return $datiQuesiti;
        }  
        
        function ottieniQuesitiRC($titoloTest, $numeroProgressivo){
            $sql_quesitoRC = "SELECT * FROM QUESITORISPOSTACHIUSA WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
            $result_quesitoRC = $_SESSION['conn']->query($sql_quesitoRC);
            $_SESSION['conn']->next_result();
        
            $datiQuesiti = array();
            if ($result_quesitoRC->num_rows > 0) {
                $sql_soluzioni = "SELECT CampoTesto, RispostaCorretta FROM OPZIONERISPOSTA WHERE NumeroProgressivoQuesito = $numeroProgressivo AND TitoloTest = '$titoloTest'";
                $result_soluzioni = $_SESSION['conn']->query($sql_soluzioni);
                $_SESSION['conn']->next_result();
                $soluzione = $result_soluzioni->fetch_assoc();
                $soluzione['Tipologia'] = "Risposta Chiusa";
                $datiQuesiti[] = $soluzione;
            }
            return $datiQuesiti;
        }

        function ottieniQuesitiCodice($titoloTest, $numeroProgressivo){
            $sql_quesitoCodice = "SELECT * FROM QUESITOCODICE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
            $result_quesitoCodice = $_SESSION['conn']->query($sql_quesitoCodice);
            $_SESSION['conn']->next_result();
        
            $datiQuesiti = array();
            if ($result_quesitoCodice->num_rows > 0) {
                $sql_soluzioni = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
                $result_soluzioni = $_SESSION['conn']->query($sql_soluzioni);
                $_SESSION['conn']->next_result();
                $soluzione = $result_soluzioni->fetch_assoc();
                $soluzione['Tipologia'] = "Codice";
                $datiQuesiti[] = $soluzione;
            }
            return $datiQuesiti;
        }

        function ottieniRisposte($numeroProgressivo, $titoloTest){
            $datiRisposte = array();
            $sql_soluzioni = "SELECT CampoTesto, RispostaCorretta FROM OPZIONERISPOSTA WHERE NumeroProgressivoQuesito = $numeroProgressivo AND TitoloTest = '$titoloTest'";
            $result_soluzioni = $_SESSION['conn']->query($sql_soluzioni);
            $_SESSION['conn']->next_result();
            while ($row = $result_soluzioni->fetch_assoc()) {
                $row['Tipologia'] = "Risposta Chiusa";
                $datiRisposte[] = $row;
            }
            return $datiRisposte;
        }

        function ottieniSoluzioni($numeroProgressivo, $titoloTest){
            $datiSoluzioni = array();
            $sql_soluzioni = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
            $result_soluzioni = $_SESSION['conn']->query($sql_soluzioni);
            $_SESSION['conn']->next_result();
            while ($row = $result_soluzioni->fetch_assoc()) {
                $row['Tipologia'] = "Codice";
                $datiSoluzioni[] = $row;
            }
            return $datiSoluzioni;
        }
    }
    
?>