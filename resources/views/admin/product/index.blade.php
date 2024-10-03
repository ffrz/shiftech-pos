@php
  use App\Models\Product;
  use App\Models\AclResource;
@endphp

@extends('admin._layouts.default', [
    'title' => 'Produk',
    'menu_active' => 'inventory',
    'nav_active' => 'product',
])

@section('right-menu')
  <li class="nav-item">
    <a class="btn plus-btn btn-primary mr-2" href="{{ url('/admin/product/edit/0') }}" title="Baru"><i
        class="fa fa-plus"></i></a>
    <button class="btn btn-default plus-btn mr-2" data-toggle="modal" data-target="#filter-dialog" title="Saring"><i
        class="fa fa-filter"></i>
      @if ($filter_active)
        <span class="badge badge-warning">!</span>
      @endif
    </button>
  </li>
@endSection

@section('content')
  <form method="GET">
    <div class="modal fade" id="filter-dialog">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Penyaringan</h4>
            <button class="close" data-dismiss="modal" type="button" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="type">Jenis Produk:</label>
              <div class="col-sm-8">
                <select class="custom-select select2" id="type" name="type">
                  <option value="-1" <?= $filter['type'] == -1 ? 'selected' : '' ?>>Semua</option>
                  <option value="{{ Product::NON_STOCKED }}"
                    {{ $filter['type'] == Product::NON_STOCKED ? 'selected' : '' }}>
                    {{ Product::formatType(Product::NON_STOCKED) }}</option>
                  <option value="{{ Product::STOCKED }}" {{ $filter['type'] == Product::STOCKED ? 'selected' : '' }}>
                    {{ Product::formatType(Product::STOCKED) }}</option>
                  <option value="{{ Product::SERVICE }}" {{ $filter['type'] == Product::SERVICE ? 'selected' : '' }}>
                    {{ Product::formatType(Product::SERVICE) }}</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="active">Akitf / Nonaktif:</label>
              <div class="col-sm-8">
                <select class="custom-select select2" id="active" name="active">
                  <option value="-1" {{ $filter['active'] == -1 ? 'selected' : '' }}>Semua</option>
                  <option value="0" {{ $filter['active'] == 0 ? 'selected' : '' }}>Non Aktif</option>
                  <option value="1" {{ $filter['active'] == 1 ? 'selected' : '' }}>Aktif</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="category_id">Kategori:</label>
              <div class="col-sm-8">
                <select class="custom-select select2" id="category_id" name="category_id">
                  <option value="-1" {{ $filter['category_id'] == -1 ? 'selected' : '' }}>Semua</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $filter['category_id'] == $category->id ? 'selected' : '' }}>
                      {{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="supplier_id">Supplier:</label>
              <div class="col-sm-8">
                <select class="custom-select select2" id="supplier_id" name="supplier_id">
                  <option value="-1" {{ $filter['supplier_id'] == -1 ? 'selected' : '' }}>Semua</option>
                  @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $filter['supplier_id'] == $supplier->id ? 'selected' : '' }}>
                      {{ $supplier->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-4" for="stock_status">Status Stok:</label>
              <div class="col-sm-8">
                <select class="custom-select select2" id="stock_status" name="stock_status">
                  <option value="-1" {{ $filter['stock_status'] == -1 ? 'selected' : '' }}>Semua</option>
                  <option value="0" {{ $filter['stock_status'] == 0 ? 'selected' : '' }}>Kosong</option>
                  <option value="1" {{ $filter['stock_status'] == 1 ? 'selected' : '' }}>Minimum</option>
                  <option value="2" {{ $filter['stock_status'] == 2 ? 'selected' : '' }}>Tersedia</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button class="btn btn-primary" type="submit"><i class="fas fa-check mr-2"></i> Terapkan</button>
            <button class="btn btn-default" name="action" type="submit" value="reset"><i
                class="fa fa-filter-circle-xmark"></i> Reset Filter</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-light">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            @if (Auth::user()->canAccess(AclResource::EDIT_PRODUCT))
              <div class="form-group form-inline">
                <div class="custom-control custom-checkbox">
                  <input class="custom-control-input" type="checkbox" id="showCost" value="true">
                  <label for="showCost" class="custom-control-label">Tampilkan Modal</label>
                </div>
              </div>
            @endif
          </div>
          <div class="col-md-6 d-flex justify-content-end">
            <div class="form-group form-inline">
              <label class="mr-2" for="search">Cari:</label>
              <input class="form-control" id="search" name="search" type="text"
                value="{{ $filter['search'] }}" placeholder="Cari produk">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table-bordered table-striped table-sm table">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    @if (Auth::user()->canAccess(AclResource::EDIT_PRODUCT))
                      <th class="cost">Harga Beli</th>
                    @endif
                    <th>Harga Jual</th>
                    @if (Auth::user()->canAccess(AclResource::EDIT_PRODUCT))
                      <th style="width:5%">Aksi</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @forelse ($items as $item)
                    @php $is_at_low_stock = $item->stock < $item->minimum_stock @endphp
                    <tr class="{{ $filter['active'] == -1 && !$item->active ? 'table-danger' : '' }}">
                      <td>{{ $item->idFormatted() }}</td>
                      <td>{{ $item->code }}</td>
                      <td>{!! $item->category ? e($item->category->name) : '<i>Tanpa Kategori</i>' !!}</td>
                      <td
                        class="{{ $item->type == Product::STOCKED && $is_at_low_stock ? 'text-danger' : '' }} text-right">
                        {{ $item->type == Product::STOCKED ? format_number($item->stock) : '-' }}
                      </td>
                      <td>{{ $item->uom }}</td>
                      @if (Auth::user()->canAccess(AclResource::EDIT_PRODUCT))
                        <td class="cost text-right">{{ format_number($item->cost) }}</td>
                      @endif
                      <td class="text-right">{{ format_number($item->price) }}</td>
                      @if (Auth::user()->canAccess(AclResource::EDIT_PRODUCT))
                        <td class="text-center">
                          <div class="btn-group">
                            <a class="btn btn-default btn-sm" href="{{ url("/admin/product/detail/$item->id") }}"><i
                                class="fa fa-eye" title="Rincian"></i></a>
                            <a class="btn btn-default btn-sm" href="{{ url("/admin/product/duplicate/$item->id") }}"><i
                                class="fa fa-copy" title="Duplikat"></i></a>
                            <a class="btn btn-default btn-sm" href="{{ url("/admin/product/edit/$item->id") }}"><i
                                class="fa fa-edit"></i></a>
                            <a class="btn btn-danger btn-sm" href="{{ url("/admin/product/delete/$item->id") }}"
                              onclick="return confirm('Anda yakin akan menghapus rekaman ini?')"><i
                                class="fa fa-trash"></i></a>
                          </div>
                        </td>
                      @endif
                    </tr>
                  @empty
                    <tr class="empty">
                      <td colspan="{{ Auth::user()->canAccess(AclResource::EDIT_PRODUCT) ? 8 : 6 }}">Tidak ada rekaman
                        yang dapat
                        ditampilkan.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            @include('admin._components.paginator', ['items' => $items])
          </div>
        </div>
      </div>
    </div>
  </form>
@endSection
@section('footscript')
  <script>
    $(document).ready(function() {
      function onShowCostCheckboxToggled() {
        if ($('#showCost').prop('checked')) {
          $('.cost').show()
        } else {
          $('.cost').hide()
        }
      }

      $('#showCost').change(onShowCostCheckboxToggled)
      onShowCostCheckboxToggled()
    })
  </script>
@endSection
