<?php
namespace Slack\Tests\Block;

use Maknz\Slack\Block\Image;
use Maknz\Slack\BlockElement\Text;
use Slack\Tests\TestCase;

class ImageUnitTest extends TestCase
{
    public function testImageFromArray()
    {
        $i = new Image([
            'image_url' => 'http://fake.host/image.png',
            'alt_text'  => 'Alt text',
            'title'     => 'Image title',
        ]);

        $this->assertSame('http://fake.host/image.png', $i->getUrl());

        $this->assertSame('Alt text', $i->getAltText());

        $this->assertSame(Text::TYPE_PLAIN, $i->getTitle()->getType());
    }

    public function testToArray()
    {
        $i = new Image([
            'image_url' => 'http://fake.host/image.png',
            'alt_text'  => 'Alt text',
            'title'     => 'Image title',
            'block_id'  => 'block_1234',
        ]);

        $out = [
            'type' => 'image',
            'image_url' => 'http://fake.host/image.png',
            'alt_text' => 'Alt text',
            'title' => [
                'type' => Text::TYPE_PLAIN,
                'text' => 'Image title',
                'emoji' => false,
            ],
            'block_id' => 'block_1234',
        ];

        $this->assertEquals($out, $i->toArray());
    }
}
