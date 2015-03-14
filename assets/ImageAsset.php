<?php

namespace artkost\attachment\fileapi\assets;

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
        'artkost\attachment\fileapi\assets\FileAsset',
    ];
}
