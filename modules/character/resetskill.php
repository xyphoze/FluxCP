<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Reset Skill';

$charID = $params->get('id');
if (!$charID) {
	$this->deny();
}

$char = $server->getCharacter($charID);
if (!$char || ($char->account_id != $session->account->account_id && !$auth->allowedToResetSkill)) {
	$this->deny();
}

$reset = $server->resetSkillPoint($charID);
if ($reset === -1) {
	$message = sprintf(Flux::message('CantResetSkillWhenOnline'), $char->name);
}
elseif ($reset === true) {
	$message = sprintf(Flux::message('ResetSkillSuccessful'), $char->name);
}
else {
	$message = sprintf(Flux::message('ResetSkillFailed'), $char->name);
}

$session->setMessageData($message);
$this->redirect($this->url('character', 'view', array('id' => $charID)));
?>
