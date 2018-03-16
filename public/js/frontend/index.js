jQuery( function ($) {

    $(".user-logout").on("click",function() {
        location.href = "/logout";
    });

    $(".admin-logout").on("click",function() {
        location.href = "/admin/logout";
    });




    // 收藏自己
    $(".item-option").off("click",".collect-mine").on('click', ".collect-mine", function() {
        layer.msg('不能收藏自己的', function(){});
    });
    // 收藏
    $(".item-option").off("click",".collect-this").on('click', ".collect-this", function() {
        var that = $(this);
        var item_option = $(this).parents('.item-option');

        layer.msg('确认"收藏"？', {
            time: 0
            ,btn: ['确定', '取消']
            ,yes: function(index){
                $.post(
                    "/topic/collect/save",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        topic_id: item_option.attr('data-id'),
                        type: 1
                    },
                    function(data){
                        if(!data.success) layer.msg(data.msg, function(){});
                        else
                        {
                            layer.msg("收藏成功");

                            var btn = that.parents('.collect-btn');
                            var num = parseInt(btn.attr('data-num'));
                            num = num + 1;
                            btn.attr('data-num',num);
                            var html = '<span class="collect-this-cancel"><i class="fa fa-heart text-red"></i> '+num+'</span>';
                            btn.html(html);
                            // item_option.html(data.data.html);
                        }
                    },
                    'json'
                );
            }
        });

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
                        type: 1
                    },
                    function(data){
                        if(!data.success) layer.msg(data.msg, function(){});
                        else
                        {
                            layer.closeAll();
                            // var index = parent.layer.getFrameIndex(window.name);
                            // parent.layer.close(index);

                            var btn = that.parents('.collect-btn');
                            var num = parseInt(btn.attr('data-num'));
                            num = num - 1;
                            btn.attr('data-num',num);
                            if(num == 0) num = '';
                            var html = '<span class="collect-this"><i class="fa fa-heart-o"> '+num+'</span>';
                            btn.html(html);

                            // item_option.html(data.data.html);
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
                    layer.msg("点赞成功");

                    var btn = that.parents('.favor-btn');
                    var num = parseInt(btn.attr('data-num'));
                    num = num + 1;
                    btn.attr('data-num',num);
                    var html = '<span class="favor-this-cancel"><i class="fa fa-thumbs-up text-red"></i> '+num+'</span>';
                    btn.html(html);
                    // item_option.html(data.data.html);
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
                            layer.closeAll();
                            // var index = parent.layer.getFrameIndex(window.name);
                            // parent.layer.close(index);

                            var btn = that.parents('.favor-btn');
                            var num = parseInt(btn.attr('data-num'));
                            num = num - 1;
                            btn.attr('data-num',num);
                            if(num == 0) num = '';
                            var html = '<span class="favor-this"><i class="fa fa-thumbs-o-up"></i> '+num+'</span>';
                            btn.html(html);

                            // item_option.html(data.data.html);
                        }
                    },
                    'json'
                );
            }
        });
    });





    // 显示评论
    $(".item-option").off("click",".comment-toggle").on('click', ".comment-toggle", function() {
        var item_option = $(this).parents('.item-option');
        item_option.find(".comment-container").toggle();
        if(!item_option.find(".comment-container").is(":hidden"))
        {
            item_option.find(".get-comments-default").click();
        }
    });
    // 发布评论
    $(".item-option").off("click",".comment-submit").on('click', ".comment-submit", function() {
        var item_option = $(this).parents('.item-option');
        var form = $(this).parents('.topic-comment-form');
        var options = {
            url: "/item/comment/save",
            type: "post",
            dataType: "json",
            // target: "#div2",
            success: function (data) {
                if(!data.success) layer.msg(data.msg);
                else
                {
                    form.find('textarea').val('');
                    item_option.find('.comment-list-container').prepend(data.data.html);
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
            "/item/comment/get",
            {
                _token: $('meta[name="_token"]').attr('content'),
                type: 1,
                topic_id: item_option.attr('data-id'),
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
                "/item/comment/get",
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


    // 显示对评论的回复
    $(".item-option").off("click",".comment-reply-toggle").on('click', ".comment-reply-toggle", function() {
        var comment_option = $(this).parents('.comment-option');
        comment_option.find(".comment-reply-input-container").toggle();
    });
    // 发布对评论的回复
    $(".item-option").off("click",".comment-reply-submit").on('click', ".comment-reply-submit", function() {
        var that = $(this);
        var item_option = $(this).parents('.item-option');
        var comment_option = $(this).parents('.comment-option');

        var topic_id = item_option.attr('data-id');
        var comment_id = comment_option.attr('data-id');

        var content_input = comment_option.find('.comment-reply-content');
        var content = content_input.val();
        if(content == "")
        {
            layer.msg('请输入回复内容',function(){});
            return false;
        }

        var anonymous = comment_option.find('.comment-reply-anonymous').is(":checked");
        var is_anonymous = 0;
        if(anonymous) is_anonymous = 1;
        else is_anonymous = 0;

        $.post(
            "/item/reply/save",
            {
                _token: $('meta[name="_token"]').attr('content'),
                type: 1,
                topic_id: topic_id,
                comment_id: comment_id,
                is_anonymous: is_anonymous,
                content: content
            },
            function(data){
                if(!data.success) layer.msg(data.msg);
                else
                {
                    content_input.val('');
                    comment_option.find('.reply-list-container').prepend(data.data.html);
                }
            },
            'json'
        );
    });


    // 显示回复的回复
    $(".item-option").off("click",".reply-toggle").on('click', ".reply-toggle", function() {
        var reply_option = $(this).parents('.reply-option');
        reply_option.find(".reply-input-container").toggle();
    });
    // 发布对回复的回复
    $(".item-option").off("click",".reply-submit").on('click', ".reply-submit", function() {
        var that = $(this);
        var item_option = $(this).parents('.item-option');
        var comment_option = $(this).parents('.comment-option');
        var reply_option = $(this).parents('.reply-option');

        var topic_id = item_option.attr('data-id');
        var comment_id = reply_option.attr('data-id');

        var content_input = reply_option.find('.reply-content');
        var content = content_input.val();
        if(content == "")
        {
            layer.msg('请输入回复内容',function(){});
            return false;
        }

        var anonymous = reply_option.find('.reply-anonymous').is(":checked");
        var is_anonymous = 0;
        if(anonymous) is_anonymous = 1;
        else is_anonymous = 0;

        $.post(
            "/item/reply/save",
            {
                _token: $('meta[name="_token"]').attr('content'),
                type: 1,
                topic_id: topic_id,
                comment_id: comment_id,
                is_anonymous: is_anonymous,
                content: content
            },
            function(data){
                if(!data.success) layer.msg(data.msg);
                else
                {
                    content_input.val('');
                    comment_option.find('.reply-list-container').prepend(data.data.html);
                }
            },
            'json'
        );
    });


    // 更多回复
    $(".item-option").off("click",".replies-more").on('click', ".replies-more", function() {

        var that = $(this);
        var more = that.attr('data-more');
        var getSort = that.attr('data-getSort');
        var min_id = that.attr('data-minId');

        var item_option = $(this).parents('.item-option');
        var comment_option = $(this).parents('.comment-option');

        if(more == 'more')
        {
            $.post(
                "/item/reply/get",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    type: 1,
                    topic_id: item_option.attr('data-id'),
                    comment_id: comment_option.attr('data-id'),
                    min_id: min_id
                },
                function(data){
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        comment_option.find('.reply-list-container').append(data.data.html);

                        comment_option.find('.replies-more').attr("data-getSort",getSort);
                        comment_option.find('.replies-more').attr("data-maxId",data.data.max_id);
                        comment_option.find('.replies-more').attr("data-minId",data.data.min_id);
                        comment_option.find('.replies-more').attr("data-more",data.data.more);
                        if(data.data.more == 'more')
                        {
                            comment_option.find('.replies-more').html("更多");
                        }
                        else if(data.data.more == 'none')
                        {
                            comment_option.find('.replies-more').html("没有更多回复了");
                        }
                    }
                },
                'json'
            );
        }
        else if(more == 'none')
        {
            layer.msg('没有更多回复了', function(){});
        }
    });


    // 发布对回复的回复
    $(".item-option").off("click",".comment-favor-this").on('click', ".comment-favor-this", function() {
        var that = $(this);
        var that_parent = that.attr('data-parent');
        var reply_option = $(this).parents(that_parent);
        var item_option = $(this).parents('.item-option');

        var topic_id = item_option.attr('data-id');
        var comment_id = reply_option.attr('data-id');

        $.post(
            "/item/comment/favor/save",
            {
                _token: $('meta[name="_token"]').attr('content'),
                type: 5,
                topic_id: topic_id,
                comment_id: comment_id
            },
            function(data){
                if(!data.success) layer.msg(data.msg);
                else
                {
                    layer.msg("点赞成功");

                    that.addClass('comment-favor-this-cancel');
                    that.removeClass('comment-favor-this');
                    var btn = that.parents('.comment-favor-btn');
                    var num = parseInt(btn.attr('data-num'));
                    num = num + 1;
                    btn.attr('data-num',num);
                    var html = '<i class="fa fa-thumbs-up text-red"></i> '+num;
                    that.html(html);
                }
            },
            'json'
        );
    });
    // 取消点赞
    $(".item-option").off("click",".comment-favor-this-cancel").on('click', ".comment-favor-this-cancel", function() {
        var that = $(this);
        var that_parent = that.attr('data-parent');
        var reply_option = $(this).parents(that_parent);
        var item_option = $(this).parents('.item-option');

        var topic_id = item_option.attr('data-id');
        var comment_id = reply_option.attr('data-id');

        layer.msg('取消"点赞"？', {
            time: 0
            ,btn: ['确定', '取消']
            ,yes: function(index){
                $.post(
                    "/item/comment/favor/cancel",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        type: 5,
                        topic_id: topic_id,
                        comment_id: comment_id
                    },
                    function(data){
                        if(!data.success) layer.msg(data.msg);
                        else
                        {
                            layer.closeAll();
                            // var index = parent.layer.getFrameIndex(window.name);
                            // parent.layer.close(index);

                            that.addClass('comment-favor-this');
                            that.removeClass('comment-favor-this-cancel');
                            var btn = that.parents('.comment-favor-btn');
                            var num = parseInt(btn.attr('data-num'));
                            num = num - 1;
                            btn.attr('data-num',num);
                            if(num == 0) num = '';
                            var html = '<i class="fa fa-thumbs-o-up"></i> '+num;
                            that.html(html);
                        }
                    },
                    'json'
                );
            }
        });
    });





});
