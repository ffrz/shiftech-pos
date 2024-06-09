@extends('admin._layouts.default', [
    'title' => 'Aktivitas Pengguna',
    'menu_active' => 'system',
    'nav_active' => 'user-activity',
])

@section('content')
  <div class="card card-light">
    <div class="card-body">
      <form action="?" method="GET">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group form-inline">
              <label class="mr-2" for="user_id">Pengguna:</label>
              <select class="form-control custom-select" id="user_id" name="user_id" onchange="this.form.submit();">
                <option value="">Semua</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ $filter['user_id'] == $user->id ? 'selected' : '' }}>
                    {{ $user->username }}</option>
                @endforeach
              </select>
              <label class="ml-4 mr-2" for="type">Tipe:</label>
              <select class="form-control custom-select" id="type" name="type" onchange="this.form.submit();">
                <option value="">Semua</option>
                @foreach ($types as $type => $label)
                  <option value="{{ $type }}" {{ $filter['type'] == $type ? 'selected' : '' }}>
                    {{ $label }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6 d-flex justify-content-end">
            <div class="form-group form-inline">
              <label class="mr-2" for="search">Cari:</label>
              <input class="form-control" id="search" name="search" type="text" value="{{ $filter['search'] }}" placeholder="Cari deskripsi">
            </div>
          </div>
        </div>
      </form>
      <div class="row">
        <div class="col-md-12">
          <form method="POST" action="{{ url('admin/user-activity/delete') }}" onsubmit="return confirm('Hapus rekaman?')">
            @csrf
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
                          <a class="btn btn-default btn-sm" href="{{ url("/admin/user-activity/show/$item->id") }}" title="Lihat"><i class="fa fa-eye"></i></a>
                          <button class="btn btn-danger btn-sm" type="submit" href="{{ url('/admin/user-activity/delete') }}" title="Hapus"><i class="fa fa-trash"></i></button>
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
          </form>
        </div>
      </div>
      @include('admin._components.paginator', ['items' => $items])
    </div>
  </div>
@endsection
