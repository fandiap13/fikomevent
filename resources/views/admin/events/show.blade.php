@extends('template.layout_admin')

@push('styles')
    <link href="{{ asset('adminlte/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminlte/plugins/bootstrap-fileinput/themes/explorer-fas/theme.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title">
                            Judul Event <strong>{{ $event->judul }}</strong>
                        </h5>

                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item">

                                    <a class="btn btn-default" href="{{ route('admin.events.index') }}"><i
                                            class="fa fa-undo"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                @php
                                    if ($event->gambar == '') {
                                        $poster = 'assets/img/no-image.png';
                                    } else {
                                        $poster = Storage::exists('public/' . $event->gambar)
                                            ? 'storage/' . $event->gambar
                                            : 'assets/img/no-image.png';
                                    }
                                @endphp
                                <a href="{{ asset($poster) }}" target="_blank">
                                    <img id="poster" src="{{ asset($poster) }}"
                                        style="width: 100%; height: 200px; object-fit: contain;" alt="Poster">
                                </a>
                            </li>
                            <li class="list-group-item"><strong>Event Yang Diikuti: </strong>{{ $event->judul }}</li>
                            <li class="list-group-item"><strong>Deskripsi: </strong>{{ $event->deskripsi }}</li>
                            <li class="list-group-item"><strong>Tanggal Posting:
                                </strong>{{ date('d F Y', strtotime($event->created_at)) }}</li>
                            <li class="list-group-item"><strong>Tenggat Waktu:
                                </strong>{{ date('d F Y', strtotime($event->waktu_selesai)) }}</li>
                            <li class="list-group-item"><strong>Status Event: </strong>
                                {!! $event->jenis_event == 'aktif'
                                    ? '<span class="badge badge-success">AKTIF</span>'
                                    : '<span class="badge badge-danger">TIDAK AKTIF</span>' !!}
                            </li>
                            <li class="list-group-item"><strong>Jenis Event: </strong>
                                {!! $event->jenis_event == 'gratis'
                                    ? '<span class="badge badge-success">GRATIS</span>'
                                    : '<span class="badge badge-info">BERBAYAR</span>' !!}
                            </li>
                            <li class="list-group-item"><strong>Kuota:
                                </strong>{{ $event->kuota }} orang</li>
                            <li class="list-group-item"><strong>Biaya:
                                </strong>{{ $event->biaya ? 'Rp. ' . number_format($event->biaya, 0, ',', '.') : '-' }}
                            </li>
                            <li class="list-group-item"><strong>Jumlah Peserta:
                                </strong>{{ count($event->pendaftar) }} orang
                            </li>
                            <li class="list-group-item"><strong>Link Sertifikat Default:
                                </strong><a href="{{ $event->link_sertifikat_default }}"
                                    target="_blank">{{ $event->link_sertifikat_default }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /.col-md-6 -->

            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title">
                            List Peserta
                        </h5>

                        <div class="card-tools">
                            <ul class="nav nav-pills ml-auto">
                                <li class="nav-item">
                                    {{-- <button type="button" class="btn btn-sm btn-default exportAll"><i
                                            class="fa fa-file-excel"></i>
                                        Export Semua Peserta</button> --}}
                                    <button type="button" class="btn btn-sm btn-default"
                                        onclick="showModalSertifAll('{{ $event->id }}')"><i class="fa fa-link"></i>
                                        Buat Link Sertifikat Default</button>
                                    <button type="button" class="btn btn-sm btn-success accSemua"><i
                                            class="fa fa-check-circle"></i> ACC
                                        Semua</button>
                                    <button type="button" class="btn btn-sm btn-danger tolakSemua"><i
                                            class="fa fa-times"></i>
                                        Tolak
                                        Semua</button>
                                    <button type="button" class="btn btn-sm btn-warning resetSemua"><i
                                            class="fa fa-sync-alt"></i>
                                        Reset
                                        Semua Status</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-nowrap" id="datatable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="pilihsemua"></th>
                                        <th class="text-center">No</th>
                                        @if ($event->jenis_event == 'berbayar')
                                            <th class="text-center">Bukti Pembayaran</th>
                                        @endif
                                        <th>Nama Peserta</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Instansi</th>
                                        <th>No.Telp/WA</th>
                                        <th>Sertifikat</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($event->pendaftar as $key => $row)
                                        @php
                                            if ($row->bukti_pembayaran == '') {
                                                $row['bukti_pembayaran'] = 'assets/img/no-image.png';
                                            } else {
                                                $row['bukti_pembayaran'] = Storage::exists(
                                                    'public/' . $row->bukti_pembayaran,
                                                )
                                                    ? 'storage/' . $row->bukti_pembayaran
                                                    : 'assets/img/no-image.png';
                                            }
                                        @endphp
                                        <tr>
                                            <td><input type="checkbox" data-id="{{ $row->id }}" name="accAll[]"
                                                    class="pilihCheckbox">
                                            </td>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            @if ($event->jenis_event == 'berbayar')
                                                <td class="text-center">
                                                    <a href="{{ asset($row->bukti_pembayaran) }}" target="_blank">
                                                        <img src="{{ asset($row->bukti_pembayaran) }}"
                                                            alt="Bukti Pembayaran"
                                                            style="width: 100px; height: 100px; object-fit: contain">
                                                    </a>
                                                </td>
                                            @endif
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->created_at }}</td>
                                            <td>{{ $row->instansi }}</td>
                                            <td>{{ $row->telp }}</td>
                                            <td>
                                                @if ($event->link_sertifikat_default)
                                                    <button onclick="showModalSertif('{{ $row->id }}')"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fa fa-edit"></i> Sudah Ada
                                                    </button>
                                                @else
                                                    @if ($row->link_sertifikat != null)
                                                        <button onclick="showModalSertif('{{ $row->id }}')"
                                                            class="btn btn-sm btn-success">
                                                            <i class="fa fa-edit"></i> Sudah Ada
                                                        </button>
                                                    @else
                                                        <button onclick="showModalSertif('{{ $row->id }}')"
                                                            class="btn btn-sm btn-warning">
                                                            <i class="fa fa-edit"></i> Belum Ada
                                                        </button>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {!! $row->acc == 1
                                                    ? '<button onclick="showModal(' .
                                                        $row->id .
                                                        ')" class="btn btn-sm btn-success"><i class="fa fa-edit"></i> ACC</button>'
                                                    : ($row->acc == 0
                                                        ? '<button onclick="showModal(' .
                                                            $row->id .
                                                            ')" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Diproses</button>'
                                                        : '<button onclick="showModal(' .
                                                            $row->id .
                                                            ')" class="btn btn-sm btn-danger"><i class="fa fa-edit"></i> Ditolak</button>') !!}

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->

    <div class="modal fade" id="modal-sertif-all" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form action="{{ url('admin/events/simpandefaultsertif') }}" method="POST" id="form-sertif-all">
                @csrf
                <input type="hidden" name="id" value="{{ $event->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title font-weight-bold">Buat Link Sertifikat Default</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="link_sertifikat_default">Link Sertifikat Default</label>
                            <input type="text" name="link_sertifikat_default" class="form-control"
                                id="link_sertifikat_default" value="{{ $event->link_sertifikat_default }}">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan
                            Perubahan</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-sertif" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form action="{{ url('admin/events/simpanstatuspendaftaran') }}" method="POST" id="form-link">
                @csrf
                <input type="hidden" name="id">
                <input type="hidden" name="aksi" value="link_sertifikat">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title font-weight-bold judul"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="loading-event">
                            <div class="text-center"><i class="fa fa-2x fa-sync-alt fa-spin text-gray"></i></div>
                        </div>
                        <div class="form-group">
                            <label for="link_sertifikat">Link Sertifikat</label>
                            <input type="text" name="link_sertifikat" class="form-control" id="link_sertifikat">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan
                            Perubahan</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-default" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form action="{{ url('admin/events/simpanstatuspendaftaran') }}" method="POST" id="form">
                @csrf
                <input type="hidden" name="id">
                <input type="hidden" name="aksi" value="status_pembayaran">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title font-weight-bold judul"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="loading-event">
                            <div class="text-center"><i class="fa fa-2x fa-sync-alt fa-spin text-gray"></i></div>
                        </div>
                        <ul class="list-group show-event d-none">
                            <li class="list-group-item">
                                <img id="bukti_pembayaran" src="{{ asset('assets/img/no-image.png') }}"
                                    style="width: 100%; height: 100px; object-fit: contain;" alt="bukti_pembayaran">
                            </li>
                        </ul>

                        <div class="form-group mt-2">
                            <label for="acc">Status Pendaftaran</label>
                            <select name="acc" id="acc" class="form-control" required>
                                <option value="0">Diproses</option>
                                <option value="1">ACC</option>
                                <option value="2">Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan
                            Perubahan</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('adminlte/plugins/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap-fileinput/themes/explorer-fas/theme.min.js') }}"></script>
    <script src="{{ asset('adminlte') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script>
        function saveStatusAll(status, btnName, text) {
            let dataIdArray = [];
            $('input[name="accAll[]"]:checked').each(function() {
                const dataId = $(this).data('id');
                dataIdArray.push(dataId);
            });

            if (dataIdArray.length < 1) {
                $.gritter.add({
                    title: 'Warning!',
                    text: "Anda belum memilih data peserta!",
                    class_name: 'gritter-warning',
                    time: 3000,
                });
                return;
            }

            $.ajax({
                type: "post",
                url: "{{ url('admin/events/simpansemuastatuspendaftaran') }}",
                data: {
                    '_token': `{{ csrf_token() }}`,
                    id: dataIdArray,
                    status: status,
                },
                dataType: "json",
                beforeSend: function() {
                    $(btnName).html("<i class='fa fa-spin fa-sync-alt'></i>").attr(
                        'disabled',
                        'true');
                },
            }).done(function(response) {
                $(btnName).html(text).removeAttr(
                    'disabled');
                // console.log(response);
                if (response.status) {
                    document.location.reload();
                } else {
                    $.gritter.add({
                        title: 'Warning!',
                        text: response.message,
                        class_name: 'gritter-warning',
                        time: 3000,
                    });
                }
            }).fail(function(response) {
                $(btnName).html(text).removeAttr(
                    'disabled');
                var response = response.responseJSON;
                $.gritter.add({
                    title: 'Error!',
                    text: response.message ? response.message : "Terdapat kesalahan pada sistem!",
                    class_name: 'gritter-error',
                    time: 3000,
                });
            });
        }

        function showModal(id) {
            $(".loading-event").removeClass('d-none');
            $(".show-event").addClass('d-none');
            $("#modal-default").modal('show');
            $.ajax({
                url: "{{ url('admin/events/detailpendaftaranevent') }}/" + id,
                method: 'GET',
                processData: false,
                contentType: false,
                dataType: 'json',
            }).done(function(response) {
                if (response.status) {
                    $(".loading-event").addClass('d-none');
                    $(".show-event").removeClass('d-none');

                    var data = response.data;
                    $('#bukti_pembayaran').attr('src', data.bukti_pembayaran ? data.bukti_pembayaran :
                        "{{ asset('assets/img/no-image.png') }}");
                    $('.judul').text(data.nama);
                    $('#acc').val(data.acc);
                    $('input[name=id]').val(data.id);
                } else {
                    $.gritter.add({
                        title: 'Warning!',
                        text: response.message,
                        class_name: 'gritter-warning',
                        time: 3000,
                    });
                }
            }).fail(function(response) {
                var response = response.responseJSON;
                $.gritter.add({
                    title: 'Error!',
                    text: response.message ? response.message : "Terdapat kesalahan pada sistem!",
                    class_name: 'gritter-error',
                    time: 3000,
                });
            });
        }

        function showModalSertif(id) {
            $(".loading-event").removeClass('d-none');
            $(".show-event").addClass('d-none');
            $("#modal-sertif").modal('show');
            $.ajax({
                url: "{{ url('admin/events/detailpendaftaranevent') }}/" + id,
                method: 'GET',
                processData: false,
                contentType: false,
                dataType: 'json',
            }).done(function(response) {
                if (response.status) {
                    $(".loading-event").addClass('d-none');
                    $(".show-event").removeClass('d-none');

                    var data = response.data;
                    $('.judul').text(data.nama);
                    $('input[name=id]').val(data.id);
                    $('input[name=link_sertifikat]').val(data.link_sertifikat);
                } else {
                    $.gritter.add({
                        title: 'Warning!',
                        text: response.message,
                        class_name: 'gritter-warning',
                        time: 3000,
                    });
                }
            }).fail(function(response) {
                var response = response.responseJSON;
                $.gritter.add({
                    title: 'Error!',
                    text: response.message ? response.message : "Terdapat kesalahan pada sistem!",
                    class_name: 'gritter-error',
                    time: 3000,
                });
            });
        }

        function showModalSertifAll(id) {
            $("#modal-sertif-all").modal('show');
        }

        $(document).ready(function() {
            $('#datatable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });

            // $(".exportAll").click(function(e) {
            //     e.preventDefault();
            //     window.location.href = "{{ url('admin/events/exportallparticipants') }}";
            // });

            $("#pilihsemua").click(function(e) {
                if ($('#pilihsemua').is(':checked')) {
                    $(".pilihCheckbox").attr('checked', true);
                } else {
                    $(".pilihCheckbox").removeAttr('checked');
                }
            });

            $(".accSemua").click(function(e) {
                e.preventDefault();
                bootbox.confirm({
                    buttons: {
                        confirm: {
                            label: '<i class="fa fa-check"></i>',
                            className: 'btn-danger'
                        },
                        cancel: {
                            label: '<i class="fa fa-undo"></i>',
                            className: 'btn-default'
                        },
                    },
                    title: "Yakin ingin mengubah semua status ke ACC?",
                    message: "Data status akan terubah semua!",
                    callback: function(result) {
                        if (result) {
                            saveStatusAll(1, ".accSemua",
                                `<i class="fa fa-check-circle"></i> ACC Semua`);
                        }
                    }
                });
            });

            $(".tolakSemua").click(function(e) {
                e.preventDefault();
                bootbox.confirm({
                    buttons: {
                        confirm: {
                            label: '<i class="fa fa-check"></i>',
                            className: 'btn-danger'
                        },
                        cancel: {
                            label: '<i class="fa fa-undo"></i>',
                            className: 'btn-default'
                        },
                    },
                    title: "Yakin ingin mengubah semua status ke Ditolak?",
                    message: "Data status akan terubah semua!",
                    callback: function(result) {
                        if (result) {
                            saveStatusAll(2, ".tolakSemua", `<i class="fa fa-times"></i>
                                        Tolak
                                        Semua`);
                        }
                    }
                });
            });

            $(".resetSemua").click(function(e) {
                e.preventDefault();
                bootbox.confirm({
                    buttons: {
                        confirm: {
                            label: '<i class="fa fa-check"></i>',
                            className: 'btn-danger'
                        },
                        cancel: {
                            label: '<i class="fa fa-undo"></i>',
                            className: 'btn-default'
                        },
                    },
                    title: "Yakin ingin mengubah semua status ke Diproses?",
                    message: "Data status akan terubah semua!",
                    callback: function(result) {
                        if (result) {
                            saveStatusAll(0, ".resetSemua", `<i class="fa fa-sync-alt"></i>
                                        Reset
                                        Semua Status`);
                        }
                    }
                });
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

            $('#form-link').validate({
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
                    $("#form-link").find("button[type=submit]").attr('disabled', true).html(
                        "<i class='fa fa-spin fa-sync-alt'></i>");
                    form.submit();
                }
            });

            $('#form-sertif-all').validate({
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
                submitHandler: function() {
                    $.ajax({
                        type: "post",
                        url: $('#form-sertif-all').attr('action'),
                        data: $('#form-sertif-all').serialize(),
                        dataType: "json",
                        beforeSend: function() {
                            $("#form-sertif-all").find("button[type=submit]").attr(
                                'disabled',
                                true).html(
                                "<i class='fa fa-spin fa-sync-alt'></i>");
                        },
                    }).done(function(response) {
                        $("#form-sertif-all").find("button[type=submit]").html(`<i class="fa fa-save"></i> Simpan
                            Perubahan`).removeAttr(
                            'disabled');
                        // console.log(response);
                        if (response.status) {
                            document.location.reload();
                        } else {
                            $.gritter.add({
                                title: 'Warning!',
                                text: response.message,
                                class_name: 'gritter-warning',
                                time: 3000,
                            });
                        }
                    }).fail(function(response) {
                        $("#form-sertif-all").find("button[type=submit]").html(`<i class="fa fa-save"></i> Simpan
                            Perubahan`).removeAttr(
                            'disabled');
                        var response = response.responseJSON;
                        $.gritter.add({
                            title: 'Error!',
                            text: response.message ? response.message :
                                "Terdapat kesalahan pada sistem!",
                            class_name: 'gritter-error',
                            time: 3000,
                        });
                    });
                }
            });
        });
    </script>
@endpush
