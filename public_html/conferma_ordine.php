<?php
  
  session_start();

  $login=$_SESSION['login'];
  $tipoutente=$_SESSION['tipoutente'];
  $idu=$_POST['idu'];
  if(isset($_POST['sconto']))
    $sconto=$_POST['sconto'];
  else $sconto="FALSE";

  $host="basidati";
  $user="gbeltram";
  $pwd= "UuRnP33o";

  $connect=mysql_connect($host, $user, $pwd) or die("Impossibile connettersi!");

  $dbname="gbeltram-PR";

  $connection=mysql_select_db($dbname,$connect);

  $query1="select TotaleOrdine($idu,$sconto)";

  $send1=mysql_query($query1,$connect) or die("Query fallita1. ".mysql_error($connect));

  $row1=mysql_fetch_row($send1);

  $totale=$row1[0];
  
  $query2="insert into Ordini(idu,dataconferma,totalepagato) values ($idu,curdate(),$totale)";

  $send2=mysql_query($query2,$connect) or die("Query fallita2. ".mysql_error($connect));

  $query3="select MAX(ido) from Ordini where idu=$idu";

  $send3=mysql_query($query3,$connect) or die("Query fallita3. ".mysql_error($connect));

  $row3=mysql_fetch_row($send3);

  $ido=$row3[0];

  $query4="select idp from Carrello natural join Prodotti where idu=$idu";

  $send4=mysql_query($query4,$connect) or die("Query fallita4. ".mysql_error($connect));

  while($row4=mysql_fetch_row($send4)){
    $query5="call ConcludiAcquisto($ido,$row4[0])";
    $send5=mysql_query($query5,$connect) or die("Query fallita5. ".mysql_error($connect));
  }

  echo <<< HTML
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
  <head>
    <title>Shop&Fight - Acquisto effettuato</title>
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
HTML;
  if(!empty($_SESSION['login']) and $tipoutente=="Proprietario") echo "<li><a href=\"gestisci_utenti.php\">UTENTI</a></li>";
  echo <<< HTML
        </ul>
        <div id="path">
          Ti trovi in: Acquisto effettuato
        </div>
      </div>
      <div id="body">
        <h1>Complimenti</h1>
        <p>Il suo acquisto è andato a buon fine. Al più presto le sarà spedito l'ordine all'indirizzo da lei indicato nella sua area personale.</p>
HTML;
  
  if($totale>=50 and $totale<100){
    echo "<p>COMPLIMENTI!!! Ha ottenuto un buono sconto di 5 €. Le ricordiamo che i buoni sono cumulabili.</p>";
  }
  else{
    if($totale>=100 and $totale<150){
      echo "<p>COMPLIMENTI!!! Ha ottenuto un buono sconto di 10 €. Le ricordiamo che i buoni sono cumulabili.</p>";
    }
    else{
      if($totale>=150)
      echo "<p>COMPLIMENTI!!! Ha ottenuto un buono sconto di 15 €. Le ricordiamo che i buoni sono cumulabili.</p>";
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