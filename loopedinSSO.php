<?php
/*
 * Plugin Name: LoopedIn SSO
 * Description: SSO Plugin for LoopedIn
 * Version: 1.0.03
 * Author: Bitsclan
 * Author URI: https://bitsclan.com
*/

define( "LoopedinSSO_PLUGIN_BASENAME", plugin_basename( __FILE__ ) );
include_once("includes/LoopedInSSO_Settings.php");
include_once("includes/LoopedInSSO_Login.php");

if (\is_readable(__DIR__ . '/vendor/autoload.php')) {
   require __DIR__ . '/vendor/autoload.php';
}

if( is_admin() )
    new LoopedInSSO_Settings();

new LoopedInSSO_Login();