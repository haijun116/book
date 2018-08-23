;
var account_set_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        $('.save').click(function () {
            var nickname = $(".wrap_account_set input[name='nickname']").val();
            var mobile = $(".wrap_account_set input[name='mobile']").val();
            var email = $(".wrap_account_set input[name='email']").val();
            var login_name = $(".wrap_account_set input[name='login_name']").val();
            var password = $(".wrap_account_set input[name='login_pwd']").val();
            btn_target = $(this);
            if (btn_target.hasClass('disabled')) {
                common_ops.alert('正在处理中,请不要重复点击');
                return false;
            }
            if(nickname.length < 1){
                common_ops.tip('请输入合法的用户名',$(".wrap_account_set input[name='nickname']"));
                return false;
            }
            if(email.length < 1){
                common_ops.tip('请输入合法的邮箱',$(".wrap_account_set input[name='email']"));
                return false;
            }
            if(mobile.length < 1){
                common_ops.tip('请输入合法的手机名',$(".wrap_account_set input[name='mobile']"));
                return false;
            }
            if(login_name.length < 1){
                common_ops.tip('请输入合法的登录名',$(".wrap_account_set input[name='login_name']"));
                return false;
            }
            if(password.length<6){
                common_ops.tip('请输入合法的登录密码',$(".wrap_account_set input[name='password']"));
                return false;
            }
            btn_target.addClass('disabled');
            $.ajax({
                url: common_ops.buildWebUrl('account/set'),
                type: 'post',
                data: {
                    nickname: nickname,
                    mobile: mobile,
                    email: email,
                    login_name: login_name,
                    password: password,
                    id:$(".wrap_account_set input[name='id']").val()
                },
                dataType: 'json',
                success: function (res) {
                    btn_target.removeClass('disabled');
                    if(res.code = 200){
                        common_ops.alert(res.msg);
                        window.location.href = window.location.href;
                    }else {
                        common_ops.alert(res.msg);
                    }
                }
            })
        })
    }
}
$(document).ready(function () {
    account_set_ops.init();
})