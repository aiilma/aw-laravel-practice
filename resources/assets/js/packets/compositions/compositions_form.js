$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });


    // AJAX. 
    $('#buyCompositionBtn').on('click', (e) => {
        // form data object
        let buyCompositionData = {
            _visualization: $('.aw__visual__case>input:checked').val(),
            _background: $("#userBackgroundInput").val(),
            _compHash: $("#compositionHash").val(), // get from url on current page
        };

        // request
        $.ajax({
            url: e.target.attributes['data-link'].value,
            type: "POST",
            data: buyCompositionData,
            dataType: 'json',
            success: function (result) {
                console.log(result);

                // объект кфг уведомления
                var noteCfg = {
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


                if (result.messages.steam.length !== 0 || result.messages.transaction.length !== 0) {
                    noteCfg['heading'] = 'Whoops!';
                    noteCfg['text'] = '';

                    for (let key in result.messages) {
                        for (let index in result.messages[key]) {
                            noteCfg['text'] = result.messages[key][index][0];
                        }
                    }

                    $.toast(noteCfg);
                }
                else {
                    window.location.href = 'https://artworch.com/account/orders/';
                }

            },
        });

    });

});