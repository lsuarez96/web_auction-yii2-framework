<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ProdAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
         'css/bootstrap.css',
        'css/etalage.css',
        'css/fwslider.css',
        'css/megamenu.css',
        'css/site.css',
        'css/style.css',
        'css/countdown.css',
    ];
    public $js = [
         'js/jquery-2.0.3.js',
        'js/jquery-ui.min.js',
        'js/menu_jquery.js',
        'js/fwslider.js',
        'js/jquery.etalage.min.js',
        'js/jquery.jscrollpane.min.js',
        'js/megamenu.js',
        'js/lsg.script.js',
        'js/jquery.countdown.js',
        'js/bootstrap.js',
        'js/filter.products.script.js',
        'js/lsg.load.data.onscroll.script.js',
        'js/push.producs.script.js',
        'js/post.coments.script.js',
        'js/lsg.product.timer.script.js',
        'js/monitor.auction.script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
