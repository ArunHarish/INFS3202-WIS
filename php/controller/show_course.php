<?php
/**   Output example:
 * Yeah looks right, I just have to get the first element of value
 *  {"INFS3202":["WebInfor",10],"CSSE2002":["JAVA",11],"CSSE2010":["Computer",12]} 
 **/
    require_once "../admin.php";
    
    header('Content-Type: text/json');
    
    $course = new course;
    
    
    if ($_POST['show_course'] && $_POST['show_course'] == true){
        $result = $course->show_course();
        if ($result != false){
            printf($result);
        }
        else{
            die('Something went wrong');
        }
    }
    
    
?>