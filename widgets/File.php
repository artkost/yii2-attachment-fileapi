<?php

namespace artkost\attachmentFileAPI\widgets;

use artkost\attachment\behaviors\AttachBehavior;
use artkost\attachment\Manager;
use artkost\attachment\models\AttachmentFile;
use artkost\attachmentFileAPI\Asset;
use artkost\attachmentFileAPI\assets\FileAsset;
use artkost\attachmentFileAPI\Widget;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class File extends Widget
{
    /**
     * @var url
     */
    public $url;

    /**
     * @var string FileAPI selector
     */
    public $selector;

    /**
     * @var array
     */
    protected $defaultSettings = [
        'autoUpload' => false
    ];

    /**
     * Widget settings.
     *
     * @var array {@link https://github.com/RubaXa/jquery.fileapi/ FileAPI options}
     */
    public $settings = [];

    /**
     * @var string Widget template view
     *
     * @see \yii\base\Widget::render
     */
    public $template;

    protected $_attachments = [];

    protected $_multiple;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->settings = ArrayHelper::merge($this->defaultSettings, $this->settings);

        $this->checkMultiple();

        $this->setupCsrf();

        $this->setupUrl();

        $this->setupAttachments();

        $this->setupTemplate();
    }

    public function setupCsrf()
    {
        $request = Yii::$app->getRequest();

        if ($request->enableCsrfValidation === true) {
            $this->settings['data'][$request->csrfParam] = $request->getCsrfToken();
        }
    }

    public function setupUrl()
    {
        $request = Yii::$app->getRequest();

        if (!isset($this->settings['url'])) {
            $this->settings['url'] = $this->url ? Url::to($this->url) : $request->getUrl();
        } else {
            $this->settings['url'] = Url::to($this->settings['url']);
        }
    }

    public function setupAttachments()
    {
        $related = $this->getModelAttributeValue();

        $this->_attachments = is_array($related) ? $related : [$related];
    }

    public function setupTemplate()
    {
        if ($this->template === null) {
            $this->template = $this->isMultiple() ? 'file_multiple' : 'file_single';
        }
    }

    public function checkMultiple()
    {
        $config = $this->getAttachmentModelConfig();

        if ($config) {
            $this->setMultiple($config['multiple']);
            $this->settings['multiple'] = $config['multiple'];
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerFiles();
        $this->register();

        $data = [
            'selector' => $this->getSelector(),
            'settings' => $this->settings,
            'paramName' => Manager::PARAM_NAME,
            'value' => $this->value,
            'inputName' => $this->getHiddenInputName()
        ];

        return $this->render($this->template, $data);
    }

    /**
     * Registering already uploaded files.
     */
    public function registerFiles()
    {
        foreach ($this->_attachments as $attach) if ($attach) {
            $this->settings['files'][] = [
                'id' => $attach->id,
                'src' => $attach->fileUrl,
                'name' => $attach->name,
                'type' => $attach->mime
            ];
        }
    }

    /**
     * Register all widget scripts and callbacks
     */
    public function register()
    {
        $this->registerMainClientScript();
        $this->registerClientScript();
    }

    /**
     * Register widget main asset.
     */
    protected function registerMainClientScript()
    {
        $view = $this->getView();

        Asset::register($view);
    }

    /**
     * Register widget asset.
     */
    public function registerClientScript()
    {
        $view = $this->getView();

        FileAsset::register($view);

        $selector = $this->getSelector();
        $options = Json::encode($this->settings);

        $view->registerJs("jQuery('#$selector').yiiAttachmentFileAPI('file', $options);");
    }

    /**
     * @return bool
     */
    public function isMultiple()
    {
        return $this->_multiple;
    }

    /**
     * @param $value
     */
    public function setMultiple($value)
    {
        $this->_multiple = $value;
        $this->settings['multiple'] = $value;
    }

    /**
     * @return string Widget selector
     */
    public function getSelector()
    {
        return $this->selector !== null ? $this->selector : 'attachment-' . $this->options['id'];
    }

    /**
     * @return mixed
     */
    protected function getModelAttributeValue()
    {
        return $this->model->{$this->attribute};
    }

    /**
     * @return array
     */
    protected function getAttachmentModelConfig()
    {
        return $this->model->getAttachmentConfig($this->attribute);
    }

    /**
     * @return AttachmentFile
     */
    protected function getAttachmentModel()
    {
        return Manager::getInstance()->getAttachmentModel(get_class($this->model), $this->attribute);
    }

    /**
     * @return string
     */
    protected function getHiddenInputName()
    {
        return $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->name;
    }

    protected function getHiddenInput()
    {
        return $this->hasModel() ?
            Html::activeHiddenInput(
                $this->model,
                $this->attribute,
                $this->options
            ) :
            Html::hiddenInput(
                $this->name,
                $this->value,
                $this->options
            );
    }
}
