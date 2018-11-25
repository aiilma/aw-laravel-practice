try {
    window.$ = window.jQuery = require('jquery');

    $(document).ready(function() {
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


        // AJAX. 
        $('#confirmOrderBtn').on('click', (e) => {
            // form data object
            let orderData = {
                message: 'acceptAJAX',
                // _visualization: $('.aw__visual__case>input:checked').val(),
                // _background: $("#userBackgroundInput").val(),
                // _compHash: $("#compositionHash").val(), // get from url on current page
            };

            // request
            $.ajax({
                url: e.target.attributes['data-link'].value,
                type: "POST",
                data: orderData,
                dataType: 'json',
                success: function(result) {
                    console.log(result);
                },
            });
            
        });

        // AJAX. 
        $('#denyOrderBtn').on('click', (e) => {
            // form data object
            let orderData = {
                message: 'declineAJAX',
                // _visualization: $('.aw__visual__case>input:checked').val(),
                // _background: $("#userBackgroundInput").val(),
                // _compHash: $("#compositionHash").val(), // get from url on current page
            };

            // request
            $.ajax({
                url: e.target.attributes['data-link'].value,
                type: "POST",
                data: orderData,
                dataType: 'json',
                success: function(result) {
                    console.log(result);
                },
            });
            
        });

    });
} catch (e) {}