<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding-top: 20px; 
      width: 100%;
      height: auto;
      background-color: #f9acac;

    }
    .containerNav {
      width: 100%; 
      height: auto;
      margin: 20px auto; 
      padding-top: 20px;
      padding-left: 0;
      padding-right: 0;
      font-weight: bold;
      background-color: #f9acac;
      border-radius: 5px;
    }
    .containerNavH2 {
      text-align: center;
      font-weight: bold;
      margin-bottom: 20px;
      font-size: 40px;
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
    }
    .containerNav p{
      text-align: center;
      font: sans-serif;
      font-weight: bold;
      font-size: medium;
      margin: 10px;
      border-radius: 5px;
      height: auto;
      width: auto;
    }
    .navbar ul{
      list-style-type: none;
      margin: 0;
      padding: 0;

    }
    .navbar{
      padding: 0;
      margin: 0;
      width: auto;
    }
    .altoDestra{
      float: right;
    }
  </style>
</head>

<body>

  <div class="containerNav">
    <nav class="navbar navbar-default">
      <div>
        <ul class="nav navbar-nav">
          <li <?php echo basename($_SERVER['PHP_SELF']) == 'testStudenti.php' ? 'class="active"' : ''; ?>>
            <a href="testStudenti.php">Test Disponibili</a>
          </li>
          <li <?php echo basename($_SERVER['PHP_SELF']) == 'messaggiStudenti.php' ? 'class="active"' : ''; ?>>
            <a href="messaggiStudenti.php">Messaggi</a>
          </li>
        </ul>
        <div class='altoDestra'>
        <button type="button" class="btnLogout btn btn-secondary" onclick="window.location.href='../../'">Logout</button>
  </div>
      </div>
    </nav>
    <h2 class='containerNavH2'>HomePage Studente</h2>
  </div>
</body>

</html>