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
if($_REQUEST['smsid'])
{
	$cur_id = $_REQUEST['smsid'];
	$new_id = $cur_id - 1;
	while($new_id != 0)
	{
		if(get_post_type($new_id) == "post" && get_post_by_sms($new_id) != "No post found.")
		{
			break;
		}
		else 
			$new_id = $new_id - 1;
	}
		echo  get_post_by_sms($cur_id);  ?>
 <br /><br />
<a href="<?php echo get_site_url(); ?>/get-post-by-sms/?smsid=<?php echo $new_id ?>" class="txtweb-menu-for"> Next Post </a>	
<?php	
} else {
	$last = wp_get_recent_posts( '2');
	$last_id = $last['0']['ID'];
	echo  get_post_by_sms($last_id);  ?>
 <br /><br />
<a href="<?php echo get_site_url(); ?>/get-post-by-sms/?smsid=<?php echo $last['1']['ID'] ?>" class="txtweb-menu-for"> Next Post </a>
<?php
}

 } ?>
</body>
</html>