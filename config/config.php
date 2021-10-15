<?php
	return [
		'wsdl' => env('MNB_SOAP_WSDL', 'http://www.mnb.hu/arfolyamok.asmx?wsdl'),
		'cache' => [
			'store'   => env('MNB_CACHE_DRIVER', 'file'),
			'timeout' => env('MNB_CACHE_MINUTES', 1440),
		],
	];
