<?php
if (!isset($_SESSION)) {
    session_start();
} 
include '../connessione.php';
include '../Condiviso/Utente.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #ff0000;
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

        .login-form {
            display: inline-block;
            width: 300px;
            /* Larghezza dei form */
            height: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin: 20px;
            /* Spaziatura tra i form */
        }

        .login-form h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="parteSuperiore">
        <a href="login.php" class="collegamenti">Accedi</a>
        <a href="registrazione.php" class="collegamenti">Registrati</a>
    </div>
    <div class='parteInferiore'>
        <div class="login-form">
            <!-- Form per il login -->
            <form action="login.php" method="POST">
                <h2>Login Utente</h2>
                <label class='labelReg' for="ruolo_login">Ruolo:</label>
                <select class='areaInsRuolo' for="ruolo_login" name="ruolo_login">
                    <option value="docente">Docente</option>
                    <option value="studente">Studente</option>
                </select>

                <label class='labelReg' for="email_login">Email:</label>
                <input class='areaIns' type="email" id="email_login" name="email_login" required>

                <label class='labelReg' for="password_login">Password:</label><br>
                <input class='areaIns' type="password" id="password_login" name="password_login" required>

                <input type="submit" value="Accedi">
            </form>
        </div>
    </div>

    <?php
    $utente = new Utente();
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $_SESSION['email'] = "";
        $_SESSION['ruolo'] = "";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recupera l'email dal form di login
        $email_login = isset($_POST['email_login']) ? $_POST['email_login'] : '';
        $ruolo_login = isset($_POST['ruolo_login']) ? $_POST['ruolo_login'] : '';
        $password_login = isset($_POST['password_login']) ? $_POST['password_login'] : '';

        // Verifica se email_login, ruolo_login e password_login sono presenti
        if (empty($email_login) || empty($ruolo_login) || empty($password_login)) {
            echo '<script>window.alert("Email e ruolo devono essere specificati");</script>';
        } else {
            // Query per verificare se l'email esiste nella tabella del ruolo selezionato
            $emailPresente = "";
            if ($ruolo_login === "docente") {
                $emailPresente = $utente->emailPresenteDocente($email_login, $password_login);
            } else if ($ruolo_login === "studente") {
                $emailPresente = $utente->emailPresenteStudente($email_login, $password_login);
            }


            // Verifica se l'email esiste nella tabella del ruolo selezionato
            if ($emailPresente->num_rows <= 0) {
                echo '<script>window.alert("Credenziali errate!");</script>';
            } else {
                //Imposta le variabili di sessione
                $_SESSION['email'] = $email_login; //NON SPOSTARE DA QUI
                $_SESSION['ruolo'] = $ruolo_login; //NON SPOSTARE DA QUI
                if ($ruolo_login === "docente") {
                    echo '<script>window.alert("Accesso effettuato!");
                            window.location.href = "../Docente/navBar/testDocenti.php";
                        </script>';
                } else if ($ruolo_login === "studente") {
                    echo '<script>window.alert("Accesso effettuato!");
                            window.location.href = "../Studente/testStudenti.php";
                        </script>';
                }
            }
        }
    }
    ?>
</body>

</html>