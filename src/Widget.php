<?php

namespace artkost\yii2\attachmentFileAPI;

use Yii;
use yii\widgets\InputWidget;

class Widget extends InputWidget
{
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('attachment-fileapi/' . $category, $message, $params, $language);
    }
}
