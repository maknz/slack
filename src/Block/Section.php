<?php
namespace Maknz\Slack\Block;

use InvalidArgumentException;
use Maknz\Slack\Block;
use Maknz\Slack\BlockElement;
use Maknz\Slack\BlockElement\Text;
use Maknz\Slack\FieldsTrait;

class Section extends Block
{
    use FieldsTrait;

    /**
     * Block type.
     *
     * @var string
     */
    protected $type = 'section';

    /**
     * The text for the section.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $text;

    /**
     * Fields to appear in the section.
     *
     * @var \Maknz\Slack\BlockElement\Text[]
     */
    protected $fields = [];

    /**
     * Block element to be included in the section.
     *
     * @var \Maknz\Slack\BlockElement
     */
    protected $accessory;

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'text'      => 'text',
        'block_id'  => 'block_id',
        'fields'    => 'fields',
        'accessory' => 'accessory',
    ];

    /**
     * Class name of valid fields.
     *
     * @var string
     */
    protected static $fieldClass = Text::class;

    /**
     * Get the section text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the section text.
     *
     * @param mixed $text
     *
     * @return Section
     *
     * @throws \InvalidArgumentException
     */
    public function setText($text)
    {
        $this->text = Text::create($text);

        return $this;
    }

    /**
     * Add a field to the block.
     *
     * @param mixed $field
     *
     * @return Section
     *
     * @throws \InvalidArgumentException
     */
    public function addField($field)
    {
        $field = static::$fieldClass::create($field);

        $this->fields[] = $field;

        return $this;
    }

    /**
     * Get the section accessory.
     *
     * @return \Maknz\Slack\BlockElement
     */
    public function getAccessory()
    {
        return $this->accessory;
    }

    /**
     * Set the section accessory.
     *
     * @param mixed $accessory
     *
     * @return Section
     *
     * @throws \InvalidArgumentException
     */
    public function setAccessory($accessory)
    {
        $accessory = BlockElement::factory($accessory);

        if ( ! $accessory->isValidFor($this)) {
            throw new InvalidArgumentException('Block element '.get_class($accessory).' is not valid for '.static::class);
        }

        $this->accessory = $accessory;

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
            'type' => $this->getType(),
            'text' => $this->getText()->toArray(),
        ];

        if ($this->getBlockId()) {
            $data['block_id'] = $this->getBlockId();
        }

        if (count($this->getFields())) {
            $data['fields'] = $this->getFieldsAsArrays();
        }

        if ($this->getAccessory()) {
            $data['accessory'] = $this->getAccessory()->toArray();
        }

        return $data;
    }
}
