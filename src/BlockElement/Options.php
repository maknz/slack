<?php
namespace Maknz\Slack\BlockElement;

use InvalidArgumentException;
use Maknz\Slack\BlockElement;
use Maknz\Slack\Object\Option;

abstract class Options extends Confirmable
{
    protected $action_id;

    protected $options;

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
}
