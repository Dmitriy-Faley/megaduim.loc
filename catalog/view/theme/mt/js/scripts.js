$(document).ready(function () {
    
    //Общий подсчет количества товаров
    


    //инициализация остальных функций
    add_bookmarks();
    add_bookmarks_login();
    add_compare();
    $(window).resize();
    acc_window();
    basket_mobile();
    closeMenus();
   
    $('.close_search').click(function() {
        if ($('.mobile_search').hasClass('open')) $('.mobile_search').removeClass('open');
        else $('.mobile_search').addClass('open');
    });

    // выпадающее меню
    /* document.querySelector('.menu__body').addEventListener("click", (e) => {
        const targetElement = e.target;
        const parentElement = targetElement.parentElement;
        const subMenuList = parentElement.querySelector('.menu__sub-list');
        if(targetElement.classList.contains('menu__button-sub-open')) {
            if (parentElement && subMenuList) {
                closeAllSubMenu(subMenuList);
                subMenuList.classList.toggle('_open');
                parentElement.classList.toggle('_hover');
            }
        }
    }); */

    // каталог выпадающее меню 
    /* document.querySelector('.catalog_but-box').addEventListener("click", (e) => {
        const targetElement = e.target;
        const parentElement = targetElement.parentElement;
        const subMenuList = parentElement.querySelector('.menu__sub-list');
        if(targetElement.classList.contains('menu__button-sub-open')) {
            if (parentElement && subMenuList) {
                subMenuList.classList.toggle('_open');
                parentElement.classList.toggle('_hover');
            }
        }
    }) */
       

/* document.querySelector('.menu').onmouseleave = closeAllSubMenu; */

// закрытие открытых подменю 
function closeAllSubMenu(current = null) {
    let parents = [];
    if (current) {
        let currentParent = current.parentNode;
        while(currentParent) {
            if(currentParent.classList.contains('menu__list')) break;
            if(currentParent.nodeName === 'UL') parents.push(currentParent);
            currentParent = currentParent.parentNode;
        }
    }
    const subMenu = document.querySelectorAll('.menu__sub-list');
    // проверка родителей
    subMenu.forEach(item => {
        if(item != current && !parents.includes(item)) {
            item.classList.remove('_open');
            item.parentElement.classList.remove('_hover');
        }
    });
}

// подменю
function SubMenu() {
    const windowWidth = document.documentElement.clientWidth;
    let subMenu = document.querySelectorAll('.menu__sub-list');
        subMenu.forEach(function(item, i, subMenu) {
            let left = item.getBoundingClientRect().left;
            let width = item.clientWidth;
            let check = left + width;
    
            if( windowWidth < check ) {;
                item.classList.add('_left');
            };
        });
}
SubMenu();


// добавление кнопок в подменю (стрелка вправо)
let listUl = document.querySelectorAll('.menu__sub-list');
listUl.forEach(function(item, i, listUl) {
    if (item) {
        item.parentElement.insertAdjacentHTML("beforeend", '<div class="menu__button-sub-open fas fa-chevron-right"></div>');
    };
});



   
    //кнопка каталога 
    $('.catalog_but').click(function (e) {
        e.preventDefault();
        $(this).next('.catalog_menu').toggleClass('open');

        closeMenus(e);
    });


// корзина
// парсинг значений указанной цены и добавление в data-price
let cards = $('.cart_card')
for (let i = 0; i < cards.length; i++) {
  const cd = cards[i];
  let cd_prices = $(cd).find('text.product_price').text();
  $(cd).attr('data-price', cd_prices)
}



//функция калькуляции суммы
function change($tr, val) {
  let $input = $tr.find('.quantity'); // поле с кол-вом
  let count = parseInt($input.val()) + val; // значение кол-ва
  count = count < 1 ? 1 : count;
  $input.val(count);
  let $price = $tr.find('.product_price'); // поле с ценой
  let $priceOld = $tr.find('.product_priceOld'); // поле с ценой (скидка)
  let $sale = $tr.find('.sale_price'); // поле с ценой (скидка)

  let $price_dataset = $tr.data('product-price');
  let $sale_dataset = $tr.data('special');

  $price.text(count * $price_dataset);
  $priceOld.text(count * $price_dataset);
  $sale.text(count * $sale_dataset);

}

summ();
summSale();
;



//подсчет общей суммы
function summ() {
  let all_price = $('.cart_card').find('.product_price');
  let old_price = $('.cart_card').find('.product_priceOld');

  let sum = 0
  let end_prices = [];

  for (let e = 0; e < all_price.length; e++) {
      const elt = all_price[e];
      let t = parseInt($(elt).text());
      end_prices.push(t)
  }

  for (let e = 0; e < old_price.length; e++) {
        const elt = old_price[e];
        let t = parseInt($(elt).text());
        end_prices.push(t)
    }

  for (i = 0; i < end_prices.length; i++) {
      sum += end_prices[i]; 
  }

  let itog = $('.cart_total__list').find('text.cart_total__priceAll');
  $(itog).text(sum)
  // Подсчет общей стоимости
  $('.cart_total__list').find('text.cart_total__priceLast').text($('text.cart_total__priceAll').text() - $('text.cart_total__priceSale').text())
}

//подсчет общей скидки
function summSale() {

    let old_price = $('.cart_card').find('.product_priceOld');
    let sale_price = $('.cart_card').find('.sale_price');
  
    let sumNormal = 0
    let sumSale = 0
    let normal_prices = [];
    let sale_prices = [];
  
    for (let e = 0; e < old_price.length; e++) {
        const elt = old_price[e];
        let t = parseInt($(elt).text());
        normal_prices.push(t)
    }
    for (let e = 0; e < sale_price.length; e++) {
        const elt = sale_price[e];
        let t = parseInt($(elt).text());
        sale_prices.push(t)
    }
  
    for (i = 0; i < normal_prices.length; i++) {
        sumNormal += normal_prices[i]; 
    }

    for (i = 0; i < sale_prices.length; i++) {
        sumSale += sale_prices[i]; 
    }
  
    let itog = $('.cart_total__list').find('text.cart_total__priceSale');
    $(itog).text(sumNormal - sumSale);
    // Подсчет общей стоимости
    $('.cart_total__list').find('text.cart_total__priceLast').text($('text.cart_total__priceAll').text() - $('text.cart_total__priceSale').text())
}





function countQuantity() {
    let countQuantity = 0;
    $('.quantity').each(function() {
        countQuantity +=Number($(this).val())
    });
    $('.cart_total__textCount').text(countQuantity)
}

//уменьшение кол-ва товара
$('.cart_card__amount').each(function() {
    if ($(this).find('.quantity').val() == 1) {
        $(this).find('.minus').addClass('disabledBTN')
    } else {
        $(this).find('.minus').removeClass('disabledBTN')
    }
})

$(document).on('click', '.minus', function() {
    //change($(this).closest('.basket__product'), -1);
    change($(this).closest('.cart_card'), -1);
    summ();
    summSale();
    $('.trash-header').load('/index.php?route=mt/common/header/load-cart-count', function(e) {
        if (e == 0) {
            $('.trash-header').addClass('header-trash-count');
        }
    });
    countQuantity()
    
    if ($(this).parent().find('.quantity').val() < 2) {
        $(this).addClass('disabledBTN')
    } else {
        $(this).removeClass('disabledBTN')
    }
});

//увеличение кол-ва товара
$(document).on('click', '.plus', function() {
    //change($(this).closest('.basket__product'), 1);
    change($(this).closest('.cart_card'), 1);
    summ();
    summSale();
    $('.trash-header').load('/index.php?route=mt/common/header/load-cart-count', function(e) {
        if (e == 0) {
            $('.trash-header').addClass('header-trash-count');
        }
    });
    countQuantity()

    if ($(this).parent().find('.quantity').val() >= 2) {
        $(this).parent().find('.minus').removeClass('disabledBTN')
    }
});

$(document).on('click', '.delete_product', function() {
    var btn = this;
    fetch("/index.php?route=mt/common/header/remove-from-cart&id=" + $(this).data('id')).then(() => {
        $(btn).closest('.cart_card').remove();
        summ();
        summSale();
        
        $('.trash-header').load('/index.php?route=mt/common/header/load-cart-count', function(e) {
            if (e == 0) {
                $('.trash-header').addClass('header-trash-count');
            }
        });
        // $('#basket').find('.is-close').click();
        $('.my-trash').removeClass('fancybox');
        $('.my-trash').attr('href', '/index.php?route=checkout/checkout');
    });
  });

//изменение кол-ва при ручном вводе
$(document).on("input", '.quantity', function() {

  if (this.value < 0 ) {
      this.value = '1'
  }
  let $price = $(this).parent().parent().find('.cart_total__price').find("text.product_price");


 // let $price = $(this).parent().parent().find('.basket__product-price').find("text.product_price");
  let $price_dataset = $(this).parent().parent().parent().data('product-price');
  $price.text(this.value * $price_dataset);
  summ();
  summSale();
  ;
  $('.trash-count').load('/index.php?route=mt/common/header/load-cart-count');
  console.log($price_dataset)
});

$(document).on('click', '.add-to-cart, .product_page_info__btn-cart', function(){
    setTimeout(function () {
        $('.my-trash').addClass('fancybox');
        $('.trash-header').removeClass('header-trash-count');
        $('.my-trash').attr('href', '#basket');
        console.log('2222');
        $('#basket').load('/index.php?route=mt/common/header/load-cart', function() {
            summ();
            summSale();
            ;
            $('.trash-header').load('/index.php?route=mt/common/header/load-cart-count', function(e) {
                if (e == 0) {
                    $('.trash-header').addClass('header-trash-count');
                }
            });
        });
    }, 100);
});
$(document).on('click', '#button-cart', function(){
    setTimeout(function () {
        $('.my-trash').addClass('fancybox');
        $('.trash-header').removeClass('header-trash-count');
        $('.my-trash').attr('href', '#basket');
        $('#basket').load('/index.php?route=mt/common/header/load-cart', function() {
            summ();
            summSale();
            ;
            $('.trash-header').load('/index.php?route=mt/common/header/load-cart-count', function(e) {
                if (e == 0) {
                    $('.trash-header').addClass('header-trash-count');
                }
            });
        });
    }, 100);
});



    // поиск
    let search_block = $('.search-block');
    [].forEach.call(search_block, function (e) {
        let search_form = $(e).find('form.form-search')
        let search_form_input = $(search_form).find('input.form-search-input');
        $(search_form_input).click(function (e) {
            $(this).parent().parent().addClass('search-active');
            let class_active = $(this).parent().parent().hasClass('search-active');
            let class_block = $(this).parent().parent().find('.search__block-background');
            console.log(class_block)
            if (class_active) {
                $(class_block).click(function () {
                    $(this).parent().removeClass('search-active');
                    $(this).parent().parent().find('.search_vars').removeClass('open')
                })

            }
            $(window).scroll(function () {
                if (class_block) {
                    $(class_block).parent().removeClass('search-active');
                    $(class_block).parent().parent().find('.search_vars').removeClass('open')
                }
            })
        });

        $(search_form_input).keyup(function(){
            let val = $(this).val();
            $(this).parent().parent().find('.search_vars').addClass('open')
    
            if (val.length < 1) {
                $(this).parent().parent().find('.search_vars').removeClass('open')
            }
        });

        
    });




    // спойлеры

    let spoilers = $('.accordion-item');
    let spoiler_title = $(spoilers).find('.accordion__header')

    $(spoiler_title).click(function () {
        if ($(spoilers).hasClass('open')) {
            $(spoilers).not($(this).parent()).removeClass('open')
            $(spoilers).not($(this).parent()).find('.accordion-content').css('max-height', '');
        }
        $(this).parent().toggleClass('open')

        if ($(this).parent().hasClass('open')) {
            $(this).parent().find('.accordion-content').css('max-height', $('.accordion-content').prop('scrollHeight') + 'px')
        } else {
            $(this).parent().find('.accordion-content').css('max-height', '');
        }
      
    })

    // // раскрывающийся текст 
    // let more_text = $(".more-text");
    // [].forEach.call(more_text, function (e) {
    //     let more_button = $(e).find("button.more-text_open");
    //     let get_content = $(e).find('.more-text_content').children();
    //     let get_overflow = $(e).find('.more-text_content').attr("data-overflow");
    //     let get_height = $(get_content).height();

    //     const def_button_text = $(more_button).text();

    //     $(get_content).css('max-height', get_overflow + 'px');

    //     const fix_height = get_overflow;

    //     $(more_button).click(function (e) {
    //         let this_content = $(e.currentTarget).parent().find('.more-text_content').children();
    //         $(this_content).parent().toggleClass('open');
    //         $(this_content).parent().hasClass('open') ? ($(this_content).css('max-height', $(this_content).prop('scrollHeight') + 'px')) & $(more_button).text('Свернуть') : $(this_content).css('max-height', fix_height + 'px') & $(more_button).text(def_button_text)
    //     })
    // });

    //количество товаров
    $(".product_page_counter").find(".number-amount").val('1')
    $(".product_page_counter").children(".product_page_counter__btn--plus").click(function () {
        var $price = $(this).parent().find(".number-amount");
        $price.val(parseInt($price.val()) + 1);
        
        $price.change();
    });
    $(".product_page_counter").children(".product_page_counter__btn--minus").click(function () {
        var $price = $(this).parent().find(".number-amount");
        if ($price.val() != 1) {
            $price.val(parseInt($price.val()) - 1);
            $price.change();
        } 
    });

     //табы 
     let tab = function () {
        let tabNav = document.querySelectorAll('.tabs-nav__item'),
            tabContent = document.querySelectorAll('.tab'),
            tabName;
    
            tabNav.forEach(item => {
                item.addEventListener('click', selectTabNav)
            });
    
            function selectTabNav() {
                tabNav.forEach(item => {
                    item.classList.remove('is-active');
                });
                this.classList.add('is-active');
                tabName = this.getAttribute('data-tab-name');
                selectTabContent(tabName);
            }
    
            function selectTabContent(tabName) {
            tabContent.forEach(item => {
                item.classList.contains(tabName) ? item.classList.add('is-active') : item.classList.remove('is-active');
            });

        }
        };
        tab();


    //спойлеры меню в подвале на моб. версии
    $('.footer__menu-title').click(function () {
        if ($('.footer__menu').hasClass('opened')) {
            $('.footer__menu-title').not($(this)).removeClass('active');
            $('.footer__menu').not($(this).next()).slideUp(300);
        }
        $(this).toggleClass('active').next().slideToggle(300);
        $(this).next().toggleClass("opened")
    });


    //окошко выбора валюты
    $(".site__currency").click(function () {
        $(this).children(".select__currency").toggleClass("open")
    })

    //спойлеры категорий
    $(".cat-title.cat-spoiler").click(function () {
        $(this).next('.others__item-list').slideToggle(200);
    })

    //пункты в мобильном меню
    let mobile_item = $('.mobile-menu-item');

    [].forEach.call(mobile_item, function (el) {
       el.addEventListener('click', (e) => {
            const target_item = e.target;
    
            if ($(target_item).hasClass('sub')) {
                $(target_item).children('.mobile-submenu').addClass('_open');
            }

        })
    });

    // кнопка назад в моб. меню
    $('.nav-mobile_back').click(function () {
        if ($('.mobile-menu').find('.mobile-submenu').hasClass('_open')) {
            $('.mobile-menu').find('.mobile-submenu._open:last').removeClass('_open')
        } else {
            $(this).parent().parent().removeClass('open');
            $('.main-wrapper').removeClass('backlight');
        }
    });

    // кнопка открытия моб меню
    $('.mob_catalog-button').click(function () {
        $('.nav-mobile').addClass('open');
        $('.main-wrapper').addClass('backlight');
    })

    // кнопка закрытия моб меню
    $('.nav-mobile_close').click(function () {
        if($('.nav-mobile').hasClass('open')) {
            $('.nav-mobile').removeClass('open');
            $('.main-wrapper').removeClass('backlight');
        }
      
    })

    //поиск на моб.
    $('.header__mobile-search').click(function () {
        $('.mobile_search').toggleClass('open')
    });

    //поле поиска на моб.
    $('.mobile_search-field').keyup(function(){
        var val = $(this).val();
        $(this).parent().addClass('in-input');
        $(this).parent().next('.search_vars').addClass('show');

        if (val.length < 1) {
            $(this).parent().removeClass('in-input');
            $(this).parent().next('.search_vars').removeClass('show');
        }
    });

    //очистка поля при нажатии на крестик в поиске
    $('.mobile_search-clear').click(function (e) {
        $('.mobile_search-field').parent().removeClass('in-input');
        $('.mobile_search-field').parent().next('.search_vars').removeClass('show');
    });

    // кнопка закрытия фильтров на моб.
    $("button.close-filters").click(function () {      
        let filters_menu = $(".category__content-leftside")
        if ($(filters_menu).hasClass("open")) {
			$('.col-sm-3').removeClass('open');
            $(filters_menu).removeClass('open');			
            $('.main-wrapper').removeClass("backlight")
        }
    })


    // окошко Мой аккаунт
    $('.my-account').click(function (e) {
        e.preventDefault();
        $(this).next('.account-menu').toggleClass('open');
        acc_window(); 
    });

    // кнопки вариантов сортировки
    $('.head_sorting-filterbut').click(function () {
		$('.col-sm-3').addClass('open');		
        $('.category__content-leftside').addClass('open');
        $('.main-wrapper').addClass('backlight');
    })


    // функция сортировки
    // mytoggle('.head_sorting-variant',function(e) {
    //     e.preventDefault();

    //     if ($('.head_sorting-variant').hasClass('sort_1') || $('.head_sorting-variant').hasClass('sort_2')) {
    //         $('.head_sorting-variant').removeClass('sort_1')
    //         $('.head_sorting-variant').removeClass('sort_2')
    //     }

    //     $('.head_sorting-variant').find(".sort-arr").remove();
    //     let sort_arrow = `<span class="sort-arr"><img src="assets/img/icons/sort-arr.svg"></span>`;
    //     $(this).addClass('sort_1')
    //     $(sort_arrow).appendTo($(this))
    //   }, 

    //   function(e) {
    //     e.preventDefault();

    //     if ($('.head_sorting-variant').hasClass('sort_1') || $('.head_sorting-variant').hasClass('sort_2')) {
    //         $('.head_sorting-variant').not($(this)).removeClass('sort_1')
    //         $('.head_sorting-variant').not($(this)).removeClass('sort_2')
    //     }

    //     if ($(this).hasClass('sort_1')) {
    //         $(this).removeClass('sort_1')
    //     }

    //     if ($(this).closest('.sort-arr')) {
    //         $(this).addClass('sort_2')
    //     } else {
    //         $(this).removeClass('sort_1')
    //     }
    // });

    //инициализация всплывающих окон (формы обратной связи, уведомления при отправке, и другие всплывашки (подвязаны через fancybox))
    /* Fancybox.bind('.fancybox', {
        type: "inline",
        dragToClose: false,
        closeButton: 'inside',       
    }); */
});

// кастомный селект 
// function customselect() {
//     var x, i, j, l, ll, selElmnt, a, b, c;

//     x = document.getElementsByClassName("custom-select");
//     l = x.length;
//     for (i = 0; i < l; i++) {
//         selElmnt = x[i].getElementsByTagName("select")[0];
//         ll = selElmnt.length;
//         a = document.createElement("DIV");
//         a.setAttribute("class", "select-selected");
//         a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
//         x[i].appendChild(a);
//         b = document.createElement("DIV");
//         b.setAttribute("class", "select-items select-hide");
//         for (j = 1; j < ll; j++) {
//             c = document.createElement("DIV");
//             c.innerHTML = selElmnt.options[j].innerHTML;
//             c.addEventListener("click", function (e) {
//                 var y, i, k, s, h, sl, yl;
//                 s = this.parentNode.parentNode.getElementsByTagName("select")[0];
//                 sl = s.length;
//                 h = this.parentNode.previousSibling;
//                 for (i = 0; i < sl; i++) {
//                     if (s.options[i].innerHTML == this.innerHTML) {
//                         s.selectedIndex = i;
//                         h.innerHTML = this.innerHTML;
//                         y = this.parentNode.getElementsByClassName("same-as-selected");
//                         yl = y.length;
//                         for (k = 0; k < yl; k++) {
//                             y[k].removeAttribute("class");
//                         }
//                         this.setAttribute("class", "same-as-selected");
//                         break;
//                     }
//                 }
//                 h.click();
//             });
//             b.appendChild(c);
//         }
//         x[i].appendChild(b);
//         a.addEventListener("click", function (e) {
//             e.stopPropagation();
//             closeAllSelect(this);
//             this.nextSibling.classList.toggle("select-hide");
//             this.classList.toggle("select-arrow-active");
//             const windowHeight = document.documentElement.clientHeight;

//             repair_popups(this.nextSibling)

//         });
//     }

//     function closeAllSelect(elmnt) {
//         var x, y, i, xl, yl, arrNo = [];
//         x = document.getElementsByClassName("select-items");
//         y = document.getElementsByClassName("select-selected");
//         xl = x.length;
//         yl = y.length;
//         for (i = 0; i < yl; i++) {
//             if (elmnt == y[i]) {
//                 arrNo.push(i)
//             } else {
//                 y[i].classList.remove("select-arrow-active");
//             }
//         }
//         for (i = 0; i < xl; i++) {
//             if (arrNo.indexOf(i)) {
//                 x[i].classList.add("select-hide");
//             }
//         }
//     }

//     document.addEventListener("click", closeAllSelect);
// }
// customselect()

// логика сортировки
function mytoggle() {
    var funs = [].slice.call(arguments, 1);
    var elems = [].slice.call(document.querySelectorAll(arguments[0]));
    elems.forEach(function(item) {
        item.addEventListener("click", function() {
            var c = 0;
            return function() {
                funs[c++ % funs.length].apply(item, arguments)
            }
        }())
    })
};

function compare_remove(product_id) {
    $.ajax({
        url: 'index.php?route=product/compare/remove',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json) {
            if ($("#compare_deleted").hasClass('notify_action')) {
                $('#compare_deleted').removeClass('notify_action');
            }
            $(document).find('#compare_deleted').addClass('notify_action').css('top', 90 + 'px'); 
            active_notify();
            $('.wishlist_compare_header').text(json.count);
            if (json.count == 0) {
                $('.wishlist_compare_header').addClass('header-trash-count');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
function compare_add(product_id) {
    $.ajax({
        url: 'index.php?route=product/compare/add',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json) {
            if ($("#compare_added").hasClass('notify_action')) {
                $('#compare_added').removeClass('notify_action');
            }
            $(document).find('#compare_added').addClass('notify_action').css('top', 90 + 'px'); 
            active_notify();
            $('.wishlist_compare_header').text(json.count);
            $('.wishlist_compare_header').removeClass('header-trash-count');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
function wishlist_remove(product_id) {
    $.ajax({
        url: 'index.php?route=account/wishlist/remove',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json) {
            if ($("#bookmark_deleted").hasClass('notify_action')) {
                $('#bookmark_deleted').removeClass('notify_action');
                
            }
            $(document).find('#bookmark_deleted').addClass('notify_action').css('top', 90 + 'px'); 
            active_notify();
            $('.wishlist_compare_header').text('Избранное (' + json.count + ')');
            if (json.count == 0) {
                $('.wishlist_compare_header').addClass('header-trash-count');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
function wishlist_add(product_id) {
    $.ajax({
        url: 'index.php?route=account/wishlist/add',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json) {
            if ($("#bookmark_added").hasClass('notify_action')) {
                $('#bookmark_added').removeClass('notify_action');
            }
            $(document).find('#bookmark_added').addClass('notify_action').css('top', 90 + 'px'); 
            active_notify();
            $('.wishlist_compare_header').text('Избранное (' + json.count + ')');
            $('.wishlist_compare_header').removeClass('header-trash-count');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// добавление/удаление в закладки (+ уведомление об этом)
function add_bookmarks() {
    let like_btn = $('.add-bookmark');
    [].forEach.call(like_btn, function (el) {
        el.addEventListener('click', (e) => {
            const target_like = e.target;
            e.preventDefault();
            $(target_like).toggleClass('added');
        
            if ($(target_like).hasClass('added')) {


                if ($("#bookmark_deleted").hasClass('notify_action')) {
                    $('#bookmark_deleted').removeClass('notify_action');
                }

                $(document).find('#bookmark_added').addClass('notify_action').css('top', 90 + 'px');

            } else if (!$(target_like).hasClass('added')) {
                console.log('Товар удален из избранных');

                
                if ($("#bookmark_added").hasClass('notify_action')) {
                    $('#bookmark_added').removeClass('notify_action');
                }

                $(document).find('#bookmark_deleted').addClass('notify_action').css('top', 90 + 'px');           
            }
            active_notify();
        });
    })
}


// уведомление об входе в аккаунт
function add_bookmarks_login() {
    let like_btn = $('.add-bookmark-login');
    [].forEach.call(like_btn, function (el) {
        el.addEventListener('click', (e) => {
            e.preventDefault();

            $(document).find('#bookmark_login').addClass('notify_action').css('top', 90 + 'px');
            
            active_notify();
        });
    })
}


// добавление/удаление в сравнение (+ уведомление об этом)
function add_compare() {
    let compare_btn = $('.add-compare');

    [].forEach.call(compare_btn, function (el) {
        el.addEventListener('click', (e) => {
            const this_compare = e.target;
            e.preventDefault();

            $(this_compare).toggleClass('added');


            if ($(this_compare).hasClass('added')) {

                if ($("#compare_deleted").hasClass('notify_action')) {
                    $('#compare_deleted').removeClass('notify_action');
                }

                $(document).find('#compare_added').addClass('notify_action').css('top', 90 + 'px');

            } else if (!$(this_compare).hasClass('added')) {
               
                if ($("#compare_added").hasClass('notify_action')) {
                    $('#compare_added').removeClass('notify_action');
                }

                $(document).find('#compare_deleted').addClass('notify_action').css('top', 90 + 'px');           
            }

            active_notify();
        })
    })
};

// логика уведомлений при добавлении в закладки или в сравнение
function active_notify() {
    if ($('.notify_window').hasClass('notify_action')) {   
        setTimeout(function () {
            $('.notify_window').removeClass('notify_action');
        },5000);
    }
}

// закрытие уведомлений по крестику
$(".notify_close").click(function (e) {
    if ($(this).parent().parent().hasClass("notify_action")) {
        $(this).parent().parent().removeClass("notify_action");
    }
    clearTimeout();
})

//закрытие всех каких-либо открытых меню при клике на фон
function closeMenus() {
    $(document).mouseup(function(e){
        var div = $(".popup");

        if ( !div.is(e.target) && div.has(e.target).length === 0 ) {
            div.removeClass('open');
			$('.col-sm-3').removeClass('open');
            $('.main-wrapper').removeClass('backlight');
        }

    });
};

//прослушиватель, функции которые должны выполняться при изменении размеров окна 
$(window).resize(function (){
    basket_mobile();
    acc_window();
})

// Активное меню
$(function($){
	var url = document.location.href;
	var pos= url.indexOf("#");
	if (pos > 0) {
		url = url.substring(0, pos);
	}
	$.each($('.menu__link'), function(index, value) {
		if (url.indexOf($(this).attr('href')) + 1) {
			$(this).addClass('active');
			//$(this).addClass('activemenu').parent().addClass('activemenu');
		}
	});

});

// переключение модели корзины в зависимости от разрешения
function basket_mobile() {
    let basket = $('.basket');
    if ($(window).width() < 1024) {
        $(basket).addClass('mobile')
    } 
    else if ($(window).width() > 1024) {
        $(basket).removeClass('mobile')
    }
}

// окно Мой аккаунт (на моб уст.вах)
function acc_window() {
    let viewport_width = document.documentElement.clientWidth;

    let acc_menu = $('.account-menu'); 
    [].forEach.call(acc_menu, function (acc_m) {
        let left = acc_m.getBoundingClientRect().left;
        let width = acc_m.clientWidth;
        let check = left + width;
        if (viewport_width < check) {
            $(acc_m).addClass('_left');
        };
    })  
}


function modalProduct() {
    //$('.modal_card__slider_wrap').find('img').remove();

    $('.hits_slide__pic, .hits_slide__left').click(function() {
        //Забираем клики добавить в корзину и вишлист
        $('.product_page_info__btn-cart').attr('onclick', $(this).parent().find('.add-to-cart').attr('onclick'));
        $('.product_page_info__btn-wish').attr('onclick', $(this).parent().find('.add-wish').attr('onclick'));
        //Имя товара
        $('.product_page_info__title').text($(this).parent().find('.hits_slide__text-name').text());
        //Цены со скидкой и без
        $('.product_page_info__price.normal text').text($(this).parent().find('.hits_slide__text.normal text').text());
        $('.product_page_info__price.old text').text($(this).parent().find('.hits_slide__text.old text').text());
        //Линк на страницу
        $('.modal_card__btn').attr('href', $(this).parent().find('.hits_slide__href').attr('href'));
        //Описание товара
        $('.product_page_info__text-descr').text($(this).parent().find('.hits_slide__descr').text());
        //В наличии или нет
        $('.product_page_info__text-stock').text($(this).parent().find('.hits_slide__stock').text());
        // Отображение значка добавить в корзину
        console.log($(this).parent().find('.add-to-cart').length)
        if($(this).parent().find('.add-to-cart').length) {
            $('.product_page_info__btn-cart').css('display', "flex")
        } else {
            $('.product_page_info__btn-cart').css('display', "none")
        }


        // .replace(/[^0-9\.]/g, '')
        // Опции (Тут передаем все опции в модалку)
        //Переменные с данными
        let width = $(this).parent().find('.attr__size-category.shir p').text().replace(/[^0-9\.]/g, '');
        let length = $(this).parent().find('.attr__size-category.leng p').text().replace(/[^0-9\.]/g, '');
        let height = $(this).parent().find('.attr__size-category.heig-lin p').text().replace(/[^0-9\.]/g, '');
        let widthLince = $(this).parent().find('.attr__size-category.shir-lin p').text().replace(/[^0-9\.]/g, '');
        let bridge = $(this).parent().find('.attr__size-category.most-len p').text().replace(/[^0-9\.]/g, '');
        // Вставляем текста в модалку
        $('.p-width').find('.product_page_size_block__text text').text(width);
        $('.p-length').find('.product_page_size_block__text text').text(length);
        $('.p-height').find('.product_page_size_block__text text').text(height);
        $('.p-widthLince').find('.product_page_size_block__text text').text(widthLince);
        $('.p-bridge').find('.product_page_size_block__text text').text(bridge);

        if($(this).parent().find('.hits_slide_option_param').length) {
            $('.product_page_info__block_option').css('display', 'block');
            $('.product_page_info__colors').find('.product_page_info__colors_btn').remove();
            $(this).parent().find('.hits_slide_option_param').each(function() {
                $('.product_page_info__colors').append(`
                    <label class="product_page_info__colors_btn">
                        <input class="radio" type="radio" name="color" value="" hidden="">
                        <a href="${$(this).find('a').attr('href')}" class="text">${$(this).find('span').text().trim()}</a>
                    </label>
                `)
            })
        } else {
            $('.product_page_info__block_option').css('display', 'none');
            $('.product_page_info__colors').find('.product_page_info__colors_btn').remove();
        }
        

        //Если такого пункта нет, не выводим, если есть выводим (Габариты очков)
        if(width != '') {
            $('.modalSize').find('.p-width').css('display', 'flex');
        } else {
            $('.modalSize').find('.p-width').css('display', 'none');
        }
        if(length != '') {
            $('.modalSize').find('.p-length').css('display', 'flex');
        } else {
            $('.modalSize').find('.p-length').css('display', 'none');
        }
        if(height != '') {
            $('.modalSize').find('.p-height').css('display', 'flex');
        } else {
            $('.modalSize').find('.p-height').css('display', 'none');
        }
        if(widthLince != '') {
            $('.modalSize').find('.p-widthLince').css('display', 'flex');
        } else {
            $('.modalSize').find('.p-widthLince').css('display', 'none');
        }
        if(bridge != '') {
            $('.modalSize').find('.p-bridge').css('display', 'flex');
        } else {
            $('.modalSize').find('.p-bridge').css('display', 'none');
        }
        if(width != '' || length != '' || height != '' || widthLince != '' || bridge != '') {
            $('.modalSize').css('display', 'block');
        } else {
            $('.modalSize').css('display', 'none');
        }
        /*//-----------//
        $('.modalChar')
        $(this).parent().find('.attr__size-category.most-len p').text().replace(/[^0-9\.]/g, '');*/
        $('.modalChar').find('.product_page_info_dropdown__content .product_page_characteristics_block').remove();

        if($(this).parent().find('.attrs__char .attr__char-category').length) {
            $('.modalChar').css('display', 'block');
        } else {
            $('.modalChar').css('display', 'none');
        }
        
        $(this).parent().find('.attrs__char .attr__char-category').each(function() {
            $(this).find('.attr__char-name')
            $('.modalChar').find('.product_page_info_dropdown__content').append(`
                <div class="product_page_characteristics_block">
                  <p class="product_page_characteristics_block__text">
                    ${$(this).find('.attr__char-name').text()}
                  </p>
                  <p class="product_page_characteristics_block__text">
                    ${$(this).find('.attr__char-cal').text()}
                  </p>
                </div>
            `)
        })

        //Покраска надписи в наличии или нет
        $('.product_page_info__text-stock').removeClass('green');
        $('.product_page_info__text-stock').removeClass('red');
        $('.product_page_info__text-stock').removeClass('blue');
        $('.product_page_info__text-stock').addClass($(this).parent().find('.hits_slide__stock').attr('data-color'))       
        
        $('.modal_card__slider_wrap').find('img').remove();
        $('.modal_card .hvr__sectors').find('.hvr__sector').remove();
        $('.modal_card .hvr__dots').find('.hvr__dot').remove();


        if($(this).parent().find('.hits_slide__pic_wrap img').length) {
            $(this).parent().find('.hits_slide__pic_wrap img').each(function() {
                let img = $(this).attr('data-src')
                $('.modal_card__slider_wrap').append(`<img class="image entered" src="${img}" alt="">`);
                $('.modal_card .hvr__sectors').append(`<div class="hvr__sector"></div>`);
                $('.modal_card .hvr__dots').append(`<div class="hvr__dot"></div>`);
            });
        } else {
            $(this).parent().parent().find('.hits_slide__pic_wrap img').each(function() {
                let img = $(this).attr('data-src')
                $('.modal_card__slider_wrap').append(`<img class="image entered" src="${img}" alt="">`);
                $('.modal_card .hvr__sectors').append(`<div class="hvr__sector"></div>`);
                $('.modal_card .hvr__dots').append(`<div class="hvr__dot"></div>`);
            });
        }

        

        $('.modal_card__slider_wrap').find('img').css("display", "none");
        $('.modal_card__slider_wrap').find('img:first-child').css("display", "block");


        if($(this).parent().find('.hits_slide__text.old text').text() == '') {
            $('.product_page_info__price.old').css('display', 'none');
        } else {
            $('.product_page_info__price.old').css('display', 'inline');
        }
        if($(this).parent().find('.add-wish').hasClass('added')) {
            $('.product_page_info__btn-wish').addClass('added');
        } else {
            $('.product_page_info__btn-wish').removeClass('added');
        }
        if($(this).parent().find('.add-to-cart').hasClass('added')) {
            $('.product_page_info__btn-cart').addClass('added');
            $('.product_page_info__btn-cart').find('.btn__text').text('В корзине');    
            setTimeout(() => {
                $('.product_page_info__btn-cart').find('.btn__text').text('Добавить в корзину');      
            }, 1000);        
        } else {
            $('.product_page_info__btn-cart').removeClass('added');
            $('.product_page_info__btn-cart').find('.btn__text').text('Добавить в корзину'); 
        }
        hoverDopImage();
    });
    function hoverDopImage() {
        $('.modal_card .hvr__sector').hover(function() {
            $('.modal_card .hvr__sector').removeClass('act');
            $(this).addClass('act');
            $('.modal_card .hvr__sector').each(function(i) {
                if($(this).hasClass('act')) {
                    if($('.modal_card .hvr__dot').eq(i) == i) {
                        $(this).parent().parent().find('.hvr__dot').addClass('hvr__dot--active');
                    }
                    $(this).parent().prev().find('img').css("display", "none");
                    $(this).parent().prev().find('img').eq(i).css("display", "block");
                }
            });
        })
    }
}
modalProduct();

function cartCheckbox() {
    let arr = [];
    // Кнопка выбрать все
    $('.checked-all').mouseup(function() {
        if($(this).find('input').prop('checked') == true) {
            $('.cart__left_content').find('.cart_card .catalog_filters_sort__label input').prop('checked', false);
            arr = [];
            $('.del-all').removeAttr('onclick')
        } else {
            $('.cart__left_content').find('.cart_card .catalog_filters_sort__label input').prop('checked', true);
            $('.cart__left_content').find('.cart_card .catalog_filters_sort__label').each(function() {
                arr.push($(this).parent().find('.cart__left_btn--red').attr('onclick'))
                $('.del-all').attr('onclick', arr.join(''));
            });
        }
    });
    
    //Определенные чекбоксы
    $('.cart__left_content .cart_card .catalog_filters_sort__label').each(function() {
        arr.push($(this).parent().find('.cart__left_btn--red').attr('onclick'))
        $('.del-all').attr('onclick', arr.join(''));

        let allCard = $('.cart__left_content .cart_card .catalog_filters_sort__label').length;
        $(this).click(function(element) {
            $('.del-all').removeAttr('onclick')
            arr = [];
            element.preventDefault();
            let length = 0;
            if($(this).find('input').prop('checked') == true) {
                $(this).find('input').prop('checked', false)
            } else {
                $(this).find('input').prop('checked', true)
            }
            $('.cart__left_content').find('.cart_card .catalog_filters_sort__label').each(function() {
                if($(this).find('input').prop('checked') == true) {
                    ++length;
                    arr.push($(this).parent().find('.cart__left_btn--red').attr('onclick'))
                    $('.del-all').attr('onclick', arr.join(''));
                } else {
                    --length;
                }
            });
            if(length < allCard) {
                $('.checked-all').find('input').prop('checked', false);
            } else {
                $('.checked-all').find('input').prop('checked', true);
            }
        });
        //$('.del-all').attr('onclick', + $(this).parent().find('.cart__left_btn').attr('onclick'))
    });
}
cartCheckbox();

function visualAddSettFunc() {
    $('.product_page_info__btn-cart').click(function() {
        if($(this).hasClass('added')) {
            $('.product_page_info__btn-cart').find('.btn__text').text('Добавить в корзину'); 
        } else {
            $(this).addClass('added');
            $('.product_page_info__btn-cart').find('.btn__text').text('В корзине');
            setTimeout(() => {
                $('.product_page_info__btn-cart').find('.btn__text').text('Добавить в корзину');  
                
                $(this).removeClass('added')    
            }, 1000);         
        }
    });

    $('.add-to-cart').click(function() {
        if($(this).hasClass('added')) {
        } else {
            $(this).addClass('added');
        }
    });
    $('.add-wish').click(function() {
        let str = [];
        setTimeout(() => {
            if($(this).hasClass('added')) {
                $(this).removeClass('added');
                $(this).attr('onclick').split(' ')[0].split('').map(function(item, index) {
                    if(index == 9 && item == 'r') {
                        item = 'a';
                    }
                    if(index == 10 && item == 'e') {
                        item = 'd';
                    }
                    if(index == 11 && item == 'm') {
                        item = 'd';
                    }
                    if(index == 12 && item == 'o' || index == 13 && item == 'v' || index == 14 && item == 'e' ) {
                        item = '';
                    }
                    str.push(item)
                });
                $(this).attr('onclick', str.join(''))
            } else {
                $(this).addClass('added');
                $(this).attr('onclick').split(' ')[0].split('').map(function(item, index) {
                    if(index == 9 && item == 'a') {
                        item = 'r'
                    }
                    if(index == 10 && item == 'd') {
                        item = 'e'
                    }
                    if(index == 11 && item == 'd') {
                        item = 'm'
                    }
                    str.push(item)
                });
                str.splice(12, 0, "o")
                str.splice(13, 0, "v")
                str.splice(14, 0, "e")
                $(this).attr('onclick', str.join(''))
            }          
        }, 100);
    });
    $('.product_page_info__btn-wish').click(function() {
        let str = [];
        let findBTN;
        setTimeout(() => {
            if($(this).hasClass('added')) {
                $(this).removeClass('added');
                $(this).attr('onclick').split(' ')[0].split('').map(function(item, index) {
                    if(index == 9 && item == 'r') {
                        item = 'a';
                    }
                    if(index == 10 && item == 'e') {
                        item = 'd';
                    }
                    if(index == 11 && item == 'm') {
                        item = 'd';
                    }
                    if(index == 12 && item == 'o' || index == 13 && item == 'v' || index == 14 && item == 'e' ) {
                        item = '';
                    }
                    str.push(item)
                });
                $(this).attr('onclick', str.join(''))
            } else {
                $(this).addClass('added');
                $(this).attr('onclick').split(' ')[0].split('').map(function(item, index) {
                    if(index == 9 && item == 'a') {
                        item = 'r'
                    }
                    if(index == 10 && item == 'd') {
                        item = 'e'
                    }
                    if(index == 11 && item == 'd') {
                        item = 'm'
                    }
                    str.push(item)
                });
                str.splice(12, 0, "o")
                str.splice(13, 0, "v")
                str.splice(14, 0, "e")
                $(this).attr('onclick', str.join(''))
            }
            setTimeout(() => {
                $('.hits_slide__text-name').each(function() {
                
                    if($('.product_page_info__btn-wish').parent().parent().find('.product_page_info__title').text() == $(this).text()) {
                        findBTN = $(this).parent().parent().find('.hits_slide__btn.add-wish');
                        console.log($('.product_page_info__btn-wish').parent().parent().find('.product_page_info__title').text() + '||' + $(this).text())
                    }
                })
                findBTN.each(function () {
                    $(this).attr('onclick', $('.product_page_info__btn-wish').attr('onclick'));
                    $(this).toggleClass('added');
                }) 
            }, 100);
        }, 100);
    });
    
   
}
visualAddSettFunc();

function indexCatalogBlock() {
    $(document).mousemove(function(){
        if($('.catalog_section_list__item:nth-child(1):hover').length != 0 || $('.catalog_section_list_inner:nth-child(2):hover').length != 0 ){
            $('.catalog_section_list_inner:nth-child(2)').addClass('is-open')
            $('.catalog_section__content').addClass('bg');
        } else if($('.catalog_section_list__item:nth-child(2):hover').length != 0 || $('.catalog_section_list_inner:nth-child(3):hover').length != 0 ){
            $('.catalog_section_list_inner:nth-child(3)').addClass('is-open')
            $('.catalog_section__content').addClass('bg');
        } else {
            $('.catalog_section_list_inner').removeClass('is-open')
            $('.catalog_section__content').removeClass('bg');
        }
   });
}
indexCatalogBlock();


/*function checkoutDeliveryCost() {
    console.log($('.checkout_total__btn'));
    
    console.log(123);
    $('.checkout_total__btn').click(function(){
        console.log(123)
        setTimeout(() => {
            $('#collapse-shipping-method').find('.checkout_option__block').each(function() {
                console.log($(this).find('input').prop('checked'))
            })
        }, 500);
    })
    $('.checkout_option__block').find('input')
}
checkoutDeliveryCost()*/