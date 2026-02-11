<?php

$query = urlencode(trim(clearSearch($_GET["query"])));

$results = [];

if($query){

	$curl=curl_init('https://nominatim.openstreetmap.org/search?q='.$query.'&format=json&polygon=1&addressdetails=1&accept-language=ru');

	curl_setopt_array($curl,array(
	        CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0',
	        CURLOPT_ENCODING=>'gzip, deflate',
	        CURLOPT_RETURNTRANSFER=>1,
	        CURLOPT_HTTPHEADER=>array(
	                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
	                'Accept-Language: en-US,en;q=0.5',
	                'Accept-Encoding: gzip, deflate',
	                'Connection: keep-alive',
	                'Upgrade-Insecure-Requests: 1',
	        ),
	));

	$results_decode = json_decode(curl_exec($curl), true);

	if($results_decode){

		foreach ($results_decode as $value) { 
			if(isset($value["address"])){	
				if(isset($value["address"]["country_code"])) unset($value["address"]["country_code"]);
				if(isset($value["address"]["country"])) unset($value["address"]["country"]);
				if(isset($value["address"]["postcode"])) unset($value["address"]["postcode"]);
				if(isset($value["address"]["region"]))  unset($value["address"]["region"]);
				if(isset($value["address"]["state"])) unset($value["address"]["state"]);
				if(isset($value["address"]["city"])) unset($value["address"]["city"]);
				if(isset($value["address"]["region"])) unset($value["address"]["region"]);
				if(isset($value["address"]["ISO3166-2-lvl3"])) unset($value["address"]["ISO3166-2-lvl3"]);
				if(isset($value["address"]["ISO3166-2-lvl4"])) unset($value["address"]["ISO3166-2-lvl4"]);
				if($value['address'] && $value['lat'] && $value['lon']) $results[] = ['address'=>implode(', ',$value["address"]), 'lat'=>$value['lat'], 'lon'=>$value['lon']];											
			}
		}
		
	}

}

echo json_encode($results);

?>