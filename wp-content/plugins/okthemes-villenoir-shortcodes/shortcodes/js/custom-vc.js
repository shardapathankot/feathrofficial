(function ($) {
	//FeaturedIconView	
    window.villenoirVcFeaturedIconView = vc.shortcode_view.extend( {
        changeShortcodeParams:function (model) {
            window.villenoirVcFeaturedIconView.__super__.changeShortcodeParams.call(this, model);
            var params = model.get('params');
            if (_.isObject(params)) {
                this.$el.find('.wpb_element_wrapper .vc_element-icon, .wpb_element_wrapper i').remove();
                this.$el.find('.wpb_element_wrapper').prepend( '<i class="' + params.featured_icon +' ' + params.align + '"></i>' );
                if( params.align == 'pull-center' ) {
                    this.$el.find('.wpb_element_wrapper').css( {'text-align': 'center'});
                }
            }
        }
    });

    //FeaturedImageView
    window.villenoirVcFeaturedImageView = vc.shortcode_view.extend( {
        changeShortcodeParams:function (model) {
            window.villenoirVcFeaturedImageView.__super__.changeShortcodeParams.call(this, model);
            var params = model.get('params');
            if (_.isObject(params)) {
                if( !_.isEmpty( params.image ) ) {
                    var element = this.$el;
                    $.ajax({
                        type: 'POST',
                        url: window.ajaxurl,
                        data:{
                            action: 'wpb_single_image_src',
                            content: params.image,
                            size: 'thumbnail',
                            _vcnonce: window.vcAdminNonce
                        },
                        dataType:'html'
                    }).done(function (url) {
                        element.find('.wpb_element_wrapper').css( {
                            'background-image': 'url(' + url + ')',
                            'background-size': 'auto 42px',
                            'background-position': 'center 10px',
                            'padding-top': '72px',
                            'text-align': 'center'
                        });
                    });
                }
            }
        }
    });
	
	//TitleSubtitleView
	window.villenoirVcTitleSubtitleView = vc.shortcode_view.extend( {
        changeShortcodeParams:function (model) {
            window.villenoirVcTitleSubtitleView.__super__.changeShortcodeParams.call(this, model);
            var params = model.get('params');
            if (_.isObject(params)) {
                this.$el.find('.wpb_element_wrapper').empty();

                var box = $('<div style="text-align: '+ params.align +'" />');

                if( !_.isEmpty( params.subtitle ) ) {
                    var subtitle = $('<p style="margin-bottom:0px;">' + params.subtitle + '</p>');
                }

                if( !_.isEmpty( params.title ) ) {
                    var title = $('<' + params.title_type + ' style="margin-bottom:1em; padding-top:0">' + params.title + '</' + params.title_type + '>');
                }
                

                this.$el.find('.wpb_element_wrapper').wrap(box).prepend(subtitle, title);

            }
        }
    });
})(window.jQuery);