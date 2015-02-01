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
   * Optional text that should appear above the formatted data
   *
   * @var string
   */
  protected $pretext;

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

    if (isset($attributes['pretext'])) $this->setPretext($attributes['pretext']);

    if (isset($attributes['color'])) $this->setColor($attributes['color']);

    if (isset($attributes['fields'])) $this->setFields($attributes['fields']);

    if (isset($attributes['mrkdwn_in'])) $this->setMarkdownFields($attributes['mrkdwn_in']);
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
      'mrkdwn_in' => $this->getMarkdownFields()
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