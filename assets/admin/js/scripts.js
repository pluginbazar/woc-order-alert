/**
 * Front Script
 */

(function ($, window, document, pluginObject) {
    "use strict";

    let audioElement = document.createElement('audio');

    $(document).on('ready', function () {

        let listenerWrap = $('.olistener'),
            audioSrc = listenerWrap.data('audio'),
            olistenerController = $('.olistener-action.olistener-controller'),
            ordersList = $('.olistener-orders');

        if (typeof audioSrc !== 'undefined') {
            audioElement.setAttribute('src', audioSrc);

            setInterval(function () {
                if (olistenerController.hasClass('active')) {
                    $.ajax({
                        type: 'POST',
                        context: this,
                        url: pluginObject.ajaxUrl,
                        data: {
                            'action': 'olistener',
                        },
                        success: function (response) {
                            if (response.success && response.data.count > 0) {
                                ordersList.prepend(response.data.html);
                                audioElement.load();
                                audioElement.play();
                            }
                            console.log(response.data);
                        },
                    });
                }

                if (ordersList.find('tr').length === 0) {
                    audioElement.pause();
                }
            }, pluginObject.interval);
        }

        audioElement.addEventListener('ended', function () {
            audioElement.currentTime = 0;
            audioElement.play();
        });

        // automatically start order listening
        if (listenerWrap.length > 0) {
            setTimeout(function () {
                $('.dashicons-controls-play').trigger('click');
            }, 300);
        }
    });


    $(document).on('click', '.order-action.mark-read', function () {
        $(this).parent().parent().fadeOut().remove();
    });

    $(document).on('click', '.olistener-volume', function () {
        audioElement.muted = $(this).hasClass('active');
    });

    $(document).on('click', '.olistener-action', function () {

        let controller = $(this),
            oListenerChecker = $('.olistener'),
            controllerClasses = controller.data('classes'),
            controllerIcon = controller.find('span.dashicons'),
            needToggle = true;

        if (typeof controllerClasses === 'undefined' || controllerClasses.length === 0) {
            needToggle = false;
        }

        if (needToggle) {
            controller.toggleClass('active');
            controllerIcon.toggleClass(controllerClasses);

            if (controllerIcon.hasClass('dashicons-controls-pause')) {
                oListenerChecker.addClass('olistener-active');
            } else if (controllerIcon.hasClass('dashicons-controls-play')) {
                oListenerChecker.removeClass('olistener-active');
                audioElement.pause();
            }
            return;
        }

        if (!needToggle && confirm(pluginObject.confirmText)) {
            location.href = '';
        }
    });

})(jQuery, window, document, olistener);







