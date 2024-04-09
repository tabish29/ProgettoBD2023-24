<?php
include '../connessione.php';

    class Test{
        
        
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

        function ottieniQuesiti($titoloTest) {
            $datiQuesiti = array();
            $sql_quesiti_test = "CALL VisualizzaQuesitiPerTest('$titoloTest')";
            $result_quesiti_test = $_SESSION['conn']->query($sql_quesiti_test);
            $_SESSION['conn']->next_result();
            
            if ($result_quesiti_test->num_rows > 0) {
                while ($row = $result_quesiti_test->fetch_assoc()) {
                    $datiQuesiti[] = $row;
                    
                    if ($this->verificaTipologiaRispostaChiusa($titoloTest, $row['NumeroProgressivo'])) {
                        $datiQuesiti[count($datiQuesiti) - 1]['Tipologia'] = "Risposta Chiusa";
                    } else if ($this->verificaTipologiaCodice($titoloTest, $row['NumeroProgressivo'])) {
                        $datiQuesiti[count($datiQuesiti) - 1]['Tipologia'] = "Codice";
                    }
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