@extends('template.layout_home')

@push('styles')
    <link href="{{ asset('adminlte/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/bootstrap-fileinput/themes/explorer-fas/theme.min.css') }}" rel="stylesheet">
@endpush

@section('header')
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

@section('main')
    <div class="row">
        @csrf
        <div class="col-md-12">
            <form action="{{ url('simpan-pendaftaran') }}" method="POST" id="form">
                <input type="hidden" id="event_id" name="event_id" value="{{ $event->id }}">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title">
                            <a class="btn btn-default" onclick="return window.location.reload()"><i
                                    class="fa fa-sync-alt"></i>
                                Refresh</a>
                        </h5>

                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item">
                                    <a title="Kembali" class="btn btn-default" href="{{ url('/') }}"><i
                                            class="fa fa-undo"></i></a>
                                    <button type="submit" title="Simpan" class="btn btn-primary"><i
                                            class="fa fa-save"></i></button>
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
                            <label for="judul" class="col-sm-2 col-form-label">judul <b class="text-danger">*</b></label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control" value="{{ $event->judul }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama Pendaftar <b
                                    class="text-danger">*</b></label>
                            <div class="col-sm-6 controls">
                                <input type="text" name="nama" class="form-control" value="{{ Auth::user()->nama }}"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="instansi" class="col-sm-2 col-form-label">Instansi <b
                                    class="text-danger">*</b></label>
                            <div class="col-sm-6 controls">
                                <input type="text" class="form-control" id="instansi" name="instansi"
                                    placeholder="Instansi" value="{{ old('instansi', $user->instansi) }}" required />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="telp" class="col-sm-2 col-form-label">No.Telp/WA <b
                                    class="text-danger">*</b></label>
                            <div class="col-sm-6 controls">
                                <input type="number" class="form-control" id="telp" name="telp"
                                    placeholder="No.Telp/WA" value="{{ old('telp', $user->telp) }}" required />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="status" class="col-sm-2 col-form-label">Status<b class="text-danger">*</b></label>
                            <div class="col-sm-6 controls">
                                <select name="status" id="status" class="form-control" required>
                                    <option value="mahasiswa"
                                        {{ old('status', $user->status) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa
                                    </option>
                                    <option value="dosen" {{ old('status', $user->status) == 'dosen' ? 'selected' : '' }}>
                                        Dosen</option>
                                    <option value="umum" {{ old('status', $user->status) == 'umum' ? 'selected' : '' }}>
                                        Umum</option>
                                </select>
                            </div>
                        </div>

                        @if ($event->jenis_event == 'berbayar')
                            <div class="form-group row">
                                <label for="biaya" class="col-sm-2 col-form-label">Biaya Pendaftaran <b
                                        class="text-danger">*</b></label>
                                <div class="col-sm-6 controls">
                                    <input type="text" class="form-control" value="{{ $event->biaya }}" required
                                        disabled>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Bukti Pembayaran <b
                                        class="text-danger">*</b></label>
                                <div class="col-sm-6">
                                    <input type="file" class="form-control" name="bukti_pembayaran"
                                        id="bukti_pembayaran" accept="image/*" required />
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="overlay d-none">
                        <i class="fa fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endSection()

@push('scripts')
    <script src="{{ asset('adminlte/plugins/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap-fileinput/themes/explorer-fas/theme.min.js') }}"></script>
    <script>
        $("#bukti_pembayaran").fileinput({
            browseClass: "btn btn-primary",
            showRemove: false,
            showUpload: false,
            showDrag: false,
            allowedFileExtensions: ["png", "jpg", "jpeg"],
            dropZoneEnabled: false,
            initialPreview: '<img src="{{ asset('assets/img/no-image.png') }}" class="kv-preview-data file-preview-image">',
            initialPreviewAsData: false,
            initialPreviewFileType: 'image',
            initialPreviewConfig: [{
                caption: "{{ 'assets/img/no-image.png' }}",
                downloadUrl: "{{ asset('assets/img/no-image.png') }}",
                size: "{{ @File::size(public_path('assets/img/no-image.png')) }}",
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
    </script>
@endpush
