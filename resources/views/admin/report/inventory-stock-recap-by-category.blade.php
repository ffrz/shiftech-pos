@php
  use App\Models\Setting;
@endphp

@extends('admin._layouts.print-report', [
    'title' => 'Laporan Stok Inventori',
])

@section('content')
  <h5 class="text-center">LAPORAN REKAPITULASI STOK PER KATEGORI</h5>
  <h5 class="text-center">{{ Setting::value('company.name') }}</h5>
  <h6 class="text-center">Per Tanggal: {{ date('d-m-Y') }}</h6>
  <table class="report-table">
    <thead style="background:#08e;color:#fff;">
      <tr class="bg-primary">
        <th>KATEGORI</th>
        <th>MODAL / HARGA BELI (Rp)</th>
        <th>HARGA JUAL (Rp)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data['categories'] as $category)
        <tr>
          <td>{{ $category['name'] }}</td>
          <td class="text-right">{{ format_number($category['total_cost']) }}</td>
          <td class="text-right">{{ format_number($category['total_price']) }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot style="background:#08e;color:#fff;">
      <tr>
        <th class="text-right">TOTAL</th>
        <th class="text-right">{{ format_number($data['total_cost']) }}</th>
        <th class="text-right">{{ format_number($data['total_price']) }}</th>
      </tr>
    </tfoot>
  </table>
@endSection
