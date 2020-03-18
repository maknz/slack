<?php
namespace Maknz\Slack\Block;

use InvalidArgumentException;
use Maknz\Slack\Block;
use Maknz\Slack\BlockElement;

abstract class ElementsBlock extends Block
{
    /**
     * Elements to be displayed within the block.
     *
     * @var BlockElement[]
     */
    protected $elements = [];

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'elements' => 'elements',
        'block_id' => 'block_id',
    ];

    /**
     * Get the elements included in the block.
     *
     * @return BlockElement[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Get the elements included in the block in an array representation.
     *
     * @return array
     */
    public function getElementsAsArrays()
    {
        $elements = [];

        foreach ($this->getElements() as $element) {
            $elements[] = $element->toArray();
        }

        return $elements;
    }

    /**
     * Set the elements included in the block.
     *
     * @param array $elements
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setElements(array $elements)
    {
        $this->clearElements();

        foreach ($elements as $element) {
            $this->addElement($element);
        }

        return $this;
    }

    /**
     * Remove all elements from the block.
     *
     * @return $this
     */
    public function clearElements()
    {
        $this->elements = [];

        return $this;
    }

    /**
     * Add an element to the block.
     *
     * @param array|BlockElement $element
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function addElement($element)
    {
        $element = BlockElement::factory($element);

        if ( ! $element->isValidFor($this)) {
            throw new InvalidArgumentException('Block element '.get_class($element).' is not valid for '.static::class);
        }

        $this->elements[] = $element;

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
            'type'     => $this->getType(),
            'elements' => $this->getElementsAsArrays(),
        ];

        if ($this->getBlockId()) {
            $data['block_id'] = $this->getBlockId();
        }

        return $data;
    }
}
