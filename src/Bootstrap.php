<?php

namespace artkost\yii2\attachmentFileAPI;

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
            'basePath' => '@artkost/attachmentFileAPI/messages',
            'forceTranslation' => true,
            'fileMap' => [
                'attachment-fileapi/widget' => 'widget.php',
            ]
        ];
    }
}
