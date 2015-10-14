<?php namespace Maknz\Slack;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Queue\Capsule\Manager as Queue;

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
    $key = $this->app['config']['app.key'];
    return new Illuminate\Encryption\Encrypter($key);
  }

  protected function getQueue()
  {
    $name = $this->app['config']['queue.default'];
    $config = $this->app['config']["queue.connections.{$name}"];
    
    $queue = new Queue();
    $queue->addConnection($config);

    $queue->getContainer()->bind('encrypter', $this->getEncrypter());

    $queue->setAsGlobal();

    return $queue;
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app['maknz.slack'] = $this->app->share(function($app)
    {
      $allow_markdown = $app['config']->get('slack::allow_markdown');

      $markdown_in_attachments = $app['config']->get('slack::markdown_in_attachments');

      $unfurl_media = $app['config']->get('slack::unfurl_media');

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
          'markdown_in_attachments' => is_array($markdown_in_attachments) ? $markdown_in_attachments : []
        ],
        $this->getQueue(),
        new Guzzle
      );
    });

    $this->app->bind('Maknz\Slack\Client', 'maknz.slack');
  }

}
