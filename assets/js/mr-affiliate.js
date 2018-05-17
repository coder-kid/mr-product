/*----------------------------------------------*/
/* MR Affiliate
/*----------------------------------------------*/
jQuery(document).ready(function($){


    $(document).on('click', 'a.mr-affiliate-reset-btn', function() {
        if(! confirm('[WARNING This will be reset your full settings, Are you sure?]') ) {
            return false;
        }
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'mr_affiliate_reset'
            },
            success: function(data) {
                window.location.reload(true);
            }
        });
    });

});