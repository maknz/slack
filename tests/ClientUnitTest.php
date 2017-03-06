<?php

use Maknz\Slack\Client;
use Maknz\Slack\Team;

class ClientUnitTest extends PHPUnit_Framework_TestCase
{
    public function testInstantiationWithNoDefaults()
    {
        $client = new Client([
            new Team('teamname', 'http://teamname.slack.com', '#general', 'Test', ':+1:'),
        ], 'teamname');
        $this->assertInstanceOf('Maknz\Slack\Client', $client);

        $this->assertSame('http://teamname.slack.com', $client->getEndpoint());
    }

    public function testInstantiationWithDefaults()
    {
        $defaults = [
            'channel' => '#general',
            'username' => 'Test',
            'icon' => ':+1:',
            'link_names' => true,
            'unfurl_links' => true,
            'unfurl_media' => false,
            'allow_markdown' => false,
            'markdown_in_attachments' => ['text'],
        ];

        $client = new Client([
            new Team(
                'teamname',
                'http://teamname.slack.com',
                $defaults['channel'],
                $defaults['username'],
                $defaults['icon']
            ),
        ], 'teamname', [
            'link_names' => true,
            'unfurl_links' => true,
            'unfurl_media' => false,
            'allow_markdown' => false,
            'markdown_in_attachments' => ['text'],
        ]);

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
        $client = new Client([
            new Team('teamname', 'http://teamname.slack.com', '#general', 'Test', ':+1:'),
        ], 'teamname');

        $message = $client->createMessage();

        $this->assertInstanceOf('Maknz\Slack\Message', $message);

        $this->assertSame($client->getDefaultChannel(), $message->getChannel());

        $this->assertSame($client->getDefaultUsername(), $message->getUsername());

        $this->assertSame($client->getDefaultIcon(), $message->getIcon());
    }

    public function testWildcardCallToMessage()
    {
        $client = new Client([
            new Team('teamname', 'http://teamname.slack.com', '#general', 'Test', ':+1:'),
        ], 'teamname');

        $message = $client->to('@regan');

        $this->assertInstanceOf('Maknz\Slack\Message', $message);

        $this->assertSame('@regan', $message->getChannel());
    }

    public function testDivergedTeamNamesWillThrowException()
    {
        $this->setExpectedException(Exception::class);

        $client = new Client([
            new Team('teamname1', 'http://teamname.slack.com', '#general', 'Test', ':+1:'),
        ], 'teamname2');
    }

    public function testTeamMethodWillReturnClonedClientWithTeamSettings()
    {
        $guzzle = Mockery::mock('GuzzleHttp\Client');

        $teamOne = new Team('teamname1', 'http://teamname1.slack.com', '#general', 'Test', ':+1:');
        $teamTwo = new Team('teamname2', 'http://teamname2.slack.com', '#general', 'Test', ':ghost:');
        $teamThree = new Team('teamname3', 'http://teamname3.slack.com', '#general', 'Test', ':+1:');

        $client = new Client([
            $teamOne,
            $teamTwo,
            $teamThree,
        ], 'teamname1', [], $guzzle);

        $this->assertSame($teamOne->getUsername(), $client->getDefaultUsername());
        $this->assertSame($teamOne->getIcon(), $client->getDefaultIcon());
        $this->assertSame($teamOne->getWebhook(), $client->getEndpoint());
        $this->assertSame($teamOne->getDefaultChannel(), $client->getDefaultChannel());

        // Client after switching teams will still have default team settings
        $temp = $client->team('teamname2');

        $this->assertSame($teamOne->getUsername(), $client->getDefaultUsername());
        $this->assertSame($teamOne->getIcon(), $client->getDefaultIcon());
        $this->assertSame($teamOne->getWebhook(), $client->getEndpoint());
        $this->assertSame($teamOne->getDefaultChannel(), $client->getDefaultChannel());

        // Returned team intended for chaining will have properties of team2
        $this->assertSame($teamTwo->getUsername(), $temp->getDefaultUsername());
        $this->assertSame($teamTwo->getIcon(), $temp->getDefaultIcon());
        $this->assertSame($teamTwo->getWebhook(), $temp->getEndpoint());
        $this->assertSame($teamTwo->getDefaultChannel(), $temp->getDefaultChannel());
    }
}
