<?php

require("settings.php");


//confirm it's the twilio server calling it
if ($_GET['auth'] != $secretAuth)
{
  exit("incorrect auth");
}


$messageSid = $_POST['MessageSid'];
$accountSid = $_POST['AccountSid'];
$from = $_POST['From'];
$to = $_POST['To'];
$body = $_POST['Body'];



$query = $pdo->prepare("SELECT `message` FROM `messages` WHERE `toNum` = :toNum");
$query->bindParam(":toNum", $from);
$query->execute();
$responseMsg = $query->fetch()['message'];


if ($responseMsg == "")
{
  $responseMsg = "Your phone number isn't signed up yet. If you just signed up you may need to wait a few minutes";
}
?>
<Response>
  <Message>-

<?php echo $responseMsg; ?></Message>
</Response>
