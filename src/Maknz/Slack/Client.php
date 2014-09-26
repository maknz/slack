<?php namespace Maknz\Slack;

use InvalidArgumentException;
use GuzzleHttp\Client as Guzzle;

class Client {

  /**
   * The Slack incoming webhook endpoint
   *
   * @var string
   */
  protected $endpoint;
  
  /**
   * The channel we should send messages to
   *
   * @var string
   */
  protected $channel = '#general';

  /**
   * The username we should send messages as
   *
   * @var string
   */
  protected $username = 'Robot';

  /**
   * The URL to the icon to use
   *
   * @var string
   */
  protected $icon;

  /**
   * The type of icon we are using
   *
   * @var enum
   */
  protected $iconType;

  /**
   * An array of attachments to send
   *
   * @var array
   */
  protected $attachments = [];

  /**
   * The Guzzle HTTP client
   *
   * @var \GuzzleHttp\Client
   */
  protected $guzzle;

  /**
   *
   * @var string
   */
  const ICON_TYPE_URL = 'icon_url';

  /**
   *
   * @var string
   */
  const ICON_TYPE_EMOJI = 'icon_emoji';

  /**
   * Instantiate a new client
   *
   * @param string $endpoint The Slack webhook
   * @param array $attributes
   * @param \GuzzleHttp\Client $guzzle
   * @return void
   */
  public function __construct($endpoint, array $attributes = [], Guzzle $guzzle = null)
  {
    $this->setEndpoint($endpoint);

    if (isset($attributes['channel'])) $this->setChannel($attributes['channel']);

    if (isset($attributes['username'])) $this->setUsername($attributes['username']);

    if (isset($attributes['icon'])) $this->setIcon($attributes['icon']);

    $this->guzzle = $guzzle ?: new Guzzle;
  }

  /**
   * Get the Slack incoming webhook endpoint
   *
   * @return string
   */
  public function getEndpoint()
  {
    return $this->endpoint;
  }

  /**
   * Set the Slack incoming webhook endpoint
   *
   * @param string $endpoint The full endpoint URL
   * @return $this
   */
  public function setEndpoint($endpoint)
  {
    $this->endpoint = $endpoint;

    return $this;
  }

  /**
   * Get the channel we will post to 
   *
   * @return string
   */
  public function getChannel()
  {
    return $this->channel;
  }

  /**
   * Set the channel we will post to
   *
   * @param string $channel
   * @return $this
   */
  public function setChannel($channel)
  {
    $this->channel = $channel;

    return $this;
  }

  /**
   * Get the username we will post as
   *
   * @return string
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * Set the username we will post as
   *
   * @param string $username
   * @return $this
   */
  public function setUsername($username)
  {
    $this->username = $username;

    return $this;
  }

  /**
   * Get the icon (either URL or emoji) we will post as
   *
   * @return string
   */
  public function getIcon()
  {
    return $this->icon;
  }

  /**
   * Set the icon (either URL or emoji) we will post as.
   *
   * @param string $icon
   * @return this
   */
  public function setIcon($icon)
  {
    if (mb_substr($icon, 0, 1) == ":" && mb_substr($icon, mb_strlen($icon) - 1, 1) == ":")
    {
      $this->iconType = self::ICON_TYPE_EMOJI;
    }

    else
    {
      $this->iconType = self::ICON_TYPE_URL;
    }

    $this->icon = $icon;

    return $this;
  }

  /**
   * Get the icon type being used, if an icon is set
   *
   * @return string
   */
  public function getIconType()
  {
    return $this->iconType;
  }

  /**
   * Change the name of the user the post will be made as
   *
   * @param string $username
   * @return $this
   */
  public function from($username)
  {
    $this->setUsername($username);

    return $this;
  }

  /**
   * Change the channel the post will be made to
   *
   * @param string $channel
   * @return $this
   */
  public function to($channel)
  {
    $this->setChannel($channel);

    return $this;
  }

  /**
   * Chainable method for setting the icon
   *
   * @param string $icon
   * @return $this
   */
  public function withIcon($icon)
  {
    $this->setIcon($icon);

    return $this;
  }

  /**
   * Add an attachment to the message
   * 
   * @param mixed $attachment
   * @return $this
   */
  public function attach($attachment)
  {
    if ($attachment instanceof Attachment)
    {
      $this->attachments[] = $attachment;

      return $this;
    }

    elseif (is_array($attachment))
    {
      $attachment = new Attachment($attachment);

      $this->attachments[] = $attachment;

      return $this;
    }

    throw new InvalidArgumentException('Attachment must be an instance of Maknz\\Slack\\Attachment or a keyed array');
  }

  /**
   * Get the attachments for the message
   *
   * @return array
   */
  public function getAttachments()
  {
    return $this->attachments;
  }

  /**
   * Set the attachments for the message
   *
   * @param string $attachments
   * @return $this
   */
  public function setAttachments(array $attachments)
  {
    $this->clearAttachments();
    
    foreach ($attachments as $attachment)
    {
      $this->attach($attachment);
    }

    return $this;
  }

  /**
   * Remove all attachments for the message
   *
   * @return $this
   */
  public function clearAttachments()
  {
    $this->attachments = [];

    return $this;
  }

  /**
   * Sends a message to a Slack channel
   *
   * @param string $message The message to send
   * @return void
   */
  public function send($message)
  {
    $payload = json_encode($this->preparePayload($message));
    
    $this->guzzle->post($this->endpoint, ['body' => $payload]);
  }

  /**
   * Prepares the payload to be sent to the webhook
   *
   * @param string $message The message to send
   * @return array
   */
  public function preparePayload($message)
  {
    $payload = [
      'text' => $message,
      'channel' => $this->channel,
      'username' => $this->username
    ];

    if ($icon = $this->getIcon())
    {
      $payload[$this->getIconType()] = $icon;
    }

    $payload['attachments'] = $this->getAttachmentsAsArrays();

    return $payload;
  }

  /**
   * Get the attachments in array form
   *
   * @return array
   */
  protected function getAttachmentsAsArrays()
  {
    $attachments = [];

    foreach ($this->getAttachments() as $attachment)
    {
      $attachments[] = $attachment->toArray();
    }

    return $attachments;
  }

}
