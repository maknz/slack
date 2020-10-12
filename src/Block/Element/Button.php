<?php namespace Maknz\Slack\Block\Element;

class Button extends \Maknz\Slack\Block\Element
{
    protected $type = 'plain_text';
    protected $text = '';
    protected $emoji = false;

    public function __construct(array $attributes = [])
    {
        if (isset($attributes['type'])) {
            $this->setType($attributes['type']);
        }
        if (isset($attributes['text'])) {
            $this->setText($attributes['text']);
        }
    }

    public function getType()
    {
        return $this->type;
    }
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getText()
    {
        return $this->text;
    }
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    public function setEmoji($bool)
    {
        $this->emoji = $bool;
    }
    public function getEmoji()
    {
        return $this->emoji;
    }

    public function toArray()
    {
        return [
            'type' => 'button',
            'text' => [
                'type' => $this->getType(),
                'text' => $this->getText(),
                'emoji' => $this->getEmoji()
            ]
        ];
    }
}