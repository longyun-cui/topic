jQuery( function ($) {

    $(".user-logout").on("click",function() {
        location.href = "/logout";
    });

    $(".admin-logout").on("click",function() {
        location.href = "/admin/logout";
    });




    // 收藏
    $(".item-option").off("click",".collect-mine").on('click', ".collect-mine", function() {
        layer.msg('不能收藏自己的', function(){});
    });
    // 收藏
    $(".item-option").off("click",".collect-this").on('click', ".collect-this", function() {
        var that = $(this);
        var item_option = $(this).parents('.item-option');

        $.post(
            "/topic/collect/save",
            {
                _token: $('meta[name="_token"]').attr('content'),
                topic_id: item_option.attr('data-id'),
                type: 2
            },
            function(data){
                if(!data.success) layer.msg(data.msg, function(){});
                else
                {
                    layer.msg("收藏成功");
                    item_option.html(data.data.html);
                }
            },
            'json'
        );
    });
    // 取消收藏
    $(".item-option").off("click",".collect-this-cancel").on('click', ".collect-this-cancel", function() {
        var that = $(this);
        var item_option = $(this).parents('.item-option');

        layer.msg('取消"收藏"？', {
            time: 0
            ,btn: ['确定', '取消']
            ,yes: function(index){
                $.post(
                    "/topic/collect/cancel",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        topic_id: item_option.attr('data-id'),
                        type: 2
                    },
                    function(data){
                        if(!data.success) layer.msg(data.msg, function(){});
                        else
                        {
                            item_option.html(data.data.html);
                            layer.closeAll();
                            // var index = parent.layer.getFrameIndex(window.name);
                            // parent.layer.close(index);
                        }
                    },
                    'json'
                );
            }
        });
    });


    // 点赞
    $(".item-option").off("click",".favor-this").on('click', ".favor-this", function() {
        var that = $(this);
        var item_option = $(this).parents('.item-option');

        $.post(
            "/topic/favor/save",
            {
                _token: $('meta[name="_token"]').attr('content'),
                topic_id: item_option.attr('data-id'),
                type: 1
            },
            function(data){
                if(!data.success) layer.msg(data.msg, function(){});
                else
                {
                    layer.msg("赞");
                    item_option.html(data.data.html);
                }
            },
            'json'
        );
    });
    // 取消赞
    $(".item-option").off("click",".favor-this-cancel").on('click', ".favor-this-cancel", function() {
        var that = $(this);
        var item_option = $(this).parents('.item-option');

        layer.msg('取消"赞"？', {
            time: 0
            ,btn: ['确定', '取消']
            ,yes: function(index){
                $.post(
                    "/topic/favor/cancel",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        topic_id: item_option.attr('data-id'),
                        type: 1
                    },
                    function(data){
                        if(!data.success) layer.msg(data.msg, function(){});
                        else
                        {
                            item_option.html(data.data.html);
                            layer.closeAll();
                            // var index = parent.layer.getFrameIndex(window.name);
                            // parent.layer.close(index);
                        }
                    },
                    'json'
                );
            }
        });
    });




    // 显示评论
    $(".comment-toggle").off("click").on('click', function() {
        var item_option = $(this).parents('.item-option');
        item_option.find(".comment-container").toggle();
        if(!item_option.find(".comment-container").is(":hidden"))
        {
            item_option.find(".get-comments-default").click();
        }
    });

    // 发布评论
    $(".item-option").off("click",".comment-submit").on('click', ".comment-submit", function() {
        var topic_option = $(this).parents('.topic-option');
        var form = $(this).parents('.topic-comment-form');
        var options = {
            url: "/topic/comment/save",
            type: "post",
            dataType: "json",
            // target: "#div2",
            success: function (data) {
                if(!data.success) layer.msg(data.msg);
                else
                {
                    form.find('textarea').val('');
                    topic_option.find('.comment-list-container').prepend(data.data.html);
                }
            }
        };
        form.ajaxSubmit(options);
    });

    // 查看评论
    $(".item-option").off("click",".get-comments").on('click', ".get-comments", function() {
        var that = $(this);
        var item_option = $(this).parents('.item-option');
        var getSort = that.attr('data-getSort');

        $.post(
            "/topic/comment/get",
            {
                _token: $('meta[name="_token"]').attr('content'),
                id: item_option.attr('data-id'),
                getSort: getSort
            },
            function(data){
                if(!data.success) layer.msg(data.msg);
                else
                {
                    item_option.find('.comment-list-container').html(data.data.html);

                    item_option.find('.comments-more').attr("data-getSort",getSort);
                    item_option.find('.comments-more').attr("data-maxId",data.data.max_id);
                    item_option.find('.comments-more').attr("data-minId",data.data.min_id);
                    item_option.find('.comments-more').attr("data-more",data.data.more);
                    if(data.data.more == 'more')
                    {
                        item_option.find('.comments-more').html("更多");
                    }
                    else if(data.data.more == 'none')
                    {
                        item_option.find('.comments-more').html("没有更多了");
                    }
                }
            },
            'json'
        );
    });
    // 更多评论
    $(".item-option").off("click",".comments-more").on('click', ".comments-more", function() {

        var that = $(this);
        var more = that.attr('data-more');
        var getSort = that.attr('data-getSort');
        var min_id = that.attr('data-minId');

        var item_option = $(this).parents('.item-option');

        if(more == 'more')
        {
            $.post(
                "/topic/comment/get",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    id: item_option.attr('data-id'),
                    getSort: getSort,
                    min_id: min_id
                },
                function(data){
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        item_option.find('.comment-list-container').append(data.data.html);

                        item_option.find('.comments-more').attr("data-getSort",getSort);
                        item_option.find('.comments-more').attr("data-maxId",data.data.max_id);
                        item_option.find('.comments-more').attr("data-minId",data.data.min_id);
                        item_option.find('.comments-more').attr("data-more",data.data.more);
                        if(data.data.more == 'more')
                        {
                            item_option.find('.comments-more').html("更多");
                        }
                        else if(data.data.more == 'none')
                        {
                            item_option.find('.comments-more').html("没有更多了");
                        }
                    }
                },
                'json'
            );
        }
        else if(more == 'none')
        {
            layer.msg('没有更多了', function(){});
        }
    });

});
