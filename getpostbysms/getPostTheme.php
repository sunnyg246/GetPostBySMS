<?php
/*
Template Name: Get Post By SMS
*/
//Display the page content/body
?>
<html>
<head>
<meta name="txtweb-appkey" content="<?php echo get_option('getPostBySMS_key'); ?>"/>
<title>Get Post By SMS</title>
</head>
<body>
<?php

function get_post_by_sms($i)
	{
	global $wpdb;
	$querystr = "
    SELECT $wpdb->posts.post_content 
    FROM $wpdb->posts
    WHERE $wpdb->posts.ID = $i 
    AND $wpdb->posts.post_status = 'publish' 
    AND $wpdb->posts.post_type = 'post'";

      $pageposts = $wpdb->get_var($querystr,0,0);
	  if($pageposts != "")
		return $pageposts;
	  else
	   return "No post found.";
	} 

if($_REQUEST['txtweb-message'])
{
echo  get_post_by_sms($_REQUEST['txtweb-message']);
}
else
{
?>
Get POSTID from SMSTextMsgs.com <br /><br /> To Get a POST : <form action='' class='txtweb-form' method='get'>
		SMSID<input type='text' name='txtweb-message' />
		<input type='submit' name='submit' />
		</form>
<?php } ?>
</body>
</html>