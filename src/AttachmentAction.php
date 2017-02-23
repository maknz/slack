<?php

namespace Maknz\Slack;

use InvalidArgumentException;

class AttachmentAction
{
    const TYPE_BUTTON = 'button';

    const STYLE_DEFAULT = 'default';
    const STYLE_PRIMARY = 'primary';
    const STYLE_DANGER = 'danger';

    /**
     * The required name field of the action. The name will be returned to your Action URL.
     *
     * @var string
     */
    protected $name;

    /**
     * The required label for the action.
     *
     * @var string
     */
    protected $text;

    /**
     * Button style.
     *
     * @var string
     */
    protected $style;

    /**
     * The required type of the action.
     *
     * @var string
     */
    protected $type = self::TYPE_BUTTON;

    /**
     * Optional value. It will be sent to your Action URL.
     *
     * @var string
     */
    protected $value;

    /**
     * Confirmation field.
     *
     * @var ActionConfirmation
     */
    protected $confirm;

    /**
     * Instantiate a new AttachmentAction.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes)
    {
        if (isset($attributes['name'])) {
            $this->setName($attributes['name']);
        }

        if (isset($attributes['text'])) {
            $this->setText($attributes['text']);
        }

        if (isset($attributes['style'])) {
            $this->setStyle($attributes['style']);
        }

        if (isset($attributes['type'])) {
            $this->setType($attributes['type']);
        }

        if (isset($attributes['value'])) {
            $this->setValue($attributes['value']);
        }

        if (isset($attributes['confirm'])) {
            $this->setConfirm($attributes['confirm']);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return AttachmentAction
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return AttachmentAction
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param string $style
     * @return AttachmentAction
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return AttachmentAction
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return AttachmentAction
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return ActionConfirmation
     */
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     * @param ActionConfirmation|array $confirm
     * @return AttachmentAction
     */
    public function setConfirm($confirm)
    {
        if ($confirm instanceof ActionConfirmation) {
            $this->confirm = $confirm;

            return $this;
        } elseif (is_array($confirm)) {
            $this->confirm = new ActionConfirmation($confirm);

            return $this;
        } elseif (! isset($confirm)) {
            $this->confirm = null;

            return $this;
        }

        throw new InvalidArgumentException('The action confirmation must be an instance of Maknz\Slack\ActionConfirmation or a keyed array');
    }

    /**
     * Get the array representation of this attachment action.
     *
     * @return array
     */
    public function toArray()
    {
        if ($this->getConfirm() != null) {
            return [
                'name' => $this->getName(),
                'text' => $this->getText(),
                'style' => $this->getStyle(),
                'type' => $this->getType(),
                'value' => $this->getValue(),
                'confirm' => $this->getConfirm()->toArray(),
            ];
        } else {
            return [
                'name' => $this->getName(),
                'text' => $this->getText(),
                'style' => $this->getStyle(),
                'type' => $this->getType(),
                'value' => $this->getValue(),
            ];
        }
    }
}
