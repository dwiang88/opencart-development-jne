<?php
// include DIR_SYSTEM . 'jne/classes/jne-shipping-rate-functions.php';
include DIR_SYSTEM . 'jne/classes/class-parse-jne.php';

class ModelShippingJne extends Model {
	private $jne;

	function populateJNE(){
		$this->jne = new Parse_JNE();
		$this->jne->filename = '04-2013';
		$this->jne->start = 5;
		$this->jne->populate();
		return $this->jne;
	}
}
?>