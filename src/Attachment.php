<?php
namespace Maknz\Slack;

use InvalidArgumentException;

class Attachment
{
    /**
      * The fallback text to use for clients that don't support attachments
      *
      * @var string
      */
    protected $fallback;

    /**
     * Optional text that should appear within the attachment
     *
     * @var string
     */
    protected $text;

    /**
     * Optional image that should appear within the attachment
     *
     * @var string
     */
    protected $imageUrl;

    /**
     * Optional thumbnail that should appear within the attachment
     *
     * @var string
     */
    protected $thumbUrl;

    /**
     * Optional text that should appear above the formatted data
     *
     * @var string
     */
    protected $pretext;

    /**
     * Optional title for the attachment
     *
     * @var string
     */
    protected $title;

    /**
     * Optional title link for the attachment
     *
     * @var string
     */
    protected $titleLink;

    /**
    * Optional author name for the attachment
     *
     * @var string
     */
    protected $authorName;

    /**
     * Optional author link for the attachment
     *
     * @var string
     */
    protected $authorLink;

    /**
     * Optional author icon for the attachment
     *
     * @var string
     */
    protected $authorIcon;

    /**
     * The color to use for the attachment
     *
     * @var string
     */
    protected $color = 'good';

    /**
     * The fields of the attachment
     *
     * @var array
     */
    protected $fields = [];

    /**
     * The fields of the attachment that Slack should interpret
     * with its Markdown-like language
     *
     * @var array
     */
    protected $markdownFields = [];

    /**
     * Instantiate a new Attachment
     *
     * @param array $attributes
     *
     * @return void
     */
    public function __construct(array $attributes)
    {
        if (isset($attributes['fallback']))
        {
            $this->setFallback($attributes['fallback']);
        }

        if (isset($attributes['text']))
        {
            $this->setText($attributes['text']);
        }

        if (isset($attributes['image_url']))
        {
            $this->setImageUrl($attributes['image_url']);
        }

        if (isset($attributes['thumb_url']))
        {
            $this->setThumbUrl($attributes['thumb_url']);
        }

        if (isset($attributes['pretext']))
        {
            $this->setPretext($attributes['pretext']);
        }

        if (isset($attributes['color']))
        {
            $this->setColor($attributes['color']);
        }

        if (isset($attributes['fields']))
        {
            $this->setFields($attributes['fields']);
        }

        if (isset($attributes['mrkdwn_in']))
        {
            $this->setMarkdownFields($attributes['mrkdwn_in']);
        }

        if (isset($attributes['title']))
        {
            $this->setTitle($attributes['title']);
        }

        if (isset($attributes['title_link']))
        {
            $this->setTitleLink($attributes['title_link']);
        }

        if (isset($attributes['author_name']))
        {
            $this->setAuthorName($attributes['author_name']);
        }

        if (isset($attributes['author_link']))
        {
            $this->setAuthorLink($attributes['author_link']);
        }

        if (isset($attributes['author_icon']))
        {
            $this->setAuthorIcon($attributes['author_icon']);
        }
    }

    /**
     * Get the fallback text
     *
     * @return string
     */
    public function getFallback()
    {
        return $this->fallback;
    }

    /**
     * Set the fallback text
     *
     * @param string $fallback fallback text
     *
     * @return $this
     */
    public function setFallback($fallback)
    {
        $this->fallback = $fallback;

        return $this;
    }

    /**
     * Get the optional text to appear within the attachment
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the optional text to appear within the attachment
     *
     * @param string $text text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the optional image to appear within the attachment
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set the optional image to appear within the attachment
     *
     * @param string $imageUrl iamge url
     *
     * @return $this
    */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get the optional thumbnail to appear within the attachment
     *
     * @return string
     */
    public function getThumbUrl()
    {
        return $this->thumbUrl;
    }

    /**
     * Set the optional thumbnail to appear within the attachment
     *
     * @param string $thumbUrl thumb url
     *
     * @return $this
    */
    public function setThumbUrl($thumbUrl)
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
    }

    /**
     * Get the text that should appear above the formatted data
     *
     * @return string
     */
    public function getPretext()
    {
        return $this->pretext;
    }

    /**
     * Set the text that should appear above the formatted data
     *
     * @param string $pretext pretext
     *
     * @return $this
     */
    public function setPretext($pretext)
    {
        $this->pretext = $pretext;

        return $this;
    }

    /**
     * Get the color to use for the attachment
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set the color to use for the attachment
     *
     * @param string $color color
     *
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get the title to use for the attachment
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the title to use for the attachment
     *
     * @param string $title title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the title link to use for the attachment
     *
     * @return string
     */
    public function getTitleLink()
    {
        return $this->titleLink;
    }

    /**
    * Set the title link to use for the attachment
    *
    * @param string $titleLink title link
    *
    * @return $this
    */
    public function setTitleLink($titleLink)
    {
        $this->titleLink = $titleLink;

        return $this;
    }

    /**
     * Get the author name to use for the attachment
     *
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set the author name to use for the attachment
     *
     * @param string $authorName author name
     *
     * @return void
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Get the author link to use for the attachment
     *
     * @return string
     */
    public function getAuthorLink()
    {
        return $this->authorLink;
    }

    /**
     * Set the auhtor link to use for the attachment
     *
     * @param string $authorLink authorlink
     *
     * @return void
    */
    public function setAuthorLink($authorLink)
    {
        $this->authorLink = $authorLink;

        return $this;
    }

    /**
     * Get the author icon to use for the attachment
     *
     * @return string
     */
    public function getAuthorIcon()
    {
        return $this->authorIcon;
    }

    /**
    * Set the author icon to use for the attachment
    *
    * @param string $authorIcon author icon
    *
    * @return void
    */
    public function setAuthorIcon($authorIcon)
    {
        $this->authorIcon = $authorIcon;

        return $this;
    }

    /**
     * Get the fields for the attachment
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set the fields for the attachment
     *
     * @param array $fields fields
     *
     * @return void
    */
    public function setFields(array $fields)
    {
        $this->clearFields();

        foreach ($fields as $field)
        {
            $this->addField($field);
        }

        return $this;
    }

    /**
     * Add a field to the attachment
     *
     * @param mixed $field fields
     *
     * @return $this
    */
    public function addField($field)
    {
        if ($field instanceof AttachmentField)
        {
            $this->fields[] = $field;
        }
        else if (is_array($field))
        {
            $this->fields[] = new AttachmentField($field);
        }
        else
        {
            throw new InvalidArgumentException(
                'The attachment field must be an instance of Maknz\Slack\AttachmentField or a keyed array');
        }

        return $this;
    }

    /**
     * Clear the fields for the attachment
     *
     * @return $this
     */
    public function clearFields()
    {
        $this->fields = [];

        return $this;
    }

    /**
     * Get the fields Slack should interpret in its
     * Markdown-like language
     *
     * @return array
     */
    public function getMarkdownFields()
    {
        return $this->markdownFields;
    }

    /**
     * Set the fields Slack should interpret in its
     * Markdown-like language
     *
     * @param array $fields fields
     *
     * @return $this
     */
    public function setMarkdownFields(array $fields)
    {
        $this->markdownFields = $fields;

        return $this;
    }

    /**
     * Convert this attachment to its array representation
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'fallback'   => $this->getFallback(),
            'text'       => $this->getText(),
            'pretext'    => $this->getPretext(),
            'color'      => $this->getColor(),
            'mrkdwnIn'   => $this->getMarkdownFields(),
            'imageUrl'   => $this->getImageUrl(),
            'thumbUrl'   => $this->getThumbUrl(),
            'title'      => $this->getTitle(),
            'titleLink'  => $this->getTitleLink(),
            'authorName' => $this->getAuthorName(),
            'authorLink' => $this->getAuthorLink(),
            'authorIcon' => $this->getAuthorIcon(),
            'fields'     => $this->getFieldsAsArrays()
        ];

        return $data;
    }

    /**
     * Iterates over all fields in this attachment and returns
     * them in their array form
     *
     * @return array
     */
    protected function getFieldsAsArrays()
    {
        $fields = [];

        foreach ($this->getFields() as $field)
        {
            $fields[] = $field->toArray();
        }

        return $fields;
    }
}
