@php
  use App\Models\ServiceOrder;
@endphp

@extends('admin._layouts.default', [
    'title' => 'Order Servis',
    // 'menu_active' => 'sales',
    'nav_active' => 'service-order',
])

@section('right-menu')
  <li class="nav-item">
    <a class="btn plus-btn btn-primary mr-2" href="{{ url('/admin/service-order/edit/0') }}" title="Baru"><i class="fa fa-plus"></i></a>
    <button class="btn btn-default plus-btn mr-2" data-toggle="modal" data-target="#filter-dialog" title="Saring"><i class="fa fa-filter"></i>
      @if ($filter_active)
        <span class="badge badge-warning">!</span>
      @endif
    </button>
  </li>
@endSection

@section('content')
  <form class="form-horizontal" method="GET" action="?">
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
              <label class="col-form-label col-sm-4" for="pickup_status">Pengambilan:</label>
              <div class="col-md-8">
                <select class="custom-select select2" id="pickup_status" name="pickup_status">
                  <option value="-1" {{ $filter['pickup_status'] == -1 ? 'selected' : '' }}>Semua Status</option>
                  <option value="0" {{ $filter['pickup_status'] == 0 ? 'selected' : '' }}>Belum Diambil</option>
                  <option value="1" {{ $filter['pickup_status'] == 1 ? 'selected' : '' }}>Sudah Diambil</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="order_status">Status Order:</label>
              <div class="col-md-8">
                <select class="custom-select select2" id="order_status" name="order_status">
                  <option value="-1" {{ $filter['order_status'] == -1 ? 'selected' : '' }}>Semua Status</option>
                  <option value="{{ ServiceOrder::ORDER_STATUS_ACTIVE }}" {{ $filter['order_status'] == ServiceOrder::ORDER_STATUS_ACTIVE ? 'selected' : '' }}>
                    {{ ServiceOrder::formatOrderStatus(ServiceOrder::ORDER_STATUS_ACTIVE) }}</option>
                  <option value="{{ ServiceOrder::ORDER_STATUS_COMPLETED }}" {{ $filter['order_status'] == ServiceOrder::ORDER_STATUS_COMPLETED ? 'selected' : '' }}>
                    {{ ServiceOrder::formatOrderStatus(ServiceOrder::ORDER_STATUS_COMPLETED) }}</option>
                  <option value="{{ ServiceOrder::ORDER_STATUS_CANCELED }}" {{ $filter['order_status'] == ServiceOrder::ORDER_STATUS_CANCELED ? 'selected' : '' }}>
                    {{ ServiceOrder::formatOrderStatus(ServiceOrder::ORDER_STATUS_CANCELED) }}</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="service_status">Status Servis:</label>
              <div class="col-sm-8">
                <select class="custom-select select2" id="service_status" name="service_status">
                  <option value="-1" {{ $filter['service_status'] == -1 ? 'selected' : '' }}>Semua Status</option>
                  <option value="{{ ServiceOrder::SERVICE_STATUS_NOT_YET_CHECKED }}"
                    {{ $filter['service_status'] == ServiceOrder::SERVICE_STATUS_NOT_YET_CHECKED ? 'selected' : '' }}>
                    {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_NOT_YET_CHECKED) }}</option>
                  <option value="{{ ServiceOrder::SERVICE_STATUS_CHECKED }}" {{ $filter['service_status'] == ServiceOrder::SERVICE_STATUS_CHECKED ? 'selected' : '' }}>
                    {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_CHECKED) }}</option>
                  <option value="{{ ServiceOrder::SERVICE_STATUS_WORKED }}" {{ $filter['service_status'] == ServiceOrder::SERVICE_STATUS_WORKED ? 'selected' : '' }}>
                    {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_WORKED) }}</option>
                  <option value="{{ ServiceOrder::SERVICE_STATUS_SUCCESS }}" {{ $filter['service_status'] == ServiceOrder::SERVICE_STATUS_SUCCESS ? 'selected' : '' }}>
                    {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_SUCCESS) }}</option>
                  <option value="{{ ServiceOrder::SERVICE_STATUS_FAILED }}" {{ $filter['service_status'] == ServiceOrder::SERVICE_STATUS_FAILED ? 'selected' : '' }}>
                    {{ ServiceOrder::formatServiceStatus(ServiceOrder::SERVICE_STATUS_FAILED) }}</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="payment_status">Status Pembayaran:</label>
              <div class="col-md-8">
                <select class="custom-select select2" id="payment_status" name="payment_status">
                  <option value="-1" {{ $filter['payment_status'] == -1 ? 'selected' : '' }}>Semua Status</option>
                  <option value="{{ ServiceOrder::PAYMENT_STATUS_UNPAID }}" {{ $filter['payment_status'] == ServiceOrder::PAYMENT_STATUS_UNPAID ? 'selected' : '' }}>
                    {{ ServiceOrder::formatPaymentStatus(ServiceOrder::PAYMENT_STATUS_UNPAID) }}</option>
                  <option value="{{ ServiceOrder::PAYMENT_STATUS_PARTIALLY_PAID }}"
                    {{ $filter['payment_status'] == ServiceOrder::PAYMENT_STATUS_PARTIALLY_PAID ? 'selected' : '' }}>
                    {{ ServiceOrder::formatPaymentStatus(ServiceOrder::PAYMENT_STATUS_PARTIALLY_PAID) }}</option>
                  <option value="{{ ServiceOrder::PAYMENT_STATUS_FULLY_PAID }}" {{ $filter['payment_status'] == ServiceOrder::PAYMENT_STATUS_FULLY_PAID ? 'selected' : '' }}>
                    {{ ServiceOrder::formatPaymentStatus(ServiceOrder::PAYMENT_STATUS_FULLY_PAID) }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button class="btn btn-primary" type="submit"><i class="fas fa-check mr-2"></i> Terapkan</button>
            <button class="btn btn-default" name="action" type="submit" value="reset"><i class="fa fa-filter-circle-xmark"></i> Reset Filter</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-light">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
          </div>
          <div class="col-md-6 d-flex justify-content-end">
            <div class="form-group form-inline">
              <label class="mr-2" for="search">Cari:</label>
              <input class="form-control" id="search" name="search" type="text" value="{{ $filter['search'] }}" placeholder="Cari">
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
                    <th>Tgl Masuk</th>
                    <th>Pemilik</th>
                    <th>Perangkat</th>
                    <th>Kendala & Tindakan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($items as $item)
                    <tr>
                      <td class="text-nowrap">{{ $item->id }}</td>
                      <td class="text-nowrap">{{ $item->date_received }}</td>
                      <td><b>{{ $item->customer_name }}</b><br>{{ $item->customer_phone }}<br>{{ $item->customer_address }}</td>
                      <td>
                        <b>{{ $item->device }}</b><br>+ {{ $item->equipments }}
                        <br><span class="badge badge-info">{{ $item->device_type }}</span>
                      </td>
                      <td>Kendala: {{ $item->problems }}<br>Tindakan: {{ $item->actions }}<br>Catatan: <i>{!! nl2br(e($item->notes)) !!}</i></td>
                      <td class="text-center align-middle">
                        <span
                          class="badge badge-{{ $item->order_status == ServiceOrder::ORDER_STATUS_COMPLETED ? 'success' : ($item->order_status == ServiceOrder::ORDER_STATUS_CANCELED ? 'danger' : 'warning') }}">
                          <b>Order: {{ $item->formatOrderStatus($item->order_status) }}</b></span>
                        <span
                          class="badge badge-{{ $item->service_status == ServiceOrder::SERVICE_STATUS_SUCCESS ? 'success' : ($item->service_status == ServiceOrder::SERVICE_STATUS_FAILED ? 'danger' : 'info') }}">
                          <b>{{ $item->formatServiceStatus($item->service_status) }}</b>
                        </span>
                        <br>
                        <span class="badge badge-{{ $item->payment_status == ServiceOrder::PAYMENT_STATUS_FULLY_PAID ? 'success' : 'warning' }}">
                          <b>{{ $item->formatPaymentStatus($item->payment_status) }}</b></span>
                        <span class="badge badge-{{ $item->date_picked ? 'success' : 'warning' }}">
                          <b>{{ $item->date_picked ? 'Sudah' : 'Belum' }} Diambil</b></span>
                      </td>
                      <td class="text-center align-middle">
                        <div class="btn-group">
                          @if (empty($item->deleted_at))
                            <a class="btn btn-default btn-sm" href="{{ url("/admin/service-order/detail/$item->id") }}"><i class="fa fa-eye" title="View"></i></a>
                            <a class="btn btn-default btn-sm" href="{{ url("/admin/service-order/edit/$item->id") }}"><i class="fa fa-edit" title="Edit"></i></a>
                            <a class="btn btn-default btn-sm" href="{{ url("/admin/service-order/duplicate/$item->id") }}"><i class="fa fa-copy" title="Duplikat"></i></a>
                            <a class="btn btn-danger btn-sm" href="{{ url("/admin/service-order/delete/$item->id") }}"
                              onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"><i class="fa fa-trash" title="Hapus"></i></a>
                          @else
                            <a href="{{ url("/admin/service-order/restore/$item->id") }} onclick="return confirm('Anda yakin akan mengembalikan rekaman ini?')"
                              class="btn btn-default btn-sm"><i class="fa fa-trash-arrow-up" title="Pulihkan"></i></a>
                          @endif
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr class="empty">
                      <td colspan="10">Tidak ada rekaman yang dapat ditampilkan.</td>
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
