<?php
class GestoreTest {
    public function crea() {

        function creaGraficaCreazioneTest() {
            echo "
            <form id='creaTestForm' action='funzioniPerTest.php' method='post'>
                <label for='titolo'>Titolo:</label>
                <input type='text' id='titolo' name='titolo' required>
                <br>
                <label for='fotoLabel'>Foto:</label>
                <input type='number' id='foto' name='foto' required> //Da inserire type= il file per la foto
                <br>
                <label for='visibilita'>Visibilità:</label>
                <input type='text' id='visibilita' name='visibilita' required>
                <br>
                <input type='hidden' name='action' value='crea'>
                <button type='submit' id='creaTestButton'>Crea</button>
            </form>
            ";
        }

        creaGraficaCreazioneTest();
            
           
            
        }

        

        

    
}
?>