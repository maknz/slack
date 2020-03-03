<?php
namespace Maknz\Slack\Block;

use Maknz\Slack\Block;

class File extends Block
{
    /**
     * Block type.
     *
     * @var string
     */
    protected $type = 'file';

    /**
     * External identifier for the file.
     *
     * @var string
     */
    protected $external_id;

    /**
     * Source for the file.
     *
     * @var string
     */
    protected $source = 'remote';

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'external_id' => 'external_id',
        'source'      => 'source',
        'block_id'    => 'block_id',
    ];

    /**
     * Get the external identifier for the file.
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->external_id;
    }

    /**
     * Set the external identifier for the file.
     *
     * @param string $externalId
     *
     * @return File
     */
    public function setExternalId($externalId)
    {
        $this->external_id = $externalId;

        return $this;
    }

    /**
     * Get the file source.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set the file source.
     *
     * @param string $source
     *
     * @return File
     */
    public function setSource($source)
    {
        $this->source = $source;

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
            'type'        => $this->getType(),
            'external_id' => $this->getExternalId(),
            'source'      => $this->getSource(),
        ];

        if ($this->getBlockId()) {
            $data['block_id'] = $this->getBlockId();
        }

        return $data;
    }
}
