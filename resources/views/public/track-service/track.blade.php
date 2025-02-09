@php
  use App\Models\Setting;
  use App\Models\ServiceOrder;
  $title = 'Kartu Servis: #' . $serviceOrder->idFormatted();
  $item = $serviceOrder;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Shift Computer - Service Order Tracking System</title>
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">
  <link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('dist/css/adminlte.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
  @vite([])
</head>

<body style="margin: 10px;">
  <div class="row justify-content-center mt-5">
    <div class="col col-lg-8">
      <h4>{{ Setting::value('company.name') }}</h4>
      <h5>Service Order Tracking System</h5>
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
                  <td>{{ $item->customer_phone }}</td>
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
            <div class="col-sm-12">
              <h4>Info Status Servis</h4>
              <ul class="timeline">
                @if ($item->date_received)
                  <li>
                    <div class="timeline-title">{{ format_date($item->date_received, 'l, j F Y') }}</div>
                    <p>Barang diterima</p>
                  </li>
                @endif
                @if ($item->date_checked)
                  <li>
                    <div class="timeline-title">{{ format_date($item->date_received, 'l, j F Y') }}</div>
                    <p>Barang sedang diperiksa</p>
                  </li>
                @endif
                @if ($item->date_worked)
                  <li>
                    <div class="timeline-title">{{ format_date($item->date_worked, 'l, j F Y') }}</div>
                    <p>Servis sedang dikerjakan</p>
                  </li>
                @endif
                @if ($item->date_completed)
                  <li>
                    <div class="timeline-title">{{ format_date($item->date_picked, 'l, j F Y') }}</div>
                    <div>Servis Selesai</div>
                    @if ($item->service_status == ServiceOrder::SERVICE_STATUS_SUCCESS || $item->service_status == ServiceOrder::SERVICE_STATUS_FAILED)
                      <div>
                        <span class="btn btn-sm {{ ServiceOrder::SERVICE_STATUS_SUCCESS ? 'btn-success' : (ServiceOrder::SERVICE_STATUS_FAILED ? 'btn-danger' : '') }}">
                          {{ $item->service_status == ServiceOrder::SERVICE_STATUS_SUCCESS ? 'Sukses' : '' }}
                        </span>
                      </div>
                    @endif
                  </li>
                @endif
                @if ($item->date_picked)
                  <li>
                    <div class="timeline-title">{{ format_date($item->date_picked, 'l, j F Y') }}</div>
                    <p>Barang telah diambil</p>
                  </li>
                @endif
              </ul>
            </div>
          </div>
        </div>
      </div> {{-- .card-body --}}
    </div>
  </div>
  </div>
</body>

</html>
