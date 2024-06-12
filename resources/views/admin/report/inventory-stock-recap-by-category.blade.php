@php
  use App\Models\Setting;
@endphp

@extends('admin._layouts.print-report', [
    'title' => 'Laporan Stok Inventori',
])

@section('content')
  <h5 class="text-center">LAPORAN REKAP STOK PER KATEGORI</h5>
  <h5 class="text-center">{{ Setting::value('company.name') }}</h5>
  <h6 class="text-center">Per Tanggal: {{ date('d-m-Y') }}</h6>
  <table class="table info table-sm table-striped">
    <thead>
      <tr>
        <th>Kategori</th>
        <th>Modal (Rp)</th>
        <th>Harga (Rp)</th>
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
    <tfoot>
      <tr>
        <th>Total</th>
        <th class="text-right">{{ format_number($data['total_cost']) }}</th>
        <th class="text-right">{{ format_number($data['total_price']) }}</th>
      </tr>
    </tfoot>
  </table>
@endSection
