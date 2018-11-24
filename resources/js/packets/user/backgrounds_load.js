try {
    window.$ = window.jQuery = require('jquery');

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('.user__bg__body>.user-bg-list-layer>ul').html('<img id="loader" src="//artworch.com/storage/img/light-loader.svg">');
                $('#loader').show();
            },
            complete: function(){
                $('#loader').hide();
                $('.user__bg__body>.user-bg-list-layer>ul>#loader').remove();
            },
        });


        // Popover Backgrounds List
        $(function () {
            $('.user__bg__cont>.aw__form__component>button').popover({
            'html' : true,
            'template' : '<div class="popover currently-background" role="tooltip">  \
                            <div class="arrow"> \
                            </div>  \
                            <h3 class="popover-header"></h3>  \
                            <div class="popover-body text-center">  \
                            </div>  \
                            </div>',
            'delay': { "show": 400, "hide": 100 },
            })

            $('#collapseUserBackgrounds').collapse({
                toggle: false
            })
        });


        // AJAX. On refresh backgrounds List + Chooser Background
        $('#refreshUserBackgroundsList').on('click', (e) => {
            $.ajax({
                type: 'POST',
                url: e.target.attributes['data-link'].value,
                success: function(result) {
                    
                    let heading = 'Undefined',
                        text = 'We have no messages for you :(',
                        bgColor = 'rgba(39, 45, 51, 1)';

                    // объект кфг уведомления
                    var noteCfg = {
                        heading: heading,
                        text: text,
                        showHideTransition: 'slide',
                        loaderBg: 'rgba(255,226,163, 1)',
                        loader: false,
                        stack: 1,
                        hideAfter: 6500,
                        textAlign: 'center',
                        position: 'bottom-right',
                        bgColor: bgColor,
                    };


                    if (result.messages.steam !== undefined)
                    {
                        noteCfg['heading'] = 'Whoops!';
                        noteCfg['text'] = '';

                        for (let key in result.messages.steam) {
                            if (result.messages.steam.hasOwnProperty(key)) {
                                noteCfg['text'] = result.messages.steam[key];
                            }
                        }

                        // удаление обработчиков на элементы фонов и очистка списка от HTML содержимого
                        if ($('.user__bg__body>.user-bg-list-layer>ul>.userbg-item').length !== 0) {
                            $('.user__bg__body>.user-bg-list-layer>ul>.userbg-item>button').off('click');
                            $('.user__bg__body>.user-bg-list-layer>ul>').empty();
                        }
                    }
                    // если фоны не отсутствуют...
                    if (result.backgrounds !== undefined)
                    {
                        noteCfg['heading'] = 'OK!';
                        noteCfg['text'] = 'Your inventory has been successfully refreshed';

                        $('.user__bg__body>.user-bg-list-layer>ul').html(result.backgrounds);

                        // установка обработчиков на элементы фонов
                        if ($('.user__bg__body>.user-bg-list-layer>ul>.userbg-item').length !== 0) {
                            $('.user__bg__body>.user-bg-list-layer>ul>.userbg-item>button').on('click', (e) => {
                                let backgroundImageUrl = $(e.currentTarget).find('img').attr('src');
                                $('.user__bg__cont>.aw__form__component>button').attr('data-content', "<img src='"+backgroundImageUrl+"' weight='96' height='96' />");
                                $(".user__bg__cont>.aw__form__component>input[name='_background']").val(backgroundImageUrl);
                                $('#collapseUserBackgrounds').collapse('hide');
                            });
                        }
                    }



                    $.toast(noteCfg);

                },
            });
            
        });

    });
} catch (e) {}