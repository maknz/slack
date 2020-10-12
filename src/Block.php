<?php namespace Maknz\Slack;

abstract class Block
{
    protected $blockId;

    public function __construct(array $attributes)
    {
        if (isset($attributes['block_id'])) {
            $this->setBlockId($attributes['block_id']);
        }
    }

    public abstract function toArray();

    public function setBlockId($blockId)
    {
        $this->blockId = $blockId;
    }

    public function getBlockId()
    {
        $blockId = $this->blockId;

        // If it's null, generate a unique one
        if ($blockId == null) {
            $blockId = sha1(random_bytes(32));
            $this->setBlockId($blockId);
        }
        return $blockId;
    }
}