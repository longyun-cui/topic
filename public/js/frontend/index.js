jQuery( function ($) {

    $(".user-logout").on("click",function() {
        location.href = "/logout";
    });

    $(".admin-logout").on("click",function() {
        location.href = "/admin/logout";
    });


    // 点赞
    $(".topic-option").off("click",".favor-this").on('click', ".favor-this", function() {
        var that = $(this);
        var topic_option = $(this).parents('.topic-option');

        $.post(
            "/topic/favor/save",
            {
                _token: $('meta[name="_token"]').attr('content'),
                topic_id: topic_option.attr('data-id'),
                type: 1
            },
            function(data){
                if(!data.success) layer.msg(data.msg);
                else
                {
                    layer.msg("赞！");
                    topic_option.html(data.data.html);
                }
            },
            'json'
        );
    });
    // 取消赞
    $(".topic-option").off("click",".favor-this-cancel").on('click', ".favor-this-cancel", function() {
        var that = $(this);
        var topic_option = $(this).parents('.topic-option');

        layer.msg('取消"赞"？', {
            time: 0
            ,btn: ['确定', '取消']
            ,yes: function(index){
                $.post(
                    "/topic/favor/cancel",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        topic_id: topic_option.attr('data-id'),
                        type: 1
                    },
                    function(data){
                        if(!data.success) layer.msg(data.msg);
                        else
                        {
                            topic_option.html(data.data.html);
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


    // 收藏
    $(".topic-option").off("click",".collect-this").on('click', ".collect-this", function() {
        var that = $(this);
        var topic_option = $(this).parents('.topic-option');

        $.post(
            "/topic/collect/save",
            {
                _token: $('meta[name="_token"]').attr('content'),
                topic_id: topic_option.attr('data-id'),
                type: 2
            },
            function(data){
                if(!data.success) layer.msg(data.msg);
                else
                {
                    layer.msg("收藏成功！");
                    topic_option.html(data.data.html);
                }
            },
            'json'
        );
    });
    // 取消收藏
    $(".topic-option").off("click",".collect-this-cancel").on('click', ".collect-this-cancel", function() {
        var that = $(this);
        var topic_option = $(this).parents('.topic-option');

        layer.msg('取消"收藏"？', {
            time: 0
            ,btn: ['确定', '取消']
            ,yes: function(index){
                $.post(
                    "/topic/collect/cancel",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        topic_id: topic_option.attr('data-id'),
                        type: 2
                    },
                    function(data){
                        if(!data.success) layer.msg(data.msg);
                        else
                        {
                            topic_option.html(data.data.html);
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
        var topic_option = $(this).parents('.topic-option');
        topic_option.find(".comment-container").toggle();
    });

    // 发布评论
    $(".topic-option").off("click",".comment-submit").on('click', ".comment-submit", function() {
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
    $(".topic-option").off("click",".get-comments").on('click', ".get-comments", function() {
        var that = $(this);
        var topic_option = $(this).parents('.topic-option');

        $.post(
            "/topic/comment/get",
            {
                _token: $('meta[name="_token"]').attr('content'),
                id: topic_option.attr('data-id'),
                type: that.attr('data-type')
            },
            function(data){
                if(!data.success) layer.msg(data.msg);
                else
                {
                    topic_option.find('.comment-list-container').html(data.data.html);
                    // location.reload();
                }
            },
            'json'
        );
    });

});
