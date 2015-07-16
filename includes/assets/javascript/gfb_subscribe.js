var GFBSubscribe = function(){};
var gfb_subscribe = new GFBSubscribe()

GFBSubscribe.prototype.overlay = function() {
  el = document.getElementById("overlay");
  document.getElementById("gfb_response_message_success").style.display = "none";
  document.getElementById("gfb_response_message_error").style.display = "none";
  document.getElementById("gfb_subscribe_email_text").value = "";
  document.getElementById("gfb_intial_message").style.display = "inline";
  el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
}

GFBSubscribe.prototype.postData = function(email, url){
 if(this.isValidEmail(email) == true){
   // console.log("isValidEmail: "+this.isValidEmail(email));
   var xmlhttp;
   if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
     xmlhttp=new XMLHttpRequest();
   } else {// code for IE6, IE5
     xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }

   xmlhttp.onreadystatechange = function(){
     // console.log("in ready state", xmlhttp.status);
     if (xmlhttp.status == 0 || xmlhttp.status == 200)
       {
         var respTag = document.getElementById("gfb_response_message_success");
         // console.log("Email: ", respTag);
         respTag.innerHTML= "A confirmation email has been sent to your email address. <br/> Click the confirmation link to recieve the ebook.";
         respTag.style.display = "block";
         document.getElementById("gfb_intial_message").style.display = "none";
         document.getElementById("gfb_response_message_error").style.display = "none";

         setTimeout(function(){ gfb_subscribe.overlay(); }, 15000);
       }
     }

   xmlhttp.open("POST",url,true);
   xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
   xmlhttp.send("EMAIL="+email);

 } else {
   var respTag = document.getElementById("gfb_response_message_error");
   // console.log("Email: ", respTag);
   document.getElementById("gfb_response_message_success").style.display = "none";

   respTag.innerHTML= "Valid email address required";
   respTag.style.display = "block";
   // console.log("Not valid: "+this.isValidEmail(email));
 }
}

GFBSubscribe.prototype.isValidEmail = function(entry) {
   return (entry.indexOf(".") > 2) && (entry.indexOf("@") > 0);
}

GFBSubscribe.prototype.scrollManager = function(isNotLoggedin) {
  if(isNotLoggedin) {
    var docBody = document.body,
    docElement = document.documentElement,
    height

    if (typeof document.height !== 'undefined') {
        height = document.height // For webkit browsers
    } else {
        height = Math.max( docBody.scrollHeight, docBody.offsetHeight,docElement.clientHeight, docElement.scrollHeight, docElement.offsetHeight );
    }

    window.onscroll = function () {
      if (window.pageYOffset > (height/2)){
        if (document.getElementById("overlay").style.visibility == false ){
          gfb_subscribe.overlay();
        }
      }
    }
  }
}
