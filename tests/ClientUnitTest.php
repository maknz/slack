<?php

use Razorpay\Slack\Client;
use Illuminate\Queue\SyncQueue as Queue;

class ClientUnitTest extends PHPUnit_Framework_TestCase
{
    public function testInstantiationWithNoDefaults()
    {
        $client = new Client('http://fake.endpoint');

        $this->assertInstanceOf('Razorpay\Slack\Client', $client);

        $this->assertSame('http://fake.endpoint', $client->getEndpoint());

        $client->setEndpoint('http://new.fake.endpoint');

        $this->assertSame('http://new.fake.endpoint', $client->getEndpoint());
    }

    public function testGetAndSetQueue()
    {
        $queue = new Queue;

        $client = new Client('http://fake.endpoint');

        $client->setQueue($queue);

        $this->assertInstanceOf('Illuminate\Contracts\Queue\Queue', $client->getQueue());
    }

    public function testInstantiationWithDefaults()
    {
        $defaults = [
            'channel'                 => '#random',
            'username'                => 'Archer',
            'icon'                    => ':ghost:',
            'link_names'              => true,
            'unfurl_links'            => true,
            'unfurl_media'            => false,
            'allow_markdown'          => false,
            'markdown_in_attachments' => ['text'],
            'is_slack_enabled'        => true,
        ];

        $client = new Client('http://fake.endpoint', $defaults);

        $this->assertSame($defaults['channel'], $client->getDefaultChannel());

        $this->assertSame($defaults['username'], $client->getDefaultUsername());

        $this->assertSame($defaults['icon'], $client->getDefaultIcon());

        $this->assertTrue($client->getLinkNames());

        $this->assertTrue($client->getUnfurlLinks());

        $this->assertFalse($client->getUnfurlMedia());

        $this->assertSame($defaults['allow_markdown'], $client->getAllowMarkdown());

        $this->assertSame($defaults['markdown_in_attachments'], $client->getMarkdownInAttachments());
    }

    public function testCreateMessage()
    {
        $defaults = [
            'channel' => '#random',
            'username' => 'Archer',
            'icon' => ':ghost:',
        ];

        $client = new Client('http://fake.endpoint', $defaults);

        $message = $client->createMessage();

        $this->assertInstanceOf('Razorpay\Slack\Message', $message);

        $this->assertSame($client->getDefaultChannel(), $message->getChannel());

        $this->assertSame($client->getDefaultUsername(), $message->getUsername());

        $this->assertSame($client->getDefaultIcon(), $message->getIcon());
    }

    public function testWildcardCallToMessage()
    {
        $client = new Client('http://fake.endpoint');

        $message = $client->to('@regan');

        $this->assertInstanceOf('Razorpay\Slack\Message', $message);

        $this->assertSame('@regan', $message->getChannel());
    }
}
