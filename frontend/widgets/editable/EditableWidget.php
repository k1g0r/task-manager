<?php
namespace frontend\widgets\editable;

use yii\base\Widget;
use yii\helpers\Url;

class EditableWidget extends Widget
{

    /**
     * Text input
     */
    const INPUT_TEXT = 'text';
    /**
     * Text area
     */
    const INPUT_TEXTAREA = 'textarea';
    /**
     * Dropdown list allowing single select
     */
    const INPUT_DROPDOWN_LIST = 'select';

    const POSITION_TOP = 'top';
    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';
    const POSITION_BOTTOM = 'bottom';

    /**
     * @var array the input type to render for the editing the input in the editable form. This must be one of the
     * `Editable::TYPE` constants.
     */
    public $inputType = self::INPUT_TEXT;

    public $pk;
    public $name;
    public $value;
    public $title = '';
    public $source = '';
    public $url = '';
    public $defaultValue = null;
    public $options = [];
    public $inputClass = [];
    public $asPopover = true;
    public $placement = self::POSITION_TOP;


    public function run()
    {
        $data = [
            'pk' => $this->pk,
            'type' => $this->inputType,
            'title' => $this->title,
            'value' => $this->value,
            'source' => $this->source,
            'url' => Url::to($this->url),
            'defaultValue' => $this->defaultValue,
            'inputclass' => $this->inputClass,
            'name' => $this->name,
            'mode' => $this->asPopover ? 'popup' : 'inline',
            'placement' => $this->placement
        ];

        $value = $data['value'];

        if ($this->inputType == self::INPUT_DROPDOWN_LIST) {
            $value = $this->source[$data['value']];
        }

        return $this->render('editable', [
            'data' => $data,
            'value' => $value,
            'options' => $this->options
        ]);
    }


}