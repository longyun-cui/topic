@extends('home.layout.layout')

@section('title')
    @if(empty($encode_id)) 添加话题 @else 编辑话题 @endif
@endsection

@section('header')
    @if(empty($encode_id)) 添加话题 @else 编辑话题 @endif
@endsection

@section('description')
    @if(empty($encode_id)) 添加话题 @else 编辑话题 @endif
@endsection

@section('breadcrumb')
    <li><a href="{{url('/home')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="{{url('/home/topic/list')}}"><i class="fa "></i>话题列表</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info" id="topic-edit-body">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title"> @if(empty($encode_id)) 添加话题 @else 编辑话题 @endif </h3>
                <div class="box-tools pull-right">
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-topic">
            <div class="box-body">

                {{csrf_field()}}
                <input type="hidden" name="operate" value="{{$operate or 'create'}}" readonly>
                <input type="hidden" name="id" value="{{$encode_id or encode(0)}}" readonly>

                {{--类型--}}
                <div class="form-group form-type">
                    <label class="control-label col-md-2">类型</label>
                    <div class="col-md-8">
                        @if(empty($data->type))
                        <div class="btn-group">
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="type" value="1" checked="checked"> 话题
                                    </label>
                                </div>
                            </button>
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="type" value="2"> 辩题
                                    </label>
                                </div>
                            </button>
                        </div>
                        @else
                            <div class="form-control" style="border:0;">
                                <b>
                                    @if($data->type == 1) 话题
                                    @else 辩题
                                    @endif
                                </b>
                            </div>
                        @endif
                    </div>
                </div>
                {{--匿名--}}
                <div class="form-group form-type">
                    <label class="control-label col-md-2">是否匿名</label>
                    <div class="col-md-8">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_anonymous" @if(!empty($data->is_anonymous)) checked="checked" @endif> 匿名
                            </label>
                        </div>
                    </div>
                </div>
                {{--标题--}}
                <div class="form-group">
                    <label class="control-label col-md-2">标题</label>
                    <div class="col-md-8 ">
                        <div><input type="text" class="form-control" name="title" placeholder="请输入标题" value="{{$data->title or ''}}"></div>
                    </div>
                </div>
                <div class="form-group debate-show" @if(empty($data->type) || $data->type != 2) style="display:none;" @endif>
                    <label class="control-label col-md-2">正方</label>
                    <div class="col-md-8 ">
                        <div><input type="text" class="form-control" name="positive" placeholder="正方结论" value="{{$data->positive or ''}}"></div>
                    </div>
                </div>
                <div class="form-group debate-show" @if(empty($data->type) || $data->type != 2) style="display:none;" @endif>
                    <label class="control-label col-md-2">反方</label>
                    <div class="col-md-8 ">
                        <div><input type="text" class="form-control" name="negative" placeholder="反方结论" value="{{$data->negative or ''}}"></div>
                    </div>
                </div>
                {{--说明--}}
                <div class="form-group">
                    <label class="control-label col-md-2">描述</label>
                    <div class="col-md-8 ">
                        <div><textarea class="form-control" name="description" rows="3" placeholder="描述">{{$data->description or ''}}</textarea></div>
                    </div>
                </div>
                {{--内容--}}
                <div class="form-group">
                    <label class="control-label col-md-2">介绍详情</label>
                    <div class="col-md-8 ">
                        <div>
                            @include('UEditor::head')
                            <!-- 加载编辑器的容器 -->
                            <script id="container" name="content" type="text/plain">{!! $data->content or '' !!}</script>
                            <!-- 实例化编辑器 -->
                            <script type="text/javascript">
                                var ue = UE.getEditor('container');
                                ue.ready(function() {
                                    ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                                });
                            </script>
                        </div>
                    </div>
                </div>
                {{--cover 封面图片--}}
                @if(!empty($data->cover_pic))
                    <div class="form-group">
                        <label class="control-label col-md-2">封面图片</label>
                        <div class="col-md-8 ">
                            <div class="edit-img"><img src="{{url('http://cdn.'.$_SERVER['HTTP_HOST'].'/'.$data->cover_pic.'?'.rand(0,999))}}" alt=""></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">更换封面图片</label>
                        <div class="col-md-8 ">
                            <div><input type="file" name="cover" placeholder="请上传封面图片"></div>
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <label class="control-label col-md-2">上传封面图片</label>
                        <div class="col-md-8 ">
                            <div><input type="file" name="cover" placeholder="请上传封面图片"></div>
                        </div>
                    </div>
                @endif

            </div>
            </form>

            <div class="box-footer">
                <div class="row" style="margin:16px 0;">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" class="btn btn-primary" id="edit-topic-submit"><i class="fa fa-check"></i> 提交</button>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>
@endsection

@section('style')
<style>
    #form-edit-topic .radio {padding-top:0px;}
</style>
@endsection


@section('js')
<script>
    $(function() {
        // 修改幻灯片信息
        $("#edit-topic-submit").on('click', function() {
            var options = {
                url: "/home/topic/edit",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.msg);
                        location.href = "/home/topic/list";
                    }
                }
            };
            $("#form-edit-topic").ajaxSubmit(options);
        });

        // 表格【取消分享】
        $("#topic-edit-body").on('click', "input[name=type]", function() {
            var type_value = $(this).val();
            if(type_value == 1)
            {
                $('.debate-show').hide();
            }
            else if(type_value == 2)
            {
                $('.debate-show').show();
            }
        });

    });
</script>
@endsection
