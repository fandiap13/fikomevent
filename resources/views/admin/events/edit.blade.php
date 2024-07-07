@extends('template.layout_admin')

@push('styles')
    <link href="{{ asset('adminlte/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/bootstrap-fileinput/themes/explorer-fas/theme.min.css') }}" rel="stylesheet">
@endpush

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Event</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

@section('main')
    <div class="container-fluid">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" id="form"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title">
                            </h5>

                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">

                                        <a class="btn btn-default" href="{{ route('admin.events.index') }}"><i
                                                class="fa fa-undo"></i></a>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible mb-3">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group row">
                                <label for="judul" class="col-sm-2 col-form-label">Judul <b
                                        class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    <input type="text" class="form-control" id="judul" name="judul"
                                        placeholder="Judul" value="{{ old('judul', $event->judul) }}" required />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi <b
                                        class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    <textarea name="deskripsi" id="deskripsi" cols="30" rows="3" class="form-control" placeholder="Deskripsi"
                                        required>{{ old('deskripsi', $event->deskripsi) }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="kuota" class="col-sm-2 col-form-label">Kuota</label>
                                <div class="col-sm-6 controls">
                                    <input type="number" class="form-control" id="kuota" name="kuota"
                                        placeholder="kuota" value="{{ old('kuota', $event->kuota) }}" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="jenis_event" class="col-sm-2 col-form-label">Jenis Event<b
                                        class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    <select name="jenis_event" id="jenis_event" class="form-control" required>
                                        <option value="gratis"
                                            {{ old('jenis_event', $event->jenis_event) == 'gratis' ? 'selected' : '' }}>
                                            Gratis</option>
                                        <option value="berbayar"
                                            {{ old('jenis_event', $event->jenis_event) == 'berbayar' ? 'selected' : '' }}>
                                            Berbayar</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row show_biaya"
                                style="display: {{ old('jenis_event', $event->jenis_event) == 'berbayar' ? 'default' : 'none' }};">
                                <label for="biaya" class="col-sm-2 col-form-label">Biaya Pendaftaran<b
                                        class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    <input type="number" class="form-control" id="biaya" name="biaya"
                                        placeholder="biaya" value="{{ old('biaya', $event->biaya) }}" required />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="waktu_mulai" class="col-sm-2 col-form-label">Mulai<b
                                        class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    <input type="datetime-local" class="form-control" id="waktu_mulai" name="waktu_mulai"
                                        placeholder="waktu_mulai" value="{{ old('waktu_mulai', $event->waktu_mulai) }}"
                                        required />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="waktu_selesai" class="col-sm-2 col-form-label">Selesai<b
                                        class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    <input type="datetime-local" class="form-control" id="waktu_selesai"
                                        name="waktu_selesai" placeholder="waktu_selesai"
                                        value="{{ old('waktu_selesai', $event->waktu_selesai) }}" required />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status_event" class="col-sm-2 col-form-label">Status Event<b
                                        class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    <select name="status_event" id="status_event" class="form-control" required>
                                        <option value="aktif"
                                            {{ old('status_event', $event->status_event) == 'aktif' ? 'selected' : '' }}>
                                            Aktif</option>
                                        <option value="tidak aktif"
                                            {{ old('status_event', $event->status_event) == 'tidak aktif' ? 'selected' : '' }}>
                                            Tidak aktif</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Poster</label>
                                <div class="col-sm-6">
                                    <input type="file" class="form-control" name="gambar" id="gambar"
                                        accept="image/*" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </form>
    </div><!-- /.container-fluid -->
@endsection

@push('scripts')
    <script src="{{ asset('adminlte/plugins/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap-fileinput/themes/explorer-fas/theme.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("select[name=jenis_event]").change(function(e) {
                e.preventDefault();
                if ($(this).val() == 'berbayar') {
                    $(".show_biaya").show();
                } else {
                    $(".show_biaya").hide();
                }
            });

            $("#gambar").fileinput({
                browseClass: "btn btn-primary",
                showRemove: false,
                showUpload: false,
                showDrag: false,
                allowedFileExtensions: ["png", "jpg", "jpeg"],
                dropZoneEnabled: false,
                initialPreview: '<img src="{{ asset($event->gambar) }}" class="kv-preview-data file-preview-image">',
                initialPreviewAsData: false,
                initialPreviewFileType: 'image',
                initialPreviewConfig: [{
                    caption: "{{ $event->gambar }}",
                    downloadUrl: "{{ asset($event->gambar) }}",
                    size: "{{ @File::size(public_path($event->gambar)) }}",
                    url: false
                }],
                theme: 'explorer-fas'
            });

            $('#form').validate({
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    if (element.is(':file')) {
                        error.insertAfter(element.parent().parent().parent());
                    } else
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else
                    if (element.attr('type') == 'checkbox') {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    $("#form").find("button[type=submit]").attr('disabled', true).html(
                        "<i class='fa fa-spin fa-sync-alt'></i>");
                    form.submit();
                }
            });
        });
    </script>
@endpush
