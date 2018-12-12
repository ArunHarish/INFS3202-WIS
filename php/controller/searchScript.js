 /* While using AJAX, it is necessary to create a different object for Internet Explorer, otherwise IE browser won't support. It is shown in below coding.  
 */  
 var xmlHttp = createXmlHttpRequestObject();  
 // Create XML Http Request object  
 function createXmlHttpRequestObject(){  
  var xmlHttp;  
 // For Internet Explorer   
  if(window.ActiveXObject){  
  try{  
   xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");  
  }catch(e){  
   alert("can't create IE object");  
   xmlHttp = false;  
  }  
  }else{  
 //For Google Chrome and other  
  try{  
   xmlHttp = new XMLHttpRequest();   
  }catch(e){  
   xmlHttp = false;   
  }  
  }  
  if(!xmlHttp){  
  alert("can't create that object!");  
  }  
  else{  
  return xmlHttp;  
  }  
 }  
 //function which gets triggered on the event onkeyup in search box  
 function process(){  
 if(xmlHttp.readyState == 4 || xmlHttp.readyState == 0){  
  filter = document.getElementById('filter').style.display="block";  
  //Store the keyword form the search box in a variable  
  userInput = encodeURIComponent(document.getElementById("userInput").value);  
  /*Send the request, make sure you give 3 parameters  
 1-Method, 2- url, 3- true or false   
 */  
  xmlHttp.open("GET", "search.php?userInput="+userInput, true);  
 //set the function which takes care of response  
  xmlHttp.onreadystatechange = handleResponse;  
  xmlHttp.send(null);  
  }else{  
  }  
 }  
 function handleResponse(){  
  if(xmlHttp.readyState == 4 || xmlHttp.readyState == 0){  
  if(xmlHttp.status == 200){  
   xmlResponse = xmlHttp.responseText;  
   document.getElementById("filter").innerHTML = xmlResponse;  
  }else{  
   alert("something went wrong");  
  }  
  }  
 } 