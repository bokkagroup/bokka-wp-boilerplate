var $ = window.jQuery;

module.exports = {
    send: function(data) {
        $.post(
            BokkaWP.ajaxurl,
            {
                action: 'send_hatchbuck',
                data: data
            }
        )
    }
}
