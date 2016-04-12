<?php
  
  $host="basidati";
  $user="gbeltram";
  $pwd= "UuRnP33o";

  $connect=mysql_connect($host, $user, $pwd) or die("Impossibile connettersi!");

  $dbname="gbeltram-PR";

  $connection=mysql_select_db($dbname,$connect);

  session_start();
  $login=$_SESSION['login'];
  $tipoutente=$_SESSION['tipoutente'];
  
  $prod=$_POST['prod'];
  $tipo=$_POST['tipo'];
  $quantita=$_POST['quantita'];

  $query = "select idp from Prodotti";  
  if($tipo==0){
    $query .= " natural join Indumenti where";
    $sottotipo=$_POST['sottotipo'];
    if($sottotipo=="scarpe"){
      $numero=$_POST['numero1'];
      $query .= " numero=\"$numero\"";
    }
    else{
      $taglia=$_POST['taglia1'];
      $query .= " taglia=\"$taglia\"";
    }
    $sesso=$_POST['sesso1'];
    $query .= " and sesso=\"$sesso\" and nomeprodotto=\"$prod\"";
  }
  else{
    $peso=$_POST['peso1'];
    $materiale=$_POST['materiale1'];
    $misure=$_POST['misure1'];
    $livello=$_POST['livello1'];
    $query .= " natural join Attrezzatura where peso=\"$peso\" and materiale=\"$materiale\" and misure=\"$misure\" and livello=\"$livello\" and nomeprodotto=\"$prod\"";
  }

  $query .= " and idp not in (select idp from Carrello)";

  $send=mysql_query($query,$connect) or die("Query fallita. ".mysql_error($connect));
  
  $i=0;
  while($row=mysql_fetch_row($send) and $i<$quantita){
    $insert="call AddCart('$login',$row[0])";
    $send_insert=mysql_query($insert,$connect) or die("Query fallita. ".mysql_error($connect));
    $i++;
  }

  echo <<< HTML
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
  <head>
    <title>Shop&Fight - Stato</title>
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
          <li><a href="carrello.php">CARRELLO
HTML;

  $query1="select idp from Carrello natural join Utenti where username=\"$login\"";
  $send=mysql_query($query1,$connect) or die("Query fallita. ".mysql_error($connect));
  $num_rows=mysql_num_rows($send);
  if($num_rows>0) echo "($num_rows)";
  echo "</a></li>";
  if(!empty($_SESSION['login']) and $tipoutente=="Proprietario") echo "<li><a href=\"gestisci_utenti.php\">UTENTI</a></li>";
  echo <<< HTML
      </ul>
        <div id="path">
          Ti trovi in: Stato
        </div>
      </div>
      <div id="body">
HTML;
  if($i==$quantita){
    echo "<h1>Eseguito</h3>\n<p>Controlla il tuo <a href=\"carrello.php\">carrello</a></p>\n";
  }
  else{
    if($i==0)echo "<h1>Errore</h3>\n<p>Sembra che nel frattempo il prodotto sia andato esaurito. Riprova più tardi.</p>\n";
    else{
      if($i==1)echo "<h1>Attenzione</h3>\n<p>Al tuo <a href=\"carrello.php\">carrello</a> è stato inserito un solo articolo invece che $quantita per un momentaneo calo di scorte in magazzino.</p>\n";
      else echo "<h1>Attenzione</h3>\n<p>Al tuo <a href=\"carrello.php\">carrello</a> sono stati inseriti solo $i articoli invece che $quantita per un momentaneo calo di scorte in magazzino.</p>\n";
    }
  }

  echo <<< HTML
        </div>
      </div>
      <div id="footer">
        Copyright &#xA9; 2014 - All Rights Reserved.
      </div>
    </body>
    </html>
HTML;

?>