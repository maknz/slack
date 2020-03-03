<?php
namespace Slack\Tests\Block;

use Maknz\Slack\Block\Divider;
use Slack\Tests\TestCase;

class DividerUnitTest extends TestCase
{
    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testDividerFromArray()
    {
        $d = new Divider([
            'block_id' => 'block-1234',
        ]);

        $this->assertSame('block-1234', $d->getBlockId());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testToArray()
    {
        $d = new Divider([]);

        $out = [
            'type' => 'divider',
        ];

        $this->assertEquals($out, $d->toArray());
    }
}
