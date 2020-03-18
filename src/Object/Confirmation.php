<?php
namespace Maknz\Slack\Object;

use Maknz\Slack\BlockElement\Text;

class Confirmation extends CompositionObject
{
    /**
     * Confirmation title.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $title;

    /**
     * Confirmation explanatory text.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $text;

    /**
     * Text that confirms the action.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $confirm;

    /**
     * Text that denies the action.
     *
     * @var \Maknz\Slack\BlockElement\Text
     */
    protected $deny;

    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [
        'title'   => 'title',
        'text'    => 'text',
        'confirm' => 'confirm',
        'deny'    => 'deny',
    ];

    /**
     * Get the confirmation title.
     *
     * @return \Maknz\Slack\BlockElement\Text
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the confirmation title.
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

    /**
     * Get the confirmation explanatory text.
     *
     * @return \Maknz\Slack\BlockElement\Text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the confirmation explanatory text.
     *
     * @param mixed $text
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setText($text)
    {
        $this->text = Text::create($text);

        return $this;
    }

    /**
     * Get the text that confirms the action.
     *
     * @return \Maknz\Slack\BlockElement\Text
     */
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     * Set the text that confirms the action.
     *
     * @param mixed $confirm
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setConfirm($confirm)
    {
        $this->confirm = Text::create($confirm, Text::TYPE_PLAIN);

        return $this;
    }

    /**
     * Get the text that denies the action.
     *
     * @return \Maknz\Slack\BlockElement\Text
     */
    public function getDeny()
    {
        return $this->deny;
    }

    /**
     * Set the text that denies the action.
     *
     * @param mixed $deny
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setDeny($deny)
    {
        $this->deny = Text::Create($deny, Text::TYPE_PLAIN);

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
            'title'   => $this->getTitle()->toArray(),
            'text'    => $this->getText()->toArray(),
            'confirm' => $this->getConfirm()->toArray(),
            'deny'    => $this->getDeny()->toArray(),
        ];

        return $data;
    }
}
