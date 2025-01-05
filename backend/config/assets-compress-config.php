<?php
/**
 * Configuration file for the "yii asset" console command.
 */

// In the console environment, some path aliases may not exist. Please define these:
Yii::setAlias('@webroot', __DIR__ . '/../web');
Yii::setAlias('@web', '/');

return [
    // Adjust command/callback for JavaScript files compressing:
    'jsCompressor' => 'java -jar '.Yii::getAlias('@vendor').'/quartz/yii2-utilities/compiler.jar --warning_level QUIET --js {from} --js_output_file {to}',

    // Adjust command/callback for CSS files compressing:
    'cssCompressor' => 'java -jar '.Yii::getAlias('@vendor').'/quartz/yii2-utilities/yuicompressor.jar --type css {from} -o {to}',

    // The list of asset bundles to compress:
    'bundles' => [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'backend\assets\BowerAsset',
        'backend\assets\AppAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ],

    // Asset bundle for compression output:
    'targets' => [
        'all' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'all-{hash}.js',
            'css' => 'all-{hash}.css',
        ],
    ],

    // Asset manager configuration:
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
    ],
];
