<?php
/*
* @Author 		Pluginbazar
* Copyright: 	2015 Pluginbazar
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

update_option( 'woa_checking_status', 'checking_off' );

?>

<div class="woa-panel-checker">

    <div class="pc-section pc-section-checker">
        <div class="pc-section-title">Check New Order</div>
        <div class="pc-checker-loading"><i class="fa fa-cog"></i></div>
        <div class="pc-checker-buttons">
            <div class="button pc-start"><span>Start Checking</span> &nbsp <i class="fa fa-play"></i></div>
            <div class="button pc-stop" disabled><span>Stop Checking</span> &nbsp <i class="fa fa-stop"></i></div>
            <div class="button pc-mute">Sound On <i class="fa fa-volume-up"></i></div>
        </div>
    </div>

    <div class="pc-section pc-section-orderlist">
        <div class="pc-section-title">New Order List</div>
        <div class="pc-orders-list"></div>
    </div>

</div>