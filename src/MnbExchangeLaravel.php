<?php
	
	namespace Greksazoo\MnbExchangeLaravel;
	
	use Illuminate\Cache\Repository;
	use Illuminate\Support\Carbon;
	
	class MnbExchangeLaravel
	{
		protected $client;
		protected $cache;
		protected $timeout;
		
		public function __construct (Client $client, Repository $cache, $timeout=1440)
		{
			$this->client = $client;
			$this->cache = $cache;
			$this->timeout = $timeout;
		}
		
		protected function normalizeDate (&$date)
		{
			$date = Carbon::createFromFormat('Y-m-d', $date);
		}
		
		public function currencies ()
		{
			return $this->cache->remember('mnb.currencies', $this->timeout, function ()
			{
				return $this->client->currencies();
			});
		}
		
		public function hasCurrency ($code)
		{
			return in_array($code, $this->currencies());
		}
		
		public function exchangeRate ($code, &$date = null)
		{
			return $this->cache->remember("mnb.currencies.rate.$code", $this->timeout, function () use (&$code, &$date)
			{
				return $this->currentExchangeRate($code, $date);
			});
		}
		
		public function exchangeRates (&$date = null)
		{
			return $this->cache->remember("mnb.currencies.rate", $this->timeout, function () use (&$date)
			{
				return $this->currentExchangeRates($date);
			});
		}
		
		public function currentExchangeRate ($code, &$date = null)
		{
			$result = $this->client->currentExchangeRate($code, $date);
			$this->normalizeDate($date);
			
			return $result;
		}
		
		public function currentExchangeRates (&$date = null)
		{
			$result = $this->client->currentExchangeRates($date);
			$this->normalizeDate($date);
			
			return $result;
		}
	}
