<?php
    require_once "php/session.php";
    $status = ( new sessionStatus() ) ->getStatus();
    
    if($status) {
       header("Location: index.php"); 
    }
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.95">
    <title>StudentTM - Forgot</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="css/master.css" />
    <link rel="stylesheet" href="css/login.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>
    <div id="wrapper" class="container">
        <div id="header" class="row">
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
        <div id="content" class="row">
            <div id="content_wrapper" class="container-fluid">
                <div id="form">
                    <div id="form_wrapper" class="well">
                        <div id="icon">
                            <span class="glyphicon glyphicon-check"></span>
                        </div>
                        <div id="heading">
                            <h1>Recover Password</h1>
                        </div>
                        <div id="form_content">
                            <form method="post" class="form-horizontal" action="php/forgotpassword_sentemail.php">
                                <div class="form-group">
                                    <label for="username" class="control-label col-sm-2">Username:</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="username" class="form-control" name="username" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="control-label col-sm-2">Email:</label>
                                    <div class="col-sm-10">
                                        <input type="email" id="email" class="form-control" name="email"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-default" name='recover'>Email <span class="glyphicon glyphicon-envelope"></span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="footer">
                            <div id="createacc">
                                <a href="register.php">Create an account</a>
                            </div>
                            <div id="forgot" class="second">
                                <a href="resend.php">Resend Activation email</a>
                            </div>
                        </div>
                    </div>

                </div>
                
            </div>
        </div>
    </div>
</body>

</html>
