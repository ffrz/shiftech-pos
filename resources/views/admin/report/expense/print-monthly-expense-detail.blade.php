@php
  use App\Models\Setting;
@endphp

@extends('admin._layouts.print-report', [
    'title' => 'Laporan Rincian Pengeluaran Bulanan',
])

@section('content')
  <h5 class="text-center">LAPORAN RINCIAN PENGELUARAN BULANAN</h5>
  <h5 class="text-center">{{ Str::upper(Setting::value('company.name')) }}</h5>
  <h6 class="text-center">Periode: {{ $period[0] . ' - ' . $period[1] }}</h6>
  <table class="report-table">
    <thead style="background:#08e;color:#fff;">
      <th>No</th>
      <th>Tanggal</th>
      <th>Kategori</th>
      <th>Deskripsi</th>
      <th>Jumlah</th>
      <th>Keterangan</th>
    </thead>
    <tbody>
      @php $sum_cost = 0 @endphp
      @forelse ($items as $num => $item)
        <tr style="vertical-align:top;">
          <td class="text-right">{{ $num + 1 }}</td>
          <td class="text-right">{{ $item->date }}</td>
          <td>{{ $item->category ? $item->category->name : '-' }}</td>
          <td style="white-space:nowrap">{{ $item->description }}</td>
          <td class="text-right">{{ format_number($item->amount) }}</td>
          <td style="white-space:nowrap">{{ $item->notes }}</td>
        </tr>
        @php $sum_cost += $item->amount @endphp
      @empty
        <tr>
          <td colspan="6" class="text-center font-italic text-muted">Tidak ada rekaman</td>
        </tr>
      @endforelse
    </tbody>
    <tfoot style="background:#08e;color:#fff;">
      <th colspan="4">Total</th>
      <th class="text-right">{{ format_number($sum_cost) }}</th>
      <th></th>
    </tfoot>
  </table>
@endSection
