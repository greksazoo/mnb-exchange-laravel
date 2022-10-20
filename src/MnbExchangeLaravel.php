<?php
	
	namespace Greksazoo\MnbExchangeLaravel;
	
	use Illuminate\Cache\Repository;
	use Illuminate\Support\Carbon;
	
	class MnbExchangeLaravel
	{
		/**
		 * @var Client
		 */
		protected $client;
		/**
		 * @var Repository
		 */
		protected $cache;
		/**
		 * @var int|mixed
		 */
		protected $timeout;
		
		/**
		 * @param Client     $client
		 * @param Repository $cache
		 * @param int|mixed $timeout
		 */
		public function __construct (Client $client, Repository $cache, $timeout=1440)
		{
			$this->client = $client;
			$this->cache = $cache;
			$this->timeout = $timeout;
		}
		
		/**
		 * @param mixed $date
		 *
		 * @return void
		 */
		protected function normalizeDate (&$date): void
        {
			$date = Carbon::createFromFormat('Y-m-d', $date);
		}
		
		/**
		 * @return array|mixed
		 */
		public function currencies ()
		{
			return $this->cache->remember('mnb.currencies', $this->timeout, function ()
			{
				return $this->client->currencies();
			});
		}
		
		/**
		 * @param string $code
		 *
		 * @return bool
		 */
		public function hasCurrency ($code): bool
        {
			return in_array($code, $this->currencies());
		}
		
		/**
		 * @param string $code
		 * @param mixed $date
		 *
		 * @return array|mixed
		 */
		public function exchangeRate ($code, &$date = null)
		{
			return $this->cache->remember("mnb.currencies.rate.$code", $this->timeout, function () use (&$code, &$date)
			{
				return $this->currentExchangeRate($code, $date);
			});
		}
		
		/**
		 * @param mixed $date
		 *
		 * @return array|mixed
		 */
		public function exchangeRates (&$date = null)
		{
			return $this->cache->remember("mnb.currencies.rate", $this->timeout, function () use (&$date)
			{
				return $this->currentExchangeRates($date);
			});
		}
		
		/**
		 * @param  string  $code
		 * @param mixed $date
		 *
		 * @return Model\Currency|null
		 */
		public function currentExchangeRate (string $code, &$date = null): ?Model\Currency
        {
			$result = $this->client->currentExchangeRate($code, $date);
			$this->normalizeDate($date);
			
			return $result;
		}
		
		/**
		 * @param mixed $date
		 *
		 * @return array|Model\Currency[]
		 */
		public function currentExchangeRates (&$date = null): array
        {
			$result = $this->client->currentExchangeRates($date);
			$this->normalizeDate($date);
			
			return $result;
		}
        
        /**
         * @param  string  $code
         * @param mixed $date
         *
         * @return Model\Currency
         */
        public function getExchangeRateByDate (string $code,&$date): Model\Currency
        {
            $result = $this->client->getExchangeRateByDate($code,$date);
            $this->normalizeDate($date);
            
            return $result;
        }
	}
