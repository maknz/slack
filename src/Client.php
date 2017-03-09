<?php

namespace Razorpay\Slack;

use GuzzleHttp\Client as Guzzle;
use RuntimeException;
use GuzzleHttp\Exception\ClientException;

class Client
{
    /**
     * The Slack incoming webhook endpoint.

     *
     * @var string
     */
    protected $endpoint;

    /**
     * The default channel to send messages to.
     *
     * @var string
     */
    protected $channel;

    /**
     * The default username to send messages as.
     *
     * @var string
     */
    protected $username;

    /**
     * The default icon to send messages with.
     *
     * @var string
     */
    protected $icon;

    /**
     * Whether to link names like @regan or leave
     * them as plain text.
     *
     * @var bool
     */
    protected $linkNames = false;

    /**
     * Whether Slack should unfurl text-based URLs.
     *
     * @var bool
     */
    protected $unfurlLinks = false;

    /**
     * Whether Slack should unfurl media URLs.
     *
     * @var bool
     */
    protected $unfurlMedia = true;

    /**
     * Whether message text should be formatted with Slack's
     * Markdown-like language.
     *
     * @var bool
     */
    protected $allowMarkdown = true;

    /**
     * The attachment fields which should be formatted with
     * Slack's Markdown-like language.
     *
     * @var array
     */
    protected $markdownInAttachments = [];

    /**
     * The Guzzle HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * The QueueManager instance
     *
     * @var Illuminate\Contracts\Queue\Queue
     */
    protected $queue;

    /**
     * Slack Enable mode
     * @var boolean
     */
    protected $slackStatus = true;


    /**
     * Queue wait timeout on releasing the job. Ideally, for a more complicated scenario
     * the wait timeout could increase based on the number of failures. But we
     * are not handling such cases here.
     *
     * @var integer
     */
    const RELEASE_WAIT_TIMEOUT = 120;

    /**
     * Number of retries before giving up on the job.
     *
     * @var integer
     */
    const MAX_RETRY_ATTEMPTS = 10;

    /**
     * Instantiate a new Client
     *
     * @param string                           $endpoint   endpoint
     * @param array                            $attributes attributes
     * @param Illuminate\Contracts\Queue\Queue $queue      queue
     * @param Guzzle                           $guzzle     guzzle
     *
     * @return void
     */
    public function __construct(
        $endpoint,
        array $attributes = [],
        $queue = null,
        Guzzle $guzzle = null)
    {
        $this->endpoint = $endpoint;

        if (isset($attributes['channel']))
        {
            $this->setDefaultChannel($attributes['channel']);
        }

        if (isset($attributes['username']))
        {
            $this->setDefaultUsername($attributes['username']);
        }

        if (isset($attributes['icon']))
        {
            $this->setDefaultIcon($attributes['icon']);
        }

        if (isset($attributes['link_names']))
        {
            $this->setLinkNames($attributes['link_names']);
        }

        if (isset($attributes['unfurl_links']))
        {
            $this->setUnfurlLinks($attributes['unfurl_links']);
        }

        if (isset($attributes['unfurl_media']))
        {
            $this->setUnfurlMedia($attributes['unfurl_media']);
        }

        if (isset($attributes['allow_markdown']))
        {
            $this->setAllowMarkdown($attributes['allow_markdown']);
        }

        if (isset($attributes['markdown_in_attachments']))
        {
            $this->setMarkdownInAttachments($attributes['markdown_in_attachments']);
        }

        if(isset($attributes['is_slack_enabled']))
        {
            $this->setSlackStatus($attributes['is_slack_enabled']);
        }

        $this->guzzle = $guzzle ?: new Guzzle;

        $this->queue = $queue;

        $this->maxRetryAttempts = self::MAX_RETRY_ATTEMPTS;
    }

    /**
     * Sets the Connection of Queue to be used
     * Please Note: This Sets the Connection not the Queue
     *
     * @param string $queue Name of the queue
     *
    * @return $this
    */
    public function setQueue($queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Get the Queue Connection to be used
     * Please Note: This Gets the Connection not the Queue
     *
     * @return string
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Pass any unhandled methods through to a new Message
     * instance
     *
     * @param string $name      The name of the method
     * @param array  $arguments The method arguments
     *
     * @return \Razorpay\Slack\Message
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
     * @param string $endpoint endpoint
     *
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
     * @param string $channel channel
     *
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
     * @param string $username username
     *
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
     * @param string $icon icon
     *
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
        return $this->linkNames;
    }

    /**
     * Set whether messages sent will have names (like @regan)
     * will be converted into links
     *
     * @param boolean $value value
     *
     * @return void
     */
    public function setLinkNames($value)
    {
        $this->linkNames = (boolean) $value;
    }

    /**
     * Get whether text links should be unfurled
     *
     * @return boolean
     */
    public function getUnfurlLinks()
    {
        return $this->unfurlLinks;
    }

    /**
     * Set whether text links should be unfurled
     *
     * @param boolean $value value
     *
     * @return void
     */
    public function setUnfurlLinks($value)
    {
        $this->unfurlLinks = (boolean) $value;
    }

    /**
     * Get whether media links should be unfurled
     *
     * @return boolean
     */
    public function getUnfurlMedia()
    {
        return $this->unfurlMedia;
    }

    /**
     * Set whether media links should be unfurled
     *
     * @param boolean $value value
     *
     * @return void
     */
    public function setUnfurlMedia($value)
    {
        $this->unfurlMedia = (boolean) $value;
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
     * @return void
     */
    public function setAllowMarkdown($value)
    {
        $this->allowMarkdown = (boolean) $value;
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
     * @return void
     */
    public function setMarkdownInAttachments(array $fields)
    {
        $this->markdownInAttachments = $fields;
    }

    /**
     * Set whether slack mode is enabled/disabled
     *
     * @param boolean $status status
     *
     * @return void
     */
    public function setSlackStatus($status)
    {
        $this->slackStatus = $status;
    }

    /**
     * Gets the current slack mode - enabled/disabled
     *
     * @return boolean
     */
    public function getSlackStatus()
    {
        return $this->slackStatus;
    }

    /**
     * Create a new message with defaults
     *
     * @return \Razorpay\Slack\Message
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
     *
     * @param array $data Slack Payload
     *
     * @return void
     * @throws GuzzleHttp\Exception\ClientException   throws exception due to network errors. The client/caller
     *                                                needs to handle this as the resulting behavior might be
     *                                                different for different client calls between syncronous and
     *                                                asynchronous calls
     */
    protected function messagePoster($data)
    {
        $encoded = json_encode($data, JSON_UNESCAPED_UNICODE);

        if ($encoded === false)
        {
            throw new RuntimeException(
                sprintf(
                    'JSON encoding error %s: %s',
                    json_last_error(),
                    json_last_error_msg()));
        }

        $this->guzzle->post($this->endpoint, ['body' => $encoded]);
    }

    /**
     * Queue a message
     *
     * @param \Razorpay\Slack\Message $message message
     * @return void
     */
    public function queueMessage(Message $message, $numRetries, $queue = null)
    {
        $payload = $this->preparePayload($message, $numRetries);

        $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE);

        $this->queue->push(__CLASS__, $payload, $queue);
    }

    /**
     * Execute the message sending via a Queue
     *
     * @param Illuminate\Queue\Jobs\Job $job  Job instance
     * @param array                     $data Slack Payload
     *
     * @return void
     */
    public function fire($job, array $data)
    {
        if ($job->attempts() >= $data['metadata']['num_retries'])
        {
            $job->delete();
        }
        else
        {
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
    }

    /**
     * Prepares the payload to be sent to the webhook
     *
     * @param \Razorpay\Slack\Message $message The message to send
     *
     * @return array
     */
    public function preparePayload(Message $message, $numRetries = null)
    {
        $payload = [
          'text'         => $message->getText(),
          'channel'      => $message->getChannel(),
          'username'     => $message->getUsername(),
          'link_names'   => $this->getLinkNames() ? 1 : 0,
          'unfurl_links' => $this->getUnfurlLinks(),
          'unfurl_media' => $this->getUnfurlMedia(),
          'mrkdwn'       => $message->getAllowMarkdown()
        ];

        if ($numRetries)
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
     * @param \Razorpay\Slack\Message $message message
     *
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

    /**
     * Send a message.
     *
     * @param \Razorpay\Slack\Message $message
     * @return void
     */
    public function sendMessage(Message $message)
    {
        $payload = $this->preparePayload($message);

        $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE);

        if ($encoded === false)
        {
            throw new RuntimeException(
                sprintf(
                    'JSON encoding error %s: %s',
                    json_last_error(),
                    json_last_error_msg()));
        }

        $this->guzzle->post($this->endpoint, ['body' => $encoded]);
    }
}
