jQuery(document).ready(function () {
    $('button.btn-submit').on('click', function(){
        $($(this).data('for')).trigger('submit');
        return false;
    });
});
