<?php
namespace Maknz\Slack\BlockElement;

use Maknz\Slack\OptionsTrait;

abstract class Options extends Confirmable
{
    use OptionsTrait;

    /**
     * Options action.
     *
     * @var string
     */
    protected $action_id;

    /**
     * Get the options action.
     *
     * @return string
     */
    public function getActionId()
    {
        return $this->action_id;
    }

    /**
     * Set the options action.
     *
     * @param string $actionId
     *
     * @return $this
     */
    public function setActionId($actionId)
    {
        $this->action_id = $actionId;

        return $this;
    }
}
