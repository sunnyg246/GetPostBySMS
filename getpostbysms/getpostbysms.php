<?php
/*
Plugin Name: Get Post By SMS
Plugin URI: http://business.txtweb.com/user/apps/getpostbysms
Description: Allow users to get your post as SMS
Version: 1.0
Author: Sunny Gulati
Author URI: http://business.txtweb.com/user/profile?userid=20309
License: GPL2
*/

/*  Copyright 2012  Sunny Gulati  (email : sunnyg246@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'getPostBySMS_plugin_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'getPostBySMS_plugin_remove' );


function getPostBySMS_plugin_install() {

    $the_page_title = 'Get Post By SMS';
    $the_page_name = 'get-post-by-sms';

    // the menu entry...
    delete_option("getPostBySMS_page_title");
    add_option("getPostBySMS_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("getPostBySMS_page_name");
    add_option("getPostBySMS_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("getPostBySMS_page_id");
    add_option("getPostBySMS_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );
		update_post_meta( $the_page_id, '_wp_page_template','getPostTheme.php',TRUE );

		   

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );
		update_post_meta( $the_page_id, '_wp_page_template','getPostTheme.php',TRUE );
    }

    delete_option( 'getPostBySMS_page_id' );
    add_option( 'getPostBySMS_page_id', $the_page_id );
	add_option("getPostBySMS_key", ' ', 'Please Enter Service Meta Key', 'yes');
	add_option("getPostBySMS_name", ' ', 'Please Enter Service Name', 'yes');
add_option("getPostBySMS_color", '#7F9A42', 'Background color for front end', 'yes');
add_option("getPostBySMS_textColor", '#FFFFFF', 'Text color for front end', 'yes');
}

function getPostBySMS_plugin_remove() {

    global $wpdb;

    $the_page_title = get_option( "getPostBySMS_page_title" );
    $the_page_name = get_option( "getPostBySMS_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'getPostBySMS_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("getPostBySMS_page_title");
    delete_option("getPostBySMS_page_name");
    delete_option("getPostBySMS_page_id");
delete_option('getPostBySMS_key');
delete_option('getPostBySMS_name');
delete_option('getPostBySMS_color');
delete_option('getPostBySMS_textColor');

}
	
	
	function template_redirect_to_winning_page()
	{
		global $wp;
		if( $wp->query_vars["pagename"] == "get-post-by-sms" ) {
			include(WP_PLUGIN_DIR . '/getpostbysms/getPostTheme.php');
			die();
		}
	}

add_action ('template_redirect', 'template_redirect_to_winning_page');
	

	add_filter('the_content', 'GetPostBySMSContent');

	function GetPostBySMSContent($content){
	global $post;
	$msg = "<div id='GetPostBySMS' style='margin-top:10px;background-color:". get_option('getPostBySMS_color').";color:". get_option('getPostBySMS_textColor').";padding:10px;'><strong>Get This POST</strong> : SEND ". get_option('getPostBySMS_name')." ".$post->ID." to 92433 42000 </div>";
		return $content . $msg;
	}
	
	
	add_action('admin_menu', 'getPostBySMS_admin_menu');
	
function getPostBySMS_admin_menu() {
add_options_page('GetPostBySMS Admin Options', 'Get Post By SMS', 'manage_options',
'get-post-by-sms', 'GetPostBySMS_admin_options_page');
}

function getPostBySMS_update()
{
	$ok=false;
	if($_REQUEST['getPostBySMS_key'])
	{
	update_option('getPostBySMS_key',trim($_REQUEST['getPostBySMS_key']));
	$ok=true;
	}

	if($_REQUEST['getPostBySMS_name'])
	{
	update_option('getPostBySMS_name',$_REQUEST['getPostBySMS_name']);
	$ok=true;
	}

	if($_REQUEST['getPostBySMS_color'])
	{
	update_option('getPostBySMS_color',trim($_REQUEST['getPostBySMS_color']));
	$ok=true;
	}	
	
	if($_REQUEST['getPostBySMS_textColor'])
	{
	update_option('getPostBySMS_textColor',trim($_REQUEST['getPostBySMS_textColor']));
	$ok=true;
	}

	if($ok) {
	?>
	<div id="message" class="update fade"><p>
	Options saved.
	</p></div>
	<?php
	}
	else
	{ ?>
	<div id="message" class="error fade"><p>
	Failed to save options.
	</p></div>

	<?php
	}
}

function GetPostBySMS_admin_options_page()
{
if($_REQUEST['submit']) {
getPostBySMS_update();
}

GetPostBySMS_admin_print_form();
}

function GetPostBySMS_admin_print_form() {
?>
<div class="wrap">
<?php screen_icon(); ?>
<h2>Get Post By SMS</h2>
<p>
<form method="post" action="">
Service App Key &nbsp; : <input name="getPostBySMS_key" type="text" id="getPostBySMS_key"
value="<?php echo get_option('getPostBySMS_key'); ?>" /><br />
Service Name &nbsp; &nbsp;&nbsp; : <input name="getPostBySMS_name" type="text" id="getPostBySMS_name"
value="<?php echo get_option('getPostBySMS_name'); ?>" /><br />
BG Color &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : <input name="getPostBySMS_color" type="text" id="getPostBySMS_color"
value="<?php echo get_option('getPostBySMS_color'); ?>" /><br />
Text Color &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : <input name="getPostBySMS_textColor" type="text" id="getPostBySMS_textColor"
value="<?php echo get_option('getPostBySMS_textColor'); ?>" /><br /><br />
<input type="submit" name="submit" value="Save Changes" />
</form><br /><br /><br />
To get the Service Key & Name Register Here :- <a href="http://developer.txtweb.com/">http://developer.txtweb.com/</a>
<br /> Its Free :)
</p>
</div>
<?php
}
?>