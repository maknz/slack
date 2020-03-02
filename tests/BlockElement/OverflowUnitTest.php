<?php
namespace Slack\Tests\BlockElement;

use Maknz\Slack\BlockElement\Overflow;
use Maknz\Slack\BlockElement\Text;
use Maknz\Slack\Object\Option;
use Slack\Tests\TestCase;

class OverflowUnitTest extends TestCase
{
    public function testCheckboxesFromArray()
    {
        $o = new Overflow([
            'action_id' => 'Overflow action',
            'options'   => [[
                'text'  => 'Option 1',
                'value' => 'option_1',
            ], [
                'text'  => 'Option 2',
                'value' => 'option_2',
            ]],
        ]);

        $options = $o->getOptions();

        $this->assertSame(2, count($options));

        $this->assertSame(Text::TYPE_PLAIN, $options[0]->getText()->getType());

        $this->assertSame('Option 1', $options[0]->getText()->getText());

        $this->assertSame('option_1', $options[0]->getValue());
    }

    public function testAddOptionAsObject()
    {
        $o = new Overflow([
            'action_id' => 'Overflow action',
        ]);

        $option = new Option([
            'text'  => 'Option 1',
            'value' => 'option_1',
        ]);

        $o->addOption($option);
        $options = $o->getOptions();

        $this->assertSame(1, count($options));

        $this->assertSame($option, $options[0]);
    }

    public function testToArray()
    {
        $o = new Overflow([
            'action_id' => 'Overflow action',
            'options'   => [[
                'text'  => 'Option 1',
                'value' => 'option_1',
            ], [
                'text'  => 'Option 2',
                'value' => 'option_2',
            ]],
            'confirm'   => [
                'title'   => 'Confirmation title',
                'text'    => 'Confirmation text',
                'confirm' => 'Confirm',
                'deny'    => 'Deny',
            ],
        ]);

        $out = [
            'type' => 'overflow',
            'action_id' => 'Overflow action',
            'options'   => [[
                'text'  => [
                    'type' => 'plain_text',
                    'text' => 'Option 1',
                    'emoji' => false,
                ],
                'value' => 'option_1',
            ], [
                'text'  => [
                    'type' => 'plain_text',
                    'text' => 'Option 2',
                    'emoji' => false,
                ],
                'value' => 'option_2',
            ]],
            'confirm'   => [
                'title'   => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Confirmation title',
                    'emoji' => false,
                ],
                'text'    => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Confirmation text',
                    'emoji' => false,
                ],
                'confirm' => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Confirm',
                    'emoji' => false,
                ],
                'deny'    => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Deny',
                    'emoji' => false,
                ],
            ],
        ];

        $this->assertEquals($out, $o->toArray());
    }
}
