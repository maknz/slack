<?php
namespace Maknz\Slack;

use Maknz\Slack\BlockElement\Text;

trait ImageTrait
{
    /**
     * The image URL.
     *
     * @var string
     */
    protected $url;

    /**
     * The alternative text for the image.
     *
     * @var string
     */
    protected $alt_text;

    /**
     * The image title.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $title;

    /**
     * Get the image URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the image URL.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the alternative text for the image.
     *
     * @return string
     */
    public function getAltText()
    {
        return $this->alt_text;
    }

    /**
     * Set the alternative text for the image.
     *
     * @param mixed $text
     *
     * @return $this
     */
    public function setAltText($text)
    {
        $this->alt_text = $text;

        return $this;
    }

    /**
     * Get the image title.
     *
     * @return \Maknz\Slack\BlockElement\Text
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the image title.
     *
     * @param mixed $title
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setTitle($title)
    {
        $this->title = Text::create($title, Text::TYPE_PLAIN);

        return $this;
    }
}
