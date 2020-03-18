<?php
namespace Maknz\Slack\Object;

use Maknz\Slack\BlockElement\Text;

class Option extends CompositionObject
{
    /**
     * Option text.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $text;

    /**
     * Option value.
     *
     * @var string
     */
    protected $value;

    /**
     * Option group description.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $description;

    /**
     * URL to be loaded when the option is clicked.
     *
     * @var string
     */
    protected $url;

    /**
     * Whether this option is initially selected.
     *
     * @var bool
     */
    protected $initially_selected = false;

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'text'        => 'text',
        'value'       => 'value',
        'description' => 'description',
        'url'         => 'url',
        'selected'    => 'initially_selected',
    ];

    /**
     * Get the option text.
     *
     * @return \Maknz\Slack\BlockElement\Text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the option text.
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
     * Get the option value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the option value.
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
     * Get the option description.
     *
     * @return \Maknz\Slack\BlockElement\Text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the option description.
     *
     * @param mixed $description
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setDescription($description)
    {
        $this->description = Text::create($description, Text::TYPE_PLAIN);

        return $this;
    }

    /**
     * Get the option URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the option URL.
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
     * Get whether the option group has a selected option.
     *
     * @return bool
     */
    public function isInitiallySelected()
    {
        return $this->initially_selected;
    }

    /**
     * Set whether the option group has a selected option.
     *
     * @param bool $selected
     *
     * @return $this
     */
    public function setInitiallySelected($selected)
    {
        $this->initially_selected = (bool)$selected;

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
            'text'    => $this->getText()->toArray(),
            'value'   => $this->getValue(),
        ];

        if ($this->getDescription()) {
            $data['description'] = $this->getDescription()->toArray();
        }

        if ($this->getUrl()) {
            $data['url'] = $this->getUrl();
        }

        return $data;
    }
}
