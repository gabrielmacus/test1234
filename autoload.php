<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/12/2017
 * Time: 21:49
 */


define("ROOT_DIR",dirname(__FILE__)."/");

define("FRAMEWORK_DIR",ROOT_DIR."framework/");

define("CURRENT_URL",(isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");


