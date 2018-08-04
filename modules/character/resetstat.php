<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Reset Stat';

$charID = $params->get('id');
if (!$charID) {
	  $this->deny();
}

$char = $server->getCharacter($charID);
if (!$char || ($char->account_id != $session->account->account_id && !$auth->allowedToResetStat)) {
	  $this->deny();
}

$reset = $server->resetStatPoint($charID);
if ($reset === -1) {
	  $message = sprintf(Flux::message('CantResetStatWhenOnline'), $char->name);
}
elseif ($reset === true) {
	  $message = sprintf(Flux::message('ResetStatSuccessful'), $char->name);
}
else {
	  $message = sprintf(Flux::message('ResetStatFailed'), $char->name);
}

$session->setMessageData($message);
$this->redirect($this->url('character', 'view', array('id' => $charID)));
?>
