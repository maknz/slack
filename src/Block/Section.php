<?php namespace Maknz\Slack\Block;

use \Maknz\Slack\Block;

class Section extends Block
{
    protected $text = [
        'type' => 'section',
        'text' => ''
    ];
    protected $accessory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        if (isset($attributes['text'])) {
            $this->setText($attributes['text']);
        }

        if (isset($attributes['accessory'])) {
            $this->setAccessory($attributes['accessory']);
        }
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setAccessory($accessory)
    {
        $this->accessory = $accessory;
    }

    public function getText()
    {
        error_log(print_r('getText', true));
        return [
            'type' => $this->text['type'],
            'text' => $this->text['text']
        ];
    }

    public function getAccessory()
    {
        return $this->accessory;
    }

    public function toArray()
    {
        error_log(print_r('toArray section', true));
        $data = [
            'type' => 'section',
            'text' => $this->getText(),
            'block_id' => $this->getBlockId()
        ];
        error_log(print_r($data, true));

        $accessory = $this->getAccessory();
        error_log('acc: '.var_export($accessory, true));

        if ($accessory !== null) {
            $data['accessory'] = $accessory;
        }

        return $data;
    }
}