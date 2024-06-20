$(document).ready(function () {

    // логика уведомлений при добавлении в закладки или в сравнение
    function active_notify() {
        if ($('.notify_window').hasClass('notify_action')) {
            setTimeout(function () {
                $('.notify_window').removeClass('notify_action');
            }, 5000);
        }
    }

    // закрытие уведомлений по крестику
    $(".notify_close").click(function (e) {
        if ($(this).parent().parent().hasClass("notify_action")) {
            $(this).parent().parent().removeClass("notify_action");
        }
        clearTimeout();
    })

});