$(document).ready(function () {
    $(".sync-translate-from").syncTranslitFix({destination: "sync-translate-to"});
    $(".sync-translate-to").syncTranslitFix({destination: "sync-translate-to"});
    $('span.attached-button-chain').on('click', function () {
        if ($('.sync-translate-to').attr('readonly')) {
            $('.sync-translate-to').attr('readonly', false);
        } else {
            $('.sync-translate-to').attr('readonly', true);
        }
    });
});