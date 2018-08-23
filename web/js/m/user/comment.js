var user_comment_ops = {

    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        $('.star').raty({
            readOnly: true,
            score:function(  ){
                return $(this).attr("data-score")/2;
            },
            width:200
        });
    }
}

$(document).ready(function () {
    user_comment_ops.init();
});