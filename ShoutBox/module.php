<?php
/*
=   Shoutbox | NamelessMC
=   Author: Hyfalls | IsS127.com

=   module.php
=   Description: Module class
=   Created: 2019-04-14 - 00:39 - CEST
*/

class ShoutBox_Module extends Module {
	private $_language;

	public function __construct($pages, $language){
		$name = 'Shoutbox';
		$author = '<a href="https://namelessmc.com/profile/Hyfalls/" target="_blank" rel="nofollow noopener">Hyfalls</a>';
		$module_version = '2.0.0-pr6';
		$nameless_version = '2.0.0-pr6';

		$this->_language = $language;

		parent::__construct($this, $name, $author, $module_version, $nameless_version);

		// Define URLs which belong to this module
		$pages->add('ShoutBox', '/panel/shoutbox/settings', 'pages/panel/shoutbox.php');
        $pages->add('ShoutBox', '/shoutbox', 'pages/shoutbox.php');
	}

	public function onInstall()
    {
        // Queries
        $queries = new Queries();

        try {
            $data = $queries->createTable("shoutbox_messages", " `id` int(11) NOT NULL AUTO_INCREMENT, `user` int(11) NOT NULL, `message` varchar(2048) NOT NULL, `deleted` tinyint(1) NOT NULL DEFAULT '0', `message_date` int(11) NULL, `timestamp` datetime NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
            // Update main admin group permissions
            $group = $queries->getWhere('groups', array('id', '=', 2));
            $group = $group[0];

            $group_permissions = json_decode($group->permissions, TRUE);
            $group_permissions['shoutbox.delete_message'] = 1;

            $group_permissions = json_encode($group_permissions);
            $queries->update('groups', 2, array('permissions' => $group_permissions));
        } catch (Exception $e) {
            // Error
        }

    }

	public function onUninstall(){
	}

	public function onEnable(){
	}

	public function onDisable(){
	}

	public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template){
		if(defined('BACK_END')){
            PermissionHandler::registerPermissions($this->_language->get('shoutbox', 'shoutbox'), array(
                'shoutbox.delete_message' => $this->_language->get('shoutbox', 'delete_message')
            ));
		}
	}
}