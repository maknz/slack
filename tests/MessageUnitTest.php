<?php

use Maknz\Slack\Client;
use Maknz\Slack\Message;
use Maknz\Slack\Attachment;

class MessageUnitTest extends PHPUnit_Framework_TestCase {

  public function testInstantiation()
  {
    $this->assertInstanceOf('Maknz\Slack\Message', $this->getMessage());
  }

  public function testSetText()
  {
    $message = $this->getMessage();

    $message->setText('Hello world');

    $this->assertSame('Hello world', $message->getText());
  }

  public function testSetChannelWithTo()
  {
    $message = $this->getMessage();

    $message->to('#php');

    $this->assertSame('#php', $message->getChannel());
  }

  public function testSetChannelWithSetter()
  {
    $message = $this->getMessage();

    $message->setChannel('#php');

    $this->assertSame('#php', $message->getChannel());
  }

  public function testSetUsernameWithFrom()
  {
    $message = $this->getMessage();

    $message->from('Archer');

    $this->assertSame('Archer', $message->getUsername());
  }

  public function testSetUsernameWithSetter()
  {
    $message = $this->getMessage();

    $message->setUsername('Archer');

    $this->assertSame('Archer', $message->getUsername());
  }

  public function testAttachWithArray()
  {
    $message = $this->getMessage();

    $attachmentArray = [
      'fallback' => 'Fallback text for IRC',
      'text' => 'Attachment text',
      'pretext' => 'Attachment pretext',
      'color' => 'bad',
      'fields' => []
    ];

    $message->attach($attachmentArray);

    $attachments = $message->getAttachments();

    $this->assertEquals(1, count($attachments));

    $obj = $attachments[0];

    $this->assertEquals($attachmentArray['fallback'], $obj->getFallback());

    $this->assertEquals($attachmentArray['text'], $obj->getText());

    $this->assertEquals($attachmentArray['pretext'], $obj->getPretext());

    $this->assertEquals($attachmentArray['color'], $obj->getColor());
  }

  public function testAttachWithObject()
  {
    $message = $this->getMessage();

    $obj = new Attachment([
      'fallback' => 'Fallback text for IRC',
      'text' => 'Text'
    ]);

    $message->attach($obj);

    $attachments = $message->getAttachments();

    $this->assertEquals(1, count($attachments));

    $remoteObj = $attachments[0];

    $this->assertEquals($obj, $remoteObj);
  }

  public function testMultipleAttachments()
  {
    $message = $this->getMessage();

    $obj1 = new Attachment([
      'fallback' => 'Fallback text for IRC',
      'text' => 'Text'
    ]);

    $obj2 = new Attachment([
      'fallback' => 'Fallback text for IRC',
      'text' => 'Text'
    ]);

    $message->attach($obj1)->attach($obj2);

    $attachments = $message->getAttachments();

    $this->assertEquals(2, count($attachments));

    $remote1 = $attachments[0];

    $remote2 = $attachments[1];

    $this->assertEquals($obj1, $remote1);

    $this->assertEquals($obj2, $remote2);
  }

  public function testSetAttachmentsWipesExistingAttachments()
  {
    $message = $this->getMessage();

    $obj1 = new Attachment([
      'fallback' => 'Fallback text for IRC',
      'text' => 'Text'
    ]);

    $obj2 = new Attachment([
      'fallback' => 'Fallback text for IRC',
      'text' => 'Text'
    ]);

    $message->attach($obj1)->attach($obj2);

    $this->assertEquals(2, count($message->getAttachments()));

    $message->setAttachments([['fallback' => 'a', 'text' => 'b']]);

    $this->assertEquals(1, count($message->getAttachments()));

    $this->assertEquals('a', $message->getAttachments()[0]->getFallback());
  }

  public function testSetIconToEmoji()
  {
    $message = $this->getMessage();

    $message->setIcon(':ghost:');

    $this->assertEquals(Message::ICON_TYPE_EMOJI, $message->getIconType());

    $this->assertEquals(':ghost:', $message->getIcon());
  }

  public function testSetIconToUrl()
  {
    $message = $this->getMessage();

    $message->setIcon('http://www.fake.com/someimage.png');

    $this->assertEquals(Message::ICON_TYPE_URL, $message->getIconType());

    $this->assertEquals('http://www.fake.com/someimage.png', $message->getIcon());
  }

  protected function getMessage()
  {
    return new Message(Mockery::mock('Maknz\Slack\Client'));
  }

}