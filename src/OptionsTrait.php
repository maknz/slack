<?php
namespace Maknz\Slack;

use InvalidArgumentException;
use Maknz\Slack\Object\Option;

trait OptionsTrait
{
    /**
     * Options available within the block.
     *
     * @var \Maknz\Slack\Object\Option[]
     */
    protected $options = [];

    /**
     * Get options available within the block.
     *
     * @return \Maknz\Slack\Object\Option[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get options available within the block in array format.
     *
     * @return array
     */
    public function getOptionsAsArrays()
    {
        $options = [];

        foreach ($this->getOptions() as $option) {
            $options[] = $option->toArray();
        }

        return $options;
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
        $this->clearOptions();

        foreach ($options as $option) {
            $this->addOption($option);
        }

        return $this;
    }

    /**
     * Clear options available within the block.
     *
     * @return $this
     */
    public function clearOptions()
    {
        $this->options = [];

        return $this;
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
        if (is_array($option)) {
            $option = new Option($option);
        }

        if ($option instanceof Option) {
            $this->options[] = $option;

            return $this;
        }

        throw new InvalidArgumentException('The option must be an instance of '.Option::class.' or a keyed array');
    }
}
