<?php

namespace artkost\attachment\fileapi\assets;

use yii\web\AssetBundle;

/**
 * Widget asset bundle.
 */
class FileAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@artkost/attachment/fileapi/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/attachment-widget.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/attachment.FileAPI.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'artkost\attachment\fileapi\Asset',
    ];
}
