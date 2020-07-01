$(function() {
    if (window.location.hash) {
        var url = window.location.href.split('#')[0] + window.location.hash.substring(1);

        showModal(url, window.location.hash.substring(1));
    }

    $('#album .image').on('click', function(event) {
        showModal($(this).attr('href'), $(this).attr('title'));

        event.preventDefault();
    });

    $('.redux-modal-close').on('click', function(event) {
        hideModal();

        event.preventDefault();
    });

    $(document).keyup(function(e) {
        if (e.keyCode === 27) hideModal();
    });
});

function showModal(url, title) {
    window.location.hash = title;

    $('.redux-modal-image').attr('src', url);
    $('.redux-modal-caption').html(title);

    $('#redux-modal').removeClass('hidden');
}

function hideModal() {
    window.location.hash = '';

    $('#redux-modal').addClass('hidden');
}
