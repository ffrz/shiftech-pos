@php
  use App\Models\ServiceOrder;
  use App\Models\Setting;

  $company_name = Setting::value('company.name');
  $title = 'Rincian Order Servis';
  $wa_notification =
      '*' . $company_name . ' - Pemberitahuan*'
      . "%0aPerangkat $item->device_type $item->device atas nama " . $item->customer_name . " telah kami terima dengan No bukti servis *{$item->idFormatted()}*"
      . " pada tanggal " . format_date($item->date_received, 'd MMMM yyyy')
      . ($item->estimated_cost ? ' dan biaya perkiraan Rp. ' . format_number($item->estimated_cost) : '') . '. ' .
      ' Anda dapat memeriksa status servis perangkat Anda dengan membuka tautan dibawah ini.' .
      '%0a' . url('track-service-order/' . encrypt_id($item->id)) .
      '%0aApabila link tidak dapat dibuka, anda dapat menyimpan dan menghubungi nomor ini.';

@endphp

@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'service-order',
    'nav_active' => 'service-order',
])

@section('content')
  <form class="form-horizontal quick-form" method="POST" action="{{ url('admin/service-order/action/' . (int) $item->id) }}">
    @csrf
    <input name="id" type="hidden" value="{{ $item->id }}">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <h4>Info Order</h4>
            <table class="table table-sm info" style="width:100%">
              <tr>
                <td style="width:30%"># Order</td>
                <td style="width:2%">:</td>
                <td>{{ $item->idFormatted() }}</td>
              </tr>
              <tr>
                <td>Status</td>
                <td>:</td>
                <td>{{ ServiceOrder::formatOrderStatus($item->order_status) }}</td>
              </tr>
              @if ($item->created_by)
                <tr>
                  <td>Dibuat</td>
                  <td>:</td>
                  <td>oleh <b>{{ $item->created_by->username }}</b> pada {{ format_datetime($item->created_datetime) }}</td>
                </tr>
              @endif
              @if ($item->closed_by)
                <tr>
                  <td>Ditutup</td>
                  <td>:</td>
                  <td>oleh <b>{{ $item->closed_by->username }}</b> pada {{ format_datetime($item->closed_datetime) }}</td>
                </tr>
              @endif
              <tr>
                <td>Tracking URL</td>
                <td>:</td>
                <td>
                  @if ($wa_url = wa_me_url($item->customer_phone, $wa_notification))
                    <a class="btn btn-xs btn-success" href="{!! $wa_url !!}" target="_blank"><i class="fas fa-paper-plane mr-1"></i> Kirim melalui WA</a>
                  @else
                    <span>Invalid phone number</span>
                  @endif
                </td>
              </tr>
            </table>
          </div>
          <div class="col-md-4">
            <h4>Info Pelanggan</h4>
            <table class="table table-sm info" style="width:100%">
              <tr>
                <td style="width:30%">Nama Pelanggan</td>
                <td style="width:2%">:</td>
                <td>{{ $item->customer_name }}</td>
              </tr>
              <tr>
                <td>Kontak</td>
                <td>:</td>
                <td>{{ $item->customer_phone }} @if($item->customer_phone)<a href="{{ wa_me_url($item->customer_phone) }}" target="_blank">(Buka WA)</a>@endif</td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $item->customer_address }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-4">
            <h4>Info Perangkat</h4>
            <table class="table table-sm info" style="width:100%">
              <tr>
                <td style="width:30%">Jenis</td>
                <td style="width:2%">:</td>
                <td>{{ $item->device_type }}</td>
              </tr>
              <tr>
                <td>Perangkat</td>
                <td>:</td>
                <td>{{ $item->device }}</td>
              </tr>
              <tr>
                <td>Perlengkapan</td>
                <td>:</td>
                <td>{{ $item->equipments }}</td>
              </tr>
              <tr>
                <td>SN</td>
                <td>:</td>
                <td>{{ $item->device_sn }}</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-4">
            <h4>Info Servis</h4>
            <table class="table table-sm info" style="width:100%">
              <tr>
                <td style="width:30%">Keluhan</td>
                <td style="width:2%">:</td>
                <td>{{ $item->problems }}</td>
              </tr>
              <tr>
                <td>Tindakan</td>
                <td>:</td>
                <td>{{ $item->actions }}</td>
              </tr>
              <tr>
                <td>Status Barang</td>
                <td>:</td>
                <td>{{ $item->date_picked ? 'Diambil' : ($item->date_received ? 'Diterima' : '-') }}</td>
              </tr>
              <tr>
                <td>Status Servis</td>
                <td>:</td>
                <td>{{ ServiceOrder::formatServiceStatus($item->service_status) }}</td>
              </tr>
              @if ($item->date_received)
                <tr>
                  <td>Tanggal Diterima</td>
                  <td>:</td>
                  <td>{{ format_date($item->date_received) }}</td>
                </tr>
              @endif
              @if ($item->date_checked)
                <tr>
                  <td>Tanggal Diperiksa</td>
                  <td>:</td>
                  <td>{{ format_date($item->date_checked) }}</td>
                </tr>
              @endif
              @if ($item->date_worked)
                <tr>
                  <td>Tanggal Dikerjakan</td>
                  <td>:</td>
                  <td>{{ format_date($item->date_worked) }}</td>
                </tr>
              @endif
              @if ($item->date_completed)
                <tr>
                  <td>Tanggal Selesai</td>
                  <td>:</td>
                  <td>{{ $item->date_completed ? format_date($item->date_completed) : '-' }}</td>
                </tr>
              @endif
              @if ($item->date_picked)
                <tr>
                  <td>Tanggal Diambil</td>
                  <td>:</td>
                  <td>{{ $item->date_picked ? format_date($item->date_picked) : '-' }}</td>
                </tr>
              @endif
              <tr>
                <td>Teknisi</td>
                <td>:</td>
                <td>{{ $item->technician }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-4">
            <h4>Info Biaya</h4>
            <table class="table table-sm info" style="width:100%">
              <tr>
                <td style="width:30%">Biaya Perkiraan</td>
                <td style="width:2%">:</td>
                <td>Rp. {{ format_number($item->estimated_cost) }}</td>
              </tr>
              <tr>
                <td>Uang Muka</td>
                <td>:</td>
                <td>Rp. {{ format_number($item->down_payment) }}</td>
              </tr>
              <tr>
                <td>Total Biaya</td>
                <td>:</td>
                <td>Rp. {{ format_number($item->total_cost) }}</td>
              </tr>
              <tr>
                <td>Status</td>
                <td>:</td>
                <td>{{ ServiceOrder::formatPaymentStatus($item->payment_status) }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-4">
            <h4>Catatan</h4>
            <p>{!! empty($item->notes) ? '- tidak ada catatan -' : nl2br(e($item->notes)) !!}</p>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-12">
            <h4>Perbarui Status</h4>
            <div class="form-horizontal row mb-2">
              <label class="form-label col-md-2">Status Barang: </label>
              <div class="btn-group">
                <button
                  class="btn btn-sm {{ !$item->date_received ? 'btn-default' : (!$item->date_picked ? 'btn-warning' : 'btn-default') }}" name="action" type="submit"
                  value="service_receive">
                  Diterima
                </button>
                <button class="btn btn-sm {{ !$item->date_picked ? 'btn-default' : 'btn-warning' }}" name="action" type="submit" value="taken">
                  Diambil
                </button>
              </div>
            </div>
            <div class="form-horizontal row mb-2">
              <label class="form-label col-md-2">Status Servis: </label>
              <div class="btn-group">
                <button
                  class="btn btn-sm {{ $item->service_status == ServiceOrder::SERVICE_STATUS_NOT_YET_CHECKED ? 'btn-warning' : 'btn-default' }}" name="action" type="submit"
                  value="service_receive">
                  Belum Diperiksa
                </button>
                <button
                  class="btn btn-sm {{ $item->service_status == ServiceOrder::SERVICE_STATUS_CHECKED ? 'btn-warning' : 'btn-default' }}" name="action" type="submit"
                  value="service_check">
                  Periksa
                </button>
                <button
                  class="btn btn-sm {{ $item->service_status == ServiceOrder::SERVICE_STATUS_WORKED ? 'btn-warning' : 'btn-default' }}" name="action" type="submit"
                  value="service_do">
                  Kerjakan
                </button>
                <button
                  class="btn btn-sm {{ $item->service_status == ServiceOrder::SERVICE_STATUS_SUCCESS ? 'btn-warning' : 'btn-default' }}" name="action" type="submit"
                  value="service_success">
                  Sukses
                </button>
                <button
                  class="btn btn-sm {{ $item->service_status == ServiceOrder::SERVICE_STATUS_FAILED ? 'btn-warning' : 'btn-default' }}" name="action" type="submit"
                  value="service_failed">
                  Gagal
                </button>
              </div>
            </div>
            <div class="form-horizontal row mb-2">
              <label class="form-label col-md-2">Status Pembayaran: </label>
              <div class="btn-group">
                <button
                  class="btn btn-sm {{ $item->payment_status == ServiceOrder::PAYMENT_STATUS_UNPAID ? 'btn-warning' : 'btn-default' }}" name="action" type="submit" value="unpaid">
                  Belum Dibayar
                </button>
                <button
                  class="btn btn-sm {{ $item->payment_status == ServiceOrder::PAYMENT_STATUS_PARTIALLY_PAID ? 'btn-warning' : 'btn-default' }}" name="action" type="submit"
                  value="partially_paid">
                  Dibayar Sebagian
                </button>
                <button
                  class="btn btn-sm {{ $item->payment_status == ServiceOrder::PAYMENT_STATUS_FULLY_PAID ? 'btn-warning' : 'btn-default' }}" name="action" type="submit"
                  value="fully_paid">
                  Lunas
                </button>
              </div>
            </div>
            <div class="form-horizontal row">
              <label class="form-label col-md-2">Status Order: </label>
              <div class="btn-group">
                <button
                  class="btn btn-sm {{ $item->order_status == ServiceOrder::ORDER_STATUS_ACTIVE ? 'btn-warning' : 'btn-default' }}" name="action" type="submit"
                  value="activate_order">
                  Aktif
                </button>
                <button
                  class="btn btn-sm {{ $item->order_status == ServiceOrder::ORDER_STATUS_COMPLETED ? 'btn-warning' : 'btn-default' }}" name="action" type="submit"
                  value="complete_order">
                  Selesai
                </button>
                <button
                  class="btn btn-sm {{ $item->order_status == ServiceOrder::ORDER_STATUS_CANCELED ? 'btn-warning' : 'btn-default' }}" name="action" type="submit"
                  value="cancel_order">
                  Dibatalkan
                </button>
              </div>
            </div>
          </div>
        </div>
      </div> {{-- .card-body --}}
      <div class="card-footer">
        @if (
            $item->order_status == ServiceOrder::ORDER_STATUS_ACTIVE &&
                ($item->service_status != ServiceOrder::SERVICE_STATUS_FAILED || $item->service_status != ServiceOrder::SERVICE_STATUS_SUCCESS) &&
                $item->payment_status != ServiceOrder::PAYMENT_STATUS_FULLY_PAID)
          <button class="btn btn-sm btn-primary mr-2" name="action" type="submit" value="complete_all"><i
              class="fas fa-check mr-1"></i> Sukses → Lunas → Selesai</button>
        @endif
        <div class="btn-group mt-1 mb-1">
          <a class="btn btn-sm btn-default" href="/admin/service-order/print/{{ $item->id }}"><i
              class="fas fa-print mr-1"></i>
            Cetak</a>
          <a class="btn btn-sm btn-default" href="/admin/service-order/edit/{{ $item->id }}"><i
              class="fas fa-edit mr-1"></i>
            Edit</a>
          <a class="btn btn-sm btn-danger" href="/admin/service-order/delete/{{ $item->id }}"
            onclick="return confirm('Anda yakin akan menghapus rekaman order servis ini?')"><i class="fas fa-edit mr-1"></i> Hapus</a>
        </div>
      </div>
    </div>
  </form>
@endSection
