<?php
namespace Maknz\Slack\BlockElement;

use InvalidArgumentException;
use Maknz\Slack\Object\OptionGroup;

abstract class AbstractSelect extends Options
{
    /**
     * Select placeholder.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $placeholder;

    /**
     * Select option groups.
     *
     * @var \Maknz\Slack\Object\OptionGroup[]
     */
    protected $option_groups = [];

    /**
     * Get the placeholder.
     *
     * @return \Maknz\Slack\BlockElement\Text
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Set the placeholder.
     *
     * @param mixed $placeholder
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = Text::create($placeholder, Text::TYPE_PLAIN);

        return $this;
    }

    /**
     * Set options available within the block.
     *
     * @param array $options
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setOptions(array $options)
    {
        $this->clearOptionGroups();

        return parent::setOptions($options);
    }

    /**
     * Add an option to the block.
     *
     * @param mixed $option
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function addOption($option)
    {
        parent::addOption($option);
        $this->clearOptionGroups();

        return $this;
    }

    /**
     * Get the option groups.
     *
     * @return \Maknz\Slack\Object\OptionGroup[]
     */
    public function getOptionGroups()
    {
        return $this->option_groups;
    }

    /**
     * Get the option groups in array format.
     *
     * @return array
     */
    public function getOptionGroupsAsArrays()
    {
        $groups = [];

        foreach ($this->getOptionGroups() as $group) {
            $groups[] = $group->toArray();
        }

        return $groups;
    }

    /**
     * Set the option groups.
     *
     * @param array $groups
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
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
     * Clear option groups in the block.
     *
     * @return $this
     */
    public function clearOptionGroups()
    {
        $this->option_groups = [];

        return $this;
    }

    /**
     * Clear options and option groups.
     *
     * @return $this
     */
    public function clearAllOptions()
    {
        $this->clearOptions();
        $this->clearOptionGroups();

        return $this;
    }

    /**
     * Add an option group to the block.
     *
     * @param mixed $group
     *
     * @return $this
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
            $this->option_groups[] = $group;

            return $this;
        }

        throw new InvalidArgumentException('The option group must be an instance of '.OptionGroup::class.' or a keyed array');
    }
}
