<?php if (!defined('IN_PHPBB')) exit; ?>Subject: Un nouveau message privé est arrivé

Bonjour <?php echo (isset($this->_rootref['USERNAME'])) ? $this->_rootref['USERNAME'] : ''; ?>,

Vous avez reçu un nouveau message privé de « <?php echo (isset($this->_rootref['AUTHOR_NAME'])) ? $this->_rootref['AUTHOR_NAME'] : ''; ?> » sur votre compte de « <?php echo (isset($this->_rootref['SITENAME'])) ? $this->_rootref['SITENAME'] : ''; ?> » dont le sujet est :

<?php echo (isset($this->_rootref['SUBJECT'])) ? $this->_rootref['SUBJECT'] : ''; ?>


Vous pouvez consulter votre nouveau message en cliquant sur le lien suivant :

<?php echo (isset($this->_rootref['U_VIEW_MESSAGE'])) ? $this->_rootref['U_VIEW_MESSAGE'] : ''; ?>


Vous avez demandé à recevoir une notification lors de cet évènement, sachez que vous pouvez toujours choisir de ne pas être notifié(e) lors de la réception de nouveaux messages en modifiant le réglage approprié dans votre profil.

<?php echo (isset($this->_rootref['EMAIL_SIG'])) ? $this->_rootref['EMAIL_SIG'] : ''; ?>