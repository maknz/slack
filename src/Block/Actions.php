<?php
namespace Maknz\Slack\Block;

use InvalidArgumentException;

use Maknz\Slack\Block;
use Maknz\Slack\BlockElement;

class Actions extends Block
{
    protected $type = 'actions';

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

    public function getElements()
    {
        return $this->elements;
    }

    public function getElementsAsArrays()
    {
        $elements = [];

        foreach ($this->getElements() as $element) {
            $elements[] = $element->toArray();
        }

        return $elements;
    }

    public function setElements(array $elements)
    {
        $this->clearElements();

        foreach ($elements as $element) {
            $this->addElement($element);
        }

        return $this;
    }

    public function clearElements()
    {
        $this->elements = [];
    }

    public function addElement($element)
    {
        $element = BlockElement::factory($element);

        if (!$element->isValidFor($this)) {
            throw new InvalidArgumentException('Block element ' . get_class($element) . ' is not valid for ' . static::class);
        }

        $this->elements[] = $element;

        return $this;
    }

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
