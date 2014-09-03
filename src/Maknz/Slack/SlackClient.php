<?php namespace Maknz\Slack;

use GuzzleHttp\Client as Guzzle;

class SlackClient {

  /**
   * The Slack incoming webhook endpoint
   *
   * @var string
   */
  protected $endpoint;
  
  /**
   * The channel we should send messages to
   * by default
   *
   * @var string
   */
  protected $defaultChannel;

  /**
   * The username we should send messages as
   * by default
   *
   * @var string
   */
  protected $defaultUsername;
  
  /**
   * The default icon url we should send messages with
   * by default
   *
   * @var string
   */
  protected $defaultIcon;

  /**
   * The Guzzle HTTP client
   *
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * Instantiate a new client
   *
   * @param \GuzzleHttp\Client $client
   * @param string $endpoint
   * @param string $defaultChannel
   * @param string $defaultUsername
   */
  public function __construct(Guzzle $client, 
                            $endpoint,
                            $defaultChannel,
                            $defaultUsername,
                            $defaultIcon) {
    
    $this->client = $client;
    $this->endpoint = $endpoint;
    $this->defaultChannel = $defaultChannel;
    $this->defaultUsername = $defaultUsername;
    $this->defaultIcon = $defaultIcon;

  }

  /**
   * Sends a message to a Slack channel
   *
   * @param string $message The message to send
   * @param string $channel An optional non-default channel
   * @param string $username An optional non-default username
   * @param string $icon_url An optional non-default url of an image
   * @param array $attachments An optional attachement to send with the payload
   * @return void
   */
  public function send($message, $channel = null, $username = null, $icon_url = null, $attachments = null) {

    $payload = json_encode([
      'text' => $message,
      'channel' => $channel ?: $this->defaultChannel,
      'username' => $username ?: $this->defaultUsername,
      'icon_url' => $icon_url ?: $this->defaultIcon,
      'attachments' => $attachments ?: []
  }

}
