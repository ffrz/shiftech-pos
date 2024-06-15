@php
  use App\Models\StockUpdate;
  use App\Models\Party;
  $title = $item->idFormatted();
@endphp

@extends('admin/_layouts/default', [
    'title' => $title,
    'nav_active' => 'product',
    'menu_active' => 'inventory',
])

@section('right-menu')
  <li class="nav-item">
    <a class="btn btn-sm btn-default" href="{{ url('admin/product/edit/' . $item->id) }}"><i class="fa fa-edit mr-2"></i>Edit</a>
    <a class="btn btn-sm btn-default" href="{{ url('admin/product/duplicate/' . $item->id) }}"><i class="fa fa-copy mr-2"></i>Duplikat</a>
  </li>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header" style="padding:0;border-bottom:0;">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab"aria-controls="tab1">Info Produk</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2">Riwayat Stok</a>
            </li>
          </ul>
        </div>
        <div class="tab-content card-body" id="myTabContent">
          <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
            <section>
              <h5>Info Produk</h5>
              <table class="table info table-striped">
                <tr>
                  <td class="text-nowrap" style="width:5%">Kode Produk</td>
                  <td style="width:1%">:</td>
                  <td>{{ $item->idFormatted() }}</td>
                </tr>
                <tr>
                  <td class="text-nowrap">Nama Produk</td>
                  <td>:</td>
                  <td>{{ $item->code }}</td>
                </tr>
                <tr>
                  <td>Deskripsi</td>
                  <td>:</td>
                  <td>{{ $item->description }}</td>
                </tr>
                <tr>
                  <td>Jenis</td>
                  <td>:</td>
                  <td>{{ $item->typeFormatted() }}</td>
                </tr>
                <tr>
                  <td>Kategori</td>
                  <td>:</td>
                  <td>{{ $item->category ? $item->category->name : '' }}</td>
                </tr>
                <tr>
                  <td>Barcode</td>
                  <td>:</td>
                  <td>{{ $item->barcode }}</td>
                </tr>
                <tr>
                  <td>Status</td>
                  <td>:</td>
                  <td>{{ $item->active ? 'Aktif' : 'Non Aktif' }}</td>
                </tr>
              </table>
            </section>
            <section>
              <h5>Info Inventori</h5>
              <table class="table info table-striped">
                @if ($item->type == 0)
                  <tr>
                    <td class="text-nowrap" style="width:5%">Supplier Tetap</td>
                    <td style="width:1%">:</td>
                    <td><a href="{{ $item->supplier ? url('admin/supplier/detail/' . $item->supplier->id) : '#' }}">{{ $item->supplier ? $item->supplier->name : '' }}</a></td>
                  </tr>
                @endif
                <tr>
                  <td style="width:5%">Satuan</td>
                  <td style="width:1%">:</td>
                  <td>{{ $item->uom }}</td>
                </tr>
                @if ($item->type == 0)
                  <tr>
                    <td>Stok</td>
                    <td>:</td>
                    <td>{{ format_number($item->stock) . ' ' . $item->uom }}</td>
                  </tr>
                  <tr>
                    <td class="text-nowrap">Stok Minimum</td>
                    <td>:</td>
                    <td>{{ format_number($item->minimum_stock) . ' ' . $item->uom }}</td>
                  </tr>
                @endif
              </table>
            </section>
            <section>
              <h5>Info Harga</h5>
              <table class="table info table-striped">
                <tr>
                  <td class="text-nowrap" style="width:5%">Harga Beli</td>
                  <td style="width:1%">:</td>
                  <td>Rp. {{ format_number($item->cost) }}</td>
                </tr>
                <tr>
                  <td class="text-nowrap">Harga Jual</td>
                  <td>:</td>
                  <td>Rp. {{ format_number($item->price) }}</td>
                </tr>
                <tr>
                  <td class="text-nowrap">Laba</td>
                  <td>:</td>
                  <td>Rp. {{ format_number($item->price - $item->cost) }} ({{ format_number((($item->price - $item->cost) / $item->price) * 100, 2) }}%)</td>
                </tr>
              </table>
            </section>
            <section>
              <h5>Catatan</h5>
              <div>
                @if (empty($item->notes))
                  <span class="text-muted font-italic">Tidak ada catatan</span>
                @else
                  {{ nl2br($item->notes) }}
                @endif
              </div>
            </section>
          </div>
          <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
            <table class="table table-sm table-striped">
              <thead>
                <th>#Trx</th>
                <th>Jenis</th>
                <th>Pihak</th>
                <th>Qty</th>
                <th>Modal</th>
                <th>Harga</th>
                <th>Jml Modal</th>
                <th>Jml Harga</th>
                <th>Selisih</th>
              </thead>
              <tbody>
                @forelse ($stock_update_details as $detail)
                  <tr>
                    <td class="text-nowrap"><a
                        href="{{ url('admin/stock-update/detail/' . $detail->update_id) }}">{{ StockUpdate::formatId2($detail->update_id2, $detail->update_type, $detail->update_created_datetime) }}</a>
                    </td>
                    <td>{{ StockUpdate::formatType($detail->update_type) }}</td>
                    <td><a
                        href="{{ $detail->party_id ? url('admin/' . ($detail->party_type == Party::TYPE_CUSTOMER ? 'customer' : 'supplier') . '/detail/' . $detail->party_id) : '#' }}">{{ $detail->party_name }}</a>
                    </td>
                    <td class="text-right">{{ format_number($detail->quantity) }}</td>
                    <td class="text-right">{{ format_number($detail->cost) }}</td>
                    <td class="text-right">{{ format_number($detail->price) }}</td>
                    <td class="text-right">{{ format_number($subtotal_cost = $detail->quantity * $detail->cost) }}</td>
                    <td class="text-right">{{ format_number($subtotal_price = $detail->quantity * $detail->price) }}</td>
                    <td class="text-right">{{ format_number($subtotal_price - $subtotal_cost) }}</td>
                  </tr>
                @empty
                  <tr>
                    <td></td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
