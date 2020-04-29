<?php

class EMDR /* extends Redis */ {

    public  $regionID;
    private $version;
    
	public function __construct($regionID, $emdrVersion = 1)
    {
        // parent::__construct();
		
		$this->regionID = $regionID;
		$this->version = $emdrVersion;

		// parent::connect('localhost', 6379) or die ("Could not connect to Redis server");
    }
	
	public function get($typeID) {
		$string = 'emdr-'.$this->version.'-'.$this->regionID.'-'.$typeID;

		// return parent::get($string);
		// EMDR is dead, return dummy data for now to prevent breaking of things
		return '{"orders": {"sell": ['.rand(1000, 100000).']}}';
	}
}

?>