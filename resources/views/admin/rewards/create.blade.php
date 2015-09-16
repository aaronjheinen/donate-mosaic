@extends('admin')
@section('title', 'Admin Panel')
@section('content')
<div class="container"> 
    <h2>Create New Reward Level</h2>
    <ul class="breadcrumb">
       <li><a href="{{ url('admin/rewards') }}">Reward Levels</a></li>
       <li class="active">Create New Reward Level</li>
    </ul>
    <form action="{{ url('admin/rewards') }}" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row">
        <div class="col s9">
            <div id="name-group" class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" />
            </div>
        </div>
        <div class="col s3">
            <div id="name-group" class="form-group">
                <label for="name">Blocks</label>
                <input type="number" name="blocks" placeholder="1" />
            </div>
        </div>
        <div class="col s12">
            <h4>Select an Image to be shown with the reward</h4>
            <div class="file-field input-field">
              <input class="file-path validate" type="text"  />
              <div class="btn">
                <span>File</span>
                <input type="file" name="image" />
              </div>
            </div>
        </div>
        <div class="col s12">
            <div id="description-group" class="input-field">
                <textarea name="description" class="materialize-textarea"></textarea>
                <label for="description">Description</label>
            </div>
        </div>
        <div class="col s12">
            <button id="btn_submit" type="submit" class="btn blue right">Create <span class="fa fa-arrow-right"></span></button>
        </div>
    </div>
    </form>
</div>
@endsection