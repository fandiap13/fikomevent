@extends('template.layout_home')

@section('header')
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    {{-- <li class="breadcrumb-item active"><a href="{{ url('/') }}">Beranda</a></li> --}}
                    {{-- <li class="breadcrumb-item active">Top Navigation</li> --}}
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

@section('main')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <p>Selamat datang di Fikom Event, portal resmi Fakultas Ilmu Komunikasi Universitas Duta Bangsa (UDB).
                        Website ini menyediakan informasi terbaru mengenai berbagai event dan kegiatan menarik seperti
                        SIBARMATI, webinar, lomba, dan banyak lagi yang diadakan di FIKOM UDB.
                    </p>
                    <p>
                        Fikom Event dirancang untuk memudahkan Anda menemukan detail acara dan mendaftar secara online
                        dengan cepat dan praktis. Kami berkomitmen untuk memberikan informasi yang akurat dan terbaru,
                        memastikan Anda tidak ketinggalan acara-acara penting.
                    </p>
                    <p>
                        Dengan beragam acara yang kami tawarkan, Fikom Event menjadi pusat informasi bagi mahasiswa dan
                        masyarakat umum yang ingin terlibat dalam kegiatan yang bermanfaat dan menginspirasi.
                    </p>
                    <p>
                        Terima kasih telah mengunjungi Fikom Event. Kami berharap dapat melihat Anda di acara-acara kami
                        selanjutnya!
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
@endSection()
