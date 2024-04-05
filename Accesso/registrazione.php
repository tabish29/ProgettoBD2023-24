<?php
include '../connessione.php';
if (!isset($_SESSION)){
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: rgb(0, 0, 0);
            font-size: medium;
            padding: 10px 20px;
            border: rgb(0, 0, 0);
            border-radius: 5px;
            cursor: pointer;
            width: auto;
            height: auto;
        }

        .forms-container {
            text-align: center; 
        }

        .registration-form {
            display: inline-block;
            width: 300px; /* Larghezza dei form */
            height: auto;
            padding: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin: 20px; /* Spaziatura tra i form */
        }

        .registration-form h2 {
            text-align: center;
        }

        .labelReg {
            font-size: medium;
            font-weight: bold;
            width: 100%;
        }

        .areaIns {
            margin-top: 10px;
            margin-bottom: 10px;
            height: 15px;
            width: 90%;
        }

        .areaInsRuolo {
            margin-top: 10px;
            margin-bottom: 10px;
            height: auto;
            width: 90%;
        }

        .parteSuperiore {
            height: 10%;
            background-color: #ddd7d7;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0 20px;
        }

        .parteInferiore {
            height: 80%;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ff0000;
        }

        

        .collegamenti {
            color: #000;
            text-decoration: none;
            margin-left: 20px;
            font-family: sans-serif;
            font-size: auto;
            text-decoration: underline;
            font-weight: bold;
        }
        
    </style>
    <title>Registrazione</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
    <div class="parteSuperiore">
        <a href="registrazione.php" class="collegamenti">Registrati</a>
        <a href="login.php" class="collegamenti">Accedi</a>
        <a href="Contatti.php" class="collegamenti">Contatti</a>
    </div>
    <div class="parteInferiore">
        <div class="registration-form">
            <!-- Form per la registrazione -->
            <form action="registrazione.php" method="POST">
                <h2>Registrazione Utente</h2>

                <label class='labelReg' for="ruolo">Ruolo:</label>
                <select class='areaInsRuolo' id="ruolo" name="ruolo">
                    <option value="docente">Docente</option>
                    <option value="studente">Studente</option>
                </select>

                <label class='labelReg' for="nome">Nome:</label>
                <input class='areaIns' type="text" id="nome" name="nome" required>

                <label class='labelReg' for="cognome">Cognome:</label>
                <input class='areaIns' type="text" id="cognome" name="cognome" required>

                <label class='labelReg' for="email_reg">Email:</label>
                <input class='areaIns' type="email" id="email_reg" name="email_reg" required>

                <label class='labelReg' for="password_reg">Password:</label>
                <input class='areaIns' type="password" id="password_reg" name="password_reg" required>

                <label class='labelReg' for="recapito_telefonico">Recapito Telefonico:</label>
                <input class='areaIns' type="text" id="recapito_telefonico" name="recapito_telefonico">

                <!-- Campi aggiuntivi per studenti -->
                <div id="studente-fields" style="display: none;">
                    <label class='labelReg' for="codice_alfanumerico">Codice Alfanumerico (16 cifre):</label>
                    <input class='areaIns' type="text" id="codice_alfanumerico" name="codice_alfanumerico" pattern="[0-9a-zA-Z]{16}">

                    <label class='labelReg' for="anno_immatricolazione">Anno Immatricolazione:</label>
                    <input class='areaIns' type="number" id="anno_immatricolazione" name="anno_immatricolazione" min="1900" max="2099">
                </div>

                <!-- Campi aggiuntivi per docenti -->
                <div id="docente-fields" style="display: none;">
                    <label class='labelReg' for="nome_dipartimento">Nome Dipartimento:</label>
                    <input class='areaIns' type="text" id="nome_dipartimento" name="nome_dipartimento">

                    <label class='labelReg' for="nome_corso">Nome Corso:</label>
                    <input class='areaIns' type="text" id="nome_corso" name="nome_corso">
                </div>

                <input type="submit" value="Registrati">
            </form>
        </div>
    </div>
    
    <script>
        // Funzione per gestire la visibilità dei campi aggiuntivi in base al ruolo selezionato
        function gestisciCampiAggiuntivi() {
            var ruolo = document.getElementById('ruolo').value;
            var campiStudente = document.getElementById('studente-fields');
            var campiDocente = document.getElementById('docente-fields');

            // Mostra i campi aggiuntivi per studenti
            if (ruolo === 'studente') {
                campiStudente.style.display = 'block';
                campiDocente.style.display = 'none';
            } else if (ruolo === 'docente') {
                campiStudente.style.display = 'none';
                campiDocente.style.display = 'block';
            } else {
                campiStudente.style.display = 'none';
                campiDocente.style.display = 'none';
            }
        }

        // Aggiungi un event listener per l'evento onchange al menu a discesa del ruolo
        document.getElementById('ruolo').addEventListener('change', gestisciCampiAggiuntivi);

        // Esegui la funzione una volta all'avvio per assicurarsi che i campi siano correttamente visualizzati/nascosti
        gestisciCampiAggiuntivi();
    </script>
</body>
</html>

<?php
    // Verifica se è stata inviata una richiesta POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recupero i dati inseriti nel form di registrazione
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $email = $_POST["email_reg"];
        $password = $_POST["password_reg"];
        $ruolo = $_POST['ruolo'];
        $recapito_telefonico = $_POST['recapito_telefonico'];

        // Campi aggiuntivi per studenti
        $codice_alfanumerico = "";
        $anno_immatricolazione = "";

        // Campi aggiuntivi per docenti
        $nome_corso = "";
        $nome_dipartimento = "";

        try {
            // Se il ruolo è "studente", recupera i campi aggiuntivi specifici per gli studenti
            if ($ruolo === "studente") {
                $codice_alfanumerico = $_POST['codice_alfanumerico'];
                $anno_immatricolazione = $_POST['anno_immatricolazione'];

                // Chiama la procedura di registrazione dello studente
                $sql = "CALL RegistrazioneStudente('$email', '$password','$nome', '$cognome', '$recapito_telefonico', '$anno_immatricolazione', '$codice_alfanumerico')";
                
                if ($conn->query($sql) === TRUE) {
                    echo '<script>window.alert("Registrazione avvenuta con successo!");</script>';
                } else {
                    echo '<script>window.alert("Errore, riprova!");</script>';
                }
                
            }

            // Se il ruolo è "docente", recupera i campi aggiuntivi specifici per i docenti
            if ($ruolo === "docente") {
                $nome_corso = $_POST['nome_corso'];
                $nome_dipartimento = $_POST['nome_dipartimento'];

                $sql = "CALL RegistrazioneDocente('$email', '$password','$nome', '$cognome', '$recapito_telefonico', '$nome_dipartimento', '$nome_corso')";
                if ($conn->query($sql) === TRUE) {
                    echo '<script>window.alert("Registrazione avvenuta con successo!");</script>';
                } else {
                    echo '<script>window.alert("Errore, riprova!");</script>';
                }
            }

        } catch (Exception $e) {
            echo '<script>window.alert("Errore, riprova!");</script>';
        }
    }
?>
