<?php


function is_json($json_string) {
    if (!is_string($json_string))
        return false;
    
    return !preg_match('/[^,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t]/', preg_replace('/"(\\.|[^"\\\\])*"/', '', $json_string));
}

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

if (file_exists(__DIR__ . '/vendor/yiisoft/yii/framework/yii.php')) {
    require_once __DIR__ . '/vendor/yiisoft/yii/framework/yii.php';
} else if (file_exists(__DIR__ . '/../yii/framework/yii.php')) {
    require_once __DIR__ . '/../yii/framework/yii.php';
} else
    die("yii framework not found");

$config = dirname(__FILE__) . '/protected/config/main.php';

Yii::createWebApplication($config)->run();
