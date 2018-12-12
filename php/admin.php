<?php
    //require_once 'security.php';
    require_once 'session.php';
    
    /* Connect database */
    function connect_database(){
        $link = mysqli_connect('localhost','arunharish','ArunKenSai@2017');
        if(!$link){
            die('Could not connect: ' . mysqli_error($link));
        }
        $db = mysqli_select_db($link,'studenttm1.2');
        if(!$db){
            die('Cannot use : ' . mysqli_error($link));
        }
        return $link;
    }		
    
    /* Create user */
    function create_user($username,$password,$email,$hash){
        $link = connect_database();
        $query = 'Insert INTO `user` (username,password,email,hash) VALUES (?,?,?,?)';
        $stmt = mysqli_prepare($link, $query);
        if($stmt) {
            $password = encrypt_password($password);
            mysqli_stmt_bind_param($stmt, "ssss", $username, $password, $email,$hash);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return true;
        }
        return false;
    }
    
    
    /* Login function and check authentication of current session */
    function check_login($username = null,$password = null){
        // Check if their current session is valid
        if (isset($_SESSION['auth']) && (!isset($username) || !isset($password))) {
            return $_SESSION['auth'] == true;
        }
        $link = connect_database();
        // Check password
        if (isset($username) && isset($password)){
            $query = "SELECT username, password FROM user WHERE username = ?";
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $usernamedb, $passworddb);
                while (mysqli_stmt_fetch($stmt)){
                    if (check_password($password,$passworddb)){
                        $_SESSION['auth'] = true;
                        $_SESSION['username'] = $username;
                        mysqli_stmt_close($stmt);
                        return true;
                    }
                }
            }
        }
        $_SESSION['auth'] = false;
        return false;
    }
    
    /* Log out user */
    function logout_user(){
        session_start();
        foreach($_SESSION as $key=> $value) {
            unset($_SESSION[$key]);
        }
        
        return session_destroy();
    }

    /* Change user password */
    function change_password($password){
        $link = connect_database();
        $query = 'UPDATE user SET password = ? WHERE username = ?';
        if ($stmt = mysqli_prepare($link,$query)){
            mysqli_stmt_bind_param($stmt,'ss',$password,$_SESSION['username']);
            $password = encrypt_password($password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return true;
        }
        return false;
    }
    
    /* Check duplicate username, return true if theres no duplicate*/
    function check_user($username){
        $link = connect_database();
        $query = 'SELECT username from user WHERE username = ?';
        if ($stmt = mysqli_prepare($link,$query)){	
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 0){
                mysqli_stmt_close($stmt);
                return true;
            }
        }
        return false;
    }
    
    // Check for duplicate email, true if theres no duplicate email;
    function check_email($email){
        $link = connect_database();
        $query = 'Select email from user WHERE email = ?';
        if ($stmt = mysqli_prepare($link, $query)){
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) < 1){
                mysqli_stmt_close($stmt);
                return true;
            }
        }
        return false;
    }
    
    function check_username_email($email,$username){
        $link = connect_database();
        $query = 'Select email from user WHERE email = ? AND username = ?';
        if ($stmt = mysqli_prepare($link, $query)){
            mysqli_stmt_bind_param($stmt, 'ss', $email, $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) < 1){
                mysqli_stmt_close($stmt);
                return true;
            }
        }
        return false;
    }
    
    function get_username($email){
        $link = connect_database();
        $query = 'Select username from user WHERE email = ?';
        if ($stmt = mysqli_prepare($link, $query)){
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt,$username);
            while(mysqli_stmt_fetch($stmt)){
                mysqli_stmt_close($stmt);
                return $username;
            }
        }
        return false;
    }
    
    function change_email($newemail){
        $link = connect_database();
        $query = 'UPDATE user SET email = ? WHERE UID = ?';
        if ($stmt = mysqli_prepare($link,$query)){
            mysqli_stmt_bind_param($stmt,'ss',$newemail,get_uid());
            mysqli_stmtexecute($stmt);
            mysqli_stmt_close($stmt);
            return true;
        }
        return false;
    }
    
    /* Sent activate email */
    function activate_email($email,$username,$password,$hash){
        $subject = "User account activation email";
        $to= $email; // Send email to our user
        $message = "
        Thanks for signing up!
        Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
         
        ------------------------
        Username: '.$username.'
        Password: '.$password.'
        ------------------------
         
        Please click this link to activate your account:
        https://infs3202-studenttm-arunharish.c9users.io/INFS3202/php/verify.php?email=$email&hash=$hash'
         
        "; // Our message above including the link
                             
        $headers = 'From:noreplystudenttm@gmail.com' . "\r\n"; // Set from headers
        if (mail($to, $subject, $message, $headers)){
            return true;
        }
        return false;
    }
    
    /* Sent recover password email */
    function recovery_password_email($email,$hash){
        $subject = "Reset password email";
        $to= $email; // Send email to our user
        $message = "
        Your password reset link send to your e-mail address: 
        
        https://infs3202-studenttm-arunharish.c9users.io/INFS3202/php/reset.php?email=$email&hash=$hash'
         
        "; // Our message above including the link
                             
        $headers = 'From:noreplystudenttm@gmail.com' . "\r\n"; // Set from headers
        if (mail($to, $subject, $message, $headers)){
            return true;
        }
        return false;
    }
    
    function get_email(){
        $link = connect_database();
        $query =  'SELECT email FROM user WHERE username = ?';
        if ($stmt = mysqli_prepare($link,$query)){
            mysqli_stmt_bind_param($stmt, 's', $_SESSION['username']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt,$email);
            while(mysqli_stmt_fetch($stmt)){
                mysqli_stmt_close($stmt);    
                return $email;
            }
        }
        return false;
    }
    
    function check_email_hash($email,$hash){
        $link = connect_database();
        $query =  'SELECT email, hash FROM user WHERE email = ? AND hash = ?';
        if ($stmt = mysqli_prepare($link,$query)){
            mysqli_stmt_bind_param($stmt, 'ss', $email, $hash);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_close($stmt);
                return true;
            }
        }
        return false;
    }
    
    function set_active($email,$hash){
        $link = connect_database();
        $query =  'UPDATE user SET active = 1 WHERE email = ? AND hash = ?';
        if ($stmt = mysqli_prepare($link,$query)){
            mysqli_stmt_bind_param($stmt, 'ss', $email, $hash);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return true;
        }
        return false;
    }
    
    function get_hash($email){
        $link = connect_database();
        $query =  'SELECT hash FROM user WHERE email = ?';
        if ($stmt = mysqli_prepare($link,$query)){
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt,$hash);
            while (mysqli_stmt_fetch($stmt)){
                mysqli_stmt_close($stmt);
                return $hash;
            }
        }
        return false;
    }
    
    
    // Get UID for current session
    function get_uid(){
        $link = connect_database();
        if(check_login()){
            $query = "SELECT uid FROM user where username = ?";
            if ($stmt = mysqli_prepare($link, $query)){
                //There is an error here isn't the $_SESSION gives the username not UID
                mysqli_stmt_bind_param($stmt, 's', $_SESSION['username']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $uid);
                while (mysqli_stmt_fetch($stmt)){
                    return $uid;
                }
            }
        }
        return false;
    }
    
    /* Get CID for current course */
    function get_cid($coursecode){
        $link = connect_database();
        if (check_login){
            $query = "SELECT CID FROM courses where UID = ? AND coursecode = ? ";
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ss', get_uid(), $coursecode);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $cid);
                while (mysqli_stmt_fetch($stmt)){
                    return $cid;
                }
            }
        }
        return false;
    }
    
    function get_rid($cid, $remindertitle){
        $link = connect_database();
        if (check_login){
            $query = "SELECT RID FROM reminder where CID = ? AND title = ?";
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ss', $cid, $remindertitle);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $rid);
                while (mysqli_stmt_fetch($stmt)){
                    return $rid;
                }
            }
        }
        return false;
    }
    
    function encrypt_password($password, $cost = 10){
        // Create a random salt
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
    
        // Prefix information about the hash so PHP knows how to verify it later.
        // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
        $salt = sprintf("$2a$%02d$", $cost) . $salt;
        return crypt($password, $salt);
    }
    
    /**
     * Checks a hash with a password from the database
     * @param $password string The password to check
     * @param $hash string The password from the database
     * @return bool true if matching, false if not
     */
    function check_password($password, $hash){
        return ($hash == crypt($password, $hash));
    }

    function get_password(){
        $link = connect_database();
        $query = 'SELECT password FROM user WHERE UID = ?';
        if ($stmt = mysqli_prepare($link,$query)){
            mysqli_stmt_bind_param($stmt,'s',get_uid());
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt,$password);
            while(mysqli_stmt_fetch($stmt)){
                mysqli_stmt_close($stmt);
                return $password;
            }
        }
    }
    
    function get_active(){
        $link = connect_database();
        $query = 'SELECT active FROM user WHERE uid =?';
        if ($stmt = mysqli_prepare($link,$query)){
            mysqli_stmt_bind_param($stmt,'s',get_uid());
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt,$active);
            while (mysqli_stmt_fetch($stmt)){
                mysqli_stmt_close($stmt);
                return $active;
            }
        }
    }
    
    
    
    class course{
        
        /* Create courses */
        function create_course($coursecode, $coursename){
            $link = connect_database();
            $course = new course;
            $query = 'Insert INTO courses (UID, coursecode, coursename) VALUES (?,?,?)';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'sss', get_uid(), $coursecode, $coursename);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        /* Delete existing courses */
        function delete_course($cid){
            $link = connect_database();
            $query = 'DELETE FROM courses where cid = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 's', $cid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        /* Change course code */
        function change_coursecode($coursecode, $newcoursecode){
            $link = connect_database();
            $query = 'UPDATE courses SET coursecode = ? WHERE UID = ? AND coursecode = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'sss', $newcoursecode, get_uid(), $coursecode);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        /* Change course name */
        function change_coursename($coursecode,$newcoursename){
            $link = connect_database();
            $query = 'UPDATE courses SET coursename = ? WHERE UID = ? AND coursecode = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 'sss', $newcoursename, get_uid(), $coursecode);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        
        /* Print the courses enrolled by the user  */
        function show_course(){
            $link = connect_database();
            $query = 'SELECT coursecode, coursename, CID FROM courses WHERE UID = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 'd', get_uid());
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$coursecode,$coursename,$cid);
                $result = array();
                while (mysqli_stmt_fetch($stmt)){
                    $result[$coursecode][] = $coursename;
                    $result[$coursecode][] = $cid;
                }
                $json_result = json_encode($result);
                return $json_result;
            }
            return false;
        }
        
        /* Change color*/
        function change_color($coursecode,$color){
            $link = connect_database();
            $course = new course;
            $query = 'UPDATE courses SET colorcode = ? WHERE UID = ? AND coursecode = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'sss', $course->get_color($color), get_uid(), $coursecode);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        /* Helper function */
        
        /* Check duplicate coursecode
            Return true when no duplicate */
        function check_coursecode($coursecode){
            $link = connect_database();
            $query = 'SELECT coursecode FROM courses where UID = ? AND coursecode =  ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ss', get_uid(), $coursecode);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 0){
                    mysqli_stmt_close($stmt);
                    return true;
                }
            }
            return false;
        }
        
        /* Get appropriate hexadecimal color */
        function get_color($color){
            $color_array = array('indigo'=>'#6e69ff','yellow'=>'#fdff69','red'=>'#ff6969',
                                    'green'=>'#b1ff69','grey'=>'#9d9d9d','blue'=>'#4072ff');
            return $color_array[$color];
        }
    }
    
    
    class reminder{
        
         /* Create reminder */
         //Also we forgot to add the location API and the ordering of the users.
         //The ordering is such that it can use drag and drop API.
         //We need to design the database well I think cause it is not yet clear for me - Arun
        function create_reminder($cid, $title, $location = null, $longitude = null, $latitude = null){
            $link = connect_database();
            $query = 'Insert INTO reminder (UID, CID, title, location, longitude, latitude) VALUES (?,?,?,?,?,?)';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ssssss', get_uid(), $cid, $title, $location, $longitude, $latitide);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        function delete_reminder($rid){
            $link = connect_database();
            $query = 'DELETE FROM reminder WHERE RID = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 's',$rid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        
        /* Change reminder title */
        function change_remindertitle($coursecode, $title, $newtitle){
            $link = connect_database();
            $query = 'UPDATE reminder SET title = ? WHERE CID = ? AND title = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'sss', $newtitle, get_cid($coursecode), $title);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        /* Change reminder location (Set it to null when location is deleted) */
        function change_location($coursecode,$title, $location){
            $link = connect_database();
            $query = 'UPDATE reminder SET location = ? WHERE CID = ? AND title = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 'sss', $location, get_cid($coursecode), $title);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        /* Add coordinate to the database. (Should call this function when location is set) */
        function add_coordinate($coursecode, $title, $location,$boolean){
            $link = connect_database();
            $query = 'UPDATE reminder SET longitude = ? AND latitude = ? WHERE CID = ? AND title = ?';
            if ($boolean){
                $longitude = ''; //make a function that return coordinate
                $latitude = '';                
            }
            else{
                $longitude = null;
                $latitude = null;
            }
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 'ddss', $longitude, $latitide, get_cid($coursecode), $title);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        function show_reminder(){
            $link = connect_database();
            $query = 'SELECT title FROM reminder WHERE UID = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 'd', get_uid());
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$title);
                $result = array();
                while (mysqli_stmt_fetch($stmt)){
                    array_push($result,$title);
                }
                $json_result = json_encode($result);
                return $json_result;
            }
            return false;
        }
        
        function show_course_reminder($cid){
            $link = connect_database();
            $query = 'SELECT title,RID,longitude,latitude FROM reminder WHERE CID = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt,'d',$cid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$remindertitle,$rid,$longitude,$latitude);
                $result = array();
                //Well I think above can be an object 
                while(mysqli_stmt_fetch($stmt)){
                    $reminderarray = array();
                    array_push($reminderarray,$rid,$remindertitle,$longitude,$latitude);
                    array_push($result,$reminderarray);
                }
                return $result;
            }
            return false;
        }

        function search_reminder($value){
            $link = connect_database();
            $query = "SELECT title FROM reminder WHERE title LIKE .'%?%'.";// AND uid = ?";
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 's', $value);//, get_uid());
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $title);
                $result = array();
                while (mysqli_stmt_fetch($stmt)){
                    array_push($result, $title);
                }
                return $result;
            }
            return false;
        }
        
        /* Helper function */
        
        /* Check if exist duplicate reminder */
        function check_reminder($cid, $title){
            $link = connect_database();
            $query = 'SELECT title FROM reminder WHERE CID = ? AND title = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ss', $cid, $title);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 0){
                    mysqli_stmt_close($stmt);
                    return true;
                }   
            }
            return false;
        }
        
        function mark_reminder($rid,$boolean){
            $link = connect_database();
            $query = 'UPDATE reminder SET completed = ? WHERE RID = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ss', $boolean, $rid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        function get_reminder_status($rid){
            $link = connect_database();
            $query = 'SELECT completed FROM reminder WHERE RID = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ss', $boolean, $rid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$completed);
                while(mysqli_stmt_fetch($stmt)){
                    mysqli_stmt_close($stmt);
                    return $completed;
                }
            }
        }
    }
    
    class reminderlist {
        
         /* Create reminderlist */
        function create_reminderlist($coursecode, $remindertitle, $content, $roworder, $completed = false){
            $link = connect_database();
            $query = 'Insert INTO reminderlist (RID, CID, UID, content, completed, roworder) VALUES (?,?,?,?,?,?)';
            
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ssssss', 
                    get_rid($coursecode,$remindertitle), get_cid($coursecode), 
                    get_uid(), $content, $completed, $roworder);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        /* Delete reminderlist */
        function delete_reminderlist($coursecode, $remindertitle, $content){
            $link = connect_database();
            $query = 'DELETE FROM reminderlist WHERE RID = ? AND content = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ss', get_cid($coursecode,$remindertitle), $content);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        
        /* Change reminderlist content */
        function change_reminderlist($coursecode, $remindertitle, $content, $newcontent){
            $link = connect_database();
            $query = 'UPDATE reminder SET content = ? WHERE RID = ? AND content = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'sss', $newcontent, get_rid($coursecode,$remindertitle), $content);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        /* Mark reminderlist */
        function completed_reminderlist($coursecode, $remindertitlte, $content, $boolean){
            $link = connect_database();
            $query = 'UPDATE reminder SET completed = ? WHERE RID = ? AND content = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'sss', $boolean, get_rid($coursecode,$remindertitle), $content);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        /* Shift the row order of reminder list 
            (e.g.) $contentA shifted to $contentB, compare the row of A(rowA) and B(rowB).
            if rowA > rowB, then we the row between the rowA and row B which is row B <= (rowA and row B) < row A. the row A and row B will be increase by 1*/
        function shift_roworder($coursecode,$title,$LIDA,$LIDB){
            $link = connect_database();
            $reminderlist = new reminderlist;
            $contentA = $reminderlist->show_reminder_content($LIDA);
            $contentB = $reminderlist->show_reminder_content($LIDB);
            $rowA = $reminderlist->current_reminderlist_order($coursecode,$title,$contentA);
            $rowB = $reminderlist->current_reminderlist_order($coursecode,$title,$contentB);
            
            if ($rowB > $rowA){
                $temp = $rowB;
                $tempcont = $contentA;
                $rowB = $rowA;
                $rowA = $temp;
                $contentA = $contentB;
                $contentB = $tempcont;
            }
            // In the query, we assume that rowA is greater then row B. this query only set the rows between and row B. still need to set rowA
            $query = 'UPDATE reminderlist SET roworder = roworder + 1 WHERE RID = ? AND roworder < ? AND roworder >= ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 'sss', get_rid($coursecode,$title), $rowA, $rowB);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $query = 'UPDATE reminderlist SET roworder = ? WHERE RID = ? AND content = ?';
                if ($stmt = mysqli_prepare($link,$query)){
                    mysqli_stmt_bind_param($stmt, 'sss', $rowB, get_rid($coursecode,$title), $contentA);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    return true;
                }
            }
            return false;
        }
        
        function show_reminder_content($LID){
            $link = connect_database();
            $query = 'SELECT content FROM reminderlist WHERE LID = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 's', $LID);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$content);
                while (mysqli_stmt_fetch($stmt)){
                    mysqli_stmt_close($stmt);
                    return $content;
                }
            }
            return false;
        }
        
        /* Show reminderlist */
        function show_reminderlist($rid){
            $link = connect_database();
            $query = 'SELECT LID, content FROM reminderlist WHERE RID = ? ORDER BY roworder ASC';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 's', $rid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$LID, $content);
                $result = array();
                while (mysqli_stmt_fetch($stmt)){
                    $reminderlistarray = array();
                    array_push($reminderlistarray,$LID,$content);
                    array_push($result,$reminderlistarray);
                }
                return $result;
            }
            return false;
        }

        
        /* Helper function */
        
        /* Check if exist duplicate reminder */
        function check_reminderlist($coursecode, $title, $content){
            $link = connect_database();
            $query = 'SELECT content FROM reminderlist WHERE RID = ? AND content = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ss', get_rid($coursecode, $title), $content);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 0){
                    mysqli_stmt_close($stmt);
                    return true;
                }   
            }
            return false;
        }
        
        /* Count the amount of the reminderlist of reminder, and return the number(Use when creating new reminderlist)*/
        function count_reminderlist_order($coursecode, $title){
            $link = connect_database();
            $query = 'SELECT COUNT(*) FROM reminderlist WHERE RID = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 's', get_rid($coursecode, $title));
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$value);
                while(mysqli_stmt_fetch($stmt)){
                    return $value;  
                    mysqli_stmt_close($stmt);
                }
            }
        }
        
        /* Return the current row order for the reminderlist.*/
        function current_reminderlist_order($coursecode,$title, $content){
            $link = connect_database();
            $query = 'SELECT roworder FROM reminderlist WHERE RID = ? AND content = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ss', get_rid($coursecode,$title), $content);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $row_order);
                while (mysqli_stmt_fetch($stmt)){
                    mysqli_stmt_close($stmt);
                    return $row_order;
                }
            }
        }
        
        /* Change the row order of reminder list when one of the reminder list deleted */
        function change_roworder_delete($coursecode,$title,$order){
            $link = connect_database();
            $query = 'UPDATE reminderlist SET roworder = roworder - 1 WHERE RID = ? AND roworder > ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 'ss', get_rid($coursecode,$title), $order);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        function search_reminderlist($value){
            $link = connect_database();
            $query = "SELECT content FROM reminderlist WHERE content LIKE %?% AND uid = ?";
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ss', $value, get_uid());
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $content);
                while (mysqli_stmt_fetch($stmt)){
                    mysqli_stmt_close($stmt);
                    return $content;
                }
            }
            return false;
        }
        
        function mark_all_reminderlist_complete($rid,$boolean){
            $link = connect_database();
            $query = 'UPDATE reminderlist SET completed = ? WHERE RID = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 'ss', $boolean, $rid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
    }
    
    class task {
        
        /* Create task */
        function create_task($coursecode, $taskname, $description, $type, $duedate){
            $link = connect_database();
            $query = 'Insert INTO task (UID, CID, taskname, description, type, duedate) VALUES (?,?,?,?,?,?)';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ssssss', get_uid(), get_cid($coursecode), $taskname, $description, $type, $duedate);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
                
        /* Delete task */
        function delete_task($coursecode, $taskname){
            $link = connect_database();
            $query = 'DELETE FROM task where CID = ? AND taskname = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 'ss', get_cid($coursecode),$taskname);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        /* Change task name */
        function change_taskname($coursecode, $taskname, $newtaskname){
            $link = connect_database();
            $query = 'UPDATE task SET taskname = ? WHERE CID = ? AND taskname = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 'sss', $newtaskname, get_cid($coursecode), $taskname);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        /* Change task's description */
        function change_taskdescription($coursecode, $taskname, $newdescription){
            $link = connect_database();
            $query = 'UPDATE task SET description = ? WHERE CID = ? AND taskname = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 'sss', $newdescription, get_cid($coursecode), $taskname);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        function change_tasktype($coursecode,$taskname,$tasktype){
            $link = connect_database();
            $query = 'UPDATE task set type = ? WHERE CID = ? AND taskname = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt,'sss',$tasktype,get_cid(),$taskname);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        function change_taskduedate($coursecode, $taskname, $duedate){
            $link = connect_database();
            $query = 'UPDATE task set duedate = ? WHERE CID = ? AND taskname = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt,'sss',$duedate, get_cid(), $taskname);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }
        
        
        function task_done($coursecode, $taskname, $boolean){
            $link = connect_database();
            $query = 'UPDATE task SET done = ? WHERE CID = ? AND taskname = ?';
            if ($stmt = mysqli_prepare($link,$query)){
                mysqli_stmt_bind_param($stmt, 'sss', $boolean, get_cid($coursecode),$taskname);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            }
            return false;
        }


        /* Helper function */
        function get_boolean($taskname, $coursecode){
            $link = connect_database();
            $query = 'SELECT done FROM task WHERE CID = ? AND taskname = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt,'ss', get_cid($coursecode), $taskname);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $boolean);
                while(mysqli_stmt_fetch($stmt)){
                    mysqli_stmt_close($close);
                    return $boolean;
                }
            }
        }
        
        
        
        function check_task($taskname,$coursecode){
            $link = connect_database();
            $query = 'SELECT taskname FROM task WHERE taskname = ? AND CID = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ss', $taskname, get_cid($coursecode));
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 0){
                    mysqli_stmt_close($stmt);
                    return true;           
                }
            }
            return false;
        }
        
        function show_task($coursecode){
            $link = connect_database();
            $query = 'SELECT taskname FROM task WHERE CID = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 's', get_cid($coursecode));
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$taskname);
                $result = array();
                if (mysqli_stmt_fetch($stmt)){
                    array_push($result,$taskname);
                }
                return $result;
            }
            return false;
        }
        
        /* havent implement */
        function show_task_details($coursecode, $taskname){
            $link = connect_database();
            $query = 'SELECT taskname FROM task WHERE CID = ? AND taskname = ?';
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 's', get_cid($coursecode));
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$taskname);
                $result = array();
                if (mysqli_stmt_fetch($stmt)){
                    array_push($result,$taskname);
                }
                return $result;
            }
            return false;
        }
        function search_task($value){
            $link = connect_database();
            $query = "SELECT taskname FROM task WHERE taskname LIKE %?% AND uid = ?";
            if ($stmt = mysqli_prepare($link, $query)){
                mysqli_stmt_bind_param($stmt, 'ss', $value, get_uid());
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $taskname);
                while (mysqli_stmt_fetch($stmt)){
                    mysqli_stmt_close($stmt);
                    return $taskname;
                }
            }
            return false;
        }
        
    }
    
?>