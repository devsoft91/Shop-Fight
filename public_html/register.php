<?php
  
  if($_POST){

    $nome=$_POST['nome'];
    $cognome=$_POST['cognome'];
    $indirizzo=$_POST['indirizzo'];
    $login=$_POST['login'];
    $password=$_POST['pwd'];
    $rpassword=$_POST['rpwd'];

    $prev_page=$_SERVER["HTTP_REFERER"];

    $errore1=FALSE;
    $errore2=FALSE;

    if($rpassword!=$password){
      $errore1=TRUE;
    }

    if($nome=="" OR $cognome=="" OR $indirizzo=="" OR $login=="" OR $password==""){
      $errore2=TRUE;
    }

    if($errore1 OR $errore2){
      echo <<< HTML
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
      <head>
        <title>Shop&Fight - Errore</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="title" content="Login"/>
        <meta name="author" content="Viviana Alessio, Giacomo Beltrame"/>
        <meta name="language" content="italian it"/>
        <meta name="viewport" content="width=device-width, user-scalable=no"/>
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>
        <link rel="icon" href="imgs/favicon.png" type="image/ico"/>
        <link href="stylesheet/style.css" rel="stylesheet" type="text/css" media="screen"/>
      </head>
      <body>
        <div id="content">
          <div id="navbar">
            <div id="logo">
            </div>
            <ul id="menu">
              <li><a href="index.php">HOME</a></li>
              <li><a href="filtra.php">CERCA/FILTRA</a></li>
              <li><a href="sconti.php">SCONTI</a></li>
              <li><a href="ordini.php">ORDINI</a></li>
              <li><a href="carrello.php">CARRELLO</a></li>      
            </ul>
            <div id="path">
              Ti trovi in: Errore
            </div>
          </div>
          <div id="body">
HTML;

    if($errore1) echo "<h1>Errore</h1>\n<p>Le password non coincidono</p>\n";
    if($errore2) echo "<h1>Errore</h1>\n<p>Tutti i campi vanno compilati</p>\n";
    
    echo <<< HTML
            <p><a href="$prev_page">Torna indietro</a></p>
          </div>
        </div>
        <div id="footer">
          Copyright &#xA9; 2014 - All Rights Reserved.
        </div>
      </body>
      </html>
HTML;
    }
    else{

      $host="basidati";
      $user="gbeltram";
      $pwd= "UuRnP33o";

      $connect=mysql_connect($host, $user, $pwd) or die("Impossibile connettersi!");

      $dbname="gbeltram-PR";
      $connection=mysql_select_db($dbname,$connect);

      $query="insert into Utenti(nome,cognome,username,password,indirizzo,tipoutente) values (\"$nome\",\"$cognome\",\"$login\",\"$password\",\"$indirizzo\",\"Cliente\")";

      $send=mysql_query($query,$connect) or die("Query fallita. ".mysql_error($connect));  

      echo <<< HTML
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
      <head>
        <title>Shop&Fight - Registrato</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="refresh" content="3; url=$prev_page">
        <meta name="title" content="Login"/>
        <meta name="author" content="Viviana Alessio, Giacomo Beltrame"/>
        <meta name="language" content="italian it"/>
        <meta name="viewport" content="width=device-width, user-scalable=no"/>
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>
        <link rel="icon" href="imgs/favicon.png" type="image/ico"/>
        <link href="stylesheet/style.css" rel="stylesheet" type="text/css" media="screen"/>
      </head>
      <body>
        <div id="content">
          <div id="navbar">
            <div id="logo">
            </div>
            <ul id="menu">
              <li><a href="index.php">HOME</a></li>
              <li><a href="filtra.php">CERCA/FILTRA</a></li>
              <li><a href="test_query.php">TEST_QUERY</a></li>
              <li><a href="ordini.php">ORDINI</a></li>
              <li><a href="carrello.php">CARRELLO</a></li>      
            </ul>
            <div id="path">
              Ti trovi in: Registrato
            </div>
          </div>
          <div id="body">
            <h1>Complimenti</h1>
            <p>Registrazione effettuata con successo!</p>
          </div>
        </div>
        <div id="footer">
          Copyright &#xA9; 2014 - All Rights Reserved.
        </div>
      </body>
      </html>
HTML;
    }
  }

?>