<?php

namespace artkost\attachmentFileAPI\assets;

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
        'artkost\attachmentFileAPI\assets\FileAsset',
    ];
}
