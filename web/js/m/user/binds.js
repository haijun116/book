;
var user_bind_ops = {

    init: function () {
        this.eventBind();
    },

    eventBind: function () {

        $('.login_form_wrap .dologin').click(function () {

            var btn_target = $(this);

            var mobile = $('.login_form_wrap input[name=mobile]').val();
            var img_captcha = $('.login_form_wrap input[name=img_captcha]').val();
            var captcha_code = $('.login_form_wrap input[name=captcha_code]').val();

            if (mobile.length < 1 || !/^[1-9]\d{10}$/.test(mobile)) {
                alert('请输入正确的手机号！！');
                return;

            }

            if (img_captcha.length < 1) {
                alert('请输入正确的图像校验码');
                return false;
            }

            if (captcha_code.length < 1) {
                alert('请输入手机验证码');
                return false;
            }

            btn_target.addClass('disabled');

            var data = {
                mobile: mobile,
                img_captcha: img_captcha,
                captcha_code: captcha_code,
            }

            $.ajax({
                url: common_ops.buildMUrl('/user/bind'),
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (res) {
                    btn_target.removeClass('disabled');
                    alert(res.msg);

                    if (res.code != 200) {

                        $('#img_captcha').click();
                        return;
                    }

                    window.location.href = res.data.url;
                }
            })

        })

        $('.login_form_wrap .get_captcha').click(function () {

            var mobile = $(".login_form_wrap input[name=mobile]").val();
            var img_captcha = $(".login_form_wrap input[name=img_captcha]").val();


            if (mobile.length < 1 || !/^[1-9]\d{10}$/.test(mobile)) {
                alert('请输入正确的手机号！！');
                return;

            }


            if (img_captcha.length < 1) {
                alert('请输入正确的验证码！！');
                return;
            }

            var data = {
                'mobile': mobile,
                'img_captcha': img_captcha,
                'source': 'wechat'
            }
            $.ajax({
                url: '/default/get_captcha',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (res) {

                    alert(res.msg);

                }
            })
        })

    }
}

$(document).ready(function () {
    user_bind_ops.init();
})