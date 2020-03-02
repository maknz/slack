<?php
namespace Maknz\Slack;

use InvalidArgumentException;

abstract class BlockElement extends Payload
{
    /**
     * Element type.
     *
     * @var string
     */
    protected $type;

    /**
     * List of blocks each element is valid for.
     *
     * @var array
     */
    protected static $validFor = [
        'button'              => ['Button',       ['section', 'actions']],
        'checkbox'            => ['Checkbox',     ['section', 'actions', 'input']],
        'datepicker'          => ['DatePicker',   ['section', 'actions', 'input']],
        'image'               => ['Image',        ['section', 'context']],
        'multi_static_select' => ['MultiSelect',  ['section', 'input']],
        'overflow'            => ['Overflow',     ['section', 'actions']],
        'plain_text_input'    => ['TextInput',    ['section', 'actions', 'input']],
        'radio_buttons'       => ['RadioButtons', ['section', 'actions', 'input']],
        'static_select'       => ['Select',       ['section', 'actions', 'input']],

        // Context Block allows a Text object to be used directly, so need to map types here
        'plain_text'          => ['Text', ['context']],
        'mrkdwn'              => ['Text', ['context']],
    ];

    /**
     * Create a Block element from a keyed array of attributes.
     *
     * @param mixed $attributes
     *
     * @return BlockElement
     *
     * @throws \InvalidArgumentException
     */
    public static function factory($attributes)
    {
        if ($attributes instanceof static) {
            return $attributes;
        }

        if ( ! is_array($attributes)) {
            throw new InvalidArgumentException('The attributes must be a '.static::class.' or keyed array');
        }

        if ( ! isset($attributes['type'])) {
            throw new InvalidArgumentException('Cannot create BlockElement without a type attribute');
        }

        $validElements = array_keys(static::$validFor);

        if (!in_array($attributes['type'], $validElements)) {
            throw new InvalidArgumentException('Block type must be one of: ' . implode(', ', $validElements) . '.');
        }

        $className = __NAMESPACE__.'\\BlockElement\\'.static::$validFor[$attributes['type']][0];

        return new $className($attributes);
    }

    /**
     * Check if an element is valid for a Block.
     *
     * @param Block $block
     *
     * @return bool
     */
    public function isValidFor(Block $block)
    {
        $blockType = $block->getType();
        $validBlocks = static::$validFor[$this->getType()][1];

        return in_array($blockType, $validBlocks);
    }

    /**
     * Get the block type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
