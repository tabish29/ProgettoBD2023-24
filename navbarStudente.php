<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<h2>HomePage Studente</h2>
<div class="container">
  <p>Questa è la home page dello Studente. Enjoy.</p>
</div>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <ul class="nav navbar-nav">
    <li <?php echo basename($_SERVER['PHP_SELF']) == 'testStudenti.php' ? 'class="active"' : ''; ?>>
          <a href="testStudenti.php">Test Disponibili</a>
        </li>
        <li <?php echo basename($_SERVER['PHP_SELF']) == 'messaggiStudenti.php' ? 'class="active"' : ''; ?>>
          <a href="messaggiStudenti.php">Messaggi Studente</a>
        </li>
    </ul>
  </div>
</nav>
</body>
</html>
