@php
  use App\Models\AclResource;
@endphp

@extends('admin._layouts.default', [
    'title' => 'Transaksi Keuangan',
    'menu_active' => 'finance',
    'nav_active' => 'cash-transaction',
])

@section('right-menu')
  <li class="nav-item">
    <a class="btn plus-btn btn-primary mr-2" href="{{ url('/admin/cash-transaction/edit/0') }}" title="Baru"><i
        class="fa fa-plus"></i></a>
  </li>
@endSection

@section('content')
  <div class="card card-light">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <form action="?" method="GET">
            @if ($actual_balance > 0)
              <div class="row">
                <div class="col">
                  <h3>Saldo Aktual: Rp. {{ format_number($actual_balance) }}</h3>
                </div>
              </div>
            @endif
            <div class="row mb-3">
              <div class="col-lg-1">
                <select class="custom-select select2" id="account_id" name="account_id" onchange="this.form.submit()">
                  <option value="" {{ !$filter['account_id'] ? 'selected' : '' }}>-- Pilih Akun --</option>
                  @foreach ($accounts as $account)
                    <option value="{{ $account->id }}" {{ $filter['account_id'] == $account->id ? 'selected' : '' }}>
                      {{ $account->idFormatted() }} - {{ $account->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-1">
                <input class="form-control" id="date" name="date" type="date" value="{{ $filter['date'] }}" onchange="this.form.submit()">
              </div>
            </div>
          </form>

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
                    <td class="text-right {{ $item->amount > 0 ? 'text-success' : 'text-danger' }}">
                      {{ ($item->amount > 0 ? '+' : '') . format_number($item->amount) }}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        @if (Auth::user()->canAccess(AclResource::EDIT_CASH_TRANSACTION))
                          <a class="btn btn-default btn-sm" href="{{ url("/admin/cash-transaction/edit/$item->id") }}"><i
                              class="fa fa-edit"></i></a>
                        @endif
                        @if (Auth::user()->canAccess(AclResource::DELETE_CASH_TRANSACTION))
                          <a class="btn btn-danger btn-sm" href="{{ url("/admin/cash-transaction/delete/$item->id") }}"
                            onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"><i
                              class="fa fa-trash"></i></a>
                        @endif
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr class="empty">
                    <td colspan="7">Tidak ada rekaman untuk ditampilkan.</td>
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
