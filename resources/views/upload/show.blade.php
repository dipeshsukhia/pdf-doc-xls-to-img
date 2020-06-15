@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">Preview</h1>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @if(count($files))
                @foreach($files as $file)
                    <div class="col-sm-3">
                        <img src="{{asset(Storage::url('uploads/'.$file))}}" width="150">
                    </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
