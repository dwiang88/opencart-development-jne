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

	function getShipping( $city_id ){
		$this->language->load('shipping/jne');
		
		$data = array();
		if( $taxes = $this->jne->getTax( $city_id ) ) 
		{
			$default_currency = $this->session->data['currency'];
			foreach( $taxes as $layanan => $tarif )
			{				
				$cost = ( $default_currency == 'IDR' ) ? $this->currency->convert($tarif['harga'], 'IDR', 'USD') : $this->currency->convert($tarif['harga'], 'IDR', $default_currency);
				$text = $this->currency->format($cost, 'IDR');
				if( $default_currency != 'IDR' ){
					$text .= '( ' .  $this->currency->format($cost, $default_currency) . ')';
				}
				
				$data[$layanan] = array(
	        		'code'         => 'jne.' . $layanan,
	        		'title'        => $this->language->get('text_description') . ' ' . strtoupper($layanan),
	        		'cost'         => $this->_floorDec($cost),
	        		'tax_class_id' => null,
					'text'         => $text
	      		);
			}
		}

		return $data;
	}

	// http://php.net/round
	private function _floorDec($number, $precision = 2) {
	    return round($number, $precision, PHP_ROUND_HALF_DOWN);
	}
}
?>