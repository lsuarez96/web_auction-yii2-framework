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
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/bootstrap.css',
        'css/easy-responsive-tabs',
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
        'js/jspdf.min.js',
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
        'js/jspdf.plugin.javascript.js',
        'js/jspdf.plugin.ie_bellow_9_shim.js',
        'js/jspdf.js',
        'js/jspdf.plugin.addimage.js',
        'js/jspdf.plugin.cell.js',
        'js/jspdf.plugin.sillysvgrenderer.js',
        'js/jspdf.plugin.split_text_to_size.js',
        'js/jspdf.plugin.standard_fonts_metrics.js',
        'js/jspdf.plugin.split_text_to_size.js',

        'js/from_html.js',
        'js/lsg.export.pdf.js',
        'js/easyResponsiveTabs.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
