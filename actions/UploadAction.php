<?php

namespace artkost\attachmentFileAPI\actions;

use artkost\attachment\Action;
use artkost\attachment\Manager;
use artkost\attachment\models\AttachmentFile;
use Yii;
use yii\base\NotSupportedException;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Class SingleUploadAction
 * @package app\modules\attachment\fileapi\actions
 */
class UploadAction extends Action
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $this->callCheckAccess();

        if ($this->getRequest()->isPost) {
            $file = Manager::getUploadedFile();

            $attachModel = $this->getAttachmentModel();

            if ($attachModel && $file) {

                $status = $attachModel->setFile($file)->setUser($this->getUser())->save();

                $result = $this->formatFile($attachModel, $status);
            } else {
                throw new NotSupportedException('File not given or model does not support attachments.');
            }

            return $result;
        } else {
            throw new BadRequestHttpException('Only POST is allowed');
        }
    }

    /**
     * @param AttachmentFile $model
     * @param boolean $status
     * @return array
     */
    protected function formatFile($model, $status)
    {
        return [
            'status' => $status,
            'id' => $model->id,
            'src' => $model->fileUrl,
            'name' => $model->name,
            'size' => $model->size,
            'errors' => $model->errors
        ];
    }

    /**
     * @return null|\yii\web\IdentityInterface
     */
    protected function getUser()
    {
        return Yii::$app->user->identity;
    }

    /**
     * @return \yii\web\Request
     */
    protected function getRequest()
    {
        return Yii::$app->request;
    }
}
