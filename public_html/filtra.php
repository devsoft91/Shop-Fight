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
  <title>Shop&Fight - Filtra</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="title" content="Login"/>
  <meta name="author" content="Viviana Alessio, Giacomo Beltrame"/>
  <meta name="language" content="italian it"/>
  <meta name="viewport" content="width=device-width, user-scalable=no"/>
  <meta http-equiv="Content-Script-Type" content="text/javascript"/>
  <link rel="icon" href="imgs/favicon.png" type="image/ico"/>
  <link href="stylesheet/style.css" rel="stylesheet" type="text/css" media="screen"/>
  <script type="text/javascript" src="script/script.js"></script>
</head>
<body>
  <div id="content">
    <div id="navbar">
      <div id="logo">
      </div>
      <ul id="menu">
        <li><a href="index.php">HOME</a></li>
        <li>CERCA/FILTRA</li>
        <li><a href="sconti.php">SCONTI</a></li>
        <li><a href="ordini.php">ORDINI</a></li>
        <li><a href="carrello.php">CARRELLO
HTML;
  if(!empty($_SESSION['login']) and $num_rows>0) echo "($num_rows)";
  echo "</a></li>";
  if(!empty($_SESSION['login']) and $tipoutente=="Proprietario") echo "<li><a href=\"gestisci_utenti.php\">UTENTI</a></li>";
  echo <<< HTML
      </ul>
      <div id="path">Ti trovi in: Cerca/Filtra</div>
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
            <label for="ipwd" xml:lang="en">Password:</label>
            <input type="password" name="pwd" id="pwd"/>
          </fieldset>
          <input type="submit" value="Login"/><br/>
        </form>
    </div>
HTML;
    }
    else{
      echo <<< HTML
        <h1>Cerca/Filtra</h1>
          <form name="myform" action="" method="post">
            <p>
              <span>
                Tipo:
                <select id="tipo" name="tipo" onchange="aggiornaTipo(this)">
                  <option value="all">Tutti i Tipi</option>
          				<option value="abbigliamento">Abbigliamento</option>
          				<option value="attrezzatura">Attrezzatura</option>
                  <option value="protezioni">Protezioni</option>
          			</select>
              </span>
              <span>
                Marche:
                <select id="marca" name="marca">
                  <option value="all">Tutte le Marche</option>
                  <option value="leone">Leone</option>
                  <option value="top_ring">Top Ring</option>
                  <option value="vandal">Vandal</option>
                  <option value="itaki">Itaki</option>
                  <option value="adidas">Adidas</option>
                  <option value="fighters">Fighters</option>
                  <option value="king">King</option>
                  <option value="corsport">Corsport</option>
                  <option value="sphinx">Sphinx</option>
                  <option value="fightgear">FightGear</option>
                  <option value="lonsdale">Lonsdale</option>
                  <option value="toughboys">ToughBoys</option>
                  <option value="venum">Venum</option>
                  <option value="grips">Grips</option>
                  <option value="everlast">Everlast</option>
                </select>
              </span>
              <span>
                Sport:
                <select id="sport" name="sport">
                  <option value="all">Tutti gli Sport</option>
                  <option value="mma">MMA</option>
                  <option value="karate">Karate</option>
                  <option value="judo">Judo</option>
                  <option value="boxe">Boxe</option>
                  <option value="kick boxing">Kick Boxing</option>
                  <option value="jujitsu">Jujitsu</option>
                  <option value="aikido">Aikido</option>
                  <option value="krav maga">Krav Maga</option>
                  <option value="kali">Kali</option>
                  <option value="muay thai">Muay Thai</option>
                  <option value="bjj">BJJ</option>
                </select>
              </span>
              <span>
                Prezzo: min
                <input type="number" name="min" min="0" max="1500" step="1">€
              </span>
              <span>
                max
                <input type="number" name="max" min="0" max="1500" step="1">€
              </span>
            </p>
            <p id="1_abb" style="display:none">
              SottoTipo:
              <select id="st_abb" name="st_abb">
                <option value="all">Tutti i sottotipi</option>
        				<option value="magliette">Magliette</option>
                <option value="pantaloni">Pantaloni</option>
        				<option value="pantaloncini">Pantaloncini</option>
                <option value="kimono">Kimono</option>
                <option value="scarpe">Scarpe</option>
        			</select>
            </p>
            <p id="1_att" style="display:none">
              SottoTipo:
        			<select id="st_att" name="st_att">
                <option value="all">Tutti i sottotipi</option>
        				<option value="armi">Armi</option>
        				<option value="sacchi">Sacchi</option>
        				<option value="colpitori">Colpitori</option>
        				<option value="scudi">Scudi</option>
                <option value="tatami">Tatami</option>
                <option value="corde">Corde</option>
        			</select>
            </p>
            <p id="1_prot" style="display:none">
              SottoTipo:
              <select id="st_prot" name="st_prot">
                <option value="all">Tutti i sottotipi</option>
                <option value="guanti">Guanti/Guantoni</option>
                <option value="conchiglie">Conchiglie</option>
                <option value="caschi">Caschi</option>
              </select>
            </p>
            <input name="send" type="submit" value="Cerca/Filtra">
          </form>
HTML;
    
    if($_POST){
      $tipo=$_POST['tipo'];
      $marca=$_POST['marca'];
      $sport=$_POST['sport'];
      $min=$_POST['min'];
      $max=$_POST['max'];

      $query="select nomeprodotto as Prodotto, sottotipo as Categoria, Marca, Descrizione, Prezzo from";
      if($sport!="all") $query .= " Sport natural join Prodotti_sport natural join";
      $query .= " Prodotti";
      if($tipo=="attrezzatura"){
        $sottotipo=$_POST['st_att'];
        if($sottotipo=="all") $query .= " natural join Attrezzatura where tipo=\"$tipo\"";
        else $query .= " natural join Attrezzatura where sottotipo=\"$sottotipo\"";
        if($marca!="all") $query .= " and marca=\"$marca\"";
        if($sport!="all") $query .= " and nomesport=\"$sport\"";
        if($min!="") $query .= " and prezzo>=\"$min\"";
        if($max!="") $query .= " and prezzo<=\"$max\"";
        $query .= " and idp not in (select idp from Carrello) group by nomeprodotto";
      }
      else{
        if($tipo=="abbigliamento" or $tipo=="protezioni"){
          $query .=  " natural join Indumenti";
          if($tipo=="abbigliamento")
            $sottotipo=$_POST['st_abb'];
          else $sottotipo=$_POST['st_prot'];
          if($sottotipo=="all") $query .= " where tipo=\"$tipo\"";
          else $query .= " where sottotipo=\"$sottotipo\"";
          if($marca!="all") $query .= " and marca=\"$marca\"";
          if($sport!="all") $query .= " and nomesport=\"$sport\"";
          if($min!="") $query .= " and prezzo>=\"$min\"";
          if($max!="") $query .= " and prezzo<=\"$max\"";
          $query .= " and idp not in (select idp from Carrello) group by nomeprodotto";
        }
        else{
          $query .= " where";
          if($marca!="all") $query .= " marca=\"$marca\" and";
          if($sport!="all") $query .= " nomesport=\"$sport\" and";
          if($min!="") $query .= " prezzo>=\"$min\" and";
          if($max!="") $query .= " prezzo<=\"$max\" and";
          $query .= " idp not in (select idp from Carrello) group by nomeprodotto";
        }
      }

      if($min!="" and $max!="" and $min>$max) echo "<p>Inserisci un corretto intervallo di prezzo.</p>";
      else{
        echo "<p><b>Query:</b> $query</p>";

        $send=mysql_query($query,$connect) or die("Query fallita".mysql_error($connect));

        $num_righe=mysql_num_rows($send);
        $num_colonne=mysql_num_fields($send);
        if(!$num_righe)
          echo "<p>Nessun risultato</p>";
        else{
          echo "<br>$num_righe risultati:<br>";
          echo"<table>";
          echo"<tr>";
          $i = 0;
          $index = 30;
          while($i<$num_colonne){
            $meta = mysql_fetch_field($send,$i);
            echo "<th>$meta->name</th>";
            if($meta->name=="Prezzo")
              $index=$i;
            $i++;
          }
          echo "</tr>";
          while($row=mysql_fetch_row($send)){
            $j=0;
            echo "<tr>";
            while($j<$num_colonne){
              echo "<td>";
              if($j==0){
                $name=$row[$j];
                echo "<a href=\"prodotto.php?prod=$name\">$name</a>";
              }
              else{
                echo "$row[$j]";
                if($j==$index)
                  echo " €";
              }
              echo "</td>";
              $j++;
            }
            echo "</tr>";
          }
          echo "</table>";
        }
      }
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