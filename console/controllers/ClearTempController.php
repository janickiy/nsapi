<?php

namespace console\controllers;

use yii\console\Controller;

/**
 * Class ClearTempController
 * @package console\controllers
 */
class ClearTempController extends Controller
{
    /**
     * Удаление темповых файлов
     * @return void
     */
    public function actionIndex()
    {
        /** @var string $tempPath */
        $tempPath = \Yii::getAlias(\Yii::$app->params['uploadTempDir']);
        /** @var array $files */
        $files = scandir($tempPath);
        $counter = 0;
        $excludedFileNames = ['.', '..', '.gitkeep'];
        $date = date('Y-m-d H:i:s', strtotime('-1 hour'));
        foreach ($files as $file) {
            /** @var int $filectime */
            $filectime = filectime($tempPath . '/' . $file);
            if (date('Y-m-d H:i:s', $filectime) < $date
                && !in_array($file, $excludedFileNames)
            ) {
                unlink($tempPath . '/' . $file);
                $counter++;
            }
        }

        echo "Удалено $counter файлов\n";
    }
}
