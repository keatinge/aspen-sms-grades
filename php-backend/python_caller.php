<?php
  require("settings.php");
  $ip = $_SERVER['REMOTE_ADDR'];

  //bad ip
  if ($ip !== $pythonIP)
  {
    exit("errorP");
  }
  if ($_GET['auth'] !== $securityAuth)
  {
    exit("errorA");
  }

  $query = $pdo->prepare("SELECT * FROM `users`");
  $query->execute();
  $result = $query->fetchAll();

  $bigArray = array();
  foreach ($result as $row)
  {
    $username = $row['aspenUsr'];
    $phone = $row['phone'];
    $pass = $row['pass'];

    $plainText = decrypt($pass);
    $smallArray = array("user" => $username, "pass" => $plainText, "phone" => $phone);
    array_push($bigArray, $smallArray);
  }

  //remove pesky null characters trying to sneak their way into json
  echo str_replace("\\u0000", "", json_encode($bigArray));

 ?>
