<?php
	
	namespace Greksazoo\MnbExchangeLaravel;
	
	use Illuminate\Support\ServiceProvider;
	
	class MnbExchangeLaravelServiceProvider extends ServiceProvider
	{
		/**
		 * Bootstrap
		 * the
		 * application
		 * services.
		 */
		public function boot (): void
		{
			/*
			 * Optional methods to load your package assets
			 */ // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'mnb-exchange-laravel');
			// $this->loadViewsFrom(__DIR__.'/../resources/views', 'mnb-exchange-laravel');
			// $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
			// $this->loadRoutesFrom(__DIR__.'/routes.php');
			if ($this->app->runningInConsole())
			{
				$this->publishes([
					                 __DIR__ . '/../config/config.php' => config_path('mnb-exchange-laravel.php'),
				                 ], 'config');
				// Publishing the views.
				/*$this->publishes([
					__DIR__.'/../resources/views' => resource_path('views/vendor/mnb-exchange-laravel'),
				], 'views');*/ // Publishing assets.
				/*$this->publishes([
					__DIR__.'/../resources/assets' => public_path('vendor/mnb-exchange-laravel'),
				], 'assets');*/ // Publishing the translation files.
				/*$this->publishes([
					__DIR__.'/../resources/lang' => resource_path('lang/vendor/mnb-exchange-laravel'),
				], 'lang');*/ // Registering package commands.
				// $this->commands([]);
			}
		}
		
		/**
		 * Register
		 * the
		 * application
		 * services.
		 */
		public function register ()
		{
			// Automatically apply the package configuration
			$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'mnb-exchange-laravel');
			// Register the main class to use with the facade
			$this->app->singleton(MnbExchangeLaravel::class, function ($app)
			{
				$config = $app['config']['mnb-exchange-laravel'];
				$cache = $app['cache']->store($config['cache']['store']);
				$mnbClient = new Client($config['wsdl']);
				
				return new MnbExchangeLaravel($mnbClient, $cache, $config['cache']['timeout']);
			});
			//	    $this->app->alias(MnbExchangeLaravel::class, 'mnb.mnb-exchange-laravel');
		}
	}
