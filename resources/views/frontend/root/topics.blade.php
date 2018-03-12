@extends('frontend.layout.layout')

@section('title','话题社')
@section('header','')
@section('description','')

@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    {{--作者--}}
    @foreach($datas as $num => $data)
        @include('frontend.component.topic')
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
