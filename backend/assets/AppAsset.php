<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
		'vendors/bootstrap/dist/css/bootstrap.min.css',
		'vendors/font-awesome/css/font-awesome.min.css',
		'vendors/nprogress/nprogress.css',
		'vendors/iCheck/skins/flat/green.css',
		'vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css',
		'vendors/jqvmap/dist/jqvmap.min.css',
		'vendors/bootstrap-daterangepicker/daterangepicker.css',
		'build/css/custom.min.css',
    ];
    public $js = [
		'vendors/jquery/dist/jquery.min.js',
		'vendors/bootstrap/dist/js/bootstrap.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
	public $jsOptions = array(
    'position' => \yii\web\View::POS_HEAD
);
}
