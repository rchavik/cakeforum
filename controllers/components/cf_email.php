<?php

class CfEmailComponent extends Object {

	public $components = array('Email');
	
    public function send($to, $subject, $template) {
    	
		$this->Email->to = $to;
		$this->Email->subject = $subject;
		$this->Email->from = Configure::read('CfEmail') . '<' . Configure::read('CfEmail') . '>';
		$this->Email->replyTo = Configure::read('CfEmail');
		$this->Email->template = $template;
		$this->Email->sendAs = 'both';

		return $this->Email->send();
    }
}

?>