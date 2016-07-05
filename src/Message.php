<?php namespace Maknz\Slack;

use InvalidArgumentException;

class Message {

  /**
   * Reference to the Slack client responsible for sending
   * the message
   *
   * @var \Maknz\Slack\Client
   */
  protected $client;

  /**
   * The text to send with the message
   *
   * @var string
   */
  protected $text;

  /**
   * The channel the message should be sent to
   *
   * @var string
   */
  protected $channel;

  /**
   * The username the message should be sent as
   *
   * @var string
   */
  protected $username;

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
   * Whether the message text should be interpreted in Slack's
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
   * An array of attachments to send
   *
   * @var array
   */
  protected $attachments = [];

  /**
   * The name of the queue to be used
   * @var string
   */
  protected $queue = null;

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
   *
   * @var integer
   */
  const MAX_RETRY_ATTEMPTS = 10;

  /**
   * Instantiate a new Message
   *
   * @param \Maknz\Slack\Client $client
   * @return void
   */
  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  /**
   * Get the message text
   *
   * @return string
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * Set the message text
   *
   * @param string $text
   * @return $this
   */
  public function setText($text)
  {
    $this->text = $text;

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
    if ($icon == null)
    {
      $this->icon = $this->iconType = null;

      return;
    }

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

    return $this;
  }

  /**
   * Enable Markdown formatting for the message
   *
   * @return void
   */
  public function enableMarkdown()
  {
    $this->setAllowMarkdown(true);

    return $this;
  }

  /**
   * Disable Markdown formatting for the message
   *
   * @return void
   */
  public function disableMarkdown()
  {
    $this->setAllowMarkdown(false);

    return $this;
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

    return $this;
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
      $attachmentObject = new Attachment($attachment);

      if (! isset($attachment['mrkdwn_in']))
      {
        $attachmentObject->setMarkdownFields($this->getMarkdownInAttachments());
      }

      $this->attachments[] = $attachmentObject;

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
   * Send the message
   * We try to attempt to send the message MAX_RETRY_ATTEMPTS times. If it fails,
   * we will push the message into the queue. Another implementation could be
   * a blocking implementation where the number of retries and the wait timeout could
   * be specified through the method call. We are not going ahead with that at this
   * point since it might end up blocking. We are also not waiting before retry for
   * the same reason.
   * @param string $text The text to send
   * @return void
   */
  protected function _send($text = null, $numRetries = self::MAX_RETRY_ATTEMPTS)
  {
    if($text)
    {
      $this->setText($text);
    }

    $isMessageSent = false;

    $numAttempts = 0;

    // check for malicious calls and if so, try once at least
    if ($numRetries <= 0)
    {
      $numRetries = 1;
    }

    while(($numAttempts < $numRetries) and
          ($isMessageSent === false))
    {
      try
      {
        $this->client->sendMessage($this);

        $isMessageSent = true;
      }
      catch(Exception $e){}

      $numAttempts += 1;
    }

    // push it into the queue
    if(!$isMessageSent)
    {
      $this->queue($text, $numRetries);
    }
  }

  /**
   * Queue the message
   * @param  string $text The text to send
   * @param  integer $numRetries The number of times to retry on failure
   * @return void
   */
  protected function _queue($text = null, $numRetries)
  {
    if ($text)
    {
        $this->setText($text);
    }

    $this->client->queueMessage($this, $this->queue, $numRetries);
  }

  /**
   * @param string $headline
   * @param array  $postData
   * @param string $pretext
   * @return $data
   */
   protected function buildMessage($headline, array $postData, $pretext)
   {
      // Fallback text for plaintext clients, like IRC
      $data = [];

      $data['fallback']   = $headline.'\n';

      $data['fields']     = [];

      $data['pretext']    = $pretext;

      // If our data is nested, we need to flatten it
      $postData = flatten_array($postData);

      // attach for all extra fields
      foreach($postData as $key => $value)
      {
          //  Fallback text for plaintext clients, like IRC
          $data['fallback'] .= $key . ': ' . $value . '\n';

          $data['color'] = isset($settings['color']) ? $settings['color'] : 'good';

          $data['fields'][] = array(
              'title' => $key,
              'value' => $value,
              'short' => true,
          );
      }

      return $data;
   }

  /**
   * @param string $headline
   * @param array $postData actual post data
   * @param array $settings post meta data - username, icon etc
   * @param string $pretext
   * @param bool $asQueue
   * @param integer $numRetries
   */
  protected function messageHandler($headline, array $postData, array $settings = [],
                                 $pretext = '', $asQueue = true, $numRetries = self::MAX_RETRY_ATTEMPTS)
  {
      $data = $this->buildMessage($headline, $postData, $pretext);

      $settings['username'] = isset($settings['username']) ? $settings['username'] : null;

      $settings['icon']     = isset($settings['icon']) ? $settings['icon'] : null;

      if (isset($settings['channel']))
      {
          $this->to($settings['channel'])
                  ->from($settings['username'])
                  ->withIcon($settings['icon'])
                  ->attach($data);
      }
      else
      {
          $this->attach($data);
      }

      if($asQueue)
      {
          $this->_queue($headline, $numRetries);
      }
      else
      {
          $this->_send($headline, $numRetries);
      }

  }

  /**
   * Queue the message for delivery later
   * @param string $headline
   * @param array $postData actual post data
   * @param array $settings post meta data - username, icon etc
   * @param string $pretext
   * @param integer $numRetries
   * @return void
   */
  public function queue($headline, array $postData, array $settings = [],
                        $pretext = '',$numRetries = self::MAX_RETRY_ATTEMPTS)
  {
    if($this->client->getSlackStatus())
    {
      return $this->messageHandler($headline, $postData, $settings, $pretext, true, $numRetries);
    }
  }

  /**
   * Send the message immediately
   * @param string $headline
   * @param array $postData actual post data
   * @param array $settings post meta data - username, icon etc
   * @param string $pretext
   * @param integer $numRetries
   * @return void
   */

  public function send($headline, array $postData, array $settings = [],
                       $pretext = '',$numRetries = self::MAX_RETRY_ATTEMPTS)
  {
    if($this->client->getSlackStatus())
    {
      return $this->messageHandler($headline, $postData, $settings, $pretext, false, $numRetries);

    }

  }


  /**
   * Sets the queue to be used
   * @param string $queue Name of the queue
   * @return $this
   */
  public function setQueue($queue)
  {
    $this->queue = $queue;
    return $this;
  }

  /**
   * Get the queue to be used
   *
   * @return string
   */
  public function getQueue()
  {
    return $this->queue;
  }
}
