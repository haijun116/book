<?php
    use \app\common\services\UtilService;
    use \app\common\services\UrlService;
    use app\common\services\StaticService;
    StaticService::includeAppJsStatic('js/m/default/index.js',app\assets\WebAsset::className());
?>
<div style="min-height: 500px;">
    <div class="shop_header">
        <i class="shop_icon"></i>
        <strong>编程浪子的博客</strong>
    </div>


    <div id="slideBox" class="slideBox">
        <?php if(isset($images)):?>
            <div class="bd">
                <ul>
                    <?php foreach ($images as $item):?>
                        <li><img style="max-height: 250px;" src="<?=UrlService::buildPicUrl('brand',$item['image_key']);?>"/>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        <?php endif;?>
        <div class="hd">
            <ul></ul>
        </div>
    </div>
    <div class="fastway_list_box">
        <ul class="fastway_list">
            <li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>品牌名称：<?=UtilService::encode($info['name']);?></span></a></li>
            <li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>联系电话：<?=UtilService::encode($info['mobile']);?></span></a></li>
            <li><a href="javascript:void(0);"
                   style="padding-left: 0.1rem;"><span>联系地址：<?=UtilService::encode($info['address']);?></span></a></li>
            <li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>品牌介绍：<?=UtilService::encode($info['description']);?></span></a>
            </li>
        </ul>
    </div>
</div>

