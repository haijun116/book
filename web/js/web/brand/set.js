;
upload = {
    error:function (msg) {
        common_ops.alert(msg);
    },
    success:function (image_key) {
        //common_ops.alert(msg);
        var html = '<span class="pic-each"><img src="'+image_key+'"> <span class="fa fa-times-circle del del_image"data="'+image_key+'"><i></i></span>';
        if($('.upload_pic_wrap .pic-each').size()>0){
            $('.upload_pic_wrap .pic-each').html(html);
        }else {

            $('.upload_pic_wrap .pic-each').append('<span class="pic-each">'+html+'</span>');
        }
    }
}
var brand_set_ops ={
    init:function () {
        this.eventBind();
    },
    eventBind:function () {
        $('.save').click(function () {
            var that = $(this);
            var name_target = $(".wrap_brand_set input[name=name]");
            var name = name_target.val();
            var mobile_target = $(".wrap_brand_set input[name=mobile]");
            var mobile = mobile_target.val();
            var address_target =  $(".wrap_brand_set input[name=address]");
            var address = address_target.val();
            var description_target =  $(".wrap_brand_set textarea[name=description]");
            var description = description_target.val();
            var image_key = $('.wrap_brand_set .del_image').attr('data');
            if(that.hasClass('disabled')){
                common_ops.alert('正在提交，请勿重复点击');
            }
            if($('.upload_pic_wrap .pic-each').size()< 1){
                common_ops.alert('请上传品牌logo');
                return;
            }
            if(name.length < 1){
                common_ops.tip('品牌名称不合法',name_target);
                return false;
            }
            if(mobile.length < 1){
                common_ops.tip('电话名称不合法',mobile_target);
                return false;
            }
            if(address.length < 1){
                common_ops.tip('地址名称不合法',address_target);
                return false;
            }
            if(description.length < 1){
                common_ops.tip('品牌介绍不合法',description_target);
                return false;
            }
            that.addClass('disabled');
            var  data ={
                name : name,
                mobile:mobile,
                address:address,
                description:description,
                image_key:image_key
            }
            $.ajax({
                url:common_ops.buildWebUrl('brand/set'),
                type:'post',
                data:data,
                dataType:'json',
                success:function (res) {
                    that.removeClass('disabled');
                    callback = null;
                    if(res.code == 200){
                        callback = function () {
                            window.location.href = common_ops.buildWebUrl('brand/info')
                        }
                    }
                    common_ops.alert(res.msg,callback)
                }
            })
        });
        $('.upload_pic_wrap input[name=pic]').change(function () {
            $('.upload_pic_wrap ').submit();
        })
    }
}
$(document).ready(function () {
    brand_set_ops.init();
})