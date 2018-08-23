;
var product_order_ops = {
    init: function () {
        this.evenBind();
    },
    evenBind: function () {
        $('.do_order').click(function () {
            var address_id = 0;
            var data = [];
            $('.order_list li').each(function () {

                var tmp_book_id = $(this).attr('data');
                var tmp_quantity = $(this).attr('data-quantity');
                data.push(tmp_book_id + '#' + tmp_quantity);
            })

            if (data.length < 1) {
                alert("请选择了商品在提交~~");
                return;
            }
            $.ajax({
                url: common_ops.buildMUrl('/product/order'),
                type: 'POST',
                dataType: 'json',
                data: {
                    product_items: data,
                    address_id: address_id
                },
                success: function (res) {
                    window.location.href = res.data.url;
                }
            })
        })
    }
}
$(document).ready(function () {
    product_order_ops.init();
})