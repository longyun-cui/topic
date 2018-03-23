@foreach($topics as $num => $item)
<div class="item-piece item-option topic-option {{$getType or ''}}"
     data-id="{{encode($item->id)}}"
     data-getType="{{$getType or ''}}"
>
    <!-- BEGIN PORTLET-->
    <div class="boxe panel-default box-default item-entity-container">

        <div class="box-body item-title-row">
            <span>
                <a href="{{url('/topic/'.encode($item->id))}}" >{{$item->title or ''}}</a>
            </span>
        </div>

        <div class="box-body item-info-row">
            @if($item->is_anonymous != 1)
                <span><a href="{{url('/u/'.encode($item->user->id))}}">{{$item->user->name or ''}}</a></span>
            @else
                <span><a href="javascript:void(0)">{{$item->user->anonymous_name or ''}}(匿名)</a></span>
                {{--【匿名@if($item->type == 1)话题@elseif($item->type == 2)辩题@endif】--}}
            @endif

            <span class="pull-right"><a class="show-menu" role="button"></a></span>
            {{--<span class=" text-muted disabled"> • {{ $item->created_at->format('n月j日 H:i') }}</span>--}}
            <span class=" text-muted disabled"> • {{ $item->created_at->format('n月j日 H:i') }}</span>
            <span class=" text-muted disabled"> • 浏览 <span class="text-blue">{{ $item->visit_num }}</span> 次</span>
        </div>

        @if($item->type == 2)
            <div class="box-body item-support-row text-muted">
                <div class="colo-md-12"> <span class="text-primary">【正方】 </span> {{ $item->positive or '' }} </div>
                <div class="colo-md-12"> <span class="text-danger">【反方】 </span> {{ $item->negative or '' }} </div>
            </div>
        @endif

        @if(!empty($item->description))
            <div class="box-body item-description-row text-muted">
                <div class="colo-md-12"> {{ $item->description or '' }} </div>
            </div>
        @endif

        @if(!empty($item->content))
            <div class="box-body item-content-row">
                <article class="colo-md-12"> {!! $item->content or '' !!} </article>
            </div>
        @endif


        {{--tools--}}
        <div class="box-footer item-tools-row item-tools-container">

            {{--点赞--}}
            <a class="margin favor-btn" data-num="{{$item->favor_num}}" role="button">
                @if(Auth::check())
                    @if($item->others->contains('type', 1))
                        <span class="favor-this-cancel"><i class="fa fa-thumbs-up text-red"></i>
                    @else
                        <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                    @endif
                @else
                    <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                @endif

                @if($item->favor_num) {{$item->favor_num}} @endif </span>
            </a>

            {{--收藏--}}
            <a class="margin collect-btn" data-num="{{$item->collect_num}}" role="button">
                @if(Auth::check())
                    @if($item->user_id != Auth::id())
                        @if(count($item->collections))
                            <span class="collect-this-cancel"><i class="fa fa-heart text-red"></i>
                        @else
                            <span class="collect-this"><i class="fa fa-heart-o"></i>
                        @endif
                    @else
                        <span class="collect-mine"><i class="fa fa-heart-o"></i>
                    @endif
                @else
                    <span class="collect-this"><i class="fa fa-heart-o"></i>
                @endif

                @if($item->collect_num) {{$item->collect_num}} @endif </span>
            </a>

            {{--分享--}}
            <a class="margin _none" role="button"><i class="fa fa-share"></i> @if($item->share_num) {{$item->share_num}} @endif</a>

            {{--评论--}}
            <a class="margin @if($getType == 'items') comment-toggle @endif" role="button">
                <i class="fa fa-commenting-o"></i> @if($item->comment_num) {{$item->comment_num}} @endif
            </a>

        </div>


        {{--添加评论--}}
        <div class="box-body comment-container" @if($getType == 'items') style="display:none; @endif" >

            <div class="box-body comment-input-container">
            <form action="" method="post" class="form-horizontal form-bordered topic-comment-form">

                {{csrf_field()}}
                <input type="hidden" name="topic_id" value="{{encode($item->id)}}" readonly>
                <input type="hidden" name="type" value="1" readonly>

                <div class="form-group">
                    <div class="col-md-12">
                        <div><textarea class="form-control" name="content" rows="3" placeholder="请输入你的评论"></textarea></div>
                    </div>
                </div>

                @if($item->type == 2)
                <div class="form-group form-type">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="support" value="0" checked="checked"> 只评论
                                    </label>
                                </div>
                            </button>
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="support" value="1"> 支持正方
                                    </label>
                                </div>
                            </button>
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="support" value="2"> 支持反方
                                    </label>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-group form-type">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_anonymous"> 匿名
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top:16px;">
                    <div class="col-md-12 ">
                        <button type="button" class="btn btn-block btn-flat btn-primary comment-submit">提交</button>
                    </div>
                </div>

            </form>
            </div>

            @if($item->type == 2)
            <div class="box-body comment-choice-container">
                <div class="form-group form-type">
                    <div class="btn-group">
                        <button type="button" class="btn">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="comments-get-{{encode($item->id)}}" checked="checked"
                                           class="comments-get comments-get-default" data-getSort="all"> 全部评论
                                </label>
                            </div>
                        </button>
                        <button type="button" class="btn">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="comments-get-{{encode($item->id)}}" class="comments-get" data-getSort="positive">
                                    <b class="text-primary">只看【正方 <i class="fa fa-thumbs-o-up"></i>】</b>
                                </label>
                            </div>
                        </button>
                        <button type="button" class="btn">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="comments-get-{{encode($item->id)}}" class="comments-get" data-getSort="negative">
                                    <b class="text-danger">只看【反方 <i class="fa fa-thumbs-o-up"></i>】</b>
                                </label>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            @else
                <input type="hidden" class="comments-get comments-get-default" data-type="all">
            @endif

            {{--评论列表--}}
            <div class="box-body comment-entity-container">

                @if($getType == 'items')
                    {{--@include('frontend.component.commentEntity.items')--}}
                    <div class="comment-list-container">
                        {{--@if($data->type == 1)--}}
                        {{--@foreach($data->communications as $comment)--}}
                        {{--@include('frontend.component.comment')--}}
                        {{--@endforeach--}}
                        {{--@endif--}}
                    </div>

                    <div class="col-md-12" style="margin-top:16px;padding:0;">
                        <a href="{{url('/topic/'.encode($item->id))}}" target="_blank">
                            <button type="button" class="btn btn-block btn-flat btn-more" data-getType="all">更多</button>
                        </a>
                    </div>
                @elseif($getType == 'item')
                    {{--@include('frontend.component.commentEntity.item')--}}
                    <div class="comment-list-container">
                        {{--@foreach($communications as $comment)--}}
                        {{--@include('frontend.component.comment')--}}
                        {{--@endforeach--}}
                    </div>

                    <div class="col-md-12" style="margin-top:16px;padding:0;">
                        <button type="button" class="btn btn-block btn-flat btn-more comments-more">更多</button>
                    </div>
                @else
                @endif

                {{--@include('frontend.component.commentEntity.topic')--}}

            </div>

        </div>

    </div>
    <!-- END PORTLET-->
</div>
@endforeach

