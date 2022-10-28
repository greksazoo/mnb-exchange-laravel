<?php
    
    namespace Greksazoo\MnbExchangeLaravel;
    
    use Greksazoo\MnbExchangeLaravel\Model\Currency;
    use SoapClient;
    
    class Client
    {
        /**
         * @var SoapClient
         */
        protected SoapClient $client;
        
        /**
         * Client
         * constructor.
         *
         * @param  string  $wsdl
         *
         * @throws \SoapFault
         */
        public function __construct(string $wsdl = 'http://www.mnb.hu/arfolyamok.asmx?wsdl')
        {
            $this->client = new SoapClient($wsdl);
        }
        
        /**
         * @param  SoapClient  $client
         */
        public function setClient(SoapClient $client): void
        {
            $this->client = $client;
        }
        
        /**
         * @return string[]
         */
        public function currencies(): array
        {
            return (array)simplexml_load_string($this->client->GetCurrencies(null)->GetCurrenciesResult)->Currencies->Curr;
        }
        
        /**
         * @param  string  $currency
         *
         * @return boolean
         */
        public function hasCurrency(string $currency): bool
        {
            return in_array($currency, $this->currencies());
        }
        
        /**
         * @param  Currency|string  $code
         * @param  null  $date
         *
         * @return Currency|null
         */
        public function currentExchangeRate($code, &$date = null): ?Currency
        {
            $code = strtoupper(trim($code));
            foreach ($this->currentExchangeRates($date) as $currency) {
                if ($currency->getCode() == $code) {
                    return $currency;
                }
            }
            
            return null;
        }
        
        /**
         * @param  null  $date
         *
         * @return array|Currency[]
         */
        public function currentExchangeRates(&$date = null): array
        {
            $xml        = simplexml_load_string($this->client->GetCurrentExchangeRates(null)->GetCurrentExchangeRatesResult);
            $date       = (string)$xml->Day->attributes()->date;
            $currencies = [];
            foreach ($xml->Day->Rate as $rate) {
                $currencies[] = new Currency((string)$rate->attributes()->curr, (int)$rate->attributes()->unit, (float)str_replace(',', '.', $rate));
            }
            
            return $currencies;
        }
        
        /**
         * @param  Currency|string  $code
         * @param  null  $date
         *
         * @return Currency|null
         */
        public function getExchangeRateByDate($code, $date): ?Currency
        {
            $param = array(
                'startDate'     => $date,
                'endDate'       => $date,
                'currencyNames' => $code,
            );
            $xml   = simplexml_load_string($this->client->GetExchangeRates($param)->GetExchangeRatesResult);
            if($xml->Day->Rate) {
                return new Currency((string)$xml->Day->Rate->attributes()->curr, (int)$xml->Day->Rate->attributes()->unit, (float)str_replace(',', '.', $xml->Day->Rate));
            }
            return null;
        }
        
        /**
         * @param  string  $name
         * @param  mixed  $arguments
         *
         * @return mixed
         */
        public function __call(string $name, $arguments)
        {
            return $this->client->{$name}(...$arguments);
        }
    }
