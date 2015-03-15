<?php

namespace artkost\attachment\fileapi;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->i18n->translations['attachment-fileapi/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@artkost/attachment/fileapi/messages',
            'forceTranslation' => true,
            'fileMap' => [
                'attachment-fileapi/widget' => 'widget.php',
            ]
        ];
    }
}
