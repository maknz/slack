<?php
namespace Maknz\Slack\BlockElement;

use InvalidArgumentException;
use Maknz\Slack\BlockElement;
use Maknz\Slack\Object\Confirmation;

abstract class Confirmable extends BlockElement
{
    /**
     * Confirmation object.
     *
     * @var \Maknz\Slack\Object\Confirmation
     */
    protected $confirm;

    /**
     * Get the confirmation object.
     *
     * @return \Maknz\Slack\Object\Confirmation
     */
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     * Set the confirmation object.
     *
     * @param mixed $confirm
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setConfirm($confirm)
    {
        if (is_array($confirm)) {
            $confirm = new Confirmation($confirm);
        }

        if ($confirm instanceof Confirmation) {
            $this->confirm = $confirm;

            return $this;
        }

        throw new InvalidArgumentException('Confirm must be a keyed array or '.Confirmation::class.' object');
    }
}
