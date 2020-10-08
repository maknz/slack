<?php namespace Maknz\Slack\Block;

class Action extends \Maknz\Slack\Block
{
    protected $type = 'action';
    protected $elements = [];
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (isset($attributes['elements'])) {
            $this->setElements($attributes['elements']);
        }
    }

    public function setElements($elements)
    {
        $this->elements = $elements;
    }
    public function getElements()
    {
        return $this->elements;
    }

    public function getElementsAsArray()
    {
        $elements = [];
        foreach ($this->getElements() as $element) {
            $elements[] = $element->toArray();
        }

        return $elements;
    }

    public function toArray()
    {
        return [
            'type' => 'actions',
            'elements' => $this->getElementsAsArray(),
            'block_id' => $this->getBlockId()
        ];
    }
}