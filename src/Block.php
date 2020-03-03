<?php
namespace Maknz\Slack;

use InvalidArgumentException;

abstract class Block extends Payload
{
    /**
     * Block type.
     *
     * @var string
     */
    protected $type;

    /**
     * Block identifier.
     *
     * @var string
     */
    protected $block_id;

    /**
     * Get the block type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the block identifier.
     *
     * @return string
     */
    public function getBlockId()
    {
        return $this->block_id;
    }

    /**
     * Set the block identifier.
     *
     * @param string $blockId
     *
     * @return Block
     */
    public function setBlockId($blockId)
    {
        $this->block_id = $blockId;

        return $this;
    }

    /**
     * Create a Block element from a keyed array of attributes.
     *
     * @param array $attributes
     *
     * @return Block
     *
     * @throws \InvalidArgumentException
     */
    public static function factory(array $attributes)
    {
        if ( ! isset($attributes['type'])) {
            throw new InvalidArgumentException('Cannot create Block without a type attribute');
        }

        $validBlocks = [
            'actions',
            'context',
            'divider',
            'file',
            'image',
            'input',
            'section',
        ];

        if ( ! in_array($attributes['type'], $validBlocks)) {
            throw new InvalidArgumentException('Block type must be one of: '.implode(', ', $validBlocks).'.');
        }

        $className = __NAMESPACE__.'\\Block\\'.ucfirst($attributes['type']);

        return new $className($attributes);
    }
}
