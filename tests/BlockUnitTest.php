<?php
namespace Slack\Tests;

use InvalidArgumentException;
use Maknz\Slack\Block;
use Maknz\Slack\Block\Section;

class BlockUnitTest extends TestCase
{
    public function testFactoryWithArray()
    {
        $element = Block::factory([
            'type' => 'section',
        ]);

        $this->assertInstanceOf(Section::class, $element);
    }

    public function testFactoryMissingType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create Block without a type attribute');
        $element = Block::factory([]);
    }

    public function testFactoryInvalidType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/Block type must be one of/');
        $element = Block::factory([
            'type' => 'invalid',
        ]);
    }
}
