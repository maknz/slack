<?php
namespace Maknz\Slack\BlockElement;

use InvalidArgumentException;
use Maknz\Slack\Object\OptionGroup;
use Maknz\Slack\BlockElement\Text;

class Select extends Options
{
    /**
     * Block type.
     *
     * @var string
     */
    protected $type = 'static_select';

    protected $placeholder;

    protected $option_groups;

    /**
     * Whether one of the options is initially selected.
     *
     * @var bool
     */
    protected $hasInitialOption = false;

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'placeholder'   => 'placeholder',
        'action_id'     => 'action_id',
        'options'       => 'options',
        'option_groups' => 'option_groups',
        'confirm'       => 'confirm',
    ];

    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    public function setPlaceholder($placeholder)
    {
        $this->placeholder = Text::create($placeholder, Text::TYPE_PLAIN);

        return $this;
    }

    public function setOptions(array $options)
    {
        $this->clearOptionGroups();

        return parent::setOptions($options);
    }

    /**
     * Add an option to the select.
     *
     * @param mixed $option
     *
     * @return Select
     *
     * @throws \InvalidArgumentException
     */
    public function addOption($option)
    {
        if ((is_array($option) && ! empty($option['selected']))
            || ($option instanceof Option && ! $option->isInitiallySelected())
        ) {
            if ($this->hasInitialOption) {
                throw new InvalidArgumentException('Only one option can be initially selected');
            }

            $this->hasInitialOption = true;
        }

        parent::addOption($option);
        $this->clearOptionGroups();

        return $this;
    }

    public function getOptionGroups()
    {
        return $this->option_groups;
    }

    public function getOptionGroupsAsArrays()
    {
        $groups = [];

        foreach ($this->getOptionGroups() as $group) {
            $groups[] = $group->toArray();
        }

        return $groups;
    }

    public function setOptionGroups(array $groups)
    {
        $this->clearOptions();
        $this->clearOptionGroups();

        foreach ($groups as $group) {
            $this->addOptionGroup($group);
        }

        return $this;
    }

    /**
     * Clear option groups in the select.
     *
     * @return Select
     */
    public function clearOptionGroups()
    {
        if (count($this->getOptions()) == 0) {
            $this->hasSelectedOption = false;
        }

        $this->option_groups = [];

        return $this;
    }

    /**
     * Clear options in the select.
     *
     * @return Select
     */
    public function clearOptions()
    {
        if (count($this->getOptionGroups()) == 0) {
            $this->hasSelectedOption = false;
        }

        return parent::clearOptions();
    }

    public function clearAllOptions() {
        $this->clearOptions();
        $this->clearOptionGroups();

        return $this;
    }

    /**
     * Add an option group to the select.
     *
     * @param mixed $group
     *
     * @return AbstractSelect
     *
     * @throws \InvalidArgumentException
     */
    public function addOptionGroup($group)
    {
        if (is_array($group)) {
            $group = new OptionGroup($group);
        }

        if ($group instanceof OptionGroup) {
            $this->clearOptions();

            if ($group->hasSelectedOption()) {
                if ($this->hasInitialOption) {
                    throw new InvalidArgumentException('Only one option can be initially selected');
                }

                $this->hasInitialOption = true;
            }

            $this->option_groups[] = $group;

            return $this;
        }

        throw new InvalidArgumentException('The option group must be an instance of ' . OptionGroup::class . ' or a keyed array');
    }

    /**
     * Get the intially selected option.
     *
     * @return Maknz\Slack\Object\Option
     */
    public function getInitialOption()
    {
        foreach ($this->getOptions() as $option) {
            if ($option->isInitiallySelected()) {
                return $option;
            }
        }

        foreach ($this->getOptionGroups() as $group) {
            foreach ($group->getOptions() as $option) {
                if ($option->isInitiallySelected()) {
                    return $option;
                }
            }
        }
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

        if ($this->hasInitialOption) {
            $data['initial_option'] = $this->getInitialOption()->toArray();
        }

        if ($this->getConfirm()) {
            $data['confirm'] = $this->getConfirm()->toArray();
        }

        return $data;
    }
}
