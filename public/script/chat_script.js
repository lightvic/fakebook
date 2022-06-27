let section = document.querySelector("#section_message");
// start reading info witch scroll down true at openning pages
document.addEventListener("DOMContentLoaded",() => {
    refreshState(true);
});
// function to scroll down
function scrollDown() {
    section.scrollTop = section.scrollHeight;
}

// call every 2000ms reading info
setInterval(() => {
    refreshState(false);
}, 2000);

// read every message from chat2 (php request)
// create div and span to print messages with message's user's name
function refreshState(scroll) {
    fetch("/chat2")
        .then(res => res.json())
        .then(data => {
            section.innerHTML = "";
            var name = "";
            var color = "";
            var margin = "";
            let user = data.user;
            let members = data.members;
            data.messages.forEach(element => {
                if(element.user_id == user.user_id) {
                    name = user.first_name + " " + user.last_name;
                    color = "#3CFEAF";
                    margin = "500px";
                } else {
                    members.forEach(membrs => {
                        if(membrs.user_id === element.user_id) {
                            name = membrs.first_name + " " + membrs.last_name;
                            color = "#99E7FF";
                            margin = "0px";
                        }
                    })
                }
                div = document.createElement("div");
                div.style.margin = "10px";
                div.style.marginLeft = margin;

                
                section.appendChild(div);
                span = document.createElement("span");
                span.style.border = "1px solid black";
                span.style.padding = "5px";
                span.style.borderRadius = "5px";
                span.style.backgroundColor = color;
                span.innerHTML = name + ": " + element.content;
                div.appendChild(span);
            });
            if(scroll) {
                scrollDown()
            }
        })
    ;
}

// send form info to new_message (php request)
document.querySelector("#new_message_form").addEventListener("submit", function (event) {
    event.preventDefault()
    fetch("/new_message", {
        method: "POST",
        body: new FormData(this)
    }) .then(() => {
        document.querySelector("#new_message").value = "";
        refreshState(true)
    })
});


let openChangeImgBtn = document.querySelector("#change_chat_img_btn");
let changeChatImg = document.querySelector("#change_chat_img");

let isVisibleChangeImg = false;
// open form to change chat's img
function changeImgBtn() {
    isVisibleChangeImg = !isVisibleChangeImg;
    changeChatImg.style.display = isVisibleChangeImg ? "block" : "none";
}
openChangeImgBtn.addEventListener("click", changeImgBtn);

let cancel = document.querySelector("#cancel");
// cancel open form
function cancelChangeImg() {
    changeChatImg.reset();
    var p=document.querySelector("#preview");
    p.innerHTML="";
    p.style.display="none";
    changeImgBtn();
}
cancel.addEventListener("click", cancelChangeImg);


var new_image_input = document.querySelector("#fileToUpload");
var depose = document.querySelector("#depose");

// drag'n drop
depose.addEventListener("click", function(evt) {
    evt.preventDefault();
    new_image_input.click();
    new_image_input.addEventListener("change", openVignette);
  });
  
  depose.addEventListener("dragover", function(evt) {
    evt.preventDefault();
  });
  depose.addEventListener("dragenter", function() {
    this.className="onDropZone";
  });
  depose.addEventListener("dragleave", function() {
    this.className="";
  }); 
  depose.addEventListener("drop", function(evt) {
    evt.preventDefault();
    new_image_input.files=evt.dataTransfer.files;
    this.className="";
    openVignette()
  });
  
  // print vignette to see picture we want to download
  function openVignette() {
    var p=document.querySelector("#preview");
    p.innerHTML="";
    for (var i=0; i<new_image_input.files.length; i++) {
      var f=new_image_input.files[i];
      var div=document.createElement("div");
      div.className="fichier";
      var vignette=document.createElement("img");
      vignette.src = window.URL.createObjectURL(f);
      div.appendChild(vignette);
      p.appendChild(div);
    }
    p.style.display="block"; 
  };



