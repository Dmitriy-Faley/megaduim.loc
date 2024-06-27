$(document).ready(function () {

    //количество товаров
    $(".card-product__total").find('.card-product__count').find(".card-product__count-num").val('1')
    $(".card-product__total").find('.card-product__count').children(".amount-plus").click(function () {
        var $price = $(this).parent().find(".card-product__count-num");
        $price.val(parseInt($price.val()) + 1);
        $price.change();
    });
    $(".card-product__total").find('.card-product__count').children(".amount-minus").click(function () {
        var $price = $(this).parent().find(".card-product__count-num");
        if ($price.val() != 1) {
            $price.val(parseInt($price.val()) - 1);
            $price.change();
        }
    });

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
    });

});