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

    $query="select idp from Carrello natural join Utenti where username=\"$login\"";

    $send=mysql_query($query,$connect) or die("Query fallita. ".mysql_error($connect));

    $num_rows=mysql_num_rows($send);
  }
  echo <<< HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Shop&Fight - Home</title>
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
        <li>HOME</li>
        <li><a href="filtra.php">CERCA/FILTRA</a></li>
        <li><a href="sconti.php">SCONTI</a></li>
        <li><a href="ordini.php">ORDINI</a></li>
        <li><a href="carrello.php">CARRELLO
HTML;
  if(!empty($_SESSION['login']) and $num_rows>0) echo "($num_rows)";
  echo "</a></li>";
  if(!empty($_SESSION['login']) and $tipoutente=="Proprietario") echo "<li><a href=\"gestisci_utenti.php\">UTENTI</a></li>";
  echo <<< HTML
      </ul>
      <div id="path">
        Ti trovi in: Home
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
      $login=$_SESSION['login'];
      echo <<< HTML
      <h1>Benvenuto</h1>
      <p>Sei loggato come $login</p>
      <p><b>Dettagli del sito:</b> In <i>Cerca/Filtra</i> puoi cercare il prodotto che ti interessa comprare usando i menù a tendina.
      Ad avvenuta ricerca ti verrà sempre mostrata la query appena composta in modo da controllare se stai cercando ciò che volevi.<br>
      Raggiunta la pagina del prodotto desiderato puoi sceglierne le caratteristiche e controllare quanti elementi ci sono in magazzino.<br>
      In <i>Sconti</i> tieni sempre d'occhio il tuo sconto o quello di tutti gli utenti se sei Admin
      (in ogni caso al momento dall'acquisto ti verrà sempre chiesto se vorrai utilizzarlo o meno).<br>
      La pagina <i>Ordini</i> mostra lo storico dei tuoi ordini o degli ordini di tutti gli utenti se sei Admin con la possibilità, cliccando sull'id dell'ordine,
      di vedere in dettaglio i prodotti che lo compongono.<br>
      La pagina <i>Carrello</i> mostra i prodotti che hai inserito nel carrello durante la navigazione del sito; da questa pagina puoi anche rimuovere gli elementi che non hai più intenzione di acquistare
      e di vedere il totale dell'ordine ed eventualmente il prezzo scontato se possiedi uno sconto; puoi inoltre decidere se utilizzare il buono sconto o meno (sempre ricordando che gli sconti sono cumulabili);
      a fondo pagina, infine, il tasto per confermare il carrello e procedere all'acquisto.<br>
      Infine <i>Utenti</i> mostra solo gli utenti che sono clienti e i rispettivi sconti a disposizione, con la possibilità di azzerarli
      oppure di eliminare l'utente stesso. A questa pagina possono accedere solo gli Amministratori Proprietari.</p>      
      <p>Effettua il <a href="logout.php">logout</a></p>
HTML;
    }
    ?>
    
    </div>
  </div>
  <div id="footer">
    Copyright &#xA9; 2014 - All Rights Reserved.
  </div>
</body>
</html>