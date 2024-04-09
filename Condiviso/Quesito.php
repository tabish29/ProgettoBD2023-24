<?php
    include '../connessione.php';

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

    function verificaRispostaQuesitoCodice($titoloTest, $numeroProgressivoQuesito, $queryUtente){
        $queryGiustaDaRunnare = $this->ottieniQueryQuesito($titoloTest, $numeroProgressivoQuesito);
        $risultati = array();
        for ($i = 0; $i < count($queryGiustaDaRunnare); $i++){
            $risultati[] = $this->runnaQuery($queryGiustaDaRunnare[$i]);
        }
        $risultatoUtente = $this->runnaQuery($queryUtente);
        if (in_array($risultatoUtente, $risultati)){
            return true;
        } else {
            return false;
        }
    }

    function runnaQuery($query){
        
        // Esegui la query
        $result = $_SESSION['conn']->query($query);
        $_SESSION['conn']->next_result();
    
        // Verifica se la query ha prodotto risultati
        if ($result->num_rows > 0) {
            // Inizializza la stringa di output con l'intestazione della tabella
            $output = "<table border='1'><tr>";
    
            // Ottieni i nomi delle colonne
            $fieldinfo = $result->fetch_fields();
            foreach ($fieldinfo as $val) {
                $output .= "<th>{$val->name}</th>";
            }
            $output .= "</tr>";
    
            // Ottieni i dati e aggiungili alla stringa di output
            while ($row = $result->fetch_assoc()) {
                $output .= "<tr>";
                foreach ($row as $value) {
                    $output .= "<td>{$value}</td>";
                }
                $output .= "</tr>";
            }
            $output .= "</table>";
    
            // Ritorna la stringa tabellare dei risultati
            return $output;
        } else {
            // Se la query non ha prodotto risultati, ritorna un messaggio di nessun risultato
            return "Nessun risultato trovato.";
        }
    }

    function ottieniQueryQuesito($titoloTest, $numeroProgressivoQuesito){
        $sql_ottieniQuery = "SELECT TestoSoluzione FROM Soluzione WHERE NumeroProgressivo = $numeroProgressivoQuesito AND TitoloTest = '$titoloTest'";
        $result_ottieniQuery = $_SESSION['conn']->query($sql_ottieniQuery);
        
        $soluzioni = array();
        while ($row = $result_ottieniQuery->fetch_assoc()){
            $soluzioni[] = $row['TestoSoluzione'];
        }
        $_SESSION['conn']->next_result();
        return $soluzioni;
    }
}
?>