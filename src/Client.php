<?php
namespace Maknz\Slack;

use GuzzleHttp\Client as Guzzle;
use RuntimeException;

/**
 * @method Message to(string $channel)
 * @method Message send(string $text = null)
 */
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
     * Whether the response should be viewable by others
     * when posted to a channel. 'ephemeral' | 'in_channel'.
     *
     * @var string
     */
    protected $response_type = 'ephemeral';

    /**
     * Whether to link names like @regan or leave
     * them as plain text.
     *
     * @var bool
     */
    protected $link_names = false;

    /**
     * Whether Slack should unfurl text-based URLs.
     *
     * @var bool
     */
    protected $unfurl_links = false;

    /**
     * Whether Slack should unfurl media URLs.
     *
     * @var bool
     */
    protected $unfurl_media = true;

    /**
     * Whether message text should be formatted with Slack's
     * Markdown-like language.
     *
     * @var bool
     */
    protected $allow_markdown = true;

    /**
     * The attachment fields which should be formatted with
     * Slack's Markdown-like language.
     *
     * @var array
     */
    protected $markdown_in_attachments = [];

    /**
     * The Guzzle HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * Option name to setter method map.
     *
     * @var array
     */
    protected static $optionSetter = [
        'channel'                 => 'setDefaultChannel',
        'username'                => 'setDefaultUsername',
        'icon'                    => 'setDefaultIcon',
        'response_type'           => 'setResponseType',
        'link_names'              => 'setLinkNames',
        'unfurl_links'            => 'setUnfurlLinks',
        'unfurl_media'            => 'setUnfurlMedia',
        'allow_markdown'          => 'setAllowMarkdown',
        'markdown_in_attachments' => 'setMarkdownInAttachments',
    ];

    /**
     * Instantiate a new Client.
     *
     * @param string                  $endpoint
     * @param array                   $options
     * @param \GuzzleHttp\Client|null $guzzle
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct($endpoint, array $options = [], Guzzle $guzzle = null)
    {
        $this->endpoint = $endpoint;

        $this->setOptions($options);

        $this->guzzle = $guzzle ?: new Guzzle();
    }

    /**
     * Returns property setter method by given option name.
     *
     * @param string $option
     *
     * @return mixed|null
     */
    private static function getOptionSetter(string $option)
    {
        return static::$optionSetter[$option] ?? null;
    }

    /**
     * Pass any unhandled methods through to a new Message
     * instance.
     *
     * @param string $name      The name of the method
     * @param array  $arguments The method arguments
     *
     * @return \Maknz\Slack\Message
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->createMessage(), $name], $arguments);
    }

    /**
     * Get the Slack endpoint.
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set the Slack endpoint.
     *
     * @param string $endpoint
     *
     * @return void
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * Get the default channel messages will be created for.
     *
     * @return string
     */
    public function getDefaultChannel()
    {
        return $this->channel;
    }

    /**
     * Set the default channel messages will be created for.
     *
     * @param string $channel
     *
     * @return void
     */
    public function setDefaultChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * Get the default username messages will be created for.
     *
     * @return string
     */
    public function getDefaultUsername()
    {
        return $this->username;
    }

    /**
     * Set the default username messages will be created for.
     *
     * @param string $username
     *
     * @return void
     */
    public function setDefaultUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get the default icon messages will be created with.
     *
     * @return string
     */
    public function getDefaultIcon()
    {
        return $this->icon;
    }

    /**
     * Set the default icon messages will be created with.
     *
     * @param string $icon
     *
     * @return void
     */
    public function setDefaultIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * Get whether the response should be viewable by others
     * when posted to a channel. 'ephemeral' | 'in_channel'.
     *
     * @return string
     */
    public function getResponseType()
    {
        return $this->response_type;
    }

    /**
     * Set whether the response should be viewable by others
     * when posted to a channel. 'ephemeral' | 'in_channel'.
     *
     * @param string $value
     *
     * @return void
     */
    public function setResponseType($value)
    {
        $this->response_type = $value;
    }

    /**
     * Get whether messages sent will have names (like @regan)
     * will be converted into links.
     *
     * @return bool
     */
    public function getLinkNames()
    {
        return $this->link_names;
    }

    /**
     * Set whether messages sent will have names (like @regan)
     * will be converted into links.
     *
     * @param bool $value
     *
     * @return void
     */
    public function setLinkNames($value)
    {
        $this->link_names = (bool)$value;
    }

    /**
     * Get whether text links should be unfurled.
     *
     * @return bool
     */
    public function getUnfurlLinks()
    {
        return $this->unfurl_links;
    }

    /**
     * Set whether text links should be unfurled.
     *
     * @param bool $value
     *
     * @return void
     */
    public function setUnfurlLinks($value)
    {
        $this->unfurl_links = (bool)$value;
    }

    /**
     * Get whether media links should be unfurled.
     *
     * @return bool
     */
    public function getUnfurlMedia()
    {
        return $this->unfurl_media;
    }

    /**
     * Set whether media links should be unfurled.
     *
     * @param bool $value
     *
     * @return void
     */
    public function setUnfurlMedia($value)
    {
        $this->unfurl_media = (bool)$value;
    }

    /**
     * Get whether message text should be formatted with
     * Slack's Markdown-like language.
     *
     * @return bool
     */
    public function getAllowMarkdown()
    {
        return $this->allow_markdown;
    }

    /**
     * Set whether message text should be formatted with
     * Slack's Markdown-like language.
     *
     * @param bool $value
     *
     * @return void
     */
    public function setAllowMarkdown($value)
    {
        $this->allow_markdown = (bool)$value;
    }

    /**
     * Get the attachment fields which should be formatted
     * in Slack's Markdown-like language.
     *
     * @return array
     */
    public function getMarkdownInAttachments()
    {
        return $this->markdown_in_attachments;
    }

    /**
     * Set the attachment fields which should be formatted
     * in Slack's Markdown-like language.
     *
     * @param array $fields
     *
     * @return void
     */
    public function setMarkdownInAttachments(array $fields)
    {
        $this->markdown_in_attachments = $fields;
    }

    /**
     * Create a new message with defaults.
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
     * Send a message.
     *
     * @param \Maknz\Slack\Message $message
     *
     * @throws \RuntimeException
     */
    public function sendMessage(Message $message)
    {
        $payload = $this->preparePayload($message);

        $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE);

        if ($encoded === false) {
            throw new RuntimeException(sprintf('JSON encoding error %s: %s', json_last_error(), json_last_error_msg()));
        }

        $this->guzzle->post($this->endpoint, ['body' => $encoded]);
    }

    /**
     * Prepares the payload to be sent to the webhook.
     *
     * @param \Maknz\Slack\Message $message The message to send
     *
     * @return array
     */
    public function preparePayload(Message $message)
    {
        $payload = [
            'text'         => $message->getText(),
            'channel'      => $message->getChannel(),
            'username'     => $message->getUsername(),
            'response_type'=> $this->getResponseType(),
            'link_names'   => $this->getLinkNames() ? 1 : 0,
            'unfurl_links' => $this->getUnfurlLinks(),
            'unfurl_media' => $this->getUnfurlMedia(),
            'mrkdwn'       => $message->getAllowMarkdown(),
        ];

        if ($icon = $message->getIcon()) {
            $payload[$message->getIconType()] = $icon;
        }

        if (count($message->getBlocks())) {
            $payload['blocks'] = $message->getBlocksAsArrays();
        } else {
            $payload['attachments'] = $this->getAttachmentsAsArrays($message);
        }

        return $payload;
    }

    /**
     * Get the attachments in array form.
     *
     * @param \Maknz\Slack\Message $message
     *
     * @return array
     */
    protected function getAttachmentsAsArrays(Message $message)
    {
        $attachments = [];

        foreach ($message->getAttachments() as $attachment) {
            $attachments[] = $attachment->toArray();
        }

        return $attachments;
    }

    /**
     * @param array $options
     *
     * @return \Maknz\Slack\Client
     */
    public function setOptions(array $options)
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }

        return $this;
    }

    /**
     * @param $option
     * @param $value
     */
    public function setOption($option, $value)
    {
        $setter = self::getOptionSetter($option);
        if ($setter !== null) {
            $this->$setter($value);
        }
    }
}
