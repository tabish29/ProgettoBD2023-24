<?php
if (!isset($_SESSION)) {
    session_start();
}
include 'navbarDocente.php';
include '../connessione.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=100%, initial-scale=1.0">
    <style>
        body {
            height: 100%;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9acac;
        }

        .container {
            width: 100%;
            min-height: 100%;
            background-color: #f9acac;
            border-radius: 5px;
            text-align: center;
            word-wrap: break-word;

        }

        
    </style>
</head>
<body>
    <div class="container">
        <h2>Ciao Nano</h2>
    </div>
</body>
