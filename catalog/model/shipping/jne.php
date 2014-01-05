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
		$weights = (array_key_exists('cart_weights', $address)) ? $address['cart_weights'] : 1 ;

		$JNE  = $this->populateJNE();
	
		if ($status) {
			$quote_data = array();

			if( $taxes = $JNE->getTax( $this->session->data['shipping_city_id'] ) )
			{
				foreach( $taxes as $layanan => $tarif )
				{				
					$cost = ( $default_currency == 'IDR' ) ? $this->currency->convert($tarif['harga'], 'IDR', 'USD') : $this->currency->convert($tarif['harga'], 'IDR', $default_currency);
					$cost_format = $this->currency->format($cost, 'IDR');
					$calc_cost = $weights * $cost ;

					$text = $this->currency->format($calc_cost, 'IDR');
					if( $default_currency != 'IDR' ){
						$text .= '( ' .  $this->currency->format($calc_cost, $default_currency) . ')';
					}
					
					$quote_data[$layanan] = array(
		        		'code'         => 'jne.' . $layanan,
		        		'title'        => $this->language->get('text_description') . ' ' . strtoupper($layanan),
		        		'cost'         => $this->_floorDec($calc_cost),
		        		'tax_class_id' => null,
						'text'         => $text,
						'description'  => "($weights kg x $cost_format)"
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

	function getAttributes( $product, $tax ){

		$weight 	= $this->jneConvertion( 'weight', 'convert', $product );
        $dimension  = $this->jneConvertion( 'dimension', 'convert', $product );

        $weight_jne_original   = $this->jneConvertion( 'tolerance', null, $product['weight'] );
        $weight_jne_volumetrik = $dimension ? ((($dimension['length'] * $dimension['width'] * $dimension['height']) / 6000) * $product['weight'] ): 0;

        $attributes = array(
        	'key'           => $product['key'],
            'name'          => $product['name'],
            'weight' 		=> array(
            	'original'   => $this->jneConvertion( 'weight', 'format', $product ),
            	'convertion' => $weight . 'kg',
            	'floor'  	 => $this->_floorDec($weight) . 'kg',
            	'tolerance'  => $weight_jne_original
            ),
            'dimension' 	=> array(
            	'format'  => $this->jneConvertion( 'dimension', 'format', $product ),
            	'convert' => $dimension
            ),
            'jne'			=> array(
	            'weight'	=> array(
	            	'original'   => $weight_jne_original,
	            	'volumetrik' => $weight_jne_volumetrik
	            ),
	            'calculation' => array(
	            	'original'	 => $weight_jne_original * $tax,
	            	'volumetrik' => $weight_jne_volumetrik * $tax
	            )
            )
    	);

    	return $attributes;
	}

	function jneConvertion( $type, $output, $product ){
		$_default_class_id = array(
			'weight' 	=> '1', 	// kg
			'length' 	=> '1', 	// cm
			'currency' 	=> 'IDR' 	// rp
		);

		if( $type == 'weight' ){

			if( $product['weight'] == 0 ) return 1.00;

			$class_id   = $_default_class_id['weight'];
			$p_class_id = $product['weight_class_id'];

			if( $output == 'convert' ){
				return $p_class_id != $class_id ? 
						$this->weight->convert($product['weight'], $p_class_id, $class_id) :
						$product['weight'] ;
			}
			else if( $output == 'format' ){
				return $p_class_id != $class_id ? 
						$this->weight->format($product['weight'], $p_class_id, $class_id) :
						$this->weight->format($product['weight'], $class_id) ;
			}

		} elseif( $type == 'dimension' ) {

			if($product['length'] == 0 || $product['width'] == 0 || $product['height'] == 0) return array();

			$class_id   = $_default_class_id['length'];
			$p_class_id = $product['length_class_id'];

			if( $output == 'convert' ){
				return array(
					'length' => $p_class_id != $class_id ? 
									$this->length->convert($product['length'], $p_class_id, $class_id) :
									$product['length'], 
					'width' => $p_class_id != $class_id ? 
									$this->length->convert($product['width'], $p_class_id, $class_id) :
									$product['width'],
					'height' => $p_class_id != $class_id ? 
									$this->length->convert($product['height'], $p_class_id, $class_id) :
									$product['height'] 

				);
			}
			else if( $output == 'format' ){
				return array(
					'length' => $p_class_id != $class_id ? 
									$this->length->format($product['length'], $p_class_id, $class_id) :
									$this->length->format($product['length'], $class_id), 
					'width' => $p_class_id != $class_id ? 
									$this->length->format($product['width'], $p_class_id, $class_id) :
									$this->length->format($product['width'], $class_id), 
					'height' => $p_class_id != $class_id ? 
									$this->length->format($product['height'], $p_class_id, $class_id) :
									$this->length->format($product['height'], $class_id), 

				);
			}

		} elseif( $type == 'tolerance' ) {

        	$tolerance  = $this->config->get('jne_tolerance');
        	// $tolerance  = 0.3;

			$weight = $product;
			if( $weight > 1 ){
				$_weights   = $this->_floorDec($weight);
				$intval     = intval($weight);
				$diff       = $weight - $intval;
				$weight_jne = $diff > $tolerance ? ceil($weight) : $intval;
            } else {
            	$weight_jne = 1;
            }

            return $weight_jne;
		}

		return null;
	}

	// http://php.net/round
	private function _floorDec($number, $precision = 2) {
	    return round($number, $precision, PHP_ROUND_HALF_DOWN);
	}
}
?>