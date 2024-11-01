@php
  use App\Models\Setting;
@endphp

@extends('admin._layouts.print-report', [
    'title' => 'Laporan Rincian Penjualan',
])

@section('content')
  <h5 class="text-center text-bold">LAPORAN RINCIAN PENJUALAN</h5>
  <h5 class="text-center text-bold">{{ Str::upper(Setting::value('company.name')) }}</h5>
  <h6 class="text-center">Periode: {{ $period[0] . ' - ' . $period[1] }}</h6>
  @forelse ($items as $num => $item)
    <div class="no-break">
      <p style="margin-top:10px;margin-bottom:0;">
        <b>{{ $num + 1 }} - {{ format_datetime($item->datetime) }} - #{{ $item->id2Formatted() }}</b>
        {{ $item->party_id ? ' - ' . $item->party_name . ($item->party_address ? ' - ' . $item->party_address : '') : '' }}
      </p>
      <table class="report-table dense">
        <tbody>
          <tr>
            <th style="width:5%">No</th>
            <th>Nama</th>
            <th style="width:5%">Qty</th>
            <th style="width:5%">Satuan</th>
            <th style="width:15%">Harga</th>
            <th style="width:15%">Subtotal</th>
          </tr>
          @php
            $total = 0;
          @endphp
          @foreach ($item->details as $j => $detail)
            <tr>
              <td class="text-right">{{ $j + 1 }} </td>
              <td>{{ $detail->product->code }}</td>
              <td class="text-right">{{ format_number(abs($detail->quantity)) }}</td>
              <td>{{ $detail->product->uom }}</td>
              <td class="text-right">{{ format_number($detail->price) }}</td>
              <td class="text-right">{{ format_number($subtotal = abs($detail->price * $detail->quantity)) }}</td>
            </tr>
            @php $total += $subtotal @endphp
          @endforeach
          <tr>
            <th class="text-right" colspan="5">Total</th>
            <th class="text-right">{{ format_number(abs($item->total)) }}</th>
          </tr>
        </tbody>
      </table>
      @if ($total != abs($item->total))
        <p style="color: red">WARNING: TOTAL MISSMATCH: ID: {{ $item->id }}</p>
      @endif
    </div>
  @empty
    <p class=" font-italic text-muted">
      Tidak ada data untuk ditampilkan.
    </p>
  @endforelse
  {{-- <table class="report-table">
    <thead style="background:#08e;color:#fff;">
      <th>No</th>
      <th>Kode Trx</th>
      <th>Waktu</th>
      <th>Pelanggan</th>
      <th>Total</th>
      <th>Modal</th>
      <th>Laba</th>
      <th>Alamat</th>
    </thead>
    <tbody>
      @php
        $sum_price = 0;
        $sum_cost = 0;
        $sum_profit = 0;
      @endphp
      @forelse ($items as $num => $item)
        <tr style="vertical-align:top;">
          <td class="text-right">{{ $num + 1 }}</td>
          <td>{{ $item->id2Formatted() }}</td>
          <td class="text-right">{{ $item->datetime }}</td>
          <td>{{ $item->party_name }}</td>
          <td class="text-right">{{ format_number(abs($item->total_price)) }}</td>
          <td class="text-right">{{ format_number(abs($item->total_cost)) }}</td>
          <td class="text-right">{{ format_number(abs($item->total_price) - abs($item->total_cost)) }}</td>
          <td>{{ $item->party_address }}</td>
        </tr>
        @php
          $sum_cost += abs($item->total_cost);
          $sum_price += abs($item->total_price);
          $sum_profit += abs($item->total_price) - abs($item->total_cost);
        @endphp
      @empty
        <tr>
          <td class="text-center font-italic text-muted" colspan="8">Tidak ada rekaman</td>
        </tr>
      @endforelse
      <tr style="background:#08e;color:#fff;">
        <th colspan="4">Total</th>
        <th class="text-right">{{ format_number($sum_price) }}</th>
        <th class="text-right">{{ format_number($sum_cost) }}</th>
        <th class="text-right">{{ format_number($sum_profit) }}</th>
        <th></th>
      </tr>
    </tbody>
  </table> --}}
@endSection
