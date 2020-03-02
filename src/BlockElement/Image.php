<?php
namespace Maknz\Slack\BlockElement;

use Maknz\Slack\BlockElement;
use Maknz\Slack\BlockElement\Text;

class Image extends BlockElement
{
    protected $type = 'image';

    protected $url;

    protected $alt_text;

    protected $title;

    protected static $availableAttributes = [
        'image_url' => 'url',
        'alt_text'  => 'alt_text',
        'title'     => 'title',
    ];

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getAltText()
    {
        return $this->alt_text;
    }

    public function setAltText($text)
    {
        $this->alt_text = $text;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = Text::create($title, Text::TYPE_PLAIN);

        return $this;
    }

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

        return $data;
    }
}
