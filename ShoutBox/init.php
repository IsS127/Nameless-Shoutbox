<?php
/*
=   Shoutbox | NamelessMC
=   Author: Hyfalls | IsS127.com

=   init.php
=   Description: Init file
=   Created: 2019-04-14 - 00:39 - CEST
*/

$module_installed = $cache->retrieve('module_shoutbox');
if(!$module_installed){
    // Hasn't been installed
    // Need to run the installer

    $exists = $queries->tableExists('shoutbox_messages');
    if(empty($exists)) {
        die('Run the installer first!');
    } else {
        $cache->store('module_shoutbox', true);
    }
}

// Custom language
$shoutbox_language = new Language(ROOT_PATH . '/modules/ShoutBox/language', LANGUAGE);

require_once(ROOT_PATH . '/modules/ShoutBox/module.php');
$module = new ShoutBox_Module($pages, $shoutbox_language);