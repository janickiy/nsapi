<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class BowerAsset
 * @package backend\assets
 */
class BowerAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $js = [
        'bootstrap-tabdrop/src/js/bootstrap-tabdrop.js',
    ];

    public $css = [
    ];
}
