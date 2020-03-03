<?php
namespace Maknz\Slack\Block;

use Maknz\Slack\Block;
use Maknz\Slack\ImageTrait;

class Image extends Block
{
    use ImageTrait;

    /**
     * Block type.
     *
     * @var string
     */
    protected $type = 'image';

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'image_url' => 'url',
        'alt_text'  => 'alt_text',
        'title'     => 'title',
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
            'image_url' => $this->getUrl(),
            'alt_text' => $this->getAltText(),
        ];

        if ($this->getTitle()) {
            $data['title'] = $this->getTitle()->toArray();
        }

        if ($this->getBlockId()) {
            $data['block_id'] = $this->getBlockId();
        }

        return $data;
    }
}
