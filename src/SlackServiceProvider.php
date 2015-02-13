<?php namespace Maknz\Slack;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as Guzzle;
use RuntimeException;

class SlackServiceProvider extends ServiceProvider {

  /**
   * Indicates if loading of the provider is deferred.
   *
   * @var bool
   */
  protected $defer = false;

  /**
   * The actual provider
   *
   * @var \Illuminate\Support\ServiceProvider
   */
  protected $provider;

  /**
   * Instantiate the service provider
   *
   * @param mixed $app
   * @return void
   */
  public function __construct($app)
  {
    parent::__construct($app);

    $this->provider = $this->getProvider();
  }

  /**
   * Bootstrap the application events.
   *
   * @return void
   */
  public function boot()
  {
    return $this->provider->boot();
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    return $this->provider->register();
  }

  /**
   * Return the service provider for the particular Laravel version
   *
   * @return mixed
   */
  private function getProvider()
  {
    $app = $this->app;

    $version = intval($app::VERSION);

    switch ($version)
    {
      case 4:
        return new SlackServiceProviderLaravel4($app);

      case 5:
        return new SlackServiceProviderLaravel5($app);

      default:
        throw new RuntimeException('Your version of Laravel is not supported');
    }
  }

  /**
  * Get the services provided by the provider.
  *
  * @return array
  */
  public function provides()
  {
    return ['maknz.slack'];
  }

}
