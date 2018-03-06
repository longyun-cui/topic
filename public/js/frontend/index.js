jQuery( function ($) {

    $(".user-logout").on("click",function() {
        location.href = "/logout";
    });

    $(".admin-logout").on("click",function() {
        location.href = "/admin/logout";
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
                id:topic_option.attr('data-id'),
                type:that.attr('data-type')
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
