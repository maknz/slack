<?php namespace Maknz\Slack;

use GuzzleHttp\Client as Guzzle;

class Client {

  /**
   * The Slack incoming webhook endpoint
   *
   * @var string
   */
  protected $endpoint;

  /**
   * The default channel to send messages to
   *
   * @var string
   */
  protected $channel;

  /**
   * The default username to send messages as
   *
   * @var string
   */
  protected $username;

  /**
   * The default icon to send messages with
   *
   * @var string
   */
  protected $icon;

  /**
   * Whether to link names like @regan or leave
   * them as plain text
   *
   * @var boolean
   */
  protected $link_names = false;

  /**
   * Slack will automatically unfurl links to well known
   * media URLs (like Youtube, Twitter), but not for URLs
   * with mostly text-based content. But, if you want that,
   * this option can be used.
   *
   * @var boolean
   */
  protected $unfurl_links = false;

  /**
   * The Guzzle HTTP client instance
   *
   * @var \GuzzleHttp\Client
   */
  protected $guzzle;

  /**
   * Instantiate a new Client
   *
   * @param string $endpoint
   * @param array $attributes
   * @return void
   */
  public function __construct($endpoint, array $attributes = [], Guzzle $guzzle = null)
  {
    $this->endpoint = $endpoint;

    if (isset($attributes['channel'])) $this->setDefaultChannel($attributes['channel']);

    if (isset($attributes['username'])) $this->setDefaultUsername($attributes['username']);

    if (isset($attributes['icon'])) $this->setDefaultIcon($attributes['icon']);

    if (isset($attributes['link_names'])) $this->setLinkNames($attributes['link_names']);

    if (isset($attributes['unfurl_links'])) $this->setUnfurlLinks($attributes['unfurl_links']);

    $this->guzzle = $guzzle ?: new Guzzle;
  }

  /**
   * Pass any unhandled methods through to a new Message
   * instance
   *
   * @param string $name The name of the method
   * @param array $arguments The method arguments
   * @return \Maknz\Slack\Message
   */
  public function __call($name, $arguments)
  {
    $message = $this->createMessage();

    call_user_func_array([$message, $name], $arguments);

    return $message;
  }

  /**
   * Get the Slack endpoint
   *
   * @return string
   */
  public function getEndpoint()
  {
    return $this->endpoint;
  }

  /**
   * Set the Slack endpoint
   *
   * @param string $endpoint
   * @return void
   */
  public function setEndpoint($endpoint)
  {
    $this->endpoint = $endpoint;
  }

  /**
   * Get the default channel messages will be created for
   *
   * @return string
   */
  public function getDefaultChannel()
  {
    return $this->channel;
  }

  /**
   * Set the default channel messages will be created for
   *
   * @param string $channel
   * @return void
   */
  public function setDefaultChannel($channel)
  {
    $this->channel = $channel;
  }

  /**
   * Get the default username messages will be created for
   *
   * @return string
   */
  public function getDefaultUsername()
  {
    return $this->username;
  }

  /**
   * Set the default username messages will be created for
   *
   * @param string $username
   * @return void
   */
  public function setDefaultUsername($username)
  {
    $this->username = $username;
  }

  /**
   * Get the default icon messages will be created with
   *
   * @return string
   */
  public function getDefaultIcon()
  {
    return $this->icon;
  }

  /**
   * Set the default icon messages will be created with
   *
   * @param string $icon
   * @return void
   */
  public function setDefaultIcon($icon)
  {
    $this->icon = $icon;
  }

  /**
   * Get whether messages sent will have names (like @regan)
   * will be converted into links
   *
   * @return boolean
   */
  public function getLinkNames()
  {
    return $this->link_names;
  }

  /**
   * Set whether messages sent will have names (like @regan)
   * will be converted into links
   *
   * @param boolean $value
   * @return void
   */
  public function setLinkNames($value)
  {
    $this->link_names = (boolean) $value;
  }

  /**
   * Get whether links should be unfurled
   *
   * @return boolean
   */
  public function getUnfurlLinks()
  {
    return $this->unfurl_links;
  }

  /**
   * Set whether links should be unfurled
   *
   * @param boolean $value
   * @return void
   */
  public function setUnfurlLinks($value)
  {
    $this->unfurl_links = (boolean) $value;
  }

  /**
   * Create a new message with defaults
   *
   * @return \Maknz\Slack\Message
   */
  public function createMessage()
  {
    $message = new Message($this);

    $message->setChannel($this->getDefaultChannel());

    $message->setUsername($this->getDefaultUsername());

    $message->setIcon($this->getDefaultIcon());

    return $message;
  }

  /**
   * Send a message
   *
   * @param \Maknz\Slack\Message $message
   * @return void
   */
  public function sendMessage(Message $message)
  {
    $payload = $this->preparePayload($message);

    $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE);

    $this->guzzle->post($this->endpoint, ['body' => $encoded]);
  }

  /**
   * Prepares the payload to be sent to the webhook
   *
   * @param \Maknz\Slack\Message $message The message to send
   * @return array
   */
  public function preparePayload(Message $message)
  {
    $payload = [
      'text' => $message->getText(),
      'channel' => $message->getChannel(),
      'username' => $message->getUsername(),
      'link_names' => $this->getLinkNames() ? 1 : 0,
      'unfurl_links' => $this->getUnfurlLinks() ? 1 : 0
    ];

    if ($icon = $message->getIcon())
    {
      $payload[$message->getIconType()] = $icon;
    }

    $payload['attachments'] = $this->getAttachmentsAsArrays($message);

    return $payload;
  }

  /**
   * Get the attachments in array form
   *
   * @param \Maknz\Slack\Message $message
   * @return array
   */
  protected function getAttachmentsAsArrays(Message $message)
  {
    $attachments = [];

    foreach ($message->getAttachments() as $attachment)
    {
      $attachments[] = $attachment->toArray();
    }

    return $attachments;
  }

}
