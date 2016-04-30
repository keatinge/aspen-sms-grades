<!DOCTYPE html>
<?php require("settings.php"); ?>
<html>
<head>
   <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</head>

<body>
  <div class="container" style="margin-top:10px;">
    <div class="well">
      <h1 class="text-center">Signup For GHS Aspen Text Message Grades</h1>
      <p class="text-center">Send a text message and recieve your aspen grades instantly in a response</p>

      <h2 style="margin-top:50px;"> Signup: </h2>
      <hr>
      <label for="phone">Phone number (no parentheses or dashes, only numbers!)</label>
      <input id="phone" class="form-control" type="number" style="width:100%;" placeholder="2031234567"><br>

      <label for="aspenUsr">Aspen Username</label>
      <input id="aspenUsr" class="form-control" type="text" style="width:100%;" placeholder="001001234"><br>

      <label for="aspenPw">Aspen Password</label>
      <input id="aspenPw" class="form-control" type="password" style="width:100%;" placeholder="password123"><br>

      <button id="signUp" class="btn btn-success" style="width:100%">Sign me up!</button>
    </div>

    <div id="thanks" class="well" style="margin-top:10px;">
      <center>
        <h1> Thanks for signing up! </h1>
        <h3> You can get your grades by texting the word `grades` to the number <strong><?php echo $twilNum ?> </strong></h3>
        <h4><em> (We'll send you a text from this number in a minute so you don't have to type it in) </em></h4>
      </center>
    </div>
  </div>


  <script>

    $("#thanks").hide();
    $("#signUp").click( function() {
      var phone = $("#phone").val();
      var aspenUsr = $("#aspenUsr").val();
      var aspenPw = $("#aspenPw").val();

      //phone validation
      if (isNaN(phone))
      {
        alert("That doesn't look like a valid phone number!");
        return null;
      }
      if (phone.length < 10)
      {
        alert("Please include your area code in your phone number!");
        return null;
      }
      if (phone.charAt(0) != "1")
      {
        phone = "1" + phone;
      }

      var url = "<?php echo $baseUrl; ?>" + "signup-api.php?u=" + aspenUsr + "&pa=" + aspenPw + "&ph=" + phone;

      $.get(url, function(data) {
        if (data == "SUCCESS")
        {
          $("#thanks").fadeIn(1000);
          $("#signUp").attr("disabled", "true");
        }
        else {
          alert(data);
        }

      });
    });



  </script>
</body>

</html>
