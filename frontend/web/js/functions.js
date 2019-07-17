function copyToClipboard(copy) {
    var copytext = document.createElement('input');
    copytext.value = copy;
    document.body.appendChild(copytext);
    copytext.select();
    document.execCommand('copy');
    document.body.removeChild(copytext);
}

// Удаляем данные в окне чтобы данные всегда грузились заного
$(function() {
    $('body').on('hidden.bs.modal', '.modalBoxAjax', function () {
        $(this).removeData('bs.modal');
    });
});
