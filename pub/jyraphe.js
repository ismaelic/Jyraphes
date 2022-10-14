var uploaded = document.getElementById('uploaded');
var input = document.getElementById('link');
input.addEventListener('keydown', function(e) {
    var allowedKeys = [67, 65, 76, 75, 84, 82]; // select all, copy and browser stuff.
    if(!(e.ctrlKey && allowedKeys.indexOf(e.keyCode) > -1)) {
        e.preventDefault();
    }
});
input.addEventListener('click', function(e) {
    this.select();
});

if(!filelink || filelink == '') {
    uploaded.style.display = 'none';
} else {
    input.focus();
    input.select();
}

function doNothing(e) {
    e.stopPropagation();
    e.preventDefault();
}

var dropbox = document.getElementById('dropbox');
dropbox.addEventListener("dragenter", doNothing, false);
dropbox.addEventListener("dragexit", doNothing, false);
dropbox.addEventListener("dragover", doNothing, false);
dropbox.addEventListener("drop", function(e) {
    e.stopPropagation();
    e.preventDefault();

    var files = e.dataTransfer.files;

    if(files.count <= 0) {
        return;
    }

    var file = files[0];
    var xhr = new XMLHttpRequest();
    if(xhr.upload) {
        xhr.open("POST", "/ajax/upload", true);
        xhr.setRequestHeader("X-File-Name", file.name);
        
        xhr.addEventListener("progress", function(event) {
            if (event.lengthComputable) {
                var percent = event.loaded / event.total;
                console.log(percent);
            }
        }, false);
        
        xhr.onload = function(e) {
            link = this.responseText;
            uploaded.style.display = 'block';
            input.value = link;
            input.focus();
            input.select();
        }
        
        xhr.send(file);
    }
}, false);
