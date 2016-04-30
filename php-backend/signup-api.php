<?php
require("settings.php");
require_once("Requests/library/Requests.php");

$username = $_GET['u'];
$password = $_GET['pa'];
$phone = "+" . $_GET['ph'];

if (!isset($_GET['u']) || !isset($_GET['pa']) || !isset($_GET['ph']))
{
  exit("missing some information");
}



//check if phone in database w
$query = $pdo->prepare("SELECT * FROM `users` WHERE `phone` = :checkPhone");
$query->bindParam(":checkPhone", $phone);
$query->execute();
$rows = $query->rowCount();

if ($rows > 0){
  exit("ERR: That phone number is already registered!");
}

//check if aspen username is in database
$checkUser = $pdo->prepare("SELECT * FROM `users` WHERE `aspenUsr` = :checkAspen");
$checkUser->bindParam(":checkAspen", $username);
$checkUser->execute();
$rows = $checkUser->rowCount();

if ($rows > 0){
  exit("ERR: That aspen account is already registered!");
}

if (!validate_login($username, $password))
{
  exit("invalid aspen username / password");
}

$cryptedPass = encrypt($password);

//ready to put in mysql database
$query = $pdo->prepare("INSERT INTO `users`(`phone`, `aspenUsr`, `pass`) VALUES (:phone, :user, :pass)");
$query->bindParam(":phone", $phone);
$query->bindParam(":user", $username);
$query->bindParam(":pass", $cryptedPass);
$query->execute();

if ($query)
{
  echo "SUCCESS";
}
else {
  echo "something went wrong";
}






function validate_login($username, $password)
{

  //load internal request class
  Requests::register_autoloader();

  $session = new Requests_Session("https://ct-greenwich.myfollett.com/aspen/logon.do");
  $session->useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36";

  //get auth token
  $response = $session->get("https://ct-greenwich.myfollett.com/aspen/logon.do");
  $text = $response->body;

  //regex match token
  preg_match('/jsessionid=(.+?)"/', $text, $result);
  $token = $result[1];

  $payload = array(
    "username" => $username,
    "password" => $password,
    "org.apache.struts.taglib.html.TOKEN" => $token,
    "userEvent" => "930",
    "deploymentId" => "ct-greenwich",
    "mobile" => "false"
  );

  //set them again just incase
  $headers = array(
    "User-Agent" => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36"
  );

  //actual login request
  $request = $session->post("https://ct-greenwich.myfollett.com/aspen/logon.do", $headers, $payload);
  $respText =  $request->body;

  //Student View only appears if you are logged in
  if (strpos($respText, "Student View") !== false)
  {
    return true;
  }
  return false;

}



?>
