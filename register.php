<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.95">
    <title>StudentTM - Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="css/master.css" />
    <link rel="stylesheet" href="css/register.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>
    <div id="wrapper">
        <div id="header">
            <div id="header_wrapper">
                <div id="sidelogo_wrapper" class="col-sm-3">
                    <div id="canvas">
                        <a href="/">
                            <div id="font">
                                <span>
                                Student<b>TM</b><sub>
                                <span class = "glyphicon glyphicon-check" aria-hidden = "true"></span>
                                </sub>
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="content">
            <div id="content_wrapper" class="container-fluid">
                <div id="form">
                    <div id="form_wrapper" class="well">
                        <div id="icon">
                            <span class="glyphicon glyphicon-user"></span>
                        </div>
                        <div id="heading">
                            <h1>Register</h1>
                        </div>
                        <div id="form_content">
                            <form name = "registerForm" action="php/create_user.php" method="POST" onsubmit=" return validateForm()" class="form-horizontal">

                                <div class="form-group">
                                    <label for="username" class="control-label col-sm-2">Username:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="username" name="uname" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="control-label col-sm-2">Email:</label>
                                    <div class="col-sm-10">
                                        <input type="email" id="email" class="form-control" name="email" />
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="pwd" class="control-label col-sm-2">Password:</label>
                                    <div class="col-sm-10">
                                        <input type="password" id="pwd" class="form-control" name="pwd" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-default" name ="create_user">Register <span class="glyphicon glyphicon-check"></span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="footer">
                            <div id="gotanacc">
                                <a href="login.php">Got an account?</a>
                            </div>
                            <div id="actemail" class="second">
                                <a href="resend.php">Resend Activation email</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    function validateForm(){
    var username = document.forms["registerForm"]["uname"].value;
    if (username == "") {
        alert("Name must be filled out");
        return false;
    }
    else if (username.length < 3){
        alert ("The length of the name must be greater then 2");
        return false;
    }
    
    var email = document.forms["registerForm"]["email"].value;
    if (email == ""){
        alert("Email must be filled out");
        return false;
    }
    
    var password = document.forms["registerForm"]["pwd"].value;
    if (password == ""){
        alert("Password must be filled out");
        return false;
    }
    else if(password.length < 8){
        alert ("The length of the password has to be greater then 7");
        return false;
    }
}

</script>

</html>
