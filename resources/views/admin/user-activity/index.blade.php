@extends('admin._layouts.default', [
    'title' => 'Aktivitas Pengguna',
    'menu_active' => 'system',
    'nav_active' => 'user-activity',
])

@section('right-menu')
  <li class="nav-item">
    <a href="{{ url('/admin/user-activity/clear') }}" class="btn plus-btn btn-default mr-2" title="Hapus"><i
        class="fa fa-trash mr-2"></i> Bersihkan</a>
    <button class="btn btn-default plus-btn mr-2" data-toggle="modal" data-target="#filter-dialog" title="Saring"><i
        class="fa fa-filter"></i>
      @if ($filter_active)
        <span class="badge badge-warning">!</span>
      @endif
    </button>
  </li>
@endsection

@section('content')
  <form action="?" method="GET">
    <div class="modal fade" id="filter-dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Penyaringan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="user_id" class="col-form-label col-sm-4">Pengguna:</label>
              <div class="col-sm-8">
                <select class="form-control custom-select select2" id="user_id" name="user_id">
                  <option value="">Semua</option>
                  @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $filter['user_id'] == $user->id ? 'selected' : '' }}>
                      {{ $user->username }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="type" class="col-form-label col-sm-4">Tipe:</label>
              <div class="col-sm-8">
                <select class="form-control custom-select select2" id="type" name="type">
                  <option value="">Semua</option>
                  @foreach ($types as $type => $label)
                    <option value="{{ $type }}" {{ $filter['type'] == $type ? 'selected' : '' }}>
                      {{ $label }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2"></i> Terapkan</button>
            <button type="submit" name="action" value="reset" class="btn btn-default"><i
                class="fa fa-filter-circle-xmark"></i> Reset Filter</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-light">
      <div class="card-body">
        <div class="row">
          <div class="col d-flex justify-content-end">
            <div class="form-group form-inline">
              <label class="mr-2" for="search">Cari:</label>
              <input class="form-control" id="search" name="search" type="text" value="{{ $filter['search'] }}"
                placeholder="Cari deskripsi">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Tipe</th>
                    <th>Aktivitas</th>
                    <th>Deskripsi</th>
                    <th class="text-center" style="max-width:10%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($items as $item)
                    <tr>
                      <td>{{ $item->id }}</td>
                      <td>{{ $item->datetime }}</td>
                      <td>{{ $item->username }}</td>
                      <td>{{ $item->typeFormatted() }}</td>
                      <td>{{ $item->name }}</td>
                      <td>{{ $item->description }}</td>
                      <td class="text-center">
                        <div class="btn-group">
                          <input name="id" type="hidden" value="{{ $item->id }}">
                          <a class="btn btn-default btn-sm" href="{{ url("/admin/user-activity/show/$item->id") }}"
                            title="Lihat"><i class="fa fa-eye"></i></a>
                          <a class="btn btn-danger btn-sm" type="submit" href="{{ url('/admin/user-activity/delete') }}"
                            title="Hapus"><i class="fa fa-trash"></i></a>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr class="empty">
                      <td colspan="7">Belum ada rekaman</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
        @include('admin._components.paginator', ['items' => $items])
      </div>
    </div>
  </form>
@endsection

@section('footscript')
  <script>
    $(document).ready(function() {
      $('.select2').select2();
    })
  </script>
@endsection
