<div class="colo-md-12 box-body comment-piece comment-option" data-id="{{encode($comment->id)}}">

    <div class="box-body comment-title-container">
        @if($comment->is_anonymous == 1)
            <a href="javascript:void(0)">
                {{$comment->user->anonymous_name}} (匿名)
                @if(Auth::check()) @if($comment->user->id == Auth::user()->id) @endif @endif
            </a>
        @else
            <a href="{{url('/u/'.encode($comment->user->id))}}">{{$comment->user->name}}</a>
        @endif
        @if($comment->support == 1) <b class="text-primary">【正方 <i class="fa fa-thumbs-o-up"></i>】</b>
        @elseif($comment->support == 2) <b class="text-danger">【反方 <i class="fa fa-thumbs-o-up"></i>】</b>
        @endif

        <span class="pull-right text-muted disabled">{{ $comment->created_at->format('n月j日 H:i') }}</span>

        <span class="pull-right text-muted disabled comment-reply-toggle" role="button" data-num="{{$comment->comment_num}}">
            回复 @if($comment->comment_num){{$comment->comment_num}}@endif
        </span>

        <span class="comment-favor-btn" data-num="{{$comment->favor_num or 0}}">
            @if(Auth::check())
                @if(count($comment->favors))
                    <span class="pull-right text-muted disabled comment-favor-this-cancel" data-parent=".comment-option" role="button">
                        <i class="fa fa-thumbs-up text-red"></i>
                @else
                    <span class="pull-right text-muted disabled comment-favor-this" data-parent=".comment-option" role="button">
                        <i class="fa fa-thumbs-o-up"></i>
                @endif
            @else
                <span class="pull-right text-muted disabled comment-favor-this" data-parent=".comment-option" role="button">
                    <i class="fa fa-thumbs-o-up"></i>
            @endif

            @if($comment->favor_num){{$comment->favor_num}}@endif </span>
        </span>

    </div>

    <div class="box-body comment-content-container">
        {{ $comment->content }} <br>
    </div>

    <div class="box-body comment-reply-input-container">

        <div class="input-group margin">
            <input type="text" class="form-control comment-reply-content">

            <span class="input-group-addon" style="border-left:0;">
                <div class="checkbox" style="margin:0;line-height:20px;">
                    <label>
                        <input type="checkbox" name="is_anonymous" class="comment-reply-anonymous" style="margin-top:4px;"> 匿名
                    </label>
                </div>
            </span>

            <span class="input-group-btn">
                  <button type="button" class="btn btn-primary btn-flat comment-reply-submit">回复</button>
            </span>
        </div>

    </div>

    <div class="box-body reply-container">

        <div class="reply-list-container"></div>

        @if($comment->dialogs_count)
            <div class="col-md-12 more-box" style="margin-top:4px;">
                <button type="button" class="btn btn-block btn-flat btn-more replies-more"
                        data-more="{{$comment->dialog_more}}"
                        data-maxId="{{$comment->dialog_max_id}}"
                        data-minId="{{$comment->dialog_min_id}}"
                >{!! $comment->dialog_more_text !!}</button>
            </div>
        @endif

        {{--<div class="reply-list-container">--}}
        {{--@if(count($comment->dialogs))--}}
        {{--@foreach($comment->dialogs as $reply)--}}
        {{--@include('frontend.component.reply')--}}
        {{--@endforeach--}}
        {{--@endif--}}
        {{--</div>--}}

        {{--@if(count($comment->dialogs))--}}
        {{--<div class="col-md-12 more-box" style="margin-top:4px;">--}}
        {{--<button type="button" class="btn btn-block btn-flat btn-default replies-more">更多</button>--}}
        {{--</div>--}}
        {{--@endif--}}

    </div>

</div>

