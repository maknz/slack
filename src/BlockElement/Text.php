<?php
namespace Maknz\Slack\BlockElement;

use InvalidArgumentException;
use Maknz\Slack\BlockElement;
use Maknz\Slack\Field;

class Text extends BlockElement implements Field
{
    const TYPE_PLAIN = 'plain_text';
    const TYPE_MARKDOWN = 'mrkdwn';

    /**
     * Text format type.
     *
     * @var string
     */
    protected $type;

    /**
     * Text content.
     *
     * @var string
     */
    protected $text;

    /**
     * Whether emojis should be escaped.
     *
     * @var bool
     */
    protected $escape_emojis = false;

    /**
     * Whether text content should be treated as-is.
     *
     * @var bool
     */
    protected $verbatim = false;

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'type'     => 'type',
        'text'     => 'text',
        'emoji'    => 'escape_emojis',
        'verbatim' => 'verbatim',
    ];

    /**
     * Get the text format type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the text format type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the text content.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the text content.
     *
     * @param string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get whether to escape emojis.
     *
     * @return bool
     */
    public function getEscapeEmojis()
    {
        return $this->escape_emojis;
    }

    /**
     * Set whether to escape emojis.
     *
     * @param bool $escape
     *
     * @return $this
     */
    public function setEscapeEmojis($escape)
    {
        $this->escape_emojis = (bool)$escape;

        return $this;
    }

    /**
     * Get whether to treat text as-is.
     *
     * @return bool
     */
    public function getVerbatim()
    {
        return $this->verbatim;
    }

    /**
     * Set whether to treat text as-is.
     *
     * @param bool $verbatim
     *
     * @return $this
     */
    public function setVerbatim($verbatim)
    {
        $this->verbatim = (bool)$verbatim;

        return $this;
    }

    /**
     * Convert the block to its array representation.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'type' => $this->getType(),
            'text' => $this->getText(),
        ];

        if ($data['type'] == static::TYPE_PLAIN) {
            $data['emoji'] = $this->getEscapeEmojis();
        } else {
            $data['verbatim'] = $this->getVerbatim();
        }

        return $data;
    }

    /**
     * Create a Text block from various formats.
     *
     * @param mixed  $text
     * @param string $type If passed, the Text object will be of this type.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public static function create($text, $type = null)
    {
        if (is_string($text)) {
            $text = [
                'type' => $type ?? static::TYPE_PLAIN,
                'text' => $text,
            ];
        }

        if (is_array($text)) {
            $text = new static($text);
        }

        if ($text instanceof static) {
            if ($type && $text->getType() != $type) {
                throw new InvalidArgumentException('Text type must be '.$type);
            }

            return $text;
        }

        throw new InvalidArgumentException('Text must be a string, keyed array or '.static::class.' object');
    }
}
