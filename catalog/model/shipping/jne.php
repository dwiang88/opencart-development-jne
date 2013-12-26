<?php
include DIR_SYSTEM . 'jne/classes/jne-shipping-rate-functions.php';
include DIR_SYSTEM . 'jne/classes/class-parse-jne.php';

class ModelShippingJne extends Model {
	private $jne;

	function getQuote($address) {
		$this->language->load('shipping/jne');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('jne_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (!$this->config->get('jne_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		// $default_currency = $this->config->get("config_currency");
		$default_currency = $this->session->data['currency'];

		$JNE  = $this->model_shipping_jne->populateJNE();
	
		if ($status) {
			$quote_data = array();

			if( $taxes = $JNE->getTax( $this->session->data['shipping_city_id'] ) )
			{
				foreach( $taxes as $layanan => $tarif )
				{				
					$cost = ( $default_currency == 'IDR' ) ? $this->currency->convert($tarif['harga'], 'IDR', 'USD') : $this->currency->convert($tarif['harga'], 'IDR', $default_currency);

					$quote_data[$layanan] = array(
		        		'code'         => 'jne.' . $layanan,
		        		'title'        => $this->language->get('text_description') . ' ' . strtoupper($layanan),
		        		'cost'         => $cost,
		        		'tax_class_id' => null,
						'text'         => $this->currency->format($cost, 'IDR')
		      		);
				}
			}

      		$method_data = array(
        		'code'       => 'jne',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('jne_sort_order'),
        		'error'      => false
      		);
		}
	
		return $method_data;
	}

	function populateJNE(){
		$this->jne = new Parse_JNE();
		$this->jne->filename = '04-2013';
		$this->jne->start = 5;
		$this->jne->populate();
		return $this->jne;
	}
}
?>