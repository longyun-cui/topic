<div class="row item-option topic-option" data-id="{{encode($data->id)}}" data-getType="{{$getType or ''}}">
    <div class="col-md-9">
        <!-- BEGIN PORTLET-->
        <div class="box panel-default box-default">

            <div class="box-header _with-border _panel-heading" style="margin:16px 0 8px;">
                <h3 class="box-title">
                    <a href="{{url('/topic/'.encode($data->id))}}">{{$data->title or ''}}</a>
                </h3>
                @if($data->is_anonymous != 1)
                    <span>来自 <a href="{{url('/u/'.encode($data->user->id))}}">{{$data->user->name or ''}}</a></span>
                @else
                    <span>
                           【匿名@if($data->type == 1)话题@elseif($data->type == 2)辩题@endif】
                    <a href="javascript:void(0)"></a></span>
                @endif
                <span class="pull-right"><a class="show-menu" style="cursor:pointer"></a></span>
                <span class="pull-right text-muted disabled">{{ $data->created_at->format('n月j日 H:i') }}</span>
            </div>

            @if($data->type == 2)
                <div class="box-body with-border panel-heading text-muted">
                    <div class="colo-md-12"> <b class="text-primary">【正方】 </b> {{ $data->positive or '' }} </div>
                </div>
                <div class="box-header with-border panel-heading">
                    <div class="colo-md-12"> <b class="text-danger">【反方】 </b> {{ $data->negative or '' }} </div>
                </div>
            @endif

            @if(!empty($data->description))
                <div class="box-body text-muted">
                    <div class="colo-md-12"> {{ $data->description or '' }} </div>
                </div>
            @endif

            @if(!empty($data->content))
                <div class="box-body">
                    <div class="colo-md-12"> {!! $data->content or '' !!} </div>
                </div>
            @endif


            <div class="box-footer">

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

                <a class="margin"><i class="fa fa-share"></i> @if($data->share_num) {{$data->share_num}} @endif</a>

                <a class="margin @if($getType == 'items') comment-toggle @endif"><i class="fa fa-commenting-o"></i> @if($data->comment_num) {{$data->comment_num}} @endif</a>

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
                                        <input type="radio" name="get-comments-{{encode($data->id)}}" checked="checked"
                                               class="get-comments get-comments-default" data-getSort="all"> 全部评论
                                    </label>
                                </div>
                            </button>
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="get-comments-{{encode($data->id)}}" class="get-comments" data-getSort="positive">
                                        <b class="text-primary">只看【正方 <i class="fa fa-thumbs-o-up"></i>】</b>
                                    </label>
                                </div>
                            </button>
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="get-comments-{{encode($data->id)}}" class="get-comments" data-getSort="negative">
                                        <b class="text-danger">只看【反方 <i class="fa fa-thumbs-o-up"></i>】</b>
                                    </label>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                @else
                    <input type="hidden" class="get-comments get-comments-default" data-type="all">
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

