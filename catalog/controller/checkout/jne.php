<?php 
class ControllerCheckoutJne extends Controller {

	public function index(){
		return $this->forward('checkout/jne/debug');
	}
	
	public function tax(){

		$data = array();
		
		$this->load->model('localisation/zone');
		$this->load->model('shipping/jne');

		$zone = $this->model_localisation_zone->getZonesByCountryId(100);
		$JNE  = $this->model_shipping_jne->populateJNE();
		
		$act = isset($this->request->get['act']) ? $this->request->get['act'] : null ;
		switch ($act) {
			case 'city':
				$provinsi = $this->request->get['province'];

				$data = $JNE->getCitiesByProvinceOnGroup( $provinsi );
				$json = array(
					'postcode_required' => '0',
					'data' => $data
				);
				break;
			
			default:
				$zone = $JNE::OrderProvinsi( $zone );

				if( $jne_zone_allowed = $this->config->get('jne_zone_allowed') ){
					$jne_zone_allowed = unserialize($this->config->get('jne_zone_allowed'));
					$zone = array_intersect_key($zone, array_flip($jne_zone_allowed));
				}

				sort($zone);

				$json = array(
					'postcode_required' => '0',
					'zone' => $zone
				);
				break;
		}
		
		$this->response->setOutput(json_encode($json));
	}

	public function debug() {
		// $default_currency = $this->config->get("config_currency");
		$default_currency = $this->session->data['currency'];
		$convert_from_IDR = $this->currency->convert(27000, "IDR", 'USD');
		$this->response->setOutput(json_encode(array(
    		'currency_code' => $this->session->data['currency'],
    		'default'  => $default_currency,
    		'currency' => $this->currency->has("IDR"),
    		'convert_from_IDR' => $convert_from_IDR,
    		'format'   => $this->currency->format($convert_from_IDR, "IDR"),
			'length' => $this->length->getUnit(2),
			'length_format' => $this->length->format(3, 1),
			'length_convert' => $this->length->convert(3, 1, 2)
		)));

		// $this->load->model('localisation/zone');
		// $this->load->model('shipping/jne');

		// $zone = $this->model_localisation_zone->getZonesByCountryId(100);

		// $c = array_filter($zone, function($z){
		// 	return (int) $z['status'] && $z['zone_id'] == 1515;
		// });

		// $c = array_pop($c);
		// $provinsi = $c['name'];
	}
}
?>