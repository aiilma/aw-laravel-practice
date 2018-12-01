

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });

    $(function () {

        // событие открытия модального окна
        $("#orderDataModalWrapper").on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var orderstatus = button.parents(".row__user__current__order").attr('data-order-status');
            $(this).children().attr('class', 'modal-dialog modal-lg order__dataview ' + orderstatus);
        });

        $("#orderDataModalWrapper").on('hidden.bs.modal', function (event) {
            $(this).children().attr('class', 'modal-dialog modal-lg order__dataview');
        });

    });


    // notifier configurations...
    let notifierCfg = {
        heading: 'Undefined',
        text: 'Whoops! We got an unregistered error :(',
        showHideTransition: 'slide',
        loaderBg: 'rgba(255,226,163, 1)',
        loader: false,
        stack: 1,
        hideAfter: 6500,
        textAlign: 'center',
        position: 'bottom-right',
        bgColor: 'rgba(39, 45, 51, 1)',
    };


    // AJAX. confirm order
    $('.aw__icon__confirm').on('click', (e) => {
        // form data object
        let orderData = {
            _orderHash: $(e.target).parents(".row__user__current__order").children('input[name="_orderHash"]').val(),
            _compHash: $(e.target).parents(".row__user__current__order").children('input[name="_compHash"]').val(),
        };

        // request
        $.ajax({
            url: $(".aw__icon__confirm").attr('data-link'),
            type: "POST",
            data: orderData,
            dataType: 'json',
            success: function (result) {
                console.log(result);
                if (result.done === true) {
                    $(e.target).parents(".row__user__current__order").remove();
                    $('.user__order__dataset').prepend(result.html.order);
                    $('.navbar-nav .aw__ui__balance').html(result.html.uiBalance);

                    // объект кфг уведомления
                    notifierCfg['heading'] = 'Success!';
                    notifierCfg['text'] = 'Your order has been confirmed';

                    $.toast(notifierCfg);
                } else {
                    // объект кфг уведомления
                    notifierCfg['heading'] = 'Whoops!';
                    notifierCfg['text'] = '';

                    for (let key in result.messages) {
                        notifierCfg['text'] = result.messages[key];
                    }

                    $.toast(notifierCfg);
                }
            },
        });

    });

    // AJAX. deny order
    $('.aw__icon__deny').on('click', (e) => {
        // form data object
        let orderData = {
            _orderHash: $(e.target).parents(".row__user__current__order").children('input[name="_orderHash"]').val(),
        };

        // request
        $.ajax({
            url: $(".aw__icon__deny").attr('data-link'),
            type: "POST",
            data: orderData,
            dataType: 'json',
            success: function (result) {

                if (result.done === true) {
                    $(e.target).parents(".row__user__current__order").remove();

                    // объект кфг уведомления
                    notifierCfg['heading'] = 'Success!';
                    notifierCfg['text'] = 'Your unconfirmed order has been removed';

                    $.toast(notifierCfg);
                } else {
                    // объект кфг уведомления
                    notifierCfg['heading'] = 'Whoops!';
                    notifierCfg['text'] = '';

                    for (let key in result.messages) {
                        notifierCfg['text'] = result.messages[key];
                    }

                    $.toast(notifierCfg);
                }
            },
        });

    });



    // AJAX. check data order
    $('.user__order__dataset').on('click', '.aw__btn__check__order__info', function (e) {

        // data object
        let orderData = {
            _orderHash: $(e.target).parents(".row__user__current__order").children('input[name="_orderHash"]').val(),
            _isUnconfirmedOrder: (['cart'].indexOf($(e.target).parents(".row__user__current__order").attr('data-order-status')) > -1) ? true : false,
        };

        let visualizationHtmlPatt = $('.aw__visualization__variants>.aw__form__component').html();
        let backgroundHtmlPatt = $('.aw__user__bg__variant>.aw__form__component').html();

        // request
        $.ajax({
            url: $(".aw__btn__check__order__info").attr('data-link'),
            type: "POST",
            data: orderData,
            dataType: 'json',
            beforeSend: function () {
                visualizationHtmlPatt = $('.aw__visualization__variants>.aw__form__component').html();
                backgroundHtmlPatt = $('.aw__user__bg__variant>.aw__form__component').html();

                $('.aw__visualization__variants>.aw__form__component').html('<img id="visualizationLoader" src="//artworch.com/storage/img/light-loader.svg">');
                $('.aw__user__bg__variant>.aw__form__component').html('<img id="backgroundLoader" src="//artworch.com/storage/img/light-loader.svg">');

                $('#visualizationLoader').show();
                $('#backgroundLoader').show();
            },
            success: function (result) {
                // paste fresh HTML code of two components (visualization, background)
                $('.aw__visualization__variants>.aw__form__component').html(visualizationHtmlPatt);
                $('.aw__user__bg__variant>.aw__form__component').html(backgroundHtmlPatt);


                console.log(result);
                // if data is received...
                if (result.orderData == null) {
                    // объект кфг уведомления
                    notifierCfg['heading'] = 'Whoops!';
                    notifierCfg['text'] = '';

                    for (let key in result.messages) {
                        notifierCfg['text'] = result.messages[key];
                    }

                    $.toast(notifierCfg);
                } else {
                    if (result.orderData.visualization == 0) // is short
                    {
                        $('.aw__visualization__variants>.aw__form__component>#visualCaseShort>input').prop('checked', true);
                        $('.aw__visualization__variants>.aw__form__component>#visualCaseLong>input').prop('checked', false);
                        $('.aw__visualization__variants>.aw__form__component>#visualCaseShort>input').prop('disabled', false);
                    }
                    else // is long
                    {
                        $('.aw__visualization__variants>.aw__form__component>#visualCaseLong>input').prop('checked', true);
                        $('.aw__visualization__variants>.aw__form__component>#visualCaseShort>input').prop('checked', false);
                        $('.aw__visualization__variants>.aw__form__component>#visualCaseLong>input').prop('disabled', false);
                    }

                    $('.aw__user__bg__variant>.aw__form__component>.order__data__background>img').attr('src', result.orderData.background);
                }
            },
            complete: function () {
                $('#visualizationLoader').hide();
                $('#backgroundLoader').hide();

                $('.aw__visualization__variants>.aw__form__component>#visualizationLoader').remove();
                $('.aw__user__bg__variant>.aw__form__component>#backgroundLoader').remove();
            },
        });

    });




});