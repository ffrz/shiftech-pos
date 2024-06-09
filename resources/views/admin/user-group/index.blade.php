@extends('admin._layouts.default', [
    'title' => 'Grup Pengguna',
    'menu_active' => 'system',
    'nav_active' => 'user-group',
])

@section('right-menu')
  <li class="nav-item">
    <a class="btn plus-btn btn-primary mr-2" href="{{ url('/admin/user-group/edit/0') }}" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endSection

@section('content')
  <div class="card card-light">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th style="width:30%">Nama Grup</th>
                <th>Deskripsi</th>
                <th style="width:5%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($items as $item)
                <tr>
                  <td>{{ $item->name }}</td>
                  <td>{{ $item->description }}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a class="btn btn-default btn-sm" href="{{ url("/admin/user-group/edit/$item->id") }}">
                        <i class="fa fa-edit"></i>
                      </a>
                      <a class="btn btn-danger btn-sm" href="{{ url("/admin/user-group/delete/$item->id") }}" onclick="return confirm('Anda yakin akan menghapus rekaman ini?')">
                        <i class="fa fa-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr class="empty">
                  <td colspan="3">Belum ada rekaman</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @include('admin._components.paginator', ['items' => $items])
    </div>
  </div>
@endSection
