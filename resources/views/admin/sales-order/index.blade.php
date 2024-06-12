@php
  use App\Models\StockUpdate;
@endphp

@extends('admin._layouts.default', [
    'title' => 'Order Penjualan',
    'menu_active' => 'sales',
    'nav_active' => 'sales-order',
])

@section('right-menu')
  <li class="nav-item">
    <a class="btn plus-btn btn-primary mr-2" href="{{ url('/admin/sales-order/create') }}" title="Baru"><i
        class="fa fa-plus"></i></a>
    <button class="btn btn-default plus-btn mr-2" data-toggle="modal" data-target="#filter-dialog" title="Saring"><i
        class="fa fa-filter"></i>
      @if ($filter_active)
        <span class="badge badge-warning">!</span>
      @endif
    </button>
  </li>
@endSection

@section('content')
  <form method="GET" onsubmit="$(this).hide())">
    <div class="modal fade" id="filter-dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Penyaringan</h4>
            <button class="close" data-dismiss="modal" type="button" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="status">Status Order:</label>
              <div class="col-sm-8">
                <select class="form-control" id="status" name="status">
                  <option value="-1" <?= $filter['status'] == -1 ? 'selected' : '' ?>>Semua Status</option>
                  <option value="{{ StockUpdate::STATUS_OPEN }}" {{ $filter['status'] == StockUpdate::STATUS_OPEN ? 'selected' : '' }}>
                    {{ StockUpdate::formatStatus(StockUpdate::STATUS_OPEN) }}</option>
                  <option value="{{ StockUpdate::STATUS_COMPLETED }}" {{ $filter['status'] == StockUpdate::STATUS_COMPLETED ? 'selected' : '' }}>
                    {{ StockUpdate::formatStatus(StockUpdate::STATUS_COMPLETED) }}</option>
                  <option value="{{ StockUpdate::STATUS_CANCELED }}" {{ $filter['status'] == StockUpdate::STATUS_CANCELED ? 'selected' : '' }}>
                    {{ StockUpdate::formatStatus(StockUpdate::STATUS_CANCELED) }}</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="datetime">Waktu:</label>
              <div class="col-sm-8">
                <select class="form-control" id="datetime" name="datetime">
                  <option value="all" <?= $filter['datetime'] == 'all' ? 'selected' : '' ?>>Semua</option>
                  <option value="today" <?= $filter['datetime'] == 'today' ? 'selected' : '' ?>>Hari ini</option>
                  <option value="yesterday" <?= $filter['datetime'] == 'yesterday' ? 'selected' : '' ?>>Kemarin</option>
                  <option value="this-week" <?= $filter['datetime'] == 'this-week' ? 'selected' : '' ?>>Minggu ini</option>
                  <option value="prev-week" <?= $filter['datetime'] == 'prev-week' ? 'selected' : '' ?>>Minggu kemarin</option>
                  <option value="this-month" <?= $filter['datetime'] == 'this-month' ? 'selected' : '' ?>>Bulan ini</option>
                  <option value="prev-month" <?= $filter['datetime'] == 'prev-month' ? 'selected' : '' ?>>Bulan kemarin</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button class="btn btn-primary" type="submit"><i class="fas fa-check mr-2"></i> Terapkan</button>
            <button class="btn btn-default" name="action" type="submit" value="reset"><i
                class="fa fa-filter-circle-xmark"></i> Reset Filter</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col d-flex justify-content-end">
            <div class="form-group form-inline">
              <label class="mr-2" for="search">Cari:</label>
              <input class="form-control" id="search" name="search" type="text" value="{{ $filter['search'] }}" autofocus placeholder="Cari pelanggan">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th style="width:12%;">#</th>
                    <th style="width:12%;">Tanggal</th>
                    <th style="width:1%;">Status</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Piutang</th>
                    <th style="width:1%;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($items as $item)
                    <tr
                      class="{{ $item->status == StockUpdate::STATUS_CANCELED ? 'table-danger' : ($item->status == StockUpdate::STATUS_OPEN ? 'table-warning' : '') }}">
                      <td>{{ $item->idFormatted() }}</td>
                      <td>{{ format_datetime($item->datetime) }}</td>
                      <td>{{ $item->statusFormatted() }}</td>
                      <td>{{ $item->party ? $item->party->idFormatted() . ' - ' . $item->party->name : '' }}</td>
                      <td class="text-right">{{ format_number(abs($item->total)) }}</td>
                      <td class="text-right">{{ format_number($item->total_receivable) }}</td>
                      <td class="text-center">
                        <div class="btn-group">
                          @if ($item->status != StockUpdate::STATUS_OPEN)
                            <a class="btn btn-default btn-sm" href="{{ url("/admin/sales-order/detail/$item->id") }}"><i
                                class="fa fa-eye" title="View"></i></a>
                          @else
                            <a class="btn btn-default btn-sm" href="{{ url("/admin/sales-order/edit/$item->id") }}"><i
                                class="fa fa-edit" title="Edit"></i></a>
                          @endif
                          <a class="btn btn-danger btn-sm" href="{{ url("/admin/stock-update/delete/$item->id?goto=" . url('admin/sales-order')) }}"
                            onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"><i class="fa fa-trash" title="Hapus"></i></a>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr class="empty">
                      <td colspan="7">Tidak ada rekaman untuk ditampilkan.
                      </td>
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
@endSection
