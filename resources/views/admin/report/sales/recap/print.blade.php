@php
  use App\Models\Setting;
@endphp

@extends('admin._layouts.print-report', [
    'title' => 'Laporan Rekapitulasi Penjualan',
])

@section('content')
  <style>
    .no-wrap {
      white-space: nowrap;
    }

    .date-group {
      background: #eee;
    }

    thead th,
    tfoot th {
      background: rgb(0, 108, 248);
      color: #fff;
    }
  </style>
  <h5 class="text-bold text-center">LAPORAN REKAPITULASI PENJUALAN</h5>
  <h5 class="text-bold text-center">{{ Str::upper(Setting::value('company.name')) }}</h5>
  <h6 class="text-center">Periode: {{ $period[0] . ' - ' . $period[1] }}</h6>
  <div class="no-break">
    @php
      $total = 0;
    @endphp
    <table class="report-table dense">
      <thead>
        <tr>
          <th style="width:1%">No</th>
          <th style="width:10%">ID Order</th>
          <th style="width:10%">Tanggal</th>
          <th>Pelanggan</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($items as $date => $subitems)
          <tr>
            <td colspan="5" class="text-bold date-group text-left">{{ $date }}</td>
          </tr>
          @foreach ($subitems as $num => $item)
            <tr>
              <td class="text-right">{{ $num+1 }} </td>
              <td class="no-wrap">{{ $item->id2Formatted() }}</td>
              <td class="no-wrap">{{ format_date($item->datetime) }}</td>
              <td>{{ $item->party_name }}</td>
              <td class="text-right">{{ format_number(abs($item->total)) }}</td>
            </tr>
            @php $total += abs($item->total) @endphp
          @endforeach
        @empty
          <tr>
            <td colspan="5" class="font-italic text-muted text-center">
              Tidak ada data untuk ditampilkan.
            </td>
          </tr>
        @endforelse
      </tbody>
      <tfoot>
        <tr>
          <th colspan="4" class="text-right">Grand Total (Omzet)</th>
          <th class="text-right">{{ format_number($total) }}</th>
        </tr>
      </tfoot>
    </table>
  </div>

@endSection
