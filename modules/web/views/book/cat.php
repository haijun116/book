<?php

use \app\common\services\StaticService;
use \app\common\services\UrlService;

StaticService::includeAppJsStatic('js/web/book/cat.js', app\assets\WebAsset::className());
?>
<div class="row  border-bottom">
    <div class="col-lg-12">
        <div class="tab_title">
            <ul class="nav nav-pills">
                <li>
                    <a href="/web/book/index">图书列表</a>
                </li>
                <li class="current">
                    <a href="/web/book/cat">分类列表</a>
                </li>
                <li>
                    <a href="/web/book/images">图片资源</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <form class="form-inline wrap_search">
            <div class="row  m-t p-w-m">
                <div class="form-group">
                    <select name="status" class="form-control inline">
                        <option value="-1">请选择状态</option>
                        <option value="1">正常</option>
                        <option value="0">已删除</option>
                    </select>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-w-m btn-outline btn-primary pull-right" href="/web/book/cat_set">
                        <i class="fa fa-plus"></i>分类
                    </a>
                </div>
            </div>

        </form>
        <table class="table table-bordered m-t">
            <thead>
            <tr>
                <th>序号</th>
                <th>分类名称</th>
                <th>状态</th>
                <th>权重</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($list): ?>
                <?php foreach ($list as $_item): ?>
                    <tr>
                        <td><?= $_item['id']; ?></td>
                        <td><?= $_item['name']; ?></td>
                        <td><?= $status_mapping[$_item['status']]; ?></td>
                        <td><?= $_item['weight']; ?></td>
                        <td>
                            <?php if ($_item['status']): ?>
                                <a class="m-l"
                                   href="<?= UrlService::buildWebUrl("/book/cat_set", ['id' => $_item['id']]); ?>">
                                    <i class="fa fa-edit fa-lg"></i>
                                </a>

                                <a class="m-l remove" href="<?= UrlService::buildNullUrl(); ?>"
                                   data="<?= $_item['id']; ?>">
                                    <i class="fa fa-trash fa-lg"></i>
                                </a>
                            <?php else: ?>
                                <a class="m-l recover" href="<?= UrlService::buildNullUrl(); ?>"
                                   data="<?= $_item['id']; ?>">
                                    <i class="fa fa-rotate-left fa-lg"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">暂无数据</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>



