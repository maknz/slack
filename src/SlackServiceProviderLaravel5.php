<?php namespace Maknz\Slack;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Encryption\Encrypter as Encrypter;
use Illuminate\Queue\Capsule\Manager as QueueManager;
use Queue;

class SlackServiceProviderLaravel5 extends ServiceProvider
{
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
        $queue = $app['queue.connection'];

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

        $this->app->singleton('slack', function ($app) {
            $slack = new Client(
                $app['config']->get('slack.endpoint'),
                [
                    'channel'                 => $app['config']->get('slack.channel'),
                    'username'                => $app['config']->get('slack.username'),
                    'icon'                    => $app['config']->get('slack.icon'),
                    'link_names'              => $app['config']->get('slack.link_names'),
                    'unfurl_links'            => $app['config']->get('slack.unfurl_links'),
                    'unfurl_media'            => $app['config']->get('slack.unfurl_media'),
                    'allow_markdown'          => $app['config']->get('slack.allow_markdown'),
                    'markdown_in_attachments' => $app['config']->get('slack.markdown_in_attachments'),
                    'is_slack_enabled'        => $app['config']->get('slack.is_slack_enabled'),
                ],
                $this->getQueue(),
                new Guzzle
            );
        });

        $this->app->bind('Maknz\Slack\Client', 'slack');
    }
}
