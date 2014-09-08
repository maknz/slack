<?php
use Maknz\Slack\Client;
use Maknz\Slack\Attachment;
use Maknz\Slack\AttachmentField;

class ClientFunctionalTest extends PHPUnit_Framework_TestCase {

  public function testFunctionalPayloadBasic()
  {
    $expectedHttpData = [
      'username' => 'Test',
      'channel' => '#general',
      'text' => 'Message',
      'attachments' => []
    ];

    $client = new Client($this->getEndpoint(), ['username' => 'Test']);

    $payload = $client->preparePayload('Message');

    $this->assertEquals($expectedHttpData, $payload);
  }

  public function testFunctionalPayloadWithAttachment()
  {
    $attachmentArray = [
      'fallback' => 'Some fallback text',
      'text' => 'Some text to appear in the attachment',
      'pretext' => null,
      'color' => 'bad',
      'fields' => []
    ];

    $expectedHttpData = [
      'username' => 'Test',
      'channel' => '#general',
      'text' => 'Message',
      'attachments' => [$attachmentArray]
    ];

    $client = new Client($this->getEndpoint(), ['username' => 'Test']);

    $attachment = new Attachment($attachmentArray);

    $client->attach($attachment);

    $payload = $client->preparePayload('Message');

    $this->assertEquals($expectedHttpData, $payload);
  }

  public function testFunctionalPayloadWithAttachmentWithFields()
  {
    $attachmentArray = [
      'fallback' => 'Some fallback text',
      'text' => 'Some text to appear in the attachment',
      'pretext' => null,
      'color' => 'bad',
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
      'attachments' => [$attachmentArray]
    ];

    $client = new Client($this->getEndpoint(), ['username' => $expectedHttpData['username']]);

    $attachment = new Attachment($attachmentArray);

    $client->attach($attachment);

    $payload = $client->preparePayload('Message');

    $this->assertEquals($expectedHttpData, $payload);
  }

  private function getEndpoint()
  {
    return 'http://fake.endpoint';
  }

}