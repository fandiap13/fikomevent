@extends('template.layout_home')

@section('header')
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('/') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

@section('main')
    <div class="row">
        <div class="col-lg-12">
            <form action="" method="get">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Cari event..." value="{{ $search }}"
                        name="search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>

            <div class="row justify-content-center mt-3">
                @foreach ($data as $row)
                    @php
                        if ($row->gambar == '') {
                            $row['gambar'] = 'assets/img/no-image.png';
                        } else {
                            $row['gambar'] = Storage::exists('public/' . $row->gambar)
                                ? 'storage/' . $row->gambar
                                : 'assets/img/no-image.png';
                        }
                    @endphp
                    <div class="col-lg-3">
                        <div class="card" style="width: 100%;">
                            <img class="card-img-top" style="height: 250px; object-fit: cover;"
                                src="{{ asset($row->gambar) }}" alt="Poster">
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold">{{ $row->judul }}</h5>
                                <p class="card-text"></p>
                                <a href="#" onclick="showDetail('{{ $row->id }}')"
                                    class="btn btn-block btn-success"><i class="fa fa-search"></i> Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-12 d-flex justify-content-center">
            {{ $data->links() }}
        </div>
    </div>
    <!-- /.row -->

    <div class="modal fade" id="modal-default" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title judul"></h4>
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
                            <img id="poster" src="{{ asset('assets/img/no-image.png') }}"
                                style="width: 100%; height: 400px; object-fit: contain;" alt="Poster">
                        </li>
                        <li class="list-group-item"><strong>Judul: </strong><span class="judul"></span></li>
                        <li class="list-group-item"><strong>Tanggal Posting: </strong><span id="tanggal_posting"></span>
                        </li>
                        <li class="list-group-item"><strong>Tenggat Waktu: </strong><span id="tenggat_waktu"></span> <span
                                id="jatuh_tempo"></span></li>
                        </li>
                        <li class="list-group-item"><strong>Jenis Event: </strong><span id="jenis_event"></span></li>
                        </li>
                        <li class="list-group-item"><strong>Kuota: </strong><span id="kuota"></span></li>
                        <li class="list-group-item"><strong>Biaya: </strong><span id="biaya"></span></li>
                        <li class="list-group-item"><strong>Jumlah yang sudah daftar: </strong><span id="jml_daftar"></span>
                        <li class="list-group-item"><strong>Deskripsi: </strong><span id="deskripsi"></span></li>
                        <li class="list-group-item show-button-event">

                        </li>
                    </ul>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endSection()

@push('scripts')
    <script>
        var countdownInterval;

        function calculateCountdown(startTime, endTime) {
            var start = new Date(startTime).getTime();
            var end = new Date(endTime).getTime();

            countdownInterval = setInterval(function() {
                var now = new Date().getTime();
                var distance = end - now;

                // Jika waktu habis, hentikan interval
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    $('#jatuh_tempo').html("Waktu habis");
                    $('#jatuh_tempo').html(`<span class='badge badge-danger'>Waktu habis</span>`);
                    return;
                }

                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                var jatuh_tempo = days + " hari " + hours + " jam " + minutes + " menit " + seconds +
                    " detik ";
                $('#jatuh_tempo').html(`<span class='badge badge-success'>${jatuh_tempo}</span>`);
            }, 1000);
        }

        function showDetail(id) {
            $(".loading-event").removeClass('d-none');
            $(".show-event").addClass('d-none');
            $("#modal-default").modal('show');
            $.ajax({
                url: "{{ url('detailevent') }}/" + id,
                method: 'GET',
                processData: false,
                contentType: false,
                dataType: 'json',
            }).done(function(response) {
                if (response.status) {
                    $(".loading-event").addClass('d-none');
                    $(".show-event").removeClass('d-none');

                    var event = response.data;
                    // Set nilai masing-masing elemen di modal
                    $('#poster').attr('src', event.gambar ? event.gambar :
                        "{{ asset('assets/img/no-image.png') }}");
                    $('.judul').text(event.judul);
                    $('#tanggal_posting').text(event.tanggal_posting);
                    $('#tenggat_waktu').text(event.selesai);
                    $('#deskripsi').html(event.deskripsi);
                    $('#jenis_event').html(event.jenis_event);
                    $('#kuota').text(event.kuota);
                    $('#biaya').text(event.biaya);
                    $('#jml_daftar').text(event.jml_daftar);

                    if (event.status_daftar) {
                        $('.show-button-event').html(`<a href="{{ url('detail-pendaftaran-event') }}/${id}" class="btn btn-info btn-block link-pendaftaran"><i
                                    class="fa fa-check-circle"></i>
                                Anda Sudah Mendaftar...</a>`);
                    } else {
                        if (event.status_waktu_aktif) {
                            $('.show-button-event').html(`<a href="{{ url('pendaftaran') }}/${id}" class="btn btn-success btn-block link-pendaftaran"><i
                                    class="fa fa-clipboard"></i>
                                Lakukan Pendaftaran</a>`);
                        } else {
                            $('.show-button-event').html(`<a href="#" class="btn btn-danger btn-block" disabled><i
                                    class="fa fa-clipboard" style="opacity: 0.6;"></i>
                                Pendaftaran ditutup...</a>`);
                        }
                    }

                    calculateCountdown(event.waktu_mulai, event.waktu_selesai);
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
            })
        }

        // Event listener untuk menghentikan interval ketika modal ditutup
        $('#modal-default').on('hidden.bs.modal', function() {
            clearInterval(countdownInterval);
        });
    </script>
@endpush
