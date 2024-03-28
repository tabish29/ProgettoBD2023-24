<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    .container {
      width: 80%; 
      margin: 20px auto; 
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
    }
    p {
      font-size: 18px; 
      color: #333; 
      line-height: 1.5; 
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: large;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>HomePage Docente</h2>
    <p>Questa è la home page dove puoi gestire i tuoi dati.</p>
    <nav class="navbar navbar-default">
      <div>
        <ul class="nav navbar-nav">
          <li <?php echo basename($_SERVER['PHP_SELF']) == 'testDocenti.php' ? 'class="active"' : ''; ?>>
            <a href="testDocenti.php">Gestione Test</a>
          </li>
          <li <?php echo basename($_SERVER['PHP_SELF']) == 'messaggiDocenti.php' ? 'class="active"' : ''; ?>>
            <a href="messaggiDocenti.php">Messaggi</a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</body>
</html>
