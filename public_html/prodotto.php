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
  <title>Shop&Fight - Dettaglio Prodotto</title>
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
        Ti trovi in: <a href="filtra.php">Cerca/Filtra</a> -> Dettaglio Prodotto
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

    $prod=$_GET['prod'];

    $tipo=0;

    $query="select Sottotipo as Prodotto, Marca, Descrizione, Prezzo from Prodotti natural join Indumenti where nomeprodotto=\"$prod\"";

    $host="basidati";
    $user="gbeltram";
    $pwd= "UuRnP33o";

    $connect=mysql_connect($host, $user, $pwd) or die("Impossibile connettersi!");

    $dbname="gbeltram-PR";
    $connection=mysql_select_db($dbname,$connect);

    $send=mysql_query($query,$connect) or die("Query fallita".mysql_error($connect));

    $num_righe=mysql_num_rows($send);
    $num_colonne=mysql_num_fields($send);

    if(!$num_righe){
      $tipo=1;
      $query="select Sottotipo as Prodotto, Marca, Peso, Materiale, Misure,  Livello, Descrizione, Prezzo from Prodotti natural join Attrezzatura where nomeprodotto=\"$prod\"";
      $send=mysql_query($query,$connect) or die("Query fallita".mysql_error($connect));
      $num_righe=mysql_num_rows($send);
      $num_colonne=mysql_num_fields($send);
    }

    if(!$num_righe)
      echo "<html>\n<head>\n<meta http-equiv=\"refresh\" content=\"0; url=filtra.php\">\n</head></html>";
    else{
      $row=mysql_fetch_row($send);
      $sottotipo=$row[0];

      echo <<< HTML
        <h1>$prod</h1>
        <div id="image">
          <img src="imgs/$prod.jpg">
        </div>
        <div id="scheda">
          <h2>Caratteristiche</h2>
          <form action="#" method="post">\n
HTML;
      
      $i=0;
      while($i<$num_colonne){
        if($row[$i]!=""){
          $meta = mysql_fetch_field($send,$i);
          echo "<p>\n<h3>$meta->name:</h3>\n";
          echo "$row[$i]";
          if($i==1){
            if($tipo==0){
              select_ind($sottotipo);
            }
          }
          if($meta->name=="Prezzo")
            echo " €";
          if($meta->name=="Peso")
            echo " Kg";
          if($meta->name=="Misure")
            echo " cm";
          echo "</p>\n";
        }
        $i++;
      }

      if($tipo==0)
        echo "<input type=\"submit\" value=\"Verifica disponibilità\" id=\"send\">";

      if($_POST){
        $sottotipo=$_POST['sottotipo'];
        if($tipo==0){
          $sesso=$_POST['sesso'];
          if($sottotipo=="Scarpe"){
            $numero=$_POST['numero'];
            $dispon="select nomeprodotto, idp from Prodotti natural join Indumenti where nomeprodotto=\"$prod\" and numero=\"$numero\" and sesso=\"$sesso\"";
          }
          else{
            $taglia=$_POST['taglia'];
            $dispon="select nomeprodotto, idp from Prodotti natural join Indumenti where nomeprodotto=\"$prod\" and taglia=\"$taglia\" and sesso=\"$sesso\"";
          }
        }
        else{
          $dispon="select nomeprodotto, idp from Prodotti natural join Attrezzatura where nomeprodotto=\"$prod\" and peso>=\"$peso\" and peso<=\"$peso_sup\" and materiale=\"$materiale\" and livello=\"$livello\"";
        }
        $dispon .= " and idp not in (select idp from Carrello)";

        $send_dispon=mysql_query($dispon,$connect) or die("Query fallita".mysql_error($connect));
        $num_righe=mysql_num_rows($send_dispon);
        if(!$num_righe)
          echo "<label for=\"send\">Prodotto non disponibile</label>\n";
        else{
          if($num_righe==1)
            echo "<label for=\"send\">$num_righe prodotto disponibile</label>\n";
          else echo "<label for=\"send\">$num_righe prodotti disponibili</label>\n";
        }
      }

      echo <<< HTML
        <input type="hidden" name="sottotipo" value="$sottotipo">
      </form>
      <form action="addcart.php" name="add" method="post">\n
HTML;

      if($tipo==0 and $_POST){
        echo "<input type=\"hidden\" name=\"prod\" value=\"$prod\">\n";
        echo "<input type=\"hidden\" name=\"tipo\" value=\"$tipo\">\n";
        if($sottotipo=="Scarpe"){
          echo "<input type=\"hidden\" name=\"sottotipo\" value=\"scarpe\">\n";
          echo "<input type=\"hidden\" name=\"numero1\" value=\"$numero\">\n";
        }
        else{
          echo "<input type=\"hidden\" name=\"sottotipo\" value=\"altro\">\n";
          echo "<input type=\"hidden\" name=\"taglia1\" value=\"$taglia\">\n";
        }
        echo "<input type=\"hidden\" name=\"sesso1\" value=\"$sesso\">\n";
      }
      else{
        if($tipo==1){
          echo <<< HTML
          <input type="hidden" name="prod" value="$prod">\n
          <input type="hidden" name="tipo" value="$tipo">\n
          <input type="hidden" name="sottotipo" value="$row[0]">\n
          <input type="hidden" name="peso1" value="$row[2]">
          <input type="hidden" name="materiale1" value="$row[3]">
          <input type="hidden" name="misure1" value="$row[4]">
          <input type="hidden" name="livello1" value="$row[5]">\n
HTML;
          }
      }
      echo "<input type=\"submit\" name=\"button\" value=\"Aggiungi al carrello\" id=\"add\" disabled=\"true\">\n";
      echo "<label for=\"add\">";

      if(($tipo==0 and $_POST) or $tipo==1){
        if($num_righe>0){
          echo " Quantità <select name=\"quantita\" id=\"quantity\" onchange=\"upbutton(this)\">\n<option value=\"none\" selected></option>";
          for($i=0;$i<$num_righe;$i++){
            $j=$i+1;
            echo "<option value=\"$j\">$j</option>\n";
          }
          echo "</select>";
        }
      }
      
      echo <<< HTML
          </label>
        </form>
        </div>
HTML;
    
      if($tipo==0){
        if($sottotipo=="Scarpe")
          $query1="select distinct numero as Numeri_disponibili from Prodotti natural join Indumenti where nomeprodotto=\"$prod\" and numero!=\"NULL\" and idp not in (select idp from Carrello)";
        else
          $query1="select distinct taglia as Taglie_disponibili from Prodotti natural join Indumenti where nomeprodotto=\"$prod\" and taglia!=\"NULL\" and idp not in (select idp from Carrello)";
        $query2="select distinct sesso as Sesso_rimanente from Prodotti natural join Indumenti where nomeprodotto=\"$prod\" and idp not in (select idp from Carrello)";

        $send1=mysql_query($query1,$connect) or die("Query 1 fallita. ".mysql_error($connect));
        $send2=mysql_query($query2,$connect) or die("Query 2 fallita. ".mysql_error($connect));

        echo <<< HTML
        <div id="disponibilita">
          <div id="taglia_numero">
            <table>
HTML;
        $meta1 = mysql_fetch_field($send1,0);
        echo "<th>$meta1->name</th>\n";
        while($row1=mysql_fetch_row($send1)){
          echo "<tr>\n<td>$row1[0]</td>\n</tr>";
        }

        echo <<< HTML
            </table>
          </div>
          <div id="sesso">
            <table>
HTML;
        $meta2 = mysql_fetch_field($send2,0);
        echo "<th>$meta2->name</th>\n";
        while($row2=mysql_fetch_row($send2)){
          echo "<tr>\n<td>$row2[0]</td>\n</tr>";
        }

        echo <<< HTML
            </table>
          </div>
        </div>
HTML;
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

<?php
  
  function select_ind($sottotipo){
    if($_POST){
      if($sottotipo=="Scarpe"){
        echo "<p>\n<h3>Numero:</h3>\n<select name=\"numero\">\n<option value=\"none\">Seleziona</option>\n<option value=\"35\" ";
        if($_POST['numero']==35)echo "selected";
        echo ">35</option>\n<option value=\"36\" ";
        if($_POST['numero']==36)echo "selected";
        echo ">36</option>\n<option value=\"37\" ";
        if($_POST['numero']==37)echo "selected";
        echo ">37</option>\n<option value=\"38\" ";
        if($_POST['numero']==38)echo "selected";
        echo ">38</option>\n<option value=\"39\" ";
        if($_POST['numero']==39)echo "selected";
        echo ">39</option>\n<option value=\"40\" ";
        if($_POST['numero']==40)echo "selected";
        echo ">40</option>\n<option value=\"41\" ";
        if($_POST['numero']==41)echo "selected";
        echo ">41</option>\n<option value=\"42\" ";
        if($_POST['numero']==42)echo "selected";
        echo ">42</option>\n<option value=\"43\" ";
        if($_POST['numero']==43)echo "selected";
        echo ">43</option>\n<option value=\"44\" ";
        if($_POST['numero']==44)echo "selected";
        echo ">44</option>\n<option value=\"45\" ";
        if($_POST['numero']==45)echo "selected";
        echo ">45</option>\n</select>\n</p>\n";
      }
      else{
        echo "<p>\n<h3>Taglia:</h3>\n<select name=\"taglia\">\n<option value=\"none\">Seleziona</option>\n<option value=\"xs\" ";
        if($_POST['taglia']=="xs")echo "selected";
        echo ">XS</option>\n<option value=\"s\" ";
        if($_POST['taglia']=="s")echo "selected";
        echo ">S</option>\n<option value=\"m\" ";
        if($_POST['taglia']=="m")echo "selected";
        echo ">M</option>\n<option value=\"l\" ";
        if($_POST['taglia']=="l")echo "selected";
        echo ">L</option>\n<option value=\"xl\" ";
        if($_POST['taglia']=="xl")echo "selected";
        echo ">XL</option>\n<option value=\"xxl\" ";
        if($_POST['taglia']=="xxl")echo "selected";
        echo ">XXL</option>\n<option value=\"u\" ";
        if($_POST['taglia']=="u")echo "selected";
        echo ">Unica</option>\n</select>\n</p>\n";
      }

      echo "<p>\n<h3>Sesso:</h3>\n<select name=\"sesso\">\n<option value=\"none\">Seleziona</option>\n<option value=\"uomo\" ";
      if($_POST['sesso']=="uomo")echo "selected";
      echo ">Uomo</option>\n<option value=\"donna\" ";
      if($_POST['sesso']=="donna")echo "selected";
      echo ">Donna</option>\n<option value=\"unisex\" ";
      if($_POST['sesso']=="unisex")echo "selected";
      echo ">Unisex</option>\n</select>\n</p>\n";
    }
    else{
      if($sottotipo=="Scarpe"){
        echo <<< HTML
        <p>
          <h3>Numero:</h3>
          <select name="numero">
            <option value="none">Seleziona</option>
            <option value="35" $35>35</option>
            <option value="36" $36>36</option>
            <option value="37" $37>37</option>
            <option value="38" $38>38</option>
            <option value="39" $39>39</option>
            <option value="40" $40>40</option>
            <option value="41" $41>41</option>
            <option value="42" $42>42</option>
            <option value="43" $43>43</option>
            <option value="44" $44>44</option>
            <option value="45" >45</option>
          </select>
        </p>
HTML;
      }
      else{
        echo <<< HTML
        <p>
          <h3>Taglia:</h3>
          <select name="taglia">
            <option value="none">Seleziona</option>
            <option value="xs">XS</option>
            <option value="s">S</option>
            <option value="m">M</option>
            <option value="l">L</option>
            <option value="xl">XL</option>
            <option value="xxl">XXL</option>
            <option value="u">Unica</option>
          </select>
        </p>
HTML;
      }

      echo <<< HTML
      <p>
        <h3>Sesso:</h3>
        <select name="sesso">
          <option value="none">Seleziona</option>
          <option value="uomo">Uomo</option>
          <option value="donna">Donna</option>
          <option value="unisex">Unisex</option>
        </select>
      </p>
HTML;
    }
}

?>