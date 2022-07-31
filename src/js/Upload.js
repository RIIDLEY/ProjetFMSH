function func() {
    if (document.getElementById('selectid').value == "Media") {
        document.getElementById('InputFile').removeAttribute("accept")
        document.getElementById("InputFile").setAttribute("accept", "video/*, audio/*");
    }
    if (document.getElementById('selectid').value == "Document") {
        document.getElementById('InputFile').removeAttribute("accept")
        document.getElementById("InputFile").setAttribute("accept", ".pdf,.txt");
    }
}

function getExtension(filename) {
    var parts = filename.split('.');
    return parts[parts.length - 1];
}

function isMedia(filename) {
    var ext = getExtension(filename);
    switch (ext.toLowerCase()) {
        case 'm4v':
        case 'avi':
        case 'mpg':
        case 'mp4':
        case 'mkv':
        case 'mp3':
        case 'wav':
        case 'wma':
        case 'aac':
        case 'flac':
        case 'mov':
            return true;
    }
    return false;
}

function isDocument(filename) {
    var ext = getExtension(filename);
    switch (ext.toLowerCase()) {
        case 'txt':
        case 'pdf':
            return true;
    }
    return false;
}

$(function() {
    $('form').submit(function() {
        function failValidation(msg) {
            alert(msg);
            return false;
        }
        var selectType = document.getElementById('selectid').value;
        var file = $('#InputFile');
        if ( selectType == "Media" && !isMedia(file.val())) {
            return failValidation('Veuillez selectionner un fichier audio ou vidéo.');
        }else if (selectType == "Document" && !isDocument(file.val())) {
            return failValidation('Veuillez selectionner le bon type de fichier. Type de document autorisé : .txt');
        }
    });

});