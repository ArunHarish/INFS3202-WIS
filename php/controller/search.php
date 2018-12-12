<?php
    require_once "../admin.php";
    header('Content-Type: text/json');
    

    if(isset($_GET['userInput'])){
        $value = $_GET['userInput']; //assign the value
    }
    else{
        echo "no input";
    } 
    
    $uid = get_uid();
    $value = strtoupper($value);
    $conn = connect_database();
    
    
    $sql = "SELECT title FROM reminder WHERE upper(title) LIKE '%".$value."%' AND UID = ".$uid."";
    if($result = mysqli_query($conn, $sql)){
       if(mysqli_num_rows($result) > 0){ 
        //Store the result in an array list[]
            while($row = mysqli_fetch_array($result)){
                $list[] = $row['title'];
            }
       }
       else{
           $list[] = "";
       }
   }

   $sql = "SELECT content FROM reminderlist WHERE upper(content) LIKE '%".$value."%' AND UID = ".$uid."";
    if($result = mysqli_query($conn, $sql)){
       if(mysqli_num_rows($result) > 0){ 
        //Store the result in an array list[]
            while($row = mysqli_fetch_array($result)){
                $list[] = $row['content'];
            }
       }
    }
    

        
    // PROBLEM: The user input does not updated asap    
        
        
    if(!empty($value)){
        if($matched = preg_grep('~'.$value.'~', $list)){
            $count = 0;
            echo '<ul>';
            while($count < sizeOf($list)){
                if(isset($matched[$count])){
    
                  echo '<li>'.$matched[$count].'</li>';
                }
                $count++;
            }
            echo '</ul>';
        }
        else{
            echo "No result";
        }
    }
  
    
?>