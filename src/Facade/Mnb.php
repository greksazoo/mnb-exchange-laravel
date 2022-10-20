<?php
	
	namespace Greksazoo\MnbExchangeLaravel\Facade;
	
	use Illuminate\Support\Facades\Facade;
	use Greksazoo\MnbExchangeLaravel\MnbExchangeLaravel;
	
	class Mnb extends Facade
	{
		
		protected static function getFacadeAccessor()
        {
			return MnbExchangeLaravel::class;
		}
	}
