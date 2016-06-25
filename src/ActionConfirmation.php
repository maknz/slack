<?php

namespace Maknz\Slack;

class ActionConfirmation
{
    /**
     * The required title for the pop up window.
     *
     * @var string
     */
    protected $title;

    /**
     * The required description.
     *
     * @var string
     */
    protected $text;

    /**
     * The text label for the OK button.
     *
     * @var string
     */
    protected $okText;

    /**
     * The text label for the Cancel button.
     *
     * @var string
     */
    protected $dismissText;

    /**
     * Instantiate a new ActionConfirmation.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes)
    {
        if (isset($attributes['title'])) {
            $this->setTitle($attributes['title']);
        }

        if (isset($attributes['text'])) {
            $this->setText($attributes['text']);
        }

        if (isset($attributes['ok_text'])) {
            $this->setOkText($attributes['ok_text']);
        }

        if (isset($attributes['dismiss_text'])) {
            $this->setDismissText($attributes['dismiss_text']);
        }
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ActionConfirmation
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @return ActionConfirmation
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getOkText()
    {
        return $this->okText;
    }

    /**
     * @param string $okText
     * @return ActionConfirmation
     */
    public function setOkText($okText)
    {
        $this->okText = $okText;

        return $this;
    }

    /**
     * @return string
     */
    public function getDismissText()
    {
        return $this->dismissText;
    }

    /**
     * @param string $dismissText
     * @return ActionConfirmation
     */
    public function setDismissText($dismissText)
    {
        $this->dismissText = $dismissText;

        return $this;
    }

    /**
     * Get the array representation of this action confirmation.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->getTitle(),
            'text' => $this->getText(),
            'ok_text' => $this->getOkText(),
            'dismiss_text' => $this->getDismissText(),
        ];
    }
}
