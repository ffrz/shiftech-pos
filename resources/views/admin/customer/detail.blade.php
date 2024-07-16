@php
  $title = 'Rincian Pelanggan';
  use App\Models\StockUpdate;
  use App\Models\ServiceOrder;
@endphp

@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'sales',
    'nav_active' => 'customer',
])

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header" style="padding:0;border-bottom:0;">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1"
                role="tab"aria-controls="tab1">Info Pelanggan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab"
                aria-controls="tab2">Riwayat Transaksi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tab3-tab" data-toggle="tab" href="#tab3" role="tab"
                aria-controls="tab3">Riwayat Servis</a>
            </li>
          </ul>
        </div>
        <div class="tab-content card-body" id="myTabContent">
          <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
            <div class="row">
              <div class="col-lg-4">
                <table class="table info table-striped">
                  <tr>
                    <td style="width:5%;">Kode</td>
                    <td style="width:1%;">:</td>
                    <td>{{ $item->idFormatted() }}</td>
                  </tr>
                  <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $item->name }}</td>
                  </tr>
                  <tr>
                    <td>Telepon</td>
                    <td>:</td>
                    <td>{{ $item->phone }} @if($item->phone)<a href="{{ wa_me_url($item->phone) }}">(Buka WA)</a>@endif</td>
                  </tr>
                  <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $item->address }}</td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>{{ $item->active ? 'Aktif' : 'Non Aktif' }}</td>
                  </tr>
                  <tr>
                    <td class="text-nowrap">Jumlah Order</td>
                    <td>:</td>
                    <td>{{ format_number($item->sales_count) }} kali</td>
                  </tr>
                  <tr>
                    <td class="text-nowrap">Total Transaksi</td>
                    <td>:</td>
                    <td>Rp. {{ format_number($item->total_sales) }}</td>
                  </tr>
                  <tr>
                    <td class="text-nowrap">Total Keuntungan</td>
                    <td>:</td>
                    <td>Rp. {{ format_number($item->total_profit) }}</td>
                  </tr>
                  <tr>
                    <td class="text-nowrap">Total Piutang</td>
                    <td>:</td>
                    <td>Rp. {{ format_number($item->total_receivable) }}</td>
                  </tr>
                  <tr>
                    <td class="text-nowrap">Jumlah Order Servis</td>
                    <td>:</td>
                    <td>{{ format_number($item->service_count) }} kali</td>
                  </tr>
                  <tr>
                    <td>Catatan</td>
                    <td>:</td>
                    <td>{!! nl2br(e($item->notes)) !!}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th style="width:12%;">#</th>
                        <th style="width:12%;">Tanggal</th>
                        <th style="width:1%;">Status</th>
                        <th>Total</th>
                        <th>Piutang</th>
                        <th style="width:1%;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($sales as $item)
                        <tr
                          class="{{ $item->status == StockUpdate::STATUS_CANCELED ? 'table-danger' : ($item->status == StockUpdate::STATUS_OPEN ? 'table-warning' : '') }}">
                          <td>{{ $item->idFormatted() }}</td>
                          <td>{{ format_datetime($item->datetime) }}</td>
                          <td>{{ $item->statusFormatted() }}</td>
                          <td class="text-right">{{ format_number(abs($item->total)) }}</td>
                          <td class="text-right">{{ format_number($item->total_receivable) }}</td>
                          <td class="text-center">
                            <div class="btn-group">
                              @if ($item->status != StockUpdate::STATUS_OPEN)
                                <a href="{{ url("/admin/sales-order/detail/$item->id") }}"
                                  class="btn btn-default btn-sm"><i class="fa fa-eye" title="View"></i></a>
                              @endif
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
          </div>
          <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Tgl Masuk</th>
                        <th>Perangkat</th>
                        <th>Kendala & Tindakan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($services as $item)
                        <tr>
                          <td class="text-nowrap">{{ $item->id }}</td>
                          <td class="text-nowrap">{{ $item->date_received }}</td>
                          <td>
                            <b>{{ $item->device }}</b><br>+ {{ $item->equipments }}
                            <br><span class="badge badge-info">{{ $item->device_type }}</span>
                          </td>
                          <td>Kendala: {{ $item->problems }}<br>Tindakan: {{ $item->actions }}<br>Catatan:
                            <i>{!! nl2br(e($item->notes)) !!}</i>
                          </td>
                          <td class="text-center align-middle">
                            <span
                              class="badge badge-{{ $item->order_status == ServiceOrder::ORDER_STATUS_COMPLETED ? 'success' : ($item->order_status == ServiceOrder::ORDER_STATUS_CANCELED ? 'danger' : 'warning') }}">
                              <b>Order: {{ $item->formatOrderStatus($item->order_status) }}</b></span>
                            <span
                              class="badge badge-{{ $item->service_status == ServiceOrder::SERVICE_STATUS_SUCCESS ? 'success' : ($item->service_status == ServiceOrder::SERVICE_STATUS_FAILED ? 'danger' : 'info') }}">
                              <b>{{ $item->formatServiceStatus($item->service_status) }}</b>
                            </span>
                            <br>
                            <span
                              class="badge badge-{{ $item->payment_status == ServiceOrder::PAYMENT_STATUS_FULLY_PAID ? 'success' : 'warning' }}">
                              <b>{{ $item->formatPaymentStatus($item->payment_status) }}</b></span>
                            <span class="badge badge-{{ $item->date_picked ? 'success' : 'warning' }}">
                              <b>{{ $item->date_picked ? 'Sudah' : 'Belum' }} Diambil</b></span>
                          </td>
                          <td class="text-center align-middle">
                            <div class="btn-group">
                              <a class="btn btn-default btn-sm"
                                href="{{ url("/admin/service-order/detail/$item->id") }}"><i class="fa fa-eye"
                                  title="View"></i></a>
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
          </div>
        </div>
      </div>
    </div>
  </div>
@endSection
