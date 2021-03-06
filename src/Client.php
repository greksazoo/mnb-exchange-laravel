<?php
	
	namespace Greksazoo\MnbExchangeLaravel;
	
	use Greksazoo\MnbExchangeLaravel\Model\Currency;
	use SoapClient;
	
	class Client
	{
		/**
		 * @var SoapClient
		 */
		protected $client;
		
		/**
		 * Client
		 * constructor.
		 *
		 * @param string $wsdl
		 *
		 * @throws \SoapFault
		 */
		public function __construct ($wsdl = 'http://www.mnb.hu/arfolyamok.asmx?wsdl')
		{
			$this->client = new SoapClient($wsdl);
		}
		
		/**
		 * @param SoapClient $client
		 */
		public function setClient (SoapClient $client): void
		{
			$this->client = $client;
		}
		
		/**
		 * @return string[]
		 */
		public function currencies ()
		{
			return (array)simplexml_load_string($this->client->GetCurrencies(null)->GetCurrenciesResult)->Currencies->Curr;
		}
		
		/**
		 * @param string $currency
		 *
		 * @return boolean
		 */
		public function hasCurrency ($currency)
		{
			return in_array($currency, $this->currencies());
		}
		
		/**
		 * @param Currency|string $code
		 * @param null            $date
		 *
		 * @return Currency|null
		 */
		public function currentExchangeRate ($code, &$date = null)
		{
			$code = trim(strtoupper($code));
			foreach ($this->currentExchangeRates($date) as $currency)
			{
				
				if ($currency->getCode() == $code)
				{
					return $currency;
				}
			}
			
			return null;
		}
		
		/**
		 * @param null $date
		 *
		 * @return array|Currency[]
		 */
		public function currentExchangeRates (&$date = null)
		{
			$xml = simplexml_load_string($this->client->GetCurrentExchangeRates(null)->GetCurrentExchangeRatesResult);
			$date = (string)$xml->Day->attributes()->date;
			$currencies = [];
			foreach ($xml->Day->Rate as $rate)
			{
				
				$currencies[] = new Currency((string)$rate->attributes()->curr, (int)$rate->attributes()->unit, (float)str_replace(',', '.', $rate));
			}
			
			return $currencies;
		}
		
		/**
		 * @param string $name
		 * @param mixed  $arguments
		 *
		 * @return mixed
		 */
		public function __call ($name, $arguments)
		{
			return $this->client->{$name}(...$arguments);
		}
	}
