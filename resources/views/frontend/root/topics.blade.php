@extends('frontend.layout.layout')

@section('title','匿名者')
@section('header','')
@section('description','')

@section('content')
<div style="display:none;">
    <input type="hidden" id="" value="{{$encode or ''}}" readonly>
</div>

{{--作者--}}
@foreach($datas as $num => $data)
    <div class="row topic-option" data-id="{{encode($data->id)}}">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->
            <div class="box panel-default box-default
                {{--@if($loop->index % 7 == 0) box-info--}}
                {{--@elseif($loop->index % 7 == 1) box-danger--}}
                {{--@elseif($loop->index % 7 == 2) box-success--}}
                {{--@elseif($loop->index % 7 == 3) box-default--}}
                {{--@elseif($loop->index % 7 == 4) box-warning--}}
                {{--@elseif($loop->index % 7 == 5) box-primary--}}
                {{--@elseif($loop->index % 7 == 6) box-danger--}}
                {{--@endif--}}
            ">

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
                    <a class="margin" title="点赞"><i class="fa fa-thumbs-o-up"></i> @if($data->favor_num) {{$data->favor_num}} @endif</a>
                    <a class="margin"><i class="fa fa-heart-o"></i> @if($data->collect_num) {{$data->collect_num}} @endif</a>
                    <a class="margin"><i class="fa fa-share"></i> @if($data->share_num) {{$data->share_num}} @endif</a>
                    <a class="margin comment-toggle"><i class="fa fa-commenting-o"></i> @if($data->comment_num) {{$data->comment_num}} @endif</a>
                </div>

                {{--添加评论--}}
                <div class="box-body comment-container" style="display:none;" >

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

                        <div class="form-group">
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
                                <button type="button" class="btn get-comments" data-type="all">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="get-comments-{{encode($data->id).'-'.$loop->index}}" checked="checked"> 全部评论
                                        </label>
                                    </div>
                                </button>
                                <button type="button" class="btn get-comments" data-type="positive">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="get-comments-{{encode($data->id).'-'.$loop->index}}" value="1"> 只看正方
                                        </label>
                                    </div>
                                </button>
                                <button type="button" class="btn get-comments" data-type="negative">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="get-comments-{{encode($data->id).'-'.$loop->index}}" value="2"> 只看反方
                                        </label>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{--评论列表--}}
                    <div class="box-body comment-entity-container">

                        <div class="comment-list-container">
                        @foreach($data->communications as $comment)
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
                                    {{--<span class="pull-right">{{ date('Y-n-j H:i', $comment->created_at) }}</span>--}}
                                    <span class="pull-right text-muted disabled">{{ $comment->created_at->format('n月j日 H:i') }}</span>
                                </div>
                                <div class="box-body" style="padding:0;">

                                    <p> {{ $comment->content }} <br> </p>

                                </div>
                            </div>
                        @endforeach
                        </div>

                        <div class="col-md-12" style="padding:16px 0">
                            <a href="{{url('/topic/'.encode($data->id))}}" target="_blank">
                                <button type="button" class="btn btn-block btn-flat btn-default comment-more">更多</button></a>
                        </div>

                    </div>

                </div>

            </div>
            <!-- END PORTLET-->
        </div>
    </div>
@endforeach

{{ $datas->links() }}

@endsection


@section('style')
<style>
    .box-footer a {color:#777;cursor:pointer;}
    .box-footer a:hover {color:orange;cursor:pointer;}
    .comment-container {border-top:2px solid #ddd;}
    .comment-choice-container {border-top:2px solid #ddd;}
    .comment-choice-container .form-group { margin-bottom:0;}
    .comment-entity-container {border-top:2px solid #ddd;}
    .comment-piece {border-bottom:1px solid #eee;}
    .comment-piece:first-child {}
</style>
@endsection

@section('js')
<script>
    $(function() {
    });
</script>
@endsection
