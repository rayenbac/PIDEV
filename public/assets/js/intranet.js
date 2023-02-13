var App = new Vue({
    el: '#steps',
    data: {
        selected: '',
        datatype: null
    },
    created: function() {},
    Updated: function() {},
    methods: {
        onChange(event) {
            setTimeout(function() {
                $('.datepicker-input').datepicker({ locale: 'fr', format: 'yyyy-mm-dd' });
            }, 0);
        }
    }
});


/*CKEDITOR.replace('mail');
$("form").submit(function (e) {
var messageLength = CKEDITOR.instances['mail'].getData().replace(/<[^>]*>/gi, '').length;
if (! messageLength) {
$(".error-messages").text("Vous dever remplir le message à envoyer!").fadeIn();
e.preventDefault();
}
});*/

var detail = document.getElementById("details");
var note = document.getElementById("notes");
var x = document.getElementById("cv");
var step = document.getElementById("steps");

function word() {
    url = window.location.href.split("#");
    if (url[1] = remarque) {
        notes();
    }
    if (url[1] = cv) {
        cv();
    }
    if (url[1] = demarche) {
        steps();
    }
}

function notes() {
    detail.style.display = "none";
    note.style.display = "block";
    x.style.display = "none";
    step.style.display = "none";

}

function action() {
    detail.style.display = "block";
    note.style.display = "none";
    x.style.display = "none";
    step.style.display = "none";

}

function cv() {
    x.style.display = "block";
    detail.style.display = "none";
    note.style.display = "none";
    step.style.display = "none";

}

function steps() {
    step.style.display = "block";
    detail.style.display = "none";
    note.style.display = "none";
    x.style.display = "none";

}

var App = new Vue({
    el: '#add-demande',
    data: {
        selected: '',
        datatype: null
    },
    created: function() {},
    Updated: function() {},
    methods: {
        onChange(event) {
            console.log('je suis la ')
            setTimeout(function() {
                $('.datepicker-input').datepicker({ locale: 'fr', format: 'yyyy-mm-dd' });
            }, 0);

        }
    }
});




var input = document.getElementById('customFile');
var infoArea = document.getElementById('file-upload-filename');
if (infoArea) {
    input.addEventListener('change', showFileName);
}

function showFileName(event) {

    // the change event gives us the input it occurred in 
    var input = event.srcElement;

    // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
    var fileName = input.files[0].name;

    // use fileName however fits your app best, i.e. add it into a div
    infoArea.textContent = 'Nom du fichier: ' + fileName;
}