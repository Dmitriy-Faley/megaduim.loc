$(document).ready(function () {

    $(document).on('click', '.add-to-cart', function(){
        setTimeout(function () {
            $('.my-trash').addClass('fancybox');
            $('.header-bottom__price-label').removeClass('header-trash-count');
            $('.my-trash').attr('href', '#basket');
            console.log('2222');
            $('#basket').load('/index.php?route=mt/common/header/load-cart', function() {
                summ();
                summSale();
                ;
                $('.header-bottom__price-label').load('/index.php?route=mt/common/header/load-cart-count', function(e) {
                    if (e == 0) {
                        $('.header-bottom__price-label').addClass('header-trash-count');
                    }
                });
            });
        }, 100);
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
    })

});