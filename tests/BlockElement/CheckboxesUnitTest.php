<?php
namespace Slack\Tests\BlockElement;

use Maknz\Slack\BlockElement\Checkboxes;
use Maknz\Slack\BlockElement\Text;
use Maknz\Slack\Object\Option;
use Slack\Tests\TestCase;

class CheckboxesUnitTest extends TestCase
{
    public function testCheckboxesFromArray()
    {
        $c = new Checkboxes([
            'action_id' => 'Checkbox action',
            'options'   => [[
                'text'  => 'Option 1',
                'value' => 'option_1',
            ], [
                'text'  => 'Option 2',
                'value' => 'option_2',
            ]],
            'url'       => 'https://example.com',
        ]);

        $options = $c->getOptions();

        $this->assertSame(2, count($options));

        $this->assertSame(Text::TYPE_PLAIN, $options[0]->getText()->getType());

        $this->assertSame('Option 1', $options[0]->getText()->getText());

        $this->assertSame('option_1', $options[0]->getValue());
    }

    public function testAddOptionAsObject()
    {
        $c = new Checkboxes([
            'action_id' => 'Checkbox action',
        ]);

        $option = new Option([
            'text'  => 'Option 1',
            'value' => 'option_1',
        ]);

        $c->addOption($option);
        $options = $c->getOptions();

        $this->assertSame(1, count($options));

        $this->assertSame($option, $options[0]);
    }

    public function testInitiallySelected()
    {
        $c = new Checkboxes([
            'action_id' => 'Checkbox action',
            'options'   => [[
                'text'  => 'Option 1',
                'value' => 'option_1',
            ], [
                'text'     => 'Option 2',
                'value'    => 'option_2',
                'selected' => true,
            ]],
        ]);

        $options = $c->getOptions();
        $initialOptions = $c->getInitialOptions();

        $this->assertTrue($options[1]->isInitiallySelected());

        $this->assertSame(1, count($initialOptions));

        $this->assertSame('Option 2', $initialOptions[0]->getText()->getText());
    }

    public function testToArray()
    {
        $c = new Checkboxes([
            'action_id' => 'Checkbox action',
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
            'type' => 'checkboxes',
            'action_id' => 'Checkbox action',
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
            'initial_options' => [[
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

        $this->assertEquals($out, $c->toArray());
    }
}
