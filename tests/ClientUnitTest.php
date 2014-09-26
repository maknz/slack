<?php

use Maknz\Slack\Client;
use Maknz\Slack\Attachment;
use Maknz\Slack\AttachmentField;

class ClientUnitTest extends PHPUnit_Framework_TestCase {

  public function testFrom()
  {
    $c = $this->getClient();

    $c->from('Jake the Dog');

    $this->assertEquals('Jake the Dog', $c->getUsername());
  }

  public function testTo()
  {
    $c = $this->getClient();

    $c->to('Finn the Human');

    $this->assertEquals('Finn the Human', $c->getChannel());
  }

  public function testAttachWithArray()
  {
    $c = $this->getClient();

    $attachmentArray = [
      'fallback' => 'Fallback text for IRC',
      'text' => 'Attachment text',
      'pretext' => 'Attachment pretext',
      'color' => 'bad',
      'fields' => []
    ];

    $c->attach($attachmentArray);

    $attachments = $c->getAttachments();

    $this->assertEquals(1, count($attachments));

    $obj = $attachments[0];

    $this->assertEquals($attachmentArray['fallback'], $obj->getFallback());

    $this->assertEquals($attachmentArray['text'], $obj->getText());

    $this->assertEquals($attachmentArray['pretext'], $obj->getPretext());

    $this->assertEquals($attachmentArray['color'], $obj->getColor());
  }

  public function testAttachWithObject()
  {
    $c = $this->getClient();

    $obj = new Attachment([
      'fallback' => 'Fallback text for IRC',
      'text' => 'Text'
    ]);

    $c->attach($obj);

    $attachments = $c->getAttachments();

    $this->assertEquals(1, count($attachments));

    $remoteObj = $attachments[0];

    $this->assertEquals($obj, $remoteObj);
  }

  public function testMultipleAttachments()
  {
    $c = $this->getClient();

    $obj1 = new Attachment([
      'fallback' => 'Fallback text for IRC',
      'text' => 'Text'
    ]);

    $obj2 = new Attachment([
      'fallback' => 'Fallback text for IRC',
      'text' => 'Text'
    ]);

    $c->attach($obj1)->attach($obj2);

    $attachments = $c->getAttachments();

    $this->assertEquals(2, count($attachments));

    $remote1 = $attachments[0];

    $remote2 = $attachments[1];

    $this->assertEquals($obj1, $remote1);

    $this->assertEquals($obj2, $remote2);
  }

  public function testSetAttachmentsWipesExistingAttachments()
  {
    $c = $this->getClient();

    $obj1 = new Attachment([
      'fallback' => 'Fallback text for IRC',
      'text' => 'Text'
    ]);

    $obj2 = new Attachment([
      'fallback' => 'Fallback text for IRC',
      'text' => 'Text'
    ]);

    $c->attach($obj1)->attach($obj2);

    $this->assertEquals(2, count($c->getAttachments()));

    $c->setAttachments([['fallback' => 'a', 'text' => 'b']]);

    $this->assertEquals(1, count($c->getAttachments()));

    $this->assertEquals('a', $c->getAttachments()[0]->getFallback());
  }

  public function testSetIconToEmoji()
  {
    $client = $this->getClient();

    $client->setIcon(':ghost:');

    $this->assertEquals(Client::ICON_TYPE_EMOJI, $client->getIconType());

    $this->assertEquals(':ghost:', $client->getIcon());
  }

  public function testSetIconToUrl()
  {
    $client = $this->getClient();

    $client->setIcon('http://www.fake.com/someimage.png');

    $this->assertEquals(Client::ICON_TYPE_URL, $client->getIconType());

    $this->assertEquals('http://www.fake.com/someimage.png', $client->getIcon());
  }

  public function testInstantiationSetsCorrectValues()
  {
    $data = [
      'channel' => '#test',
      'username' => 'Test Username',
      'icon' => ':heart_eyes:'
    ];

    $client = new Client($this->getEndpoint(), $data);

    $this->assertEquals('#test', $client->getChannel());

    $this->assertEquals('Test Username', $client->getUsername());

    $this->assertEquals(':heart_eyes:', $client->getIcon());

    $this->assertEquals(Client::ICON_TYPE_EMOJI, $client->getIconType());
  }

  private function getClient()
  {
    return new Client($this->getEndpoint());
  }

  private function getEndpoint()
  {
    return 'http://fake.endpoint';
  }

}