<?php
namespace Maknz\Slack\Block;

use Maknz\Slack\Block;

class Divider extends Block
{
    /**
     * Block type.
     *
     * @var string
     */
    protected $type = 'divider';

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'block_id'  => 'block_id',
    ];

    /**
     * Convert the block to its array representation.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'type' => $this->getType(),
        ];

        if ($this->getBlockId()) {
            $data['block_id'] = $this->getBlockId();
        }

        return $data;
    }
}
