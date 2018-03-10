<?php

namespace artkost\yii2\attachmentFileAPI\assets;

use yii\web\AssetBundle;

/**
 * Widget asset bundle.
 */
class ImageAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $depends = [
        'artkost\yii2\attachmentFileAPI\assets\FileAsset',
    ];
}
