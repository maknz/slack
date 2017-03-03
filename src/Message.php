<?php

namespace Maknz\Slack;

use InvalidArgumentException;

class Message
{

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
     * The actualy payload being created from the text for sending
     *
     * @var array
     */
    protected $payload;

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
    protected $allowMarkdown = true;

    /**
     * The attachment fields which should be formatted with
     * Slack's Markdown-like language
     *
     * @var array
     */
    protected $markdownInAttachments = [];

    /**
     * An array of attachments to send
     *
     * @var array
     */
    protected $attachments = [];

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
     * @param \Maknz\Slack\Client $client client
     *
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
     * @param string $text text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     *  Get the payload
     *
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Set the payload
     *
     * @param array $payload payload
     *
     * @return array
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;
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
     * @param string $channel channel
     *
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
     * @param string $username username
     *
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
     * @param string $icon icon
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        if ($icon === null)
        {
            $this->iconType = null;
        }
        else if (($icon[0] === ":") and ($icon[strlen($icon) - 1] === ":"))
        {
            $this->iconType = self::ICON_TYPE_EMOJI;
        }
        else
        {
            $this->iconType = self::ICON_TYPE_URL;
        }

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
        return $this->allowMarkdown;
    }

    /**
     * Set whether message text should be formatted with
     * Slack's Markdown-like language
     *
     * @param boolean $value value
     *
     * @return $this
     */
    public function setAllowMarkdown($value)
    {
        $this->allowMarkdown = (boolean) $value;

        return $this;
    }

    /**
     * Enable Markdown formatting for the message
     *
     * @return $this
     */
    public function enableMarkdown()
    {
        return $this->setAllowMarkdown(true);
    }

    /**
     * Disable Markdown formatting for the message
     *
     * @return $this
     */
    public function disableMarkdown()
    {
        return $this->setAllowMarkdown(false);
    }

    /**
     * Get the attachment fields which should be formatted
     * in Slack's Markdown-like language
     *
     * @return array
     */
    public function getMarkdownInAttachments()
    {
        return $this->markdownInAttachments;
    }

    /**
     * Set the attachment fields which should be formatted
     * in Slack's Markdown-like language
     *
     * @param array $fields fields
     *
     * @return $this
     */
    public function setMarkdownInAttachments(array $fields)
    {
        $this->markdownInAttachments = $fields;

        return $this;
    }

    /**
     * Change the name of the user the post will be made as
     *
     * @param string $username username
     *
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
     * @param string $channel channel
     *
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
     * @param string $icon icon
     *
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
     * @param mixed $attachment attachment
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function attach($attachment)
    {
        if ($attachment instanceof Attachment)
        {
            $this->attachments[] = $attachment;
        }
        else if (is_array($attachment) === true)
        {
            $attachmentObject = new Attachment($attachment);

            if (isset($attachment['mrkdwn_in']) === false)
            {
                $attachmentObject->setMarkdownFields(
                    $this->getMarkdownInAttachments());
            }

            $this->attachments[] = $attachmentObject;
        }
        else
        {
            throw new InvalidArgumentException(
                'Attachment must be an instance of Slack\\Attachment or a keyed array');
        }

        return $this;
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
     * @param string $attachments attachments
    *
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
     *
     * @param string  $text       The text to send
     * @param integer $numRetries Number of retry attempts for sending the message
     *
     * @return void
     */
    protected function sendMessage(
        $text = null,
        $numRetries = self::MAX_RETRY_ATTEMPTS,
        $queue = null)
    {
        $isMessageSent = false;

        $numAttempts = 0;

        while (($numAttempts <= $numRetries) and
               ($isMessageSent === false))
        {
            try
            {
                $this->client->sendMessage($this);

                $isMessageSent = true;
            }
            catch(\Exception $e)
            {
            }

            $numAttempts += 1;
        }

        // push it into the queue
        if ($isMessageSent === false)
        {
            $this->setPayload($this->client->preparePayload($this, $numRetries));

            $this->queueMessage($text, $numRetries, $queue);
        }
    }

    /**
     * Queue the message
     *
     * @param string  $text       The text to send
     * @param integer $numRetries The number of times to retry on failure
     *
     * @return void
     */
    protected function queueMessage(
        $text = null,
        $numRetries = self::MAX_RETRY_ATTEMPTS,
        $queue = null)
    {
        $this->client->queueMessage($this, $numRetries, $queue);
    }

    /**
    * @param string $headline headline
    * @param array  $postData postData
    * @param string $pretext  pretext
    *
    * @return $data
    */
    protected function buildMessage($headline, array $postData, string $pretext, array $settings)
    {
        // Fallback text for plaintext clients, like IRC
        $data = [
            'fallback' => $headline.'\n',
            'fields'   => [],
            'pretext' => $pretext,
        ];

        // If our data is nested, we need to flatten it
        $postData = $this->flattenArray($postData);

        // attach for all extra fields
        foreach ($postData as $key => $value)
        {
            //  Fallback text for plaintext clients, like IRC
            $data['fallback'] .= $key . ': ' . $value . '\n';

            // TODO : fix this $settings is undefined
            $data['color'] = isset($settings['color']) ? $settings['color'] : 'good';

            $data['fields'][] = [
                'title' => $key,
                'value' => $value,
                'short' => true,
            ];
        }

        return $data;
    }

    /**
    * @param string $headline headline/title of the message as appearing on the channel.
    * @param array $postData actual post data
    * @param array $settings post meta data - username, icon etc
    * @param string $pretext
    * @param bool $asQueue boolean to determine whether the message is to be queued or sent immediately
    * @param integer $numRetries maximum number of times to retry sending the message.
    * @param string  $queue Queue on which message has to be sent
    */
    protected function messageHandler(
        $headline,
        array $postData,
        array $settings = [],
        $pretext = '',
        $asQueue = true,
        $numRetries = self::MAX_RETRY_ATTEMPTS,
        $queue = null)
    {
        $data = $this->buildMessage($headline, $postData, $pretext, $settings);

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

        // check for malicious calls.
        if ($numRetries <= 0)
        {
            $numRetries = self::MAX_RETRY_ATTEMPTS;
        }

        if ($headline)
        {
            $this->setText($headline);
        }

        if ($asQueue === true)
        {
            $this->setPayload($this->client->preparePayload($this, $numRetries));

            $this->queueMessage($headline, $numRetries, $queue);
        }
        else
        {
            $this->setPayload($this->client->preparePayload($this));

            $this->sendMessage($headline, $numRetries, $queue);
        }
    }

    /**
     * Queue the message for delivery later
     *
     * @param string  $headline        headline of the message as appearing on channel
     * @param array   $postData   Actual post data
     * @param array   $settings   Post meta data - username, icon etc
     * @param string  $pretext    Pretext
     * @param integer $numRetries The maximum no of times to retry sending msg
     * @param string  $queue      Queue to be used
     *
     * @return void
     */
    public function queue(
        $headline,
        array $postData,
        array $settings = [],
        $pretext = '',
        $numRetries = self::MAX_RETRY_ATTEMPTS,
        $queue = null)
    {
        if ($this->client->getSlackStatus())
        {
            return $this->messageHandler(
                $headline,
                $postData,
                $settings,
                $pretext,
                true,
                $numRetries,
                $queue);
        }
    }

    public function onQueue(
        $queue,
        $headline,
        array $postData,
        array $settings = [],
        $pretext = '',
        $numRetries = self::MAX_RETRY_ATTEMPTS)
    {
        $this->queue($headline, $postData, $settings, $pretext, $numRetries, $queue);
    }

    /**
     * Send the message immediately
     *
     * @param string  $headline   headline/title of the message as appearing on the channel.
     * @param array   $postData   actual post data
     * @param array   $settings   post meta data - username, icon etc
     * @param string  $pretext    pretext
     * @param integer $numRetries the maximum number of times to retry sending the message.
     *
     * @return void
     */
    public function send(
        $headline,
        array $postData,
        array $settings = [],
        $pretext = '',
        $numRetries = self::MAX_RETRY_ATTEMPTS)
    {
        if ($this->client->getSlackStatus())
        {
            return $this->messageHandler(
                $headline, $postData, $settings,
                $pretext, false, $numRetries);
        }
    }

    /**
     * Flatten multi dimensional array into a single dimensional array
     *
     * @param array  $array     array
     * @param string $separator separator
     * @param string $prefix    prefix
     *
     * @return array
    */
    protected function flattenArray($array, $separator = '.', $prefix = '')
    {
        $result = [];

        foreach ($array as $key => $value)
        {
            $newKey = $prefix . (empty($prefix) ? '' : $separator) . $key;

            if (is_array($value))
            {
                $result = array_merge(
                    $result,
                    flattenArray(
                        $value,
                        $separator,
                        $newKey));
            }
            else
            {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
}
