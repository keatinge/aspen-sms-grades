<?php
require("settings.php");

if ($_GET['auth'] != $secretAuth)
{
  exit("invalid auth");
}
if (!isset($_POST['phone']))
{
  exit("phone not set");
}
if (!isset($_POST['msg']))
{
  exit("msg not set");
}
//at this point everything has been validated

$msg = $_POST['msg'];
$phone = $_POST['phone'];



//if we didn't replace anything we have to figure out of its in here
//if we didnt replace anything it means it's either not in the table or already existing in the table
//this checks if its in the table right now
$isIn = $pdo->prepare("SELECT * FROM `messages` WHERE `toNum` = :numToCheck AND `message` = :msgToCheck");
$isIn->bindParam(":numToCheck", $phone);
$isIn->bindParam(":msgToCheck", $msg);
$isIn->execute();
$affectedRows = $isIn->rowCount();
if ($affectedRows > 0)
{
  //exact data is in in the table, work is done
  exit("SAME");
}

//if its in there this will replace it with its new value
$updateQuery = $pdo->prepare("UPDATE messages SET `message` = :msgValue WHERE `toNum` = :toValue");
$updateQuery->bindParam(":toValue", $phone);
$updateQuery->bindParam(":msgValue", $msg);
$updateQuery->execute();
$affectedRows = $updateQuery->rowCount();

if ($affectedRows > 0)
{
  //we already replaced, our work is done
  exit("REPL NEW");
}



// Run this code if it's not in the table at all
$query = $pdo->prepare("INSERT INTO `messages`(`toNum`, `message`) VALUES (:toValue, :msgValue)");
$query->bindParam(":toValue", $phone);
$query->bindParam(":msgValue", $msg);
$query->execute();

//new data inserted into table, work is done
exit("INSRT");


?>
