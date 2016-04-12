<?php
  
  /*comincia pagina progetto*/

  $host="basidati";
  $user="gbeltram";
  $pwd= "UuRnP33o";

  $connect=mysql_connect($host, $user, $pwd) or die("Impossibile connettersi!");

  $dbname="gbeltram-PR";
  $connection=mysql_select_db($dbname,$connect);

  $login=$_POST['login'];
  $pwd=$_POST['pwd'];

  $query="select * from Utenti where username=\"$login\" and password=\"$pwd\"";

  $send=mysql_query($query,$connect) or die("Query fallita".mysql_error($connect));

  $row=mysql_fetch_row($send);

  $tipoutente=$row[6];

  $prev_page=$_SERVER["HTTP_REFERER"];

  if(mysql_num_rows($send)==1){

    session_start();
    
    $_SESSION['login']=$login;
    $_SESSION['tipoutente']=$tipoutente;

    header('Location:'.$prev_page);
  }
  else{
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
            Ti trovi in: Home
          </div>
        </div>
        <div id="body">
          <h1>Errore</h1>
          <p>Username o password errati</p>
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

?>