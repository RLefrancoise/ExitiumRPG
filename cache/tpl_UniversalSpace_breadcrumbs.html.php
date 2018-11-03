<?php if (!defined('IN_PHPBB')) exit; ?><table class="tablebg" width="100%" cellspacing="1" cellpadding="0" style="margin-top: 5px;">
	<tr>
		<td class="row4">
			<p class="breadcrumbs"><a href="<?php echo (isset($this->_rootref['U_INDEX'])) ? $this->_rootref['U_INDEX'] : ''; ?>"><?php echo ((isset($this->_rootref['L_INDEX'])) ? $this->_rootref['L_INDEX'] : ((isset($user->lang['INDEX'])) ? $user->lang['INDEX'] : '{ INDEX }')); ?></a><?php $_navlinks_count = (isset($this->_tpldata['navlinks'])) ? sizeof($this->_tpldata['navlinks']) : 0;if ($_navlinks_count) {for ($_navlinks_i = 0; $_navlinks_i < $_navlinks_count; ++$_navlinks_i){$_navlinks_val = &$this->_tpldata['navlinks'][$_navlinks_i]; ?> &#187; <a href="<?php echo $_navlinks_val['U_VIEW_FORUM']; ?>"><?php echo $_navlinks_val['FORUM_NAME']; ?></a><?php }} ?></p>
			<p class="datetime"><?php echo (isset($this->_rootref['S_TIMEZONE'])) ? $this->_rootref['S_TIMEZONE'] : ''; ?></p>
		</td>
	</tr>
	</table>
	
<!--
	Style version: UniversalSpace 1.0.7
	Released: 01-09-2009
	copyright (C) 2009 - 2012 LEOITALIA
	http://leoitalia.oraweb.it
	phpBB version: 3.0.11
	
	Creative Commons Attribution-Noncommercial-No Derivative Works 2.5 Italy
	http://creativecommons.org/licenses/by-nc-nd/2.5/it/deed.en_US
	You may use this Style for free if you don't alert the copyright info.

	We request you retain the full copyright notice below including the link to
	leoitalia.oraweb.it  to  www.oraweb.it  and logo OW
	This not only gives respect to the large amount of time
	given freely by the developers.	But also helps build interest.
	If you (honestly) cannot retain the full copyright are prohibited from using.

	LEOITALIA : 2009

//-->