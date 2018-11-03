<?php if (!defined('IN_PHPBB')) exit; ?><form id="users_mchat" method="post" action="<?php echo (isset($this->_rootref['U_ACTION'])) ? $this->_rootref['U_ACTION'] : ''; ?>">
	<fieldset>
		<legend><?php echo ((isset($this->_rootref['L_UCP_PROFILE_MCHAT'])) ? $this->_rootref['L_UCP_PROFILE_MCHAT'] : ((isset($user->lang['UCP_PROFILE_MCHAT'])) ? $user->lang['UCP_PROFILE_MCHAT'] : '{ UCP_PROFILE_MCHAT }')); ?></legend>
	<dl>
		<dt><label for="mchat_index"><?php echo ((isset($this->_rootref['L_DISPLAY_MCHAT'])) ? $this->_rootref['L_DISPLAY_MCHAT'] : ((isset($user->lang['DISPLAY_MCHAT'])) ? $user->lang['DISPLAY_MCHAT'] : '{ DISPLAY_MCHAT }')); ?>:</label></dt>
		<dd>
			<label><input type="radio" name="user_mchat_index" value="1"<?php if ($this->_rootref['DISPLAY_MCHAT']) {  ?> id="mchat_index" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label> 
			<label><input type="radio" name="user_mchat_index" value="0"<?php if (! $this->_rootref['DISPLAY_MCHAT']) {  ?> id="mchat_index" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label>
		</dd>
	</dl>
	<dl>
		<dt><label for="mchat_sound"><?php echo ((isset($this->_rootref['L_SOUND_MCHAT'])) ? $this->_rootref['L_SOUND_MCHAT'] : ((isset($user->lang['SOUND_MCHAT'])) ? $user->lang['SOUND_MCHAT'] : '{ SOUND_MCHAT }')); ?>:</label></dt>
		<dd>
			<label><input type="radio" name="user_mchat_sound" value="1"<?php if ($this->_rootref['SOUND_MCHAT']) {  ?> id="mchat_sound" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label> 
			<label><input type="radio" name="user_mchat_sound" value="0"<?php if (! $this->_rootref['SOUND_MCHAT']) {  ?> id="mchat_sound" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label>
		</dd>
	</dl>
	<dl>
		<dt><label for="mchat_statsindex"><?php echo ((isset($this->_rootref['L_DISPLAY_STATS_INDEX'])) ? $this->_rootref['L_DISPLAY_STATS_INDEX'] : ((isset($user->lang['DISPLAY_STATS_INDEX'])) ? $user->lang['DISPLAY_STATS_INDEX'] : '{ DISPLAY_STATS_INDEX }')); ?>:</label></dt>
		<dd>
			<label><input type="radio" name="user_mchat_stats_index" value="1"<?php if ($this->_rootref['STATS_MCHAT']) {  ?> id="mchat_statsindex" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label> 
			<label><input type="radio" name="user_mchat_stats_index" value="0"<?php if (! $this->_rootref['STATS_MCHAT']) {  ?> id="mchat_statsindex" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label>
		</dd>
	</dl>
	<dl>
		<dt><label for="mchat_topics"><?php echo ((isset($this->_rootref['L_DISPLAY_NEW_TOPICS'])) ? $this->_rootref['L_DISPLAY_NEW_TOPICS'] : ((isset($user->lang['DISPLAY_NEW_TOPICS'])) ? $user->lang['DISPLAY_NEW_TOPICS'] : '{ DISPLAY_NEW_TOPICS }')); ?>:</label></dt>
		<dd>
			<label><input type="radio" name="user_mchat_topics" value="1"<?php if ($this->_rootref['TOPICS_MCHAT']) {  ?> id="mchat_topics" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label> 
			<label><input type="radio" name="user_mchat_topics" value="0"<?php if (! $this->_rootref['TOPICS_MCHAT']) {  ?> id="mchat_topics" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label>
		</dd>
	</dl>
	<dl>
		<dt><label for="mchat_avatars"><?php echo ((isset($this->_rootref['L_DISPLAY_AVATARS'])) ? $this->_rootref['L_DISPLAY_AVATARS'] : ((isset($user->lang['DISPLAY_AVATARS'])) ? $user->lang['DISPLAY_AVATARS'] : '{ DISPLAY_AVATARS }')); ?>:</label></dt>
		<dd>
			<label><input type="radio" name="user_mchat_avatars" value="1"<?php if ($this->_rootref['AVATARS_MCHAT']) {  ?> id="mchat_avatars" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_YES'])) ? $this->_rootref['L_YES'] : ((isset($user->lang['YES'])) ? $user->lang['YES'] : '{ YES }')); ?></label> 
			<label><input type="radio" name="user_mchat_avatars" value="0"<?php if (! $this->_rootref['AVATARS_MCHAT']) {  ?> id="mchat_avatars" checked="checked"<?php } ?> /> <?php echo ((isset($this->_rootref['L_NO'])) ? $this->_rootref['L_NO'] : ((isset($user->lang['NO'])) ? $user->lang['NO'] : '{ NO }')); ?></label>
		</dd>
	</dl>	
	</fieldset>
	<fieldset class="quick">
		<input class="button1" type="submit" name="update" value="<?php echo ((isset($this->_rootref['L_SUBMIT'])) ? $this->_rootref['L_SUBMIT'] : ((isset($user->lang['SUBMIT'])) ? $user->lang['SUBMIT'] : '{ SUBMIT }')); ?>" />
		<?php echo (isset($this->_rootref['S_FORM_TOKEN'])) ? $this->_rootref['S_FORM_TOKEN'] : ''; ?>

	</fieldset>	
	</form>