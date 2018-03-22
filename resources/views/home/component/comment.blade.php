@foreach($datas as $data)
<div class="row notification-option notification-piece"
     data-id="{{ encode($data->id) or '' }}"
     data-course="{{ encode($data->id) or '' }}"
     data-content="{{ encode(0) }}"
>

    <div class="col-md-9">
        <!-- BEGIN PORTLET-->
        <div class="box panel-default box-default">

            {{--header--}}
            <div class="box-header" style="margin:8px 0 0;border-bottom:1px solid #f4f4f4;">

                @if($data->comment->is_anonymous == 1)
                    <a href="javascript:void(0)">{{$data->source->anonymous_name or ''}}(匿名)</a>
                @else
                    <a target="_blank" href="{{url('/u/'.encode($data->source_id))}}">{{$data->source->name or ''}}</a>
                @endif

                @if($data->sort == 1)
                    : {{$data->comment->content or ''}}
                @elseif($data->sort == 2)
                    : {{$data->comment->content or ''}}
                @elseif($data->sort == 3) <i class="fa fa-thumbs-up text-red"></i>赞了我的
                @elseif($data->sort == 5) <i class="fa fa-thumbs-up text-red"></i>赞了我的的评论
                @endif

                <span class="pull-right">{{ $data->created_at->format('Y-n-j H:i') }}</span>

            </div>


            {{----}}
            <div class="box-body text-muted margin" style="background-color: #f4f4f4;">

                <div class="box-body">
                    <a target="_blank" href="{{url('/topic/'.encode($data->topic_id))}}">{{$data->topic->title or ''}}</a>
                </div>

                @if($data->sort == 2 || $data->sort == 5)
                <div class="box-footer">

                    {{--评论者--}}
                    @if($data->reply->is_anonymous == 1)
                        <a href="javascript:void(0)">{{$data->reply->user->anonymous_name or ''}}(匿名)</a>
                    @else
                        <a target="_blank" href="{{url('/u/'.encode($data->reply->user_id))}}">{{$data->reply->user->name or ''}}</a>
                    @endif

                    {{--被评论者--}}
                    @if($data->reply->reply_id)

                        回复
                        @if($data->reply->reply->is_anonymous == 1)
                            <a href="javascript:void(0)">{{$data->reply->reply->user->anonymous_name or ''}}(匿名)</a>
                        @else
                            <a target="_blank" href="{{url('/u/'.encode($data->reply->reply->user_id))}}">{{$data->reply->reply->user->name or ''}}</a>
                        @endif

                    @endif

                    : {{$data->reply->content or ''}}
                </div>
                @endif

            </div>

            {{--tools--}}
            <div class="box-footer">
                @if($data->sort == 1 || $data->sort == 2)
                @endif
            </div>


        </div>
        <!-- END PORTLET-->
    </div>
</div>
@endforeach

{{ $datas->links() }}