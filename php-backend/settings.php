<?php

  //mysql datbase must be setup with 2 tables
  //table `users` has id, phone, aspenUsr, pass
  //table `messages` has id, toNum, message

  $pdoHost = "localhost";
  $pdoUsername = "";
  $pdoPassword = "";
  $pdoDatabase = "";
  $pdo = new PDO("mysql:host=$pdoHost;dbname=$pdoDatabase", $pdoUsername, $pdoPassword);

  $secretAuth = ""; //used to update the messages
  $key = ""; //used to encrypt messages
  $twilNum = ""; //twilio number that the users text to (this gets shown to user)
  $pythonIP = ""; //ip address of the python server

  //auth to download user information
  $securityAuth = "";

  //directory of all the files
  $baseUrl = "";



  function encrypt($data)
  {
    return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data , MCRYPT_MODE_ECB);
  }

  function decrypt($data)
  {
    return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB);
  }

 ?>
