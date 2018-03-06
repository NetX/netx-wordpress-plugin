(function ($, undefined) {
    var lastJqueryAjaxRequest = null;

    var updateNetxMediaSelectorForm = function(catId, pageId) {
        //kill any current requests
        if(lastJqueryAjaxRequest) {
            lastJqueryAjaxRequest.abort();
        }

        //show the loading indicator
        $('.netx-upload-loading-area').html('<div class="netx-spinner"><div class="netx-dot1"></div><div class="netx-dot2"></div></div>');

        //set the new catId link as selected
        $('a.netx-tree-node-link.selected').removeClass('selected');
        $('a.netx-tree-node-link').filter(function(x) { return $(this).data("catId") == catId }).addClass('selected');
        //load and display the data.
        lastJqueryAjaxRequest = $.post(ajaxurl, { action: 'netx_get_image_select_form', catId: catId, pageId: pageId }, function(resp) {
            $('.netx-upload-loading-area').html(resp);
            //hide the all of the element with class msg_body
            $(".slidetoggle").hide();
            //toggle the componenet with class msg_body
            $(".toggle").click(function(){
                $(this).parent().children('a.toggle').toggle();
                $(this).siblings(".slidetoggle").toggle();
            });
            $('a.page-numbers').click(function () {
                updateNetxMediaSelectorForm(catId, $(this).data("pageNum"));
            });
        }); 
        var treeToggle = jQuery('a.netx-tree-node-link')
            .filter(function(x) { return jQuery(this).data("catId") == catId })
            .siblings('a.netx-tree-node-toggle');

        if(treeToggle.length) {
            if(treeToggle.find('span.dashicons.dashicons-plus').length != 0) {
                treeToggle.click();
            }
        }
    };

    var initNetxForm = function () {
        $.post(ajaxurl, {action: 'netx_load'}, function (resp) {
            $('.netx-form-target').html(resp);

            $('.netx-tree-node-toggle').click(function () {
                $(this).parent('.netx-tree-node').toggleClass("collapsed");

                $(this).children('span.dashicons').toggleClass('dashicons-plus');
                $(this).children('span.dashicons').toggleClass('dashicons-minus');
            });

            $('.netx-tree-node-link').click(function () {
                if (!$(this).hasClass("selected")) {
                    updateNetxMediaSelectorForm($(this).data("catId"));
                } else {
                    $(this).siblings('a.netx-tree-node-toggle').click();
                }
            });

            $('.netx-form-target').on('click', '.netx-add-item-submit', function () {
                var refreshMediaLib = function(callback) {

                    // get wp outside iframe
                    var pwp = parent.wp;

                    // switch tabs (required for the code below)

                    pwp.media.frame.setState('insert');

                    // refresh
                    if( pwp.media.frame.content.get() &&
                        pwp.media.frame.content.get().collection &&
                        pwp.media.frame.content.get().collection.props
                    ) {
                        pwp.media.frame.content.get().collection.props.set({ignore: (+new Date())});
                        pwp.media.frame.content.get().options.selection.reset();
                    } else if(pwp.media.frame.library &&
                        pwp.media.frame.library.props
                    ) {
                        pwp.media.frame.library.props.set({ignore: (+new Date())});
                    } else {
                        var editor = pwp.media.editor.get(parent.wpActiveEditor);

                        if(editor && editor.views && editor.views._views) {
                            var views = editor.views._views;

                            if(views['.media-frame-content'] && views['.media-frame-content'].length) {
                                for(var i = 0; i < views['.media-frame-content'].length; i++) {
                                    var view = views['.media-frame-content'][i];

                                    if(view.views && view.views && view.views._views) {
                                        var cView = view.views._views;
                                        if(cView[""] && cView[""].length > 1) {
                                            var target = cView[""][1];

                                            if(target && target.collection && target.collection.props) {
                                                target.collection.props.set({ignore:(+(new Date()))});
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    callback();
                };

                var netxAssetId = $(this).data("assetId");
                var view = $('input[name="asset[' + netxAssetId + '][image-size]"]:checked').val();
                var enforceDownload = $('input[name="asset[' + netxAssetId + '][enable-download]"]:checked').val() == 'enable-download' ? 'true' : 'false';

                //start ajax notification
                $('body').append(
                    "<div class='netx-ajax'><div class='netx-spinner'><div class='netx-dot1'></div><div class='netx-dot2'></div></div></div>"
                );
                $.post(ajaxurl, { action: 'netx_import_from_netx', asset_id: netxAssetId, asset_view: view, enforce_download: enforceDownload }, function(resp) {
                    refreshMediaLib(function() {
                        $('.netx-ajax').remove();

                        // get wp outside iframe
                        var pacf = parent.acf;
                        var pwp = parent.wp;
                        var useAcf = $(".hide-menu:visible").length;

                        var frame = null;

                        if(pacf && pacf.media.frame && useAcf && (typeof pacf.media.frame.state === "function")) {
                            frame = pacf.media.frame;
                        } else {
                            frame = pwp.media.frame;
                        }

                        var selection = frame.state().get('selection');

                        selection.add(pwp.media.attachment(resp));

                        $('.media-toolbar').show();
                        $('.media-button-select').click();

                        //$('#netx-form').submit();

                        var setImageSrc = function(url) {
                            if($('.acf-image-value[value=' + resp + ']').length) {
                                $('.acf-image-value[value=' + resp + ']')
                                    .siblings('.has-image')
                                    .children('.acf-image-image')
                                    .attr('src', url);
                            } else {
                                if(console && console.log && netxScript.debug == "1") {
                                    console.log(
                                        'Could not find ' +
                                        '.acf-image-value[value=' + resp + ']' +
                                        ' to set preview image'
                                    );
                                }
                            }

                            if($('.acf-image-uploader .acf-hidden input[type="hidden"][value="' + resp + '"]').length) {
                                $('.acf-image-uploader .acf-hidden input[type="hidden"][value="' + resp + '"]')
                                    .closest('.acf-image-uploader')
                                    .find('img')
                                    .attr('src', url);
                            } else {
                                if(console && console.log && netxScript.debug == "1") {
                                    console.log(
                                        'Could not find ' +
                                        '.acf-image-uploader .acf-hidden input[type="hidden"][value="' + resp + '"]' +
                                        ' to set preview image'
                                    );
                                }
                            }
                        };

                        var thumbnailUpdate = function () {
                            //if we're using acf let's update the image thumb
                            var attachment = pwp.media.attachment(resp);
                            if(attachment.attributes.url) {
                                setImageSrc(attachment.attributes.url);
                            } else {
                                setTimeout(thumbnailUpdate, 100);
                            }
                        };

                        setImageSrc(netxScript.ajaxLoaderUrl);
                        setTimeout(thumbnailUpdate, 100);
                    });
                });
            });

            updateNetxMediaSelectorForm();
        });
    };

    var setupNetxSelector = function () {
        $('.media-frame-router .media-router').each(function() {
            if (!$(this).children('.netx-media-selector-button').length) {
                $(this)
                    .append(
                        $("<a/>", {"href": "#", "class": "netx-media-selector-button media-menu-item", "text": "NetX"})
                    );

                $('.media-menu-item').click(function () {
                    $(".media-menu-item").removeClass('active');
                    $(this).addClass('active');

                    if ($(this).hasClass('netx-media-selector-button')) {
                        $(".media-frame-content").html(
                            "<div class='netx-form-target'><div class='netx-spinner'><div class='netx-dot1'></div><div class='netx-dot2'></div></div></div>"
                        );

                        initNetxForm();

                        $('.media-toolbar').hide();
                    } else {
                        $('.media-toolbar').show();
                    }
                });
            }
        });

        setTimeout(setupNetxSelector, 100);
    };

    $(function() {
        if($('.netx-form-target').length) {
            initNetxForm();
        }

        setupNetxSelector();
    });
})(jQuery);
