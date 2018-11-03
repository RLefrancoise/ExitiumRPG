<?php

include_once(__DIR__ . '/../../common.php');
include_once(__DIR__ . '/../../includes/functions_user.' . $phpEx);
include_once(__DIR__ . '/../../includes/functions_posting.' . $phpEx);
include_once(__DIR__ . '/../../includes/functions_privmsgs.' . $phpEx);
include_once(__DIR__ . "/../classes/rpgconfig.php");
include_once(__DIR__ . "/../database/RPGUsersPlayers.class.php");

function rpg_post($subject, $text, $mode, $forum_id, $topic_id = 0, $post_id = 0) {

	global $db, $user, $auth;
	
	//backup current user
	$backup = array(
		'user'	=> $user,
		'auth'	=> $auth,
	);
	
	//create user for rpg post
	$sql = 'SELECT *
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . RPG_POST_USER_ID;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$user->data = array_merge($user->data, $row);
	$auth->acl($user->data);

	$user->ip = '0.0.0.0 ';
	
	//post message
	$poll = $uid = $bitfield = $options = ''; 

	$subject = utf8_normalize_nfc($subject);
	$text = utf8_normalize_nfc($text);
	
	generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
	generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);

	$data = array( 
		'forum_id'      	=> $forum_id,
		'topic_id'			=> $topic_id,
		'post_id'			=> $post_id,
		'icon_id'       	=> false,

		'poster_id'			=> RPG_POST_USER_ID,
		
		'enable_bbcode'     => true,
		'enable_smilies'    => true,
		'enable_urls'       => true,
		'enable_sig'        => true,

		'message'      		=> $text,
		'message_md5'   	=> md5($text),
					
		'bbcode_bitfield'   => $bitfield,
		'bbcode_uid'        => $uid,

		'post_edit_locked'  => 1,
		'topic_title'       => $subject,
		'notify_set'        => false,
		'notify'            => false,
		'post_time'         => 0,
		'forum_name'        => '',
		'enable_indexing'   => true,
	);

	submit_post($mode, $subject, '', POST_NORMAL, $poll, $data);
	
	//restore user
	$user = $backup['user'];
	$auth = $backup['auth'];
	
	return $data;
}

function rpg_pm($subject, $text, $addresses) {
	$poll = $uid = $bitfield = $options = ''; 
	
	$subject = utf8_normalize_nfc($subject);
	$text = utf8_normalize_nfc($text);
	
	generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
	generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);
	
	$poster = RPGUsersPlayers::getUserData(RPG_POST_USER_ID);
	
	$pm_data = array(
		'from_user_id'            	=> $poster['user_id'],
		'icon_id'               	=> 0,
		'from_user_ip'             	=> $poster['user_ip'],
		'from_username'            	=> $poster['username'],
		'enable_sig'             	=> false,
		'enable_bbcode'           	=> true,
		'enable_smilies'          	=> true,
		'enable_urls'             	=> true,
		'bbcode_bitfield'         	=> $bitfield,
		'bbcode_uid'             	=> $uid,
		'message'                	=> $text,
		'message_attachment'    	=> 0,
		'address_list'        		=> $addresses,
	);
	
	if(submit_pm('post', $subject, $pm_data, false)) return true;
	return false;
}

?>