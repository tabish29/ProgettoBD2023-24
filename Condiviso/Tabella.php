<?php
include '../../connessione.php';

    class Tabella{

        function tabelleDelTest($titoloTest){
            try{
                $queryCostituzione = "SELECT NomeTabella FROM COSTITUZIONE WHERE TitoloTest = ?";
                $stmt = $_SESSION['conn']->prepare($queryCostituzione);
                $stmt->bind_param("s", $titoloTest);
                $stmt->execute();
                $resultCostituzione = $stmt->get_result();

                $nomiTabella = [];
                while ($row = $resultCostituzione->fetch_assoc()) {
                    $nomiTabella[] = $row['NomeTabella'];
                }
                $stmt->close();
                return $nomiTabella;
            }
            catch (Exception $e) {
                echo"Eccezione: " . $e->getMessage();
                return false;
            }
        }

        function ottieniTutteTabelle(){
            try{
                $queryTabellaEsercizio = "SELECT Nome FROM TABELLADIESERCIZIO";
                $resultTabellaEsercizio = $_SESSION['conn']->query($queryTabellaEsercizio);
                return $resultTabellaEsercizio;
            }
            catch (Exception $e) {
                echo"Eccezione: " . $e->getMessage();
                return false;
            }
        }

        function ottieniTabelleDocente($emailDocente){
            try{
                $queryTabellaEsercizio = "SELECT Nome FROM TABELLADIESERCIZIO WHERE EmailDocente = '$emailDocente'";
                $resultTabellaEsercizio = $_SESSION['conn']->query($queryTabellaEsercizio);
                return $resultTabellaEsercizio;
            } catch (Exception $e) {
                echo"Eccezione: " . $e->getMessage();
                return false;
            }
        }

        function ottieniDatiTabella($nomiTabella){
            try{
                // Seconda query per ottenere informazioni dalle tabelle di esercizio
                $placeholders = implode(',', array_fill(0, count($nomiTabella), '?')); // Crea una stringa di placeholders
                $queryTabellaEsercizio = "SELECT Nome, DataCreazione, num_righe, EmailDocente FROM TABELLADIESERCIZIO WHERE Nome IN ($placeholders)";
                $stmt = $_SESSION['conn']->prepare($queryTabellaEsercizio);
                $stmt->bind_param(str_repeat('s', count($nomiTabella)), ...$nomiTabella); // Assegna dinamicamente i parametri alla query
                $stmt->execute();
                $resultTabellaEsercizio = $stmt->get_result();
                $stmt->close();
                return $resultTabellaEsercizio;
            } catch (Exception $e) {
                echo"Eccezione: " . $e->getMessage();
                return false;
            }
        }

        function tabelleDelQuesito($titoloTest, $numeroQuesito){
            try{
                $queryCostituzione = "SELECT NomeTabella FROM COSTITUZIONE WHERE TitoloTest = '$titoloTest' AND NumeroProgressivoQuesito = $numeroQuesito";
                $risultato = $_SESSION['conn']->query($queryCostituzione);

                $nomiTabella = [];
                while ($row = $risultato->fetch_assoc()) {
                    $nomiTabella[] = $row['NomeTabella'];
                }
                $_SESSION['conn']->next_result();

                return $nomiTabella;
            } catch (Exception $e) {
                echo"Eccezione: " . $e->getMessage();
                return false;
            }
        }

        function ottieniContenutoTabella($nomeTabella){
            try{
                $query = "SELECT * FROM " . $nomeTabella;
                $risultato = $_SESSION['conn']->query($query);
                return $risultato;
            } catch (Exception $e) {
                echo"Eccezione: " . $e->getMessage();
                return false;
            }
        }
        
       
    }
    
?>