<?php
include '../Studente/navBar/navbarStudente.php';
include '../connessione.php';
include '../Docente/navBar/navBarDocente.php';

if (!isset($_SESSION)) {
    session_start();
}

$messaggio = "";
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['message'])) {
      $messaggio = $_GET['message'];
  }
} else {
  $messaggio = "Logout effettuato con successo.";
}

session_unset(); // Unset all session variables
$conn->close();
session_destroy(); 
?>        <script>
            window.alert("<?php echo $messaggio; ?>");
            window.location.href = '../';
          </script>";
