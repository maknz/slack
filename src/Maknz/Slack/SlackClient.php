<?php namespace Maknz\Slack;

use GuzzleHttp\Client as Guzzle;

class SlackClient {

  /**
   * The Slack account name
   *
   * @var string
   */
  protected $account;

  /**
   * The incoming webhook token
   *
   * @param string
   */
  protected $token;
  
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
   * The Guzzle HTTP client
   *
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * Instantiate a new client
   *
   * @param \GuzzleHttp\Client $client
   * @param string $account
   * @param string $token
   */
  public function __construct(Guzzle $client, 
                            $account,
                            $token,
                            $defaultChannel,
                            $defaultUsername) {
    $this->client = $client;
    $this->account = $account;
    $this->token = $token;
    $this->defaultChannel = $defaultChannel;
    $this->defaultUsername = $defaultUsername;

  }

  /**
   * Generate the incoming webhook endpoint to send
   * messages to.
   *
   * @return string
   */
  private function getEndpoint() {

    return sprintf('https://%s.slack.com/services/hooks/incoming-webhook?token=%s',
                   $this->account,
                   $this->token);

  }

  /**
   * Sends a message to a Slack channel
   *
   * @param string $message The message to send
   * @param string $channel An optional non-default channel
   * @param string $username An optional non-default username
   * @return void
   */
  public function send($message, $channel = null, $username = null) {

    $payload = json_encode([
      'text' => $message,
      'channel' => $channel ?: $this->defaultChannel,
      'username' => $username ?: $this->defaultUsername,
    ]);
    
    $this->client->post($this->getEndpoint(), ['body' => $payload]);

  }

}