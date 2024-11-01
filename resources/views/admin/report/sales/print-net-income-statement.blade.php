@php
  use App\Models\Setting;
@endphp

@extends('admin._layouts.print-report', [
    'title' => 'Laporan Laba Rugi',
])

@section('content')
  <h5 class="text-center">LAPORAN LABA RUGI</h5>
  <h5 class="text-center">{{ Str::upper(Setting::value('company.name')) }}</h5>
  <h6 class="text-center">Periode: {{ $period[0] . ' - ' . $period[1] }}</h6>
  <table style="margin: 50px auto 0;">
    <tbody>
      <tr>
        <td style="width:200px">Omset Penjualan</td>
        <td>Rp.</td>
        <td class="text-right" style="width:100px">{{ format_number($total_sales) }}</td>
      </tr>
      <tr>
        <td>HPP Penjualan</td>
        <td>Rp.</td>
        <td class="text-right">{{ format_number($total_cost) }}</td>
      </tr>
      <tr>
        <td colspan="3">
          <hr>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <th>Laba / Rugi Kotor</th>
      <th>Rp.</th>
      <th class="text-right">{{ format_number($total_sales - $total_cost) }}</th>
    </tfoot>
  </table>
@endSection
