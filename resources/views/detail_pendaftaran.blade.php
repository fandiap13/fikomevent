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
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">Lihat Event</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            @php
                                if ($event->event->gambar == '') {
                                    $poster = 'assets/img/no-image.png';
                                } else {
                                    $poster = Storage::exists('public/' . $event->event->gambar)
                                        ? 'storage/' . $event->event->gambar
                                        : 'assets/img/no-image.png';
                                }
                            @endphp

                            <a href="{{ asset($poster) }}" target="_blank">
                                <img id="poster" src="{{ asset($poster) }}"
                                    style="width: 100%; height: 200px; object-fit: contain;" alt="Poster">
                            </a>
                        </li>
                        <li class="list-group-item"><strong>Event Yang Diikuti: </strong>{{ $event->event->judul }}</li>
                        <li class="list-group-item"><strong>Deskripsi: </strong>{{ $event->event->deskripsi }}</li>
                        <li class="list-group-item"><strong>Tanggal Posting:
                            </strong>{{ date('d F Y', strtotime($event->event->created_at)) }}</li>
                        <li class="list-group-item"><strong>Tenggat Waktu:
                            </strong>{{ date('d F Y', strtotime($event->event->waktu_selesai)) }}</li>
                        <li class="list-group-item"><strong>Jenis Event: </strong>
                            {!! $event->event->jenis_event == 'gratis'
                                ? '<span class="badge badge-success">GRATIS</span>'
                                : '<span class="badge badge-info">BERBAYAR</span>' !!}
                        </li>
                        <li class="list-group-item"><strong>Kuota:
                            </strong>{{ $event->event->kuota }} orang</li>
                        <li class="list-group-item"><strong>Biaya:
                            </strong>{{ $event->event->biaya ? 'Rp. ' . number_format($event->event->biaya, 0, ',', '.') : '-' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">Data Pendaftaran Event</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Tanggal Pendaftaran: </strong>{{ $event->created_at }}</li>
                        <li class="list-group-item"><strong>Nama Pendaftar: </strong>{{ $event->nama }}</li>
                        <li class="list-group-item"><strong>Instansi: </strong>{{ $event->instansi }}</li>
                        <li class="list-group-item"><strong>No.Telp/WA: </strong>{{ $event->telp }}</li>
                        <li class="list-group-item"><strong>Status Pendaftar: </strong>{{ Str::ucfirst($event->status) }}
                        <li class="list-group-item"><strong>Status Pendaftaran: </strong>{!! $event->acc == 1
                            ? '<span class="badge badge-success">ACC</span>'
                            : ($event->acc == 0
                                ? '<span class="badge badge-warning">Diproses</span>'
                                : '<span class="badge badge-danger">Ditolak</span>') !!}
                        </li>
                        @if ($event->bukti_pembayaran != null || $event->bukti_pembayaran != '')
                            <li class="list-group-item"><strong>Bukti Pembayaran:</strong>
                                <br>
                                @php
                                    $event['bukti_pembayaran'] = Storage::exists('public/' . $event->bukti_pembayaran)
                                        ? 'storage/' . $event->bukti_pembayaran
                                        : 'assets/img/no-image.png';
                                @endphp
                                <a href="{{ asset($event->bukti_pembayaran) }}" target="_blank">
                                    <img src="{{ asset($event->bukti_pembayaran) }}" alt="Bukti Pembayaran"
                                        style="width: 300px; height: 300px; object-fit: contain">
                                </a>
                            </li>
                        @endif
                    </ul>
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
