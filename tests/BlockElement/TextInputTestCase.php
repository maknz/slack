<?php
namespace Slack\Tests\BlockElement;

use Maknz\Slack\BlockElement\Text;
use Maknz\Slack\BlockElement\TextInput;
use Slack\Tests\TestCase;

class TextInputUnitTest extends TestCase
{
    public function testTextInputFromArray()
    {
        $t = new TextInput([
            'action_id' => 'input_action',
            'placeholder' => 'Placeholder text',
        ]);

        $this->assertSame('input_action', $t->getActionId());

        $this->assertSame(Text::TYPE_PLAIN, $t->getPlaceholder()->getType());

        $this->assertSame('Placeholder text', $t->getPlaceholder()->getText());
    }

    public function testToArray()
    {
        $t = new TextInput([
            'action_id' => 'input_action',
            'placeholder' => 'Placeholder text',
            'initial_value' => 'Initial value',
            'min_length' => 5,
            'max_length' => 20,
        ]);

        $out = [
            'type' => 'plain_text_input',
            'action_id' => 'input_action',
            'placeholder' => [
                'type' => Text::TYPE_PLAIN,
                'text' => 'Placeholder text',
                'emoji' => false,
            ],
            'initial_value' => 'Initial value',
            'min_length' => 5,
            'max_length' => 20,
        ];

        $this->assertEquals($out, $t->toArray());
    }
}
