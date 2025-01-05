<?php
namespace backend\controllers;

use quartz\multipleUpload\actions\MultipleDeleteAction;
use quartz\multipleUpload\actions\MultipleUploadAction;
use yii\filters\AccessControl;
use yii\web\Controller;

use quartz\fileapi\actions\UploadAction as FileAPIUpload;

class UploaderController extends Controller
{

    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'fileapi-upload',
                            'multiple-upload',
                            'multiple-delete',
                            'fileapi-upload-document',
                        ],
                        'allow' => true,
                        'roles' => ['upload'],
                    ]
                ],
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function actions()
    {
        /* При указании максимального размера файла нужно учитывать
        параметры post_max_size и upload_max_filesize в php.ini, т.к. если размер, указанный
        в этих параметрах ниже параметра maxSize, то файл будет отсекаться еще до контроллера настройками php
        post_max_size - данная настройка определяет макс. размер тела запроса POST(в том числе в котором летит file)
        upload_max_filesize - определяет максимальный размер файлов загруженных на сервер */
        return [
            'multiple-upload' => [
                'class' => MultipleUploadAction::class,
            ],
            'multiple-delete' => [
                'class' => MultipleDeleteAction::class,
            ],
            'fileapi-upload-document' => [
                'class' => FileAPIUpload::class,
                'path' => \Yii::$app->params['uploadTempDir'],
                'uploadOnlyImage' => false,
                'validatorOptions' => [
                    'extensions' => 'pdf,xdoc,doc',
                    'maxSize' => 5242880 /* 5мб */,
                    'checkExtensionByMimeType' => false
                ]
            ],
            'fileapi-upload' => [
                'class' => FileAPIUpload::class,
                'path' => \Yii::$app->params['uploadTempDir'],
                'validatorOptions' => ['extensions' => 'png, jpeg, jpg, gif', 'checkExtensionByMimeType' => false]
            ],
        ];
    }
}
