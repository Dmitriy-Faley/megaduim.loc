/*** 


$('#mselect-all').on('click', function () {
    $('.new').toggleClass('noactive');
});***/



(() => {
    // webpackBootstrap
    /******/ var __webpack_modules__ = {
        /***/ "./scripts/components/actions.js":
            /*!***************************************!*\
  !*** ./scripts/components/actions.js ***!
  \***************************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ toggleActions: () => /* binding */ toggleActions,
                    /* harmony export */
                });
                /* harmony import */ var _storage__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(
                    /*! ../storage */ "./scripts/storage.js"
                );
                /* harmony import */ var _products__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(
                    /*! ./products */ "./scripts/components/products.js"
                );
                /* harmony import */ var _total__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(
                    /*! ./total */ "./scripts/components/total.js"
                );

                const actions = document.querySelector(".mtcheck-products__actions");
                const removeAll = actions.querySelector("#remove-all");
                const actionsActiveClassName = "mtcheck-products__actions--active";

                function toggleActions() {
                    const selectedProducts = document.querySelectorAll(".mtcheck-product__left .checkbox__input:checked");
					$('.new').toggleClass('noactive');

                    if (!selectedProducts.length) {
                        return actions.classList.remove(actionsActiveClassName);
                    }

                    actions.classList.add(actionsActiveClassName);
					 
                }

                removeAll.addEventListener("click", () => {
                    const checkboxes = document.querySelectorAll(".mtcheck-product__left .checkbox__input:checked");

                    checkboxes.forEach((checkbox) => {
                        const productNode = checkbox.closest(".mtcheck-product");
                        const id = productNode.dataset.id;
                        fetch("/index.php?route=extension/mtcheckout/checkout/remove-from-cart&id=" + id);
                        (0, _storage__WEBPACK_IMPORTED_MODULE_0__.removeProductFromStorage)(id);
                        (0, _products__WEBPACK_IMPORTED_MODULE_1__.removeProductFromPage)(productNode, id);
                    });

                    (0, _products__WEBPACK_IMPORTED_MODULE_1__.updateProductsSummary)();
                    (0, _total__WEBPACK_IMPORTED_MODULE_2__.updateTotalResults)();
                });

                /***/
            },

        /***/ "./scripts/components/counter.js":
            /*!***************************************!*\
  !*** ./scripts/components/counter.js ***!
  \***************************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ initializeCounter: () => /* binding */ initializeCounter,
                    /* harmony export */
                });
                /* harmony import */ var _products__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(
                    /*! ./products */ "./scripts/components/products.js"
                );
                /* harmony import */ var _price__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(
                    /*! ./price */ "./scripts/components/price.js"
                );
                /* harmony import */ var _total__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(
                    /*! ./total */ "./scripts/components/total.js"
                );

                function initializeCounter(counter) {
                    if (!counter) return;
                    const parent = counter.parentElement;

                    const display = counter.querySelector(".counter__display");
                    const increment = counter.querySelector(".counter__action--increment");
                    const decrement = counter.querySelector(".counter__action--decrement");

                    const maxValue = +display.dataset.max;

                    const types = {
                        "+": display.stepUp.bind(display),
                        "-": display.stepDown.bind(display),
                    };

                    const isEmpty = (elem) => {
                        if (!elem.value) {
                            elem.classList.add("input--danger");
                            (0, _utils__WEBPACK_IMPORTED_MODULE_0__.addWarning)(elem, "Обязательно для заполения");
                            return true;
                        } else {
                            elem.classList.remove("input--danger");
                            (0, _utils__WEBPACK_IMPORTED_MODULE_0__.removeWarning)(elem);
                            return false;
                        }
                    };
    
                    const isEqual = (password, elem) => {
                        if (password !== elem.value) {
                            elem.classList.add("input--danger");
                            (0, _utils__WEBPACK_IMPORTED_MODULE_0__.addWarning)(elem, "Пароли не совпадают");
                            return false;
                        } else {
                            elem.classList.remove("input--danger");
                            (0, _utils__WEBPACK_IMPORTED_MODULE_0__.removeWarning)(elem);
                            return true;
                        }
                    };
    
                    const validateData = (form) => {
                        let flag = true;
                        let password;
    
                        const inpupElems = form.querySelectorAll("input");
    
                        [...inpupElems].forEach((input) => {
                            if (
                                input.name === "middlename" ||
                                input.name === "address_2" ||
                                input.type === "checkbox" ||
                                input.dataset.required == 0
                            ) return;
                            if (isEmpty(input)) return (flag = false);
    
                            if (input.name === "password") password = input.value;
                            if (input.name === "password-again") {
                                if (!isEqual(password, input)) return (flag = false);
                            }
                        });
    
                        return flag;
                    };

                    display.addEventListener("input", (event) => {
                        const value = +event.target.value;

                        if (!value) {
                            return (display.value = 1);
                        }

                        isEnough(parent, maxValue, value);
                        // (0, _price__WEBPACK_IMPORTED_MODULE_1__.calculateProductPrice)(event.target.closest(".mtcheck-product"));
                        // (0, _products__WEBPACK_IMPORTED_MODULE_0__.updateProductsSummary)();

                        // (0, _total__WEBPACK_IMPORTED_MODULE_2__.updateTotalResults)();
                    });
                    [increment, decrement].forEach((button) =>
                        button.addEventListener("click", (event) => {
                            const type = event.target.value;

                            types[type]();
                            isEnough(parent, maxValue, +display.value);
                            // (0, _price__WEBPACK_IMPORTED_MODULE_1__.calculateProductPrice)(event.target.closest(".mtcheck-product"));
                            // (0, _products__WEBPACK_IMPORTED_MODULE_0__.updateProductsSummary)();

                            // (0, _total__WEBPACK_IMPORTED_MODULE_2__.updateTotalResults)();
                        })
                    );

                    $(document).on('click', '.ll_set_point', function() {
                        setTimeout(() => { 
                            $('input[name="shipping_method"]').val($('input[name="delivery-type"]:checked').data('title'));
                            $('input[name="shipping_code"]').val($('input[name="delivery-type"]:checked').data('code'));
                            $('input[name="shipping_cost"]').val($('input[name="delivery-type"]:checked').val());
                            $('input[name="shipping_tax_class_id"]').val($('input[name="delivery-type"]:checked').data('tax'));
                            // let validateResult;
                            // let formSelector;

                            // formSelector = ".mtcheck-address__add-form";
                            // if (document.querySelector(formSelector) == null) {
                            //     formSelector = ".mtcheck-register";
                            // }
                            // const form = document.querySelector(formSelector);

                            var products = [];
                            $('.mtcheck-product').each(function(){
                            if ($(this).find('.checkbox__input').is(':checked')) {
                                products.push($(this).data('productid'));
                            }
                            });
                            $('input[name=products]').val(products.join(','));
                            $('input[name="payment_method"]').val($('input[name="pay-type"]:checked').data('title'));
                            $('input[name="payment_code"]').val($('input[name="pay-type"]:checked').data('code'));
                            updateSummary(); 
                        }, 500);
                        
                    });
    
                    $(document).on('change', '.ll_change_point', function() {
                        setTimeout(() => { 
                            $('input[name="shipping_method"]').val($('input[name="delivery-type"]:checked').data('title'));
                            $('input[name="shipping_code"]').val($('input[name="delivery-type"]:checked').data('code'));
                            $('input[name="shipping_cost"]').val($('input[name="delivery-type"]:checked').val());
                            $('input[name="shipping_tax_class_id"]').val($('input[name="delivery-type"]:checked').data('tax'));
                            // let validateResult;
                            // let formSelector;

                            // formSelector = ".mtcheck-address__add-form";
                            // if (document.querySelector(formSelector) == null) {
                            //     formSelector = ".mtcheck-register";
                            // }
                            // const form = document.querySelector(formSelector);

                            var products = [];
                            $('.mtcheck-product').each(function(){
                            if ($(this).find('.checkbox__input').is(':checked')) {
                                products.push($(this).data('productid'));
                            }
                            });
                            $('input[name=products]').val(products.join(','));
                            $('input[name="payment_method"]').val($('input[name="pay-type"]:checked').data('title'));
                            $('input[name="payment_code"]').val($('input[name="pay-type"]:checked').data('code'));
                            updateSummary(); 
                        }, 500);
                        
                    });

                    $(document).on('DOMSubtreeModified', '.cdek_selectedPvzInfo', function() {
                        setTimeout(() => { 
                            $(this).closest('.mtcheck-delivery__type').click();
                            $('input[name="shipping_method"]').val($('input[name="delivery-type"]:checked').data('title'));
                            $('input[name="shipping_code"]').val($('input[name="delivery-type"]:checked').data('code'));
                            $('input[name="shipping_cost"]').val($('input[name="delivery-type"]:checked').val());
                            $('input[name="shipping_tax_class_id"]').val($('input[name="delivery-type"]:checked').data('tax'));
                            // let validateResult;
                            // let formSelector;

                            // formSelector = ".mtcheck-address__add-form";
                            // if (document.querySelector(formSelector) == null) {
                            //     formSelector = ".mtcheck-register";
                            // }
                            // const form = document.querySelector(formSelector);
                            var products = [];
                            $('.mtcheck-product').each(function(){
                            if ($(this).find('.checkbox__input').is(':checked')) {
                                products.push($(this).data('productid'));
                            }
                            });
                            $('input[name=products]').val(products.join(','));
                            $('input[name="payment_method"]').val($('input[name="pay-type"]:checked').data('title'));
                            $('input[name="payment_code"]').val($('input[name="pay-type"]:checked').data('code'));
                            updateSummary(); 
                        }, 500);
                    });

                    $(document).on('change', 'input[name="delivery-type"]', function(){
                        // $('input[name="shipping_method"]').val($(this).data('title'));
                        // $('input[name="shipping_code"]').val($(this).data('code'));
                        // $('input[name="shipping_cost"]').val($(this).val());
                        // $('input[name="shipping_tax_class_id"]').val($(this).data('tax'));
                        // // let validateResult;
                        // let formSelector;

                        // formSelector = ".mtcheck-address__add-form";
                        // if (document.querySelector(formSelector) == null) {
                        //     formSelector = ".mtcheck-register";
                        // }
                        // const form = document.querySelector(formSelector);

                        // var products = [];
                        // $('.mtcheck-product').each(function(){
                        // if ($(this).find('.checkbox__input').is(':checked')) {
                        //     products.push($(this).data('productid'));
                        // }
                        // });
                        // $('input[name=products]').val(products.join(','));
                        // $('input[name="payment_method"]').val($('input[name="pay-type"]:checked').data('title'));
                        // $('input[name="payment_code"]').val($('input[name="pay-type"]:checked').data('code'));

                        updateSummary();

                        // validateResult = validateData(form);

                        // if (!validateResult) {
                        //     e.preventDefault();
                        //     const registerForm = document.querySelector(".mtcheck-register");
                        //     window.scrollTo({
                        //         top: registerForm.offsetTop,
                        //         behavior: "smooth",
                        //     });
                        //     return;
                        // }
                        
                    });
                    $(document).on('change', 'input[name="pay-type"]', function(){
                        $('input[name="payment_method"]').val($(this).data('title'));
                        $('input[name="payment_code"]').val($(this).data('code'));
                        // let validateResult;
                        // let formSelector;

                        // formSelector = ".mtcheck-address__add-form";
                        // if (document.querySelector(formSelector) == null) {
                        //     formSelector = ".mtcheck-register";
                        // }
                        // const form = document.querySelector(formSelector);
                        
                        var products = [];
                        $('.mtcheck-product').each(function(){
                        if ($(this).find('.checkbox__input').is(':checked')) {
                            products.push($(this).data('productid'));
                        }
                        });
                        $('input[name=products]').val(products.join(','));
                        $('input[name="shipping_method"]').val($('input[name="delivery-type"]:checked').data('title'));
                        $('input[name="shipping_code"]').val($('input[name="delivery-type"]:checked').data('code'));
                        $('input[name="shipping_cost"]').val($('input[name="delivery-type"]:checked').val());
                        $('input[name="shipping_tax_class_id"]').val($('input[name="delivery-type"]:checked').data('tax'));

                        // validateResult = validateData(form);

                        // if (!validateResult) {
                        //     e.preventDefault();
                        //     const registerForm = document.querySelector(".mtcheck-register");
                        //     window.scrollTo({
                        //         top: registerForm.offsetTop,
                        //         behavior: "smooth",
                        //     });
                        //     return;
                        // }
                        $('.mtcheck-submit__button').show();
                        $('.mt_confirm').empty();
                        // if ($('.mtcheck-submit__button').is(":hidden")) {
                        //     var serializedData = $(formSelector).serialize();
                        //     $.ajax({
                        //         url: 'index.php?route=extension/mtcheckout/confirm',
                        //         type: 'post',
                        //         data: serializedData,
                        //         dataType: 'html',
                        //         cache: false,
                        //         beforeSend: function() {
                        //         },
                        //         success: function(html) {
                        //             $('.mt_confirm').empty();
                        //             $('.mt_confirm').append(html);
                        //         },
                        //         error: function(xhr, ajaxOptions, thrownError) {
                        //             // console.log(xhr);
                        //         }
                        //     });
                        // }
                        updateSummary(false, false);

                        // (0, _total__WEBPACK_IMPORTED_MODULE_2__.updateTotalResults)();

                    });
                }

                var startUpdate = false;
                function updateSummary(updateShip = true, updatePay = true) {
                    if (!startUpdate) {
                        startUpdate = true;
                        $(".mtcheck-product__count *").prop('disabled',true);
                        $('.donut.total').show();
                        $('.donut.payment').show();
                        $('.donut.delivery').show();
                        $("input[name='delivery-type']").prop('disabled',true);
                        $("input[name='pay-type']").prop('disabled',true);
                        updateShipping(updateShip, updatePay);
                        // updatePayment();
                        // updateTotal();
                    }
                    
                }

                function updateShipping(updateShip, updatePay) {
                    
                    $('input[name="shipping_method"]').val($('input[name="delivery-type"]:checked').data('title'));
                    $('input[name="shipping_code"]').val($('input[name="delivery-type"]:checked').data('code'));
                    $('input[name="shipping_cost"]').val($('input[name="delivery-type"]:checked').val());
                    $('input[name="shipping_tax_class_id"]').val($('input[name="delivery-type"]:checked').data('tax'));
                    $('input[name="payment_code"]').val($('input[name="pay-type"]:checked').val());
                    $('input[name="payment_method"]').val($('input[name="pay-type"]:checked').data('title'));
                    if (!$('.mtcheck-register.mtcheck-address__add-form').hasClass('visually-hidden')) {
                        $('input[name="zone_id"]').val($('.select__item.zone_list.select__item--active').data('value'));
                        $('input[name="zone"]').val($('.display_zone').text());
                        $('input[name="country_id"]').val($('.select__item.country_list.select__item--active').data('value'));
                        $('input[name="country"]').val($('.display_country').text());
                    }
                    var data = 'shipping_method='+$('input[name="shipping_method"]').val()
                                +'&shipping_code='+$('input[name="shipping_code"]').val()
                                +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                                +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val()
                                +'&city='+$('input[name="city"]').val()
                                +'&address_1='+$('input[name="address_1"]').val()
                                +'&postcode='+$('input[name="postcode"]').val()
                                +'&zone_id='+$('input[name="zone_id"]').val()
                                +'&country_id='+$('input[name="country_id"]').val();
                    if ($('input[name="address-type"]:checked').length > 0) {
                        data = 'shipping_method='+$('input[name="shipping_method"]').val()
                                +'&shipping_code='+$('input[name="shipping_code"]').val()
                                +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                                +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val()
                                +'&city='+$('input[name="address-type"]').data('city')
                                +'&address_1='+$('input[name="address-type"]').data('address')
                                +'&postcode='+$('input[name="address-type"]').data('postcode')
                                +'&zone_id='+$('input[name="address-type"]').data('zone-id')
                                +'&country_id='+$('input[name="address-type"]').data('country-id');
                    }
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/checkout/update-shipping',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            if (updateShip)
                                $('#shipping_block').html(html.html);
                            setTimeout(updatePayment(updatePay), 50);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                        }
                    });
                }
                function updatePayment(updatePay) {
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/checkout/update-payment',
                        type: 'post',
                        data: 'shipping_method='+$('input[name="shipping_method"]').val()
                            +'&shipping_code='+$('input[name="shipping_code"]').val()
                            +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                            +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val()
                            +'&payment_code='+$('input[name="payment_code"]').val()
                            +'&payment_method='+$('input[name="payment_method"]').val()
                            +'&zone_id='+$('input[name="zone_id"]').val()
                            +'&country_id='+$('input[name="country_id"]').val(),
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            if (updatePay)
                                $('#payment_block').html(html.html);
                            setTimeout(updateTotal(), 50);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                        }
                    });
                }
                function updateTotal() {
                    $('input[name="shipping_method"]').val($('input[name="delivery-type"]:checked').data('title'));
                    $('input[name="shipping_code"]').val($('input[name="delivery-type"]:checked').data('code'));
                    $('input[name="shipping_cost"]').val($('input[name="delivery-type"]:checked').val());
                    $('input[name="shipping_tax_class_id"]').val($('input[name="delivery-type"]:checked').data('tax'));
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/checkout/update-total',
                        type: 'post',
                        data: 'shipping_method='+$('input[name="shipping_method"]').val()
                            +'&shipping_code='+$('input[name="shipping_code"]').val()
                            +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                            +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val(),
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            $('.table-summary').empty();
                            $('.table-summary').append(html.html);
                            $('.mtcheck-total__sum').html(html.total);
                            $('.shipping_free_text').html(html.free_shipping_text);
                            if (html.free_shipping_text == '') $('.shipping_free_text').hide();
                            else $('.shipping_free_text').show();
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $(".mtcheck-product__count *").prop('disabled',false);
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $(".mtcheck-product__count *").prop('disabled',false);
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                        }
                    });
                }

                function isEnough(parent, maxValue, currentValue) {
                    const isSign = parent.querySelector(".mtcheck-product__sign");

                    if (currentValue <= maxValue && !isSign) return;
                    else if (currentValue <= maxValue && isSign) {
                        return isSign.remove();
                    }

                    // Add sign or just keep it

                    if (isSign) return;

                    const signNode = document.createElement("span");

                    signNode.classList.add("text", "text--xs", "mtcheck-product__sign");
                    signNode.textContent = "нет в выбранном количестве";

                    parent.insertAdjacentElement("beforeend", signNode);
                }

                /***/
            },

        /***/ "./scripts/components/getAllData.js":
            /*!******************************************!*\
  !*** ./scripts/components/getAllData.js ***!
  \******************************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ getAllData: () => /* binding */ getAllData,
                    /* harmony export */
                });
                const getAllData = (form) => {
                    const data = {};
                    const inputElems = form.querySelectorAll("input");

                    let inputsValues = [...inputElems].map((input) => {
                        return {
                            name: input.name,
                            value: input.value,
                        };
                    });

                    inputsValues = inputsValues.filter((input) => input);

                    console.log(inputsValues);
                };

                /***/
            },

        /***/ "./scripts/components/message.js":
            /*!***************************************!*\
  !*** ./scripts/components/message.js ***!
  \***************************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ createMessage: () => /* binding */ createMessage,
                    /* harmony export */
                });
                function createMessage({ container, type, text, isClose }, styles) {
                    // Remove old message (if it's avaible)

                    const oldMessage = container.querySelector(".message");

                    if (oldMessage) oldMessage.remove();

                    // Create message

                    const message = document.createElement("div");

                    message.classList.add("message", `message--${type}`);
                    message.textContent = text;

                    Object.entries(styles).forEach(([key, value]) => {
                        message.style[key] = value;
                    });

                    if (isClose) {
                        const close = document.createElement("button");

                        close.classList.add("message__close");
                        close.addEventListener("click", () => message.remove());

                        close.setAttribute("type", "button");
                        close.setAttribute("aria-label", "Закрыть вспывающее сообщение");

                        message.appendChild(close);
                    }

                    container.appendChild(message);
                }

                /***/
            },

        /***/ "./scripts/components/offers.js":
            /*!**************************************!*\
  !*** ./scripts/components/offers.js ***!
  \**************************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony import */ var _message__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(
                    /*! ./message */ "./scripts/components/message.js"
                );
                /* harmony import */ var _total__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(
                    /*! ./total */ "./scripts/components/total.js"
                );

                const couponForm = document.querySelector(".mtcheck-offers__coupon");
                const certificateForm = document.querySelector(".mtcheck-offers__certificate");

                let messageStyles = {
                    margin: "0",
                    top: "0",
                    right: "-20px",
                    maxWidth: "322px",
                    transform: "translateX(100%)",
                };

                if (window.matchMedia("(max-width: 1023px)").matches) {
                    messageStyles = {
                        bottom: "-10px",
                        left: "0",
                        maxWidth: "322px",
                        transform: "translateY(100%)",
                    };
                }

                couponForm.addEventListener("submit", (event) => {
                    event.preventDefault();

                    const coupon = { text: "AAA111", value: 5000 };
                    const entryCoupon = event.target.querySelector("[type=text]").value;

                    // * Здесь сделан для примера купон. По идее, должен быть запрос на базу данных и та же проверка)
                    $.ajax({
                        url: 'index.php?route=extension/total/coupon/coupon',
                        type: 'post',
                        data: 'coupon=' + encodeURIComponent(entryCoupon),
                        dataType: 'json',
                        beforeSend: function() {
                        },
                        complete: function() {
                        },
                        success: function(json) {
                          if (json['error']) {
                            (0, _message__WEBPACK_IMPORTED_MODULE_0__.createMessage)(
                                {
                                    container: couponForm,
                                    type: "warning",
                                    text: json['error'],
                                    isClose: true,
                                },
                                messageStyles
                            );
                          }
  
                          if (json['redirect']) {
                            (0, _message__WEBPACK_IMPORTED_MODULE_0__.createMessage)(
                                {
                                    container: couponForm,
                                    type: "success",
                                    text: `Купон ${entryCoupon} успешно использован!`,
                                    isClose: true,
                                },
                                messageStyles
                            );
    
                            window.coupon = coupon;
                            // $.ajax({
                            //     url: 'index.php?route=extension/mtcheckout/checkout/get-update-total',
                            //     type: 'post',
                            //     dataType: 'json',
                            //     cache: false,
                            //     beforeSend: function() {
                            //     },
                            //     success: function(html) {
                            //         
                            //         $('.table-summary').empty();
                            //         $('.table-summary').append(html.html);
                            //         $('.mtcheck-total__sum').html(html.total);
                            //     },
                            //     error: function(xhr, ajaxOptions, thrownError) {
                            //         console.log(xhr);
                            //     }
                            // });
                            updateSummary();
                            (0, _total__WEBPACK_IMPORTED_MODULE_1__.updateTotalResults)();
                          }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });

                    // if (coupon.text === entryCoupon) {
                    //     (0, _message__WEBPACK_IMPORTED_MODULE_0__.createMessage)(
                    //         {
                    //             container: couponForm,
                    //             type: "success",
                    //             text: `Купон ${coupon.text} на сумму ${coupon.value}Р успешно использован!`,
                    //             isClose: true,
                    //         },
                    //         messageStyles
                    //     );

                    //     window.coupon = coupon;

                    //     (0, _total__WEBPACK_IMPORTED_MODULE_1__.updateTotalResults)();
                    // } else if (entryCoupon === "") {
                    //     (0, _message__WEBPACK_IMPORTED_MODULE_0__.createMessage)(
                    //         {
                    //             container: couponForm,
                    //             type: "danger",
                    //             text: `Поле должно быть заполнено`,
                    //             isClose: true,
                    //         },
                    //         messageStyles
                    //     );
                    // } else {
                    //     (0, _message__WEBPACK_IMPORTED_MODULE_0__.createMessage)(
                    //         {
                    //             container: couponForm,
                    //             type: "warning",
                    //             text: `Купон не найден или уже не активен`,
                    //             isClose: true,
                    //         },
                    //         messageStyles
                    //     );
                    // }
                });

                certificateForm.addEventListener("submit", (event) => {
                    event.preventDefault();

                    const certificate = { text: "AAA111", value: 7000 };
                    const entryCertificate = event.target.querySelector("[type=text]").value;

                    // * Здесь сделан для примера сертификат. По идее, должен быть запрос на базу данных и та же проверка)
                    $.ajax({
                        url: 'index.php?route=extension/total/voucher/voucher',
                        type: 'post',
                        data: 'voucher=' + encodeURIComponent(entryCertificate),
                        dataType: 'json',
                        beforeSend: function() {
                        },
                        complete: function() {
                        },
                        success: function(json) {
                            $('.alert-dismissible').remove();
                            
                            if (json['error']) {
                                (0, _message__WEBPACK_IMPORTED_MODULE_0__.createMessage)(
                                    {
                                        container: certificateForm,
                                        type: "warning",
                                        text: json['error'],
                                        isClose: true,
                                    },
                                    messageStyles
                                );
                            }
                            
                            if (json['redirect']) {
                                (0, _message__WEBPACK_IMPORTED_MODULE_0__.createMessage)(
                                    {
                                        container: certificateForm,
                                        type: "success",
                                        text: `Сертификат ${entryCertificate} успешно использован!`,
                                        isClose: true,
                                    },
                                    messageStyles
                                );
        
                                window.coupon = coupon;
                                // $.ajax({
                                //     url: 'index.php?route=extension/mtcheckout/checkout/get-update-total',
                                //     type: 'post',
                                //     dataType: 'json',
                                //     cache: false,
                                //     beforeSend: function() {
                                //     },
                                //     success: function(html) {
                                //         $('.table-summary').empty();
                                //         $('.table-summary').append(html.html);
                                //         $('.mtcheck-total__sum').html(html.total);
                                //     },
                                //     error: function(xhr, ajaxOptions, thrownError) {
                                //         console.log(xhr);
                                //     }
                                // });
                                updateSummary();
                                (0, _total__WEBPACK_IMPORTED_MODULE_1__.updateTotalResults)();
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });

                    // if (certificate.text === entryCertificate) {
                    //     (0, _message__WEBPACK_IMPORTED_MODULE_0__.createMessage)(
                    //         {
                    //             container: certificateForm,
                    //             type: "success",
                    //             text: `Сертификат ${certificate.text} на сумму ${certificate.value}Р успешно использован!`,
                    //             isClose: true,
                    //         },
                    //         messageStyles
                    //     );

                    //     window.certificate = certificate;

                    //     (0, _total__WEBPACK_IMPORTED_MODULE_1__.updateTotalResults)();
                    // } else if (entryCertificate === "") {
                    //     (0, _message__WEBPACK_IMPORTED_MODULE_0__.createMessage)(
                    //         {
                    //             container: certificateForm,
                    //             type: "danger",
                    //             text: `Поле должно быть заполнено`,
                    //             isClose: true,
                    //         },
                    //         messageStyles
                    //     );
                    // } else {
                    //     (0, _message__WEBPACK_IMPORTED_MODULE_0__.createMessage)(
                    //         {
                    //             container: certificateForm,
                    //             type: "warning",
                    //             text: `Купон не найден или уже не активен`,
                    //             isClose: true,
                    //         },
                    //         messageStyles
                    //     );
                    // }
                });

                var startUpdate = false;
                function updateSummary() {
                    if (!startUpdate) {
                        startUpdate = true;
                        $(".mtcheck-product__count *").prop('disabled',true);
                        $('.donut.total').show();
                        $('.donut.payment').show();
                        $('.donut.delivery').show();
                        $("input[name='delivery-type']").prop('disabled',true);
                        $("input[name='pay-type']").prop('disabled',true);
                        updateShipping();
                        // updatePayment();
                        // updateTotal();
                    }
                    
                }

                function updateShipping() {
                    
                    $('input[name="shipping_method"]').val($('input[name="delivery-type"]:checked').data('title'));
                    $('input[name="shipping_code"]').val($('input[name="delivery-type"]:checked').data('code'));
                    $('input[name="shipping_cost"]').val($('input[name="delivery-type"]:checked').val());
                    $('input[name="shipping_tax_class_id"]').val($('input[name="delivery-type"]:checked').data('tax'));
                    $('input[name="payment_code"]').val($('input[name="pay-type"]:checked').val());
                    $('input[name="payment_method"]').val($('input[name="pay-type"]:checked').data('title'));
                    if (!$('.mtcheck-register.mtcheck-address__add-form').hasClass('visually-hidden')) {
                        $('input[name="zone_id"]').val($('.select__item.zone_list.select__item--active').data('value'));
                        $('input[name="zone"]').val($('.display_zone').text());
                        $('input[name="country_id"]').val($('.select__item.country_list.select__item--active').data('value'));
                        $('input[name="country"]').val($('.display_country').text());
                    }
                    var data = 'shipping_method='+$('input[name="shipping_method"]').val()
                                +'&shipping_code='+$('input[name="shipping_code"]').val()
                                +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                                +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val()
                                +'&city='+$('input[name="city"]').val()
                                +'&address_1='+$('input[name="address_1"]').val()
                                +'&postcode='+$('input[name="postcode"]').val()
                                +'&zone_id='+$('input[name="zone_id"]').val()
                                +'&country_id='+$('input[name="country_id"]').val();
                    if ($('input[name="address-type"]:checked').length > 0) {
                        data = 'shipping_method='+$('input[name="shipping_method"]').val()
                                +'&shipping_code='+$('input[name="shipping_code"]').val()
                                +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                                +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val()
                                +'&city='+$('input[name="address-type"]').data('city')
                                +'&address_1='+$('input[name="address-type"]').data('address')
                                +'&postcode='+$('input[name="address-type"]').data('postcode')
                                +'&zone_id='+$('input[name="address-type"]').data('zone-id')
                                +'&country_id='+$('input[name="address-type"]').data('country-id');
                    }
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/checkout/update-shipping',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            
                            $('#shipping_block').html(html.html);
                            setTimeout(updatePayment(), 50);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                        }
                    });
                }
                function updatePayment() {
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/checkout/update-payment',
                        type: 'post',
                        data: 'shipping_method='+$('input[name="shipping_method"]').val()
                            +'&shipping_code='+$('input[name="shipping_code"]').val()
                            +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                            +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val()
                            +'&payment_code='+$('input[name="payment_code"]').val()
                            +'&payment_method='+$('input[name="payment_method"]').val()
                            +'&zone_id='+$('input[name="zone_id"]').val()
                            +'&country_id='+$('input[name="country_id"]').val(),
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            $('#payment_block').html(html.html);
                            setTimeout(updateTotal(), 50);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                        }
                    });
                }
                function updateTotal() {
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/checkout/update-total',
                        type: 'post',
                        data: 'shipping_method='+$('input[name="shipping_method"]').val()
                            +'&shipping_code='+$('input[name="shipping_code"]').val()
                            +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                            +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val(),
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            
                            $('.table-summary').empty();
                            $('.table-summary').append(html.html);
                            $('.mtcheck-total__sum').html(html.total);
                            $('.shipping_free_text').html(html.free_shipping_text);
                            if (html.free_shipping_text == '') $('.shipping_free_text').hide();
                            else $('.shipping_free_text').show();
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $(".mtcheck-product__count *").prop('disabled',false);
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $(".mtcheck-product__count *").prop('disabled',false);
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                        }
                    });
                }

                /***/
            },

        /***/ "./scripts/components/popup.js":
            /*!*************************************!*\
  !*** ./scripts/components/popup.js ***!
  \*************************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ getSuccessfulSubmitData: () => /* binding */ getSuccessfulSubmitData,
                    /* harmony export */ togglePopup: () => /* binding */ togglePopup,
                    /* harmony export */
                });
                /* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils */ "./scripts/utils.js");

                const popup = document.querySelector(".mtcheck-popup");
                const popupBody = popup.querySelector(".mtcheck-popup__body");

                const title = $('input[name="mtcheck-popup-title"]').val();
                const desc = $('input[name="mtcheck-popup-desc"]').val();

                const getQuickOrderData = () => {
                    var priceHtml = $('#total-table-total').html();
                    var products = [];
                    $('.mtcheck-product').each(function(){
                        if ($(this).find('.checkbox__input').is(':checked')) {
                            products.push($(this).data('id'));
                        }
                    });
                    return `
            <h1 class="title title--md title--primary mtcheck-popup__title">${title}</h1>
            <input type="hidden" name="oneclick_products" value="${products.join(',')}">
            <input type="hidden" name="shipping_code" value="${$('input[name="delivery-type"]:checked').data('code')}">
            <input type="hidden" name="shipping_method" value="${$('input[name="delivery-type"]:checked').data('title')}">
            <input type="hidden" name="shipping_cost" value="${$('input[name="delivery-type"]:checked').val()}">
            <input type="hidden" name="shipping_tax_class_id" value="${$('input[name="delivery-type"]:checked').data('tax')}">
            <input type="hidden" name="payment_code" value="${$('input[name="pay-type"]:checked').data('code')}">
            <input type="hidden" name="payment_method" value="${$('input[name="pay-type"]:checked').data('title')}">
            <div class="text text--xs text--secondary mtcheck-popup__text">${desc}
            </div>
            <div class="mtcheck-popup__columns">
              <div class="mtcheck-popup__left">
                <label class="input--label"><input type="text" class="input input--min" name="firstname" placeholder="Имя" required></label>
                <label class="input--label"><input type="number" class="input input--min" name="phone" placeholder="Телефон" required></label>
              </div>
              <div class="mtcheck-popup__right">
                <div class="title title--sm title--primary mtcheck-popup__count">${
                    document.querySelector(".mtcheck-products__count").textContent
                }</div>
                <div class="title title--primary mtcheck-popup__sum">${priceHtml}</div>
              </div>
            </div>
            <textarea class="textarea mtcheck-popup__comment" name="text" placeholder="Комментарий к заказу, пожелания, уточнения и т.п." cols="30" rows="6"></textarea>
            <div class="mtcheck-popup__submit-block">
              <label class="checkbox checkbox--sm checkbox--blue mtcheck-popup__checkbox">
                <input type="checkbox" class="visually-hidden checkbox__input" required="" checked="">
                <span class="checkbox__display"></span>
                <span class="checkbox__text">отправляя данные я даю согласие на обработку персональной информации согласно <a href="#">политики конфиденциальности</a> данного сайта</span>
              </label>
                <button type="submit" class="mtbtn-reset mtbtn--blue mtcheck-popup__button">
                  <span>Отправить</span>
                </button>
            </div>
    `;
                };

                const getModalData = (type, data) => {
                    
                    if (data) {
                        switch (type) {
                            case "user-data":
                                return data.module_mt_checkout_clients_info_text;
                            case "delivery":
                                return data.module_mt_checkout_dostavka_info_text;
                            case "payment":
                                return data.module_mt_checkout_oplata_info_text;
                            case "confirm-order":
                                return data.module_mt_checkout_itogo_info_text;
                            case "your-order":
                                return `<h1 class="title title--md title--primary mtcheck-popup__title">Информация для модального окна Ваш заказ</h1>`;

                            default:
                                break;
                        }
                    }
                };

                const getSuccessfulSubmitData = () => {
                    return `
    <div class="mtcheck-popup__success-submit">
        <h1 class="title title--md title--primary mtcheck-popup__title">Ваше сообщение отправлено!</h1>
        <p>Сообщение успешно отправлено.<br>Мы скоро свяжемся с вами!</p>
    </div>
    `;
                };

                const togglePopup = () => {
                    popup.classList.toggle("is-open");
                };

                popup.addEventListener("click", (e) => {
                    const target = e.target;

                    if (
                        target.closest(".mtcheck-popup__close") ||
                        (target.closest(".mtcheck-popup") && !target.closest(".mtcheck-popup__body"))
                    ) {
                        popupBody.innerHTML = "";
                        togglePopup();
                    }
                });

                var modal_data = undefined;
                $.ajax({
                    url: 'index.php?route=extension/mtcheckout/checkout/get-modal-data',
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    beforeSend: function() {
                    },
                    success: function(html) {
                        modal_data = html;
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(xhr);
                    }
                });

                document.addEventListener("click", (e) => {
                    const target = e.target;

                    if (
                        target.closest("#buy-everything") ||
                        target.closest(".mtcheck-submit__by-phone") ||
                        target.closest(".mtcheck-user__buy")
                    ) {
                        popupBody.innerHTML = getQuickOrderData();
                        togglePopup();
                    }

                    if (target.closest(".info-mtbtn")) {
                        popupBody.innerHTML = getModalData(target.dataset.modal, modal_data);
                        togglePopup();
                    }
                });

                /***/
            },

        /***/ "./scripts/components/price.js":
            /*!*************************************!*\
  !*** ./scripts/components/price.js ***!
  \*************************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ calculateProductPrice: () => /* binding */ calculateProductPrice,
                    /* harmony export */ calculateProductsPrice: () => /* binding */ calculateProductsPrice,
                    /* harmony export */ calculateTotalPrice: () => /* binding */ calculateTotalPrice,
                    /* harmony export */
                });
                /* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils */ "./scripts/utils.js");

                function calculateProductsPrice() {
                    const products = [...document.querySelectorAll(".mtcheck-product .checkbox__input:checked")];
                    const price = products.reduce((previousValue, currentNode) => {
                        const product = currentNode.closest(".mtcheck-product");
                        const count = product.querySelector(".counter__display").value;
                        const price = product.dataset.price;

                        return (previousValue += count * price);
                    }, 0).toFixed(2);

                    return { price, count: products.length };
                }

                function calculateProductPrice(product) {
                    const display = product.querySelector(".price");
                    const count = product.querySelector(".counter__display").value;
                    const price = product.dataset.price;

                    (0, _utils__WEBPACK_IMPORTED_MODULE_0__.changeValueWithAnimation)(display, (price * count).toFixed(2));
                }

                function calculateTotalPrice(bonusBalls, consumersGroupDiscount, totalSummDiscount, deliveryPrice) {
                    let totalPrice;
                    let totalDiscount;

                    const { price } = calculateProductsPrice();

                    const couponValue = window.coupon ? window.coupon.value : 0;
                    const certificateValue = window.certificate ? window.certificate.value : 0;

                    totalDiscount =
                        (bonusBalls + consumersGroupDiscount * price + totalSummDiscount * price + couponValue + certificateValue).toFixed(2);

                    totalPrice = (price + deliveryPrice - totalDiscount).toFixed(2);

                    return { totalPrice, totalDiscount };
                }

                /***/
            },

        /***/ "./scripts/components/products.js":
            /*!****************************************!*\
  !*** ./scripts/components/products.js ***!
  \****************************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ removeProductFromPage: () => /* binding */ removeProductFromPage,
                    /* harmony export */ upValueWithAnimation: () => /* binding */ upValueWithAnimation,
                    /* harmony export */ updateProductsSummary: () => /* binding */ updateProductsSummary,
                    /* harmony export */
                });
                /* harmony import */ var _productMarkup__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(
                    /*! ../productMarkup */ "./scripts/productMarkup.js"
                );
                /* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils */ "./scripts/utils.js");
                /* harmony import */ var _price__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(
                    /*! ./price */ "./scripts/components/price.js"
                );
                /* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(
                    /*! ./actions */ "./scripts/components/actions.js"
                );
                /* harmony import */ var _counter__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(
                    /*! ./counter */ "./scripts/components/counter.js"
                );
                /* harmony import */ var _storage__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(
                    /*! ../storage */ "./scripts/storage.js"
                );
                /* harmony import */ var _total__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(
                    /*! ./total */ "./scripts/components/total.js"
                );

                // Render products (with its functionality)

                const productsContainer = document.querySelector(".mtcheck-products__list");
                // const products = (0,_storage__WEBPACK_IMPORTED_MODULE_5__.getProductsFromStorage)();
                var products = [];

                const selectAllText = document.querySelector(".mtcheck-products__head .checkbox__text");
                const selectAllCheckbox = document.querySelector("#select-all");

                $(document).on('input', '.counter__display', function({ target }) {
                    const productNode = target.closest(".mtcheck-product");
                    const id = productNode.dataset.id;
                    const product_id = productNode.dataset.productid;
                    fetch("/index.php?route=extension/mtcheckout/checkout/update-quantity&id=" + id +"&quantity="+target.value).then(response => {
                        if(response.ok) return response.json();
                      }).then(json => {
                        if (json.hide_fields) {
                            $('.mtcheck-user').hide();
                            $('#shipping_block').hide();
                            $('#payment_block').hide();
                            $('.mtcheck-confirm').hide();
                        }
                        else {
                            $('.mtcheck-user').show();
                            $('#shipping_block').show();
                            $('#payment_block').show();
                            $('.mtcheck-confirm').show();
                        }
                        $('.price_sum').text(json.products_sum.toFixed(2));
                        $(productNode).find('.price').text(json.product_total.toFixed(2));
                        update_products();
                        if (json.min_sum_text) {
                            $('.min_sum_text').text(json.min_sum_text);
                            $('.min_sum_text').show();
                            $('.mtcheck-user').hide();
                            $('#shipping_block').hide();
                            $('#payment_block').hide();
                            $('.mtcheck-confirm').hide();
                            const increment_products = (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsFromStorage)();
                            const index = increment_products.findIndex((p) => p.id === id);
                            updateConfirm(false);
                            updateSummary();
                            increment_products[index]["count"] = Number(target.value);
                            localStorage.setItem("products", JSON.stringify(increment_products));
                        } else if (!json.hide_fields) {
                            $('.min_sum_text').hide();
                            $('.mtcheck-user').show();
                            $('#shipping_block').show();
                            $('#payment_block').show();
                            $('.mtcheck-confirm').show();
                            const increment_products = (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsFromStorage)();
                            const index = increment_products.findIndex((p) => p.id === id);
                            updateConfirm();
                            updateSummary();
                            increment_products[index]["count"] = Number(target.value);
                            localStorage.setItem("products", JSON.stringify(increment_products));
                        }
                    });
                });

                $(document).on('click', '.counter__action--increment', function({ target }) {
                    const productNode = target.closest(".mtcheck-product");
                    const id = productNode.dataset.id;
                    const product_id = productNode.dataset.productid;
                    fetch("/index.php?route=extension/mtcheckout/checkout/increment&id=" + id).then(response => {
                        if(response.ok) return response.json();
                      }).then(json => {
                        if (json.hide_fields) {
                            $('.mtcheck-user').hide();
                            $('#shipping_block').hide();
                            $('#payment_block').hide();
                            $('.mtcheck-confirm').hide();
                        }
                        else {
                            $('.mtcheck-user').show();
                            $('#shipping_block').show();
                            $('#payment_block').show();
                            $('.mtcheck-confirm').show();
                        }
                        $('.price_sum').text(json.products_sum.toFixed(2));
                        $(productNode).find('.price').text(json.product_total.toFixed(2));
                        update_products();
                        if (json.min_sum_text) {
                            $('.min_sum_text').text(json.min_sum_text);
                            $('.min_sum_text').show();
                            $('.mtcheck-user').hide();
                            $('#shipping_block').hide();
                            $('#payment_block').hide();
                            $('.mtcheck-confirm').hide();
                            const increment_products = (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsFromStorage)();
                            const index = increment_products.findIndex((p) => p.id === id);
                            updateConfirm(false);
                            updateSummary();
                            // increment_products[index]["count"] = Number(increment_products[index]["count"]) + Number(1);
                            // localStorage.setItem("products", JSON.stringify(increment_products));
                        } else if (!json.hide_fields) {
                            $('.min_sum_text').hide();
                            $('.mtcheck-user').show();
                            $('#shipping_block').show();
                            $('#payment_block').show();
                            $('.mtcheck-confirm').show();
                            const increment_products = (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsFromStorage)();
                            const index = increment_products.findIndex((p) => p.id === id);
                            updateConfirm();
                            updateSummary();
                            // increment_products[index]["count"] = Number(increment_products[index]["count"]) + Number(1);
                            // localStorage.setItem("products", JSON.stringify(increment_products));
                        }
                    });
                    
                });

                $(document).on('click', '.counter__action--decrement', function({ target }) {
                    const productNode = target.closest(".mtcheck-product");
                    const id = productNode.dataset.id;
                    const product_id = productNode.dataset.productid;
                    if (productNode.querySelector(".counter__display").value >= 1) {
                        fetch("/index.php?route=extension/mtcheckout/checkout/decrement&id=" + id).then(response => {
                            if(response.ok) return response.json();
                        }).then(json => {
                            if (json.hide_fields) {
                                $('.mtcheck-user').hide();
                                $('#shipping_block').hide();
                                $('#payment_block').hide();
                                $('.mtcheck-confirm').hide();
                            }
                            else {
                                $('.mtcheck-user').show();
                                $('#shipping_block').show();
                                $('#payment_block').show();
                                $('.mtcheck-confirm').show();
                            }
                            $('.price_sum').text(json.products_sum.toFixed(2));
                            $(productNode).find('.price').text(json.product_total.toFixed(2));
                            update_products();
                            if (json.min_sum_text) {
                                $('.min_sum_text').text(json.min_sum_text);
                                $('.min_sum_text').show();
                                $('.mtcheck-user').hide();
                                $('#shipping_block').hide();
                                $('#payment_block').hide();
                                $('.mtcheck-confirm').hide();
                                const decrement_products = (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsFromStorage)();
                                const index = decrement_products.findIndex((p) => p.id === id);
                                updateConfirm(false);
                                updateSummary();
                                decrement_products[index]["count"] = Number(decrement_products[index]["count"]) - Number(1);
                                localStorage.setItem("products", JSON.stringify(decrement_products));
                            } else if (!json.hide_fields) {
                                $('.min_sum_text').hide();
                                $('.mtcheck-user').show();
                                $('#shipping_block').show();
                                $('#payment_block').show();
                                $('.mtcheck-confirm').show();
                                const decrement_products = (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsFromStorage)();
                                const index = decrement_products.findIndex((p) => p.id === id);
                                updateConfirm();
                                updateSummary();
                                decrement_products[index]["count"] = Number(decrement_products[index]["count"]) - Number(1);
                                localStorage.setItem("products", JSON.stringify(decrement_products));
                            }
                        });
                    }
                    else productNode.querySelector(".counter__display").value = 1;
                });

                function update_products() {
                    $('.mtcheck-product').each(function(){
                        const productNode = this;
                        const id = productNode.dataset.id;
                        fetch("/index.php?route=extension/mtcheckout/checkout/update-product-total&id=" + id).then(response => {
                            if(response.ok) return response.json();
                          }).then(json => {
                            $(productNode).find('.price').text(json.product_total.toFixed(2));
                        });
                    });
                }

                $(document).on('click', '.zone_list', function({ target }) {
                    updateConfirm();
                    updateSummary();
                });
                $(document).on('click', '.country_list', function({ target }) {
                    updateConfirm();
                    updateSummary();
                });
                var typewatch = function(){
                    var timer = 0;
                    return function(callback, ms){
                        clearTimeout (timer);
                        timer = setTimeout(callback, ms);
                    }  
                }();  
                $(document).on('keyup', 'input[name="city"]', function(){
                    typewatch(function(){
                        updateConfirm();
                        updateSummary(false);
                    }, 1000 );
                });
                $(document).on('focusout', 'input[name="city"]', function(){
                    updateConfirm();
                    updateSummary(false);
                });
                $(document).on('keyup', 'input[name="address_1"]', function(){
                    typewatch(function(){
                        updateConfirm();
                        updateSummary(false);
                    }, 1000 );
                });
                $(document).on('focusout', 'input[name="address_1"]', function(){
                    updateConfirm();
                    updateSummary(false);
                });
                $(document).on('keyup', 'input[name="postcode"]', function(){
                    typewatch(function(){
                        updateConfirm();
                        updateSummary(false);
                    }, 1000 );
                });
                $(document).on('focusout', 'input[name="postcode"]', function(){
                    updateConfirm();
                    updateSummary(false);
                });
                $(document).on('checked', 'input[name="address-type"]', function(){
                    updateConfirm();
                    updateSummary(false);
                });
                $(document).on('click', 'input[name="address-type"]', function(){
                    $('input[name="zone_id"]').val($(this).data('zone-id'));
                    $('input[name="zone"]').val($(this).data('zone'));
                    $('input[name="country_id"]').val($(this).data('country-id'));
                    $('input[name="country"]').val($(this).data('country'));
                    updateConfirm();
                    updateSummary(false);
                });

                // $(document).on('click', '.mtcheck-product__remove', function({ target }) {
                //     const productNode = target.closest(".mtcheck-product");
                //     const id = productNode.dataset.id;
                //     const restore = productNode.dataset.restore;
                //     fetch("/index.php?route=extension/mtcheckout/checkout/remove-from-cart&id=" + id).then(() => {
                //         (0, _storage__WEBPACK_IMPORTED_MODULE_5__.removeProductFromStorage)(id);
                //         removeProductFromPage(productNode, id);
                //         updateProductsSummary();
                //         updateConfirm();
                //         updateSummary();
                //         (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                //     });
                // });

                // $(document).on('click', '.mtcheck-product__remove', function({ target }) {
                //     const productNode = target.closest(".mtcheck-product");
                //     const id = productNode.dataset.id;
                //     const restore = productNode.dataset.restore;
                //     fetch("/index.php?route=extension/mtcheckout/checkout/remove-from-cart&id=" + id).then(() => {
                //         (0, _storage__WEBPACK_IMPORTED_MODULE_5__.removeProductFromStorage)(id);
                //         removeProductFromPage(productNode, id);
                //         updateProductsSummary();
                //         updateConfirm();
                //         updateSummary();
                //         (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                //     });
                // });

                $('#select-all').click(function() {
                    if ($(this).data('checked') != undefined && $(this).data('checked') == 'true') {
                        $(this).data('checked', 'false');
                    }
                    else {
                        $(this).data('checked', 'true');
                    }
                    $('.mtcheck-product__left').each(function() {
                        $(this).find('.checkbox__input').click();
                    });
                });

                $(document).on('click', '.checkbox__input', function({ target }) {
                    if ($(this).attr('id') == 'select-all') return false;
                    (0, _actions__WEBPACK_IMPORTED_MODULE_3__.toggleActions)();
                                    
                    const activeProductsCount = (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsFromStorage)().filter(
                        ({ isDeleted }) => !isDeleted
                    );
                    const checkedItemsCount = document.querySelectorAll(".mtcheck-product__left .checkbox__input:checked");
                    $(this).data('checked', 'true');
                    if (activeProductsCount !== checkedItemsCount) selectAllCheckbox.checked = false;
                    if ($(this).data('checked') != undefined && $(this).data('checked') == 'true') {
                        $(this).data('checked', 'false');
                        const productNode = target.closest(".mtcheck-product");
                        const id = productNode.dataset.id;
                        fetch(
                            "/index.php?route=extension/mtcheckout/checkout/restore-cart&quantity=" +
                                $(productNode).find('.counter__display').val() +
                                "&restore=" +
                                encodeURIComponent($(productNode).data('restore'))
                        ).then((res) => {
                            updateProductsSummary();
                            updateConfirm();
                            updateSummary();
                            (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                        });
                        (0, _storage__WEBPACK_IMPORTED_MODULE_5__.restoreProductFromStorage)(id);
                    }
                    else {
                        $(this).data('checked', 'true');
                        const productNode = target.closest(".mtcheck-product");
                        const id = productNode.dataset.id;
                        fetch(
                            `/index.php?route=extension/mtcheckout/checkout/remove-from-cart&id=${id}`
                        ).then((res) => {
                            updateProductsSummary();
                            updateConfirm();
                            updateSummary();
                            (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                        });
                    }
                    
                });

                fetch("/index.php?route=extension/mtcheckout/checkout/products")
                    .then((response) => response.json())
                    .then((res) => {
                        localStorage.setItem("products", JSON.stringify(res.products));
                        products = res.products;
                        products.forEach((product) => {
                            const node = new DOMParser().parseFromString(
                                (0, _productMarkup__WEBPACK_IMPORTED_MODULE_0__.productMarkup)(product),
                                "text/html"
                            ).body.firstChild;

                            const select = node.querySelector(".checkbox__input");
                            const remove = node.querySelector(".mtcheck-product__remove");
                            const restore = node.querySelector(".mtcheck-product__restore");
                            const counter = node.querySelector(".counter");
                            const decrement = node.querySelector(".counter__action--decrement");
                            const increment = node.querySelector(".counter__action--increment");
                            // if (decrement) {
                            //     decrement.addEventListener("click", ({ target }) => {
                            //         const productNode = target.closest(".mtcheck-product");
                            //         const id = productNode.dataset.id;
                            //         fetch("/index.php?route=extension/mtcheckout/checkout/decrement&id=" + id);
                            //         const decrement_products = (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsFromStorage)();
                            //         const index = decrement_products.findIndex((p) => p.id === id);
                            //         updateConfirm();
                            //         updateSummary();
                            //         decrement_products[index]["count"] = Number(decrement_products[index]["count"]) - Number(1);
                            //         localStorage.setItem("products", JSON.stringify(decrement_products));
                            //     });
                            // }
                            // if (increment) {
                            //     increment.addEventListener("click", ({ target }) => {
                            //         const productNode = target.closest(".mtcheck-product");
                            //         const id = productNode.dataset.id;
                            //         fetch("/index.php?route=extension/mtcheckout/checkout/increment&id=" + id);
                            //         const increment_products = (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsFromStorage)();
                            //         const index = increment_products.findIndex((p) => p.id === id);
                            //         updateConfirm();
                            //         updateSummary();
                            //         increment_products[index]["count"] = Number(increment_products[index]["count"]) + Number(1);
                            //         localStorage.setItem("products", JSON.stringify(increment_products));
                            //     });
                            // }
                            // if (select) {
                            //     select.addEventListener("change", ({ target }) => {
                            //         (0, _actions__WEBPACK_IMPORTED_MODULE_3__.toggleActions)();
                                    
                            //         const activeProductsCount = (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsFromStorage)().filter(
                            //             ({ isDeleted }) => !isDeleted
                            //         );
                            //         const checkedItemsCount = document.querySelectorAll(".mtcheck-product__left .checkbox__input:checked");

                            //         if (activeProductsCount !== checkedItemsCount) selectAllCheckbox.checked = false;
                            //         if ($(this).data('checked') != undefined && $(this).data('checked') == 'true') {
                            //             $(this).data('checked', 'false');
                            //             const productNode = target.closest(".mtcheck-product");
                            //             const id = productNode.dataset.id;
                            //             fetch(
                            //                 "/index.php?route=extension/mtcheckout/checkout/restore-cart&quantity=" +
                            //                     $(productNode).find('.counter__display').val() +
                            //                     "&restore=" +
                            //                     encodeURIComponent($(productNode).data('restore'))
                            //             ).then((res) => {
                            //                 updateProductsSummary();
                            //                 updateConfirm();
                            //                 updateSummary();
                            //                 (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                            //             });
                            //             (0, _storage__WEBPACK_IMPORTED_MODULE_5__.restoreProductFromStorage)(id);
                            //         }
                            //         else {
                            //             $(this).data('checked', 'true');
                            //             const productNode = target.closest(".mtcheck-product");
                            //             const id = productNode.dataset.id;
                            //             fetch(
                            //                 `/index.php?route=extension/mtcheckout/checkout/remove-from-cart&id=${id}`
                            //             ).then((res) => {
                            //                 updateProductsSummary();
                            //                 updateConfirm();
                            //                 updateSummary();
                            //                 (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                            //             });
                            //         }
                                    
                            //     });
                            // }

                            if (remove) {
                                remove.addEventListener("click", ({ target }) => {
                                    const productNode = target.closest(".mtcheck-product");
                                    const id = productNode.dataset.id;
                                    const restore = productNode.dataset.restore;
                                    fetch("/index.php?route=extension/mtcheckout/checkout/remove-from-cart&id=" + id).then(() => {
                                        (0, _storage__WEBPACK_IMPORTED_MODULE_5__.removeProductFromStorage)(id);
                                        removeProductFromPage(productNode, id);
                                        updateProductsSummary();
                                        updateConfirm();
                                        updateSummary();
                                        (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                                    });
                                });
                            }

                            if (restore) {
                                restore.addEventListener("click", ({ target }) => {
                                    const productNode = target.closest(".mtcheck-product");
                                    const id = productNode.dataset.id;

                                    (0, _storage__WEBPACK_IMPORTED_MODULE_5__.restoreProductFromStorage)(id);
                                    restoreProductFromPage(productNode, id);
                                    updateProductsSummary();
                                    updateConfirm();
                                    updateSummary();
                                    (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                                });
                            }


                            (0, _counter__WEBPACK_IMPORTED_MODULE_4__.initializeCounter)(counter);

                            productsContainer.insertAdjacentElement("afterbegin", node);

                            const checkboxes = document.querySelectorAll(".mtcheck-product__left .checkbox__input");

                            checkboxes.forEach((checkbox) => (checkbox.checked = true));


                        });

                        const summaryCount = document.querySelector(".mtcheck-products__count");
                        const summaryPrice = document.querySelector(".price_sum");

                        const { count, price: currentPrice } = (0, _price__WEBPACK_IMPORTED_MODULE_2__.calculateProductsPrice)();
                        summaryCount.textContent = `${$('input[name="module_mt_checkout_cart_tovary_text"]').val()} ${count} ${$('input[name="module_mt_checkout_cart_unit_text"]').val()}`;
                        (0, _utils__WEBPACK_IMPORTED_MODULE_1__.changeValueWithAnimation)(summaryPrice, currentPrice);
                        (0, _actions__WEBPACK_IMPORTED_MODULE_3__.toggleActions)();

                        (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                    });
                // updateSummary();

                function removeProductFromPage(currentNode, id) {
                    const isDeleted = currentNode.classList.contains("mtcheck-product--deleted");

                    if (isDeleted) {
                        return currentNode.remove();
                    }

                    const sibling = currentNode.nextSibling;
                    const newNode = new DOMParser().parseFromString(
                        (0, _productMarkup__WEBPACK_IMPORTED_MODULE_0__.productMarkup)(
                            (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductFromStorage)(id)
                        ),
                        "text/html"
                    ).body.firstChild;

                    const remove = newNode.querySelector(".mtcheck-product__remove");
                    const restore = newNode.querySelector(".mtcheck-product__restore");

                    remove.addEventListener("click", ({ target }) => {
                        const productNode = target.closest(".mtcheck-product");
                        const id = productNode.dataset.id;

                        (0, _storage__WEBPACK_IMPORTED_MODULE_5__.removeProductFromStorage)(id);
                        removeProductFromPage(productNode, id);
                        updateProductsSummary();

                        (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                    });

                    restore.addEventListener("click", ({ target }) => {
                        const productNode = target.closest(".mtcheck-product");
                        const id = productNode.dataset.id;

                        (0, _storage__WEBPACK_IMPORTED_MODULE_5__.restoreProductFromStorage)(id);
                        restoreProductFromPage(productNode, id);
                        updateProductsSummary();

                        (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                    });

                    selectAllText.textContent = `${$('input[name="module_mt_checkout_cart_item_text"]').val()} (${(0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsCountFromStorage)()})`;

                    currentNode.remove();
                    productsContainer.insertBefore(newNode, sibling);

                    (0, _actions__WEBPACK_IMPORTED_MODULE_3__.toggleActions)();
                }
                $('.donut').hide();
                var startUpdate = false;
                function updateSummary(update_zone = true) {
                    if (!startUpdate) {
                        startUpdate = true;
                        $(".mtcheck-product__count *").prop('disabled',true);
                        $('.donut.total').show();
                        $('.donut.payment').show();
                        $('.donut.delivery').show();
                        $("input[name='delivery-type']").prop('disabled',true);
                        $("input[name='pay-type']").prop('disabled',true);
                        updateShipping();
                        // updatePayment();
                        // updateTotal();
                    }
                    
                }

                function updateShipping() {
                    $('input[name="shipping_method"]').val($('input[name="delivery-type"]:checked').data('title'));
                    $('input[name="shipping_code"]').val($('input[name="delivery-type"]:checked').data('code'));
                    $('input[name="shipping_cost"]').val($('input[name="delivery-type"]:checked').val());
                    $('input[name="shipping_tax_class_id"]').val($('input[name="delivery-type"]:checked').data('tax'));
                    $('input[name="payment_code"]').val($('input[name="pay-type"]:checked').val());
                    $('input[name="payment_method"]').val($('input[name="pay-type"]:checked').data('title'));
                    if (!$('.mtcheck-register.mtcheck-address__add-form').hasClass('visually-hidden')) {
                        $('input[name="zone_id"]').val($('.select__item.zone_list.select__item--active').data('value'));
                        $('input[name="zone"]').val($('.display_zone').text());
                        $('input[name="country_id"]').val($('.select__item.country_list.select__item--active').data('value'));
                        $('input[name="country"]').val($('.display_country').text());
                    }
                    var data = 'shipping_method='+$('input[name="shipping_method"]').val()
                                +'&shipping_code='+$('input[name="shipping_code"]').val()
                                +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                                +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val()
                                +'&city='+$('input[name="city"]').val()
                                +'&address_1='+$('input[name="address_1"]').val()
                                +'&postcode='+$('input[name="postcode"]').val()
                                +'&zone_id='+$('input[name="zone_id"]').val()
                                +'&country_id='+$('input[name="country_id"]').val();
                    if ($('input[name="address-type"]:checked').length > 0) {
                        data = 'shipping_method='+$('input[name="shipping_method"]').val()
                                +'&shipping_code='+$('input[name="shipping_code"]').val()
                                +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                                +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val()
                                +'&city='+$('input[name="address-type"]').data('city')
                                +'&address_1='+$('input[name="address-type"]').data('address')
                                +'&postcode='+$('input[name="address-type"]').data('postcode')
                                +'&zone_id='+$('input[name="address-type"]').data('zone-id')
                                +'&country_id='+$('input[name="address-type"]').data('country-id');
                    }
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/checkout/update-shipping',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            $('#shipping_block').html(html.html);
                            $('input[name="shipping_method"]').val($('input[name="delivery-type"]:checked').data('title'));
                            $('input[name="shipping_code"]').val($('input[name="delivery-type"]:checked').data('code'));
                            $('input[name="shipping_cost"]').val($('input[name="delivery-type"]:checked').val());
                            $('input[name="shipping_tax_class_id"]').val($('input[name="delivery-type"]:checked').data('tax'));
                            setTimeout(updatePayment(), 50);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                            setTimeout(updatePayment(), 50);
                        }
                    });
                }
                function updatePayment() {
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/checkout/update-payment',
                        type: 'post',
                        data: 'shipping_method='+$('input[name="shipping_method"]').val()
                            +'&shipping_code='+$('input[name="shipping_code"]').val()
                            +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                            +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val()
                            +'&payment_code='+$('input[name="payment_code"]').val()
                            +'&payment_method='+$('input[name="payment_method"]').val()
                            +'&zone_id='+$('input[name="zone_id"]').val()
                            +'&country_id='+$('input[name="country_id"]').val(),
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            $('#payment_block').html(html.html);
                            setTimeout(updateTotal(), 150);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                            setTimeout(updateTotal(), 150);
                        }
                    });
                }
                function updateTotal() {
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/checkout/update-total',
                        type: 'post',
                        data: 'shipping_method='+$('input[name="shipping_method"]').val()
                            +'&shipping_code='+$('input[name="shipping_code"]').val()
                            +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                            +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val(),
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            $('.table-summary').empty();
                            $('.table-summary').append(html.html);
                            $('.mtcheck-total__sum').html(html.total);
                            $('.shipping_free_text').html(html.free_shipping_text);
                            if (html.free_shipping_text == '') $('.shipping_free_text').hide();
                            else $('.shipping_free_text').show();
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                            $(".mtcheck-product__count *").prop('disabled',false);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                            $(".mtcheck-product__count *").prop('disabled',false);
                        }
                    });
                }

                function updateConfirm(send = true) {
                    if (!$('.mtcheck-submit__button').is(":hidden")) return false;
                    // let validateResult;
                    let mtformSelector;
                    
                    mtformSelector = ".mtcheck-address__add-form";
                    if (document.querySelector(mtformSelector) == null) {
                        mtformSelector = ".mtcheck-register";
                    }
                    const form = document.querySelector(mtformSelector);

                    var products = [];
                    $('.mtcheck-product').each(function(){
                    if ($(this).find('.checkbox__input').is(':checked')) {
                        products.push($(this).data('productid'));
                    }
                    });
                    $('input[name=products]').val(products.join(','));
                    $('input[name="shipping_method"]').val($('input[name="delivery-type"]:checked').data('title'));
                    $('input[name="shipping_code"]').val($('input[name="delivery-type"]:checked').data('code'));
                    $('input[name="shipping_cost"]').val($('input[name="delivery-type"]:checked').val());
                    $('input[name="shipping_tax_class_id"]').val($('input[name="delivery-type"]:checked').data('tax'));
                    $('input[name="payment_method"]').val($('input[name="pay-type"]:checked').data('title'));
                    $('input[name="payment_code"]').val($('input[name="pay-type"]:checked').data('code'));

                    // validateResult = validateData(form);

                    // if (!validateResult) {
                    //     e.preventDefault();
                    //     const registerForm = document.querySelector(".mtcheck-register");
                    //     window.scrollTo({
                    //         top: registerForm.offsetTop,
                    //         behavior: "smooth",
                    //     });
                    //     return;
                    // }
                    if (send) {
                        var serializedData = $(mtformSelector).serialize();
                        $.ajax({
                            url: 'index.php?route=extension/mtcheckout/confirm',
                            type: 'post',
                            data: serializedData,
                            dataType: 'html',
                            cache: false,
                            beforeSend: function() {
                            },
                            success: function(html) {
                                $('.mt_confirm').empty();
                                $('.mt_confirm').append(html);
                                
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                console.log(xhr);
                            }
                        });
                    }
                }

                function restoreProductFromPage(currentNode, id) {
                    const sibling = currentNode.nextSibling;
                    const newNode = new DOMParser().parseFromString(
                        (0, _productMarkup__WEBPACK_IMPORTED_MODULE_0__.productMarkup)(
                            (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductFromStorage)(id)
                        ),
                        "text/html"
                    ).body.firstChild;

                    const select = newNode.querySelector(".checkbox__input");
                    const remove = newNode.querySelector(".mtcheck-product__remove");
                    const counter = newNode.querySelector(".counter");

                    select.checked = true;

                    select.addEventListener("change", () => {
                        (0, _actions__WEBPACK_IMPORTED_MODULE_3__.toggleActions)();

                        const activeProductsCount = (0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsFromStorage)().filter(
                            ({ isDeleted }) => !isDeleted
                        );
                        const checkedItemsCount = document.querySelectorAll(".mtcheck-product__left .checkbox__input:checked");

                        if (activeProductsCount !== checkedItemsCount) selectAllCheckbox.checked = false;

                        updateProductsSummary();

                        (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                    });

                    remove.addEventListener("click", ({ target }) => {
                        const productNode = target.closest(".mtcheck-product");
                        const id = productNode.dataset.id;

                        (0, _storage__WEBPACK_IMPORTED_MODULE_5__.removeProductFromStorage)(id);
                        removeProductFromPage(productNode, id);
                        updateProductsSummary();

                        (0, _total__WEBPACK_IMPORTED_MODULE_6__.updateTotalResults)();
                    });

                    (0, _counter__WEBPACK_IMPORTED_MODULE_4__.initializeCounter)(counter);

                    selectAllText.textContent = `${$('input[name="module_mt_checkout_cart_item_text"]').val()} (${(0, _storage__WEBPACK_IMPORTED_MODULE_5__.getProductsCountFromStorage)()})`;

                    currentNode.remove();
                    productsContainer.insertBefore(newNode, sibling);

                    (0, _actions__WEBPACK_IMPORTED_MODULE_3__.toggleActions)();
                }

                // Update products-summary (under the products list) (count / price)

                function upValueWithAnimation(place, prev, current) {
                    let animationStart;
                    let requestId = window.requestAnimationFrame(animate);

                    function animate(timestamp) {
                        if (!animationStart) {
                            animationStart = timestamp;
                        }

                        const progress = timestamp - animationStart;

                        prev += progress * 10;

                        if (prev < current) {
                            window.requestAnimationFrame(animate);
                        } else {
                            prev = current;
                            window.cancelAnimationFrame(requestId);
                        }
                        place.textContent = `${(0, _utils__WEBPACK_IMPORTED_MODULE_1__.normalizePrice)(Math.floor(prev))}`;
                    }
                }

                function updateProductsSummary() {
                    const summaryCount = document.querySelector(".mtcheck-products__count");
                    const summaryPrice = document.querySelector(".price_sum");

                    const { count, price: currentPrice } = (0, _price__WEBPACK_IMPORTED_MODULE_2__.calculateProductsPrice)();

                    if ($('input[name="module_mt_checkout_cart_tovary_text"]').val() != undefined) {
                        summaryCount.textContent = `${$('input[name="module_mt_checkout_cart_tovary_text"]').val()} ${count} ${$('input[name="module_mt_checkout_cart_unit_text"]').val()}`;
                    }
                    (0, _utils__WEBPACK_IMPORTED_MODULE_1__.changeValueWithAnimation)(summaryPrice, currentPrice);
                }

                /***/
            },

        /***/ "./scripts/components/select.js":
            /*!**************************************!*\
  !*** ./scripts/components/select.js ***!
  \**************************************/
            /***/ function () {
                const selects = document.querySelectorAll(".select");
                const selectOpenedClassName = "select--opened";
                const selectItemActiveClassName = "select__item--active";

                selects.forEach((select) => {
                    const items = select.querySelectorAll(".select__item");

                    select.addEventListener("click", toggle.bind(this, select));

                    items.forEach((item) => item.addEventListener("click", change.bind(this, select)));
                });

                function toggle(select) {
                    select.classList.toggle(selectOpenedClassName);
                }

                function change(select, event) {
                    const display = select.querySelector(".select__display");
                    const currentActiveItem = select.querySelector(`.${selectItemActiveClassName}`);
                    const item = event.target;

                    display.textContent = item.textContent;

                    // Change active tab (by style)

                    currentActiveItem.classList.remove(selectItemActiveClassName);
                    item.classList.add(selectItemActiveClassName);
                }

                /***/
            },

        /***/ "./scripts/components/submitData.js":
            /*!******************************************!*\
  !*** ./scripts/components/submitData.js ***!
  \******************************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ submitData: () => /* binding */ submitData,
                    /* harmony export */
                });
                /* harmony import */ var _validateData__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(
                    /*! ./validateData */ "./scripts/components/validateData.js"
                );
                /* harmony import */ var _popup__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(
                    /*! ./popup */ "./scripts/components/popup.js"
                );
                /* harmony import */ var _getAllData__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(
                    /*! ./getAllData */ "./scripts/components/getAllData.js"
                );

                const popup = document.querySelector(".mtcheck-popup");
                const popupBody = document.querySelector(".mtcheck-popup__body");

                // const summarySubmitButton = document.querySelector(".mtcheck-summary .mtcheck-submit__button");

                const confirmSubmitButton = document.querySelector(".mtcheck-submit__button");

                const isAuth = false;

                const submitData = (e) => {
                    let validateResult;
                    let mtformSelector;

                    if (isAuth) {
                        mtformSelector = ".mtcheck-address__add-form";
                    } else {
                        mtformSelector = popup.classList.contains("is-open") ? ".mtcheck-popup form" : ".mtcheck-register";
                    }

                    if ($('input[name="itogo_privacy"]').val() != '0' && !$('input[name="itogo_privacy"]').is(':checked')) return false;

                    const form = document.querySelector(mtformSelector);

                    var products = [];
                    $('.mtcheck-product').each(function(){
                        if ($(this).find('.checkbox__input').is(':checked')) {
                            products.push($(this).data('productid'));
                        }
                    });
                    
                    $('input[name=products]').val(products.join(','));
                    if ($('input[name="delivery-type"]')) {
                        $('input[name="shipping_method"]').val($('input[name="delivery-type"]:checked').data('title'));
                        $('input[name="shipping_code"]').val($('input[name="delivery-type"]:checked').data('code'));
                        $('input[name="shipping_cost"]').val($('input[name="delivery-type"]:checked').val());
                        $('input[name="shipping_tax_class_id"]').val($('input[name="delivery-type"]:checked').data('tax'));
                    }
                    $('input[name="payment_method"]').val($('input[name="pay-type"]:checked').data('title'));
                    $('input[name="payment_code"]').val($('input[name="pay-type"]:checked').data('code'));

                    validateResult = (0, _validateData__WEBPACK_IMPORTED_MODULE_0__.validateData)(form);

                    if (!validateResult) {
                        e.preventDefault();
                        const registerForm = document.querySelector(".mtcheck-register");
                        window.scrollTo({
                            top: registerForm.offsetTop,
                            behavior: "smooth",
                        });
                        return;
                    }
                    
                    var serializedData = $(mtformSelector).serialize();
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/confirm',
                        type: 'post',
                        data: serializedData,
                        dataType: 'html',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            $('.mt_confirm').append(html);
                            $(".mtcheck-confirm .mtcheck-submit__button").hide();
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                        }
                    });
                    // form.submit();
                    // const data = (0, _getAllData__WEBPACK_IMPORTED_MODULE_2__.getAllData)(form);

                    // console.log("Данные отправлены");

                    // popupBody.innerHTML = (0, _popup__WEBPACK_IMPORTED_MODULE_1__.getSuccessfulSubmitData)();

                    // if (!popup.classList.contains("is-open")) (0, _popup__WEBPACK_IMPORTED_MODULE_1__.togglePopup)();
                };
                
                // summarySubmitButton.addEventListener("click", submitData);
                confirmSubmitButton.addEventListener("click", submitData);
                // popupBody.addEventListener("submit", submitData);

                /***/
            },

        /***/ "./scripts/components/tabs.js":
            /*!************************************!*\
  !*** ./scripts/components/tabs.js ***!
  \************************************/
            /***/ () => {
                const tabsHandler = document.querySelector("[data-tab-handler]");
                const tabsStatus = document.querySelector("[data-tab-status]");
                if (tabsHandler != undefined)
                    tabsHandler.addEventListener("click", tabChange);

                function tabChange() {
                    const futureActiveTab = document.querySelector("[data-tab='']");
                    const currentActiveTab = document.querySelector("[data-tab='true']");

                    currentActiveTab.setAttribute("data-tab", "");
                    futureActiveTab.setAttribute("data-tab", "true");

                    // Set text nodes

                    const previousHandler = tabsHandler.textContent;
                    const previousStatus = tabsStatus.textContent;

                    tabsHandler.textContent = tabsHandler.dataset.tabHandler;
                    tabsStatus.textContent = tabsStatus.dataset.tabStatus;

                    tabsHandler.setAttribute("data-tab-handler", previousHandler);
                    tabsStatus.setAttribute("data-tab-status", previousStatus);
                }

                /***/
            },

        /***/ "./scripts/components/total.js":
            /*!*************************************!*\
  !*** ./scripts/components/total.js ***!
  \*************************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ updateTotalData: () => /* binding */ updateTotalData,
                    /* harmony export */ updateTotalResults: () => /* binding */ updateTotalResults,
                    /* harmony export */
                });
                /* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils */ "./scripts/utils.js");
                /* harmony import */ var _price__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(
                    /*! ./price */ "./scripts/components/price.js"
                );

                // Здесь подсчет. Я сделал примерно, саму формулу надо доработать)

                function getConfirmTableTemplate(
                    deliveryType,
                    deliveryPrice,
                    payType,
                    bonusBalls,
                    consumersGroupDiscount,
                    price,
                    totalSummDiscount
                ) {
                    return `
    <div class="mtcheck-table__item">
      <div class="text text--md text--primary mtcheck-table__item-name">
        Доставка 
        <span>(${deliveryType})</span>
      </div>
      <span class="text text--md text--primary mtcheck-table__item-value" id="confirm-delivery-price">${deliveryPrice} &#8381;</span>
    </div>
    <div class="mtcheck-table__item mtcheck-table__item--margin">
      <div class="text text--md text--primary mtcheck-table__item-name">
        Оплата 
        <span>(${payType})</span>
      </div>
    </div>
    <div class="mtcheck-table__item">
      <div class="text text--md text--primary mtcheck-table__item-name">Бонусные баллы</div>
      <span class="text text--md text--primary mtcheck-table__item-value">${bonusBalls} баллов</span>
    </div>
    <div class="mtcheck-table__item">
      <div class="text text--md text--primary mtcheck-table__item-name">
        Скидка группе покупателей, 5% 
        <span>(серебрянный клиент)</span>
      </div>
      <span class="text text--md text--primary mtcheck-table__item-value">- <span id="confirm-consumers-group-discount">${Math.floor(
          consumersGroupDiscount * price
      ).toLocaleString("ru-RU")}</span> &#8381;</span>
    </div>
    ${
        window.coupon
            ? `
        <div class="mtcheck-table__item">
          <div class="text text--md text--primary mtcheck-table__item-name">
            Купон на скидку 
            <span>(${window.coupon.text})</span>
          </div>
          <span class="text text--md text--primary mtcheck-table__item-value" id="confirm-coupon-value">- ${window.coupon.value} &#8381;</span>
        </div>
        `
            : ""
    }
    ${
        window.certificate
            ? `
        <div class="mtcheck-table__item">
          <div class="text text--md text--primary mtcheck-table__item-name">
            Сертификат 
            <span>(${window.certificate.text})</span>
          </div>
          <span class="text text--md text--primary mtcheck-table__item-value" id="confirm-certificate-value">- ${window.certificate.value} &#8381;</span>
        </div>
        `
            : ""
    }
    <div class="mtcheck-table__item">
      <div class="text text--md text--primary mtcheck-table__item-name">
        Скидка за сумму заказа, 3%
      </div>
      <span class="text text--md text--primary mtcheck-table__item-value" id="confirm-total-summ-discount">- ${Math.ceil(
          totalSummDiscount * price
      ).toLocaleString("ru-RU")} &#8381;</span>
    </div>
    <div class="mtcheck-table__item">
      <div class="text text--md text--primary mtcheck-table__item-name">
        Налог НДС, 20%
      </div>
      <span class="text text--md text--primary mtcheck-table__item-value" id="confirm-tax-value">${(price * 0.2).toLocaleString(
          "ru-RU"
      )} &#8381;</span>
    </div>
  `;
                }

                function getOrderTableTemplate(deliveryType, deliveryPrice, payType, productPrice, totalDiscount) {
                    return `
    <div class="mtcheck-table__item">
      <div class="text mtcheck-table__item-name">
        Доставка 
        <span>(${deliveryType})</span>
      </div>
      <span class="text mtcheck-table__item-value" ><span id="order-delivery-price">${deliveryPrice}</span> &#8381;</span>
    </div>
    <div class="mtcheck-table__item mtcheck-table__item--margin">
      <div class="text mtcheck-table__item-name">
        Оплата 
        <span>(${payType})</span>
      </div>
    </div>
    <div class="mtcheck-table__item">
      <div class="text mtcheck-table__item-name">Товары, с учетом налогов</div>
      <span class="text mtcheck-table__item-value" id="order-products-price"><span id="order-products-price">${productPrice}</span> &#8381;</span>
    </div>
    <div class="mtcheck-table__item">
      <div class="text mtcheck-table__item-name">Скидка, %</div>
      <span class="text mtcheck-table__item-value">- <span id="order-total-discount">${totalDiscount}<span> &#8381;</span>
    </div>
  `;
                }

                function getPrevData() {
                    const orderDeliveryPrice = document.getElementById("order-delivery-price");
                    const orderProductPrice = document.getElementById("order-products-price");
                    const orderTotalDiscount = document.getElementById("order-total-discount");

                    let prevDeliveryPrice = 0,
                        prevProductPrice = 0,
                        prevTotalDiscount = 0;

                    if ((orderDeliveryPrice, orderProductPrice, orderTotalDiscount)) {
                        prevDeliveryPrice = (0, _utils__WEBPACK_IMPORTED_MODULE_0__.getNumberValue)(orderDeliveryPrice);
                        prevProductPrice = (0, _utils__WEBPACK_IMPORTED_MODULE_0__.getNumberValue)(orderProductPrice);
                        prevTotalDiscount = (0, _utils__WEBPACK_IMPORTED_MODULE_0__.getNumberValue)(orderTotalDiscount);
                    }

                    return { prevDeliveryPrice, prevProductPrice, prevTotalDiscount };
                }

                function updateTotalResults() {
                    return false;
                    var products = [];
                    $('.mtcheck-product').each(function(){
                        if ($(this).find('.checkbox__input').is(':checked')) {
                            products.push($(this).data('productid'));
                        }
                    });
                    var shipping = $('input[name="delivery-type"]:checked');
                    var pay = $('input[name="pay-type"]:checked');
                    fetch("/index.php?route=extension/mtcheckout/checkout/get-total&shipping="+$(shipping).val())
                    .then((response) => response.json())
                    .then((res) => {
                        $('.mtcheck-total__sum').html(res);
                        // $(res.totals).each(function() {
                        //     if (this.code == 'total') this.text = parseInt(this.text, 10) + parseInt($(shipping).val(), 10);
                        //     console.log(this.text);
                        //     // (0, _utils__WEBPACK_IMPORTED_MODULE_0__.changeValueWithAnimation)(document.getElementById("total-"+this.code+''+(this.code == 'total' ? '' : this.id)), this.text, this.currencyLeft, this.currencyRight);
                        //     (0, _utils__WEBPACK_IMPORTED_MODULE_0__.changeValueWithAnimation)(document.getElementById("total-table-"+this.code+''+(this.code == 'total' ? '' : this.id)), this.text, this.currencyLeft, this.currencyRight);
                        // });
                        // // (0, _utils__WEBPACK_IMPORTED_MODULE_0__.changeValueWithAnimation)(document.getElementById("confirm-delivery-price"), $(shipping).val());
                        // // $('#delivery-title').html('('+$(shipping).data('title')+')');
                        // // $('#pay-title').html('('+$(pay).data('title')+')');
                        (0, _utils__WEBPACK_IMPORTED_MODULE_0__.changeValueWithAnimation)(document.getElementById("confirm-delivery-table-price"), $(shipping).val());
                        $('#delivery-table-title').html('('+$(shipping).data('title')+')');
                        $('#pay-table-title').html('('+$(pay).data('title')+')');
                    });
                    const confirmTable = document.querySelector(".mtcheck-confirm .mtcheck-table");
                    const orderTable = document.querySelector(".mtcheck-summary .mtcheck-table");

                    // Входные данные для расчета

                    const bonusBalls = 0;
                    const consumersGroupDiscount = 0.0;
                    const totalSummDiscount = 0.0;

                    const deliveryLabel = document.querySelector("[name=delivery-type]:checked").parentElement;
                    const deliveryType = deliveryLabel.querySelector(".radio__text").textContent;
                    const deliveryPrice = +deliveryLabel.dataset.price;
                    const payType = document
                        .querySelector("[name=pay-type]:checked")
                        .parentElement.querySelector(".radio__text").textContent;

                    // Расчет финальной цены
                    const { price } = (0, _price__WEBPACK_IMPORTED_MODULE_1__.calculateProductsPrice)();

                    const { totalPrice, totalDiscount } = (0, _price__WEBPACK_IMPORTED_MODULE_1__.calculateTotalPrice)(
                        bonusBalls,
                        consumersGroupDiscount,
                        totalSummDiscount,
                        deliveryPrice
                    );

                    const { prevDeliveryPrice, prevProductPrice, prevTotalDiscount } = getPrevData();

                    // Формирование таблиц по шаблону со сводкой данных по заказу
                    // confirmTable.innerHTML = getConfirmTableTemplate(
                    //   deliveryType,
                    //   prevDeliveryPrice,
                    //   payType,
                    //   bonusBalls,
                    //   consumersGroupDiscount,
                    //   price,
                    //   totalSummDiscount
                    // );
                    // orderTable.innerHTML = getOrderTableTemplate(
                    //   deliveryType,
                    //   prevDeliveryPrice,
                    //   payType,
                    //   prevProductPrice,
                    //   prevTotalDiscount
                    // );

                    const dataForUpdate = {
                        // confirmDeliveryPrice: {
                        //     place: document.getElementById("confirm-delivery-price"),
                        //     current: deliveryPrice,
                        // },
                        // // confirmConsumersGroupDiscount: {
                        // //   place: document.getElementById("confirm-consumers-group-discount"),
                        // //   current: consumersGroupDiscount * price,
                        // // },
                        // orderDeliveryPrice: {
                        //     place: document.getElementById("order-delivery-price"),
                        //     current: deliveryPrice,
                        // },
                        // orderProductsPrice: {
                        //     place: document.getElementById("order-products-price"),
                        //     current: price,
                        // },
                        // orderTotalDiscount: {
                        //     place: document.getElementById("order-total-discount"),
                        //     current: totalDiscount,
                        // },
                        // confirmTotalPrice: {
                        //     place: document.querySelector(".mtcheck-total__sum"),
                        //     current: totalPrice,
                        // },
                        // offerTotalPrice: {
                        //     place: document.querySelector(".mtcheck-summary__sum"),
                        //     current: totalPrice,
                        // },
                    };

                    updateTotalData(dataForUpdate);

                    return totalPrice;
                }

                function updateTotalData(data) {
                    for (const key in data) {
                        (0, _utils__WEBPACK_IMPORTED_MODULE_0__.changeValueWithAnimation)(data[key].place, data[key].current);
                    }
                }

                /***/
            },

        /***/ "./scripts/components/validateData.js":
            /*!********************************************!*\
  !*** ./scripts/components/validateData.js ***!
  \********************************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ validateData: () => /* binding */ validateData,
                    /* harmony export */
                });
                /* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils */ "./scripts/utils.js");

                const isEmpty = (elem) => {
                    if (!elem.value) {
                        elem.classList.add("input--danger");
                        (0, _utils__WEBPACK_IMPORTED_MODULE_0__.addWarning)(elem, "Обязательно для заполения");
                        return true;
                    } else {
                        elem.classList.remove("input--danger");
                        (0, _utils__WEBPACK_IMPORTED_MODULE_0__.removeWarning)(elem);
                        return false;
                    }
                };

                const isEqual = (password, elem) => {
                    if (password !== elem.value) {
                        elem.classList.add("input--danger");
                        (0, _utils__WEBPACK_IMPORTED_MODULE_0__.addWarning)(elem, "Пароли не совпадают");
                        return false;
                    } else {
                        elem.classList.remove("input--danger");
                        (0, _utils__WEBPACK_IMPORTED_MODULE_0__.removeWarning)(elem);
                        return true;
                    }
                };

                const validateData = (form) => {
                    let flag = true;
                    let password;

                    const inpupElems = form.querySelectorAll("input");

                    [...inpupElems].forEach((input) => {
                        if (
                            input.name === "middlename" ||
                            input.name === "address_2" ||
                            input.type === "checkbox" ||
                            input.dataset.required == 0
                        ) return;
                        if (isEmpty(input)) return (flag = false);

                        if (input.name === "password") password = input.value;
                        if (input.name === "password-again") {
                            if (!isEqual(password, input)) return (flag = false);
                        }
                    });

                    return flag;
                };

                /***/
            },

        /***/ "./scripts/productMarkup.js":
            /*!**********************************!*\
  !*** ./scripts/productMarkup.js ***!
  \**********************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ productMarkup: () => /* binding */ productMarkup,
                    /* harmony export */
                });
                /* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils */ "./scripts/utils.js");

                const productMarkup = ({
                    id,
                    isDeleted,
                    name,
                    imageUrl,
                    url,
                    properties,
                    count,
                    maxCount,
                    price,
                    discounts,
                    restore,
                    product_id,
                    points,
                    currencyRight,
                    currencyLeft,
                }) =>
                    !isDeleted
                        ? `
      <article class="mtcheck-product" data-id="${id}" data-productid="${product_id}" data-price="${price}" data-restore="${restore}">
        <div class="mtcheck-product__left">
          <label class="mt_checkbox">
            <input type="checkbox" class="visually-hidden checkbox__input" name="product-${id}">
            <span class="checkbox__display"></span>
          </label>
          <a href="${url}" class="mtcheck-product__image">
            <img src="${imageUrl}" alt="Изображение товара">
          </a>
          <div class="mtcheck-product__info">
            <a href="${url}" class="text title--sm text--primary mtcheck-product__name">${name}</a>            
            <div class="mtcheck-product__properties text--xs">
              ${
                  properties
                      ? properties
                            .map(
                                ({ key, value }) => `
                    <span class="text text--xs text--secondary mtcheck-product__property">
                      ${key}: ${value}
                    </span>
                  `
                            )
                            .join("")
                      : ""
              }
			  `+(points > 0 ? `<span class="text text--xs text--secondary mtcheck-product__property">Бонусных очков: ${points}</span>` : ``)+`
              `+discounts+`
            </div>
          </div>
        </div>
        <div class="mtcheck-product__right">
          <div class="mtcheck-product__count">
            <div class="counter">
              <input type="number" value="${count}" data-max="${maxCount}" class="counter__display">
              <input type="button" value="-" min="1" class="mtbtn-reset counter__action counter__action--decrement">
              <input type="button" value="+" class="mtbtn-reset counter__action counter__action--increment">
            </div>
          </div>
          <div class="mtcheck-product__wrapper">
            <div class="text text--md text--primary mtcheck-product__price"><span>${currencyLeft}</span><text class="price">${(0,
                          _utils__WEBPACK_IMPORTED_MODULE_0__.normalizePrice)(price * count)}</text><span>${currencyRight}</span></div>
            <button class="mtbtn-reset mtcheck-product__remove" aria-label="Удалить товар из корзины"></button>
          </div>
        </div>
      </article>
      `
                        : `
      <article class="mtcheck-product mtcheck-product--deleted" data-id="${id}" data-restore="${restore}">
        <div class="mtcheck-product__left">
          <div class="mtcheck-product__info">
            <div class="text mtcheck-product__name">${name}</div>
          </div>
        </div>
        <div class="mtcheck-product__right">
          <button class="mtbtn-reset link mtcheck-product__restore" data-action="product-restore">восстановить</button>
          <div class="mtcheck-product__wrapper">
            <button class="mtbtn-reset mtcheck-product__remove" aria-label="Полностью удалить товар из корзины"></button>
          </div>
        </div>
      </article>
      `;

                /***/
            },

        /***/ "./scripts/storage.js":
            /*!****************************!*\
  !*** ./scripts/storage.js ***!
  \****************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ getProductFromStorage: () => /* binding */ getProductFromStorage,
                    /* harmony export */ getProductsCountFromStorage: () => /* binding */ getProductsCountFromStorage,
                    /* harmony export */ getProductsFromStorage: () => /* binding */ getProductsFromStorage,
                    /* harmony export */ removeProductFromStorage: () => /* binding */ removeProductFromStorage,
                    /* harmony export */ restoreProductFromStorage: () => /* binding */ restoreProductFromStorage,
                    /* harmony export */
                });

                const getProductsFromStorage = () => {
                    return JSON.parse(localStorage.getItem("products"));
                };

                const getProductsCountFromStorage = () =>
                    JSON.parse(localStorage.getItem("products")).filter(({ isDeleted }) => !isDeleted).length;

                const getProductFromStorage = (id) => {
                    const products = getProductsFromStorage();
                    const index = products.findIndex((product) => product.id === id);

                    return products[index];
                };

                const removeProductFromStorage = (id) => {
                    const products = getProductsFromStorage();
                    const index = products.findIndex((product) => product.id === id);

                    const product = products[index];

                    product.isDeleted ? products.splice(index, 1) : (products[index]["isDeleted"] = true);

                    localStorage.setItem("products", JSON.stringify(products));
                };

                const restoreProductFromStorage = (id) => {
                    const products = getProductsFromStorage();
                    const index = products.findIndex((product) => product.id === id);
                    fetch(
                        "/index.php?route=extension/mtcheckout/checkout/restore-cart&quantity=" +
                            products[index]["count"] +
                            "&restore=" +
                            encodeURIComponent(products[index]["restore"])
                    ).then(() => {
                        updateSummary();
                    });
                    products[index]["isDeleted"] = false;

                    localStorage.setItem("products", JSON.stringify(products));
                };
                var startUpdate = false;
                function updateSummary() {
                    if (!startUpdate) {
                        startUpdate = true;
                        $(".mtcheck-product__count *").prop('disabled',true);
                        $('.donut.total').show();
                        $('.donut.payment').show();
                        $('.donut.delivery').show();
                        $("input[name='delivery-type']").prop('disabled',true);
                        $("input[name='pay-type']").prop('disabled',true);
                        updateShipping();
                        // updatePayment();
                        // updateTotal();
                    }
                    
                }

                function updateShipping() {
                    
                    $('input[name="shipping_method"]').val($('input[name="delivery-type"]:checked').data('title'));
                    $('input[name="shipping_code"]').val($('input[name="delivery-type"]:checked').data('code'));
                    $('input[name="shipping_cost"]').val($('input[name="delivery-type"]:checked').val());
                    $('input[name="shipping_tax_class_id"]').val($('input[name="delivery-type"]:checked').data('tax'));
                    $('input[name="payment_code"]').val($('input[name="pay-type"]:checked').val());
                    $('input[name="payment_method"]').val($('input[name="pay-type"]:checked').data('title'));
                    if (!$('.mtcheck-register.mtcheck-address__add-form').hasClass('visually-hidden')) {
                        $('input[name="zone_id"]').val($('.select__item.zone_list.select__item--active').data('value'));
                        $('input[name="zone"]').val($('.display_zone').text());
                        $('input[name="country_id"]').val($('.select__item.country_list.select__item--active').data('value'));
                        $('input[name="country"]').val($('.display_country').text());
                    }
                    var data = 'shipping_method='+$('input[name="shipping_method"]').val()
                                +'&shipping_code='+$('input[name="shipping_code"]').val()
                                +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                                +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val()
                                +'&city='+$('input[name="city"]').val()
                                +'&address_1='+$('input[name="address_1"]').val()
                                +'&postcode='+$('input[name="postcode"]').val()
                                +'&zone_id='+$('input[name="zone_id"]').val()
                                +'&country_id='+$('input[name="country_id"]').val();
                    if ($('input[name="address-type"]:checked').length > 0) {
                        data = 'shipping_method='+$('input[name="shipping_method"]').val()
                                +'&shipping_code='+$('input[name="shipping_code"]').val()
                                +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                                +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val()
                                +'&city='+$('input[name="address-type"]').data('city')
                                +'&address_1='+$('input[name="address-type"]').data('address')
                                +'&postcode='+$('input[name="address-type"]').data('postcode')
                                +'&zone_id='+$('input[name="address-type"]').data('zone-id')
                                +'&country_id='+$('input[name="address-type"]').data('country-id');
                    }
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/checkout/update-shipping',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            
                            $('#shipping_block').html(html.html);
                            setTimeout(updatePayment(), 50);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                        }
                    });
                }
                function updatePayment() {
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/checkout/update-payment',
                        type: 'post',
                        data: 'shipping_method='+$('input[name="shipping_method"]').val()
                            +'&shipping_code='+$('input[name="shipping_code"]').val()
                            +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                            +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val()
                            +'&payment_code='+$('input[name="payment_code"]').val()
                            +'&payment_method='+$('input[name="payment_method"]').val()
                            +'&zone_id='+$('input[name="zone_id"]').val()
                            +'&country_id='+$('input[name="country_id"]').val(),
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            $('#payment_block').html(html.html);
                            setTimeout(updateTotal(), 50);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                        }
                    });
                }
                function updateTotal() {
                    $.ajax({
                        url: 'index.php?route=extension/mtcheckout/checkout/update-total',
                        type: 'post',
                        data: 'shipping_method='+$('input[name="shipping_method"]').val()
                            +'&shipping_code='+$('input[name="shipping_code"]').val()
                            +'&shipping_cost='+$('input[name="shipping_cost"]').val()
                            +'&shipping_tax_class_id='+$('input[name="shipping_tax_class_id"]').val(),
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(html) {
                            
                            $('.table-summary').empty();
                            $('.table-summary').append(html.html);
                            $('.mtcheck-total__sum').html(html.total);
                            $('.shipping_free_text').html(html.free_shipping_text);
                            if (html.free_shipping_text == '') $('.shipping_free_text').hide();
                            else $('.shipping_free_text').show();
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                            $(".mtcheck-product__count *").prop('disabled',false);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                            $('.donut.total').hide();
                            $('.donut.payment').hide();
                            $('.donut.delivery').hide();
                            startUpdate = false;
                            $("input[name='delivery-type']").prop('disabled',false);
                            $("input[name='pay-type']").prop('disabled',false);
                            $(".mtcheck-product__count *").prop('disabled',false);
                        }
                    });
                }
                /***/
            },

        /***/ "./scripts/utils.js":
            /*!**************************!*\
  !*** ./scripts/utils.js ***!
  \**************************/
            /***/ (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
                "use strict";
                __webpack_require__.r(__webpack_exports__);
                /* harmony export */ __webpack_require__.d(__webpack_exports__, {
                    /* harmony export */ addWarning: () => /* binding */ addWarning,
                    /* harmony export */ changeValueWithAnimation: () => /* binding */ changeValueWithAnimation,
                    /* harmony export */ getNumberValue: () => /* binding */ getNumberValue,
                    /* harmony export */ normalizePrice: () => /* binding */ normalizePrice,
                    /* harmony export */ removeWarning: () => /* binding */ removeWarning,
                    /* harmony export */
                });
                /* harmony import */ var _components_message__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(
                    /*! ./components/message */ "./scripts/components/message.js"
                );

                const normalizePrice = (str) => {
                    return str.toFixed(2);
                };

                const getNumberValue = (elem) => +elem.textContent.match(/[0-9]/g).join("");

                function changeValueWithAnimation(place, current, curLeft = '', curRight = '') {
                    let prevPrice = +place.textContent.match(/[0-9]/g).join("");

                    const start = prevPrice;
                    const stop = current;
                    let animationStart;
                    // let requestId = window.requestAnimationFrame(animate);

                    place.childNodes[0].textContent = curLeft+`${(Math.floor(current  * 100) / 100).toFixed(2)}`+curRight;

                    function animate(timestamp) {
                        if (!animationStart) {
                            animationStart = timestamp;
                        }

                        const progress = timestamp - animationStart;

                        const speed = progress * (prevPrice > current ? (start - stop) / 5000 : (stop - start) / 5000);

                        prevPrice = prevPrice > current ? prevPrice - speed : prevPrice + speed;

                        if (start > stop && prevPrice > current) {
                            window.requestAnimationFrame(animate);
                        } else if (start < stop && prevPrice < current) {
                            window.requestAnimationFrame(animate);
                        } else {
                            prevPrice = current;
                            window.cancelAnimationFrame(requestId);
                        }
                        place.childNodes[0].textContent = curLeft+`${normalizePrice(Math.floor(prevPrice)).toFixed(2)}`+curRight;
                    }
                }

                const addWarning = (elem, text) => {
                    (0, _components_message__WEBPACK_IMPORTED_MODULE_0__.createMessage)(
                        {
                            container: elem.parentNode,
                            type: "danger",
                            text,
                            isClose: false,
                        },
                        {}
                    );
                };

                const removeWarning = (elem) => {
                    const message = elem.parentNode.querySelector(".message");
                    if (message) message.remove();
                    return;
                };

                /***/
            },

        /******/
    };
    /************************************************************************/
    /******/ // The module cache
    /******/ var __webpack_module_cache__ = {};
    /******/
    /******/ // The require function
    /******/ function __webpack_require__(moduleId) {
        /******/ // Check if module is in cache
        /******/ var cachedModule = __webpack_module_cache__[moduleId];
        /******/ if (cachedModule !== undefined) {
            /******/ return cachedModule.exports;
            /******/
        }
        /******/ // Create a new module (and put it into the cache)
        /******/ var module = (__webpack_module_cache__[moduleId] = {
            /******/ // no module.id needed
            /******/ // no module.loaded needed
            /******/ exports: {},
            /******/
        });
        /******/
        /******/ // Execute the module function
        /******/ __webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/
        /******/ // Return the exports of the module
        /******/ return module.exports;
        /******/
    }
    /******/
    /************************************************************************/
    /******/ /* webpack/runtime/compat get default export */
    /******/ (() => {
        /******/ // getDefaultExport function for compatibility with non-harmony modules
        /******/ __webpack_require__.n = (module) => {
            /******/ var getter = module && module.__esModule ? /******/ () => module["default"] : /******/ () => module;
            /******/ __webpack_require__.d(getter, { a: getter });
            /******/ return getter;
            /******/
        };
        /******/
    })();
    /******/
    /******/ /* webpack/runtime/define property getters */
    /******/ (() => {
        /******/ // define getter functions for harmony exports
        /******/ __webpack_require__.d = (exports, definition) => {
            /******/ for (var key in definition) {
                /******/ if (__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
                    /******/ Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
                    /******/
                }
                /******/
            }
            /******/
        };
        /******/
    })();
    /******/
    /******/ /* webpack/runtime/hasOwnProperty shorthand */
    /******/ (() => {
        /******/ __webpack_require__.o = (obj, prop) => Object.prototype.hasOwnProperty.call(obj, prop);
        /******/
    })();
    /******/
    /******/ /* webpack/runtime/make namespace object */
    /******/ (() => {
        /******/ // define __esModule on exports
        /******/ __webpack_require__.r = (exports) => {
            /******/ if (typeof Symbol !== "undefined" && Symbol.toStringTag) {
                /******/ Object.defineProperty(exports, Symbol.toStringTag, { value: "Module" });
                /******/
            }
            /******/ Object.defineProperty(exports, "__esModule", { value: true });
            /******/
        };
        /******/
    })();
    /******/
    /************************************************************************/
    var __webpack_exports__ = {};
    // This entry need to be wrapped in an IIFE because it need to be in strict mode.
    (() => {
        "use strict";
        /*!**************************!*\
  !*** ./scripts/index.js ***!
  \**************************/
        __webpack_require__.r(__webpack_exports__);
        /* harmony import */ var _storage__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./storage */ "./scripts/storage.js");
        /* harmony import */ var _components_products__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(
            /*! ./components/products */ "./scripts/components/products.js"
        );
        /* harmony import */ var _components_counter__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(
            /*! ./components/counter */ "./scripts/components/counter.js"
        );
        /* harmony import */ var _components_tabs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(
            /*! ./components/tabs */ "./scripts/components/tabs.js"
        );
        /* harmony import */ var _components_tabs__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/ __webpack_require__.n(
            _components_tabs__WEBPACK_IMPORTED_MODULE_3__
        );
        /* harmony import */ var _components_select__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(
            /*! ./components/select */ "./scripts/components/select.js"
        );
        /* harmony import */ var _components_select__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/ __webpack_require__.n(
            _components_select__WEBPACK_IMPORTED_MODULE_4__
        );
        /* harmony import */ var _components_offers__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(
            /*! ./components/offers */ "./scripts/components/offers.js"
        );
        /* harmony import */ var _components_actions__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(
            /*! ./components/actions */ "./scripts/components/actions.js"
        );
        /* harmony import */ var _components_total__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(
            /*! ./components/total */ "./scripts/components/total.js"
        );
        /* harmony import */ var _components_popup__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(
            /*! ./components/popup */ "./scripts/components/popup.js"
        );
        /* harmony import */ var _components_validateData__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(
            /*! ./components/validateData */ "./scripts/components/validateData.js"
        );
        /* harmony import */ var _components_submitData__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(
            /*! ./components/submitData */ "./scripts/components/submitData.js"
        );
        // Components

        // Select all products option
        const selectAllText = document.querySelector(".mtcheck-products__head .checkbox__text");
        const selectAllCheckbox = document.querySelector("#select-all");

        fetch("/index.php?route=extension/mtcheckout/checkout/products")
            .then((response) => response.json())
            .then((res) => {
                localStorage.setItem("products", JSON.stringify(res.products));
                selectAllText.textContent = `${$('input[name="module_mt_checkout_cart_item_text"]').val()} (${(0, _storage__WEBPACK_IMPORTED_MODULE_0__.getProductsCountFromStorage)()})`;
                selectAllCheckbox.addEventListener("change", toggleSelectAll);
            });

        // If select is already active (from start) - toggle items

        if (selectAllCheckbox.checked) {
            toggleSelectAll();
        }

        function toggleSelectAll() {
            const isChecked = selectAllCheckbox.checked;
            const checkboxes = document.querySelectorAll(".mtcheck-product__left .checkbox__input");

            if (isChecked) {
                checkboxes.forEach((checkbox) => (checkbox.checked = true));
            } else {
                checkboxes.forEach((checkbox) => (checkbox.checked = false));
            }

            (0, _components_actions__WEBPACK_IMPORTED_MODULE_6__.toggleActions)();
            (0, _components_products__WEBPACK_IMPORTED_MODULE_1__.updateProductsSummary)();

            (0, _components_total__WEBPACK_IMPORTED_MODULE_7__.updateTotalResults)();
        }

        // Delivery on change

        const deliveryRadio = document.querySelectorAll("[name=delivery-type]");

        deliveryRadio.forEach((radio) =>
            radio.addEventListener("change", () => {
                (0, _components_total__WEBPACK_IMPORTED_MODULE_7__.updateTotalResults)();
            })
        );

        // Pay on change

        const payRadio = document.querySelectorAll("[name=pay-type]");

        payRadio.forEach((radio) =>
            radio.addEventListener("change", () => {
                (0, _components_total__WEBPACK_IMPORTED_MODULE_7__.updateTotalResults)();
            })
        );

        // Update prices (by selected elements)

        (0, _components_products__WEBPACK_IMPORTED_MODULE_1__.updateProductsSummary)();

        // Update tables (summary / total)

        (0, _components_total__WEBPACK_IMPORTED_MODULE_7__.updateTotalResults)();
    })();

    /******/
})();
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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eEQ7QUFDOEQ7QUFDaEM7QUFDSDtBQUNFO0FBQ0E7QUFDd0I7QUFDRztBQUM1QjtBQUNPO0FBQ0Y7QUFDakM7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSw0Q0FBNEMscUVBQTJCLEdBQUc7QUFDMUU7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxJQUFJO0FBQ0o7QUFDQTtBQUNBO0FBQ0EsRUFBRSxrRUFBYTtBQUNmLEVBQUUsMkVBQXFCO0FBQ3ZCO0FBQ0EsRUFBRSxxRUFBa0I7QUFDcEI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLElBQUkscUVBQWtCO0FBQ3RCLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsSUFBSSxxRUFBa0I7QUFDdEIsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsMkVBQXFCO0FBQ3JCO0FBQ0E7QUFDQTtBQUNBLHFFQUFrQiIsInNvdXJjZXMiOlsid2VicGFjazovL210X2NoZWNrb3V0Ly4vc2NyaXB0cy9jb21wb25lbnRzL2FjdGlvbnMuanMiLCJ3ZWJwYWNrOi8vbXRfY2hlY2tvdXQvLi9zY3JpcHRzL2NvbXBvbmVudHMvY291bnRlci5qcyIsIndlYnBhY2s6Ly9tdF9jaGVja291dC8uL3NjcmlwdHMvY29tcG9uZW50cy9nZXRBbGxEYXRhLmpzIiwid2VicGFjazovL210X2NoZWNrb3V0Ly4vc2NyaXB0cy9jb21wb25lbnRzL21lc3NhZ2UuanMiLCJ3ZWJwYWNrOi8vbXRfY2hlY2tvdXQvLi9zY3JpcHRzL2NvbXBvbmVudHMvb2ZmZXJzLmpzIiwid2VicGFjazovL210X2NoZWNrb3V0Ly4vc2NyaXB0cy9jb21wb25lbnRzL3BvcHVwLmpzIiwid2VicGFjazovL210X2NoZWNrb3V0Ly4vc2NyaXB0cy9jb21wb25lbnRzL3ByaWNlLmpzIiwid2VicGFjazovL210X2NoZWNrb3V0Ly4vc2NyaXB0cy9jb21wb25lbnRzL3Byb2R1Y3RzLmpzIiwid2VicGFjazovL210X2NoZWNrb3V0Ly4vc2NyaXB0cy9jb21wb25lbnRzL3NlbGVjdC5qcyIsIndlYnBhY2s6Ly9tdF9jaGVja291dC8uL3NjcmlwdHMvY29tcG9uZW50cy9zdWJtaXREYXRhLmpzIiwid2VicGFjazovL210X2NoZWNrb3V0Ly4vc2NyaXB0cy9jb21wb25lbnRzL3RhYnMuanMiLCJ3ZWJwYWNrOi8vbXRfY2hlY2tvdXQvLi9zY3JpcHRzL2NvbXBvbmVudHMvdG90YWwuanMiLCJ3ZWJwYWNrOi8vbXRfY2hlY2tvdXQvLi9zY3JpcHRzL2NvbXBvbmVudHMvdmFsaWRhdGVEYXRhLmpzIiwid2VicGFjazovL210X2NoZWNrb3V0Ly4vc2NyaXB0cy9wcm9kdWN0TWFya3VwLmpzIiwid2VicGFjazovL210X2NoZWNrb3V0Ly4vc2NyaXB0cy9zdG9yYWdlLmpzIiwid2VicGFjazovL210X2NoZWNrb3V0Ly4vc2NyaXB0cy91dGlscy5qcyIsIndlYnBhY2s6Ly9tdF9jaGVja291dC93ZWJwYWNrL2Jvb3RzdHJhcCIsIndlYnBhY2s6Ly9tdF9jaGVja291dC93ZWJwYWNrL3J1bnRpbWUvY29tcGF0IGdldCBkZWZhdWx0IGV4cG9ydCIsIndlYnBhY2s6Ly9tdF9jaGVja291dC93ZWJwYWNrL3J1bnRpbWUvZGVmaW5lIHByb3BlcnR5IGdldHRlcnMiLCJ3ZWJwYWNrOi8vbXRfY2hlY2tvdXQvd2VicGFjay9ydW50aW1lL2hhc093blByb3BlcnR5IHNob3J0aGFuZCIsIndlYnBhY2s6Ly9tdF9jaGVja291dC93ZWJwYWNrL3J1bnRpbWUvbWFrZSBuYW1lc3BhY2Ugb2JqZWN0Iiwid2VicGFjazovL210X2NoZWNrb3V0Ly4vc2NyaXB0cy9pbmRleC5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyByZW1vdmVQcm9kdWN0RnJvbVN0b3JhZ2UgfSBmcm9tIFwiLi4vc3RvcmFnZVwiO1xyXG5pbXBvcnQgeyByZW1vdmVQcm9kdWN0RnJvbVBhZ2UsIHVwZGF0ZVByb2R1Y3RzU3VtbWFyeSB9IGZyb20gXCIuL3Byb2R1Y3RzXCI7XHJcbmltcG9ydCB7IHVwZGF0ZVRvdGFsUmVzdWx0cyB9IGZyb20gXCIuL3RvdGFsXCI7XHJcblxyXG5jb25zdCBhY3Rpb25zID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIi5tdGNoZWNrLXByb2R1Y3RzX19hY3Rpb25zXCIpO1xyXG5jb25zdCByZW1vdmVBbGwgPSBhY3Rpb25zLnF1ZXJ5U2VsZWN0b3IoXCIjcmVtb3ZlLWFsbFwiKTtcclxuY29uc3QgYWN0aW9uc0FjdGl2ZUNsYXNzTmFtZSA9IFwibXRjaGVjay1wcm9kdWN0c19fYWN0aW9ucy0tYWN0aXZlXCI7XHJcblxyXG5leHBvcnQgZnVuY3Rpb24gdG9nZ2xlQWN0aW9ucygpIHtcclxuICBjb25zdCBzZWxlY3RlZFByb2R1Y3RzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChcclxuICAgIFwiLm10Y2hlY2stcHJvZHVjdF9fbGVmdCAuY2hlY2tib3hfX2lucHV0OmNoZWNrZWRcIlxyXG4gICk7XHJcblxyXG4gIGlmICghc2VsZWN0ZWRQcm9kdWN0cy5sZW5ndGgpIHtcclxuICAgIHJldHVybiBhY3Rpb25zLmNsYXNzTGlzdC5yZW1vdmUoYWN0aW9uc0FjdGl2ZUNsYXNzTmFtZSk7XHJcbiAgfVxyXG5cclxuICBhY3Rpb25zLmNsYXNzTGlzdC5hZGQoYWN0aW9uc0FjdGl2ZUNsYXNzTmFtZSk7XHJcbn1cclxuXHJcbnJlbW92ZUFsbC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKCkgPT4ge1xyXG4gIGNvbnN0IGNoZWNrYm94ZXMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFxyXG4gICAgXCIubXRjaGVjay1wcm9kdWN0X19sZWZ0IC5jaGVja2JveF9faW5wdXQ6Y2hlY2tlZFwiXHJcbiAgKTtcclxuXHJcbiAgY2hlY2tib3hlcy5mb3JFYWNoKChjaGVja2JveCkgPT4ge1xyXG4gICAgY29uc3QgcHJvZHVjdE5vZGUgPSBjaGVja2JveC5jbG9zZXN0KFwiLm10Y2hlY2stcHJvZHVjdFwiKTtcclxuICAgIGNvbnN0IGlkID0gcHJvZHVjdE5vZGUuZGF0YXNldC5pZDtcclxuXHJcbiAgICByZW1vdmVQcm9kdWN0RnJvbVN0b3JhZ2UoaWQpO1xyXG4gICAgcmVtb3ZlUHJvZHVjdEZyb21QYWdlKHByb2R1Y3ROb2RlLCBpZCk7XHJcbiAgfSk7XHJcblxyXG4gIHVwZGF0ZVByb2R1Y3RzU3VtbWFyeSgpO1xyXG4gIHVwZGF0ZVRvdGFsUmVzdWx0cygpO1xyXG59KTtcclxuIiwiaW1wb3J0IHsgdXBkYXRlUHJvZHVjdHNTdW1tYXJ5IH0gZnJvbSBcIi4vcHJvZHVjdHNcIjtcclxuaW1wb3J0IHsgY2FsY3VsYXRlUHJvZHVjdFByaWNlIH0gZnJvbSBcIi4vcHJpY2VcIjtcclxuaW1wb3J0IHsgdXBkYXRlVG90YWxSZXN1bHRzIH0gZnJvbSBcIi4vdG90YWxcIjtcclxuXHJcbmV4cG9ydCBmdW5jdGlvbiBpbml0aWFsaXplQ291bnRlcihjb3VudGVyKSB7XHJcbiAgaWYgKCFjb3VudGVyKSByZXR1cm47XHJcbiAgY29uc3QgcGFyZW50ID0gY291bnRlci5wYXJlbnRFbGVtZW50O1xyXG5cclxuICBjb25zdCBkaXNwbGF5ID0gY291bnRlci5xdWVyeVNlbGVjdG9yKFwiLmNvdW50ZXJfX2Rpc3BsYXlcIik7XHJcbiAgY29uc3QgaW5jcmVtZW50ID0gY291bnRlci5xdWVyeVNlbGVjdG9yKFwiLmNvdW50ZXJfX2FjdGlvbi0taW5jcmVtZW50XCIpO1xyXG4gIGNvbnN0IGRlY3JlbWVudCA9IGNvdW50ZXIucXVlcnlTZWxlY3RvcihcIi5jb3VudGVyX19hY3Rpb24tLWRlY3JlbWVudFwiKTtcclxuXHJcbiAgY29uc3QgbWF4VmFsdWUgPSArZGlzcGxheS5kYXRhc2V0Lm1heDtcclxuXHJcbiAgY29uc3QgdHlwZXMgPSB7XHJcbiAgICBcIitcIjogZGlzcGxheS5zdGVwVXAuYmluZChkaXNwbGF5KSxcclxuICAgIFwiLVwiOiBkaXNwbGF5LnN0ZXBEb3duLmJpbmQoZGlzcGxheSksXHJcbiAgfTtcclxuXHJcbiAgZGlzcGxheS5hZGRFdmVudExpc3RlbmVyKFwiaW5wdXRcIiwgKGV2ZW50KSA9PiB7XHJcbiAgICBjb25zdCB2YWx1ZSA9ICtldmVudC50YXJnZXQudmFsdWU7XHJcblxyXG4gICAgaWYgKCF2YWx1ZSkge1xyXG4gICAgICByZXR1cm4gKGRpc3BsYXkudmFsdWUgPSAxKTtcclxuICAgIH1cclxuXHJcbiAgICBpc0Vub3VnaChwYXJlbnQsIG1heFZhbHVlLCB2YWx1ZSk7XHJcbiAgICBjYWxjdWxhdGVQcm9kdWN0UHJpY2UoZXZlbnQudGFyZ2V0LmNsb3Nlc3QoXCIubXRjaGVjay1wcm9kdWN0XCIpKTtcclxuICAgIHVwZGF0ZVByb2R1Y3RzU3VtbWFyeSgpO1xyXG5cclxuICAgIHVwZGF0ZVRvdGFsUmVzdWx0cygpO1xyXG4gIH0pO1xyXG4gIFtpbmNyZW1lbnQsIGRlY3JlbWVudF0uZm9yRWFjaCgoYnV0dG9uKSA9PlxyXG4gICAgYnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZXZlbnQpID0+IHtcclxuICAgICAgY29uc3QgdHlwZSA9IGV2ZW50LnRhcmdldC52YWx1ZTtcclxuXHJcbiAgICAgIHR5cGVzW3R5cGVdKCk7XHJcbiAgICAgIGlzRW5vdWdoKHBhcmVudCwgbWF4VmFsdWUsICtkaXNwbGF5LnZhbHVlKTtcclxuICAgICAgY2FsY3VsYXRlUHJvZHVjdFByaWNlKGV2ZW50LnRhcmdldC5jbG9zZXN0KFwiLm10Y2hlY2stcHJvZHVjdFwiKSk7XHJcbiAgICAgIHVwZGF0ZVByb2R1Y3RzU3VtbWFyeSgpO1xyXG5cclxuICAgICAgdXBkYXRlVG90YWxSZXN1bHRzKCk7XHJcbiAgICB9KVxyXG4gICk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIGlzRW5vdWdoKHBhcmVudCwgbWF4VmFsdWUsIGN1cnJlbnRWYWx1ZSkge1xyXG4gIGNvbnN0IGlzU2lnbiA9IHBhcmVudC5xdWVyeVNlbGVjdG9yKFwiLm10Y2hlY2stcHJvZHVjdF9fc2lnblwiKTtcclxuXHJcbiAgaWYgKGN1cnJlbnRWYWx1ZSA8PSBtYXhWYWx1ZSAmJiAhaXNTaWduKSByZXR1cm47XHJcbiAgZWxzZSBpZiAoY3VycmVudFZhbHVlIDw9IG1heFZhbHVlICYmIGlzU2lnbikge1xyXG4gICAgcmV0dXJuIGlzU2lnbi5yZW1vdmUoKTtcclxuICB9XHJcblxyXG4gIC8vIEFkZCBzaWduIG9yIGp1c3Qga2VlcCBpdFxyXG5cclxuICBpZiAoaXNTaWduKSByZXR1cm47XHJcblxyXG4gIGNvbnN0IHNpZ25Ob2RlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInNwYW5cIik7XHJcblxyXG4gIHNpZ25Ob2RlLmNsYXNzTGlzdC5hZGQoXCJ0ZXh0XCIsIFwidGV4dC0teHNcIiwgXCJtdGNoZWNrLXByb2R1Y3RfX3NpZ25cIik7XHJcbiAgc2lnbk5vZGUudGV4dENvbnRlbnQgPSBcItC90LXRgiDQsiDQstGL0LHRgNCw0L3QvdC+0Lwg0LrQvtC70LjRh9C10YHRgtCy0LVcIjtcclxuXHJcbiAgcGFyZW50Lmluc2VydEFkamFjZW50RWxlbWVudChcImJlZm9yZWVuZFwiLCBzaWduTm9kZSk7XHJcbn1cclxuIiwiZXhwb3J0IGNvbnN0IGdldEFsbERhdGEgPSAoZm9ybSkgPT4ge1xyXG4gIGNvbnN0IGRhdGEgPSB7fTtcclxuICBjb25zdCBpbnB1dEVsZW1zID0gZm9ybS5xdWVyeVNlbGVjdG9yQWxsKFwiaW5wdXRcIik7XHJcblxyXG4gIGxldCBpbnB1dHNWYWx1ZXMgPSBbLi4uaW5wdXRFbGVtc10ubWFwKChpbnB1dCkgPT4ge1xyXG4gICAgcmV0dXJuIHtcclxuICAgICAgbmFtZTogaW5wdXQubmFtZSxcclxuICAgICAgdmFsdWU6IGlucHV0LnZhbHVlLFxyXG4gICAgfTtcclxuICB9KTtcclxuXHJcbiAgaW5wdXRzVmFsdWVzID0gaW5wdXRzVmFsdWVzLmZpbHRlcigoaW5wdXQpID0+IGlucHV0KTtcclxuXHJcbiAgY29uc29sZS5sb2coaW5wdXRzVmFsdWVzKTtcclxufTtcclxuIiwiZXhwb3J0IGZ1bmN0aW9uIGNyZWF0ZU1lc3NhZ2UoeyBjb250YWluZXIsIHR5cGUsIHRleHQsIGlzQ2xvc2UgfSwgc3R5bGVzKSB7XHJcbiAgLy8gUmVtb3ZlIG9sZCBtZXNzYWdlIChpZiBpdCdzIGF2YWlibGUpXHJcblxyXG4gIGNvbnN0IG9sZE1lc3NhZ2UgPSBjb250YWluZXIucXVlcnlTZWxlY3RvcihcIi5tZXNzYWdlXCIpXHJcblxyXG4gIGlmIChvbGRNZXNzYWdlKSBvbGRNZXNzYWdlLnJlbW92ZSgpXHJcblxyXG4gIC8vIENyZWF0ZSBtZXNzYWdlXHJcblxyXG4gIGNvbnN0IG1lc3NhZ2UgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpXHJcblxyXG4gIG1lc3NhZ2UuY2xhc3NMaXN0LmFkZChcIm1lc3NhZ2VcIiwgYG1lc3NhZ2UtLSR7dHlwZX1gKVxyXG4gIG1lc3NhZ2UudGV4dENvbnRlbnQgPSB0ZXh0XHJcbiAgXHJcbiAgT2JqZWN0LmVudHJpZXMoc3R5bGVzKS5mb3JFYWNoKChba2V5LCB2YWx1ZV0pID0+IHtcclxuICAgIG1lc3NhZ2Uuc3R5bGVba2V5XSA9IHZhbHVlXHJcbiAgfSlcclxuXHJcbiAgaWYgKGlzQ2xvc2UpIHtcclxuICAgIGNvbnN0IGNsb3NlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImJ1dHRvblwiKVxyXG5cclxuICAgIGNsb3NlLmNsYXNzTGlzdC5hZGQoXCJtZXNzYWdlX19jbG9zZVwiKVxyXG4gICAgY2xvc2UuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsICgpID0+IG1lc3NhZ2UucmVtb3ZlKCkpXHJcblxyXG4gICAgY2xvc2Uuc2V0QXR0cmlidXRlKFwidHlwZVwiLCBcImJ1dHRvblwiKVxyXG4gICAgY2xvc2Uuc2V0QXR0cmlidXRlKFwiYXJpYS1sYWJlbFwiLCBcItCX0LDQutGA0YvRgtGMINCy0YHQv9GL0LLQsNGO0YnQtdC1INGB0L7QvtCx0YnQtdC90LjQtVwiKVxyXG5cclxuICAgIG1lc3NhZ2UuYXBwZW5kQ2hpbGQoY2xvc2UpXHJcbiAgfVxyXG5cclxuICBjb250YWluZXIuYXBwZW5kQ2hpbGQobWVzc2FnZSlcclxufVxyXG4iLCJpbXBvcnQgeyBjcmVhdGVNZXNzYWdlIH0gZnJvbSBcIi4vbWVzc2FnZVwiO1xyXG5pbXBvcnQgeyB1cGRhdGVUb3RhbFJlc3VsdHMgfSBmcm9tIFwiLi90b3RhbFwiO1xyXG5cclxuY29uc3QgY291cG9uRm9ybSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCIubXRjaGVjay1vZmZlcnNfX2NvdXBvblwiKTtcclxuY29uc3QgY2VydGlmaWNhdGVGb3JtID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIi5tdGNoZWNrLW9mZmVyc19fY2VydGlmaWNhdGVcIik7XHJcblxyXG5sZXQgbWVzc2FnZVN0eWxlcyA9IHtcclxuICBtYXJnaW46IFwiMFwiLFxyXG4gIHRvcDogXCIwXCIsXHJcbiAgcmlnaHQ6IFwiLTIwcHhcIixcclxuICBtYXhXaWR0aDogXCIzMjJweFwiLFxyXG4gIHRyYW5zZm9ybTogXCJ0cmFuc2xhdGVYKDEwMCUpXCIsXHJcbn07XHJcblxyXG5pZiAod2luZG93Lm1hdGNoTWVkaWEoXCIobWF4LXdpZHRoOiAxMDIzcHgpXCIpLm1hdGNoZXMpIHtcclxuICBtZXNzYWdlU3R5bGVzID0ge1xyXG4gICAgYm90dG9tOiBcIi0xMHB4XCIsXHJcbiAgICBsZWZ0OiBcIjBcIixcclxuICAgIG1heFdpZHRoOiBcIjMyMnB4XCIsXHJcbiAgICB0cmFuc2Zvcm06IFwidHJhbnNsYXRlWSgxMDAlKVwiLFxyXG4gIH07XHJcbn1cclxuXHJcbmNvdXBvbkZvcm0uYWRkRXZlbnRMaXN0ZW5lcihcInN1Ym1pdFwiLCAoZXZlbnQpID0+IHtcclxuICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG5cclxuICBjb25zdCBjb3Vwb24gPSB7IHRleHQ6IFwiQUFBMTExXCIsIHZhbHVlOiA1MDAwIH07XHJcbiAgY29uc3QgZW50cnlDb3Vwb24gPSBldmVudC50YXJnZXQucXVlcnlTZWxlY3RvcihcIlt0eXBlPXRleHRdXCIpLnZhbHVlO1xyXG5cclxuICAvLyAqINCX0LTQtdGB0Ywg0YHQtNC10LvQsNC9INC00LvRjyDQv9GA0LjQvNC10YDQsCDQutGD0L/QvtC9LiDQn9C+INC40LTQtdC1LCDQtNC+0LvQttC10L0g0LHRi9GC0Ywg0LfQsNC/0YDQvtGBINC90LAg0LHQsNC30YMg0LTQsNC90L3Ri9GFINC4INGC0LAg0LbQtSDQv9GA0L7QstC10YDQutCwKVxyXG5cclxuICBpZiAoY291cG9uLnRleHQgPT09IGVudHJ5Q291cG9uKSB7XHJcbiAgICBjcmVhdGVNZXNzYWdlKFxyXG4gICAgICB7XHJcbiAgICAgICAgY29udGFpbmVyOiBjb3Vwb25Gb3JtLFxyXG4gICAgICAgIHR5cGU6IFwic3VjY2Vzc1wiLFxyXG4gICAgICAgIHRleHQ6IGDQmtGD0L/QvtC9ICR7Y291cG9uLnRleHR9INC90LAg0YHRg9C80LzRgyAke2NvdXBvbi52YWx1ZX3QoCDRg9GB0L/QtdGI0L3QviDQuNGB0L/QvtC70YzQt9C+0LLQsNC9IWAsXHJcbiAgICAgICAgaXNDbG9zZTogdHJ1ZSxcclxuICAgICAgfSxcclxuICAgICAgbWVzc2FnZVN0eWxlc1xyXG4gICAgKTtcclxuXHJcbiAgICB3aW5kb3cuY291cG9uID0gY291cG9uO1xyXG5cclxuICAgIHVwZGF0ZVRvdGFsUmVzdWx0cygpO1xyXG4gIH0gZWxzZSBpZiAoZW50cnlDb3Vwb24gPT09IFwiXCIpIHtcclxuICAgIGNyZWF0ZU1lc3NhZ2UoXHJcbiAgICAgIHtcclxuICAgICAgICBjb250YWluZXI6IGNvdXBvbkZvcm0sXHJcbiAgICAgICAgdHlwZTogXCJkYW5nZXJcIixcclxuICAgICAgICB0ZXh0OiBg0J/QvtC70LUg0LTQvtC70LbQvdC+INCx0YvRgtGMINC30LDQv9C+0LvQvdC10L3QvmAsXHJcbiAgICAgICAgaXNDbG9zZTogdHJ1ZSxcclxuICAgICAgfSxcclxuICAgICAgbWVzc2FnZVN0eWxlc1xyXG4gICAgKTtcclxuICB9IGVsc2Uge1xyXG4gICAgY3JlYXRlTWVzc2FnZShcclxuICAgICAge1xyXG4gICAgICAgIGNvbnRhaW5lcjogY291cG9uRm9ybSxcclxuICAgICAgICB0eXBlOiBcIndhcm5pbmdcIixcclxuICAgICAgICB0ZXh0OiBg0JrRg9C/0L7QvSDQvdC1INC90LDQudC00LXQvSDQuNC70Lgg0YPQttC1INC90LUg0LDQutGC0LjQstC10L1gLFxyXG4gICAgICAgIGlzQ2xvc2U6IHRydWUsXHJcbiAgICAgIH0sXHJcbiAgICAgIG1lc3NhZ2VTdHlsZXNcclxuICAgICk7XHJcbiAgfVxyXG59KTtcclxuXHJcbmNlcnRpZmljYXRlRm9ybS5hZGRFdmVudExpc3RlbmVyKFwic3VibWl0XCIsIChldmVudCkgPT4ge1xyXG4gIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcblxyXG4gIGNvbnN0IGNlcnRpZmljYXRlID0geyB0ZXh0OiBcIkFBQTExMVwiLCB2YWx1ZTogNzAwMCB9O1xyXG4gIGNvbnN0IGVudHJ5Q2VydGlmaWNhdGUgPSBldmVudC50YXJnZXQucXVlcnlTZWxlY3RvcihcIlt0eXBlPXRleHRdXCIpLnZhbHVlO1xyXG5cclxuICAvLyAqINCX0LTQtdGB0Ywg0YHQtNC10LvQsNC9INC00LvRjyDQv9GA0LjQvNC10YDQsCDRgdC10YDRgtC40YTQuNC60LDRgi4g0J/QviDQuNC00LXQtSwg0LTQvtC70LbQtdC9INCx0YvRgtGMINC30LDQv9GA0L7RgSDQvdCwINCx0LDQt9GDINC00LDQvdC90YvRhSDQuCDRgtCwINC20LUg0L/RgNC+0LLQtdGA0LrQsClcclxuXHJcbiAgaWYgKGNlcnRpZmljYXRlLnRleHQgPT09IGVudHJ5Q2VydGlmaWNhdGUpIHtcclxuICAgIGNyZWF0ZU1lc3NhZ2UoXHJcbiAgICAgIHtcclxuICAgICAgICBjb250YWluZXI6IGNlcnRpZmljYXRlRm9ybSxcclxuICAgICAgICB0eXBlOiBcInN1Y2Nlc3NcIixcclxuICAgICAgICB0ZXh0OiBg0KHQtdGA0YLQuNGE0LjQutCw0YIgJHtjZXJ0aWZpY2F0ZS50ZXh0fSDQvdCwINGB0YPQvNC80YMgJHtjZXJ0aWZpY2F0ZS52YWx1ZX3QoCDRg9GB0L/QtdGI0L3QviDQuNGB0L/QvtC70YzQt9C+0LLQsNC9IWAsXHJcbiAgICAgICAgaXNDbG9zZTogdHJ1ZSxcclxuICAgICAgfSxcclxuICAgICAgbWVzc2FnZVN0eWxlc1xyXG4gICAgKTtcclxuXHJcbiAgICB3aW5kb3cuY2VydGlmaWNhdGUgPSBjZXJ0aWZpY2F0ZTtcclxuXHJcbiAgICB1cGRhdGVUb3RhbFJlc3VsdHMoKTtcclxuICB9IGVsc2UgaWYgKGVudHJ5Q2VydGlmaWNhdGUgPT09IFwiXCIpIHtcclxuICAgIGNyZWF0ZU1lc3NhZ2UoXHJcbiAgICAgIHtcclxuICAgICAgICBjb250YWluZXI6IGNlcnRpZmljYXRlRm9ybSxcclxuICAgICAgICB0eXBlOiBcImRhbmdlclwiLFxyXG4gICAgICAgIHRleHQ6IGDQn9C+0LvQtSDQtNC+0LvQttC90L4g0LHRi9GC0Ywg0LfQsNC/0L7Qu9C90LXQvdC+YCxcclxuICAgICAgICBpc0Nsb3NlOiB0cnVlLFxyXG4gICAgICB9LFxyXG4gICAgICBtZXNzYWdlU3R5bGVzXHJcbiAgICApO1xyXG4gIH0gZWxzZSB7XHJcbiAgICBjcmVhdGVNZXNzYWdlKFxyXG4gICAgICB7XHJcbiAgICAgICAgY29udGFpbmVyOiBjZXJ0aWZpY2F0ZUZvcm0sXHJcbiAgICAgICAgdHlwZTogXCJ3YXJuaW5nXCIsXHJcbiAgICAgICAgdGV4dDogYNCa0YPQv9C+0L0g0L3QtSDQvdCw0LnQtNC10L0g0LjQu9C4INGD0LbQtSDQvdC1INCw0LrRgtC40LLQtdC9YCxcclxuICAgICAgICBpc0Nsb3NlOiB0cnVlLFxyXG4gICAgICB9LFxyXG4gICAgICBtZXNzYWdlU3R5bGVzXHJcbiAgICApO1xyXG4gIH1cclxufSk7XHJcbiIsImltcG9ydCB7IGdldE51bWJlclZhbHVlLCBub3JtYWxpemVQcmljZSB9IGZyb20gXCIuLi91dGlsc1wiO1xyXG5cclxuY29uc3QgcG9wdXAgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiLm10Y2hlY2stcG9wdXBcIik7XHJcbmNvbnN0IHBvcHVwQm9keSA9IHBvcHVwLnF1ZXJ5U2VsZWN0b3IoXCIubXRjaGVjay1wb3B1cF9fYm9keVwiKTtcclxuXHJcbmNvbnN0IGdldFF1aWNrT3JkZXJEYXRhID0gKCkgPT4ge1xyXG4gIHJldHVybiBgXHJcbiAgICAgICAgICAgIDxoMSBjbGFzcz1cInRpdGxlIHRpdGxlLS1tZCB0aXRsZS0tcHJpbWFyeSBtdGNoZWNrLXBvcHVwX190aXRsZVwiPtCR0YvRgdGC0YDRi9C5INC30LDQutCw0Lcg4oCUPC9oMT5cclxuICAgICAgICAgICAgPHAgY2xhc3M9XCJ0ZXh0IHRleHQtLXhzIHRleHQtLXNlY29uZGFyeSBtdGNoZWNrLXBvcHVwX190ZXh0XCI+0JLQsNGIINC30LDQutCw0Lcg0LHRg9C00LXRgiDQvtGC0L/RgNCw0LLQu9C10L0g0L3QsNGI0LjQvCDQvNC10L3QtdC00LbQtdGA0LDQvCDQsdC10Lcg0YDQtdCz0LjRgdGC0YDQsNGG0LjQuC48YnI+XHJcbiAgICAgICAgICAgINCc0Ysg0YHRgNCw0LfRgyDQttC1INGB0LLRj9C20LXQvNGB0Y8g0YEg0LLQsNC80LghXHJcbiAgICAgICAgICAgIDwvcD5cclxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cIm10Y2hlY2stcG9wdXBfX2NvbHVtbnNcIj5cclxuICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwibXRjaGVjay1wb3B1cF9fbGVmdFwiPlxyXG4gICAgICAgICAgICAgICAgPGxhYmVsIGNsYXNzPVwiaW5wdXQtLWxhYmVsXCI+PGlucHV0IHR5cGU9XCJ0ZXh0XCIgY2xhc3M9XCJpbnB1dCBpbnB1dC0tbWluXCIgbmFtZT1cImZpcnN0TmFtZVwiIHBsYWNlaG9sZGVyPVwi0JjQvNGPXCI+PC9sYWJlbD5cclxuICAgICAgICAgICAgICAgIDxsYWJlbCBjbGFzcz1cImlucHV0LS1sYWJlbFwiPjxpbnB1dCB0eXBlPVwibnVtYmVyXCIgY2xhc3M9XCJpbnB1dCBpbnB1dC0tbWluXCIgbmFtZT1cInBob25lXCIgcGxhY2Vob2xkZXI9XCLQotC10LvQtdGE0L7QvVwiPjwvbGFiZWw+XHJcbiAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cIm10Y2hlY2stcG9wdXBfX3JpZ2h0XCI+XHJcbiAgICAgICAgICAgICAgICA8cCBjbGFzcz1cInRpdGxlIHRpdGxlLS1zbSB0aXRsZS0tcHJpbWFyeSBtdGNoZWNrLXBvcHVwX19jb3VudFwiPiR7XHJcbiAgICAgICAgICAgICAgICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCIubXRjaGVjay1wcm9kdWN0c19fY291bnRcIikudGV4dENvbnRlbnRcclxuICAgICAgICAgICAgICAgIH08L3A+XHJcbiAgICAgICAgICAgICAgICA8cCBjbGFzcz1cInRpdGxlIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3N1bVwiPiR7bm9ybWFsaXplUHJpY2UoXHJcbiAgICAgICAgICAgICAgICAgIGdldE51bWJlclZhbHVlKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCIubXRjaGVjay10b3RhbF9fc3VtXCIpKVxyXG4gICAgICAgICAgICAgICAgKX08c3Bhbj7igr08L3NwYW4+PC9wPlxyXG4gICAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgPHRleHRhcmVhIGNsYXNzPVwidGV4dGFyZWEgbXRjaGVjay1wb3B1cF9fY29tbWVudFwiIG5hbWU9XCJ0ZXh0XCIgcGxhY2Vob2xkZXI9XCLQmtC+0LzQvNC10L3RgtCw0YDQuNC5INC6INC30LDQutCw0LfRgywg0L/QvtC20LXQu9Cw0L3QuNGPLCDRg9GC0L7Rh9C90LXQvdC40Y8g0Lgg0YIu0L8uXCIgY29scz1cIjMwXCIgcm93cz1cIjZcIj48L3RleHRhcmVhPlxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwibXRjaGVjay1wb3B1cF9fc3VibWl0LWJsb2NrXCI+XHJcbiAgICAgICAgICAgICAgPGxhYmVsIGNsYXNzPVwiY2hlY2tib3ggY2hlY2tib3gtLXNtIGNoZWNrYm94LS1ibHVlIG10Y2hlY2stcG9wdXBfX2NoZWNrYm94XCI+XHJcbiAgICAgICAgICAgICAgICA8aW5wdXQgdHlwZT1cImNoZWNrYm94XCIgY2xhc3M9XCJ2aXN1YWxseS1oaWRkZW4gY2hlY2tib3hfX2lucHV0XCIgcmVxdWlyZWQ9XCJcIiBjaGVja2VkPVwiXCI+XHJcbiAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImNoZWNrYm94X19kaXNwbGF5XCI+PC9zcGFuPlxyXG4gICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJjaGVja2JveF9fdGV4dFwiPtC+0YLQv9GA0LDQstC70Y/RjyDQtNCw0L3QvdGL0LUg0Y8g0LTQsNGOINGB0L7Qs9C70LDRgdC40LUg0L3QsCDQvtCx0YDQsNCx0L7RgtC60YMg0L/QtdGA0YHQvtC90LDQu9GM0L3QvtC5INC40L3RhNC+0YDQvNCw0YbQuNC4INGB0L7Qs9C70LDRgdC90L4gPGEgaHJlZj1cIiNcIj7Qv9C+0LvQuNGC0LjQutC4INC60L7QvdGE0LjQtNC10L3RhtC40LDQu9GM0L3QvtGB0YLQuDwvYT4g0LTQsNC90L3QvtCz0L4g0YHQsNC50YLQsDwvc3Bhbj5cclxuICAgICAgICAgICAgICA8L2xhYmVsPlxyXG4gICAgICAgICAgICAgICAgPGJ1dHRvbiB0eXBlPVwic3VibWl0XCIgY2xhc3M9XCJidG4tcmVzZXQgYnRuIGJ0bi0tYmx1ZSBtdGNoZWNrLXBvcHVwX19idXR0b25cIj5cclxuICAgICAgICAgICAgICAgICAgPHNwYW4+0J7RgtC/0YDQsNCy0LjRgtGMPC9zcGFuPlxyXG4gICAgICAgICAgICAgICAgPC9idXR0b24+XHJcbiAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgYDtcclxufTtcclxuXHJcbmNvbnN0IGdldE1vZGFsRGF0YSA9ICh0eXBlKSA9PiB7XHJcbiAgc3dpdGNoICh0eXBlKSB7XHJcbiAgICBjYXNlIFwidXNlci1kYXRhXCI6XHJcbiAgICAgIHJldHVybiBgPGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+PGgxIGNsYXNzPVwidGl0bGUgdGl0bGUtLW1kIHRpdGxlLS1wcmltYXJ5IG10Y2hlY2stcG9wdXBfX3RpdGxlXCI+0JjQvdGE0L7RgNC80LDRhtC40Y8g0LTQu9GPINC80L7QtNCw0LvRjNC90L7Qs9C+INC+0LrQvdCwINCU0LDQvdC90YvQtSDQv9C+0LrRg9C/0LDRgtC10LvRjzwvaDE+YDtcclxuICAgIGNhc2UgXCJkZWxpdmVyeVwiOlxyXG4gICAgICByZXR1cm4gYDxoMSBjbGFzcz1cInRpdGxlIHRpdGxlLS1tZCB0aXRsZS0tcHJpbWFyeSBtdGNoZWNrLXBvcHVwX190aXRsZVwiPtCY0L3RhNC+0YDQvNCw0YbQuNGPINC00LvRjyDQvNC+0LTQsNC70YzQvdC+0LPQviDQvtC60L3QsCDQlNC+0YHRgtCw0LLQutCwPC9oMT5gO1xyXG4gICAgY2FzZSBcInBheW1lbnRcIjpcclxuICAgICAgcmV0dXJuIGA8aDEgY2xhc3M9XCJ0aXRsZSB0aXRsZS0tbWQgdGl0bGUtLXByaW1hcnkgbXRjaGVjay1wb3B1cF9fdGl0bGVcIj7QmNC90YTQvtGA0LzQsNGG0LjRjyDQtNC70Y8g0LzQvtC00LDQu9GM0L3QvtCz0L4g0L7QutC90LAg0J7Qv9C70LDRgtCwPC9oMT5gO1xyXG4gICAgY2FzZSBcImNvbmZpcm0tb3JkZXJcIjpcclxuICAgICAgcmV0dXJuIGA8aDEgY2xhc3M9XCJ0aXRsZSB0aXRsZS0tbWQgdGl0bGUtLXByaW1hcnkgbXRjaGVjay1wb3B1cF9fdGl0bGVcIj7QmNC90YTQvtGA0LzQsNGG0LjRjyDQtNC70Y8g0LzQvtC00LDQu9GM0L3QvtCz0L4g0L7QutC90LAg0J/QvtC00YLQstC10YDQttC00LXQvdC40LUg0L7Qv9C70LDRgtGLPC9oMT5gO1xyXG4gICAgY2FzZSBcInlvdXItb3JkZXJcIjpcclxuICAgICAgcmV0dXJuIGA8aDEgY2xhc3M9XCJ0aXRsZSB0aXRsZS0tbWQgdGl0bGUtLXByaW1hcnkgbXRjaGVjay1wb3B1cF9fdGl0bGVcIj7QmNC90YTQvtGA0LzQsNGG0LjRjyDQtNC70Y8g0LzQvtC00LDQu9GM0L3QvtCz0L4g0L7QutC90LAg0JLQsNGIINC30LDQutCw0Lc8L2gxPmA7XHJcblxyXG4gICAgZGVmYXVsdDpcclxuICAgICAgYnJlYWs7XHJcbiAgfVxyXG59O1xyXG5cclxuZXhwb3J0IGNvbnN0IGdldFN1Y2Nlc3NmdWxTdWJtaXREYXRhID0gKCkgPT4ge1xyXG4gIHJldHVybiBgXHJcbiAgICA8ZGl2IGNsYXNzPVwibXRjaGVjay1wb3B1cF9fc3VjY2Vzcy1zdWJtaXRcIj5cclxuICAgICAgICA8aDEgY2xhc3M9XCJ0aXRsZSB0aXRsZS0tbWQgdGl0bGUtLXByaW1hcnkgbXRjaGVjay1wb3B1cF9fdGl0bGVcIj7QktCw0YjQtSDRgdC+0L7QsdGJ0LXQvdC40LUg0L7RgtC/0YDQsNCy0LvQtdC90L4hPC9oMT5cclxuICAgICAgICA8cD7QodC+0L7QsdGJ0LXQvdC40LUg0YPRgdC/0LXRiNC90L4g0L7RgtC/0YDQsNCy0LvQtdC90L4uPGJyPtCc0Ysg0YHQutC+0YDQviDRgdCy0Y/QttC10LzRgdGPINGBINCy0LDQvNC4ITwvcD5cclxuICAgIDwvZGl2PlxyXG4gICAgYDtcclxufTtcclxuXHJcbmV4cG9ydCBjb25zdCB0b2dnbGVQb3B1cCA9ICgpID0+IHtcclxuICBwb3B1cC5jbGFzc0xpc3QudG9nZ2xlKFwiaXMtb3BlblwiKTtcclxufTtcclxuXHJcbnBvcHVwLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gIGNvbnN0IHRhcmdldCA9IGUudGFyZ2V0O1xyXG5cclxuICBpZiAoXHJcbiAgICB0YXJnZXQuY2xvc2VzdChcIi5tdGNoZWNrLXBvcHVwX19jbG9zZVwiKSB8fFxyXG4gICAgKHRhcmdldC5jbG9zZXN0KFwiLm10Y2hlY2stcG9wdXBcIikgJiZcclxuICAgICAgIXRhcmdldC5jbG9zZXN0KFwiLm10Y2hlY2stcG9wdXBfX2JvZHlcIikpXHJcbiAgKSB7XHJcbiAgICBwb3B1cEJvZHkuaW5uZXJIVE1MID0gXCJcIjtcclxuICAgIHRvZ2dsZVBvcHVwKCk7XHJcbiAgfVxyXG59KTtcclxuXHJcbmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gIGNvbnN0IHRhcmdldCA9IGUudGFyZ2V0O1xyXG5cclxuICBpZiAoXHJcbiAgICB0YXJnZXQuY2xvc2VzdChcIiNidXktZXZlcnl0aGluZ1wiKSB8fFxyXG4gICAgdGFyZ2V0LmNsb3Nlc3QoXCIubXRjaGVjay1zdWJtaXRfX2J5LXBob25lXCIpIHx8XHJcbiAgICB0YXJnZXQuY2xvc2VzdChcIi5tdGNoZWNrLXVzZXJfX2J1eVwiKVxyXG4gICkge1xyXG4gICAgcG9wdXBCb2R5LmlubmVySFRNTCA9IGdldFF1aWNrT3JkZXJEYXRhKCk7XHJcbiAgICB0b2dnbGVQb3B1cCgpO1xyXG4gIH1cclxuXHJcbiAgaWYgKHRhcmdldC5jbG9zZXN0KFwiLmluZm8tYnRuXCIpKSB7XHJcbiAgICBwb3B1cEJvZHkuaW5uZXJIVE1MID0gZ2V0TW9kYWxEYXRhKHRhcmdldC5kYXRhc2V0Lm1vZGFsKTtcclxuICAgIHRvZ2dsZVBvcHVwKCk7XHJcbiAgfVxyXG59KTtcclxuIiwiaW1wb3J0IHsgY2hhbmdlVmFsdWVXaXRoQW5pbWF0aW9uIH0gZnJvbSBcIi4uL3V0aWxzXCI7XHJcblxyXG5leHBvcnQgZnVuY3Rpb24gY2FsY3VsYXRlUHJvZHVjdHNQcmljZSgpIHtcclxuICBjb25zdCBwcm9kdWN0cyA9IFtcclxuICAgIC4uLmRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCIubXRjaGVjay1wcm9kdWN0IC5jaGVja2JveF9faW5wdXQ6Y2hlY2tlZFwiKSxcclxuICBdO1xyXG4gIGNvbnN0IHByaWNlID0gcHJvZHVjdHMucmVkdWNlKChwcmV2aW91c1ZhbHVlLCBjdXJyZW50Tm9kZSkgPT4ge1xyXG4gICAgY29uc3QgcHJvZHVjdCA9IGN1cnJlbnROb2RlLmNsb3Nlc3QoXCIubXRjaGVjay1wcm9kdWN0XCIpO1xyXG4gICAgY29uc3QgY291bnQgPSBwcm9kdWN0LnF1ZXJ5U2VsZWN0b3IoXCIuY291bnRlcl9fZGlzcGxheVwiKS52YWx1ZTtcclxuICAgIGNvbnN0IHByaWNlID0gcHJvZHVjdC5kYXRhc2V0LnByaWNlO1xyXG5cclxuICAgIHJldHVybiAocHJldmlvdXNWYWx1ZSArPSBjb3VudCAqIHByaWNlKTtcclxuICB9LCAwKTtcclxuXHJcbiAgcmV0dXJuIHsgcHJpY2UsIGNvdW50OiBwcm9kdWN0cy5sZW5ndGggfTtcclxufVxyXG5cclxuZXhwb3J0IGZ1bmN0aW9uIGNhbGN1bGF0ZVByb2R1Y3RQcmljZShwcm9kdWN0KSB7XHJcbiAgY29uc3QgZGlzcGxheSA9IHByb2R1Y3QucXVlcnlTZWxlY3RvcihcIi5tdGNoZWNrLXByb2R1Y3RfX3ByaWNlXCIpO1xyXG4gIGNvbnN0IGNvdW50ID0gcHJvZHVjdC5xdWVyeVNlbGVjdG9yKFwiLmNvdW50ZXJfX2Rpc3BsYXlcIikudmFsdWU7XHJcbiAgY29uc3QgcHJpY2UgPSBwcm9kdWN0LmRhdGFzZXQucHJpY2U7XHJcblxyXG4gIGNoYW5nZVZhbHVlV2l0aEFuaW1hdGlvbihkaXNwbGF5LCBwcmljZSAqIGNvdW50KTtcclxufVxyXG5cclxuZXhwb3J0IGZ1bmN0aW9uIGNhbGN1bGF0ZVRvdGFsUHJpY2UoXHJcbiAgYm9udXNCYWxscyxcclxuICBjb25zdW1lcnNHcm91cERpc2NvdW50LFxyXG4gIHRvdGFsU3VtbURpc2NvdW50LFxyXG4gIGRlbGl2ZXJ5UHJpY2VcclxuKSB7XHJcbiAgbGV0IHRvdGFsUHJpY2U7XHJcbiAgbGV0IHRvdGFsRGlzY291bnQ7XHJcblxyXG4gIGNvbnN0IHsgcHJpY2UgfSA9IGNhbGN1bGF0ZVByb2R1Y3RzUHJpY2UoKTtcclxuXHJcbiAgY29uc3QgY291cG9uVmFsdWUgPSB3aW5kb3cuY291cG9uID8gd2luZG93LmNvdXBvbi52YWx1ZSA6IDA7XHJcbiAgY29uc3QgY2VydGlmaWNhdGVWYWx1ZSA9IHdpbmRvdy5jZXJ0aWZpY2F0ZSA/IHdpbmRvdy5jZXJ0aWZpY2F0ZS52YWx1ZSA6IDA7XHJcblxyXG4gIHRvdGFsRGlzY291bnQgPVxyXG4gICAgYm9udXNCYWxscyArXHJcbiAgICBjb25zdW1lcnNHcm91cERpc2NvdW50ICogcHJpY2UgK1xyXG4gICAgdG90YWxTdW1tRGlzY291bnQgKiBwcmljZSArXHJcbiAgICBjb3Vwb25WYWx1ZSArXHJcbiAgICBjZXJ0aWZpY2F0ZVZhbHVlO1xyXG5cclxuICB0b3RhbFByaWNlID0gcHJpY2UgKyBkZWxpdmVyeVByaWNlIC0gdG90YWxEaXNjb3VudDtcclxuXHJcbiAgcmV0dXJuIHsgdG90YWxQcmljZSwgdG90YWxEaXNjb3VudCB9O1xyXG59XHJcbiIsImltcG9ydCB7IHByb2R1Y3RNYXJrdXAgfSBmcm9tIFwiLi4vcHJvZHVjdE1hcmt1cFwiO1xyXG5pbXBvcnQgeyBjaGFuZ2VWYWx1ZVdpdGhBbmltYXRpb24sIG5vcm1hbGl6ZVByaWNlIH0gZnJvbSBcIi4uL3V0aWxzXCI7XHJcbmltcG9ydCB7IGNhbGN1bGF0ZVByb2R1Y3RzUHJpY2UgfSBmcm9tIFwiLi9wcmljZVwiO1xyXG5pbXBvcnQgeyB0b2dnbGVBY3Rpb25zIH0gZnJvbSBcIi4vYWN0aW9uc1wiO1xyXG5pbXBvcnQgeyBpbml0aWFsaXplQ291bnRlciB9IGZyb20gXCIuL2NvdW50ZXJcIjtcclxuaW1wb3J0IHtcclxuICBnZXRQcm9kdWN0c0Zyb21TdG9yYWdlLFxyXG4gIGdldFByb2R1Y3RGcm9tU3RvcmFnZSxcclxuICBnZXRQcm9kdWN0c0NvdW50RnJvbVN0b3JhZ2UsXHJcbiAgcmVtb3ZlUHJvZHVjdEZyb21TdG9yYWdlLFxyXG4gIHJlc3RvcmVQcm9kdWN0RnJvbVN0b3JhZ2UsXHJcbn0gZnJvbSBcIi4uL3N0b3JhZ2VcIjtcclxuaW1wb3J0IHsgdXBkYXRlVG90YWxSZXN1bHRzIH0gZnJvbSBcIi4vdG90YWxcIjtcclxuXHJcbi8vIFJlbmRlciBwcm9kdWN0cyAod2l0aCBpdHMgZnVuY3Rpb25hbGl0eSlcclxuXHJcbmNvbnN0IHByb2R1Y3RzQ29udGFpbmVyID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIi5tdGNoZWNrLXByb2R1Y3RzX19saXN0XCIpO1xyXG5jb25zdCBwcm9kdWN0cyA9IGdldFByb2R1Y3RzRnJvbVN0b3JhZ2UoKTtcclxuXHJcbmNvbnN0IHNlbGVjdEFsbFRleHQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFxyXG4gIFwiLm10Y2hlY2stcHJvZHVjdHNfX2hlYWQgLmNoZWNrYm94X190ZXh0XCJcclxuKTtcclxuY29uc3Qgc2VsZWN0QWxsQ2hlY2tib3ggPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiI3NlbGVjdC1hbGxcIik7XHJcblxyXG5wcm9kdWN0cy5mb3JFYWNoKChwcm9kdWN0KSA9PiB7XHJcbiAgY29uc3Qgbm9kZSA9IG5ldyBET01QYXJzZXIoKS5wYXJzZUZyb21TdHJpbmcoXHJcbiAgICBwcm9kdWN0TWFya3VwKHByb2R1Y3QpLFxyXG4gICAgXCJ0ZXh0L2h0bWxcIlxyXG4gICkuYm9keS5maXJzdENoaWxkO1xyXG5cclxuICBjb25zdCBzZWxlY3QgPSBub2RlLnF1ZXJ5U2VsZWN0b3IoXCIuY2hlY2tib3hfX2lucHV0XCIpO1xyXG4gIGNvbnN0IHJlbW92ZSA9IG5vZGUucXVlcnlTZWxlY3RvcihcIi5tdGNoZWNrLXByb2R1Y3RfX3JlbW92ZVwiKTtcclxuICBjb25zdCByZXN0b3JlID0gbm9kZS5xdWVyeVNlbGVjdG9yKFwiLm10Y2hlY2stcHJvZHVjdF9fcmVzdG9yZVwiKTtcclxuICBjb25zdCBjb3VudGVyID0gbm9kZS5xdWVyeVNlbGVjdG9yKFwiLmNvdW50ZXJcIik7XHJcblxyXG4gIGlmIChzZWxlY3QpIHtcclxuICAgIHNlbGVjdC5hZGRFdmVudExpc3RlbmVyKFwiY2hhbmdlXCIsICgpID0+IHtcclxuICAgICAgdG9nZ2xlQWN0aW9ucygpO1xyXG5cclxuICAgICAgY29uc3QgYWN0aXZlUHJvZHVjdHNDb3VudCA9IGdldFByb2R1Y3RzRnJvbVN0b3JhZ2UoKS5maWx0ZXIoXHJcbiAgICAgICAgKHsgaXNEZWxldGVkIH0pID0+ICFpc0RlbGV0ZWRcclxuICAgICAgKTtcclxuICAgICAgY29uc3QgY2hlY2tlZEl0ZW1zQ291bnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFxyXG4gICAgICAgIFwiLm10Y2hlY2stcHJvZHVjdF9fbGVmdCAuY2hlY2tib3hfX2lucHV0OmNoZWNrZWRcIlxyXG4gICAgICApO1xyXG5cclxuICAgICAgaWYgKGFjdGl2ZVByb2R1Y3RzQ291bnQgIT09IGNoZWNrZWRJdGVtc0NvdW50KVxyXG4gICAgICAgIHNlbGVjdEFsbENoZWNrYm94LmNoZWNrZWQgPSBmYWxzZTtcclxuXHJcbiAgICAgIHVwZGF0ZVByb2R1Y3RzU3VtbWFyeSgpO1xyXG5cclxuICAgICAgdXBkYXRlVG90YWxSZXN1bHRzKCk7XHJcbiAgICB9KTtcclxuICB9XHJcblxyXG4gIGlmIChyZW1vdmUpIHtcclxuICAgIHJlbW92ZS5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKHsgdGFyZ2V0IH0pID0+IHtcclxuICAgICAgY29uc3QgcHJvZHVjdE5vZGUgPSB0YXJnZXQuY2xvc2VzdChcIi5tdGNoZWNrLXByb2R1Y3RcIik7XHJcbiAgICAgIGNvbnN0IGlkID0gcHJvZHVjdE5vZGUuZGF0YXNldC5pZDtcclxuXHJcbiAgICAgIHJlbW92ZVByb2R1Y3RGcm9tU3RvcmFnZShpZCk7XHJcbiAgICAgIHJlbW92ZVByb2R1Y3RGcm9tUGFnZShwcm9kdWN0Tm9kZSwgaWQpO1xyXG4gICAgICB1cGRhdGVQcm9kdWN0c1N1bW1hcnkoKTtcclxuXHJcbiAgICAgIHVwZGF0ZVRvdGFsUmVzdWx0cygpO1xyXG4gICAgfSk7XHJcbiAgfVxyXG5cclxuICBpZiAocmVzdG9yZSkge1xyXG4gICAgcmVzdG9yZS5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKHsgdGFyZ2V0IH0pID0+IHtcclxuICAgICAgY29uc3QgcHJvZHVjdE5vZGUgPSB0YXJnZXQuY2xvc2VzdChcIi5tdGNoZWNrLXByb2R1Y3RcIik7XHJcbiAgICAgIGNvbnN0IGlkID0gcHJvZHVjdE5vZGUuZGF0YXNldC5pZDtcclxuXHJcbiAgICAgIHJlc3RvcmVQcm9kdWN0RnJvbVN0b3JhZ2UoaWQpO1xyXG4gICAgICByZXN0b3JlUHJvZHVjdEZyb21QYWdlKHByb2R1Y3ROb2RlLCBpZCk7XHJcbiAgICAgIHVwZGF0ZVByb2R1Y3RzU3VtbWFyeSgpO1xyXG5cclxuICAgICAgdXBkYXRlVG90YWxSZXN1bHRzKCk7XHJcbiAgICB9KTtcclxuICB9XHJcblxyXG4gIGluaXRpYWxpemVDb3VudGVyKGNvdW50ZXIpO1xyXG5cclxuICBwcm9kdWN0c0NvbnRhaW5lci5pbnNlcnRBZGphY2VudEVsZW1lbnQoXCJhZnRlcmJlZ2luXCIsIG5vZGUpO1xyXG59KTtcclxuXHJcbmV4cG9ydCBmdW5jdGlvbiByZW1vdmVQcm9kdWN0RnJvbVBhZ2UoY3VycmVudE5vZGUsIGlkKSB7XHJcbiAgY29uc3QgaXNEZWxldGVkID0gY3VycmVudE5vZGUuY2xhc3NMaXN0LmNvbnRhaW5zKFwibXRjaGVjay1wcm9kdWN0LS1kZWxldGVkXCIpO1xyXG5cclxuICBpZiAoaXNEZWxldGVkKSB7XHJcbiAgICByZXR1cm4gY3VycmVudE5vZGUucmVtb3ZlKCk7XHJcbiAgfVxyXG5cclxuICBjb25zdCBzaWJsaW5nID0gY3VycmVudE5vZGUubmV4dFNpYmxpbmc7XHJcbiAgY29uc3QgbmV3Tm9kZSA9IG5ldyBET01QYXJzZXIoKS5wYXJzZUZyb21TdHJpbmcoXHJcbiAgICBwcm9kdWN0TWFya3VwKGdldFByb2R1Y3RGcm9tU3RvcmFnZShpZCkpLFxyXG4gICAgXCJ0ZXh0L2h0bWxcIlxyXG4gICkuYm9keS5maXJzdENoaWxkO1xyXG5cclxuICBjb25zdCByZW1vdmUgPSBuZXdOb2RlLnF1ZXJ5U2VsZWN0b3IoXCIubXRjaGVjay1wcm9kdWN0X19yZW1vdmVcIik7XHJcbiAgY29uc3QgcmVzdG9yZSA9IG5ld05vZGUucXVlcnlTZWxlY3RvcihcIi5tdGNoZWNrLXByb2R1Y3RfX3Jlc3RvcmVcIik7XHJcblxyXG4gIHJlbW92ZS5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKHsgdGFyZ2V0IH0pID0+IHtcclxuICAgIGNvbnN0IHByb2R1Y3ROb2RlID0gdGFyZ2V0LmNsb3Nlc3QoXCIubXRjaGVjay1wcm9kdWN0XCIpO1xyXG4gICAgY29uc3QgaWQgPSBwcm9kdWN0Tm9kZS5kYXRhc2V0LmlkO1xyXG5cclxuICAgIHJlbW92ZVByb2R1Y3RGcm9tU3RvcmFnZShpZCk7XHJcbiAgICByZW1vdmVQcm9kdWN0RnJvbVBhZ2UocHJvZHVjdE5vZGUsIGlkKTtcclxuICAgIHVwZGF0ZVByb2R1Y3RzU3VtbWFyeSgpO1xyXG5cclxuICAgIHVwZGF0ZVRvdGFsUmVzdWx0cygpO1xyXG4gIH0pO1xyXG5cclxuICByZXN0b3JlLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoeyB0YXJnZXQgfSkgPT4ge1xyXG4gICAgY29uc3QgcHJvZHVjdE5vZGUgPSB0YXJnZXQuY2xvc2VzdChcIi5tdGNoZWNrLXByb2R1Y3RcIik7XHJcbiAgICBjb25zdCBpZCA9IHByb2R1Y3ROb2RlLmRhdGFzZXQuaWQ7XHJcblxyXG4gICAgcmVzdG9yZVByb2R1Y3RGcm9tU3RvcmFnZShpZCk7XHJcbiAgICByZXN0b3JlUHJvZHVjdEZyb21QYWdlKHByb2R1Y3ROb2RlLCBpZCk7XHJcbiAgICB1cGRhdGVQcm9kdWN0c1N1bW1hcnkoKTtcclxuXHJcbiAgICB1cGRhdGVUb3RhbFJlc3VsdHMoKTtcclxuICB9KTtcclxuXHJcbiAgc2VsZWN0QWxsVGV4dC50ZXh0Q29udGVudCA9IGDQktGL0LHRgNCw0YLRjCDQstGB0LUgKCR7Z2V0UHJvZHVjdHNDb3VudEZyb21TdG9yYWdlKCl9KWA7XHJcblxyXG4gIGN1cnJlbnROb2RlLnJlbW92ZSgpO1xyXG4gIHByb2R1Y3RzQ29udGFpbmVyLmluc2VydEJlZm9yZShuZXdOb2RlLCBzaWJsaW5nKTtcclxuXHJcbiAgdG9nZ2xlQWN0aW9ucygpO1xyXG59XHJcblxyXG5mdW5jdGlvbiByZXN0b3JlUHJvZHVjdEZyb21QYWdlKGN1cnJlbnROb2RlLCBpZCkge1xyXG4gIGNvbnN0IHNpYmxpbmcgPSBjdXJyZW50Tm9kZS5uZXh0U2libGluZztcclxuICBjb25zdCBuZXdOb2RlID0gbmV3IERPTVBhcnNlcigpLnBhcnNlRnJvbVN0cmluZyhcclxuICAgIHByb2R1Y3RNYXJrdXAoZ2V0UHJvZHVjdEZyb21TdG9yYWdlKGlkKSksXHJcbiAgICBcInRleHQvaHRtbFwiXHJcbiAgKS5ib2R5LmZpcnN0Q2hpbGQ7XHJcblxyXG4gIGNvbnN0IHNlbGVjdCA9IG5ld05vZGUucXVlcnlTZWxlY3RvcihcIi5jaGVja2JveF9faW5wdXRcIik7XHJcbiAgY29uc3QgcmVtb3ZlID0gbmV3Tm9kZS5xdWVyeVNlbGVjdG9yKFwiLm10Y2hlY2stcHJvZHVjdF9fcmVtb3ZlXCIpO1xyXG4gIGNvbnN0IGNvdW50ZXIgPSBuZXdOb2RlLnF1ZXJ5U2VsZWN0b3IoXCIuY291bnRlclwiKTtcclxuXHJcbiAgaWYgKHNlbGVjdEFsbENoZWNrYm94LmNoZWNrZWQpIHNlbGVjdC5jaGVja2VkID0gdHJ1ZTtcclxuXHJcbiAgc2VsZWN0LmFkZEV2ZW50TGlzdGVuZXIoXCJjaGFuZ2VcIiwgKCkgPT4ge1xyXG4gICAgdG9nZ2xlQWN0aW9ucygpO1xyXG5cclxuICAgIGNvbnN0IGFjdGl2ZVByb2R1Y3RzQ291bnQgPSBnZXRQcm9kdWN0c0Zyb21TdG9yYWdlKCkuZmlsdGVyKFxyXG4gICAgICAoeyBpc0RlbGV0ZWQgfSkgPT4gIWlzRGVsZXRlZFxyXG4gICAgKTtcclxuICAgIGNvbnN0IGNoZWNrZWRJdGVtc0NvdW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChcclxuICAgICAgXCIubXRjaGVjay1wcm9kdWN0X19sZWZ0IC5jaGVja2JveF9faW5wdXQ6Y2hlY2tlZFwiXHJcbiAgICApO1xyXG5cclxuICAgIGlmIChhY3RpdmVQcm9kdWN0c0NvdW50ICE9PSBjaGVja2VkSXRlbXNDb3VudClcclxuICAgICAgc2VsZWN0QWxsQ2hlY2tib3guY2hlY2tlZCA9IGZhbHNlO1xyXG5cclxuICAgIHVwZGF0ZVByb2R1Y3RzU3VtbWFyeSgpO1xyXG5cclxuICAgIHVwZGF0ZVRvdGFsUmVzdWx0cygpO1xyXG4gIH0pO1xyXG5cclxuICByZW1vdmUuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsICh7IHRhcmdldCB9KSA9PiB7XHJcbiAgICBjb25zdCBwcm9kdWN0Tm9kZSA9IHRhcmdldC5jbG9zZXN0KFwiLm10Y2hlY2stcHJvZHVjdFwiKTtcclxuICAgIGNvbnN0IGlkID0gcHJvZHVjdE5vZGUuZGF0YXNldC5pZDtcclxuXHJcbiAgICByZW1vdmVQcm9kdWN0RnJvbVN0b3JhZ2UoaWQpO1xyXG4gICAgcmVtb3ZlUHJvZHVjdEZyb21QYWdlKHByb2R1Y3ROb2RlLCBpZCk7XHJcbiAgICB1cGRhdGVQcm9kdWN0c1N1bW1hcnkoKTtcclxuXHJcbiAgICB1cGRhdGVUb3RhbFJlc3VsdHMoKTtcclxuICB9KTtcclxuXHJcbiAgaW5pdGlhbGl6ZUNvdW50ZXIoY291bnRlcik7XHJcblxyXG4gIHNlbGVjdEFsbFRleHQudGV4dENvbnRlbnQgPSBg0JLRi9Cx0YDQsNGC0Ywg0LLRgdC1ICgke2dldFByb2R1Y3RzQ291bnRGcm9tU3RvcmFnZSgpfSlgO1xyXG5cclxuICBjdXJyZW50Tm9kZS5yZW1vdmUoKTtcclxuICBwcm9kdWN0c0NvbnRhaW5lci5pbnNlcnRCZWZvcmUobmV3Tm9kZSwgc2libGluZyk7XHJcblxyXG4gIHRvZ2dsZUFjdGlvbnMoKTtcclxufVxyXG5cclxuLy8gVXBkYXRlIHByb2R1Y3RzLXN1bW1hcnkgKHVuZGVyIHRoZSBwcm9kdWN0cyBsaXN0KSAoY291bnQgLyBwcmljZSlcclxuXHJcbmV4cG9ydCBmdW5jdGlvbiB1cFZhbHVlV2l0aEFuaW1hdGlvbihwbGFjZSwgcHJldiwgY3VycmVudCkge1xyXG4gIGxldCBhbmltYXRpb25TdGFydDtcclxuICBsZXQgcmVxdWVzdElkID0gd2luZG93LnJlcXVlc3RBbmltYXRpb25GcmFtZShhbmltYXRlKTtcclxuXHJcbiAgZnVuY3Rpb24gYW5pbWF0ZSh0aW1lc3RhbXApIHtcclxuICAgIGlmICghYW5pbWF0aW9uU3RhcnQpIHtcclxuICAgICAgYW5pbWF0aW9uU3RhcnQgPSB0aW1lc3RhbXA7XHJcbiAgICB9XHJcblxyXG4gICAgY29uc3QgcHJvZ3Jlc3MgPSB0aW1lc3RhbXAgLSBhbmltYXRpb25TdGFydDtcclxuXHJcbiAgICBwcmV2ICs9IHByb2dyZXNzICogMTA7XHJcblxyXG4gICAgaWYgKHByZXYgPCBjdXJyZW50KSB7XHJcbiAgICAgIHdpbmRvdy5yZXF1ZXN0QW5pbWF0aW9uRnJhbWUoYW5pbWF0ZSk7XHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICBwcmV2ID0gY3VycmVudDtcclxuICAgICAgd2luZG93LmNhbmNlbEFuaW1hdGlvbkZyYW1lKHJlcXVlc3RJZCk7XHJcbiAgICB9XHJcbiAgICBwbGFjZS50ZXh0Q29udGVudCA9IGAke25vcm1hbGl6ZVByaWNlKE1hdGguZmxvb3IocHJldikpfWA7XHJcbiAgfVxyXG59XHJcblxyXG5leHBvcnQgZnVuY3Rpb24gdXBkYXRlUHJvZHVjdHNTdW1tYXJ5KCkge1xyXG4gIGNvbnN0IHN1bW1hcnlDb3VudCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCIubXRjaGVjay1wcm9kdWN0c19fY291bnRcIik7XHJcbiAgY29uc3Qgc3VtbWFyeVByaWNlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIi5tdGNoZWNrLXByb2R1Y3RzX19zdW1cIik7XHJcblxyXG4gIGNvbnN0IHsgY291bnQsIHByaWNlOiBjdXJyZW50UHJpY2UgfSA9IGNhbGN1bGF0ZVByb2R1Y3RzUHJpY2UoKTtcclxuXHJcbiAgc3VtbWFyeUNvdW50LnRleHRDb250ZW50ID0gYNCi0L7QstCw0YDQvtCyLCAke2NvdW50fSDRiNGCLmA7XHJcbiAgY2hhbmdlVmFsdWVXaXRoQW5pbWF0aW9uKHN1bW1hcnlQcmljZSwgY3VycmVudFByaWNlKTtcclxufVxyXG4iLCJjb25zdCBzZWxlY3RzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChcIi5zZWxlY3RcIilcclxuY29uc3Qgc2VsZWN0T3BlbmVkQ2xhc3NOYW1lID0gXCJzZWxlY3QtLW9wZW5lZFwiXHJcbmNvbnN0IHNlbGVjdEl0ZW1BY3RpdmVDbGFzc05hbWUgPSBcInNlbGVjdF9faXRlbS0tYWN0aXZlXCJcclxuXHJcbnNlbGVjdHMuZm9yRWFjaCgoc2VsZWN0KSA9PiB7XHJcbiAgY29uc3QgaXRlbXMgPSBzZWxlY3QucXVlcnlTZWxlY3RvckFsbChcIi5zZWxlY3RfX2l0ZW1cIilcclxuXHJcbiAgc2VsZWN0LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCB0b2dnbGUuYmluZCh0aGlzLCBzZWxlY3QpKVxyXG5cclxuICBpdGVtcy5mb3JFYWNoKChpdGVtKSA9PiBpdGVtLmFkZEV2ZW50TGlzdGVuZXIoXHJcbiAgICBcImNsaWNrXCIsXHJcbiAgICBjaGFuZ2UuYmluZCh0aGlzLCBzZWxlY3QpXHJcbiAgKSlcclxufSlcclxuXHJcbmZ1bmN0aW9uIHRvZ2dsZShzZWxlY3QpIHtcclxuICBzZWxlY3QuY2xhc3NMaXN0LnRvZ2dsZShzZWxlY3RPcGVuZWRDbGFzc05hbWUpXHJcbn1cclxuXHJcbmZ1bmN0aW9uIGNoYW5nZShzZWxlY3QsIGV2ZW50KSB7XHJcbiAgY29uc3QgZGlzcGxheSA9IHNlbGVjdC5xdWVyeVNlbGVjdG9yKFwiLnNlbGVjdF9fZGlzcGxheVwiKVxyXG4gIGNvbnN0IGN1cnJlbnRBY3RpdmVJdGVtID0gc2VsZWN0LnF1ZXJ5U2VsZWN0b3IoYC4ke3NlbGVjdEl0ZW1BY3RpdmVDbGFzc05hbWV9YClcclxuICBjb25zdCBpdGVtID0gZXZlbnQudGFyZ2V0XHJcblxyXG4gIGRpc3BsYXkudGV4dENvbnRlbnQgPSBpdGVtLnRleHRDb250ZW50XHJcblxyXG4gIC8vIENoYW5nZSBhY3RpdmUgdGFiIChieSBzdHlsZSlcclxuXHJcbiAgY3VycmVudEFjdGl2ZUl0ZW0uY2xhc3NMaXN0LnJlbW92ZShzZWxlY3RJdGVtQWN0aXZlQ2xhc3NOYW1lKVxyXG4gIGl0ZW0uY2xhc3NMaXN0LmFkZChzZWxlY3RJdGVtQWN0aXZlQ2xhc3NOYW1lKVxyXG59XHJcbiIsImltcG9ydCB7IHZhbGlkYXRlRGF0YSB9IGZyb20gXCIuL3ZhbGlkYXRlRGF0YVwiO1xyXG5pbXBvcnQgeyBnZXRTdWNjZXNzZnVsU3VibWl0RGF0YSwgdG9nZ2xlUG9wdXAgfSBmcm9tIFwiLi9wb3B1cFwiO1xyXG5pbXBvcnQgeyBnZXRBbGxEYXRhIH0gZnJvbSBcIi4vZ2V0QWxsRGF0YVwiO1xyXG5cclxuY29uc3QgcG9wdXAgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiLm10Y2hlY2stcG9wdXBcIik7XHJcbmNvbnN0IHBvcHVwQm9keSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCIubXRjaGVjay1wb3B1cF9fYm9keVwiKTtcclxuXHJcbmNvbnN0IHN1bW1hcnlTdWJtaXRCdXR0b24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFxyXG4gIFwiLm10Y2hlY2stc3VtbWFyeSAubXRjaGVjay1zdWJtaXRfX2J1dHRvblwiXHJcbik7XHJcblxyXG5jb25zdCBjb25maXJtU3VibWl0QnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcclxuICBcIi5tdGNoZWNrLWNvbmZpcm0gLm10Y2hlY2stc3VibWl0X19idXR0b25cIlxyXG4pO1xyXG5cclxuZXhwb3J0IGNvbnN0IHN1Ym1pdERhdGEgPSAoZSkgPT4ge1xyXG4gIGUucHJldmVudERlZmF1bHQoKTtcclxuXHJcbiAgbGV0IHZhbGlkYXRlUmVzdWx0O1xyXG5cclxuICBjb25zdCBmb3JtID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcclxuICAgIHBvcHVwLmNsYXNzTGlzdC5jb250YWlucyhcImlzLW9wZW5cIilcclxuICAgICAgPyBcIi5tdGNoZWNrLXBvcHVwIGZvcm1cIlxyXG4gICAgICA6IFwiLm10Y2hlY2stcmVnaXN0ZXJcIlxyXG4gICk7XHJcblxyXG4gIHZhbGlkYXRlUmVzdWx0ID0gdmFsaWRhdGVEYXRhKGZvcm0pO1xyXG5cclxuICBpZiAoIXZhbGlkYXRlUmVzdWx0KSB7XHJcbiAgICBjb25zdCByZWdpc3RlckZvcm0gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiLm10Y2hlY2stcmVnaXN0ZXJcIik7XHJcbiAgICB3aW5kb3cuc2Nyb2xsVG8oe1xyXG4gICAgICB0b3A6IHJlZ2lzdGVyRm9ybS5vZmZzZXRUb3AsXHJcbiAgICAgIGJlaGF2aW9yOiBcInNtb290aFwiLFxyXG4gICAgfSk7XHJcbiAgICByZXR1cm47XHJcbiAgfVxyXG5cclxuICBjb25zdCBkYXRhID0gZ2V0QWxsRGF0YShmb3JtKTtcclxuXHJcbiAgY29uc29sZS5sb2coXCLQlNCw0L3QvdGL0LUg0L7RgtC/0YDQsNCy0LvQtdC90YtcIik7XHJcblxyXG4gIHBvcHVwQm9keS5pbm5lckhUTUwgPSBnZXRTdWNjZXNzZnVsU3VibWl0RGF0YSgpO1xyXG5cclxuICBpZiAoIXBvcHVwLmNsYXNzTGlzdC5jb250YWlucyhcImlzLW9wZW5cIikpIHRvZ2dsZVBvcHVwKCk7XHJcbn07XHJcblxyXG5zdW1tYXJ5U3VibWl0QnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBzdWJtaXREYXRhKTtcclxuY29uZmlybVN1Ym1pdEJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgc3VibWl0RGF0YSk7XHJcbnBvcHVwQm9keS5hZGRFdmVudExpc3RlbmVyKFwic3VibWl0XCIsIHN1Ym1pdERhdGEpO1xyXG4iLCJjb25zdCB0YWJzSGFuZGxlciA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCJbZGF0YS10YWItaGFuZGxlcl1cIilcclxuY29uc3QgdGFic1N0YXR1cyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCJbZGF0YS10YWItc3RhdHVzXVwiKVxyXG5cclxudGFic0hhbmRsZXIuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIHRhYkNoYW5nZSlcclxuXHJcbmZ1bmN0aW9uIHRhYkNoYW5nZSgpIHtcclxuICBjb25zdCBmdXR1cmVBY3RpdmVUYWIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiW2RhdGEtdGFiPScnXVwiKVxyXG4gIGNvbnN0IGN1cnJlbnRBY3RpdmVUYWIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiW2RhdGEtdGFiPSd0cnVlJ11cIilcclxuXHJcbiAgY3VycmVudEFjdGl2ZVRhYi5zZXRBdHRyaWJ1dGUoXCJkYXRhLXRhYlwiLCBcIlwiKVxyXG4gIGZ1dHVyZUFjdGl2ZVRhYi5zZXRBdHRyaWJ1dGUoXCJkYXRhLXRhYlwiLCBcInRydWVcIilcclxuXHJcbiAgLy8gU2V0IHRleHQgbm9kZXNcclxuXHJcbiAgY29uc3QgcHJldmlvdXNIYW5kbGVyID0gdGFic0hhbmRsZXIudGV4dENvbnRlbnRcclxuICBjb25zdCBwcmV2aW91c1N0YXR1cyA9IHRhYnNTdGF0dXMudGV4dENvbnRlbnRcclxuXHJcbiAgdGFic0hhbmRsZXIudGV4dENvbnRlbnQgPSB0YWJzSGFuZGxlci5kYXRhc2V0LnRhYkhhbmRsZXJcclxuICB0YWJzU3RhdHVzLnRleHRDb250ZW50ID0gdGFic1N0YXR1cy5kYXRhc2V0LnRhYlN0YXR1c1xyXG5cclxuICB0YWJzSGFuZGxlci5zZXRBdHRyaWJ1dGUoXCJkYXRhLXRhYi1oYW5kbGVyXCIsIHByZXZpb3VzSGFuZGxlcilcclxuICB0YWJzU3RhdHVzLnNldEF0dHJpYnV0ZShcImRhdGEtdGFiLXN0YXR1c1wiLCBwcmV2aW91c1N0YXR1cylcclxufVxyXG4iLCJpbXBvcnQge1xyXG4gIGNoYW5nZVZhbHVlV2l0aEFuaW1hdGlvbixcclxuICBnZXROdW1iZXJWYWx1ZSxcclxuICBub3JtYWxpemVQcmljZSxcclxufSBmcm9tIFwiLi4vdXRpbHNcIjtcclxuaW1wb3J0IHsgY2FsY3VsYXRlUHJvZHVjdHNQcmljZSwgY2FsY3VsYXRlVG90YWxQcmljZSB9IGZyb20gXCIuL3ByaWNlXCI7XHJcblxyXG4vLyDQl9C00LXRgdGMINC/0L7QtNGB0YfQtdGCLiDQryDRgdC00LXQu9Cw0Lsg0L/RgNC40LzQtdGA0L3Qviwg0YHQsNC80YMg0YTQvtGA0LzRg9C70YMg0L3QsNC00L4g0LTQvtGA0LDQsdC+0YLQsNGC0YwpXHJcblxyXG5mdW5jdGlvbiBnZXRDb25maXJtVGFibGVUZW1wbGF0ZShcclxuICBkZWxpdmVyeVR5cGUsXHJcbiAgZGVsaXZlcnlQcmljZSxcclxuICBwYXlUeXBlLFxyXG4gIGJvbnVzQmFsbHMsXHJcbiAgY29uc3VtZXJzR3JvdXBEaXNjb3VudCxcclxuICBwcmljZSxcclxuICB0b3RhbFN1bW1EaXNjb3VudFxyXG4pIHtcclxuICByZXR1cm4gYFxyXG4gICAgPGRpdiBjbGFzcz1cIm10Y2hlY2stdGFibGVfX2l0ZW1cIj5cclxuICAgICAgPHAgY2xhc3M9XCJ0ZXh0IHRleHQtLW1kIHRleHQtLXByaW1hcnkgbXRjaGVjay10YWJsZV9faXRlbS1uYW1lXCI+XHJcbiAgICAgICAg0JTQvtGB0YLQsNCy0LrQsCBcclxuICAgICAgICA8c3Bhbj4oJHtkZWxpdmVyeVR5cGV9KTwvc3Bhbj5cclxuICAgICAgPC9wPlxyXG4gICAgICA8c3BhbiBjbGFzcz1cInRleHQgdGV4dC0tbWQgdGV4dC0tcHJpbWFyeSBtdGNoZWNrLXRhYmxlX19pdGVtLXZhbHVlXCIgaWQ9XCJjb25maXJtLWRlbGl2ZXJ5LXByaWNlXCI+JHtkZWxpdmVyeVByaWNlfSAmIzgzODE7PC9zcGFuPlxyXG4gICAgPC9kaXY+XHJcbiAgICA8ZGl2IGNsYXNzPVwibXRjaGVjay10YWJsZV9faXRlbSBtdGNoZWNrLXRhYmxlX19pdGVtLS1tYXJnaW5cIj5cclxuICAgICAgPHAgY2xhc3M9XCJ0ZXh0IHRleHQtLW1kIHRleHQtLXByaW1hcnkgbXRjaGVjay10YWJsZV9faXRlbS1uYW1lXCI+XHJcbiAgICAgICAg0J7Qv9C70LDRgtCwIFxyXG4gICAgICAgIDxzcGFuPigke3BheVR5cGV9KTwvc3Bhbj5cclxuICAgICAgPC9wPlxyXG4gICAgPC9kaXY+XHJcbiAgICA8ZGl2IGNsYXNzPVwibXRjaGVjay10YWJsZV9faXRlbVwiPlxyXG4gICAgICA8cCBjbGFzcz1cInRleHQgdGV4dC0tbWQgdGV4dC0tcHJpbWFyeSBtdGNoZWNrLXRhYmxlX19pdGVtLW5hbWVcIj7QkdC+0L3Rg9GB0L3Ri9C1INCx0LDQu9C70Ys8L3A+XHJcbiAgICAgIDxzcGFuIGNsYXNzPVwidGV4dCB0ZXh0LS1tZCB0ZXh0LS1wcmltYXJ5IG10Y2hlY2stdGFibGVfX2l0ZW0tdmFsdWVcIj4ke2JvbnVzQmFsbHN9INCx0LDQu9C70L7Qsjwvc3Bhbj5cclxuICAgIDwvZGl2PlxyXG4gICAgPGRpdiBjbGFzcz1cIm10Y2hlY2stdGFibGVfX2l0ZW1cIj5cclxuICAgICAgPHAgY2xhc3M9XCJ0ZXh0IHRleHQtLW1kIHRleHQtLXByaW1hcnkgbXRjaGVjay10YWJsZV9faXRlbS1uYW1lXCI+XHJcbiAgICAgICAg0KHQutC40LTQutCwINCz0YDRg9C/0L/QtSDQv9C+0LrRg9C/0LDRgtC10LvQtdC5LCA1JSBcclxuICAgICAgICA8c3Bhbj4o0YHQtdGA0LXQsdGA0Y/QvdC90YvQuSDQutC70LjQtdC90YIpPC9zcGFuPlxyXG4gICAgICA8L3A+XHJcbiAgICAgIDxzcGFuIGNsYXNzPVwidGV4dCB0ZXh0LS1tZCB0ZXh0LS1wcmltYXJ5IG10Y2hlY2stdGFibGVfX2l0ZW0tdmFsdWVcIj4tIDxzcGFuIGlkPVwiY29uZmlybS1jb25zdW1lcnMtZ3JvdXAtZGlzY291bnRcIj4ke01hdGguZmxvb3IoXHJcbiAgICAgICAgY29uc3VtZXJzR3JvdXBEaXNjb3VudCAqIHByaWNlXHJcbiAgICAgICkudG9Mb2NhbGVTdHJpbmcoXCJydS1SVVwiKX08L3NwYW4+ICYjODM4MTs8L3NwYW4+XHJcbiAgICA8L2Rpdj5cclxuICAgICR7XHJcbiAgICAgIHdpbmRvdy5jb3Vwb25cclxuICAgICAgICA/IGBcclxuICAgICAgICA8ZGl2IGNsYXNzPVwibXRjaGVjay10YWJsZV9faXRlbVwiPlxyXG4gICAgICAgICAgPHAgY2xhc3M9XCJ0ZXh0IHRleHQtLW1kIHRleHQtLXByaW1hcnkgbXRjaGVjay10YWJsZV9faXRlbS1uYW1lXCI+XHJcbiAgICAgICAgICAgINCa0YPQv9C+0L0g0L3QsCDRgdC60LjQtNC60YMgXHJcbiAgICAgICAgICAgIDxzcGFuPigke3dpbmRvdy5jb3Vwb24udGV4dH0pPC9zcGFuPlxyXG4gICAgICAgICAgPC9wPlxyXG4gICAgICAgICAgPHNwYW4gY2xhc3M9XCJ0ZXh0IHRleHQtLW1kIHRleHQtLXByaW1hcnkgbXRjaGVjay10YWJsZV9faXRlbS12YWx1ZVwiIGlkPVwiY29uZmlybS1jb3Vwb24tdmFsdWVcIj4tICR7d2luZG93LmNvdXBvbi52YWx1ZX0gJiM4MzgxOzwvc3Bhbj5cclxuICAgICAgICA8L2Rpdj5cclxuICAgICAgICBgXHJcbiAgICAgICAgOiBcIlwiXHJcbiAgICB9XHJcbiAgICAke1xyXG4gICAgICB3aW5kb3cuY2VydGlmaWNhdGVcclxuICAgICAgICA/IGBcclxuICAgICAgICA8ZGl2IGNsYXNzPVwibXRjaGVjay10YWJsZV9faXRlbVwiPlxyXG4gICAgICAgICAgPHAgY2xhc3M9XCJ0ZXh0IHRleHQtLW1kIHRleHQtLXByaW1hcnkgbXRjaGVjay10YWJsZV9faXRlbS1uYW1lXCI+XHJcbiAgICAgICAgICAgINCh0LXRgNGC0LjRhNC40LrQsNGCIFxyXG4gICAgICAgICAgICA8c3Bhbj4oJHt3aW5kb3cuY2VydGlmaWNhdGUudGV4dH0pPC9zcGFuPlxyXG4gICAgICAgICAgPC9wPlxyXG4gICAgICAgICAgPHNwYW4gY2xhc3M9XCJ0ZXh0IHRleHQtLW1kIHRleHQtLXByaW1hcnkgbXRjaGVjay10YWJsZV9faXRlbS12YWx1ZVwiIGlkPVwiY29uZmlybS1jZXJ0aWZpY2F0ZS12YWx1ZVwiPi0gJHt3aW5kb3cuY2VydGlmaWNhdGUudmFsdWV9ICYjODM4MTs8L3NwYW4+XHJcbiAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgYFxyXG4gICAgICAgIDogXCJcIlxyXG4gICAgfVxyXG4gICAgPGRpdiBjbGFzcz1cIm10Y2hlY2stdGFibGVfX2l0ZW1cIj5cclxuICAgICAgPHAgY2xhc3M9XCJ0ZXh0IHRleHQtLW1kIHRleHQtLXByaW1hcnkgbXRjaGVjay10YWJsZV9faXRlbS1uYW1lXCI+XHJcbiAgICAgICAg0KHQutC40LTQutCwINC30LAg0YHRg9C80LzRgyDQt9Cw0LrQsNC30LAsIDMlXHJcbiAgICAgIDwvcD5cclxuICAgICAgPHNwYW4gY2xhc3M9XCJ0ZXh0IHRleHQtLW1kIHRleHQtLXByaW1hcnkgbXRjaGVjay10YWJsZV9faXRlbS12YWx1ZVwiIGlkPVwiY29uZmlybS10b3RhbC1zdW1tLWRpc2NvdW50XCI+LSAke01hdGguY2VpbChcclxuICAgICAgICB0b3RhbFN1bW1EaXNjb3VudCAqIHByaWNlXHJcbiAgICAgICkudG9Mb2NhbGVTdHJpbmcoXCJydS1SVVwiKX0gJiM4MzgxOzwvc3Bhbj5cclxuICAgIDwvZGl2PlxyXG4gICAgPGRpdiBjbGFzcz1cIm10Y2hlY2stdGFibGVfX2l0ZW1cIj5cclxuICAgICAgPHAgY2xhc3M9XCJ0ZXh0IHRleHQtLW1kIHRleHQtLXByaW1hcnkgbXRjaGVjay10YWJsZV9faXRlbS1uYW1lXCI+XHJcbiAgICAgICAg0J3QsNC70L7QsyDQndCU0KEsIDIwJVxyXG4gICAgICA8L3A+XHJcbiAgICAgIDxzcGFuIGNsYXNzPVwidGV4dCB0ZXh0LS1tZCB0ZXh0LS1wcmltYXJ5IG10Y2hlY2stdGFibGVfX2l0ZW0tdmFsdWVcIiBpZD1cImNvbmZpcm0tdGF4LXZhbHVlXCI+JHsoXHJcbiAgICAgICAgcHJpY2UgKiAwLjJcclxuICAgICAgKS50b0xvY2FsZVN0cmluZyhcInJ1LVJVXCIpfSAmIzgzODE7PC9zcGFuPlxyXG4gICAgPC9kaXY+XHJcbiAgYDtcclxufVxyXG5cclxuZnVuY3Rpb24gZ2V0T3JkZXJUYWJsZVRlbXBsYXRlKFxyXG4gIGRlbGl2ZXJ5VHlwZSxcclxuICBkZWxpdmVyeVByaWNlLFxyXG4gIHBheVR5cGUsXHJcbiAgcHJvZHVjdFByaWNlLFxyXG4gIHRvdGFsRGlzY291bnRcclxuKSB7XHJcbiAgcmV0dXJuIGBcclxuICAgIDxkaXYgY2xhc3M9XCJtdGNoZWNrLXRhYmxlX19pdGVtXCI+XHJcbiAgICAgIDxwIGNsYXNzPVwidGV4dCBtdGNoZWNrLXRhYmxlX19pdGVtLW5hbWVcIj5cclxuICAgICAgICDQlNC+0YHRgtCw0LLQutCwIFxyXG4gICAgICAgIDxzcGFuPigke2RlbGl2ZXJ5VHlwZX0pPC9zcGFuPlxyXG4gICAgICA8L3A+XHJcbiAgICAgIDxzcGFuIGNsYXNzPVwidGV4dCBtdGNoZWNrLXRhYmxlX19pdGVtLXZhbHVlXCIgPjxzcGFuIGlkPVwib3JkZXItZGVsaXZlcnktcHJpY2VcIj4ke2RlbGl2ZXJ5UHJpY2V9PC9zcGFuPiAmIzgzODE7PC9zcGFuPlxyXG4gICAgPC9kaXY+XHJcbiAgICA8ZGl2IGNsYXNzPVwibXRjaGVjay10YWJsZV9faXRlbSBtdGNoZWNrLXRhYmxlX19pdGVtLS1tYXJnaW5cIj5cclxuICAgICAgPHAgY2xhc3M9XCJ0ZXh0IG10Y2hlY2stdGFibGVfX2l0ZW0tbmFtZVwiPlxyXG4gICAgICAgINCe0L/Qu9Cw0YLQsCBcclxuICAgICAgICA8c3Bhbj4oJHtwYXlUeXBlfSk8L3NwYW4+XHJcbiAgICAgIDwvcD5cclxuICAgIDwvZGl2PlxyXG4gICAgPGRpdiBjbGFzcz1cIm10Y2hlY2stdGFibGVfX2l0ZW1cIj5cclxuICAgICAgPHAgY2xhc3M9XCJ0ZXh0IG10Y2hlY2stdGFibGVfX2l0ZW0tbmFtZVwiPtCi0L7QstCw0YDRiywg0YEg0YPRh9C10YLQvtC8INC90LDQu9C+0LPQvtCyPC9wPlxyXG4gICAgICA8c3BhbiBjbGFzcz1cInRleHQgbXRjaGVjay10YWJsZV9faXRlbS12YWx1ZVwiIGlkPVwib3JkZXItcHJvZHVjdHMtcHJpY2VcIj48c3BhbiBpZD1cIm9yZGVyLXByb2R1Y3RzLXByaWNlXCI+JHtwcm9kdWN0UHJpY2V9PC9zcGFuPiAmIzgzODE7PC9zcGFuPlxyXG4gICAgPC9kaXY+XHJcbiAgICA8ZGl2IGNsYXNzPVwibXRjaGVjay10YWJsZV9faXRlbVwiPlxyXG4gICAgICA8cCBjbGFzcz1cInRleHQgbXRjaGVjay10YWJsZV9faXRlbS1uYW1lXCI+0KHQutC40LTQutCwLCAlPC9wPlxyXG4gICAgICA8c3BhbiBjbGFzcz1cInRleHQgbXRjaGVjay10YWJsZV9faXRlbS12YWx1ZVwiPi0gPHNwYW4gaWQ9XCJvcmRlci10b3RhbC1kaXNjb3VudFwiPiR7dG90YWxEaXNjb3VudH08c3Bhbj4gJiM4MzgxOzwvc3Bhbj5cclxuICAgIDwvZGl2PlxyXG4gIGA7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIGdldFByZXZEYXRhKCkge1xyXG4gIGNvbnN0IG9yZGVyRGVsaXZlcnlQcmljZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwib3JkZXItZGVsaXZlcnktcHJpY2VcIik7XHJcbiAgY29uc3Qgb3JkZXJQcm9kdWN0UHJpY2UgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcIm9yZGVyLXByb2R1Y3RzLXByaWNlXCIpO1xyXG4gIGNvbnN0IG9yZGVyVG90YWxEaXNjb3VudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwib3JkZXItdG90YWwtZGlzY291bnRcIik7XHJcblxyXG4gIGxldCBwcmV2RGVsaXZlcnlQcmljZSA9IDAsXHJcbiAgICBwcmV2UHJvZHVjdFByaWNlID0gMCxcclxuICAgIHByZXZUb3RhbERpc2NvdW50ID0gMDtcclxuXHJcbiAgaWYgKChvcmRlckRlbGl2ZXJ5UHJpY2UsIG9yZGVyUHJvZHVjdFByaWNlLCBvcmRlclRvdGFsRGlzY291bnQpKSB7XHJcbiAgICBwcmV2RGVsaXZlcnlQcmljZSA9IGdldE51bWJlclZhbHVlKG9yZGVyRGVsaXZlcnlQcmljZSk7XHJcbiAgICBwcmV2UHJvZHVjdFByaWNlID0gZ2V0TnVtYmVyVmFsdWUob3JkZXJQcm9kdWN0UHJpY2UpO1xyXG4gICAgcHJldlRvdGFsRGlzY291bnQgPSBnZXROdW1iZXJWYWx1ZShvcmRlclRvdGFsRGlzY291bnQpO1xyXG4gIH1cclxuXHJcbiAgcmV0dXJuIHsgcHJldkRlbGl2ZXJ5UHJpY2UsIHByZXZQcm9kdWN0UHJpY2UsIHByZXZUb3RhbERpc2NvdW50IH07XHJcbn1cclxuXHJcbmV4cG9ydCBmdW5jdGlvbiB1cGRhdGVUb3RhbFJlc3VsdHMoKSB7XHJcbiAgY29uc3QgY29uZmlybVRhYmxlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcclxuICAgIFwiLm10Y2hlY2stY29uZmlybSAubXRjaGVjay10YWJsZVwiXHJcbiAgKTtcclxuICBjb25zdCBvcmRlclRhYmxlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIi5tdGNoZWNrLXN1bW1hcnkgLm10Y2hlY2stdGFibGVcIik7XHJcblxyXG4gIC8vINCS0YXQvtC00L3Ri9C1INC00LDQvdC90YvQtSDQtNC70Y8g0YDQsNGB0YfQtdGC0LBcclxuXHJcbiAgY29uc3QgYm9udXNCYWxscyA9IDQwMDtcclxuICBjb25zdCBjb25zdW1lcnNHcm91cERpc2NvdW50ID0gMC4wNTtcclxuICBjb25zdCB0b3RhbFN1bW1EaXNjb3VudCA9IDAuMDM7XHJcblxyXG4gIGNvbnN0IGRlbGl2ZXJ5TGFiZWwgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFxyXG4gICAgXCJbbmFtZT1kZWxpdmVyeS10eXBlXTpjaGVja2VkXCJcclxuICApLnBhcmVudEVsZW1lbnQ7XHJcbiAgY29uc3QgZGVsaXZlcnlUeXBlID0gZGVsaXZlcnlMYWJlbC5xdWVyeVNlbGVjdG9yKFwiLnJhZGlvX190ZXh0XCIpLnRleHRDb250ZW50O1xyXG4gIGNvbnN0IGRlbGl2ZXJ5UHJpY2UgPSArZGVsaXZlcnlMYWJlbC5kYXRhc2V0LnByaWNlO1xyXG4gIGNvbnN0IHBheVR5cGUgPSBkb2N1bWVudFxyXG4gICAgLnF1ZXJ5U2VsZWN0b3IoXCJbbmFtZT1wYXktdHlwZV06Y2hlY2tlZFwiKVxyXG4gICAgLnBhcmVudEVsZW1lbnQucXVlcnlTZWxlY3RvcihcIi5yYWRpb19fdGV4dFwiKS50ZXh0Q29udGVudDtcclxuXHJcbiAgLy8g0KDQsNGB0YfQtdGCINGE0LjQvdCw0LvRjNC90L7QuSDRhtC10L3Ri1xyXG4gIGNvbnN0IHsgcHJpY2UgfSA9IGNhbGN1bGF0ZVByb2R1Y3RzUHJpY2UoKTtcclxuXHJcbiAgY29uc3QgeyB0b3RhbFByaWNlLCB0b3RhbERpc2NvdW50IH0gPSBjYWxjdWxhdGVUb3RhbFByaWNlKFxyXG4gICAgYm9udXNCYWxscyxcclxuICAgIGNvbnN1bWVyc0dyb3VwRGlzY291bnQsXHJcbiAgICB0b3RhbFN1bW1EaXNjb3VudCxcclxuICAgIGRlbGl2ZXJ5UHJpY2VcclxuICApO1xyXG5cclxuICBjb25zdCB7IHByZXZEZWxpdmVyeVByaWNlLCBwcmV2UHJvZHVjdFByaWNlLCBwcmV2VG90YWxEaXNjb3VudCB9ID1cclxuICAgIGdldFByZXZEYXRhKCk7XHJcblxyXG4gIC8vINCk0L7RgNC80LjRgNC+0LLQsNC90LjQtSDRgtCw0LHQu9C40YYg0L/QviDRiNCw0LHQu9C+0L3RgyDRgdC+INGB0LLQvtC00LrQvtC5INC00LDQvdC90YvRhSDQv9C+INC30LDQutCw0LfRg1xyXG4gIGNvbmZpcm1UYWJsZS5pbm5lckhUTUwgPSBnZXRDb25maXJtVGFibGVUZW1wbGF0ZShcclxuICAgIGRlbGl2ZXJ5VHlwZSxcclxuICAgIHByZXZEZWxpdmVyeVByaWNlLFxyXG4gICAgcGF5VHlwZSxcclxuICAgIGJvbnVzQmFsbHMsXHJcbiAgICBjb25zdW1lcnNHcm91cERpc2NvdW50LFxyXG4gICAgcHJpY2UsXHJcbiAgICB0b3RhbFN1bW1EaXNjb3VudFxyXG4gICk7XHJcbiAgb3JkZXJUYWJsZS5pbm5lckhUTUwgPSBnZXRPcmRlclRhYmxlVGVtcGxhdGUoXHJcbiAgICBkZWxpdmVyeVR5cGUsXHJcbiAgICBwcmV2RGVsaXZlcnlQcmljZSxcclxuICAgIHBheVR5cGUsXHJcbiAgICBwcmV2UHJvZHVjdFByaWNlLFxyXG4gICAgcHJldlRvdGFsRGlzY291bnRcclxuICApO1xyXG5cclxuICBjb25zdCBkYXRhRm9yVXBkYXRlID0ge1xyXG4gICAgY29uZmlybURlbGl2ZXJ5UHJpY2U6IHtcclxuICAgICAgcGxhY2U6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwiY29uZmlybS1kZWxpdmVyeS1wcmljZVwiKSxcclxuICAgICAgY3VycmVudDogZGVsaXZlcnlQcmljZSxcclxuICAgIH0sXHJcbiAgICAvLyBjb25maXJtQ29uc3VtZXJzR3JvdXBEaXNjb3VudDoge1xyXG4gICAgLy8gICBwbGFjZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJjb25maXJtLWNvbnN1bWVycy1ncm91cC1kaXNjb3VudFwiKSxcclxuICAgIC8vICAgY3VycmVudDogY29uc3VtZXJzR3JvdXBEaXNjb3VudCAqIHByaWNlLFxyXG4gICAgLy8gfSxcclxuICAgIG9yZGVyRGVsaXZlcnlQcmljZToge1xyXG4gICAgICBwbGFjZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJvcmRlci1kZWxpdmVyeS1wcmljZVwiKSxcclxuICAgICAgY3VycmVudDogZGVsaXZlcnlQcmljZSxcclxuICAgIH0sXHJcbiAgICBvcmRlclByb2R1Y3RzUHJpY2U6IHtcclxuICAgICAgcGxhY2U6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwib3JkZXItcHJvZHVjdHMtcHJpY2VcIiksXHJcbiAgICAgIGN1cnJlbnQ6IHByaWNlLFxyXG4gICAgfSxcclxuICAgIG9yZGVyVG90YWxEaXNjb3VudDoge1xyXG4gICAgICBwbGFjZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJvcmRlci10b3RhbC1kaXNjb3VudFwiKSxcclxuICAgICAgY3VycmVudDogdG90YWxEaXNjb3VudCxcclxuICAgIH0sXHJcbiAgICBjb25maXJtVG90YWxQcmljZToge1xyXG4gICAgICBwbGFjZTogZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIi5tdGNoZWNrLXRvdGFsX19zdW1cIiksXHJcbiAgICAgIGN1cnJlbnQ6IHRvdGFsUHJpY2UsXHJcbiAgICB9LFxyXG4gICAgb2ZmZXJUb3RhbFByaWNlOiB7XHJcbiAgICAgIHBsYWNlOiBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiLm10Y2hlY2stc3VtbWFyeV9fc3VtXCIpLFxyXG4gICAgICBjdXJyZW50OiB0b3RhbFByaWNlLFxyXG4gICAgfSxcclxuICB9O1xyXG5cclxuICB1cGRhdGVUb3RhbERhdGEoZGF0YUZvclVwZGF0ZSk7XHJcblxyXG4gIHJldHVybiB0b3RhbFByaWNlO1xyXG59XHJcblxyXG5leHBvcnQgZnVuY3Rpb24gdXBkYXRlVG90YWxEYXRhKGRhdGEpIHtcclxuICBmb3IgKGNvbnN0IGtleSBpbiBkYXRhKSB7XHJcbiAgICBjaGFuZ2VWYWx1ZVdpdGhBbmltYXRpb24oZGF0YVtrZXldLnBsYWNlLCBkYXRhW2tleV0uY3VycmVudCk7XHJcbiAgfVxyXG59XHJcbiIsImltcG9ydCB7IGFkZFdhcm5pbmcsIHJlbW92ZVdhcm5pbmcgfSBmcm9tIFwiLi4vdXRpbHNcIjtcclxuXHJcbmNvbnN0IGlzRW1wdHkgPSAoZWxlbSkgPT4ge1xyXG4gIGlmICghZWxlbS52YWx1ZSkge1xyXG4gICAgZWxlbS5jbGFzc0xpc3QuYWRkKFwiaW5wdXQtLWRhbmdlclwiKTtcclxuICAgIGFkZFdhcm5pbmcoZWxlbSwgXCLQntCx0Y/Qt9Cw0YLQtdC70YzQvdC+INC00LvRjyDQt9Cw0L/QvtC70LXQvdC40Y9cIik7XHJcbiAgICByZXR1cm4gdHJ1ZTtcclxuICB9IGVsc2Uge1xyXG4gICAgZWxlbS5jbGFzc0xpc3QucmVtb3ZlKFwiaW5wdXQtLWRhbmdlclwiKTtcclxuICAgIHJlbW92ZVdhcm5pbmcoZWxlbSk7XHJcbiAgICByZXR1cm4gZmFsc2U7XHJcbiAgfVxyXG59O1xyXG5cclxuY29uc3QgaXNFcXVhbCA9IChwYXNzd29yZCwgZWxlbSkgPT4ge1xyXG4gIGlmIChwYXNzd29yZCAhPT0gZWxlbS52YWx1ZSkge1xyXG4gICAgZWxlbS5jbGFzc0xpc3QuYWRkKFwiaW5wdXQtLWRhbmdlclwiKTtcclxuICAgIGFkZFdhcm5pbmcoZWxlbSwgXCLQn9Cw0YDQvtC70Lgg0L3QtSDRgdC+0LLQv9Cw0LTQsNGO0YJcIik7XHJcbiAgICByZXR1cm4gZmFsc2U7XHJcbiAgfSBlbHNlIHtcclxuICAgIGVsZW0uY2xhc3NMaXN0LnJlbW92ZShcImlucHV0LS1kYW5nZXJcIik7XHJcbiAgICByZW1vdmVXYXJuaW5nKGVsZW0pO1xyXG4gICAgcmV0dXJuIHRydWU7XHJcbiAgfVxyXG59O1xyXG5cclxuZXhwb3J0IGNvbnN0IHZhbGlkYXRlRGF0YSA9IChmb3JtKSA9PiB7XHJcbiAgbGV0IGZsYWcgPSB0cnVlO1xyXG4gIGxldCBwYXNzd29yZDtcclxuXHJcbiAgY29uc3QgaW5wdXBFbGVtcyA9IGZvcm0ucXVlcnlTZWxlY3RvckFsbChcImlucHV0XCIpO1xyXG5cclxuICBbLi4uaW5wdXBFbGVtc10uZm9yRWFjaCgoaW5wdXQpID0+IHtcclxuICAgIGlmIChcclxuICAgICAgaW5wdXQubmFtZSA9PT0gXCJmaXJzdE5hbWVcIiB8fFxyXG4gICAgICBpbnB1dC5uYW1lID09PSBcImxhc3ROYW1lXCIgfHxcclxuICAgICAgaW5wdXQubmFtZSA9PT0gXCJtaWRkbGVOYW1lXCIgfHxcclxuICAgICAgaW5wdXQubmFtZSA9PT0gXCJjb21wYW55XCIgfHxcclxuICAgICAgaW5wdXQubmFtZSA9PT0gXCJlbWFpbFwiIHx8XHJcbiAgICAgIGlucHV0Lm5hbWUgPT09IFwiaW5kZXhcIiB8fFxyXG4gICAgICBpbnB1dC5uYW1lID09PSBcImNpdHlcIiB8fFxyXG4gICAgICBpbnB1dC5uYW1lID09PSBcImFkZHJlc3NcIiB8fFxyXG4gICAgICBpbnB1dC50eXBlID09PSBcImNoZWNrYm94XCJcclxuICAgIClcclxuICAgICAgcmV0dXJuO1xyXG5cclxuICAgIGlmIChpc0VtcHR5KGlucHV0KSkgcmV0dXJuIChmbGFnID0gZmFsc2UpO1xyXG5cclxuICAgIGlmIChpbnB1dC5uYW1lID09PSBcInBhc3N3b3JkXCIpIHBhc3N3b3JkID0gaW5wdXQudmFsdWU7XHJcbiAgICBpZiAoaW5wdXQubmFtZSA9PT0gXCJwYXNzd29yZC1hZ2FpblwiKSB7XHJcbiAgICAgIGlmICghaXNFcXVhbChwYXNzd29yZCwgaW5wdXQpKSByZXR1cm4gKGZsYWcgPSBmYWxzZSk7XHJcbiAgICB9XHJcbiAgfSk7XHJcblxyXG4gIHJldHVybiBmbGFnO1xyXG59O1xyXG4iLCJpbXBvcnQgeyBub3JtYWxpemVQcmljZSB9IGZyb20gXCIuL3V0aWxzXCJcclxuXHJcbmV4cG9ydCBjb25zdCBwcm9kdWN0TWFya3VwID0gKHtcclxuICBpZCxcclxuICBpc0RlbGV0ZWQsXHJcbiAgbmFtZSxcclxuICBpbWFnZVVybCxcclxuICBwcm9wZXJ0aWVzLFxyXG4gIGNvdW50LFxyXG4gIG1heENvdW50LFxyXG4gIHByaWNlXHJcbn0pID0+IChcclxuICAhaXNEZWxldGVkXHJcbiAgICA/IGBcclxuICAgICAgPGFydGljbGUgY2xhc3M9XCJtdGNoZWNrLXByb2R1Y3RcIiBkYXRhLWlkPVwiJHtpZH1cIiBkYXRhLXByaWNlPVwiJHtwcmljZX1cIj5cclxuICAgICAgICA8ZGl2IGNsYXNzPVwibXRjaGVjay1wcm9kdWN0X19sZWZ0XCI+XHJcbiAgICAgICAgICA8bGFiZWwgY2xhc3M9XCJjaGVja2JveFwiPlxyXG4gICAgICAgICAgICA8aW5wdXQgdHlwZT1cImNoZWNrYm94XCIgY2xhc3M9XCJ2aXN1YWxseS1oaWRkZW4gY2hlY2tib3hfX2lucHV0XCI+XHJcbiAgICAgICAgICAgIDxzcGFuIGNsYXNzPVwiY2hlY2tib3hfX2Rpc3BsYXlcIj48L3NwYW4+XHJcbiAgICAgICAgICA8L2xhYmVsPlxyXG4gICAgICAgICAgPGEgaHJlZj1cIiNcIiBjbGFzcz1cIm10Y2hlY2stcHJvZHVjdF9faW1hZ2VcIj5cclxuICAgICAgICAgICAgPGltZyBzcmM9XCIke2ltYWdlVXJsfVwiIGFsdD1cItCY0LfQvtCx0YDQsNC20LXQvdC40LUg0YLQvtCy0LDRgNCwXCI+XHJcbiAgICAgICAgICA8L2E+XHJcbiAgICAgICAgICA8ZGl2IGNsYXNzPVwibXRjaGVjay1wcm9kdWN0X19pbmZvXCI+XHJcbiAgICAgICAgICAgIDxhIGhyZWY9XCIjXCIgY2xhc3M9XCJ0ZXh0IHRleHQtLW1kIHRleHQtLXByaW1hcnkgbXRjaGVjay1wcm9kdWN0X19uYW1lXCI+JHtuYW1lfTwvYT5cclxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cIm10Y2hlY2stcHJvZHVjdF9fcHJvcGVydGllc1wiPlxyXG4gICAgICAgICAgICAgICR7cHJvcGVydGllc1xyXG4gICAgICAgICAgICAgICAgPyBwcm9wZXJ0aWVzLm1hcCgoeyBrZXksIHZhbHVlIH0pID0+IChgXHJcbiAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJ0ZXh0IHRleHQtLXhzIHRleHQtLXNlY29uZGFyeSBtdGNoZWNrLXByb2R1Y3RfX3Byb3BlcnR5XCI+XHJcbiAgICAgICAgICAgICAgICAgICAgICAke2tleX06ICR7dmFsdWV9XHJcbiAgICAgICAgICAgICAgICAgICAgPC9zcGFuPlxyXG4gICAgICAgICAgICAgICAgICBgKSkuam9pbihcIlwiKVxyXG4gICAgICAgICAgICAgICAgOiBcIlwiXHJcbiAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgIDwvZGl2PlxyXG4gICAgICAgIDxkaXYgY2xhc3M9XCJtdGNoZWNrLXByb2R1Y3RfX3JpZ2h0XCI+XHJcbiAgICAgICAgICA8ZGl2IGNsYXNzPVwibXRjaGVjay1wcm9kdWN0X19jb3VudFwiPlxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY291bnRlclwiPlxyXG4gICAgICAgICAgICAgIDxpbnB1dCB0eXBlPVwibnVtYmVyXCIgdmFsdWU9XCIke2NvdW50fVwiIG1pbj1cIjFcIiBkYXRhLW1heD1cIiR7bWF4Q291bnR9XCIgY2xhc3M9XCJjb3VudGVyX19kaXNwbGF5XCI+XHJcbiAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJidXR0b25cIiB2YWx1ZT1cIi1cIiBjbGFzcz1cImJ0bi1yZXNldCBjb3VudGVyX19hY3Rpb24gY291bnRlcl9fYWN0aW9uLS1kZWNyZW1lbnRcIj5cclxuICAgICAgICAgICAgICA8aW5wdXQgdHlwZT1cImJ1dHRvblwiIHZhbHVlPVwiK1wiIGNsYXNzPVwiYnRuLXJlc2V0IGNvdW50ZXJfX2FjdGlvbiBjb3VudGVyX19hY3Rpb24tLWluY3JlbWVudFwiPlxyXG4gICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgPGRpdiBjbGFzcz1cIm10Y2hlY2stcHJvZHVjdF9fd3JhcHBlclwiPlxyXG4gICAgICAgICAgICA8cCBjbGFzcz1cInRleHQgdGV4dC0tbWQgdGV4dC0tcHJpbWFyeSBtdGNoZWNrLXByb2R1Y3RfX3ByaWNlXCI+JHtub3JtYWxpemVQcmljZShwcmljZSAqIGNvdW50KX0gPHNwYW4+JiM4MzgxOzwvc3Bhbj48L3A+XHJcbiAgICAgICAgICAgIDxidXR0b24gY2xhc3M9XCJidG4tcmVzZXQgbXRjaGVjay1wcm9kdWN0X19yZW1vdmVcIiBhcmlhLWxhYmVsPVwi0KPQtNCw0LvQuNGC0Ywg0YLQvtCy0LDRgCDQuNC3INC60L7RgNC30LjQvdGLXCI+PC9idXR0b24+XHJcbiAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICA8L2Rpdj5cclxuICAgICAgPC9hcnRpY2xlPlxyXG4gICAgICBgXHJcbiAgICA6IGBcclxuICAgICAgPGFydGljbGUgY2xhc3M9XCJtdGNoZWNrLXByb2R1Y3QgbXRjaGVjay1wcm9kdWN0LS1kZWxldGVkXCIgZGF0YS1pZD1cIiR7aWR9XCI+XHJcbiAgICAgICAgPGRpdiBjbGFzcz1cIm10Y2hlY2stcHJvZHVjdF9fbGVmdFwiPlxyXG4gICAgICAgICAgPGRpdiBjbGFzcz1cIm10Y2hlY2stcHJvZHVjdF9faW5mb1wiPlxyXG4gICAgICAgICAgICA8cCBjbGFzcz1cInRleHQgbXRjaGVjay1wcm9kdWN0X19uYW1lXCI+JHtuYW1lfTwvcD5cclxuICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgIDwvZGl2PlxyXG4gICAgICAgIDxkaXYgY2xhc3M9XCJtdGNoZWNrLXByb2R1Y3RfX3JpZ2h0XCI+XHJcbiAgICAgICAgICA8YnV0dG9uIGNsYXNzPVwiYnRuLXJlc2V0IGxpbmsgbXRjaGVjay1wcm9kdWN0X19yZXN0b3JlXCIgZGF0YS1hY3Rpb249XCJwcm9kdWN0LXJlc3RvcmVcIj7QstC+0YHRgdGC0LDQvdC+0LLQuNGC0Yw8L2J1dHRvbj5cclxuICAgICAgICAgIDxkaXYgY2xhc3M9XCJtdGNoZWNrLXByb2R1Y3RfX3dyYXBwZXJcIj5cclxuICAgICAgICAgICAgPGJ1dHRvbiBjbGFzcz1cImJ0bi1yZXNldCBtdGNoZWNrLXByb2R1Y3RfX3JlbW92ZVwiIGFyaWEtbGFiZWw9XCLQn9C+0LvQvdC+0YHRgtGM0Y4g0YPQtNCw0LvQuNGC0Ywg0YLQvtCy0LDRgCDQuNC3INC60L7RgNC30LjQvdGLXCI+PC9idXR0b24+XHJcbiAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICA8L2Rpdj5cclxuICAgICAgPC9hcnRpY2xlPlxyXG4gICAgICBgXHJcbilcclxuIiwiZXhwb3J0IGNvbnN0IGdldFByb2R1Y3RzRnJvbVN0b3JhZ2UgPSAoKSA9PlxyXG4gIEpTT04ucGFyc2UobG9jYWxTdG9yYWdlLmdldEl0ZW0oXCJwcm9kdWN0c1wiKSk7XHJcblxyXG5leHBvcnQgY29uc3QgZ2V0UHJvZHVjdHNDb3VudEZyb21TdG9yYWdlID0gKCkgPT5cclxuICBKU09OLnBhcnNlKGxvY2FsU3RvcmFnZS5nZXRJdGVtKFwicHJvZHVjdHNcIikpLmZpbHRlcihcclxuICAgICh7IGlzRGVsZXRlZCB9KSA9PiAhaXNEZWxldGVkXHJcbiAgKS5sZW5ndGg7XHJcblxyXG5leHBvcnQgY29uc3QgZ2V0UHJvZHVjdEZyb21TdG9yYWdlID0gKGlkKSA9PiB7XHJcbiAgY29uc3QgcHJvZHVjdHMgPSBnZXRQcm9kdWN0c0Zyb21TdG9yYWdlKCk7XHJcbiAgY29uc3QgaW5kZXggPSBwcm9kdWN0cy5maW5kSW5kZXgoKHByb2R1Y3QpID0+IHByb2R1Y3QuaWQgPT09IGlkKTtcclxuXHJcbiAgcmV0dXJuIHByb2R1Y3RzW2luZGV4XTtcclxufTtcclxuXHJcbmV4cG9ydCBjb25zdCByZW1vdmVQcm9kdWN0RnJvbVN0b3JhZ2UgPSAoaWQpID0+IHtcclxuICBjb25zdCBwcm9kdWN0cyA9IGdldFByb2R1Y3RzRnJvbVN0b3JhZ2UoKTtcclxuICBjb25zdCBpbmRleCA9IHByb2R1Y3RzLmZpbmRJbmRleCgocHJvZHVjdCkgPT4gcHJvZHVjdC5pZCA9PT0gaWQpO1xyXG5cclxuICBjb25zdCBwcm9kdWN0ID0gcHJvZHVjdHNbaW5kZXhdO1xyXG5cclxuICBwcm9kdWN0LmlzRGVsZXRlZFxyXG4gICAgPyBwcm9kdWN0cy5zcGxpY2UoaW5kZXgsIDEpXHJcbiAgICA6IChwcm9kdWN0c1tpbmRleF1bXCJpc0RlbGV0ZWRcIl0gPSB0cnVlKTtcclxuXHJcbiAgbG9jYWxTdG9yYWdlLnNldEl0ZW0oXCJwcm9kdWN0c1wiLCBKU09OLnN0cmluZ2lmeShwcm9kdWN0cykpO1xyXG59O1xyXG5cclxuZXhwb3J0IGNvbnN0IHJlc3RvcmVQcm9kdWN0RnJvbVN0b3JhZ2UgPSAoaWQpID0+IHtcclxuICBjb25zdCBwcm9kdWN0cyA9IGdldFByb2R1Y3RzRnJvbVN0b3JhZ2UoKTtcclxuICBjb25zdCBpbmRleCA9IHByb2R1Y3RzLmZpbmRJbmRleCgocHJvZHVjdCkgPT4gcHJvZHVjdC5pZCA9PT0gaWQpO1xyXG5cclxuICBwcm9kdWN0c1tpbmRleF1bXCJpc0RlbGV0ZWRcIl0gPSBmYWxzZTtcclxuXHJcbiAgbG9jYWxTdG9yYWdlLnNldEl0ZW0oXCJwcm9kdWN0c1wiLCBKU09OLnN0cmluZ2lmeShwcm9kdWN0cykpO1xyXG59O1xyXG5cclxuLy8gKiDQo9C00LDQu9C40YLRjCDRjdGC0L4sINC70LjRiNGMINC00LvRjyDRgtC10YHRgtCwXHJcblxyXG5pZiAoIWdldFByb2R1Y3RzRnJvbVN0b3JhZ2UoKSkge1xyXG4gIGxvY2FsU3RvcmFnZS5zZXRJdGVtKFxyXG4gICAgXCJwcm9kdWN0c1wiLFxyXG4gICAgSlNPTi5zdHJpbmdpZnkoW1xyXG4gICAgICB7XHJcbiAgICAgICAgaWQ6IFwiMVwiLFxyXG4gICAgICAgIG5hbWU6IFwi0KLQsNC50LzQtdGAINC60YPRhdC+0L3QvdGL0LksINGN0LvQtdC60YLRgNC+0L3QvdGL0LksIEJhbGRyXCIsXHJcbiAgICAgICAgaW1hZ2VVcmw6IFwiLi9pbWFnZS9wcm9kdWN0cy9wcm9kdWN0LTEucG5nXCIsXHJcbiAgICAgICAgcHJvcGVydGllczogW3sga2V5OiBcItCR0YDQtdC90LRcIiwgdmFsdWU6IFwiQmFsZHJcIiB9XSxcclxuICAgICAgICBjb3VudDogMSxcclxuICAgICAgICBtYXhDb3VudDogNSxcclxuICAgICAgICBwcmljZTogMTI2MjgxLFxyXG4gICAgICB9LFxyXG4gICAgICB7XHJcbiAgICAgICAgaWQ6IFwiMlwiLFxyXG4gICAgICAgIG5hbWU6IFwi0JPQsNC30L7QsdC10YLQvtC90L3Ri9C5INGB0YLQtdC90L7QstC+0Lkg0LHQu9C+0LogRDQwMCA2MDB4MzAweDI1MFwiLFxyXG4gICAgICAgIGltYWdlVXJsOiBcIi4vaW1hZ2UvcHJvZHVjdHMvcHJvZHVjdC0yLnBuZ1wiLFxyXG4gICAgICAgIHByb3BlcnRpZXM6IFtcclxuICAgICAgICAgIHsga2V5OiBcItCm0LLQtdGCXCIsIHZhbHVlOiBcItCx0LXQttC10LLRi9C5XCIgfSxcclxuICAgICAgICAgIHsga2V5OiBcItCa0L7QvNC/0LvQtdC60YJcIiwgdmFsdWU6IFwi0L/QvtC70L3Ri9C5XCIgfSxcclxuICAgICAgICAgIHsga2V5OiBcItCh0L7RgdGC0L7Rj9C90LjQtVwiLCB2YWx1ZTogXCLQvdC+0LLRi9C5XCIgfSxcclxuICAgICAgICAgIHsga2V5OiBcItCg0LDQt9C80LXRgFwiLCB2YWx1ZTogXCIyOFwiIH0sXHJcbiAgICAgICAgICB7IGtleTogXCLQptCy0LXRgiDRgNCw0LzQutC4XCIsIHZhbHVlOiBcItC30L7Qu9C+0YLQvtC5XCIgfSxcclxuICAgICAgICBdLFxyXG4gICAgICAgIGNvdW50OiAyLFxyXG4gICAgICAgIG1heENvdW50OiA4LFxyXG4gICAgICAgIHByaWNlOiAxMjQyLFxyXG4gICAgICB9LFxyXG4gICAgICB7XHJcbiAgICAgICAgaWQ6IFwiM1wiLFxyXG4gICAgICAgIGlzRGVsZXRlZDogdHJ1ZSxcclxuICAgICAgICBuYW1lOiBcItCg0L7QsdC+0YIt0L/Ri9C70LXRgdC+0YEgUFZDUiAwNzI2VyAoUE9MQVJJUyksIFBvbGFyaXMg0LHQtdC20LXQstGL0LlcIixcclxuICAgICAgICBpbWFnZVVybDogXCIuL2ltYWdlL3Byb2R1Y3RzL3Byb2R1Y3QtMi5wbmdcIixcclxuICAgICAgICBwcm9wZXJ0aWVzOiBbXHJcbiAgICAgICAgICB7IGtleTogXCLQptCy0LXRglwiLCB2YWx1ZTogXCLQsdC10LbQtdCy0YvQuVwiIH0sXHJcbiAgICAgICAgICB7IGtleTogXCLQmtC+0LzQv9C70LXQutGCXCIsIHZhbHVlOiBcItC/0L7Qu9C90YvQuVwiIH0sXHJcbiAgICAgICAgICB7IGtleTogXCLQodC+0YHRgtC+0Y/QvdC40LVcIiwgdmFsdWU6IFwi0L3QvtCy0YvQuVwiIH0sXHJcbiAgICAgICAgICB7IGtleTogXCLQoNCw0LfQvNC10YBcIiwgdmFsdWU6IFwiMjhcIiB9LFxyXG4gICAgICAgICAgeyBrZXk6IFwi0KbQstC10YIg0YDQsNC80LrQuFwiLCB2YWx1ZTogXCLQt9C+0LvQvtGC0L7QuVwiIH0sXHJcbiAgICAgICAgXSxcclxuICAgICAgICBjb3VudDogMTEsXHJcbiAgICAgICAgbWF4Q291bnQ6IDEyNCxcclxuICAgICAgICBwcmljZTogNjgxLFxyXG4gICAgICB9LFxyXG4gICAgICB7XHJcbiAgICAgICAgaWQ6IFwiNFwiLFxyXG4gICAgICAgIG5hbWU6IFwi0KDQvtCx0L7Rgi3Qv9GL0LvQtdGB0L7RgSBQVkNSIDA3MjZXIChQT0xBUklTKSwgUG9sYXJpcyDQsdC10LbQtdCy0YvQuSDRhtCy0LXRgiDQvtGE0LjRhtC40LDQu9GM0L3Ri9C5INC80LDQs9Cw0LfQuNC9INCf0L7Qu9Cw0YDQuNGBXCIsXHJcbiAgICAgICAgaW1hZ2VVcmw6IFwiLi9pbWFnZS9wcm9kdWN0cy9wcm9kdWN0LTMucG5nXCIsXHJcbiAgICAgICAgcHJvcGVydGllczogW1xyXG4gICAgICAgICAgeyBrZXk6IFwi0KbQstC10YJcIiwgdmFsdWU6IFwi0LHQtdC20LXQstGL0LlcIiB9LFxyXG4gICAgICAgICAgeyBrZXk6IFwi0JrQvtC80L/Qu9C10LrRglwiLCB2YWx1ZTogXCLQv9C+0LvQvdGL0LlcIiB9LFxyXG4gICAgICAgICAgeyBrZXk6IFwi0KHQvtGB0YLQvtGP0L3QuNC1XCIsIHZhbHVlOiBcItC90L7QstGL0LlcIiB9LFxyXG4gICAgICAgICAgeyBrZXk6IFwi0KDQsNC30LzQtdGAXCIsIHZhbHVlOiBcIjI4XCIgfSxcclxuICAgICAgICAgIHsga2V5OiBcItCm0LLQtdGCINGA0LDQvNC60LhcIiwgdmFsdWU6IFwi0LfQvtC70L7RgtC+0LlcIiB9LFxyXG4gICAgICAgIF0sXHJcbiAgICAgICAgY291bnQ6IDExLFxyXG4gICAgICAgIG1heENvdW50OiAyMCxcclxuICAgICAgICBwcmljZTogNjgxLFxyXG4gICAgICB9LFxyXG4gICAgXSlcclxuICApO1xyXG59XHJcbiIsImltcG9ydCB7IGNyZWF0ZU1lc3NhZ2UgfSBmcm9tIFwiLi9jb21wb25lbnRzL21lc3NhZ2VcIjtcclxuXHJcbmV4cG9ydCBjb25zdCBub3JtYWxpemVQcmljZSA9IChzdHIpID0+IHtcclxuICByZXR1cm4gU3RyaW5nKHN0cikucmVwbGFjZSgvKFxcZCkoPz0oXFxkXFxkXFxkKSsoW15cXGRdfCQpKS9nLCBcIiQxIFwiKTtcclxufTtcclxuXHJcbmV4cG9ydCBjb25zdCBnZXROdW1iZXJWYWx1ZSA9IChlbGVtKSA9PlxyXG4gICtlbGVtLnRleHRDb250ZW50Lm1hdGNoKC9bMC05XS9nKS5qb2luKFwiXCIpO1xyXG5cclxuZXhwb3J0IGZ1bmN0aW9uIGNoYW5nZVZhbHVlV2l0aEFuaW1hdGlvbihwbGFjZSwgY3VycmVudCkge1xyXG4gIGxldCBwcmV2UHJpY2UgPSArcGxhY2UudGV4dENvbnRlbnQubWF0Y2goL1swLTldL2cpLmpvaW4oXCJcIik7XHJcblxyXG4gIGNvbnN0IHN0YXJ0ID0gcHJldlByaWNlO1xyXG4gIGNvbnN0IHN0b3AgPSBjdXJyZW50O1xyXG4gIGxldCBhbmltYXRpb25TdGFydDtcclxuICBsZXQgcmVxdWVzdElkID0gd2luZG93LnJlcXVlc3RBbmltYXRpb25GcmFtZShhbmltYXRlKTtcclxuXHJcbiAgZnVuY3Rpb24gYW5pbWF0ZSh0aW1lc3RhbXApIHtcclxuICAgIGlmICghYW5pbWF0aW9uU3RhcnQpIHtcclxuICAgICAgYW5pbWF0aW9uU3RhcnQgPSB0aW1lc3RhbXA7XHJcbiAgICB9XHJcblxyXG4gICAgY29uc3QgcHJvZ3Jlc3MgPSB0aW1lc3RhbXAgLSBhbmltYXRpb25TdGFydDtcclxuXHJcbiAgICBjb25zdCBzcGVlZCA9XHJcbiAgICAgIHByb2dyZXNzICpcclxuICAgICAgKHByZXZQcmljZSA+IGN1cnJlbnQgPyAoc3RhcnQgLSBzdG9wKSAvIDUwMDAgOiAoc3RvcCAtIHN0YXJ0KSAvIDUwMDApO1xyXG5cclxuICAgIHByZXZQcmljZSA9IHByZXZQcmljZSA+IGN1cnJlbnQgPyBwcmV2UHJpY2UgLSBzcGVlZCA6IHByZXZQcmljZSArIHNwZWVkO1xyXG5cclxuICAgIGlmIChzdGFydCA+IHN0b3AgJiYgcHJldlByaWNlID4gY3VycmVudCkge1xyXG4gICAgICB3aW5kb3cucmVxdWVzdEFuaW1hdGlvbkZyYW1lKGFuaW1hdGUpO1xyXG4gICAgfSBlbHNlIGlmIChzdGFydCA8IHN0b3AgJiYgcHJldlByaWNlIDwgY3VycmVudCkge1xyXG4gICAgICB3aW5kb3cucmVxdWVzdEFuaW1hdGlvbkZyYW1lKGFuaW1hdGUpO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgcHJldlByaWNlID0gY3VycmVudDtcclxuICAgICAgd2luZG93LmNhbmNlbEFuaW1hdGlvbkZyYW1lKHJlcXVlc3RJZCk7XHJcbiAgICB9XHJcbiAgICBwbGFjZS5jaGlsZE5vZGVzWzBdLnRleHRDb250ZW50ID0gYCR7bm9ybWFsaXplUHJpY2UoXHJcbiAgICAgIE1hdGguZmxvb3IocHJldlByaWNlKVxyXG4gICAgKX1gO1xyXG4gIH1cclxufVxyXG5cclxuZXhwb3J0IGNvbnN0IGFkZFdhcm5pbmcgPSAoZWxlbSwgdGV4dCkgPT4ge1xyXG4gIGNyZWF0ZU1lc3NhZ2UoXHJcbiAgICB7XHJcbiAgICAgIGNvbnRhaW5lcjogZWxlbS5wYXJlbnROb2RlLFxyXG4gICAgICB0eXBlOiBcImRhbmdlclwiLFxyXG4gICAgICB0ZXh0LFxyXG4gICAgICBpc0Nsb3NlOiBmYWxzZSxcclxuICAgIH0sXHJcbiAgICB7fVxyXG4gICk7XHJcbn07XHJcblxyXG5leHBvcnQgY29uc3QgcmVtb3ZlV2FybmluZyA9IChlbGVtKSA9PiB7XHJcbiAgY29uc3QgbWVzc2FnZSA9IGVsZW0ucGFyZW50Tm9kZS5xdWVyeVNlbGVjdG9yKFwiLm1lc3NhZ2VcIik7XHJcbiAgaWYgKG1lc3NhZ2UpIG1lc3NhZ2UucmVtb3ZlKCk7XHJcbiAgcmV0dXJuO1xyXG59O1xyXG4iLCIvLyBUaGUgbW9kdWxlIGNhY2hlXG52YXIgX193ZWJwYWNrX21vZHVsZV9jYWNoZV9fID0ge307XG5cbi8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG5mdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuXHR2YXIgY2FjaGVkTW9kdWxlID0gX193ZWJwYWNrX21vZHVsZV9jYWNoZV9fW21vZHVsZUlkXTtcblx0aWYgKGNhY2hlZE1vZHVsZSAhPT0gdW5kZWZpbmVkKSB7XG5cdFx0cmV0dXJuIGNhY2hlZE1vZHVsZS5leHBvcnRzO1xuXHR9XG5cdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG5cdHZhciBtb2R1bGUgPSBfX3dlYnBhY2tfbW9kdWxlX2NhY2hlX19bbW9kdWxlSWRdID0ge1xuXHRcdC8vIG5vIG1vZHVsZS5pZCBuZWVkZWRcblx0XHQvLyBubyBtb2R1bGUubG9hZGVkIG5lZWRlZFxuXHRcdGV4cG9ydHM6IHt9XG5cdH07XG5cblx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG5cdF9fd2VicGFja19tb2R1bGVzX19bbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG5cdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG5cdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbn1cblxuIiwiLy8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbl9fd2VicGFja19yZXF1aXJlX18ubiA9IChtb2R1bGUpID0+IHtcblx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG5cdFx0KCkgPT4gKG1vZHVsZVsnZGVmYXVsdCddKSA6XG5cdFx0KCkgPT4gKG1vZHVsZSk7XG5cdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsIHsgYTogZ2V0dGVyIH0pO1xuXHRyZXR1cm4gZ2V0dGVyO1xufTsiLCIvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9ucyBmb3IgaGFybW9ueSBleHBvcnRzXG5fX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSAoZXhwb3J0cywgZGVmaW5pdGlvbikgPT4ge1xuXHRmb3IodmFyIGtleSBpbiBkZWZpbml0aW9uKSB7XG5cdFx0aWYoX193ZWJwYWNrX3JlcXVpcmVfXy5vKGRlZmluaXRpb24sIGtleSkgJiYgIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBrZXkpKSB7XG5cdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywga2V5LCB7IGVudW1lcmFibGU6IHRydWUsIGdldDogZGVmaW5pdGlvbltrZXldIH0pO1xuXHRcdH1cblx0fVxufTsiLCJfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSAob2JqLCBwcm9wKSA9PiAoT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iaiwgcHJvcCkpIiwiLy8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuX193ZWJwYWNrX3JlcXVpcmVfXy5yID0gKGV4cG9ydHMpID0+IHtcblx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG5cdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG5cdH1cblx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbn07IiwiLy8gQ29tcG9uZW50c1xyXG5cclxuaW1wb3J0IHsgZ2V0UHJvZHVjdHNDb3VudEZyb21TdG9yYWdlIH0gZnJvbSBcIi4vc3RvcmFnZVwiO1xyXG5cclxuaW1wb3J0IHsgdXBkYXRlUHJvZHVjdHNTdW1tYXJ5IH0gZnJvbSBcIi4vY29tcG9uZW50cy9wcm9kdWN0c1wiO1xyXG5pbXBvcnQgXCIuL2NvbXBvbmVudHMvY291bnRlclwiO1xyXG5pbXBvcnQgXCIuL2NvbXBvbmVudHMvdGFic1wiO1xyXG5pbXBvcnQgXCIuL2NvbXBvbmVudHMvc2VsZWN0XCI7XHJcbmltcG9ydCBcIi4vY29tcG9uZW50cy9vZmZlcnNcIjtcclxuaW1wb3J0IHsgdG9nZ2xlQWN0aW9ucyB9IGZyb20gXCIuL2NvbXBvbmVudHMvYWN0aW9uc1wiO1xyXG5pbXBvcnQgeyB1cGRhdGVUb3RhbFJlc3VsdHMgfSBmcm9tIFwiLi9jb21wb25lbnRzL3RvdGFsXCI7XHJcbmltcG9ydCBcIi4vY29tcG9uZW50cy9wb3B1cFwiO1xyXG5pbXBvcnQgXCIuL2NvbXBvbmVudHMvdmFsaWRhdGVEYXRhXCI7XHJcbmltcG9ydCBcIi4vY29tcG9uZW50cy9zdWJtaXREYXRhXCI7XHJcblxyXG4vLyBTZWxlY3QgYWxsIHByb2R1Y3RzIG9wdGlvblxyXG5jb25zdCBzZWxlY3RBbGxUZXh0ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcclxuICBcIi5tdGNoZWNrLXByb2R1Y3RzX19oZWFkIC5jaGVja2JveF9fdGV4dFwiXHJcbik7XHJcbmNvbnN0IHNlbGVjdEFsbENoZWNrYm94ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIiNzZWxlY3QtYWxsXCIpO1xyXG5cclxuc2VsZWN0QWxsVGV4dC50ZXh0Q29udGVudCA9IGDQktGL0LHRgNCw0YLRjCDQstGB0LUgKCR7Z2V0UHJvZHVjdHNDb3VudEZyb21TdG9yYWdlKCl9KWA7XHJcbnNlbGVjdEFsbENoZWNrYm94LmFkZEV2ZW50TGlzdGVuZXIoXCJjaGFuZ2VcIiwgdG9nZ2xlU2VsZWN0QWxsKTtcclxuXHJcbi8vIElmIHNlbGVjdCBpcyBhbHJlYWR5IGFjdGl2ZSAoZnJvbSBzdGFydCkgLSB0b2dnbGUgaXRlbXNcclxuXHJcbmlmIChzZWxlY3RBbGxDaGVja2JveC5jaGVja2VkKSB7XHJcbiAgdG9nZ2xlU2VsZWN0QWxsKCk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIHRvZ2dsZVNlbGVjdEFsbCgpIHtcclxuICBjb25zdCBpc0NoZWNrZWQgPSBzZWxlY3RBbGxDaGVja2JveC5jaGVja2VkO1xyXG4gIGNvbnN0IGNoZWNrYm94ZXMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFxyXG4gICAgXCIubXRjaGVjay1wcm9kdWN0X19sZWZ0IC5jaGVja2JveF9faW5wdXRcIlxyXG4gICk7XHJcblxyXG4gIGlmIChpc0NoZWNrZWQpIHtcclxuICAgIGNoZWNrYm94ZXMuZm9yRWFjaCgoY2hlY2tib3gpID0+IChjaGVja2JveC5jaGVja2VkID0gdHJ1ZSkpO1xyXG4gIH0gZWxzZSB7XHJcbiAgICBjaGVja2JveGVzLmZvckVhY2goKGNoZWNrYm94KSA9PiAoY2hlY2tib3guY2hlY2tlZCA9IGZhbHNlKSk7XHJcbiAgfVxyXG5cclxuICB0b2dnbGVBY3Rpb25zKCk7XHJcbiAgdXBkYXRlUHJvZHVjdHNTdW1tYXJ5KCk7XHJcblxyXG4gIHVwZGF0ZVRvdGFsUmVzdWx0cygpO1xyXG59XHJcblxyXG4vLyBEZWxpdmVyeSBvbiBjaGFuZ2VcclxuXHJcbmNvbnN0IGRlbGl2ZXJ5UmFkaW8gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiW25hbWU9ZGVsaXZlcnktdHlwZV1cIik7XHJcblxyXG5kZWxpdmVyeVJhZGlvLmZvckVhY2goKHJhZGlvKSA9PlxyXG4gIHJhZGlvLmFkZEV2ZW50TGlzdGVuZXIoXCJjaGFuZ2VcIiwgKCkgPT4ge1xyXG4gICAgdXBkYXRlVG90YWxSZXN1bHRzKCk7XHJcbiAgfSlcclxuKTtcclxuXHJcbi8vIFBheSBvbiBjaGFuZ2VcclxuXHJcbmNvbnN0IHBheVJhZGlvID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChcIltuYW1lPXBheS10eXBlXVwiKTtcclxuXHJcbnBheVJhZGlvLmZvckVhY2goKHJhZGlvKSA9PlxyXG4gIHJhZGlvLmFkZEV2ZW50TGlzdGVuZXIoXCJjaGFuZ2VcIiwgKCkgPT4ge1xyXG4gICAgdXBkYXRlVG90YWxSZXN1bHRzKCk7XHJcbiAgfSlcclxuKTtcclxuXHJcbi8vIFVwZGF0ZSBwcmljZXMgKGJ5IHNlbGVjdGVkIGVsZW1lbnRzKVxyXG5cclxudXBkYXRlUHJvZHVjdHNTdW1tYXJ5KCk7XHJcblxyXG4vLyBVcGRhdGUgdGFibGVzIChzdW1tYXJ5IC8gdG90YWwpXHJcblxyXG51cGRhdGVUb3RhbFJlc3VsdHMoKTtcclxuIl0sIm5hbWVzIjpbXSwic291cmNlUm9vdCI6IiJ9
