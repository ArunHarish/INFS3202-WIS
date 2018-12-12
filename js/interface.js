(function($, window, screen, document) {
    $(document).ready(function() {

        //Common Event listeners
        $("div#sandbtn_wrapper").click(function() {
            $("div#header").toggleClass("expand");
        });
        
        $("div#editcourse").click(function() {
            $("body").attr("id", "overlayon")
        });
        
        $("div#content").on("click", function(e) {
            if($(this).attr("id") == "content" && 
                $(this).is(e.target)
            
            ) {
                $("body").removeAttr("id");
                console.log(e);
            }
            
            console.log(e.target)
            
        })

        //Classes
        /**Ajax request **/
        function loadData() {

            this.eventListen = $("<event></event>")[0];
            this.courses = [];
            this.reminders = [];
            this.tasks = [];

            this.createNewReminder = function(reminderTitle, cid, location) {
                //Requests create reminder to create with the reminderName
                var location = location || undefined;
                
                var response = $.ajax({
                    url: "/INFS3202/php/controller/create_reminder.php",
                    method: "POST",
                    data: {
                        cid: cid,
                        title: reminderTitle,
                        location: location
                    }
                });


                return response;
            }

            this.setCourse = function(courseData, isAjax) {
                //isAjax means initial 
                var isAjax = isAjax || false;
                if (isAjax) {
                    this.courses = courseData;
                }
                else {

                    if (courseData instanceof Object) {
                        for (var x in courseData) {
                            if (!(x in this.courses)) {
                                this.courses[x] = courseData[x];
                            }
                        }
                    }

                }

            }



            this.setReminders = function(reminder, isAjax) {

                //The reminder is from Ajax:
                var isAjax = isAjax || false;

                if (isAjax) {
                    this.reminders = reminder;
                }
                else {
                    var returnList = [reminder.CID, reminder.Name, reminder.location, reminder.LID];
                    //The param reminders is going to give a set of reminder object
                    this.reminders.push(returnList);

                    return returnList;
                }

            }

            // copies the reminderlist array returned from ajax to reminder
            // array
            this.setReminderList = function(reminderList, isAjax) {
                //isAjax part after
                var cacheReminder = this.reminders;

                for (var x = 0; x < cacheReminder.length; x++) {
                    for (var y = 0; y < cacheReminder[x].reminders.length; y++) {

                        var element = cacheReminder[x].reminders[y];
                        var listID = [];

                        for (var m = 0; m < reminderList.length; m++) {
                            if (reminderList[m].rid == element[0]) {

                                listID = reminderList[m].reminderlist;
                                break;
                            }
                        }

                        element.push(listID)
                    }
                }

                this.reminders = cacheReminder;

            }

            //Gets all reminders of a given cid
            this.getReminders = function(cid) {

                var reminders = this.reminders;

                var returnValue = {
                    CID: cid,
                    reminders: []
                };


                for (var x = 0; x < reminders.length; x++) {
                    if (reminders[x].CID == cid) {
                        returnValue.reminders =
                            (reminders[x].reminders);
                        return returnValue;
                    }
                }

            }

            this.deleteReminder = function(rid, cid) {
                var ajax = $.ajax("/INFS3202/php/controller/delete_reminder.php", {
                    data: {
                        cid: cid,
                        rid: rid
                    }
                });
                
                console.log(rid, cid);

                return ajax;
            }

            this.getAllRID = function() {
                var reminderArray = this.getAllReminders();
                var RIDs = [];

                //Fetching all RIDs
                for (var x = 0; x < reminderArray.length; x++) {
                    var rid = reminderArray[x].reminders[0];
                    if (rid) {
                        RIDs.push(rid);
                    }

                }

                return RIDs;
            }


            //Gets reminders of all cids or all stored cid
            //Returns every reminders of a course
            this.getAllReminders = function(cid) {

                var courseIDs = cid || this.getAllCID();
                var returnList = [];

                for (var x = 0; x < courseIDs.length; x++) {
                    var course = courseIDs[x];
                    var reminderList = this.getReminders(course);
                    var allReminders;

                    if (reminderList) {
                        allReminders = reminderList.reminders;
                        if (allReminders) {
                            for (var i = 0; i < allReminders.length; i++) {
                                returnList.push({
                                    CID: course,
                                    reminders: allReminders[i]
                                });
                            }
                        }
                    }

                }

                return returnList;

            }

            //Gets all the reminder List for given rid - useful for main interface
            this.getAllReminderList = function(rid) {
                //gets all reminder list based on rid
                var rid = rid || this.getAllRID();
                var cacheReminder = this.reminders;
                var returnList = [];
                if (rid instanceof Array) {

                    for (var x = 0; x < cacheReminder.length; x++) {
                        var reminder = cacheReminder[x].reminders;
                        for (var y = 0; y < reminder.length; y++) {
                            if (rid.indexOf(reminder[y][0]) > -1 || reminder[y][0] == rid) {
                                returnList.push({
                                    RID: reminder[y][0],
                                    LID: reminder[y][3],
                                    Name: reminder[y][1]
                                })
                            }
                        }

                    }


                    return returnList;

                }
                else {

                }
            }



            this.getCourses = function() {

                return this.courses;
            }

            this.createNewCourse = function(coursecode, coursename) {
                // gets the coursename, coursecode and sends it to php

                var response = $.ajax({
                    url: "/INFS3202/php/controller/create_course.php",
                    method: "POST",
                    data: {
                        coursecode: coursecode,
                        coursename: coursename
                    }
                });


                return response;

            }

            this.deleteCourse = function(cid) {
                var response = $.ajax({
                    url: "/INFS3202/php/controller/delete_course.php",
                    method: "POST",
                    data: {
                        cid: cid
                    }
                });

                return response;
            }

            //Given the course code this function returns the CID
            this.getCID = function(coursecode) {

                    var courseList = this.courses;

                    if (coursecode in courseList) {
                        return courseList[coursecode][1];
                    }
                    return null;
                }
                //Returns all the CID of current subjects using above function
            this.getAllCID = function(coursecodes) {

                var returnList = [];
                var coursecodes = coursecodes || Object.keys(this.getCourses());

                for (var x = 0; x < coursecodes.length; x++) {

                    var output = this.getCID(coursecodes[x]);

                    if (output != null && returnList.indexOf(output) < 0) {
                        returnList.push(
                            output
                        );
                    }

                }

                return returnList;

            }

            this.fetchContent = function(command) {
                //Fetch content
                switch (command) {
                    case 0:
                        // get all content
                        var courseFetch;
                        var reminderFetch;
                        var reminderListFetch;

                        courseFetch = $.ajax({
                            url: "/INFS3202/php/controller/show_course.php",
                            method: "POST",
                            data: {
                                "show_course": 1
                            },
                            context: this
                        });

                        courseFetch.done(function(e) {

                            this.setCourse(e, true);
                            var values = this.getAllCID(Object.keys(e));

                            //fetching reminders
                            reminderFetch = $.ajax({
                                url: "/INFS3202/php/controller/show_reminder_course.php",
                                method: "POST",
                                data: {
                                    "CID": JSON.stringify(values),
                                    "multiple": 1
                                },
                                context: this
                            });

                            reminderFetch.done(function(d) {
                                this.setReminders(d, true);

                                var events = new CustomEvent("entireLoadComplete");
                                var RIDs = this.getAllRID();

                                //Requires all the RIDs of the reminders

                                reminderListFetch = $.ajax({
                                    url: "/INFS3202/php/controller/show_reminderlist.php",
                                    method: "POST",
                                    data: {
                                        //Mutiple is set to be true implies the reminders are sent
                                        "multiple": 1,
                                        "rid": JSON.stringify(RIDs)
                                    },
                                    context: this
                                });

                                reminderListFetch.done(function(result) {
                                    this.setReminderList(result);
                                    this.eventListen.dispatchEvent(events);
                                });
                            });

                        });
                        break;

                    case 1:
                        //get only reminders
                        break;
                    default:
                        //get only courses
                }
            }


            this.pushContent = function(command) {
                switch (command) {
                    case 0:
                        //push all content
                        break;
                    case 1:
                        //pushes any new reminder
                        break;

                    case 2:
                        //push tasks
                        break;
                }
            }
        }

        /**View**/

        function domView() {
            //This function is a view

            //Keep tracking of elements that are already appended to side panel
            this.dom = {
                reminders: {
                    domTrack: {
                        sidePanels: {
                            courseHeading: [],
                            reminderNotes: []
                        },
                        mainInterface: {
                            reminderCard: [],
                            reminderList :[]
                        },
                        course: []
                    },
                    reference: {
                        sidePanels: $(
                            "div#sidePanel div#reminderParent div#reminderWrapper div#reminderContent div#tree"
                        ),
                        mainInterface: {
                            reminderHeading: $(
                                "div#reminderInterface>div#reminderHeading"
                            ),
                            pinContent: $(
                                "div#reminderInterface>div#reminderContent>" +
                                "div#pinBoard>div#pinContent"
                            )
                        },
                        courseEdit: $("div#editCourse div#courseContent div#listCourse")
                    },
                    template: {

                        mainInterface: {
                            divNotes: function() {
                                return $("<div id =\"notes\" class=\"col col-sm-12\"></div>");
                            },
                            notesWrapper: function() {
                                return $("<div id=\"notesWrapper\"></div>")
                            },
                            reminderTitle: function(title) {
                                return $('<div id="reminderTitle"></div>').append(
                                    $('<span>' + title + '</span>')
                                )
                            },
                            reminderNotes: function() {
                                return $('<div id="reminderNotes"></div>');
                            },
                            checkList: function() {
                                return $('<div id="checklist" draggable="true"></div>')
                            },
                            checkList_wrapper: function() {
                                return $('<div id="checklist_wrapper"></div>').append(
                                    $(
                                        '<div id="dnd">' +
                                        '<div id="icon">' +
                                        '<span class="glyphicon glyphicon-option-vertical"></span>' +
                                        '</div>' +
                                        '</div>'
                                    )
                                )
                            },
                            checklcontent: function(content) {
                                return $('<div id="checklcontent"></div>').append(
                                    $('<div id="lcontent"></div>').append(

                                        $(
                                            '<div id="reminderList" contenteditable="true">' +
                                            content +
                                            '</div>'
                                        )
                                    )
                                )
                            },
                            notesOptions: function() {
                                return $('<div id="notesOptions">' +
                                    '<div id="optionWrapper">' +
                                    '<div id="timeOption">' +
                                    '<span class="glyphicon glyphicon-time"></span>' +
                                    '</div>' +
                                    '<div id="deleteOption">' +
                                    '<span class="glyphicon glyphicon-trash"></span>' +
                                    '</div>' +
                                    '<div id="completeOption">' +
                                    '<span class="glyphicon glyphicon-check"></span>' +
                                    '</div>' +
                                    '<div id="newReminderList">' +
                                    '<span class="glyphicon glyphicon-plus"></span>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>')
                            }
                        },
                        sidePanels: {
                            //For course wrapper
                            courseWrapper: function() {
                                return $("<div id=\"courseName\" ></div>");
                            },
                            //For parent wrapper
                            reminderList: function() {
                                return $("<ul id=\"list\"></ul>");
                            },
                            parentList: function() {
                                return $("<li id =\"parent\"></li>")
                            },
                            parentCollapse: function() {
                                return $("<div id =\"exstatus\"></div>");
                            },
                            collapse: function() {
                                return $("<span id=\"open\" class=\"glyphicon glyphicon-triangle-right\"></span>")
                                    .add($("<span id=\"close\" class=\"glyphicon glyphicon-triangle-bottom\"></span>"));
                            },
                            courseName: function() {

                                return $("<div id=\"cname\"></div>");
                            },
                            courseTool: function() {
                                return $("<div id=\"tools\">" +
                                    "<div id=\"addReminders\">" +
                                    "<span class=\"glyphicon glyphicon-plus\"></span>" +
                                    "</div>" +
                                    "<div id=\"selectAll\">" +
                                    "<span class=\"glyphicon glyphicon-ok-sign\"></span>" +
                                    "</div>" +
                                    "</div>")
                            },
                            //For child wrapper
                            childList: function() {

                                return $("<li id=\"child\"></li>");
                            },
                            reminderName: function() {

                                return $("<div contentEditable id=\"cname\"></div>");
                            },
                            reminderTool: function() {

                                return $("<div id=\"tools\">" +
                                    "<div id=\"select\"><span class=\"glyphicon glyphicon-ok\"></span></div>" +
                                    "<div id=\"delete\"><span class=\"glyphicon glyphicon-trash\"></span></div>" +
                                    "</div>");
                            },
                            anchorTag: function(middleContent, listHref) {
                                var tag = $("<a>" + middleContent + "</a>")
                                for (var x = 0; x < listHref.length; x++) {
                                    tag.attr("href", (tag.attr("href") || "") + "#" + listHref[x]);
                                }
                                return tag;
                            }
                        },
                        courseEdit: {
                            mainRow: function(edited) {
                                var element = $("<div id='row'></div>");
                                if (!edited) {
                                    element.attr({
                                        "data-unedited": "true"
                                    })
                                }
                                return element;

                            },
                            colCode: function(value) {
                                if (!value) {
                                    value = "[New Code]";
                                }

                                return $("<div id='code'><div contenteditable>" +
                                    value +
                                    "</div></div>");
                            },
                            colName: function(value) {
                                if (!value) {
                                    value = "[New Name]";
                                }
                                return $("<div id='name'><div contenteditable>" +
                                    value +
                                    "</div></div>");
                            },
                            colDelete: function() {
                                return $("<div id='option'>" +
                                    "<div id='icon'>" +
                                    "<span class='glyphicon glyphicon-trash'></span>" +
                                    "</div>" +
                                    "</div>");
                            }
                        }

                    }

                }
            }


            this.updateCIDTracker = function(cid, domElement, type) {
                if (type == "courses") {

                    var tracker = this.dom.reminders.domTrack.course;

                    for (var x = 0; x < tracker.length; x++) {
                        if (tracker[x].DOM.is(domElement)) {
                            tracker[x].CID = cid;
                            break;
                        }
                    }
                }
            }



            this.addCourse = function(isAjax, value) {

                function synthesis(coursecode, coursename, courseid, edited) {
                    var edited = edited || false;
                    var mainRow = this.dom.reminders.template.courseEdit.mainRow(edited).append(
                        this.dom.reminders.template.courseEdit.colCode(coursecode).add(
                            this.dom.reminders.template.courseEdit.colName(coursename)
                        ).add(
                            this.dom.reminders.template.courseEdit.colDelete()
                        )
                    )

                    this.dom.reminders.domTrack.course.push({
                        CID: courseid,
                        DOM: mainRow
                    });

                    this.dom.reminders.reference.courseEdit.append(
                        mainRow
                    )

                };

                if (isAjax) {
                    for (var x in value) {
                        synthesis.call(this, x, value[x][0], value[x][1], true);
                    }
                }
                else {
                    synthesis.call(this, false, false, false, false);
                }


            }

            this.removeCourse = function(cid) {
                    var domTrack = this.dom.reminders.domTrack.course;

                    for (var x = 0; x < domTrack.length; x++) {
                        if (domTrack[x].CID == cid) {
                            domTrack[x].DOM.remove();
                            break;
                        }
                    }

                }
                //adding course
                /*
                 * @param courseList : Object
                 */
            this.addSidePanelCourse = function(courseList) {
                //courseList: coursecode as key coursename and CID as values



                var parent = this.dom.reminders.reference.sidePanels;
                for (var course in courseList) {

                    var courseWrapperDOM =
                        this.dom.reminders.template.sidePanels.courseWrapper().append(
                            this.dom.reminders.template.sidePanels.reminderList().append(
                                this.dom.reminders.template.sidePanels.parentList().append(
                                    this.dom.reminders.template.sidePanels.anchorTag("", ["reminders", course]).append(
                                        this.dom.reminders.template.sidePanels.parentCollapse().append(
                                            this.dom.reminders.template.sidePanels.collapse()
                                        ).add(
                                            this.dom.reminders.template.sidePanels.courseName().append(
                                                course
                                            ).add(
                                                this.dom.reminders.template.sidePanels.courseTool()
                                            )
                                        )
                                    )
                                )
                            )
                        );

                    //Any changes made in the courses should be reflected
                    //The below domTrack is to track the DOM element 
                    //associated with each course ID
                    parent.append(
                        this.dom.reminders.domTrack.sidePanels.courseHeading[
                            this.dom.reminders.domTrack.sidePanels.courseHeading.push({
                                    DOM: courseWrapperDOM,
                                    CID: courseList[course][1],
                                }

                            ) - 1
                        ].DOM
                    )
                }
            };

            //adding reminder names
            this.addReminderBranch = function(reminderObject) {



                var reminders = reminderObject;

                var domTrack = this.dom.reminders.domTrack.sidePanels.courseHeading;
                var domRIDTrack = this.dom.reminders.domTrack.sidePanels.reminderNotes;

                //Check to know where to put the reminder content
                for (var n = 0; n < reminders.length; n++) {

                    for (var m = 0; m < domTrack.length; m++) {

                        //To use the record of courses currently in private scope

                        if (reminders[n].CID == domTrack[m].CID) {

                            var uiTree = domTrack[m].DOM.children("ul#list");
                            var reminderData = reminders[n].reminders;
                            var reminderTitle = reminderData[1];
                            var reminderID = reminderData[0];
                            var DOM = this.dom.reminders.template.sidePanels.childList().append(
                                this.dom.reminders.template.sidePanels.reminderName().append(
                                    reminderTitle
                                ).add(
                                    this.dom.reminders.template.sidePanels.reminderTool()
                                )
                            )
                            
                            domRIDTrack.push({
                                RID : reminderID,
                                DOM : DOM
                            });
                            
                            uiTree.append(
                                DOM
                            );
                            break;
                        }

                    }

                }
            };
            //Adds the notes to the interface
            this.addReminder = function(notes) {

                var domTrack = this.dom.reminders.domTrack.mainInterface.reminderCard;
                var noteReference = this.dom.reminders.reference.mainInterface;
                var domTemplate = this.dom.reminders.template.mainInterface;

                //The variable is used for reference when adding the reminder lists
                //The Notes of each reminders are added to the tracker


                for (var x = 0; x < notes.length; x++) {
                    var reminderTitle = notes[x].Name;
                    var checkListNode = domTemplate.reminderNotes();

                    for (var y = 0; y < notes[x].LID.length; y++) {

                        checkListNode.append(
                            domTemplate.checkList().append(
                                domTemplate.checkList_wrapper().append(
                                    domTemplate.checklcontent(
                                        notes[x].LID[y][1]
                                    )
                                )
                            )
                        )

                    }
                    
                    var dom = domTemplate.divNotes().append(
                                domTemplate.notesWrapper().append(
                                    domTemplate.reminderTitle(
                                        reminderTitle
                                    ).add(
                                        checkListNode
                                    )
                                ).add(
                                    domTemplate.notesOptions()
                                )
                            )
                    
                    noteReference.pinContent.append(
                        domTrack[
                            domTrack.push(dom) - 1    
                        ]
                    )

                }
            }
            
            this.createNewReminderList = function(element) {
                var elementTo = element.find("div#notesWrapper>div#reminderNotes");
                var reminderListDOM = this.dom.reminders.domTrack.mainInterface.reminderList;
                
                //Create new UI
                var domTemplate = this.dom.reminders.template.mainInterface;
                var reminderListDOM = domTemplate.checkList().append(
                        domTemplate.checkList_wrapper().append(
                            domTemplate.checklcontent("").attr({
                                "data-unedited" : true
                            })    
                        )    
                    );
                
                elementTo.append(
                    reminderListDOM
                );
                
            }
            
            this.removeReminder = function(liList) {
                //same as adding

                var liChild = liList.parents("li#child");

                liChild.remove();

            }

            this.addNewReminder = function(ulList) {
                ulList.append(
                    this.dom.reminders.template.sidePanels.childList().append(
                        this.dom.reminders.template.sidePanels.reminderName().append(
                            "[New Reminder]"
                        ).attr({
                            "data-new": "true",
                            "data-unedited": "true"
                        })
                        .add(
                            this.dom.reminders.template.sidePanels.reminderTool()
                        )
                    )
                )
            }

        };

        /**Controller**/
        function domController(view, modelControl, hashChange) {
            //Initialisating to fetch all user data
            modelControl.fetchContent(0);
            //Event listens for initial load complete
            modelControl.eventListen.addEventListener("entireLoadComplete", function() {
                var courses = modelControl.getCourses();
                var reminders = modelControl.getAllReminders();
                var reminderLists = modelControl.getAllReminderList();

                view.addSidePanelCourse(courses);
                view.addReminderBranch(reminders);
                view.addReminder(reminderLists);
                view.addCourse(true, courses);

            });

            //Any Interaction with side panels here

            //Adding new reminder
            $("div#tree").on("click", "div#courseName ul#list li#parent div#tools div#addReminders", function() {

                //Create a new reminder in the model and it sends it
                //Put in the parent ul#list id to generate
                //Generate adds new reminder
                view.addNewReminder(
                    $(this).parents("ul#list")
                );
            });


            //Reminder on keyup change the attribute
            $("div#tree").on("keyup", "div#courseName ul#list li#child div#cname", function() {

                var cname = $(this);
                var text = cname.text();
                var attribute = "data-unedited";
                var uneditedText = "[New Reminder]";

                if (cname.attr(attribute) && text != uneditedText) {
                    cname.removeAttr(attribute)
                }
                else if (text == uneditedText) {
                    cname.attr(attribute, "true");
                }

            })

            //Reminder on focus remove the default content

            $("div#tree").on("focus", "div#courseName ul#list li#child div#cname", function() {
                var cname = $(this);
                var text = cname.text();
                var uneditedText = "[New Reminder]";

                if (text == uneditedText && cname.attr("data-unedited")) {
                    cname.text("");
                }

            });

            //Reminder On blur indicates user finished changes
            $("div#tree").on("blur", "div#courseName ul#list li#child div#cname", function() {

                var cname = $(this);
                var parent = cname.parents("div#courseName");
                var content = cname.text();
                var domReference = view.dom.reminders.domTrack.sidePanels.courseHeading;
                var domTrack = view.dom.reminders.domTrack.reminders;
                var cid;
                
                
                console.log(domReference);

                for (var x = 0; x < domReference.length; x++) {
                    if (domReference[x].DOM.is(parent)) {
                        cid = (domReference[x].CID);
                        break;
                    }
                }

                //if new then
                if (cname.attr("data-new") && !cname.attr("data-unedited") && content.length > 0) {
                    //Needs content, CID and location - optional

                    var response = modelControl.createNewReminder(
                        content, cid, undefined, this
                    );

                    response.done(function(e) {
                        console.log(e);
                        if (e.errorCode == 0) {

                        }
                        else if (e.errorCode == -1) {

                        }
                        else {
                            //1) Fetches the rid from db
                            //2) Puts it in the array
                            //3) Update the UI

                            //Get the rid
                            var newRID = e.rid;
                            var newReminderName = e.reminderName;
                            var newReminderCID = cid;

                            //Update the model reminder
                            var sendingObject = {
                                rid: e.rid,
                                Name: e.reminderName,
                                location: e.location,
                                CID: cid,
                                LID: e.lid
                            }
                            var newNoteList = modelControl.setReminders(
                                sendingObject
                            )

                            //Update the UI
                            view.addReminder([sendingObject]);
                            
                            window.location.reload();
                            
                        }
                    })

                }
            });

            $("div#tree").on("click", "div#courseName ul#list li#child div#tools div#delete", function() {


                //Need CID and RID
                //Get LID from DOM
                //GET CID from DOM
                var tracker = view.dom.reminders.domTrack.sidePanels.reminderNotes;
                var sidePanelTracker = view.dom.reminders.domTrack.sidePanels.courseHeading;
                var element = $(this);
                var comparator = element.parents(
                    "li#child"    
                );
                var comparatorParent = comparator.parents("div#courseName");
                var comparatorRID ;
                var comparatorCID ;
                
                for(var x = 0; x < tracker.length; x++) {
                    if(tracker[x].DOM.is(comparator)) {
                        comparatorRID = tracker[x].RID;
                        break;
                    }
                }
                
                for(var x = 0; x < sidePanelTracker.length; x++) {
                    if(sidePanelTracker[x].DOM.is(comparatorParent)) {
                        comparatorCID = sidePanelTracker[x].CID;
                        break;
                    }
                }
                
                var ajax = modelControl.deleteReminder(comparatorRID.toString(), comparatorCID.toString());

                ajax.done(function(e) {
                    if(e.status == 1) {
                        view.removeReminder(element);
                        window.location.reload();
                    }
                })

            })

            //Coursecode create

            $("div#editCourse div#courseContent div#addBtn div#options").on("click", function() {
                view.addCourse(false, $(this));
            });


            $("div#editCourse div#courseContent div#listCourse").on("blur", "div#row", function() {

                //The problem is that all the UI must be created
                //Prior to association
                //The plan is to create update CID domtracker

                var element = $(this);
                var selectedElement = element.children();
                var uneditedText = {
                    code: "[New Code]",
                    name: "[New Name]"
                }
                var cleared = true;

                for (var x = 0; x < selectedElement.length; x++) {
                    var childElement = $(selectedElement[x])
                    var attribute = childElement.attr("id");

                    if (attribute in uneditedText &&
                        (childElement.text() == uneditedText[attribute] ||
                            (childElement.text().length == 0)
                        )) {
                        cleared = false;
                    }
                }

                if (cleared) {

                    if (element.attr("data-unedited") == "true") {

                        var response = modelControl.createNewCourse($(selectedElement[0]).text(),
                            $(selectedElement[1]).text()
                        );

                        response.done(function(e) {

                            if (e.status == -2) {
                                alert("Course Name must be lesser that 15 characters");
                            }
                            else if (e.status == -1) {
                                alert("Course code already exists");
                            }
                            else if (e.status == 0) {
                                alert("Server error : Please inform the administrator");
                            }
                            else {

                                element.removeAttr("data-unedited");


                                var passingValue = {};

                                passingValue[e.coursecode] = [
                                    e.name,
                                    e.cid
                                ];
                                //Change the model of the data
                                modelControl.setCourse(passingValue);
                                //Update the UI
                                view.addSidePanelCourse(
                                    passingValue
                                );
                                //Update the UI for courses
                                view.updateCIDTracker(e.cid, element, "courses");

                            }
                        });
                    }
                    else {
                        //Code to do if it has to be renamed
                    }
                }
            })

            $("div#editCourse div#courseContent div#listCourse").on("click", "div#row>div#option", function() {
                //To delete the course you need to have the CID - done
                //Must have Dom reference so it can removed - done
                //Plan use the current element go till the div#row and compare with tracker
                var parentElement = $(this).parents("div#row");
                //Fetch the CID and then conduct the deletion below
                var parentReference = view.dom.reminders.domTrack.course;
                var ajaxResponse;
                var CID;
                for (var x = 0; x < parentReference.length; x++) {
                    if (parentReference[x].DOM.is(parentElement)) {
                        CID = parentReference[x].CID;
                        ajaxResponse = modelControl.deleteCourse(
                            CID
                        );

                        break;
                    }
                }

                ajaxResponse.done(function(e) {
                    //Once successful it should try and delete all
                    if (e.code == 1) {
                        //Start and delete all the relevant properties
                        //Delete the relevant documents:
                        //The course 
                        view.removeCourse(CID);
                        window.location.reload();
                    }
                })
            });
            
            //Interaction with main interface here
            
            $("div#pinContent").on("click", "div#notes div#notesOptions div#newReminderList", function() {
                //Create a new list on UI
                
                view.createNewReminderList(
                    $(this).parents("div#notes")
                );
                //Listens whether user has made any good changes
                
                //Send the data to server
                
                //Update the UI
                
                //Update the DOM reference
            })
            

        }

        /**Hash Location**/
        function hashLocation() {

            this.location = null;
            this.domFunction = {
                "reminders": function(commands) {
                    this.sidePanel("reminder");
                },
                "tasks": function() {
                    this.sidePanel("task")
                },
                "search": function() {

                }
            };

            this.sidePanel = function(interface) {
                $("div#menuUI div.parent").addClass("cl");
                $("div#menuUI div.parent div.wrapper div[role='panel'].menu")
                    .parents("div#" + interface + "Parent.parent").removeClass("cl");
            }

            this.detectChange = function(newLocation) {
                var currentHash = newLocation.hash;
                if (this.location != currentHash) {
                    this.location = currentHash;
                    this.parseHash(this.location);
                }
            }

            this.parseHash = function(hash) {

                var hash = hash.split("#").slice(1);

                for (var key in this.domFunction) {
                    if (hash.indexOf(key) == 0) {
                        this.domFunction[key].call(this, hash.slice(1));
                    }
                }

            }
        }

        //Objects

        var hash = new hashLocation();
        var modelControl = new loadData();
        var userInterface = new domView();
        var intefaceController = new domController(userInterface, modelControl,
            hash);


        //Intervals
        setInterval(function() {
            hash.detectChange(
                window.location
            );
        })

    });

})($, window, screen, document);
