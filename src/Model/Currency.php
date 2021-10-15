<?php
	
	namespace Greksazoo\MnbExchangeLaravel\Model;
	
	
	class Currency {
		
		/**
		 * @var string
		 */
		public $code;
		
		/**
		 * @var int
		 */
		public $unit;
		
		/**
		 * @var float
		 */
		public $amount;
		
		/**
		 * Currency constructor.
		 * @param string $code
		 * @param int $unit
		 * @param float $amount
		 */
		public function __construct($code, $unit, $amount)
		{
			$this->code = (string)$code;
			$this->unit = (int)$unit;
			$this->amount = (float)$amount;
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
