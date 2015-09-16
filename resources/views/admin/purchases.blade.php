@extends('admin')
@section('title', 'Admin Panel')
@section('breadcrumbs')
    @parent

    / <a class="page-title">Purchase History</a>
@endsection
@section('content')
<div class="container"> 
    <table class="striped bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Blocks Purchased</th>
                <th>Price</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $purchase)
                <tr>
                    <td>@if(isset($purchase->media)) <img src="{{$purchase->media->url}}" /> @endif</td>
                    <td>{{$purchase->name}}</td>
                    <td>{{$purchase->email}}</td>
                    <td>{{count($purchase->squares)}}</td>
                    <td>${{$purchase->price}}</td>
                    <td>@datetime($purchase->created_at)</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection