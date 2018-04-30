<div class="row  border-bottom">
    <div class="col-lg-12">
        <div class="tab_title">
            <ul class="nav nav-pills">
                <li class="current">
                    <a href="/web/account/index">账户列表</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="row m-t">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="m-b-md">
                    <a class="btn btn-outline btn-primary pull-right"
                       href="<?= \app\common\services\UrlService::buildWebUrl('/account/set', ['id' => $info['uid']]); ?>">
                        <i class="fa fa-pencil"></i>编辑
                    </a>
                    <h2>账户信息</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 text-center">
                <img class="img-circle circle-border" src="/images/common/qrcode.jpg" width="100px" height="100px"/>
            </div>
            <div class="col-lg-10">
                <p class="m-t"><?= $info ? $info['nickname'] : ''; ?></p>
                <p>手机：<?= $info ? $info['mobile'] : ''; ?></p>
                <p>邮箱：<?= $info ? $info['email'] : ''; ?></p>
            </div>
        </div>
        <div class="row m-t">
            <div class="col-lg-12">
                <div class="panel blank-panel">
                    <div class="panel-heading">
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="javascript:void(0);" data-toggle="tab" aria-expanded="false">访问记录</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>访问时间</th>
                                        <th>访问Url</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($access_list as $v): ?>
                                        <tr>
                                            <td>
                                                <?= $v['created_time']; ?>
                                            </td>
                                            <td>
                                                <?= $v['target_url']; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


