@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="text-center">Upload List</h1>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped" id="myTable">
                        <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(collect($data->items())->count() > 0)
                            @foreach($data as $key => $val)
                                <tr>
                                    <th scope="row">{{ $key + $data->firstItem() }}</th>
                                    <td>{{ $val->name }}</td>
                                    <td>{{ $val->created_at }}</td>
                                    <td>
                                        <a href="{{ route('uploads.show',$val->id) }}"
                                           class="btn btn-primary btn-xs" style="display: inline;" data-toggle="tooltip"
                                           data-placement="top" title="View!">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6"><h3 align="center">Data Not Found</h3></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                {{ $data->appends(\Request::all())->links() }}
            </div>
        </div>
    </div>
@endsection
