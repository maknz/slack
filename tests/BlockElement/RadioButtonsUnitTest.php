<?php
namespace Slack\Tests\BlockElement;

use InvalidArgumentException;
use Maknz\Slack\BlockElement\RadioButtons;
use Maknz\Slack\BlockElement\Text;
use Maknz\Slack\Object\Option;
use Slack\Tests\TestCase;

class RadioButtonsUnitTest extends TestCase
{
    public function testRadioButtonsFromArray()
    {
        $r = new RadioButtons([
            'action_id' => 'Radio action',
            'options'   => [[
                'text'  => 'Option 1',
                'value' => 'option_1',
            ], [
                'text'  => 'Option 2',
                'value' => 'option_2',
            ]],
        ]);

        $options = $r->getOptions();

        $this->assertSame(2, count($options));

        $this->assertSame(Text::TYPE_PLAIN, $options[0]->getText()->getType());

        $this->assertSame('Option 1', $options[0]->getText()->getText());

        $this->assertSame('option_1', $options[0]->getValue());
    }

    public function testAddOptionAsObject()
    {
        $r = new RadioButtons([
            'action_id' => 'Radio action',
        ]);

        $option = new Option([
            'text'  => 'Option 1',
            'value' => 'option_1',
        ]);

        $r->addOption($option);
        $options = $r->getOptions();

        $this->assertSame(1, count($options));

        $this->assertSame($option, $options[0]);
    }

    public function testInitiallySelected()
    {
        $r = new RadioButtons([
            'action_id' => 'Radio action',
            'options'   => [[
                'text'  => 'Option 1',
                'value' => 'option_1',
            ], [
                'text'     => 'Option 2',
                'value'    => 'option_2',
                'selected' => true,
            ]],
        ]);

        $options = $r->getOptions();
        $initialOption = $r->getInitialOption();

        $this->assertTrue($options[1]->isInitiallySelected());

        $this->assertSame('Option 2', $initialOption->getText()->getText());
    }

    public function testCreateWithTooManySelected()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only one option can be initially selected');

        $r = new RadioButtons([
            'action_id' => 'Radio action',
            'options'   => [[
                'text'     => 'Option 1',
                'value'    => 'option_1',
                'selected' => true,
            ], [
                'text'     => 'Option 2',
                'value'    => 'option_2',
                'selected' => true,
            ]],
        ]);
    }

    public function testAddOptionWithTooManySelected()
    {
        $r = new RadioButtons([
            'action_id' => 'Radio action',
            'options'   => [[
                'text'     => 'Option 1',
                'value'    => 'option_1',
                'selected' => true,
            ]],
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only one option can be initially selected');
        $r->addOption([
            'text'     => 'Option 2',
            'value'    => 'option_2',
            'selected' => true,
        ]);
    }

    public function testToArray()
    {
        $r = new RadioButtons([
            'action_id' => 'Radio action',
            'options'   => [[
                'text'  => 'Option 1',
                'value' => 'option_1',
            ], [
                'text'     => 'Option 2',
                'value'    => 'option_2',
                'selected' => true,
            ]],
            'confirm'   => [
                'title'   => 'Confirmation title',
                'text'    => 'Confirmation text',
                'confirm' => 'Confirm',
                'deny'    => 'Deny',
            ],
        ]);

        $out = [
            'type' => 'radio_buttons',
            'action_id' => 'Radio action',
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
            'initial_option' => [
                'text'  => [
                    'type' => 'plain_text',
                    'text' => 'Option 2',
                    'emoji' => false,
                ],
                'value' => 'option_2',
            ],
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

        $this->assertEquals($out, $r->toArray());
    }
}
