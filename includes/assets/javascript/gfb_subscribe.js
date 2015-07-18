var GFBSubscribe = function(){};

GFBSubscribe.prototype.scroller_activity_status = false;
GFBSubscribe.prototype.overlay_display_status = "hidden";

GFBSubscribe.prototype.overlay = function() {
  el = document.getElementById("gfb_widget_overlay");
  document.getElementById("gfb_response_message_success").style.display = "none";
  document.getElementById("gfb_response_message_error").style.display = "none";
  document.getElementById("gfb_subscribe_email_text").value = "";
  document.getElementById("gfb_intial_message").style.display = "inline";
  el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
  this.overlay_display_status = el.style.visibility;

  if (this.overlay_display_status == "visible") {
    this.scroller();
  }
  // console.log("El visibility overlay:", this.overlay_display_status);
}

GFBSubscribe.prototype.postData = function(email, url){
 var _this = this;

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

         setTimeout(function(){ _this.overlay(); }, 15000);
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

GFBSubscribe.prototype.scrollManager = function(isNotLoggedIn, scrollerActivityStatus) {
  if(typeof(isNotLoggedIn)==='undefined') isNotLoggedIn = true;
  if(typeof(scrollerActivityStatus)==='undefined') scrollerActivityStatus = false;

  this.scroller_activity_status = scrollerActivityStatus

  if (isNotLoggedIn) {
    this.scroller();
  }
}

GFBSubscribe.prototype.scroller = function() {
   if(this.scroller_activity_status){
      var _this = this;
      window.onscroll = function () {
        // console.log("El visibility:", this.overlay_display_status);
        var docBody = document.body,
        docElement = document.documentElement,
        form_el = document.getElementById("gfb_newsletter_signup_form"),
        vertical_position = 0,
        height

        if(typeof document.height !== 'undefined') {
          height = document.height // For webkit browsers
        } else {
          height = Math.max( docBody.scrollHeight, docBody.offsetHeight,docElement.clientHeight, docElement.scrollHeight, docElement.offsetHeight );
        }

        if(window.pageYOffset > (height/2)){
          if(_this.overlay_display_status == 'hidden' ){
            _this.overlay();
          }
        }

        if(window.pageYOffset){
          vertical_position = window.pageYOffset;
        } else if(docElement.clientHeight){//ie
          vertical_position = docElement.scrollTop;
        } else if(docBody){//ie quirks
          vertical_position = docBody.scrollTop;
        }

        form_el.style.top = vertical_position + 'px';
        // console.log("Now:", form_el.style.top );
      }
    }
  }

var gfb_subscribe = new GFBSubscribe();
