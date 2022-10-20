<?php
	
	namespace Greksazoo\MnbExchangeLaravel\Model;
	
	
	class Currency {
		
		/**
		 * @var string
		 */
		public string $code;
		
		/**
		 * @var int
		 */
		public int $unit;
		
		/**
		 * @var float
		 */
		public float $amount;
		
		/**
		 * Currency constructor.
		 *
		 * @param  string  $code
		 * @param  int  $unit
		 * @param  float $amount
		 */
		public function __construct(string $code, int $unit, float $amount)
		{
			$this->code = $code;
			$this->unit = $unit;
			$this->amount = $amount;
		}
		
		/**
		 * @return string
		 */
		public function getCode(): string
		{
			return $this->code;
		}
		
		/**
		 * @return int
		 */
		public function getUnit(): int
		{
			return $this->unit;
		}
		
		/**
		 * @return float
		 */
		public function getAmount(): float
		{
			return $this->amount;
		}
		
	}
