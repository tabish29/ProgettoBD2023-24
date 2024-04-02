<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    .containerNav {
      width: 50%; 
      margin: 20px auto; 
      padding: 20px;
      font-weight: bold;
      background-color: #fff8dc;
      border-radius: 5px;
    }
    .containerNavH2 {
      text-align: center;
      font-weight: bold;
      margin-bottom: 20px;
      font-size: large;
    }
   
    .btnLogout{
      font: sans-serif;
      font-weight: bold;
      font-size: medium;
      background-color: #ff0000;
      margin: 5px;
      border-radius: 5px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      height: auto;
      width: auto;
      position: absolute;
      top:0;
      right: 0;
    }
    p{
      text-align: center;
      font: sans-serif;
      font-weight: bold;
      font-size: medium;
      background-color: #f9f9f9;
      margin: 10px;
      border-radius: 5px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      height: auto;
      width: auto;
    }
  </style>
</head>
<body>
  <div class="containerNav">
    <h2 class='containerNavH2'>HomePage Docente</h2>
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
        <button type="button" class="btnLogout btn btn-secondary"  onclick="window.location.href='index.html'">Logout</button>

      </div>
    </nav>

  </div>
</body>
</html>
