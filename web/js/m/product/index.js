;
var product_index_ops = {
    init:function(){
        this.p = 1;
        this.sort_field = "default";
        this.sort = "";
        this.eventBind();
    },
    eventBind:function(){
        var that = this;

        that.sort_field = $(".sort_list a.aon").attr("data");
        if( $(".sort_list a.aon i").size() > 0  ){
            if( $(".sort_list a.aon i").hasClass("high_icon")  ){
                that.sort = "desc";
            }else{
                that.sort = "asc";
            }
        }

        $(".search_header .search_icon").click( function(){
            that.search();
        });

        $(".sort_box .sort_list li a").click( function(){
            that.sort_field = $(this).attr("data");
            if( $(this).find("i").hasClass("high_icon")  ){
                that.sort = "asc"
            }else{
                that.sort = "desc"
            }
            that.search();
        });
        process = true;
        $( window ).scroll( function() {
            if( ( ( $(window).height() + $(window).scrollTop() ) > $(document).height() - 20 ) && process ){
                process = false;
                that.p += 1;
                var data = {
                    kw:$(".search_header input[name=kw]").val(),
                    sort_field:that.sort_field,
                    sort:that.sort,
                    p:that.p
                };

                $.ajax({
                    url:common_ops.buildMUrl( "/product/search" ),
                    type:'GET',
                    dataType:'json',
                    data:data,
                    success:function( res ){
                        if( res.code != 200 ){
                            return;
                        }
                        var html = "";
                        for( idx in res.data.data ){
                            var info = res.data.data[ idx ];
                            html += '<li> <a href="' + common_ops.buildMUrl( "/product/info",{ id:info['id'] } ) + '"> <i><img src="'+ info['main_image_url'] +'"  style="width: 100%;height: 200px;"/></i> <span>'+ info['name'] +'</span> <b><label>月销量' + info['month_count'] +'</label>¥' + info['price'] +'</b> </a> </li>';
                        }

                        $(".probox ul.prolist").append( html );
                        if( !res.data.has_next ){
                            process = false;
                        }
                    }
                });
            }
        });
    },
    search:function(){
        var params = {
            kw:$(".search_header input[name=kw]").val(),
            sort_field:product_index_ops.sort_field,
            sort:product_index_ops.sort
        };

        window.location.href = common_ops.buildMUrl("/product/index",params);
    }
};
$(document).ready(function () {
    product_index_ops.init();
});