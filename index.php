<?php
    include_once "php/session.php";
    $check = (new sessionStatus())->getStatus();
    if(!$check) {
        header("Location:login.php");
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudentTM</title>
    <link rel="stylesheet" href="css/master.css" />
    <link rel="stylesheet" href="css/app.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/interface.js"></script>
</head>
<body>
    <div id = "overlay" class="courseCreation">
        <div id="courseWrapper">
            <div id="content">
                <div id="editCourse">
                    <div id="header">
                        <span>EDIT COURSES</span>
                    </div>
                    <div id="courseContent">
                        <div id="tableHeader">
                            <div id="row">
                                <div id="column1">
                                  <div>CODE</div>
                                </div>
                                <div id="column2">
                                  <div>NAME</div>    
                                </div>
                                <div id="column3">
                                    <div>DELETE?</div>
                                </div>
                            </div>
                        </div>
                        <div id="listCourse">
                            <!--<div id="row">
                                <div id="column1">
                                    <div>CSSE2002</div>
                                </div>
                                <div id="column2">
                                    <div>JAVA</div>
                                </div>
                                <div id="column3">
                                    <div id="icon">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </div>
                                </div>
                            </div>!-->
                        </div>
                        <div id="addBtn">
                            <div id="options">
                                <span class="glyphicon glyphicon-plus"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="wrapper" class="container">
        <div id="header" class="row expand">
            <div id="header_wrapper">
                <div id="sandbtn_wrapper" class="col">
                    <span class="glyphicon glyphicon-menu-hamburger"></span>
                </div>
                <div id="sidelogo_wrapper" class="col-sm-3 hidden-xs">
                    <div id="canvas">
                        <a href="/INFS3202">
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
                <div id="search_wrapper" class="col-xs-5 col-sm-6">
                  <div id="swrapper">
                     <form method="POST">
                         <div id="button" >
                             <button type="submit">
                                 <span class="glyphicon glyphicon-search"></span>
                             </button>
                         </div>
                         <div id="txt">
                            <input name="search" placeholder="Search" type="text" id="query">
                         </div>
                     </form>
                  </div>
                </div>
                <div id="righticons" class="col">
                    <div id="logout">
                        <a href="/INFS3202/php/logout.php">
                            <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                        </a>
                    </div>
                    <div id="editcourse">
                        <a href = "#">
                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="content" class="row">
            <div id="content_wrapper">
                <div id="sidePanel" class="col">
                    <div id="menuUI">
                        <div id="reminderParent" class="parent">
                            <div id="reminderWrapper" class="wrapper">
                                <a href="#reminders">
                                    <div id="menu" class="menu" role="panel">
                                        <div id="box">
                                                <div id="icon">
                                                    <span class="glyphicon glyphicon-time"></span>
                                                </div>
                                                <div id="text">
                                                    Your Reminders
                                                </div>
                                        </div>
                                    </div>
                                </a>
                                <div id="reminderContent" class="content">
                                    <div id="tree">
                                        <div id="courseName" data-course-name="Web Information System">
                                            <ul id="list">
                                                <a href="#reminders#INFS3202">
                                                    <li id="parent">
                                                        <div id="exstatus">
                                                            <span id="open" class="glyphicon glyphicon-triangle-right"></span>
                                                            <span id="close" class="glyphicon glyphicon-triangle-bottom"></span>
                                                        </div>
                                                        <div id="cname">INFS3202</div>
                                                        <div id="tools">
                                                            <a href="#reminders#INFS3202">
                                                                <div id="add">
                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                </div>
                                                                <div id="selectall">
                                                                    <span class="glyphicon glyphicon-ok-sign"></span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </li>
                                                </a>
                                                <li id="child">
                                                    <div id="cname">
                                                        Group Proposal
                                                    </div>
                                                    <div id="tools">
                                                        <div id="select">
                                                            <span class="glyphicon glyphicon-ok"></span>
                                                        </div>
                                                        <div id="delete">
                                                            <span class="glyphicon glyphicon-trash"></span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li id="child">
                                                    <div id="cname">Research HTML5</div>
                                                    <div id="tools">
                                                        <div id="select">
                                                            <span class="glyphicon glyphicon-ok"></span>
                                                        </div>
                                                        <div id="delete">
                                                            <span class="glyphicon glyphicon-trash"></span>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li id="child">
                                                    <div id="cname">Interface</div>
                                                    <div id="tools">
                                                        <div id="select">
                                                            <span class="glyphicon glyphicon-ok"></span>
                                                        </div>
                                                        <div id="delete">
                                                            <span class="glyphicon glyphicon-trash"></span>
                                                        </div>
                                                    </div>    
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="mainInterface" class="col padEffect">
                    <div id="interfaceWrapper">
                        <div id="reminderInterface" class="container">
                            <div id="reminderHeading" class="row">
                                <div id="headingWrapper" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padEffect">
                                    <div id="content">
                                        <div id="courseCode">
                                            <span>Reminders</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="reminderContent" class="row">
                                <div id="pinBoard">
                                    <div id="pinContent">
                                        <div id="notes" data-notes="Group Proposal" class="col col-sm-12">
                                            <div id="notesWrapper">
                                                <div id="reminderTitle">
                                                    <span>
                                                        Group Proposal
                                                    </span>
                                                </div>
                                                <div id="reminderNotes">
                                                    <div id="checklist" draggable="true">
                                                        <div id="checklist_wrapper">
                                                            <div id="dnd">
                                                                <div id="icon">
                                                                    <span class="glyphicon glyphicon-option-vertical"></span>
                                                                </div>
                                                            </div>
                                                            <div id="checklcontent">
                                                                <div id="lcontent">
                                                                    <div id="reminderList" contenteditable="true" class="checked">
                                                                        Notes are taken here! Nice right?
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="checkbox" class="checked"></div>
                                                        </div>
                                                    </div>
                                                    <div id="checklist" draggable="true">
                                                        <div id="checklist_wrapper">
                                                            <div id="dnd">
                                                                <div id="icon">
                                                                    <span class="glyphicon glyphicon-option-vertical"></span>
                                                                </div>
                                                            </div>
                                                            <div id="checklcontent">
                                                                <div id="lcontent">
                                                                    <div id="reminderList" contenteditable="true">
                                                                        Notes are taken here! Nice right?
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="checkbox"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="notesOptions">
                                                <div id="optionWrapper">
                                                    <div id="timeOption">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </div>
                                                    <div id="deleteOption">
                                                        <span class="glyphicon glyphicon-trash"></span>
                                                    </div>
                                                    <div id="locationOption">
                                                        <span class="glyphicon glyphicon-map-marker"></span>
                                                    </div>
                                                    <div id="completeOption">
                                                        <span class="glyphicon glyphicon-check"></span>
                                                    </div>
                                                    <div id="newReminderList">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
