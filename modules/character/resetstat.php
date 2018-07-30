<?php
if (!defined('FLUX_ROOT')) exit;

$this->loginRequired();

$title = 'Reset Position';

function latin_to_thai($input) {
  if($input) {
    $orig_entities = mb_encode_numericentity($input, array(0x00, 0xffff, 0, 0xffff), 'UTF-8');
    foreach (explode(";", rtrim($orig_entities, ";")) as $orig_entity){
      $new_entity = (int)str_replace("&#","", $orig_entity);
      if($new_entity > 160 && $new_entity < 256) { $new_entity += 3424; }
      $new_entities = $new_entities . "&#" . $new_entity . ";";
    }
    return html_entity_decode($new_entities);
  } else {
    return $input;
  }
}

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
	$message = sprintf(Flux::message('CantResetStatWhenOnline'), latin_to_thai($char->name));
}
elseif ($reset === true) {
	$message = sprintf(Flux::message('ResetStatSuccessful'), latin_to_thai($char->name));
}
else {
	$message = sprintf(Flux::message('ResetStatFailed'), latin_to_thai($char->name));
}

$session->setMessageData($message);
$this->redirect($this->url('character', 'view', array('id' => $charID)));
?>
