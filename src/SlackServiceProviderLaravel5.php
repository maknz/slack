<?php namespace Maknz\Slack;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Encryption\Encrypter as Encrypter;
use Illuminate\Queue\Capsule\Manager as QueueManager;
use Queue;

class SlackServiceProviderLaravel5 extends ServiceProvider {

  /**
   * Bootstrap the application events.
   *
   * @return void
   */
  public function boot()
  {
    $this->publishes([__DIR__ . '/config/config.php' => config_path('slack.php')]);
  }

  protected function getEncrypter()
  {
    return $this->app['encrypter'];
  }

  protected function getQueue()
  {
    $name = Queue::getFacadeRoot()->getDefaultDriver();

    $config = $this->app['config']["queue.connections.{$name}"];

    $queue = new QueueManager($this->app);

    $queue->addConnection($config);

    /*$queue->getContainer()->bind('encrypter', function(){
      $key = '4dkTd5lWhN40CkSrnyrRBuRMsSX9exXD';
      $encrypter = $this->getEncrypter();
      return $encrypter($key);
    });*/
    //$queue->getContainer()->bind('encrypter', function(){
    //        return $this->getEncrypter();
    //});

    $queue->getContainer()->bind('Illuminate\Contracts\Encryption\Encrypter', 'encrypter');

    return $queue;
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'slack');

    $this->app['slack'] = $this->app->share(function($app)
    {
      return new Client(
        $app['config']->get('slack.endpoint'),
        [
          'channel' => $app['config']->get('slack.channel'),
          'username' => $app['config']->get('slack.username'),
          'icon' => $app['config']->get('slack.icon'),
          'link_names' => $app['config']->get('slack.link_names'),
          'unfurl_links' => $app['config']->get('slack.unfurl_links'),
          'unfurl_media' => $app['config']->get('slack.unfurl_media'),
          'allow_markdown' => $app['config']->get('slack.allow_markdown'),
          'markdown_in_attachments' => $app['config']->get('slack.markdown_in_attachments'),
          'is_slack_enabled' => $app['config']->get('slack.is_slack_enabled'),
        ],
        $this->getQueue(),
        new Guzzle
      );
    });

    $this->app->bind('Maknz\Slack\Client', 'slack');
  }

}
