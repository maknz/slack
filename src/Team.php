<?php

namespace Maknz\Slack;

class Team
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $webhook;

    /**
     * @var string
     */
    private $defaultChannel;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $icon;

    /**
     * @param string $teamName
     * @param string $webhook
     * @param string $defaultChannel
     * @param $username
     * @param $icon
     */
    public function __construct($teamName, $webhook, $defaultChannel, $username, $icon)
    {
        $this->name = $teamName;
        $this->webhook = $webhook;
        $this->defaultChannel = $defaultChannel;
        $this->username = $username;
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getWebhook()
    {
        return $this->webhook;
    }

    /**
     * @return string
     */
    public function getDefaultChannel()
    {
        return $this->defaultChannel;
    }
}
