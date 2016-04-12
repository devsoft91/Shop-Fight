<?php

  $host="basidati";
  $user="gbeltram";
  $pwd= "UuRnP33o";

  $connect=mysql_connect($host, $user, $pwd) or die("Impossibile connettersi!");

  $dbname="gbeltram-PR";
  $connection=mysql_select_db($dbname,$connect);

  session_start();

  $login=$_SESSION['login'];

  $query="select tipoutente from Utenti where username=\"$login\"";
  $send=mysql_query($query,$connect) or die("Query fallita. ".mysql_error($connect));
  $row=mysql_fetch_row($send);
  $tipo=$row[0];

  if($_GET and $tipo=="Proprietario" or $tipo=="Commesso"){
    $idu=$_GET['idu'];
    $del=$_GET['del'];

    if($del=="ut"){
      $query="delete from Utenti where idu=\"$idu\" and tipoutente=\"Cliente\""; 
      $send=mysql_query($query,$connect) or die("Query fallita1 ".mysql_error($connect));
    }

    if($del=="sc"){
      $query="update Sconti set ScontoAccumulato=0 where idu=\"$idu\" and idu IN (select idu from Utenti where tipoutente=\"Cliente\")"; 
      $send=mysql_query($query,$connect) or die("Query fallita2. ".mysql_error($connect));
    }
  }

  $prev_page=$_SERVER["HTTP_REFERER"];

  header('Location:'.$prev_page);

?>