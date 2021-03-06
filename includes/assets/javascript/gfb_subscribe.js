var GFBSubscribe = function(){};

GFBSubscribe.prototype.scroller_activity_status = false;
GFBSubscribe.prototype.overlay_display_status = "hidden";

GFBSubscribe.prototype.docBody = null;
GFBSubscribe.prototype.docElement = null;

GFBSubscribe.prototype.eventSource = null;

GFBSubscribe.prototype.overlay = function(source) {
  this.eventSource = source;

  if(this.eventSource == 'onboxclose'){
    this.scroller_activity_status = false;
  }

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

  this.LayoutManager(el);
}

GFBSubscribe.prototype.postData = function(email, url){
 var _this = this;

 if(this.isValidEmail(email) == true){
   var xmlhttp;
   if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
     xmlhttp=new XMLHttpRequest();
   } else {// code for IE6, IE5
     xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }

   xmlhttp.onreadystatechange = function(){
     if (xmlhttp.status == 0 || xmlhttp.status == 200)
       {
         var respTag = document.getElementById("gfb_response_message_success");
         respTag.innerHTML= "A confirmation email has been sent to your email address. <br/> Click the confirmation link to recieve the ebook.";
         respTag.style.display = "block";
         document.getElementById("gfb_intial_message").style.display = "none";
         document.getElementById("gfb_response_message_error").style.display = "none";

         setTimeout(function(){ _this.overlay('onreadystatechange'); }, 15000);
       }
     }

   xmlhttp.open("POST",url,true);
   xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
   xmlhttp.send("EMAIL="+email);

 } else {
   var respTag = document.getElementById("gfb_response_message_error");
   document.getElementById("gfb_response_message_success").style.display = "none";

   respTag.innerHTML= "Valid email address required";
   respTag.style.display = "block";
 }
}

GFBSubscribe.prototype.isValidEmail = function(entry) {
   return (entry.indexOf(".") > 2) && (entry.indexOf("@") > 0);
}

GFBSubscribe.prototype.scrollManager = function(isNotLoggedIn, scrollerActivityStatus) {
  if(typeof(isNotLoggedIn)==='undefined') isNotLoggedIn = true;
  if(typeof(scrollerActivityStatus)==='undefined') scrollerActivityStatus = false;

  this.scroller_activity_status = scrollerActivityStatus

  this.docBody = document.body;
  this.docElement = document.documentElement;

  if (isNotLoggedIn) {
    this.scroller();
  }
}

GFBSubscribe.prototype.scroller = function() {
      var _this = this;
      window.onscroll = function () {
        var height;

        if(_this.scroller_activity_status) {
          if(typeof document.height !== 'undefined') {
            height = document.height // For webkit browsers
          } else {
            height = Math.max( _this.docBody.scrollHeight, _this.docBody.offsetHeight, _this.docElement.clientHeight, _this.docElement.scrollHeight, _this.docElement.offsetHeight);
          }

          if(window.pageYOffset > (height/6)){
            if(_this.overlay_display_status == 'hidden' ){
              _this.overlay('onscroll');
            }
          }
        }
        _this.scrollTracker();
      }
  }

  GFBSubscribe.prototype.scrollTracker = function() {
    var verticalPosition = 0,
    formEl = document.getElementById("gfb_newsletter_signup_form")

    if (window.pageYOffset){
      verticalPosition = window.pageYOffset;
    } else if(this.docElement.clientHeight){//ie
      verticalPosition = this.docElement.scrollTop;
    } else if(this.docBody){//ie quirks
      verticalPosition = this.docBody.scrollTop;
    }

    if (this.eventSource == 'onscroll' && window.screen.width >= 1000){
      formEl.style.top = (verticalPosition + (formEl.offsetHeight/7)) + 'px';
    } else {
      formEl.style.top = (verticalPosition - 150) + 'px';
    }
  }

GFBSubscribe.prototype.LayoutManager = function(el) {
  var formEl      = document.getElementById('gfb_form_box'),
      screenWidth = window.screen.width

  if (this.eventSource == 'onscroll' && screenWidth >= 1000) {
    el.style.boxShadow = 'none';
    el.style.backgroundColor = 'transparent';
    el.style.left = (screenWidth/2) + 'px';
    formEl.style.width = '100%';
    document.getElementById('gfb_ebook_img').style.display = 'none';
    document.getElementById('gfb_form_box').style.maxWidth = '450px';
  } else {
    el.style.boxShadow = '0 0 0 9999px rgba(0, 0, 0, 0.5)';
    el.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    formEl.style.width = '70%';
    el.style.left = 0;
    document.getElementById('gfb_ebook_img').style.display = 'block';
    document.getElementById('gfb_form_box').style.maxWidth = '750px';
  }
}

var gfb_subscribe = new GFBSubscribe();
