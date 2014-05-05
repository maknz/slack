<?php namespace Maknz\Slack;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as Guzzle;

class SlackServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('maknz/slack');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['slack'] = $this->app->share(function($app) {

			return new SlackClient(
				new Guzzle,
				$app['config']->get('slack::account'),
				$app['config']->get('slack::token'),
				$app['config']->get('slack::default_channel'),
				$app['config']->get('slack::default_username')
			);
			
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('slack');
	}

}
