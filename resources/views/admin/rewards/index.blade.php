@extends('admin')
@section('title', 'Admin Panel')
@section('breadcrumbs')
    @parent

    / <a class="page-title">Reward Levels</a>
@endsection
@section('content')
<div class="container" v-cloak> 
    <a href="{{ url('admin/rewards/create') }}" class="btn blue right">Add New Reward</a>
    <h2>Reward Levels</h2>
    <table class="striped bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Blocks Required</th>
                <th>Price</th>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rewards as $reward)
                <tr>
                    <td>@if(isset($reward->media)) <img src="{{$reward->media->url}}" /> @endif</td>
                    <td>{{$reward->blocks}}</td>
                    <td>{{$set->price * $reward->blocks}}</td>
                    <td>{{$reward->name}}</td>
                    <td>{{$reward->description}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection