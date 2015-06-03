<?php namespace Maknz\Slack;

use InvalidArgumentException;

class Attachment {
  
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
  protected $image_url;
  
  /**
    * Optional thumbnail that should appear within the attachment
    *
    * @var string 
    */
  protected $thumb_url;

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
  protected $title_link;
  
  /**
   * Optional author name for the attachment
   *
   * @var string
   */
  protected $author_name;

  /**
   * Optional author link for the attachment
   *
   * @var string
   */
  protected $author_link;

  /**
   * Optional author icon for the attachment
   *
   * @var string
   */
  protected $author_icon;

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
  protected $markdown_fields = [];

  /**
   * Instantiate a new Attachment
   *
   * @param array $attributes
   * @return void
   */
  public function __construct(array $attributes)
  {
    if (isset($attributes['fallback'])) $this->setFallback($attributes['fallback']);

    if (isset($attributes['text'])) $this->setText($attributes['text']);

    if (isset($attributes['image_url'])) $this->setImageUrl($attributes['image_url']);
    
    if (isset($attributes['thumb_url'])) $this->setThumbUrl($attributes['thumb_url']);

    if (isset($attributes['pretext'])) $this->setPretext($attributes['pretext']);

    if (isset($attributes['color'])) $this->setColor($attributes['color']);

    if (isset($attributes['fields'])) $this->setFields($attributes['fields']);

    if (isset($attributes['mrkdwn_in'])) $this->setMarkdownFields($attributes['mrkdwn_in']);

    if (isset($attributes['title'])) $this->setTitle($attributes['title']);

    if (isset($attributes['title_link'])) $this->setTitleLink($attributes['title_link']);
    
    if (isset($attributes['author_name'])) $this->setAuthorName($attributes['author_name']);

    if (isset($attributes['author_link'])) $this->setAuthorLink($attributes['author_link']);

    if (isset($attributes['author_icon'])) $this->setAuthorIcon($attributes['author_icon']);
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
   * @param string $fallback
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
   * @param string $text
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
    return $this->image_url;
  }

  /**
   * Set the optional image to appear within the attachment
   *
   * @param string $image_url
   * @return $this
   */
  public function setImageUrl($image_url)
  {
    $this->image_url = $image_url;

    return $this;
  }
  
  /**
   * Get the optional thumbnail to appear within the attachment
   *
   * @return string
   */
  public function getThumbUrl()
  {
    return $this->thumb_url;
  }

  /**
   * Set the optional thumbnail to appear within the attachment
   *
   * @param string $thumb_url
   * @return $this
   */
  public function setThumbUrl($thumb_url)
  {
    $this->thumb_url = $thumb_url;

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
   * @param string $pretext
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
   * @param string $colour
   * @return void
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
   * @param string $title
   * @return void
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
        return $this->title_link;
  }

  /**
   * Set the title link to use for the attachment
   *
   * @param string $title_link
   * @return void
   */
  public function setTitleLink($title_link)
  {
      $this->title_link = $title_link;

      return $this;
  }

  /**
   * Get the author name to use for the attachment
   *
   * @return string
   */
  public function getAuthorName()
  {
    return $this->author_name;
  }

  /**
   * Set the author name to use for the attachment
   *
   * @param string $author_name
   * @return void
   */
  public function setAuthorName($author_name)
  {
    $this->author_name = $author_name;

    return $this;
  }

  /**
   * Get the author link to use for the attachment
   *
   * @return string
   */
  public function getAuthorLink()
  {
    return $this->author_link;
  }

  /**
   * Set the auhtor link to use for the attachment
   *
   * @param string $author_link
   * @return void
   */
  public function setAuthorLink($author_link)
  {
    $this->author_link = $author_link;

    return $this;
  }

  /**
   * Get the author icon to use for the attachment
   *
   * @return string
   */
  public function getAuthorIcon()
  {
    return $this->author_icon;
  }

  /**
   * Set the author icon to use for the attachment
   *
   * @param string $author_icon
   * @return void
   */
  public function setAuthorIcon($author_icon)
  {
    $this->author_icon = $author_icon;

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
   * @param array $fields
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
   * @param mixed $field
   * @return $this
   */
  public function addField($field)
  {
    if ($field instanceof AttachmentField)
    {
      $this->fields[] = $field;

      return $this;
    }

    elseif (is_array($field))
    {
      $this->fields[] = new AttachmentField($field);

      return $this;
    }

    throw new InvalidArgumentException('The attachment field must be an instance of Maknz\Slack\AttachmentField or a keyed array');
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
    return $this->markdown_fields;
  }

  /**
   * Set the fields Slack should interpret in its
   * Markdown-like language
   *
   * @param array $fields
   * @return $this
   */
  public function setMarkdownFields(array $fields)
  {
    $this->markdown_fields = $fields;

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
      'fallback' => $this->getFallback(),
      'text' => $this->getText(),
      'pretext' => $this->getPretext(),
      'color' => $this->getColor(),
      'mrkdwn_in' => $this->getMarkdownFields(),
      'image_url' => $this->getImageUrl(),
      'thumb_url' => $this->getThumbUrl(),
      'title' => $this->getTitle(),
      'title_link' => $this->getTitleLink(),
      'author_name' => $this->getAuthorName(),
      'author_link' => $this->getAuthorLink(),
      'author_icon' => $this->getAuthorIcon()
    ];

    $data['fields'] = $this->getFieldsAsArrays();

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

    foreach ($this->getFields() as $field) $fields[] = $field->toArray();

    return $fields;
  }

}
