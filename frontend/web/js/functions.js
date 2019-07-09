function copyToClipboard(copy) {
    var copytext = document.createElement('input');
    copytext.value = copy;
    document.body.appendChild(copytext);
    copytext.select();
    document.execCommand('copy');
    document.body.removeChild(copytext);
}
