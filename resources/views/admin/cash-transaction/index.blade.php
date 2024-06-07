@extends('admin._layouts.default', [
    'title' => 'Transaksi Keuangan',
    'menu_active' => 'finance',
    'nav_active' => 'cash-transaction',
])

@section('right-menu')
  <li class="nav-item">
    <a href="{{ url('/admin/cash-transaction/edit/0') }}" class="btn plus-btn btn-primary mr-2" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endSection

@section('content')
  <div class="card card-light">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th style="width:1%">Kode</th>
                  <th style="width:1%">Tanggal</th>
                  <th>Akun</th>
                  <th>Kategori</th>
                  <th>Uraian</th>
                  <th>Jumlah</th>
                  <th style="width:5%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($items as $item)
                  <tr>
                    <td class="text-nowrap">{{ $item->idFormatted() }}</td>
                    <td class="text-nowrap">{{ format_date($item->date) }}</td>
                    <td>{{ $item->account->name }}</td>
                    <td>{{ $item->category ? $item->category->name : '-Tanpa Kategori-' }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-right {{ $item->amount > 0 ? 'text-success' : 'text-danger' }}">{{ ($item->amount > 0 ? '+' : '') . format_number($item->amount) }}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="<?= url("/admin/cash-transaction/edit/$item->id") ?>" class="btn btn-default btn-sm"><i
                            class="fa fa-edit"></i></a>
                        <a onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"
                          href="<?= url("/admin/cash-transaction/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i
                            class="fa fa-trash"></i></a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr class="empty">
                    <td colspan="3">Tidak ada rekaman untuk ditampilkan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endSection
