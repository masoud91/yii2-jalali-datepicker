<?php
/**
 * @link https://idco.io
 * @copyright Copyright (c) 2017 infinity Design
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace infinitydesign\jalaliDatePicker;

use infinitydesign\jalaliDatePicker\jalaliDatePickerAsset;

use Yii;
use yii\base\Model;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\Widget as Widget;

/**
 * @author Infinity Design <im@idco.io>
 */
class jalaliDatePicker extends Widget
{

    /**
     * @var string $selector
     */
    public $selector;

    /**
     * @var string attribute associated with this widget.
     */
    public $attribute;

    /**
     * @var \yii\db\ActiveRecord model associated with this widget.
     */
    public $model;

    /**
     * @var string JS Callback for Daterange picker
     */
    public $callback;
    /**
     * @var array Options to be passed to daterange picker
     */
    public $options = [];
    /**
     * @var string[] the JavaScript event handlers.
     */
    public $events = array();
    /**
     * @var array the HTML attributes for the widget container.
     */
    public $htmlOptions = [];

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        /**
         * checks for the element id
         */
        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->getId();
        }
        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->registerPlugin();
    }

    protected function registerPlugin()
    {
        if ($this->selector) {
            $this->registerJs($this->selector, $this->options, $this->callback);
        } else {
            $id = $this->htmlOptions['id'];

            /**
             * Determine name
             */
            if (isset($this->htmlOptions['name']))
                $name = $this->htmlOptions['name'];
            else
                $name = "jalali_date_picker-$id";

            /**
             * Determine value
             */
            if (isset($this->htmlOptions['value']))
                $value = $this->htmlOptions['value'];
            else
                $value = '';

            /**
             * Check if model set
             */
            if (!empty($this->model))
                echo Html::activeInput('text', $this->model, $this->attribute, $this->htmlOptions);
            else
                echo Html::input('text', $name, $value, $this->htmlOptions);

            // echo Html::tag('input', '', $this->htmlOptions);م
            $this->registerJs("#{$id}", $this->options, $this->callback);
        }


    }

    protected function registerJs($selector, $options, $callback)
    {
        $view = $this->getView();

        jalaliDatePickerAsset::register($view);

        $js = [];
        $js[] = '$("' . $selector . '").datepicker(' . Json::encode($options) . ($callback ? ', ' . Json::encode($callback) : '') . ')';
        foreach ($this->events as $event => $handler)
            $js[] = ".on('{$event}', " . Json::encode($handler) . ")";

        $view->registerJs(implode("\n", $js) . ';', View::POS_READY);

    }
}