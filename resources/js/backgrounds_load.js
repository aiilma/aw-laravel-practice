try {
    window.$ = window.jQuery = require('jquery');

    $(document).ready(function() {
        console.log('OK :D');
    });
} catch (e) {}