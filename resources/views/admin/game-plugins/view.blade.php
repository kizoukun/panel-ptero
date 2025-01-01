@extends('layouts.admin')

@section('title')
    Create Game Plugins
@endsection

@section('content-header')
    <h1>Create Game Plugins<small>Create a new game plugin.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li><a href="{{ route('admin.game-plugins') }}">Game Plugins</a></li>
        <li class="active">Create</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <form method="post">
            @method('PATCH')
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Detail</h3>
                    </div>
                    <div class="box-body row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name" class="control-label">Name</label>
                                <div>
                                    <input type="text" autocomplete="off" name="name" value="{{ old('name') ?? $plugin->name  }}"
                                           class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="version" class="control-label">Version</label>
                                <div>
                                    <input type="text" autocomplete="off" name="version" value="{{ old('version') ?? $plugin->version }}"
                                           class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="category" class="control-label">Category</label>
                                <div>
                                    <input type="text" autocomplete="off" name="category" value="{{ old('category') ?? $plugin->category }}"
                                           class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description" class="control-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') ?? $plugin->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Settings</h3>
                    </div>
                    <div class="box-body row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="eggs" class="control-label">Selected Eggs Available</label>
                                <select id="eggs" name="eggs[]" class="form-control" multiple>
                                    @foreach($eggs as $egg)
                                        <option value="{{ $egg->id }}">{{ $egg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="download_url" class="control-label">Download URL</label>
                                <div>
                                    <input type="text" autocomplete="off" name="download_url"
                                           value="{{ old('download_url') ?? $plugin->download_url }}" class="form-control"
                                           placeholder="Example: https://testing.com/myfile.zip" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="decompress" class="control-label">Decompress Type</label>
                                <select name="decompress" class="form-control">
                                    <option value="" @if(old('decompress') == '' || is_null($plugin->decompress_type)) selected @endif>None</option>
                                    <option value="unzip" @if(old('decompress') == 'unzip' || $plugin->decompress_type == 'unzip') selected @endif>Unzip
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Foldering</h3>
                    </div>
                    <div class="box-body row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="folder" class="control-label">Install Folder</label>
                                <div class="input-group">
                                    <span class="input-group-addon" id="folder">/home/container/</span>
                                    <input type="text" id="folder" name="install_folder" class="form-control"
                                           placeholder="Example: mods" aria-describedby="folder"
                                           value="{{ old('install_folder') ?? $plugin->install_folder }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox checkbox-primary no-margin-bottom">
                                    <input type="checkbox" id="is-delete-all" name="is_delete_all" @checked($plugin->is_delete_all) />
                                    <label for="is-delete-all" class="strong">Delete all files from server base
                                        folder</label>
                                </div>
                            </div>
                        </div>
                        <div id="delete-file" class="{{ $plugin->is_delete_all ? 'hidden' : '' }}">
                            <div class="col-md-12" style="display: flex; justify-content: end;">
                                <button type="button" id="new_line" class="btn btn-success">Add New Line</button>
                            </div>
                            <div id="list-line">
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! csrf_field() !!}
                        <input type="submit" value="Update Game Plugins" class="btn btn-primary">
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>

        function addNewLine(defaultValue) {
            return `
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">Delete Folder or File</label>
                <div class="input-group">
                    <span class="input-group-addon" id="folder">/home/container/</span>
                    <input type="text" name="delete_folder[]" class="form-control" ${defaultValue ? `value="${defaultValue}"` : ''} placeholder="Example: mods" aria-describedby="folder">
                    <span class="input-group-btn">
                        <button class="btn btn-danger delete-btn" type="button"><i class="fa fa-trash-o"></i></button>
                    </span>
                </div>
            </div>
        </div>`;
        }

        $(document).ready(() => {

            $('#eggs').select2();


            $('#new_line').click(() => {

                $('#list-line').append(addNewLine());
            });

            $('#list-line').on('click', '.delete-btn', function() {
                const inputGroup = $(this).closest('.input-group');

                inputGroup.closest('.col-md-12').remove();
            });

            $('#is-delete-all').change((event) => {
                if ($(event.target).prop('checked')) {
                    $('#delete-file').addClass('hidden');
                } else {
                    $('#delete-file').removeClass('hidden');
                }
            });

            @if (old('eggs') || $plugin->eggs)
            const eggs = [];

            @php
                $eggs = old('eggs') ?? $plugin->eggs;
            @endphp

            @foreach ($eggs as $egg)
                eggs.push('{{ $egg }}');
            @endforeach

            $('#eggs').val(eggs).change();
            @endif


            @if (old('delete_folder') || $plugin->delete_folder)
            @php
                $deleteFolders = old('delete_folder') ?? $plugin->delete_folder;
            @endphp

            @foreach ($deleteFolders as $folder)
            $('#list-line').append(addNewLine('{{ $folder }}'));
            @endforeach
            @endif

        });

    </script>
@endsection
