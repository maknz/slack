<?php
namespace Maknz\Slack\BlockElement;

class Overflow extends Options
{
    /**
     * Block type.
     *
     * @var string
     */
    protected $type = 'overflow';

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
     * Convert the block to its array representation.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'type'      => $this->getType(),
            'action_id' => $this->getActionId(),
            'options'   => $this->getOptionsAsArrays(),
        ];

        if ($this->getConfirm()) {
            $data['confirm'] = $this->getConfirm()->toArray();
        }

        return $data;
    }
}
