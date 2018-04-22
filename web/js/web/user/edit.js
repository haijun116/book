;
var user_edit_opt = {
    init: function () {
        this.eventBind();
    },

    eventBind: function () {
        $('.save').click(function () {
            $btn_target = $(this);
            if ($btn_target.hasClass('disabled')) {
                alert('正在处理，请不要重复操作');
                return false;
            }
            var nickname = $(".user_edit_wrap input[name=nickname]").val();
            var email = $('.user_edit_wrap input[name = email]').val();
            if (nickname.length < 1) {
                alert('请输入合法的用户名');
                return false;
            }

            if (email.length < 1) {
                alert('请输入合法的邮箱');
                return false;
            }
            $btn_target.addClass('disabled');
            $.ajax({
                url: common_ops.buildWebUrl('/user/edit'),
                type: 'POST',
                data: {
                    nickname: nickname,
                    email: email
                },
                dataType: 'JSON',
                success: function (res) {
                    $btn_target.removeClass('disabled');
                    if (res.code == 200) {
                        alert('编辑成功');
                        window.location.href = window.location.href;
                    }
                }
            })
        })
    }
}

$(document).ready(function () {
    user_edit_opt.init();
})