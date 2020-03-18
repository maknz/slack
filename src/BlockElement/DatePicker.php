<?php
namespace Maknz\Slack\BlockElement;

use DateTime;

class DatePicker extends Confirmable
{
    /**
     * Block type.
     *
     * @var string
     */
    protected $type = 'datepicker';

    /**
     * Action triggered when the date is selected.
     *
     * @var string
     */
    protected $action_id;

    /**
     * Placeholder shown on the date picker.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $placeholder;

    /**
     * Initial date to be selected.
     *
     * @var \DateTime
     */
    protected $initial_date;

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'action_id'    => 'action_id',
        'placeholder'  => 'placeholder',
        'initial_date' => 'initial_date',
        'confirm'      => 'confirm',
    ];

    /**
     * Get the action.
     *
     * @return string
     */
    public function getActionId()
    {
        return $this->action_id;
    }

    /**
     * Set the action.
     *
     * @param string $actionId
     *
     * @return $this
     */
    public function setActionId($actionId)
    {
        $this->action_id = $actionId;

        return $this;
    }

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
     * Get the initial date.
     *
     * @return \DateTime
     */
    public function getInitialDate()
    {
        return $this->initial_date;
    }

    /**
     * Set the initial date.
     *
     * @param \DateTime $initialDate
     *
     * @return $this
     */
    public function setInitialDate(DateTime $initialDate)
    {
        $this->initial_date = $initialDate;

        return $this;
    }

    /**
     * Convert the block to its array representation.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'type'      => $this->getType(),
            'action_id' => $this->getActionId(),
        ];

        if ($this->getPlaceholder()) {
            $data['placeholder'] = $this->getPlaceholder()->toArray();
        }

        if ($this->getInitialDate()) {
            $data['initial_date'] = $this->getInitialDate()->format('Y-m-d');
        }

        if ($this->getConfirm()) {
            $data['confirm'] = $this->getConfirm()->toArray();
        }

        return $data;
    }
}
