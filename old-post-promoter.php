<?php

/*

Plugin Name: Old Post Promoter - OPP

Plugin URI: http://www.blogtrafficexchange.com/old-post-promoter/

Author: Blog Traffic Exchange

Author URI: http://www.blogtrafficexchange.com

Version: 3.0.1

Description: Wordpress plugin that helps you to promote older posts by moving them back onto the front page and into the rss feed. WARNING: This plugin should only be used with data agnostic permalinks (permalink structures not containing dates).

License: GPLv2

License URI: https://www.gnu.org/licenses/gpl-2.0.html



Old Post Promoter is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.

 

Old Post Promoter is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See th GNU General Public License for more details.

 

You should have received a copy of the GNU General Public License along with Old Post Promoter. If not, see {License URI}.

*/

define("KCPLUGINPATH", realpath(dirname(__FILE__) ));

define("KCPLUGINURL", plugin_dir_url(__FILE__));

define("KCICONURL",KCPLUGINURL."images/icon.png");

define("KCCSS",KCPLUGINURL."css/old_post_promoter.css");



define ('KCMINUTE1', 60); 

define ('KCMINUTE15', 15*KCMINUTE1); 

define ('KCMINUTE30', 30*KCMINUTE1); 

define ('KCHOUR1', 60*KCMINUTE1); 

define ('KCHOUR4', 4*KCHOUR1); 

define ('KCHOUR6', 6*KCHOUR1); 

define ('KCHOUR12', 12*KCHOUR1); 

define ('KCHOUR24', 24*KCHOUR1); 

define ('KCHOUR48', 48*KCHOUR1); 

define ('KCHOUR72', 72*KCHOUR1); 

define ('KCHOUR168', 168*KCHOUR1); 

define ('KCINTERVAL', KCHOUR12); 

define ('KCINTERVALSLOP', KCHOUR4); 

define ('KCAGELIMIT', 120); // 120 days

define ('KCOMITCATS', ""); 



require_once(KCPLUGINPATH."/inc/kc-opp-register-hook.php");

require_once(KCPLUGINPATH."/inc/kc-opp-core.php");

require_once(KCPLUGINPATH."/admin/kc-admin.php");



register_activation_hook(__FILE__, 'kc_opp_activate');

register_deactivation_hook(__FILE__, 'kc_opp_deactivate');



add_action('init', 'kc_opp_old_post_promoter');

add_action('admin_menu', 'kc_opp_menu_setup');

add_action('admin_head', 'kc_opp_admin_head');

add_filter('the_content', 'kc_opp_the_content');

add_filter('plugin_action_links', 'kc_opp_action_links', 10, 2);



function kc_opp_action_links($links, $file) {

	$plugin_file = basename(__FILE__);

	if (basename($file) == $plugin_file) {

		$settings_link = '<a href="admin.php?page=old-post-promoter">'.__('Configure', 'RelatedTweets').'</a>';

		array_unshift($links, $settings_link);

	}

	return $links;

} ?>
