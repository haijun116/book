;
var user_reset_pwd_ops = {
    init:function () {
        this.eventBind();
    },
    eventBind:function () {
        $('#save').click(function () {
            var $old_password = $('#old_password').val();
            var $new_password = $('#new_password').val();
            $btn_target = $(this);
            if($btn_target.hasClass('disabled')){
                alert('正在处理中,请不要重复点击');
                return false;
            }
            $btn_target.addClass('disabled');
            if($old_password.length<1){
                alert('请输入原密码');
                return false;
            }
            if($new_password.length<6){
                alert('请输入不小于6位数的密码');
                return false;
            }

            $.ajax({
                url: common_ops.buildWebUrl('user/reset-pwd'),
                type:'post',
                data:{
                    old_password:$old_password,
                    new_password:$new_password
                },
                dataType:'json',
                success:function (res) {
                    $btn_target.removeClass('disabled');
                    if(res.code == 200){
                        alert('修改密码成功');
                        window.location.href = window.location.href;
                    }
                }
            })
        });
    }
}
$(document).ready(function () {
    user_reset_pwd_ops.init();
})