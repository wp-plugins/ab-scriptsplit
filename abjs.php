<?php
/*
Plugin Name: Javascript A-B Split Tester
Plugin URI: http://www.bbqiguana.com/wordpress-plugins/abjs/
Description: This plugin provides a mechanism by which you can do A/B split testing of Javascript functionality
Version: 1.0
Author: Randy Hunt
Author URI: http://www.bbqiguana.com/
*/

/*  Copyright 2011  Randy Hunt  (email : bbqiguana@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* When plugin is activated */
register_activation_hook  (__FILE__, 'abjs_activate'  );
register_deactivation_hook(__FILE__, 'abjs_deactivate');

function abjs_activate () {
	$options = array('ascript'=>'', 'bscript'=>'', 'acount'=>0, 'bcount'=>0);
	add_option('abjs', $options);
}

function abjs_deactivate () {
	delete_option('abjs');
}

if (is_admin()) {
	add_action('admin_menu',      'abjs_admin_menu');
	add_action('admin_init',      'abjs_init');
	add_filter('plugin_row_meta', 'abjs_links', 10, 2);

	function abjs_admin_menu(){
		add_options_page('Script split', 'ABScript', 'administrator', __FILE__, 'abjs_admin_page');
	}

	function abjs_init() {
		register_setting('abjs','abjs');
		add_settings_section('script', 'Script settings', 'abjs_foo', __FILE__);
		add_settings_field('ascript', 'Script A', 'abjs_render_ascript', __FILE__, 'script');
		add_settings_field('bscript', 'Script B', 'abjs_render_bscript', __FILE__, 'script');
		//add_settings_field('siteid', 'Site ID', 'abjs_render_sites', __FILE__, 'script');
		//add_settings_field('posttype', 'What to post', 'crossposterous_render_posttype', __FILE__, 'posterous');
	}

	function abjs_links ($links, $file){
		/*
		if( $file == 'posterize/posterize.php') {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=posterize-settings' ) . '">' . __('Settings') . '</a>';
			$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QC745TKR6AHBS" target="_blank">Donate</a>';
		}
		*/
		return $links;
	}
	
	function abjs_foo () {
		//
	}

	function abjs_render_ascript () {
		$options = get_option('abjs');
		echo '<em>Displayed ' . $options['acount'] . ' times.<br>';
		echo '<textarea id="ascript" name="abjs[ascript]" rows="10" cols="60" type="text" class="regular-text">' . $options['ascript'] . '</textarea>';
	}

	function abjs_render_bscript () {
		$options = get_option('abjs');
		echo '<em>Displayed ' . $options['bcount'] . ' times.<br>';
		echo '<textarea id="bscript" name="abjs[bscript]" rows="10" cols="60" type="text" class="regular-text">' . $options['bscript'] . '</textarea>';
	}

	function abjs_admin_page() {
		echo '<div class="wrap">';
		echo '<div class="icon32" id="icon-options-general"><br></div>';
		echo '<h2>ABScript</h2>';
		echo '<form action="options.php" method="post">';
		settings_fields('abjs');
		do_settings_sections(__FILE__);
		echo '<p class="submit">';
		echo '<input name="Submit" type="submit" class="button-primary" value="' . __('Save Changes') . '" />';
		echo '</p>';
		echo '</form>';
		echo '</div>';
	}  

}

function abjs_output () {
	$options = get_option('abjs');
	if ($options['acount'] > $options['bcount']) {
		$options['bcount'] = $options['bcount'] + 1;
		echo ($options['bscript']);
	} else {
		$options['acount'] = $options['acount'] + 1;
		echo ($options['ascript']);
	}
	update_option('abjs', $options);
}

add_action('wp_head', 'abjs_output');

?>
