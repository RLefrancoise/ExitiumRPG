<?php if (!defined('IN_PHPBB')) exit; if (! $this->_rootref['S_IS_BOT']) {  echo (isset($this->_rootref['RUN_CRON_TASK'])) ? $this->_rootref['RUN_CRON_TASK'] : ''; } ?>

</div>

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

<div id="wrapfooter"><div id="wrapfooter1"><div id="wrapfooter2"><div class="extratools"></div>

	<span class="copyright">UniversalSpace Style by <a href="http://leoitalia.oraweb.it">LEOITALIA</a> &copy; 2009, 2012 LEOITALIA Styles<br />
        <?php echo (isset($this->_rootref['CREDIT_LINE'])) ? $this->_rootref['CREDIT_LINE'] : ''; ?>






	<?php if ($this->_rootref['TRANSLATION_INFO']) {  ?><br /><?php echo (isset($this->_rootref['TRANSLATION_INFO'])) ? $this->_rootref['TRANSLATION_INFO'] : ''; } if ($this->_rootref['DEBUG_OUTPUT']) {  ?><br /><bdo dir="ltr">[ <?php echo (isset($this->_rootref['DEBUG_OUTPUT'])) ? $this->_rootref['DEBUG_OUTPUT'] : ''; ?> ]</bdo><?php } ?></span>

      <div class="copyright1">
        
   </div>
  </div>
 </div>
 	<?php if ($this->_rootref['U_ACP']) {  ?><span class="gensmall">[ <a href="<?php echo (isset($this->_rootref['U_ACP'])) ? $this->_rootref['U_ACP'] : ''; ?>"><?php echo ((isset($this->_rootref['L_ACP'])) ? $this->_rootref['L_ACP'] : ((isset($user->lang['ACP'])) ? $user->lang['ACP'] : '{ ACP }')); ?></a> ]</span><br /><br /><?php } ?>

</div>
</body>
</html>