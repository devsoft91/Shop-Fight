<?php
  
  session_start();

  $host="basidati";
  $user="gbeltram";
  $pwd= "UuRnP33o";

  $connect=mysql_connect($host, $user, $pwd) or die("Impossibile connettersi!");

  $dbname="gbeltram-PR";
  $connection=mysql_select_db($dbname,$connect);

  $login=$_SESSION['login'];
  $tipoutente=$_SESSION['tipoutente'];

  $query="select idp from Carrello natural join Utenti where username=\"$login\"";

  $send=mysql_query($query,$connect) or die("Query fallita. ".mysql_error($connect));

  $num_rows=mysql_num_rows($send);

  echo <<< HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Sportify - Dettaglio Ordine</title>
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
        <li>ORDINI</li>
        <li><a href="carrello.php">CARRELLO
HTML;
  if($num_rows>0) echo "($num_rows)";
  echo "</a></li>";
  if(!empty($_SESSION['login']) and $tipoutente=="Proprietario") echo "<li><a href=\"gestisci_utenti.php\">UTENTI</a></li>";
  echo <<< HTML
      </ul>
      <div id="path">
        Ti trovi in: <a href="ordini.php">Ordini</a> -> Dettaglio Ordine
      </div>
    </div>
    <div id="body">
HTML;

  if(empty($_SESSION['login'])){
    echo <<< HTML
    <div id="register">
      <h1>Registrazione</h1>
        <form action="register.php" method="post">
          <fieldset>
            <legend>Dati Utente</legend>
            <label for="nickname">Nickname:</label>
            <input type="text" name="login" id="nickname" maxlength="15"/><br/>
            <label for="pwd">Password:</label>
            <input type="password" name="pwd" id="pwd" maxlength="10"/><br/>
            <label for="rpwd">Ripeti Password:</label>
            <input type="password" name="rpwd" id="rpwd" maxlength="10"/>
          </fieldset>
          <fieldset>
            <legend>Dati Utente</legend>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" maxlength="10"/><br/>
            <label for="cognome">Cognome:</label>
            <input type="text" name="cognome" id="cognome" maxlength="10"/><br/>
            <label for="indirizzo">Indirizzo:</label>
            <input type="text" name="indirizzo" id="indirizzo" maxlength="20"/>
          </fieldset>
          <input type="submit" value="Registrati"/><br/>
        </form>
    </div>
    <div id="login">
      <h1>Login</h1>
        <form action="login.php" method="post">
          <fieldset>
            <legend>Dati Utente</legend>
            <label for="nickname" xml:lang="en">Nickname:</label>
            <input type="text" name="login" id="nickname"/><br/>
            <label for="pwd" xml:lang="en">Password:</label>
            <input type="password" name="pwd" id="pwd"/>
          </fieldset>
          <input type="submit" value="Login"/><br/>
        </form>
    </div>
    
HTML;
  }
  else{
    $ido=$_GET['ido'];

    $query_tipo="select tipoutente from Utenti where username=\"$login\"";
    $check=mysql_query($query_tipo,$connect) or die("Query fallita".mysql_error($connect));
    $type=mysql_fetch_row($check);

    if($type=="Cliente")
      $query="select nomeprodotto as Prodotto, sottotipo as Categoria, Marca, Descrizione, Prezzo from Utenti natural join Ordini natural join Prodotti_ordine natural join Prodotti_venduti where username=\"$login\" and ido=$ido";
    else $query="select nomeprodotto as Prodotto, sottotipo as Categoria, Marca, Descrizione, Prezzo from Utenti natural join Ordini natural join Prodotti_ordine natural join Prodotti_venduti where ido=$ido";

    $send=mysql_query($query,$connect) or die("Query fallita".mysql_error($connect));
    
    $num_righe=mysql_num_rows($send);
    $num_colonne=mysql_num_fields($send);

    echo "<h1>Ordine #$ido</h1>";

    if($num_righe!=0){
      echo "<table>\n<tr>";
    
      $i = 0;
      while($i<$num_colonne){
        $meta = mysql_fetch_field($send,$i);
        echo "<th>$meta->name</th>";
        $i++;
      }
      echo "</tr>";
      while($row=mysql_fetch_row($send)){
        $j=0;
        echo "<tr>";
        while($j<$num_colonne){
          echo "<td>$row[$j]";
          if($j==4) echo " €";
          echo "</td>";
          $j++;
        }
        echo "</tr>";
      }
      echo "</table>";
    }
    else{
      echo "<p>Non puoi vedere i dettagli di questo ordine.</p>";
    }
  }
      
?>
    
    </div>
  </div>
  <div id="footer">
    Copyright &#xA9; 2014 - All Rights Reserved.
  </div>
</body>
</html>