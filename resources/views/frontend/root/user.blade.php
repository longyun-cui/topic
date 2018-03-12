@extends('frontend.layout.layout')

@section('title') {{$data->name or ''}} @endsection
@section('header','')
@section('description','')

@section('header_title') {{ $data->name or '' }} @endsection

@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    {{--作者--}}
    @foreach($topics as $num => $data)
        @include('frontend.component.topic')
    @endforeach

    {{ $topics->links() }}

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
