<?php
namespace Maknz\Slack\Block;

use InvalidArgumentException;
use Maknz\Slack\Block;
use Maknz\Slack\BlockElement;
use Maknz\Slack\BlockElement\Text;

class Input extends Block
{
    /**
     * Block type.
     *
     * @var string
     */
    protected $type = 'input';

    /**
     * Label that appears above the input.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $label;

    /**
     * Input element.
     *
     * @var \Maknz\Slack\BlockElement
     */
    protected $element;

    /**
     * A hint that appears below the input.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $hint;

    /**
     * Whether the input may be empty.
     *
     * @var bool
     */
    protected $optional = false;

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'label'    => 'label',
        'element'  => 'element',
        'hint'     => 'hint',
        'optional' => 'optional',
    ];

    /**
     * Get the input label.
     *
     * @return \Maknz\Slack\BlockElement\Text
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the input label.
     *
     * @param mixed
     *
     * @return Input
     */
    public function setLabel($label)
    {
        $this->label = Text::create($label);

        return $this;
    }

    /**
     * Get the input element.
     *
     * @return \Maknz\Slack\BlockElement
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Set the input element.
     *
     * @param mixed
     *
     * @return Input
     *
     * @throws \InvalidArgumentException
     */
    public function setElement($element)
    {
        $element = BlockElement::factory($element);

        if ( ! $element->isValidFor($this)) {
            throw new InvalidArgumentException('Block element '.get_class($element).' is not valid for '.static::class);
        }

        $this->element = $element;

        return $this;
    }

    /**
     * Get the input hint.
     *
     * @return \Maknz\Slack\BlockElement\Text
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * Set the input hint.
     *
     * @param mixed
     *
     * @return Input
     */
    public function setHint($hint)
    {
        $this->hint = Text::create($hint);

        return $this;
    }

    /**
     * Get whether the input is optional.
     *
     * @return bool
     */
    public function getOptional()
    {
        return $this->optional;
    }

    /**
     * Set whether the input is optional.
     *
     * @param bool
     *
     * @return Input
     */
    public function setOptional($optional)
    {
        $this->optional = (bool)$optional;

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
            'type'    => $this->getType(),
            'label'   => $this->getLabel()->toArray(),
            'element' => $this->getElement()->toArray(),
        ];

        if ($this->getBlockId()) {
            $data['block_id'] = $this->getBlockId();
        }

        if ($this->getHint()) {
            $data['hint'] = $this->getHint()->toArray();
        }

        if ($this->getOptional() != null) {
            $data['optional'] = $this->getOptional();
        }

        return $data;
    }
}
