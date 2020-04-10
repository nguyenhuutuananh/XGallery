(function (window, jQuery) {
    let xgallery = {};

    xgallery.lazyload = {
        lazyloadInstance: false,

        init: function () {
            lazyLoadInstance = new LazyLoad({
                elements_selector: ".lazy"
                // ... more custom settings?
            });

            lazyLoadInstance.update();
        },

        update: function () {
            lazyLoadInstance.update();
        }
    };

    xgallery.toast = {
        show: function (html, id) {
            jQuery('.toast-container').append(html);
            let toast = jQuery('#' + id);
            toast.toast({delay: 5000});
            toast.toast('show');
        }
    };

    xgallery.ajax = {
        request: function (data) {
            ajaxUrl = data.ajaxUrl;
            delete data.ajaxUrl;

            jQuery.ajax({
                url: ajaxUrl,
                data: data,
                beforeSend: function()
                {
                    jQuery('#overlay').show();
                }
            })
                .done(function (data) {
                    jQuery(this).attr('disabled', true);
                    jQuery('#overlay').hide();
                })
                .fail(function () {
                    jQuery('#overlay').hide();
                })
        },
        init: function () {
            jQuery('body').on('click', '.ajax-pool', function () {
                xgallery.ajax.request(jQuery(this).data());
            })
        }
    }

    window.xgallery = xgallery;
})(window, jQuery);
