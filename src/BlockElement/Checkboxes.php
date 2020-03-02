<?php
namespace Maknz\Slack\BlockElement;

use InvalidArgumentException;
use Maknz\Slack\BlockElement;
use Maknz\Slack\Object\Option;

class Checkboxes extends Confirmable
{
    /**
     * Block type.
     *
     * @var string
     */
    protected $type = 'checkboxes';

    protected $action_id;

    protected $options;

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

    public function getActionId()
    {
        return $this->action_id;
    }

    public function setActionId($actionId)
    {
        $this->action_id = $actionId;

        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getOptionsAsArrays()
    {
        $options = [];

        foreach ($this->getOptions() as $option) {
            $options[] = $option->toArray();
        }

        return $options;
    }

    public function setOptions(array $options)
    {
        $this->clearOptions();

        foreach ($options as $option) {
            $this->addOption($option);
        }

        return $this;
    }

    public function clearOptions()
    {
        $this->options = [];

        return $this;
    }

    public function addOption($option)
    {
        if (is_array($option)) {
            $option = new Option($option);
        }

        if ($option instanceof Option) {
            $this->options[] = $option;

            return $this;
        }

        throw new InvalidArgumentException('The option must be an instance of ' . Option::class . ' or a keyed array');
    }

    /**
     * Get the intially selected options.
     *
     * @return Maknz\Slack\Object\Option[]
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
