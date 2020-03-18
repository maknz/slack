<?php
namespace Maknz\Slack\BlockElement;

class Button extends Confirmable
{
    const STYLE_DEFAULT = 'default';
    const STYLE_PRIMARY = 'primary';
    const STYLE_DANGER = 'danger';

    /**
     * Block type.
     *
     * @var string
     */
    protected $type = 'button';

    /**
     * Button text.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $text;

    /**
     * Button action.
     *
     * @var string
     */
    protected $action_id;

    /**
     * Button URL.
     *
     * @var string
     */
    protected $url;

    /**
     * Button value.
     *
     * @var string
     */
    protected $value;

    /**
     * Button style.
     *
     * @var string
     */
    protected $style;

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'text'      => 'text',
        'action_id' => 'action_id',
        'url'       => 'url',
        'value'     => 'value',
        'style'     => 'style',
        'confirm'   => 'confirm',
    ];

    /**
     * Get the button text.
     *
     * @return \Maknz\Slack\BlockElement\Text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the button text.
     *
     * @param mixed $text
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setText($text)
    {
        $this->text = Text::create($text, Text::TYPE_PLAIN);

        return $this;
    }

    /**
     * Get the button action.
     *
     * @return string
     */
    public function getActionId()
    {
        return $this->action_id;
    }

    /**
     * Set the button action.
     *
     * @param string $actionId
     *
     * @return $this
     */
    public function setActionId($actionId)
    {
        $this->action_id = $actionId;

        return $this;
    }

    /**
     * Get the button URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the button URL.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the button value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the button value.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get the button style.
     *
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set the button style.
     *
     * @param string $style
     *
     * @return $this
     */
    public function setStyle($style)
    {
        $this->style = $style;

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
            'type'      => $this->getType(),
            'text'      => $this->getText()->toArray(),
            'action_id' => $this->getActionId(),
        ];

        if ($this->getUrl()) {
            $data['url'] = $this->getUrl();
        }

        if ($this->getValue()) {
            $data['value'] = $this->getValue();
        }

        if ($this->getStyle()) {
            $data['style'] = $this->getStyle();
        }

        if ($this->getConfirm()) {
            $data['confirm'] = $this->getConfirm()->toArray();
        }

        return $data;
    }
}
