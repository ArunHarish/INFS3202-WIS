<?php
    require_once 'admin.php';
    require_once 'session.php';
    
    
    header('Content-Type: text/json');
    $course = new course;
    $reminder = new reminder;
    $reminderlist = new reminderlist;
    $task = new task;

    
    if (isset($_POST['create_user'])){
        $username = @$_POST['uname'];
        $email = @$_POST['email'];
        $passwordwd  = @$_POST['pwd'];
        $hash = hash_password(rand(0,1000));
        if (isset($username) && isset($passwordwd) && isset($email)){
            if (!(check_user($username))) { // duplicate username
                die('Username has been used');
            }
            else{
                if (!(check_email($email))){ // duplicate email
                    //Death
                    die('duplicate email found');
                }
                else{        
                    $success = create_user($username, $password, $email, $hash);
                }
            }
        }
        if ($success == true){
            /*if (!activate_email($email,$username,$password,$hash)){
                die('Fail to sent activation email.');
            }
            die('please activate email');*/
            header("Location: /INFS3202/login.php?");
        }
    }
    else if(isset($_POST['login'])){
    	$username = null;
        $password = null;
    	if (isset($_POST['username'])){
    		$username = $_POST['username'];
    	}
    	if (isset($_POST['password'])){
    		$password = $_POST['password'];
    	}
    	$authenticated = check_login($username,$password);
    	if ($authenticated == true){
    		// do something here when the login is sucess (e.g. visit homepage?)
    		header("Location:/INFS3202/");
    	}
    	else {
    		// do something here when the login is fail. (e.g. prompt the user login again? or anything)
    		header("Location: /INFS3202/login.php?");
    	}
    }
    else if(isset($_POST['change_password'])){
        $originalpassword = $_POST['originalpassword'];
        $newpassword = $_POST['newpassword'];
        if (!check_password($oldpassword)){
            die ('Wrong password');
        }
        else{
            if(!change_password($newpassword)){
                die('password not changed');
            }
            else{
                die ('password changed');
            }
        }
    }
    else if(isset($_POST['recovery password'])){
        $email = 'thong_ktt@ymail.com';
        
        if (isset($_email)){
            if(check_email($email)){
                die ('You havent create an account');
            }
            else{
                $hash = get_hash($email);
                if ($hash != false){
                    if(!recovery_password_email($email,$hash)){
                        die ('Recovery password email not sent');
                    }
                    else{
                        die('Email has been sent');
                    }
                }
                else{
                    die('hash not found');   
                }
            }
        }
    }
    
    ////////////////////////***Course***//////////////////////
    
    
    /* Create course */
    if(isset($_POST["create_course"])) {
        $coursecode = $_POST['coursecode'];
        $coursename = $_POST['coursename'];
        
        if (strlen($coursename) > 15){
            die('Coursename too long.');
        }
        if (!($course->check_coursecode($coursecode))){//coursecode duplicate 
            die('Duplicate coursecode');
        }
        else{
            if (!$course->create_course($coursecode,$coursename)){
                die ('course not created');
            }
            else{
                die ('Course created');
            }
        }
    }
    
    /* Delete course */
    else if(isset($_POST["delete_course"])) {
        $course = new course;
        $coursecode = $_POST['coursecode'];
        if ($course->check_coursecode($coursecode)){
            die ('Course not found');
        }
        else{
            if (!$course->delete_course($coursecode)){
                die ('course not deleted');
            }
            else{
                die ('course deleted');       
            }
        }
    }
    /* Change course code*/
    else if (isset($_POST['change_coursecode'])){
        $coursecode = $_POST['coursecode'];
        $newcoursecode = $_POST['newcoursecode'];
    	
        if (!$course->check_coursecode($newcoursecode)){//check if the new course course is duplicate
            die('Duplicate coursecode');
        }
        else{
            if (!$course->change_coursecode($coursecode,$newcoursecode)){
                die ('Coursecode not changed');
            }
            else{
                die ('Coursecode changed.');
            }
        }
    }
    
    /* Change course name */
    else if(isset($_POST['change_coursename'])){
        $coursecode = $_POST['coursename'];
        $newcoursename = $_POST['newcoursename'];
        
        if ($course->change_coursename($coursecode,$newcoursename)){
            die ('Course name changed');
        }
        else{
            die('Course name not changed');
        }
        
    }
    
    /* Show course */
    else if (isset($_POST['show_course'])){
        $result = $course->show_course();
    }
    
    
    //////////////////////////***Reminder***////////////////////////////
    
    
    /* Create reminder */
    if ($_POST['create_reminder']){
        $coursecode = $_POST['coursecode'];
        $title = $_POST['title'];
        $location = $_POST['location'];
        
        if (!($reminder->check_reminder($coursecode, $title))){//coursecode duplicate 
            die('Duplicate reminder');
        }
        else{
            if (!$reminder->create_reminder($coursecode,$title, $location)){              
                die ('reminder not created');   
            }
            else{
                die ('reminder created');
            }
        }
    }
    /* Delete reminder */
    else if ($_POST['delete_reminder']){
        $coursecode = 'INFS3202';//$_POST['coursecode'];
        $title = '';//$_POST['title'];
        if (!($reminder->delete_reminder($coursecode, $title))){
            die('Reminder not deleted');
        }
        else{
            die ('deleted');
        }
    }
    /* Change reminder title */
    else if ($_POST['change_remindertitle']){
        $coursecode = 'INFS3202';//$_POST['coursecode'];
        $title = 'Assignment 2';//$_POST['title'];
        $newtitle = 'Group project';//$_POST['location'];
    
        if (!($reminder->check_reminder($coursecode, $newtitle))){//coursecode duplicate 
            die('Duplicate reminder');
        }
        else{
            if (!$reminder->change_remindertitle($coursecode,$title,$newtitle)){
                die ('reminder title not changed');   
            }
            else{
                die ('Reminder title changed');
            }
        }
    }
    /* Change or add reminder location and set coordinate*/
    else if ($_POST['change_location']){
        $coursecode = 'INFS3202';//$_POST['coursecode'];
        $title = 'Assignment 3';//$_POST['title'];
        $location = 'UQ Centre';//$_POST['location'];
    
        if (!$reminder->change_location($coursecode,$title, $location)){
            die ('location not changed');   
        }
        else{
            if (!$reminder->add_coordinate($coursecode,$title,$location,true)){
                die('Location changed but coordinate not changed.');
            }
            else{
                die ('Location changed and coordinate changed.');
            }
        }    
    }
    /* Delete location and set the coordinate clear */
    else if($_POST['delete_location']){
        $coursecode = 'INFS3202';//$_POST['coursecode'];
        $title = 'Assignment 3';//$_POST['title'];
        $location = null;//$_POST['location'];
    
        if (!$reminder->change_location($coursecode,$title, $location)){
            die ('location not changed');   
        }
        else{
            if (!$reminder->add_coordinate($coursecode,$title,$location,false)){
                die('Location deleted but coordinate not deleted.');
            }
            else{
                die ('Both location and coordinate deleted.');
            }
        }    
    }
    
    /* Show reminder relates to course */
    
    if ($_GET['show_course_reminder']){
        //Does this mean that $result = true, cause I am not getting
        //die part as error. So I guess the you can try it with GET
        $coursecode = $_GET['coursecode'];
        $result = $reminder->show_course_reminder($coursecode);
        if ($result != false){
            printf($result);
        }else{
            die('Something went wrong');
        }
    }
    
    
    //////////////////////////***Reminderlist***///////////////////////////
    
    /* Create reminderlist */
    if ($_POST['create_reminderlist']){
        $coursecode = 'INFS3202';//$_POST['coursecode']
        $title = 'Group Project';//$_POST['taskname'];
        $content = 'Submit project';//$_POST['description'];
    
        if (!($reminderlist->check_reminderlist($coursecode,$title,$content))){
            die('Duplicate list');
        }
        else{
            $roworder = $reminderlist->new_reminderlist_order($coursecode,$title);
            if (!$reminderlist->create_reminderlist($coursecode,$title,$content,$roworder)){
                die ('reminderlist not created');   
            }
            else{
                die ('reminderlist created');
            }
        }
    }
    /* Delete reminderlist */ /* need to find the current row order, and change the rest of the row order*/
    else if ($_POST['delete_reminderlist']){
        $coursecode = 'INFS3202';//$_POST['coursecode']
        $title = 'Group Project';//$_POST['taskname'];
        $content = 'Finish interface';//$_POST['description'];
        if (!$reminderlist->delete_reminderlist($coursecode,$title,$content)){
            die ('reminderlist not deleted');   
        }
        else{
            die ('reminderlist deleted');
        }
    }
    /* Change reminderlist name */
    else if ($_POST['change_reminderlistname']){
        $coursecode = 'INFS3202';//$_POST['coursecode']
        $title = 'Group Project';//$_POST['taskname'];
        $content = 'Finish interface';//$_POST['description'];
        $newcontent = '';
        if (!($reminderlist->check_reminderlist($coursecode,$title,$content))){
            die('Duplicate list');
        }
        else{
            if (!$reminder->change_reminderlist($coursecode, $title, $content, $newcontent)){
                die ('Reminderlist not changed.');
            }
            else{
                die ('Reminderlist changed');
            }
        }
    }
    /* Mark the reminderlist */
    else if ($_POST['reminder_completed']){
        $coursecode = 'INFS3202';//$_POST['coursecode']
        $title = 'Group Project';//$_POST['taskname'];
        $content = 'Finish interface';//$_POST['description'];
        $boolean = true;
        if (!$reminderlist->completed_reminderlist($coursecode, $remindertitlte, $content, $boolean)){
            die('Cant mark the reminderlist');
        }else{
            die('Reminderlist marked');
        }
    }
    /* Shift reminder list */
    else if ($_POST['shift_reminderlist']){
        $coursecode = 'INFS3202';//$_POST['coursecode']
        $title = 'Group Project';//$_POST['taskname'];
        $contentA = 'C';//$_POST['description'];
        $contentB = 'A';
        if (!$reminderlist->shift_roworder($coursecode,$title,$contentA,$contentB)){
            die('Fail shift');
        }
        else{
            die('Sucess shift');
        }
    }
    
    
    
    ////////////////////////***Task***////////////////////////
    
    if ($_POST['create_task']){
        $coursecode = $_POST['coursecode'];
        $taskname = $_POST['taskname'];
        $description = $_POST['description'];
        $type = $_POST['tasktype'];
        $duedate = $_POST['date'];
        if (!$task->check_task($taskname,$coursecode)){
            die('duplicate taskname');
        }
        else{
            if (!$task->create_task($coursecode,$task,$description,$type,$duedate)){
                die('Fail Create');
            }
            else{
                die ('Task Created');
            }
        }
    }
    else if ($_POST['delete_task']){
        $coursecode = $_POST['coursecode'];
        $taskname = $_POST['taskname'];
        if ($task->check_task($taskname,$coursecode)){
            die('no taskname found');
        }
        else{
            if(!$task->delete_task($coursecode,$taskname)){
                die ('Task not deleted');
            }
            else{
                die('task deleted');
            }
        }
    }
    else if($_POST['change_taskname']){
        $coursecode = $_POST['coursecode'];
        $taskname = $_POST['taskname'];
        $newtaskname = $_POST['newtaskname'];
        if (!$task->check_task($newtaskname,$coursecode)){
            die('Duplicate taskname');
        }
        else{
            if(!$task->change_taskname($coursecode,$taskname,$newtaskname)){
                die('Taskname not changed');
            }
            else{
                die('Taskname changed');
            }
        }
    }
    else if ($_POST['change_taskdescription']){
        $coursecode = $_POST['coursecode'];
        $taskname = $_POST['taskname'];
        $description = $_POST['description'];
        if (!$task->change_taskdescription($coursecode,$taskname,$description)){
            die('description not changed');
        }
        else{
            die ('description changed');
        }
    }
    else if ($_POST['change_tasktype']){
        $coursecode = $_POST['coursecode'];
        $taskname = $_POST['taskname'];
        $type = $_POST['tasktype'];
        $task->change_tasktype($coursecode,$taskname,$type);
    }
    else if ($_POST['task_done']){
        $coursecode = $_POST['coursecode'];
        $taskname = $_POST['taskname'];
        $done = !$task->get_boolean($coursecode,$taskname);
        $task->task_done($coursecode,$taskname,$done);
    }
    
?>