<?php

use Maknz\Slack\Client;
use Maknz\Slack\Attachment;
use Illuminate\Queue\Capsule\Manager as Queue;

class ClientFunctionalTest extends PHPUnit_Framework_TestCase {

  public function testPlainMessage()
  {
    $expectedHttpData = [
      'username' => 'Archer',
      'channel' => '@regan',
      'text' => 'Message',
      'link_names' => false,
      'unfurl_links' => false,
      'unfurl_media' => true,
      'mrkdwn' => true,
      'attachments' => []
    ];

    $client = new Client('http://fake.endpoint');

    $message = $client->to('@regan')->from('Archer')->setText('Message');

    $payload = $client->preparePayload($message);

    $this->assertEquals($expectedHttpData, $payload);
  }

  public function testMessageWithAttachments()
  {
    $attachmentArray = [
      'fallback' => 'Some fallback text',
      'text' => 'Some text to appear in the attachment',
      'pretext' => null,
      'color' => 'bad',
      'mrkdwn_in' => ['pretext', 'text'],
      'image_url' => 'http://fake.host/image.png',
      'thumb_url' => 'http://fake.host/image.png',
      'fields' => [],
      'title' => null,
      'title_link' => null,
      'author_name' => 'Joe Bloggs',
      'author_link' => 'http://fake.host/',
      'author_icon' => 'http://fake.host/image.png'
    ];

    $expectedHttpData = [
      'username' => 'Test',
      'channel' => '#general',
      'text' => 'Message',
      'link_names' => false,
      'unfurl_links' => false,
      'unfurl_media' => true,
      'mrkdwn' => true,
      'attachments' => [$attachmentArray]
    ];

    $client = new Client('http://fake.endpoint', [
      'username' => 'Test',
      'channel' => '#general'
    ]);

    $message = $client->createMessage()->setText('Message');

    $attachment = new Attachment($attachmentArray);

    $message->attach($attachment);

    $payload = $client->preparePayload($message);

    $this->assertEquals($expectedHttpData, $payload);
  }

  public function testMessageWithAttachmentsAndFields()
  {
    $attachmentArray = [
      'fallback' => 'Some fallback text',
      'text' => 'Some text to appear in the attachment',
      'pretext' => null,
      'color' => 'bad',
      'mrkdwn_in' => [],
      'image_url' => 'http://fake.host/image.png',
      'thumb_url' => 'http://fake.host/image.png',
      'title' => 'A title',
      'title_link' => 'http://fake.host/',
      'author_name' => 'Joe Bloggs',
      'author_link' => 'http://fake.host/',
      'author_icon' => 'http://fake.host/image.png',
      'fields' => [
        [
          'title' => 'Field 1',
          'value' => 'Value 1',
          'short' => false
        ],
        [
          'title' => 'Field 2',
          'value' => 'Value 2',
          'short' => false
        ]
      ]
    ];

    $expectedHttpData = [
      'username' => 'Test',
      'channel' => '#general',
      'text' => 'Message',
      'link_names' => false,
      'unfurl_links' => false,
      'unfurl_media' => true,
      'mrkdwn' => true,
      'attachments' => [$attachmentArray]
    ];

    $client = new Client('http://fake.endpoint', [
      'username' => 'Test',
      'channel' => '#general'
    ]);

    $message = $client->createMessage()->setText('Message');

    $attachment = new Attachment($attachmentArray);

    $message->attach($attachment);

    $payload = $client->preparePayload($message);

    $this->assertEquals($expectedHttpData, $payload);
  }

  public function testSendQueue()
  {
    $queue = Mockery::mock('Illuminate\Queue\Capsule\Manager[push]')->makePartial('push');

    $queue->getContainer()->bind('encrypter', function(){
      return new Illuminate\Encryption\Encrypter('slack');
    });

    $queue->addConnection(['driver' => 'sync']);

    $expectedHttpData = [
      'text' => 'Message',
      'channel' => '#general',
      'username' => 'Test',
      'link_names' => 0,
      'unfurl_links' => false,
      'unfurl_media' => true,
      'mrkdwn' => true,
      'attachments' => []
    ];

    $queue->shouldReceive('push')->withArgs($expectedHttpData);

    $client = new Client('http://fake.endpoint', [
      'username' => 'Test',
      'channel' => '#general'
    ], $queue);

    $this->assertSame($queue, $client->getQueueManager());

    $message = $client->createMessage()->setText('Message');

    $client->queueMessage($message);
  }

}
