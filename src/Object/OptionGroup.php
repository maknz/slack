<?php
namespace Maknz\Slack\Object;

use Maknz\Slack\BlockElement\Text;
use Maknz\Slack\OptionsTrait;

class OptionGroup extends CompositionObject
{
    use OptionsTrait;

    /**
     * Option group label.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $label;

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'label'   => 'label',
        'options' => 'options',
    ];

    /**
     * Get the option group label.
     *
     * @return \Maknz\Slack\BlockElement\Text
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the option group label.
     *
     * @param mixed $label
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setLabel($label)
    {
        $this->label = Text::create($label, Text::TYPE_PLAIN);

        return $this;
    }

    /**
     * Get whether the option group has a selected option.
     *
     * @return bool
     */
    public function hasSelectedOption()
    {
        foreach ($this->getOptions() as $option) {
            if ($option->isInitiallySelected()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Convert the block to its array representation.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'label'   => $this->getLabel()->toArray(),
            'options' => $this->getOptionsAsArrays(),
        ];

        return $data;
    }
}
