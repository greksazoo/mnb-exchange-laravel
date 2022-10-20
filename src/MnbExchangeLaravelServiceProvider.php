<?php
	
	namespace Greksazoo\MnbExchangeLaravel;
	
	use Illuminate\Support\ServiceProvider;
	
	class MnbExchangeLaravelServiceProvider extends ServiceProvider
	{
		/**
		 * Bootstrap the application services.
		 */
		public function boot (): void
		{
			if ($this->app->runningInConsole())
			{
				$this->publishes([
					                 __DIR__ . '/../config/config.php' => config_path('mnb-exchange-laravel.php'),
				                 ], 'config');
			}
		}
		
		/**
		 * Register the application services.
		 */
		public function register ()
		{
            $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'mnb-exchange-laravel');
			$this->app->singleton(MnbExchangeLaravel::class, function ($app)
			{
				$config = $app['config']['mnb-exchange-laravel'];
				$cache = $app['cache']->store($config['cache']['store']);
				$mnbClient = new Client($config['wsdl']);
				
				return new MnbExchangeLaravel($mnbClient, $cache, $config['cache']['timeout']);
			});
		}
	}
