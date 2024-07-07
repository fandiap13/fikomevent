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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Poster</th>
                                    <th>Event</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Jenis Event</th>
                                    <th>Status Event</th>
                                    <th>Status Pendaftaran</th>
                                    <th class="text-center">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($data) == 0)
                                    <tr>
                                        <td colspan="8" class="text-center">Data kosong...</td>
                                    </tr>
                                @endif

                                @foreach ($data as $key => $row)
                                    @php
                                        if ($row->event->gambar == '') {
                                            $row['poster'] = 'assets/img/no-image.png';
                                        } else {
                                            $row['poster'] = Storage::exists('public/' . $row->event->gambar)
                                                ? 'storage/' . $row->event->gambar
                                                : 'assets/img/no-image.png';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-center">
                                            <a href="{{ asset($row->poster) }}" target="_blank">
                                                <img src="{{ asset($row->poster) }}" alt="Poster"
                                                    style="width: 100px; height: 100px; object-fit: contain">
                                            </a>
                                        </td>
                                        <td>{{ $row->event->judul }}</td>
                                        <td>{{ $row->event->created_at }}</td>
                                        <td class="text-center">{!! $row->event->jenis_event == 'gratis'
                                            ? '<span class="badge badge-success">Gratis</span>'
                                            : '<span class="badge badge-info">Berbayar</span>' !!}</td>
                                        <td class="text-center">{!! $row->event->status_event == 'aktif'
                                            ? '<span class="badge badge-success">Aktif</span>'
                                            : '<span class="badge badge-danger">Tidak aktif</span>' !!}</td>
                                        <td class="text-center">{!! $row->acc == 1
                                            ? '<span class="badge badge-success">ACC</span>'
                                            : ($row->acc == 0
                                                ? '<span class="badge badge-warning">Diproses</span>'
                                                : '<span class="badge badge-danger">Ditolak</span>') !!}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ url('detail-pendaftaran-event/' . $row->id) }}"
                                                    class="btn btn-sm btn-default" title="Lihat Detail"><i
                                                        class="fa fa-search"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex mt-3 justify-content-end">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
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
