@extends('frontend.layout.layout')

@section('title') {{ $data->name or '' }}的主页 @endsection
@section('header','')
@section('description','')

@section('header_title')  @endsection

@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    <div class="container">

        <div class="col-sm-12 col-md-9 container-body-left">

            <div class="box-body  visible-xs" style="margin-bottom:16px;background:#fff;">
                <b>{{ $data->name or '' }}的主页</b>
            </div>

            {{--@foreach($topics as $num => $item)--}}
                {{--@include('frontend.component.topic')--}}
            {{--@endforeach--}}
            @include('frontend.component.topic')

            {{ $topics->links() }}

        </div>

        <div class="col-sm-12 col-md-3 hidden-xs hidden-sm container-body-right">

            <div class="box-body" style="background:#fff;">
                <b>{{ $data->name or '' }}</b> 的话题
            </div>

        </div>

    </div>

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
        $('article').readmore({
            speed: 150,
            moreLink: '<a href="#">更多</a>',
            lessLink: '<a href="#">收起</a>'
        });
    });
</script>
@endsection
