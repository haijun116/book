<?php
    use app\common\services\UtilService;
?>
<div class="row  border-bottom">
    <div class="col-lg-12">
        <div class="tab_title">
            <ul class="nav nav-pills">
                <li class="current">
                    <a href="/web/brand/info">品牌信息</a>
                </li>
                <li>
                    <a href="/web/brand/images">品牌相册</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="row m-t">
    <div class="col-lg-9 col-lg-offset-2 m-t">
        <dl class="dl-horizontal">
            <dt>品牌名称</dt>
            <dd><?=$info ? UtilService::encode($info['name']): '';?></dd>
            <dt>品牌Logo</dt>
            <dd>
                <img class="img-circle circle-border" src="/uploads/brand/20170301/a8887738ab1bfd71765dd063fee4ddaa.jpg"
                     style="width: 100px;height: 100px;"/>
            </dd>

            <dt>联系电话</dt>
            <dd><?=$info ? UtilService::encode($info['mobile']): '';?></dd>
            <dt>地址</dt>
            <dd><?=$info ? UtilService::encode($info['address']): '';?></dd>
            <dt>品牌介绍</dt>
            <dd><?=$info ? UtilService::encode($info['description']): '';?></dd>
            <dd>
                <a href="<?=\app\common\services\UrlService::buildWebUrl('/brand/set');?>" class="btn btn-outline btn-primary btn-w-m">编辑</a>
            </dd>
        </dl>
    </div>
</div>





