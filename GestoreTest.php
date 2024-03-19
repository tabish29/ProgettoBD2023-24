<?php
class GestoreTest {
    public function creaGrafica() {
        
        echo "
        <form id='creaTestForm' action='funzioniPerTest.php' method='post'>
            <label for='titolo'>Titolo:</label>
            <input type='text' id='titolo' name='titolo' required>
            <br>
            <label for='fotoLabel'>Foto:</label>
            <input type='file' id='fotoF' name='foto'>
            <br>
            <label for='visibilita'>Visibilità:</label>
            <input type='checkbox' id='visibilitaCB' name='visibilita'>
            <br>
            <input type='hidden' name='action' value='crea'>
            <button type='submit' id='creaTestButton'>Crea</button>
        </form>
        ";
    }  
    
}
?>