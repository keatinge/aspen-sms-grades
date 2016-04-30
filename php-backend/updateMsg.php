<?php
require("settings.php");
require("diff.php");
require("twilio-php-master/Services/Twilio.php");

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



//this checks if its in the table right now
$isIn = $pdo->prepare("SELECT * FROM `messages` WHERE `toNum` = :numToCheck AND `message` = :msgToCheck");
$isIn->bindParam(":numToCheck", $phone);
$isIn->bindParam(":msgToCheck", $msg);
$isIn->execute();
$affectedRows = $isIn->rowCount();
if ($affectedRows > 0)
{
  exit("SAME");
}


//get currently existing message incase it turns out we update it
$getOldMsg = $pdo->prepare("SELECT `message` FROM `messages` WHERE `toNum` = :toValue");
$getOldMsg->bindParam(":toValue", $phone);
$getOldMsg->execute();
$result = $getOldMsg->fetch(PDO::FETCH_ASSOC);
$oldMsg = $result['message'];


//if its in there this will replace it with its new value
$updateQuery = $pdo->prepare("UPDATE messages SET `message` = :msgValue WHERE `toNum` = :toValue");
$updateQuery->bindParam(":toValue", $phone);
$updateQuery->bindParam(":msgValue", $msg);
$updateQuery->execute();
$affectedRows = $updateQuery->rowCount();

if ($affectedRows > 0)
{
  //this means we updated an old message with a new one
  echo "REPL";
  $differenceString = get_class_difference($oldMsg, $msg);

  //now send a text message informing the user of the change
  $client = new Services_Twilio($twilioAccountSid, $twilioAuthToken);
  $message = $client->account->messages->create(array(
    "From" => $twilioPhone,
    "To" => $phone,
    "Body" => "-\n\n" . $differenceString,
  ));

  exit();

}



// Run this code if it's not in the table at all
$query = $pdo->prepare("INSERT INTO `messages`(`toNum`, `message`) VALUES (:toValue, :msgValue)");
$query->bindParam(":toValue", $phone);
$query->bindParam(":msgValue", $msg);
$query->execute();

//new data inserted into table, work is done
exit("INSRT");


?>
