<?php

use Maknz\Slack\Client;

class ClientUnitTest extends PHPUnit_Framework_TestCase {

  public function testInstantiationWithNoDefaults()
  {
    $client = new Client('http://fake.endpoint');

    $this->assertInstanceOf('Maknz\Slack\Client', $client);

    $this->assertSame('http://fake.endpoint', $client->getEndpoint());
  }

  public function testInstantiationWithDefaults()
  {
    $defaults = [
      'channel' => '#random',
      'username' => 'Archer',
      'icon' => ':ghost:',
      'link_names' => true,
      'unfurl_links' => true,
      'unfurl_media' => false,
      'allow_markdown' => false,
      'markdown_in_attachments' => ['text']
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
      'icon' => ':ghost:'
    ];

    $client = new Client('http://fake.endpoint', $defaults);

    $message = $client->createMessage();

    $this->assertInstanceOf('Maknz\Slack\Message', $message);

    $this->assertSame($client->getDefaultChannel(), $message->getChannel());

    $this->assertSame($client->getDefaultUsername(), $message->getUsername());

    $this->assertSame($client->getDefaultIcon(), $message->getIcon());
  }

  public function testWildcardCallToMessage()
  {
    $client = new Client('http://fake.endpoint');

    $message = $client->to('@regan');

    $this->assertInstanceOf('Maknz\Slack\Message', $message);

    $this->assertSame('@regan', $message->getChannel());
  }

}