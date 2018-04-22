;
var account_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        var that = this;
        $('.search').click(function () {
            $('.wrap_search').submit();
        });

        $('.remove').click(function () {
            that.ops('remove', $(this).attr('data'));
        });
        $('.recove').click(function () {
            that.ops('recove', $(this).attr('data'));
        });
    },
    ops: function (act, uid) {
        $.ajax({
            url: common_ops.buildWebUrl('account/ops'),
            type: 'post',
            data: {
                act: act,
                uid: uid
            },
            dataType: 'json',
            success: function (res) {
                alert(res.msg);
                if(res.code = 200){
                     window.location.href = window.location.href;
                }
            }
        })
    }
}
$(document).ready(function () {
    account_index_ops.init();
})