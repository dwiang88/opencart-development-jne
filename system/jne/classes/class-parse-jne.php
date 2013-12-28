<?php
include_once 'spreadsheet/OLERead.php';
include_once 'spreadsheet/reader.php';

class Parse_JNE
{
	private $_columns;
	private $_excel;
	private $_properties = array();

	private $_data;
	private $_provinces;

	private $_indonesia = array(
		'Sumatera' => array(
			'DI. Aceh', 
			'Sumatera Utara', 
			'Bengkulu',
			'Jambi',
			'Riau',
			'Sumatera Barat',
			'Sumatera Selatan',
			'Sumatera Utara',
			'Lampung',
			'Bangka Belitung',
			'Kepulauan Riau'
		),
		'Jawa' => array(
			'Banten',
			'DKI Jakarta',
			'Jawa Barat',
			'Jawa Tengah',
			'Jawa Timur',
			'Yogyakarta'
		),
		'Bali dan Nusa Tenggara' => array(
			'Bali',
			'Nusa Tenggara Barat',
			'Nusa Tenggara Timur'
		),
		'Kalimantan' => array(
			'Kalimantan Barat',
			'Kalimantan Selatan',
			'Kalimantan Tengah',
			'Kalimantan Timur',
			'Kalimantan Utara'
		),
		'Sulawesi' => array(
			'Gorontalo',
			'Sulawesi Barat',
			'Sulawesi Selatan',
			'Sulawesi Tengah',
			'Sulawesi Tenggara',
			'Sulawesi Utara'
		),
		'Maluku dan Papua' => array(
			'Maluku',
			'Papua Barat',
			'Papua'
		)
	);

	/*
	 * urutan provinsi dari barat
	 */
	private static $_provinsi = array(
		'Aceh', 
		'Sumatera Utara', 
		'Bengkulu',
		'Jambi',
		'Riau',
		'Sumatera Barat',
		'Sumatera Selatan',
		'Sumatera Utara',
		'Lampung',
		'Bangka Belitung',
		'Kepulauan Riau',
		'Banten',
		'Jakarta',
		'Jawa Barat',
		'Jawa Tengah',
		'Jawa Timur',
		'Yogyakarta',
		'Bali',
		'Kalimantan Barat',
		'Kalimantan Selatan',
		'Kalimantan Tengah',
		'Kalimantan Timur',
		'Kalimantan Utara',
		'Nusa Tenggara Barat',
		'Nusa Tenggara Timur',
		'Gorontalo',
		'Sulawesi Barat',
		'Sulawesi Selatan',
		'Sulawesi Tengah',
		'Sulawesi Tenggara',
		'Sulawesi Utara',
		'Maluku',
		'Papua Barat',
		'Papua'
	);
	
	public function __construct( $options = array() )
	{		
		$columns = array(
			'code' 		=> 2,
			'provinsi' 	=> 3,
			'kota' 		=> 4,
			'kecamatan' => 5,
			'k_code' 	=> 6,
			'tarif' => array(
				'reg' => array(
					'harga' => 7,
					'etd' 	=> 8
				),
				'oke' => array(
					'harga' => 9,
					'etd' 	=> 10
				),
				'yes' => array(
					'harga' => 11
				)
			)
		);

		foreach ($columns as $k => $v){
			$options[$k] = array_key_exists($k, $options) ? $options[$k] : $v;
		}

		$this->_columns = $columns;
	}

	/**
	 * [__set description]
	 * @param [type] $key
	 * @param [type] $name
	 */
	public function __set( $key, $name )
	{
		$this->_properties[$key] = $name;
	}

	public function __get( $key )
	{
		return $this->_properties[$key];
	}

	public function getProperties()
	{
		return $this->_properties;
	}

	public function setFilename( $filename )
	{
		if( !isset($this->_properties['filename']) )
			$this->_properties['filename'] = $filename;
	}

	public function getFilename()
	{
		return $this->_properties['filename'];
	}

	public function getExcel()
	{
		return $this->_excel;
	}
	
	/*
	 * get cache
	 * data cache diambil dari callback dgn method sesuai parameternya	 
	 * hasil array callback dikonversi ke string dengan serialize
	 * kemudian simpan kedalam file cache .txt, sesuai nama parameternya 
	 *
	 * @param String
	 * @return Array
	 */
	private function _getCache( $action )
	{
		$cache_file = sprintf( DIR_SYSTEM . 'jne/data/caches/%s_%s.cache', $action, $this->getFilename() );
		if( file_exists($cache_file) ){	
			$data = unserialize(file_get_contents($cache_file));
		}
		else {			
			$data = call_user_func( array($this, '_get'.ucwords($action)) );
			file_put_contents($cache_file, serialize($data));
		}
		
		return $data;
	}
	
	/*
	 * get cache populate
	 */
	public function populate()
	{
		$this->_data = $this->_getCache( 'populate' );
	}

	private function _getpopulate()
	{
		if( !isset($this->_properties['filename']) )
			throw new Exception('File xls not found');

		$this->_excel = new Spreadsheet_Excel_Reader();		
		$this->_excel->read( DIR_SYSTEM . 'jne/data' . DIRECTORY_SEPARATOR . $this->_properties['filename'] . '.xls' ); 

		$start = $this->start;
		$end = $this->_excel->sheets[0]['numRows'];

		$cells = $this->_excel->sheets[0]['cells'];			
		
		$data = array();
		while( $start <= $end )
		{
			// ambil data kolom
			$cols = $cells[$start];

			$data[$start] = array(
				'code' 		=>  $cols[$this->_columns['code']],
				'provinsi'  =>  $cols[$this->_columns['provinsi']],
				'kota' 		=>  $cols[$this->_columns['kota']],
				'kecamatan' =>  $cols[$this->_columns['kecamatan']],
				'k_code' 	=>  $cols[$this->_columns['k_code']],
				'tarif' => array(
					'reg' => array(
						'harga' => $cols[$this->_columns['tarif']['reg']['harga']],
						'etd' 	=> $cols[$this->_columns['tarif']['reg']['etd']],
					)
				)
			);	

			if( is_numeric($cols[$this->_columns['tarif']['oke']['harga']]) ) {
				$data[$start]['tarif']['oke'] = array(
					'harga' => $cols[$this->_columns['tarif']['oke']['harga']],
					'etd' 	=> $cols[$this->_columns['tarif']['oke']['etd']]
				);
			}	

			if( is_numeric($cols[$this->_columns['tarif']['yes']['harga']]) ) {
				$data[$start]['tarif']['yes'] = array(
					'harga' => $cols[$this->_columns['tarif']['yes']['harga']]
				);
			}

			// increase				
			$start++;
		}

		return $data;
	}

	public function getData( $isSorted = false)
	{
		return $isSorted ? $this->_sortAll() : $this->_data ;
	}

/*
	public function getProvinces()
	{
		$self = $this;

		$this->_provinces = array_unique(
			array_map(function($k) use($self) {
				return $self->_normalize(array_pop(
					array_values(
						array_intersect_key($k, array_flip(array('provinsi')))
					)
				));
			}, $this->_data)
		);

		return $this->_provinces;
	}
*/

	/**
	 * Filter berdasarkan nama provinsi
	 */ 
	public function getCities( $index )
	{
		$provinsi = $this->_data[$index]['provinsi'];
		$kota = array_filter($this->_data, function($data) use($provinsi) {
			return preg_match('/\b'. $provinsi .'\b/', $data['provinsi']);
		});	
		
		$_kota = array();
		foreach( $kota as $k => $v )
		{
			if( !in_array_r($v['kota'], $_kota) )
			{
				$_kota[$k] = array(
					'name' 	=> $v['kota'],
					'kecamatan' => array(
						$k => array(
							'kode' 	=> $v['code'],
							'nama'  => $v['kecamatan']
						) 
					)
				);
				$index = $k;
			}	
			else {
				$_kota[$index]['kecamatan'][$k] = array(
					'kode' 	=> $v['code'],
					'nama'  => $v['kecamatan']
				);
			}
		}

		return $_kota;
	}

	public function getTree()
	{
		$rows = $this->_sortAll();
		
		$tree = array();
		foreach ($rows as $key => $value) {
			$code     = $value['code'];
			$provinsi = $value['provinsi'];
			$kota = $value['kota'];
			$kecamatan = $value['kecamatan'];

			$tree[$provinsi]['index'] = $key;
			$tree[$provinsi]['code'] = $code;
			$tree[$provinsi]['kota'][$kota][$kecamatan] = array(
				'index' => $key,
				'tarif' => $this->_getSimpleTax($value['tarif'])
			);
		}

		return $tree;
	}

	public function unity()
	{
		$rows = $this->getTree();
		
		$unity = array();
		foreach ($this->_indonesia as $provinsi => $kota) {
			foreach ($kota as $k) {
				$unity[$provinsi][$k] = $rows[$k];
			}
		}

		return $unity;
	}

	private function _getSimpleTax( $tax )
	{
		$newTax = array();
		foreach ($tax as $key => $value) {
			$newTax[$key] = $value['harga'];
		}
		return $newTax;
	}

	public function getTax( $index )
	{
		return $this->_data[$index]['tarif'];
	}

	public function _normalize( $data )
	{
		if(is_string($data))
			return ucwords( strtolower( $data ) );
			
		$data['text'] = ucwords( strtolower( $data['text'] ) );
		return $data;
	}

	/*
	 * urut data berdasar urutan provinsi
	 * preg_match boundary 'provinsi' insensitive
	 * 
	 * @return
	 * 	values array
	 */
	private function _sortAll() 
	{
		if( !$this->_data ) return array();

	    $ordered = array();
	    foreach(self::$_provinsi as $key) {
	    	foreach($this->_data as $index => $val ){
		    	if(preg_match('/\b'.$key.'\b/i', $val['provinsi'])) {
		    		$ordered[$index] = $this->_data[$index];
		    	}    		
	    	}
	    }
	    return $ordered;
	}

	/*
	 * urut provinsi berdasar urutan provinsi
	 * preg_match boundary 'provinsi' insensitive
	 * 
	 * @return
	 * 	values array(key, value)
	 */
	public static function OrderProvinsi( $array ) 
	{
	    $ordered = array();
	    foreach(self::$_provinsi as $key => $provinsi) {
	    	foreach($array as $index => $val ){
		    	if(preg_match('/\b'.$provinsi.'\b/i', $val['name'])) {
		    		$ordered[$index] = array(
		    			'index'   => $key,
		    			'zone_id' => $index,
		    			'name'    => $array[$index]['name']
		    		);
		    	}    
	    	}
	    }
	    return $ordered;
	}

	public function getCitiesByProvinceOnGroup( $provinsi ) 
	{
		$cities = array();

		$rows = array_filter($this->_data, function($_d) use($provinsi){
			return preg_match('/'. $provinsi .'/', $_d['provinsi']);
		});

		foreach( $rows as $index => $row ){
			$cities[$row['kota']][$index] = $row['kecamatan'];
		}	

	    return $cities;
	}
}
