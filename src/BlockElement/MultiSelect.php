<?php
namespace Maknz\Slack\BlockElement;

use Maknz\Slack\Object\Option;

class MultiSelect extends AbstractSelect
{
    /**
     * Block type.
     *
     * @var string
     */
    protected $type = 'multi_static_select';

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'placeholder'        => 'placeholder',
        'action_id'          => 'action_id',
        'options'            => 'options',
        'option_groups'      => 'option_groups',
        'confirm'            => 'confirm',
        'max_selected_items' => 'max_selected_items',
    ];

    /**
     * Get the intially selected options.
     *
     * @return \Maknz\Slack\Object\Option[]
     */
    public function getInitialOptions()
    {
        $initialOptions = [];

        foreach ($this->getOptions() as $option) {
            if ($option->isInitiallySelected()) {
                $initialOptions[] = $option;
            }
        }

        foreach ($this->getOptionGroups() as $group) {
            foreach ($group->getOptions() as $option) {
                if ($option->isInitiallySelected()) {
                    $initialOptions[] = $option;
                }
            }
        }

        return $initialOptions;
    }

    /**
     * Convert the block to its array representation.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'type'        => $this->getType(),
            'placeholder' => $this->getPlaceholder()->toArray(),
            'action_id'   => $this->getActionId(),
        ];

        if (count($this->getOptions())) {
            $data['options'] = $this->getOptionsAsArrays();
        } else {
            $data['option_groups'] = $this->getOptionGroupsAsArrays();
        }

        $initialOptions = $this->getInitialOptions();

        if (count($initialOptions)) {
            $data['initial_options'] = array_map(function (Option $o) {
                return $o->toArray();
            }, $initialOptions);
        }

        if ($this->getConfirm()) {
            $data['confirm'] = $this->getConfirm()->toArray();
        }

        return $data;
    }
}
