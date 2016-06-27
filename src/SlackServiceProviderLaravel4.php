<?php namespace Maknz\Slack;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Queue\Capsule\Manager as QueueManager;
use Illuminate\Encryption\Encrypter as Encrypter;
use Queue;

class SlackServiceProviderLaravel4 extends ServiceProvider {

  /**
   * Bootstrap the application events.
   *
   * @return void
   */
  public function boot()
  {
    $this->package('maknz/slack', null, __DIR__);
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

    return $queue;
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app['slack'] = $this->app->share(function($app)
    {
      $allow_markdown = $app['config']->get('slack::allow_markdown');

      $markdown_in_attachments = $app['config']->get('slack::markdown_in_attachments');

      $unfurl_media = $app['config']->get('slack::unfurl_media');

      $is_slack_enabled = $app['config']->get('slack::is_slack_enabled');

      return new Client(
        $app['config']->get('slack::endpoint'),
        [
          'channel' => $app['config']->get('slack::channel'),
          'username' => $app['config']->get('slack::username'),
          'icon' => $app['config']->get('slack::icon'),
          'link_names' => $app['config']->get('slack::link_names'),
          'unfurl_links' => $app['config']->get('slack::unfurl_links'),
          'unfurl_media' => is_bool($unfurl_media) ? $unfurl_media : true,
          'allow_markdown' => is_bool($allow_markdown) ? $allow_markdown : true,
          'markdown_in_attachments' => is_array($markdown_in_attachments) ? $markdown_in_attachments : [],
          'is_slack_enabled' => is_bool($is_slack_enabled) ? $is_slack_enabled : true,
        ],
        $this->getQueue(),
        new Guzzle
      );
    });

    $this->app->bind('Maknz\Slack\Client', 'slack');
  }

}
