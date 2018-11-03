<?php if (!defined('IN_PHPBB')) exit; $this->_tpl_include('overall_header.html'); ?>

<!--**
*
* @package mChat
* @version $Id: acp_mchat.html,v 1.3.0 27/08/2010 RMcGirr83 Exp $
* @copyright (c) 2010 Rich McGirr
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/-->
<h1><?php echo ((isset($this->_rootref['L_MCHAT_TITLE'])) ? $this->_rootref['L_MCHAT_TITLE'] : ((isset($user->lang['MCHAT_TITLE'])) ? $user->lang['MCHAT_TITLE'] : '{ MCHAT_TITLE }')); ?></h1>
<p><?php echo ((isset($this->_rootref['L_MCHAT_VERSION'])) ? $this->_rootref['L_MCHAT_VERSION'] : ((isset($user->lang['MCHAT_VERSION'])) ? $user->lang['MCHAT_VERSION'] : '{ MCHAT_VERSION }')); ?> <?php echo (isset($this->_rootref['MCHAT_VERSION'])) ? $this->_rootref['MCHAT_VERSION'] : ''; ?></p>

<?php if ($this->_rootref['MCHAT_ERROR']) {  ?>

	<div class="errorbox"><h3><?php echo ((isset($this->_rootref['L_WARNING'])) ? $this->_rootref['L_WARNING'] : ((isset($user->lang['WARNING'])) ? $user->lang['WARNING'] : '{ WARNING }')); ?></h3>
		<p><?php echo (isset($this->_rootref['MCHAT_ERROR'])) ? $this->_rootref['MCHAT_ERROR'] : ''; ?></p>
	</div>
<?php } ?>


<form id="acp_mchat" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">
	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_MCHAT_MAIN'])) ? $this->_rootref['L_MCHAT_MAIN'] : ((isset($user->lang['MCHAT_MAIN'])) ? $user->lang['MCHAT_MAIN'] : '{ MCHAT_MAIN }')); ?></legend>
		<dl>
			<dt><label for="mchat_enable"><?php echo ((isset($this->_rootref['L_MCHAT_ENABLE'])) ? $this->_rootref['L_MCHAT_ENABLE'] : ((isset($user->lang['MCHAT_ENABLE'])) ? $user->lang['MCHAT_ENABLE'] : '{ MCHAT_ENABLE }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_ENABLE_EXPLAIN'])) ? $this->_rootref['L_MCHAT_ENABLE_EXPLAIN'] : ((isset($user->lang['MCHAT_ENABLE_EXPLAIN'])) ? $user->lang['MCHAT_ENABLE_EXPLAIN'] : '{ MCHAT_ENABLE_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="mchat_enable" value="1"<?php if ($this->_rootref['MCHAT_ENABLE']) {  ?> id="mchat_enable" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
			<label><input type="radio" class="radio" name="mchat_enable" value="0"<?php if (! $this->_rootref['MCHAT_ENABLE']) {  ?> id="mchat_enable" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>
		<dl>
			<dt><label for="mchat_new_posts"><?php echo ((isset($this->_rootref['L_MCHAT_NEW_POSTS'])) ? $this->_rootref['L_MCHAT_NEW_POSTS'] : ((isset($user->lang['MCHAT_NEW_POSTS'])) ? $user->lang['MCHAT_NEW_POSTS'] : '{ MCHAT_NEW_POSTS }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_NEW_POSTS_EXPLAIN'])) ? $this->_rootref['L_MCHAT_NEW_POSTS_EXPLAIN'] : ((isset($user->lang['MCHAT_NEW_POSTS_EXPLAIN'])) ? $user->lang['MCHAT_NEW_POSTS_EXPLAIN'] : '{ MCHAT_NEW_POSTS_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="mchat_new_posts" value="1"<?php if ($this->_rootref['MCHAT_NEW_POSTS']) {  ?> id="mchat_new_posts" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
			<label><input type="radio" class="radio" name="mchat_new_posts" value="0"<?php if (! $this->_rootref['MCHAT_NEW_POSTS']) {  ?> id="mchat_new_posts" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>		
		<dl>
			<dt><label for="mchat_static_message"><?php echo ((isset($this->_rootref['L_MCHAT_STATIC_MESSAGE'])) ? $this->_rootref['L_MCHAT_STATIC_MESSAGE'] : ((isset($user->lang['MCHAT_STATIC_MESSAGE'])) ? $user->lang['MCHAT_STATIC_MESSAGE'] : '{ MCHAT_STATIC_MESSAGE }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_STATIC_MESSAGE_EXPLAIN'])) ? $this->_rootref['L_MCHAT_STATIC_MESSAGE_EXPLAIN'] : ((isset($user->lang['MCHAT_STATIC_MESSAGE_EXPLAIN'])) ? $user->lang['MCHAT_STATIC_MESSAGE_EXPLAIN'] : '{ MCHAT_STATIC_MESSAGE_EXPLAIN }')); ?></span></dt>
			<dd><textarea name="mchat_static_message" id="mchat_static_message" rows="3" cols="40"><?php echo (isset($this->_rootref['MCHAT_STATIC_MESSAGE'])) ? $this->_rootref['MCHAT_STATIC_MESSAGE'] : ''; ?></textarea></dd>
		</dl>
		<dl>
			<dt><label for="mchat_refresh"><?php echo ((isset($this->_rootref['L_MCHAT_REFRESH'])) ? $this->_rootref['L_MCHAT_REFRESH'] : ((isset($user->lang['MCHAT_REFRESH'])) ? $user->lang['MCHAT_REFRESH'] : '{ MCHAT_REFRESH }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_REFRESH_EXPLAIN'])) ? $this->_rootref['L_MCHAT_REFRESH_EXPLAIN'] : ((isset($user->lang['MCHAT_REFRESH_EXPLAIN'])) ? $user->lang['MCHAT_REFRESH_EXPLAIN'] : '{ MCHAT_REFRESH_EXPLAIN }')); ?></span></dt>
			<dd><input type="text" name="mchat_refresh" size="10" value="<?php echo (isset($this->_rootref['MCHAT_REFRESH'])) ? $this->_rootref['MCHAT_REFRESH'] : ''; ?>" /></dd>
		</dl>				
		<dl>
			<dt><label for="mchat_timeout"><?php echo ((isset($this->_rootref['L_MCHAT_USER_TIMEOUT'])) ? $this->_rootref['L_MCHAT_USER_TIMEOUT'] : ((isset($user->lang['MCHAT_USER_TIMEOUT'])) ? $user->lang['MCHAT_USER_TIMEOUT'] : '{ MCHAT_USER_TIMEOUT }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_TIMEOUT_EXPLAIN'])) ? $this->_rootref['L_MCHAT_TIMEOUT_EXPLAIN'] : ((isset($user->lang['MCHAT_TIMEOUT_EXPLAIN'])) ? $user->lang['MCHAT_TIMEOUT_EXPLAIN'] : '{ MCHAT_TIMEOUT_EXPLAIN }')); ?></span></dt>
			<dd><input type="text" name="mchat_timeout" size="10" maxlength="4" value="<?php echo (isset($this->_rootref['MCHAT_TIMEOUT'])) ? $this->_rootref['MCHAT_TIMEOUT'] : ''; ?>" /></dd>
		</dl>					
		<dl>
			<dt><label for="mchat_on_index"><?php echo ((isset($this->_rootref['L_MCHAT_ON_INDEX'])) ? $this->_rootref['L_MCHAT_ON_INDEX'] : ((isset($user->lang['MCHAT_ON_INDEX'])) ? $user->lang['MCHAT_ON_INDEX'] : '{ MCHAT_ON_INDEX }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_ON_INDEX_EXPLAIN'])) ? $this->_rootref['L_MCHAT_ON_INDEX_EXPLAIN'] : ((isset($user->lang['MCHAT_ON_INDEX_EXPLAIN'])) ? $user->lang['MCHAT_ON_INDEX_EXPLAIN'] : '{ MCHAT_ON_INDEX_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="mchat_on_index" value="1"<?php if ($this->_rootref['MCHAT_ON_INDEX']) {  ?> id="mchat_on_index" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
			<label><input type="radio" class="radio" name="mchat_on_index" value="0"<?php if (! $this->_rootref['MCHAT_ON_INDEX']) {  ?> id="mchat_on_index" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>		
		<dl>
			<dt><label for="mchat_index_height"><?php echo ((isset($this->_rootref['L_MCHAT_INDEX_HEIGHT'])) ? $this->_rootref['L_MCHAT_INDEX_HEIGHT'] : ((isset($user->lang['MCHAT_INDEX_HEIGHT'])) ? $user->lang['MCHAT_INDEX_HEIGHT'] : '{ MCHAT_INDEX_HEIGHT }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_INDEX_HEIGHT_EXPLAIN'])) ? $this->_rootref['L_MCHAT_INDEX_HEIGHT_EXPLAIN'] : ((isset($user->lang['MCHAT_INDEX_HEIGHT_EXPLAIN'])) ? $user->lang['MCHAT_INDEX_HEIGHT_EXPLAIN'] : '{ MCHAT_INDEX_HEIGHT_EXPLAIN }')); ?></span></dt>
			<dd><input type="text" name="mchat_index_height" size="10" maxlength="4" value="<?php echo (isset($this->_rootref['MCHAT_INDEX_HEIGHT'])) ? $this->_rootref['MCHAT_INDEX_HEIGHT'] : ''; ?>" /></dd>
		</dl>
		<dl>
			<dt><label for="mchat_location"><?php echo ((isset($this->_rootref['L_MCHAT_LOCATION'])) ? $this->_rootref['L_MCHAT_LOCATION'] : ((isset($user->lang['MCHAT_LOCATION'])) ? $user->lang['MCHAT_LOCATION'] : '{ MCHAT_LOCATION }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_LOCATION_EXPLAIN'])) ? $this->_rootref['L_MCHAT_LOCATION_EXPLAIN'] : ((isset($user->lang['MCHAT_LOCATION_EXPLAIN'])) ? $user->lang['MCHAT_LOCATION_EXPLAIN'] : '{ MCHAT_LOCATION_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="mchat_location" value="1"<?php if ($this->_rootref['MCHAT_LOCATION']) {  ?> id="mchat_location" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_MCHAT_TOP_OF_FORUM'])) ? $this->_rootref['L_MCHAT_TOP_OF_FORUM'] : ((isset($user->lang['MCHAT_TOP_OF_FORUM'])) ? $user->lang['MCHAT_TOP_OF_FORUM'] : '{ MCHAT_TOP_OF_FORUM }')); ?></label>
			<label><input type="radio" class="radio" name="mchat_location" value="0"<?php if (! $this->_rootref['MCHAT_LOCATION']) {  ?> id="mchat_location" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_MCHAT_BOTTOM_OF_FORUM'])) ? $this->_rootref['L_MCHAT_BOTTOM_OF_FORUM'] : ((isset($user->lang['MCHAT_BOTTOM_OF_FORUM'])) ? $user->lang['MCHAT_BOTTOM_OF_FORUM'] : '{ MCHAT_BOTTOM_OF_FORUM }')); ?></label></dd>
		</dl>		
		<dl>
			<dt><label for="mchat_custom_page"><?php echo ((isset($this->_rootref['L_MCHAT_CUSTOM_PAGE'])) ? $this->_rootref['L_MCHAT_CUSTOM_PAGE'] : ((isset($user->lang['MCHAT_CUSTOM_PAGE'])) ? $user->lang['MCHAT_CUSTOM_PAGE'] : '{ MCHAT_CUSTOM_PAGE }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_CUSTOM_PAGE_EXPLAIN'])) ? $this->_rootref['L_MCHAT_CUSTOM_PAGE_EXPLAIN'] : ((isset($user->lang['MCHAT_CUSTOM_PAGE_EXPLAIN'])) ? $user->lang['MCHAT_CUSTOM_PAGE_EXPLAIN'] : '{ MCHAT_CUSTOM_PAGE_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="mchat_custom_page" value="1"<?php if ($this->_rootref['MCHAT_CUSTOM_PAGE']) {  ?> id="mchat_custom_page" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
			<label><input type="radio" class="radio" name="mchat_custom_page" value="0"<?php if (! $this->_rootref['MCHAT_CUSTOM_PAGE']) {  ?> id="mchat_custom_page" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>
		<dl>
			<dt><label for="mchat_custom_height"><?php echo ((isset($this->_rootref['L_MCHAT_CUSTOM_HEIGHT'])) ? $this->_rootref['L_MCHAT_CUSTOM_HEIGHT'] : ((isset($user->lang['MCHAT_CUSTOM_HEIGHT'])) ? $user->lang['MCHAT_CUSTOM_HEIGHT'] : '{ MCHAT_CUSTOM_HEIGHT }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_CUSTOM_HEIGHT_EXPLAIN'])) ? $this->_rootref['L_MCHAT_CUSTOM_HEIGHT_EXPLAIN'] : ((isset($user->lang['MCHAT_CUSTOM_HEIGHT_EXPLAIN'])) ? $user->lang['MCHAT_CUSTOM_HEIGHT_EXPLAIN'] : '{ MCHAT_CUSTOM_HEIGHT_EXPLAIN }')); ?></span></dt>
			<dd><input type="text" name="mchat_custom_height" size="10" maxlength="4" value="<?php echo (isset($this->_rootref['MCHAT_CUSTOM_HEIGHT'])) ? $this->_rootref['MCHAT_CUSTOM_HEIGHT'] : ''; ?>" /></dd>
		</dl>
		<dl>
			<dt><label for="mchat_prune_enable"><?php echo ((isset($this->_rootref['L_MCHAT_PRUNE'])) ? $this->_rootref['L_MCHAT_PRUNE'] : ((isset($user->lang['MCHAT_PRUNE'])) ? $user->lang['MCHAT_PRUNE'] : '{ MCHAT_PRUNE }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_PRUNE_EXPLAIN'])) ? $this->_rootref['L_MCHAT_PRUNE_EXPLAIN'] : ((isset($user->lang['MCHAT_PRUNE_EXPLAIN'])) ? $user->lang['MCHAT_PRUNE_EXPLAIN'] : '{ MCHAT_PRUNE_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="mchat_prune_enable" value="1"<?php if ($this->_rootref['MCHAT_PRUNE']) {  ?> id="mchat_prune_enable" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
			<label><input type="radio" class="radio" name="mchat_prune_enable" value="0"<?php if (! $this->_rootref['MCHAT_PRUNE']) {  ?> id="mchat_prune_enable" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>
		<dl>
			<dt><label for="mchat_prune_num"><?php echo ((isset($this->_rootref['L_MCHAT_PRUNE_NUM'])) ? $this->_rootref['L_MCHAT_PRUNE_NUM'] : ((isset($user->lang['MCHAT_PRUNE_NUM'])) ? $user->lang['MCHAT_PRUNE_NUM'] : '{ MCHAT_PRUNE_NUM }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_PRUNE_NUM_EXPLAIN'])) ? $this->_rootref['L_MCHAT_PRUNE_NUM_EXPLAIN'] : ((isset($user->lang['MCHAT_PRUNE_NUM_EXPLAIN'])) ? $user->lang['MCHAT_PRUNE_NUM_EXPLAIN'] : '{ MCHAT_PRUNE_NUM_EXPLAIN }')); ?></span></dt>
			<dd><input type="text" name="mchat_prune_num" size="10" value="<?php echo (isset($this->_rootref['MCHAT_PRUNE_NUM'])) ? $this->_rootref['MCHAT_PRUNE_NUM'] : ''; ?>" /></dd>
		</dl>		
	</fieldset>
	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_MCHAT_MESSAGES'])) ? $this->_rootref['L_MCHAT_MESSAGES'] : ((isset($user->lang['MCHAT_MESSAGES'])) ? $user->lang['MCHAT_MESSAGES'] : '{ MCHAT_MESSAGES }')); ?></legend>				
		<dl>
			<dt><label for="mchat_flood_time"><?php echo ((isset($this->_rootref['L_MCHAT_FLOOD_TIME'])) ? $this->_rootref['L_MCHAT_FLOOD_TIME'] : ((isset($user->lang['MCHAT_FLOOD_TIME'])) ? $user->lang['MCHAT_FLOOD_TIME'] : '{ MCHAT_FLOOD_TIME }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_FLOOD_TIME_EXPLAIN'])) ? $this->_rootref['L_MCHAT_FLOOD_TIME_EXPLAIN'] : ((isset($user->lang['MCHAT_FLOOD_TIME_EXPLAIN'])) ? $user->lang['MCHAT_FLOOD_TIME_EXPLAIN'] : '{ MCHAT_FLOOD_TIME_EXPLAIN }')); ?></span></dt>
			<dd><input type="text" name="mchat_flood_time" size="10" value="<?php echo (isset($this->_rootref['MCHAT_FLOOD_TIME'])) ? $this->_rootref['MCHAT_FLOOD_TIME'] : ''; ?>" /></dd>
		</dl>		
		<dl>
			<dt><label for="mchat_message_limit"><?php echo ((isset($this->_rootref['L_MCHAT_MESSAGE_LIMIT'])) ? $this->_rootref['L_MCHAT_MESSAGE_LIMIT'] : ((isset($user->lang['MCHAT_MESSAGE_LIMIT'])) ? $user->lang['MCHAT_MESSAGE_LIMIT'] : '{ MCHAT_MESSAGE_LIMIT }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_MESSAGE_LIMIT_EXPLAIN'])) ? $this->_rootref['L_MCHAT_MESSAGE_LIMIT_EXPLAIN'] : ((isset($user->lang['MCHAT_MESSAGE_LIMIT_EXPLAIN'])) ? $user->lang['MCHAT_MESSAGE_LIMIT_EXPLAIN'] : '{ MCHAT_MESSAGE_LIMIT_EXPLAIN }')); ?></span></dt>
			<dd><input type="text" name="mchat_message_limit" size="10" value="<?php echo (isset($this->_rootref['MCHAT_MESSAGE_LIMIT'])) ? $this->_rootref['MCHAT_MESSAGE_LIMIT'] : ''; ?>" /></dd>
		</dl>
		<dl>
			<dt><label for="mchat_archive_limit"><?php echo ((isset($this->_rootref['L_MCHAT_ARCHIVE_LIMIT'])) ? $this->_rootref['L_MCHAT_ARCHIVE_LIMIT'] : ((isset($user->lang['MCHAT_ARCHIVE_LIMIT'])) ? $user->lang['MCHAT_ARCHIVE_LIMIT'] : '{ MCHAT_ARCHIVE_LIMIT }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_ARCHIVE_LIMIT_EXPLAIN'])) ? $this->_rootref['L_MCHAT_ARCHIVE_LIMIT_EXPLAIN'] : ((isset($user->lang['MCHAT_ARCHIVE_LIMIT_EXPLAIN'])) ? $user->lang['MCHAT_ARCHIVE_LIMIT_EXPLAIN'] : '{ MCHAT_ARCHIVE_LIMIT_EXPLAIN }')); ?></span></dt>
			<dd><input type="text" name="mchat_archive_limit" size="10" value="<?php echo (isset($this->_rootref['MCHAT_ARCHIVE_LIMIT'])) ? $this->_rootref['MCHAT_ARCHIVE_LIMIT'] : ''; ?>" /></dd>
		</dl>
		<dl>
			<dt><label for="mchat_avatars"><?php echo ((isset($this->_rootref['L_MCHAT_AVATARS'])) ? $this->_rootref['L_MCHAT_AVATARS'] : ((isset($user->lang['MCHAT_AVATARS'])) ? $user->lang['MCHAT_AVATARS'] : '{ MCHAT_AVATARS }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_AVATARS_EXPLAIN'])) ? $this->_rootref['L_MCHAT_AVATARS_EXPLAIN'] : ((isset($user->lang['MCHAT_AVATARS_EXPLAIN'])) ? $user->lang['MCHAT_AVATARS_EXPLAIN'] : '{ MCHAT_AVATARS_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="mchat_avatars" value="1"<?php if ($this->_rootref['MCHAT_AVATARS']) {  ?> id="mchat_avatars" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
			<label><input type="radio" class="radio" name="mchat_avatars" value="0"<?php if (! $this->_rootref['MCHAT_AVATARS']) {  ?> id="mchat_avatars" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>		
		<dl>
			<dt><label for="mchat_pause_on_input"><?php echo ((isset($this->_rootref['L_MCHAT_PAUSE_ON_INPUT'])) ? $this->_rootref['L_MCHAT_PAUSE_ON_INPUT'] : ((isset($user->lang['MCHAT_PAUSE_ON_INPUT'])) ? $user->lang['MCHAT_PAUSE_ON_INPUT'] : '{ MCHAT_PAUSE_ON_INPUT }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_PAUSE_ON_INPUT_EXPLAIN'])) ? $this->_rootref['L_MCHAT_PAUSE_ON_INPUT_EXPLAIN'] : ((isset($user->lang['MCHAT_PAUSE_ON_INPUT_EXPLAIN'])) ? $user->lang['MCHAT_PAUSE_ON_INPUT_EXPLAIN'] : '{ MCHAT_PAUSE_ON_INPUT_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="mchat_pause_on_input" value="1"<?php if ($this->_rootref['MCHAT_PAUSE_ON_INPUT']) {  ?> id="mchat_pause_on_input" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
			<label><input type="radio" class="radio" name="mchat_pause_on_input" value="0"<?php if (! $this->_rootref['MCHAT_PAUSE_ON_INPUT']) {  ?> id="mchat_pause_on_input" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>		
		<dl>
			<dt><label for="mchat_override_min_post_chars"><?php echo ((isset($this->_rootref['L_MCHAT_OVERRIDE_MIN_POST_CHARS'])) ? $this->_rootref['L_MCHAT_OVERRIDE_MIN_POST_CHARS'] : ((isset($user->lang['MCHAT_OVERRIDE_MIN_POST_CHARS'])) ? $user->lang['MCHAT_OVERRIDE_MIN_POST_CHARS'] : '{ MCHAT_OVERRIDE_MIN_POST_CHARS }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_OVERRIDE_MIN_POST_CHARS_EXPLAIN'])) ? $this->_rootref['L_MCHAT_OVERRIDE_MIN_POST_CHARS_EXPLAIN'] : ((isset($user->lang['MCHAT_OVERRIDE_MIN_POST_CHARS_EXPLAIN'])) ? $user->lang['MCHAT_OVERRIDE_MIN_POST_CHARS_EXPLAIN'] : '{ MCHAT_OVERRIDE_MIN_POST_CHARS_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="mchat_override_min_post_chars" value="1"<?php if ($this->_rootref['MCHAT_OVERRIDE_MIN_POST_CHARS']) {  ?> id="mchat_override_min_post_chars" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
			<label><input type="radio" class="radio" name="mchat_override_min_post_chars" value="0"<?php if (! $this->_rootref['MCHAT_OVERRIDE_MIN_POST_CHARS']) {  ?> id="mchat_override_min_post_chars" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>
		<dl>
			<dt><label for="mchat_override_smilie_limit"><?php echo ((isset($this->_rootref['L_MCHAT_OVERRIDE_SMILIE_LIMIT'])) ? $this->_rootref['L_MCHAT_OVERRIDE_SMILIE_LIMIT'] : ((isset($user->lang['MCHAT_OVERRIDE_SMILIE_LIMIT'])) ? $user->lang['MCHAT_OVERRIDE_SMILIE_LIMIT'] : '{ MCHAT_OVERRIDE_SMILIE_LIMIT }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_OVERRIDE_SMILIE_LIMIT_EXPLAIN'])) ? $this->_rootref['L_MCHAT_OVERRIDE_SMILIE_LIMIT_EXPLAIN'] : ((isset($user->lang['MCHAT_OVERRIDE_SMILIE_LIMIT_EXPLAIN'])) ? $user->lang['MCHAT_OVERRIDE_SMILIE_LIMIT_EXPLAIN'] : '{ MCHAT_OVERRIDE_SMILIE_LIMIT_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="mchat_override_smilie_limit" value="1"<?php if ($this->_rootref['MCHAT_OVERRIDE_SMILIE_LIMIT']) {  ?> id="mchat_override_smilie_limit" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
			<label><input type="radio" class="radio" name="mchat_override_smilie_limit" value="0"<?php if (! $this->_rootref['MCHAT_OVERRIDE_SMILIE_LIMIT']) {  ?> id="mchat_override_smilie_limit" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>		
		<dl>
			<dt><label for="mchat_max_message_lngth"><?php echo ((isset($this->_rootref['L_MCHAT_MAX_MESSAGE_LENGTH'])) ? $this->_rootref['L_MCHAT_MAX_MESSAGE_LENGTH'] : ((isset($user->lang['MCHAT_MAX_MESSAGE_LENGTH'])) ? $user->lang['MCHAT_MAX_MESSAGE_LENGTH'] : '{ MCHAT_MAX_MESSAGE_LENGTH }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_MAX_MESSAGE_LENGTH_EXPLAIN'])) ? $this->_rootref['L_MCHAT_MAX_MESSAGE_LENGTH_EXPLAIN'] : ((isset($user->lang['MCHAT_MAX_MESSAGE_LENGTH_EXPLAIN'])) ? $user->lang['MCHAT_MAX_MESSAGE_LENGTH_EXPLAIN'] : '{ MCHAT_MAX_MESSAGE_LENGTH_EXPLAIN }')); ?></span></dt>
			<dd><input type="text" name="mchat_max_message_lngth" size="10" value="<?php echo (isset($this->_rootref['MCHAT_MAX_MESSAGE_LNGTH'])) ? $this->_rootref['MCHAT_MAX_MESSAGE_LNGTH'] : ''; ?>" /></dd>
		</dl>
		<dl>
			<dt><label for="mchat_date"><?php echo ((isset($this->_rootref['L_MCHAT_DATE_FORMAT'])) ? $this->_rootref['L_MCHAT_DATE_FORMAT'] : ((isset($user->lang['MCHAT_DATE_FORMAT'])) ? $user->lang['MCHAT_DATE_FORMAT'] : '{ MCHAT_DATE_FORMAT }')); ?>:</label><br /><span><?php echo ((isset($this->_rootref['L_MCHAT_DATE_FORMAT_EXPLAIN'])) ? $this->_rootref['L_MCHAT_DATE_FORMAT_EXPLAIN'] : ((isset($user->lang['MCHAT_DATE_FORMAT_EXPLAIN'])) ? $user->lang['MCHAT_DATE_FORMAT_EXPLAIN'] : '{ MCHAT_DATE_FORMAT_EXPLAIN }')); ?></span></dt>
			<dd>
				<select name="mchat_dateoptions" id="mchat_dateoptions" onchange="if(this.value=='custom'){dE('custom_date',1);}else{dE('custom_date',-1);} if (this.value == 'custom') { document.getElementById('mchat_date').value = mchat_default_dateformat; } else { document.getElementById('mchat_date').value = this.value; }">
					<?php echo (isset($this->_rootref['S_MCHAT_DATEFORMAT_OPTIONS'])) ? $this->_rootref['S_MCHAT_DATEFORMAT_OPTIONS'] : ''; ?>

				</select>
			</dd>
			<dd id="custom_date" style="display:none;"><input type="text" name="mchat_date" id="mchat_date" value="<?php echo (isset($this->_rootref['MCHAT_DATE'])) ? $this->_rootref['MCHAT_DATE'] : ''; ?>" maxlength="30" class="inputbox narrow" style="margin-top: 3px;" /></dd>
		</dl>
		<dl>
			<dt><label for="mchat_bbcode_disallowed"><?php echo ((isset($this->_rootref['L_MCHAT_BBCODES_DISALLOWED'])) ? $this->_rootref['L_MCHAT_BBCODES_DISALLOWED'] : ((isset($user->lang['MCHAT_BBCODES_DISALLOWED'])) ? $user->lang['MCHAT_BBCODES_DISALLOWED'] : '{ MCHAT_BBCODES_DISALLOWED }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_BBCODES_DISALLOWED_EXPLAIN'])) ? $this->_rootref['L_MCHAT_BBCODES_DISALLOWED_EXPLAIN'] : ((isset($user->lang['MCHAT_BBCODES_DISALLOWED_EXPLAIN'])) ? $user->lang['MCHAT_BBCODES_DISALLOWED_EXPLAIN'] : '{ MCHAT_BBCODES_DISALLOWED_EXPLAIN }')); ?></span></dt>
			<dd><textarea name="mchat_bbcode_disallowed" id="mchat_bbcode_disallowed" rows="3" cols="40"><?php echo (isset($this->_rootref['MCHAT_BBCODE_DISALLOWED'])) ? $this->_rootref['MCHAT_BBCODE_DISALLOWED'] : ''; ?></textarea></dd>
		</dl>
		<dl>
			<dt><label for="mchat_rules"><?php echo ((isset($this->_rootref['L_ACP_MCHAT_RULES'])) ? $this->_rootref['L_ACP_MCHAT_RULES'] : ((isset($user->lang['ACP_MCHAT_RULES'])) ? $user->lang['ACP_MCHAT_RULES'] : '{ ACP_MCHAT_RULES }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_ACP_MCHAT_RULES_EXPLAIN'])) ? $this->_rootref['L_ACP_MCHAT_RULES_EXPLAIN'] : ((isset($user->lang['ACP_MCHAT_RULES_EXPLAIN'])) ? $user->lang['ACP_MCHAT_RULES_EXPLAIN'] : '{ ACP_MCHAT_RULES_EXPLAIN }')); ?></span></dt>
			<dd><textarea name="mchat_rules" id="contact_reasons" rows="8" cols="40"><?php echo (isset($this->_rootref['MCHAT_RULES'])) ? $this->_rootref['MCHAT_RULES'] : ''; ?></textarea></dd>
		</dl>		
	</fieldset>	
	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_MCHAT_STATS'])) ? $this->_rootref['L_MCHAT_STATS'] : ((isset($user->lang['MCHAT_STATS'])) ? $user->lang['MCHAT_STATS'] : '{ MCHAT_STATS }')); ?></legend>		
		<dl>
			<dt><label for="mchat_whois"><?php echo ((isset($this->_rootref['L_MCHAT_WHOIS'])) ? $this->_rootref['L_MCHAT_WHOIS'] : ((isset($user->lang['MCHAT_WHOIS'])) ? $user->lang['MCHAT_WHOIS'] : '{ MCHAT_WHOIS }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_WHOIS_EXPLAIN'])) ? $this->_rootref['L_MCHAT_WHOIS_EXPLAIN'] : ((isset($user->lang['MCHAT_WHOIS_EXPLAIN'])) ? $user->lang['MCHAT_WHOIS_EXPLAIN'] : '{ MCHAT_WHOIS_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="mchat_whois" value="1"<?php if ($this->_rootref['MCHAT_WHOIS']) {  ?> id="mchat_whois" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
			<label><input type="radio" class="radio" name="mchat_whois" value="0"<?php if (! $this->_rootref['MCHAT_WHOIS']) {  ?> id="mchat_whois" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>
		</dl>
		<dl>
			<dt><label for="mchat_stats_index"><?php echo ((isset($this->_rootref['L_MCHAT_STATS_INDEX'])) ? $this->_rootref['L_MCHAT_STATS_INDEX'] : ((isset($user->lang['MCHAT_STATS_INDEX'])) ? $user->lang['MCHAT_STATS_INDEX'] : '{ MCHAT_STATS_INDEX }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_STATS_INDEX_EXPLAIN'])) ? $this->_rootref['L_MCHAT_STATS_INDEX_EXPLAIN'] : ((isset($user->lang['MCHAT_STATS_INDEX_EXPLAIN'])) ? $user->lang['MCHAT_STATS_INDEX_EXPLAIN'] : '{ MCHAT_STATS_INDEX_EXPLAIN }')); ?></span></dt>
			<dd><label><input type="radio" class="radio" name="mchat_stats_index" value="1"<?php if ($this->_rootref['MCHAT_STATS_INDEX']) {  ?> id="mchat_stats_index" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label>
			<label><input type="radio" class="radio" name="mchat_stats_index" value="0"<?php if (! $this->_rootref['MCHAT_STATS_INDEX']) {  ?> id="mchat_stats_index" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label></dd>			
		</dl>		
		<dl>
			<dt><label for="mchat_whois"><?php echo ((isset($this->_rootref['L_MCHAT_WHOIS_REFRESH'])) ? $this->_rootref['L_MCHAT_WHOIS_REFRESH'] : ((isset($user->lang['MCHAT_WHOIS_REFRESH'])) ? $user->lang['MCHAT_WHOIS_REFRESH'] : '{ MCHAT_WHOIS_REFRESH }')); ?>:</label><br />
			<span><?php echo ((isset($this->_rootref['L_MCHAT_WHOIS_REFRESH_EXPLAIN'])) ? $this->_rootref['L_MCHAT_WHOIS_REFRESH_EXPLAIN'] : ((isset($user->lang['MCHAT_WHOIS_REFRESH_EXPLAIN'])) ? $user->lang['MCHAT_WHOIS_REFRESH_EXPLAIN'] : '{ MCHAT_WHOIS_REFRESH_EXPLAIN }')); ?></span></dt>
			<dd><input type="text" name="mchat_whois_refresh" size="10" value="<?php echo (isset($this->_rootref['MCHAT_WHOIS_REFRESH'])) ? $this->_rootref['MCHAT_WHOIS_REFRESH'] : ''; ?>" /></dd>
		</dl>		

	</fieldset>
	<p class="submit-buttons">
			<input class="button1" type="submit" id="submit" name="submit" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" />&nbsp;
			<input class="button2" type="reset" id="reset" name="reset" value="<?php echo ((isset($this->_rootref['L_RESET'])) ? $this->_rootref['L_RESET'] : ((isset($user->lang['RESET'])) ? $user->lang['RESET'] : '{ RESET }')); ?>" />
		<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

	</p>	
</form>
<?php $this->_tpl_include('overall_footer.html'); ?>