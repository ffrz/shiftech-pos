@extends('admin._layouts.default', [
    'title' => 'Laporan-laporan',
    'menu_active' => 'report',
    'nav_active' => 'report',
])

@section('content')
  <div class="card">
    <div class="card-body">
      <section>
        <h5>Laporan Penjualan</h5>
        <ul>
          <li><a href="#">Laporan Rincian Penjualan</a></li>
          <li><a href="#">Laporan Rekapitulasi Penjualan</a></li>
          <li><a href="{{ url('admin/report/net-income-statement') }}">Laporan Laba / Rugi</a></li>
        </ul>
      </section>
      <section>
        <h5>Laporan Servis</h5>
        <ul>
          <li><a href="{{ url('admin/report/service-orders') }}">Laporan Transaksi Servis</a></li>
          <li><a href="{{ url('admin/report/service-orders-completed') }}">Laporan Servis Selesai</a></li>
          <li><a href="{{ url('admin/report/service-orders-on-process') }}">Laporan Servis Dalam Proses</a></li>
        </ul>
      </section>
      <section>
        <h5>Laporan Inventori</h5>
        <ul>
          <li><a href="{{ url('admin/report/inventory-stock') }}">Laporan Stok Barang</a></li>
          <li><a href="{{ url('admin/report/inventory-minimum-stock') }}">Laporan Stok Barang Minimum</a></li>
          <li><a href="{{ url('admin/report/inventory-stock-detail-by-category') }}">Laporan Rincian Stok Barang Per Kategori</a></li>
          <li><a href="{{ url('admin/report/inventory-stock-recap-by-category') }}">Laporan Rekap Stok Barang Per Kategori</a></li>
        </ul>
      </section>
      <section>
        <h5>Laporan Pembelian</h5>
        <ul>
            <li><a href="#">Laporan Rincian Transaksi Pembelian</a></li>
            <li><a href="#">Laporan Rekapitulasi Transaksi Pembelian</a></li>
        </ul>
      </section>
      <section>
        <h5>Laporan Pengeluaran</h5>
        <ul>
            <li><a href="{{ url('admin/report/expense/monthly-expense-detail') }}">Laporan Rincian Pengeluaran</a></li>
            <li><a href="{{ url('admin/report/expense/monthly-expense-recap') }}">Laporan Rekapitulasi Pengeluaran</a></li>
        </ul>
      </section>
      <section>
        <h5>Laporan Keuangan</h5>
        <ul>
            <li><a href="#">Laporan Rincian Transaksi Keuangan</a></li>
            <li><a href="#">Laporan Rekapitulasi Transaksi Keuangan</a></li>
        </ul>
      </section>
    </div>
  </div>
@endsection
