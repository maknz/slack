<?php namespace Maknz\Slack;

use GuzzleHttp\Client as Guzzle;
use Illuminate\Queue\Capsule\Manager as Queue;
use GuzzleHttp\Exception\ClientException;

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
   * Whether Slack should unfurl text-based URLs
   *
   * @var boolean
   */
  protected $unfurl_links = false;

  /**
   * Whether Slack should unfurl media URLs
   *
   * @var boolean
   */
  protected $unfurl_media = true;

  /**
   * Whether message text should be formatted with Slack's
   * Markdown-like language
   *
   * @var boolean
   */
  protected $allow_markdown = true;

  /**
   * The attachment fields which should be formatted with
   * Slack's Markdown-like language
   *
   * @var array
   */
  protected $markdown_in_attachments = [];


  /**
   * The Guzzle HTTP client instance
   *
   * @var \GuzzleHttp\Client
   */
  protected $guzzle;

  /**
   * The QueueManager instance
   * @var Illuminate\Queue\QueueManager
   */
  protected $queue;

  /**
   * Queue wait timeout on releasing the job. Ideally, for a more complicated scenario
   * the wait timeout could increase based on the number of failures. But we
   * are not handling such cases here.
   *
   * @var integer
   */
  const RELEASE_WAIT_TIMEOUT = 10;

  /**
   * Number of retries before giving up on the job.
   *
   * @var integer
   */
  const MAX_RETRY_ATTEMPTS = 10;


  /**
   * Instantiate a new Client
   *
   * @param string $endpoint
   * @param array $attributes
   * @return void
   */
  public function __construct($endpoint, array $attributes = [], Queue $queue = null, Guzzle $guzzle = null)
  {
    $this->endpoint = $endpoint;

    if (isset($attributes['channel'])) $this->setDefaultChannel($attributes['channel']);

    if (isset($attributes['username'])) $this->setDefaultUsername($attributes['username']);

    if (isset($attributes['icon'])) $this->setDefaultIcon($attributes['icon']);

    if (isset($attributes['link_names'])) $this->setLinkNames($attributes['link_names']);

    if (isset($attributes['unfurl_links'])) $this->setUnfurlLinks($attributes['unfurl_links']);

    if (isset($attributes['unfurl_media'])) $this->setUnfurlMedia($attributes['unfurl_media']);

    if (isset($attributes['allow_markdown'])) $this->setAllowMarkdown($attributes['allow_markdown']);

    if (isset($attributes['markdown_in_attachments'])) $this->setMarkdownInAttachments($attributes['markdown_in_attachments']);

    $this->guzzle = $guzzle ?: new Guzzle;

    $this->queue = $queue;

    if($this->queue !== null)
      $this->queue->setAsGlobal();

    $this->maxRetryAttempts = self::MAX_RETRY_ATTEMPTS;
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
   * Returns the QueueManager instance being used
   * @return Illuminate\Queue\QueueManager
   */
  public function getQueueManager()
  {
    return $this->queue;
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
   * Get whether text links should be unfurled
   *
   * @return boolean
   */
  public function getUnfurlLinks()
  {
    return $this->unfurl_links;
  }

  /**
   * Set whether text links should be unfurled
   *
   * @param boolean $value
   * @return void
   */
  public function setUnfurlLinks($value)
  {
    $this->unfurl_links = (boolean) $value;
  }

  /**
   * Get whether media links should be unfurled
   *
   * @return boolean
   */
  public function getUnfurlMedia()
  {
    return $this->unfurl_media;
  }

  /**
   * Set whether media links should be unfurled
   *
   * @param boolean $value
   * @return void
   */
  public function setUnfurlMedia($value)
  {
    $this->unfurl_media = (boolean) $value;
  }

  /**
   * Get whether message text should be formatted with
   * Slack's Markdown-like language
   *
   * @return boolean
   */
  public function getAllowMarkdown()
  {
    return $this->allow_markdown;
  }

  /**
   * Set whether message text should be formatted with
   * Slack's Markdown-like language
   *
   * @param boolean $value
   * @return void
   */
  public function setAllowMarkdown($value)
  {
    $this->allow_markdown = (boolean) $value;
  }

  /**
   * Get the attachment fields which should be formatted
   * in Slack's Markdown-like language
   *
   * @return array
   */
  public function getMarkdownInAttachments()
  {
    return $this->markdown_in_attachments;
  }

  /**
   * Set the attachment fields which should be formatted
   * in Slack's Markdown-like language
   *
   * @param array $fields
   * @return void
   */
  public function setMarkdownInAttachments(array $fields)
  {
    $this->markdown_in_attachments = $fields;
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

    $message->setAllowMarkdown($this->getAllowMarkdown());

    $message->setMarkdownInAttachments($this->getMarkdownInAttachments());

    return $message;
  }

  /**
   * Actually send the message
   * @param  array            $data                 Slack Payload
   * @return void
   * @throws GuzzleHttp\Exception\ClientException   throws exception due to network errors. The client/caller
   *                                                needs to handle this as the resulting behavior might be
   *                                                different for different client calls between syncronous and
   *                                                asynchronous calls
   */
  protected function messagePoster($data)
  {
    $encoded = json_encode($data, JSON_UNESCAPED_UNICODE);

    $this->guzzle->post($this->endpoint, ['body' => $encoded]);
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

    $this->messagePoster($payload);
  }

  /**
   * Queue a message
   *
   * @param \Maknz\Slack\Message $message
   * @return void
   */
  public function queueMessage(Message $message, $numRetries)
  {
    // check for malicious calls and if so, try max times
    if ($numRetries <= 0)
    {
        $numRetries = self::MAX_RETRY_ATTEMPTS;
    }

    $payload = $this->preparePayload($message, $numRetries);

    $this->maxRetryAttempts = $numRetries;

    $this->queue->push(__CLASS__, $payload);
  }

  /**
   * Execute the message sending via a Queue
   * @param  Illuminate\Queue\Jobs\Job $job Job instance
   * @param  array $data Slack Payload
   * @return void
   */
  public function fire($job, array $data)
  {
    if($job->attempts() >= $data['metadata']['num_retries'])
    {
        $job->delete();
    }
    try
    {
        $this->messagePoster($data);

        $job->delete();
    }
    catch(ClientException $e)
    {
        $job->release(self::RELEASE_WAIT_TIMEOUT);
    }
  }

  /**
   * Prepares the payload to be sent to the webhook
   *
   * @param \Maknz\Slack\Message $message The message to send
   * @return array
   */
  public function preparePayload(Message $message, $numRetries = null)
  {
    $payload = [
      'text' => $message->getText(),
      'channel' => $message->getChannel(),
      'username' => $message->getUsername(),
      'link_names' => $this->getLinkNames() ? 1 : 0,
      'unfurl_links' => $this->getUnfurlLinks(),
      'unfurl_media' => $this->getUnfurlMedia(),
      'mrkdwn' => $message->getAllowMarkdown()
    ];

    if($numRetries)
    {
        $payload['metadata'] = ['num_retries' => $numRetries];
    }

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
