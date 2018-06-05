<?php

use \app\common\services\UrlService;

\app\common\services\StaticService::includeAppJsStatic('js/web/member/index.js', app\assets\WebAsset::className());
?>
<div class="row  border-bottom">
    <div class="col-lg-12">
        <div class="tab_title">
            <ul class="nav nav-pills">
                <li class="current">
                    <a href="/web/member/index">会员列表</a>
                </li>
                <li>
                    <a href="/web/member/comment">会员评论</a>
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
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="mix_kw" placeholder="请输入关键字" class="form-control" value="">
                        <span class="input-group-btn">
                            <button type="button" class="btn  btn-primary search">
                                <i class="fa fa-search"></i>搜索
                            </button>
                        </span>
                        <input type="hidden" name="p" value="1">
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-lg-12">
                    <a class="btn btn-w-m btn-outline btn-primary pull-right" href="/web/member/set">
                        <i class="fa fa-plus"></i>会员
                    </a>
                </div>
            </div>

        </form>
        <table class="table table-bordered m-t">
            <thead>
            <tr>
                <th>头像</th>
                <th>姓名</th>
                <th>手机</th>
                <th>性别</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $v):?>
                    <tr>
                        <td><img alt="image" class="img-circle"
                                 src="/uploads/avatar/20170313/159419a875565b1afddd541fa34c9e65.jpg"
                                 style="width: 40px;height: 40px;"></td>
                        <td><?=$v['nickname'];?></td>
                        <td><?=$v['mobile'];?></td>
                        <td><?=$v['sex_desc'];?></td>
                        <td><?=$v['status_desc'];?></td>
                        <td>
                            <a href="<?= UrlService::buildWebUrl('/member/info',['id'=>$v['id']]);?>">
                                <i class="fa fa-eye fa-lg"></i>
                            </a>
                            <a class="m-l" href="<?= UrlService::buildWebUrl('/member/set',['id'=>$v['id']]);?>">
                                <i class="fa fa-edit fa-lg"></i>
                            </a>
                            <?php if($v['status']):?>
                                <a class="m-l remove" href="javascript:void(0);" data="<?=$v['id'];?>">
                                    <i class="fa fa-trash fa-lg"></i>
                                </a>
                            <?php else:?>
                                <a class="m-l recove" href="javascript:void(0);" data="<?=$v['id'];?>">
                                    <i class="fa fa-rotate-left fa-lg"></i>
                                </a>
                            <?php endif;?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-lg-12">
                <span class="pagination_count" style="line-height: 40px;">共<?= $pages['total_count']; ?>
                    条记录 | 每页<?= $pages['page_size']; ?>条</span>

                <ul class="pagination pagination-lg pull-right" style="margin: 0 0 ;">
                    <?php for ($_page = 1; $_page <= $pages['total_page']; $_page++): ?>
                        <?php if ($_page == $pages['p']): ?>
                            <li class="active"><a
                                        href="<?= UrlService::buildNullUrl(); ?>"><?= $_page; ?></a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?= UrlService::buildWebUrl('/member/index', ['p' => $_page]); ?>;"><?= $_page; ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

