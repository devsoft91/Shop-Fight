<?php
  session_start();

  if(!empty($_SESSION['login'])){

    $host="basidati";
    $user="gbeltram";
    $pwd= "UuRnP33o";

    $connect=mysql_connect($host, $user, $pwd) or die("Impossibile connettersi!");

    $dbname="gbeltram-PR";
    $connection=mysql_select_db($dbname,$connect);

    $login=$_SESSION['login'];
    $tipoutente=$_SESSION['tipoutente'];

    $query="select idu from Utenti where username=\"$login\"";
    $send=mysql_query($query,$connect) or die("Query fallita. ".mysql_error($connect));
    $row=mysql_fetch_row($send);
    $idu=$row[0];

    if($_GET){ /* per eliminare elementi dal carrello*/
        $del=$_GET['del'];
        $query="delete from Carrello where idp=\"$del\" and idu=\"$idu\""; /*anche idu nella query impedisce di eliminare prodotti dal carrello di altri utenti scrivendo semplicemente la query string nell'URL*/
        $send=mysql_query($query,$connect) or die("Query fallita. ".mysql_error($connect));
      }

    $query="select idp from Carrello natural join Utenti where username=\"$login\"";

    $send=mysql_query($query,$connect) or die("Query fallita. ".mysql_error($connect));

    $num_rows=mysql_num_rows($send);
  }

  echo <<< HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Sportify - Carrello</title>
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
        <li>CARRELLO
HTML;
  
  if(!empty($_SESSION['login']) and $num_rows>0) echo "($num_rows)";
  echo "</li>";
  if(!empty($_SESSION['login']) and $tipoutente=="Proprietario") echo "<li><a href=\"gestisci_utenti.php\">UTENTI</a></li>";
  echo <<< HTML
      </ul>
      <div id="path">
        Ti trovi in: Carrello
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
    
    $query="select nomeprodotto, idp, tipo, prezzo from Prodotti natural join Carrello natural join Utenti where username=\"$login\" order by prezzo desc";

    $send=mysql_query($query,$connect) or die("Query fallita. ".mysql_error($connect));

    $num_righe=mysql_num_rows($send);

    echo "<h1>Carrello</h1><p>Carrello di $login:</p>";
    
    if($num_righe!=0){
      while($row=mysql_fetch_row($send)){
        if($row[2]=="Attrezzatura")
          $details="select concat_ws(', ',tipo,sottotipo,marca,peso,materiale,misure,livello) from Prodotti natural join Attrezzatura where idp=$row[1]";
        else $details="select concat_ws(', ',tipo,sottotipo,marca,taglia,numero,sesso) from Prodotti natural join Indumenti where idp=$row[1]";
        $send_details=mysql_query($details,$connect) or die("Query fallita. ".mysql_error($connect));
        $riassunto=mysql_fetch_row($send_details);
        echo <<< HTML
        <div class="item">
          <div class="item_row">
            <div class="img">
              <img src="imgs/$row[0].jpg">
            </div>
            <div class="dettagli">
              <p><h3><b>$row[0]</b></h3></p>
              $riassunto[0]
            </div>
          </div>
          <div class="prezzo">
            $row[3] €
          </div>
          <div class="link">
            <a href="carrello.php?del=$row[1]"><img class="delete" src="imgs/x_rossa.svg" title="Elimina prodotto"></a>
          </div>
        </div>
HTML;

      }

      $query="select scontoaccumulato from Sconti where idu=$idu";
      $send=mysql_query($query,$connect) or die("Query fallita. ".mysql_error($connect));
      $row=mysql_fetch_row($send);
      $sconto=(float)$row[0];
      $query="select SUM(Prezzo) from Carrello natural join Prodotti where idu=$idu";
      $send=mysql_query($query,$connect) or die("Query fallita. ".mysql_error($connect));
      $row=mysql_fetch_row($send);
      $tot=$row[0];
      $scontato=$tot-$sconto;
      if($scontato<0) $scontato=0;
      echo <<< HTML
      <div id="totale">
        Totale: $tot €<br>
HTML;
      if($sconto!=0){
        echo "<span id=\"scontato\">Scontato: ";
        echo sprintf("%01.2f", $scontato);
        echo " €</span>";
      }

      echo <<< HTML
      </div>
      <form action="conferma_ordine.php" name="compra" method="post">
        <input type="hidden" name="idu" value="$idu">
HTML;

      if($sconto!=0){
        echo "<br>Hai uno sconto di $sconto €<br><input type=\"radio\" name=\"sconto\" value=\"TRUE\">Usa sconto<br><input type=\"radio\" name=\"sconto\" value=\"FALSE\" checked>Non usare sconto<br>";
      }
      echo "<br><input type=\"submit\" value=\"Conferma ordine\" id=\"conferma\">\n</form>";
    }
    else{
      echo "<p>Nessun elemento nel carrello</p>";
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