@extends('template.layout_admin')

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
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
                            <a class="btn btn-default" href="{{ route('admin.users.index') }}"><i
                                    class="fa fa-sync-alt"></i> Refresh</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="form-group row">
                                <div class="col-md-11"> <input type="text" class="form-control" name="search"
                                        value="{{ $search }}" placeholder="Cari user..."></div>
                                <div class="col-md-1">
                                    <button class="btn btn-block btn-primary" type="submit"><i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Email</th>
                                        <th>Nama</th>
                                        <th>Instansi</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Event yang diikuti</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($data) == 0)
                                        <tr>
                                            <td colspan="5" class="text-center">Data kosong...</td>
                                        </tr>
                                    @endif

                                    @foreach ($data as $key => $row)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>{{ $row->email }}</td>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->instansi }}</td>
                                            <td>{{ date('d F Y', strtotime($row->created_at)) }}</td>
                                            <td class="text-center">{{ count($row->events) }}</td>
                                            <td class="text-center">
                                                {{-- @if ($row->role == 'admin') --}}
                                                <div class="btn-group">
                                                    <a href="{{ url('admin/users/' . $row->id . '/edit') }}"
                                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                    <button onclick="destroy('{{ $row->id }}')"
                                                        class="btn btn-sm btn-danger"><i
                                                            class="fa fa-trash-alt"></i></button>
                                                    <a href="{{ url('admin/users/' . $row->id) }}"
                                                        class="btn btn-sm btn-default"><i class="fa fa-search"></i></a>
                                                </div>
                                                {{-- @else
                                                    <div class="btn-group">
                                                        <a href="{{ url('admin/users/' . $row->id . '/edit') }}"
                                                            class="btn btn-sm btn-primary" title="reset password"><i
                                                                class="fa fa-sync-alt"></i></a>
                                                        <button onclick="destroy('{{ $row->id }}')"
                                                            class="btn btn-sm btn-danger" title="hapus"><i
                                                                class="fa fa-trash-alt"></i></button>
                                                        <a href="{{ url('admin/users/' . $row->id) }}"
                                                            class="btn btn-sm btn-default" title="detail"><i
                                                                class="fa fa-search"></i></a>
                                                    </div>
                                                @endif --}}
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
                    <div class="overlay d-none">
                        <i class="fa fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
@endsection

@push('scripts')
    <script>
        function destroy(id) {
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
                title: "Yakin ingin menghapus data event?",
                message: "Data event yang dihapus tidak dapat dikembalikan!",
                callback: function(result) {
                    if (result) {
                        var data = {
                            _token: "{{ csrf_token() }}",
                        };
                        $.ajax({
                            url: "{{ url('admin/users') }}/" + id,
                            dataType: 'json',
                            data: data,
                            type: 'DELETE',
                            beforeSend: function() {
                                $('.overlay').removeClass('d-none');
                            }
                        }).done(function(response) {
                            $('.overlay').addClass('d-none');
                            if (response.status) {
                                $.gritter.add({
                                    title: 'Success!',
                                    text: response.message,
                                    class_name: 'gritter-success',
                                    time: 1000,
                                });
                                location.reload();
                            } else {
                                $.gritter.add({
                                    title: 'Warning!',
                                    text: response.message,
                                    class_name: 'gritter-warning',
                                    time: 1000,
                                });
                            }
                        }).fail(function(response) {
                            var response = response.responseJSON;
                            $('.overlay').addClass('d-none');
                            $.gritter.add({
                                title: 'Error!',
                                text: response.message ? response.message :
                                    'Terdapat kesalahan pada sistem!',
                                class_name: 'gritter-error',
                                time: 1000,
                            });
                        })
                    }
                }
            });
        }
    </script>
@endpush
