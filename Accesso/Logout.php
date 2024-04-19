<?php
include '../Studente/navBar/navbarStudente.php';
include '../connessione.php';
include '../Docente/navBar/navBarDocente.php';

if (!isset($_SESSION)) {
    session_start();
}
session_unset(); // Unset all session variables
$conn->close();
session_destroy(); 
?>        <script>
            window.alert('Sessione terminata con successo!');
            window.location.href = '../';
          </script>";
