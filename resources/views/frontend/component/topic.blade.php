<div class="row item-piece item-option topic-option" style="margin-bottom:16px;"
     data-id="{{encode($data->id)}}"
     data-getType="{{$getType or ''}}"
>
    <div class="col-md-8 col-md-offset-2" style="border-bottom:1px solid #eee;background:#fff;">
        <!-- BEGIN PORTLET-->
        <div class="boxe panel-default box-default">

            <div class="box-body _with-border _panel-heading">
                <span>
                    <a href="{{url('/topic/'.encode($data->id))}}" style="font-size:16px;">{{$data->title or ''}}</a>
                </span>
            </div>

            <div class="box-body info-container">
                @if($data->is_anonymous != 1)
                    <span><a href="{{url('/u/'.encode($data->user->id))}}">{{$data->user->name or ''}}</a></span>
                @else
                    <span><a href="javascript:void(0)">{{$data->user->anonymous_name or ''}}(匿名)</a></span>
                    {{--【匿名@if($data->type == 1)话题@elseif($data->type == 2)辩题@endif】--}}
                @endif

                <span class="pull-right"><a class="show-menu" role="button"></a></span>
                <span class=" text-muted disabled"> • {{ $data->created_at->format('n月j日 H:i') }}</span>
            </div>

            @if($data->type == 2)
                <div class="box-body with-border panel- text-muted" style="padding-bottom:0;">
                    <div class="colo-md-12"> <span class="text-primary">【正方】 </span> {{ $data->positive or '' }} </div>
                </div>
                <div class="box-body with-border panel- text-muted" style="padding-top:0;padding-bottom:0;">
                    <div class="colo-md-12"> <b class="text-danger">【反方】 </b> {{ $data->negative or '' }} </div>
                </div>
            @endif

            @if(!empty($data->description))
                <div class="box-body text-muted">
                    <div class="colo-md-12"> {{ $data->description or '' }} </div>
                </div>
            @endif

            @if(!empty($data->content))
                <div class="box-body" style="padding-bottom:0;">
                    <article class="colo-md-12"> {!! $data->content or '' !!} </article>
                </div>
            @endif


            {{--tools--}}
            <div class="box-body">

                {{--点赞--}}
                <a class="margin favor-btn" data-num="{{$data->favor_num}}" role="button">
                    @if(Auth::check())
                        @if($data->others->contains('type', 1))
                            <span class="favor-this-cancel"><i class="fa fa-thumbs-up text-red"></i>
                        @else
                            <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                        @endif
                    @else
                        <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                    @endif

                    @if($data->favor_num) {{$data->favor_num}} @endif </span>
                </a>

                {{--收藏--}}
                <a class="margin collect-btn" data-num="{{$data->collect_num}}" role="button">
                    @if(Auth::check())
                        @if($data->user_id != Auth::id())
                            @if(count($data->collections))
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

                    @if($data->collect_num) {{$data->collect_num}} @endif </span>
                </a>

                {{--分享--}}
                <a class="margin _none" role="button"><i class="fa fa-share"></i> @if($data->share_num) {{$data->share_num}} @endif</a>

                {{--评论--}}
                <a class="margin @if($getType == 'items') comment-toggle @endif" role="button">
                    <i class="fa fa-commenting-o"></i> @if($data->comment_num) {{$data->comment_num}} @endif
                </a>

            </div>


            {{--添加评论--}}
            <div class="box-body comment-container" @if($getType == 'items') style="display:none; @endif" >

                <div class="box-body comment-input-container">
                <form action="" method="post" class="form-horizontal form-bordered topic-comment-form">

                    {{csrf_field()}}
                    <input type="hidden" name="topic_id" value="{{encode($data->id)}}" readonly>
                    <input type="hidden" name="type" value="1" readonly>

                    <div class="form-group">
                        <div class="col-md-12">
                            <div><textarea class="form-control" name="content" rows="3" placeholder="请输入你的评论"></textarea></div>
                        </div>
                    </div>

                    @if($data->type == 2)
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

                @if($data->type == 2)
                <div class="box-body comment-choice-container">
                    <div class="form-group form-type">
                        <div class="btn-group">
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="comments-get-{{encode($data->id)}}" checked="checked"
                                               class="comments-get comments-get-default" data-getSort="all"> 全部评论
                                    </label>
                                </div>
                            </button>
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="comments-get-{{encode($data->id)}}" class="comments-get" data-getSort="positive">
                                        <b class="text-primary">只看【正方 <i class="fa fa-thumbs-o-up"></i>】</b>
                                    </label>
                                </div>
                            </button>
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="comments-get-{{encode($data->id)}}" class="comments-get" data-getSort="negative">
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
                        @include('frontend.component.commentEntity.items')
                    @elseif($getType == 'item')
                        @include('frontend.component.commentEntity.item')
                    @else
                    @endif

                    {{--@include('frontend.component.commentEntity.topic')--}}

                </div>

            </div>

        </div>
        <!-- END PORTLET-->
    </div>
</div>

