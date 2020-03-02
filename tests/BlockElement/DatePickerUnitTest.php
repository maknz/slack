<?php
namespace Slack\Tests\BlockElement;

use DateTime;
use Maknz\Slack\BlockElement\DatePicker;
use Maknz\Slack\BlockElement\Text;
use Slack\Tests\TestCase;

class DatePickerUnitTest extends TestCase
{
    public function testDatePickerFromArray()
    {
        $date = new DateTime('2020-01-01');

        $d = new DatePicker([
            'action_id'    => 'Date action',
            'placeholder'  => 'Date placeholder',
            'initial_date' => $date,
        ]);

        $this->assertSame(Text::TYPE_PLAIN, $d->getPlaceholder()->getType());

        $this->assertSame('Date placeholder', $d->getPlaceholder()->getText());

        $this->assertSame($date, $d->getInitialDate());
    }

    public function testToArray()
    {
        $date = new DateTime('2020-01-01');

        $d = new DatePicker([
            'action_id'    => 'Date action',
            'placeholder'  => 'Date placeholder',
            'initial_date' => $date,
            'confirm'   => [
                'title'   => 'Confirmation title',
                'text'    => 'Confirmation text',
                'confirm' => 'Confirm',
                'deny'    => 'Deny',
            ],
        ]);

        $out = [
            'type' => 'datepicker',
            'action_id' => 'Date action',
            'initial_date' => '2020-01-01',
            'placeholder' => [
                'type' => Text::TYPE_PLAIN,
                'text' => 'Date placeholder',
                'emoji' => false,
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

        $this->assertEquals($out, $d->toArray());
    }
}
