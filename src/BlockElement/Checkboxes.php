<?php
namespace Maknz\Slack\BlockElement;

use Maknz\Slack\Object\Option;

class Checkboxes extends Options
{
    /**
     * Block type.
     *
     * @var string
     */
    protected $type = 'checkboxes';

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'action_id' => 'action_id',
        'options'   => 'options',
        'confirm'   => 'confirm',
    ];

    /**
     * Get the intially selected options.
     *
     * @return \Maknz\Slack\Object\Option[]
     */
    public function getInitialOptions()
    {
        return array_values(array_filter($this->getOptions(), function (Option $o) {
            return $o->isInitiallySelected();
        }));
    }

    /**
     * Convert the block to its array representation.
     *
     * @return array
     */
    public function toArray()
    {
        $initialOptions = [];

        $data = [
            'type'      => $this->getType(),
            'action_id' => $this->getActionId(),
            'options'   => $this->getOptionsAsArrays(),
        ];

        foreach ($this->getOptions() as $option) {
            if ($option->isInitiallySelected()) {
                $initialOptions[] = $option->toArray();
            }
        }

        if (count($initialOptions)) {
            $data['initial_options'] = $initialOptions;
        }

        if ($this->getConfirm()) {
            $data['confirm'] = $this->getConfirm()->toArray();
        }

        return $data;
    }
}
