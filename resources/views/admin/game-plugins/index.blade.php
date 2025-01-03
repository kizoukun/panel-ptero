@extends('layouts.admin')

@section('title')
    Game Plugins
@endsection

@section('content-header')
    <h1>Game Plugins<small>This is a game plugins?.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Game Plugins</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Game Plugins List</h3>
                    <div class="box-tools search01">
                        <form action="{{ route('admin.game-plugins') }}" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" name="filter[name]" class="form-control pull-right" value="{{ request()->input('filter.name') }}" placeholder="Search Nodes">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    <a href="{{ route('admin.game-plugins.new') }}"><button type="button" class="btn btn-sm btn-primary" style="border-radius: 0 3px 3px 0;margin-left:-1px;">Create New</button></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th class="text-center">Version</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        @foreach($plugins as $plugin)
                            <tr>
                                <td><code>{{ $plugin->id }}</code></td>
                                <td>{{ $plugin->name }}</td>
                                <td style="text-transform: capitalize;">{{ $plugin->category }}</td>
                                <td class="text-center"><code>{{ $plugin->version }}</code></td>
                                <td class="text-center">
                                    <div style="display: flex; justify-content: center; gap: 0.25rem">
                                        <a href="{{ route('admin.game-plugins.view', $plugin->id) }}">
                                            <button class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></button>
                                        </a>
                                        <form id="deleteform" action="{{ route('admin.game-plugins.delete', $plugin->id) }}" method="POST">
                                            {!! csrf_field() !!}
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $plugin->id }}">
                                            <button type="button" class="deletebtn btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if($plugins->hasPages())
                    <div class="box-footer with-border">
                        <div class="col-md-12 text-center">{!! $plugins->appends(['filter' => Request::input('filter')])->render() !!}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('footer-scripts')
    @parent

    <script>
        $(document).ready(() => {
            $(document).on('click', '.deletebtn', function (event) {
                event.preventDefault();
                const form = $(this).closest('form'); // Get the closest form for the clicked button
                swal({
                    title: '',
                    type: 'warning',
                    text: 'Are you sure that you want to delete this server? There is no going back, all data will immediately be removed.',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#d9534f',
                    closeOnConfirm: false
                }, function () {
                    form.submit(); // Submit the specific form
                });
            });
        });
    </script>
@endsection
