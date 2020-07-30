<?php
/*
* @Author 		Pluginbazar
* Copyright: 	2015 Pluginbazar
*/

defined( 'ABSPATH' ) || exit;

?>

<div class="olistener" data-audio="<?php echo olistener_get_audio(); ?>">

    <div class="pb-row">
        <div class="pb-col-lg-4">
            <div class="olistener-section olistener-checker">
                <div class="olistener-loading"><span class="dashicons dashicons-search"></span></div>
                <div class="olistener-actions pb-row">
                    <div class="pb-col-lg-4">
                        <div class="olistener-action olistener-controller tt--top"
                             data-classes="dashicons-controls-play dashicons-controls-pause"
                             aria-label="<?php esc_html_e( 'Start or stop listener', 'woc-order-alert' ); ?>">
                            <span class="dashicons dashicons-controls-play"></span>
                        </div>
                    </div>
                    <div class="pb-col-lg-4">
                        <div class="olistener-action olistener-volume active tt--top"
                             data-classes="dashicons-controls-volumeon dashicons-controls-volumeoff"
                             aria-label="<?php esc_html_e( 'Volume on or mute', 'woc-order-alert' ); ?>">
                            <span class="dashicons dashicons-controls-volumeon"></span>
                        </div>
                    </div>
                    <div class="pb-col-lg-4">
                        <div class="olistener-action olistener-reset tt--top"
                             aria-label="<?php esc_html_e( 'Reset listener', 'woc-order-alert' ); ?>">
                            <span class="dashicons dashicons-image-rotate"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-col-lg-8">
            <table class="olistener-section olistener-orders"></table>
        </div>
    </div>

</div>
