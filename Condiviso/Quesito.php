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
        global $conn;
        $rispostaCorretta = "SELECT TestoSoluzione FROM SOLUZIONE WHERE NumeroProgressivo = $numeroProgressivo AND TitoloTest = '$titoloTest'";
        $rispostaCorretta = $conn -> query($rispostaCorretta);
        $rispostaCorretta = $rispostaCorretta -> fetch_assoc();
        $rispostaCorretta = $rispostaCorretta['TestoSoluzione'];
        echo "risposta corretta: " . $rispostaCorretta;
        return $rispostaCorretta;

    }

    function verificaRispostaCodice($testId, $numQuesito, $rispostaData, $rispostaCorretta) {
        global $conn;
            $soluzione = $conn -> prepare($rispostaCorretta);                
            $soluzione -> execute();
            $soluzione = $soluzione -> get_result();
    
            $rispostaDataSoluzione = $conn -> prepare($rispostaData);
            $rispostaDataSoluzione -> execute();
            $rispostaDataSoluzione = $rispostaDataSoluzione -> get_result();
            
            if($rispostaDataSoluzione == $soluzione) { 
                return 1;
            } else {
                return 0;
            }
        
    }

    

}
?>