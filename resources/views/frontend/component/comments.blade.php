@foreach($comments as $comment)
    <div class="colo-md-12 box-body comment-option comment-piece" style="padding:4px 10px;">
        <div class="box-body" style="padding:4px 0">
            @if($comment->is_anonymous == 1)
            <a href="javascript:void(0)">
                @if(Auth::check())
                    @if($comment->user->id == Auth::user()->id) 【我】 @else 匿名评论 @endif
                @else 匿名评论
                @endif
            </a>
            @else
                <a href="{{url('/u/'.encode($comment->user->id))}}">{{$comment->user->name}}</a>
            @endif
            @if($comment->support == 1) <b class="text-primary">【正方 <i class="fa fa-thumbs-o-up"></i>】</b>
            @elseif($comment->support == 2) <b class="text-danger">【反方 <i class="fa fa-thumbs-o-up"></i>】</b>
            @endif

            <span class="pull-right text-muted disabled">{{ $comment->created_at->format('n月j日 H:i') }}</span>
        </div>
        <div class="box-body" style="padding:0;">

            <p> {{ $comment->content }} <br> </p>

        </div>
    </div>
@endforeach

