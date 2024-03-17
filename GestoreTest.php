<?php
class GestoreTest {
    public function crea() {

        function creaGraficaCreazioneTest() {
            echo "
            <form id='creaTestForm' action='process_creazione_test.php' method='post'>
                <label for='titolo'>Titolo:</label>
                <input type='text' id='titolo' name='titolo' required>
                <br>
                <label for='descrizione'>Descrizione:</label>
                <input type='text' id='descrizione' name='descrizione' required>
                <br>
                <label for='dataInizio'>Data inizio:</label>
                <input type='date' id='dataInizio' name='dataInizio' required>
                <br>
                <label for='dataFine'>Data fine:</label>
                <input type='date' id='dataFine' name='dataFine' required>
                <br>
                <label for='tempo'>Tempo:</label>
                <input type='number' id='tempo' name='tempo' required>
                <br>
                <label for='visibilita'>Visibilità:</label>
                <input type='number' id='visibilita' name='visibilita' required>
                <br>
                <button type='submit'>Crea</button>
            </form>
            ";
            }
            
            creaGraficaCreazioneTest();
        }

    
}
?>