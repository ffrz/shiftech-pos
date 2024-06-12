@php
  use App\Models\Product;
  use App\Models\Setting;
  $title = $item->id ? 'Edit ' . $item->idFormatted() : 'Tambah Produk';
@endphp

@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'inventory',
    'nav_active' => 'product',
    'form_action' => url('admin/product/edit/' . (int) $item->id),
])

@section('right-menu')
  <li class="nav-item">
    <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-save mr-1"></i> Simpan</button>
    <a onclick="return confirm('Batalkan perubahan?')" class="btn btn-default" href="{{ url('/admin/product/') }}"><i
        class="fas fa-cancel mr-1"></i>Batal</a>
  </li>
@endSection

@section('content')
  <div class="row">
    <div class="col-lg-5">
      <div class="card">
        <div class="card-body">
          <h4 class="mb-1">Info Produk</h4>
          <hr class="mb-3 mt-0">
          <div class="form-group">
            <label for="id">Kode Produk</label>
            <input type="text" class="form-control" id="id" readonly
              value="{{ $item->id ? $item->idFormatted() : '-otomatis-' }}">
            <p class="text-muted mt-2 font-italic">Kode produk diisi otomatis oleh sistem dan tidak bisa diubah.</p>
          </div>
          <div class="form-group">
            <label for="type">Jenis Produk</label>
            <select class="custom-select form-control" id="type" name="type">
              <option value="{{ Product::NON_STOCKED }}" <?= $item->type == Product::NON_STOCKED ? 'selected' : '' ?>>
                Barang Non Stok</option>
              <option value="{{ Product::STOCKED }}" <?= $item->type == Product::STOCKED ? 'selected' : '' ?>>Barang Stok
              </option>
              <option value="{{ Product::SERVICE }}" <?= $item->type == Product::SERVICE ? 'selected' : '' ?>>Servis
              </option>
            </select>
          </div>
          <div class="form-group">
            <label for="code">Nama Produk</label>
            <input type="text" class="form-control @error('code') is-invalid @enderror" autofocus id="code"
              placeholder="Masukkan nama produk" name="code" value="{{ old('code', $item->code) }}">
            @error('code')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          {{-- buat setting show hide deskripsi --}}
          @if (Setting::value('inv.show_description'))
            <div class="form-group">
              <label for="description">Deskripsi</label>
              <input type="text" class="form-control @error('description') is-invalid @enderror" id="description"
                placeholder="Masukkan deskripsi produk" name="description"
                value="{{ old('description', $item->description) }}">
              @error('description')
                <span class="text-danger">
                  {{ $message }}
                </span>
              @enderror
            </div>
          @endif
          {{-- buat setting show hide barcode --}}
          @if (Setting::value('inv.show_barcode'))
            <div class="form-group">
              <label for="barcode">Barcode</label>
              <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode"
                placeholder="Masukkan barcode produk" name="barcode" value="{{ old('barcode', $item->barcode) }}">
              @error('barcode')
                <span class="text-danger">
                  {{ $message }}
                </span>
              @enderror
            </div>
          @endif
          <div class="form-group">
            <label for="category_id">Kategori <button type="button" class="btn btn-sm btn-default plus-btn"
                data-toggle="modal" data-target="#category-dialog" title="Tambah"><i class="fa fa-plus"></i>
              </button></label>
            <select class="custom-select select2" id="category_id" name="category_id">
              <option value="-1" {{ !$item->category_id ? 'selected' : '' }}>-- Pilih Kategori --</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                  {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h4 class="mb-1">Info Inventori</h4>
          <hr class="mb-3 mt-0">
          <div class="form-group">
            <label for="supplier_id">Supplier Tetap <button type="button" class="btn btn-sm btn-default plus-btn"
                data-toggle="modal" data-target="#supplier-dialog" title="Tambah"><i class="fa fa-plus"></i>
              </button></label>
            <div class="input-group">
              <select class="custom-select select2" id="supplier_id" name="supplier_id">
                <option value="-1" {{ !$item->supplier_id ? 'selected' : '' }}>-- Pilih Supplier --</option>
                @foreach ($suppliers as $supplier)
                  <option value="{{ $supplier->id }}"
                    {{ old('supplier_id', $item->supplier_id) == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="uom">Satuan</label>
            <input type="text" class="form-control col-md-5 @error('uom') is-invalid @enderror" id="uom"
              placeholder="Masukkan satuan produk" name="uom" value="{{ old('uom', $item->uom) }}">
            @error('uom')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="stock">Stok</label>
            <input type="text" class="form-control col-md-5 text-right @error('stock') is-invalid @enderror"
              id="stock" placeholder="Masukkan stok produk" name="stock"
              value="{{ old('stock', format_number($item->stock)) }}">
            @error('stock')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="minimum_stock">Stok Minimum</label>
            <input type="text" class="form-control col-md-5 text-right @error('minimum_stock') is-invalid @enderror"
              id="minimum_stock" placeholder="Masukkan stok produk minimum" name="minimum_stock"
              value="{{ old('minimum_stock', format_number($item->minimum_stock)) }}">
            @error('minimum_stock')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h4 class="mb-1">Info Harga</h4>
          <hr class="mb-3 mt-0">
          <div class="form-group">
            <label for="cost">Modal / Harga Beli</label>
            <input type="text" class="form-control col-md-5 text-right @error('cost') is-invalid @enderror"
              id="cost" placeholder="Masukkan modal / harga beli produk" name="cost"
              value="{{ old('cost', format_number($item->cost)) }}">
            @error('cost')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="price">Harga Jual</label>
            <input type="text" class="form-control col-md-5 text-right @error('price') is-invalid @enderror"
              id="price" placeholder="Masukkan harga jual produk" name="price"
              value="{{ old('price', format_number($item->price)) }}">
            @error('price')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="profit">Laba</label>
            <input type="text" readonly class="form-control col-md-5 text-right" id="profit"
              value="{{ format_number(floatval(old('price', $item->price)) - floatval(old('cost', $item->cost))) }}">
            <p class="text-muted">Margin Keuntungan: <span id="profit-percent">0%</span></p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h4 class="mb-1">Info Tambahan</h4>
          <hr class="mb-3 mt-0">
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input " id="active" name="active" value="1"
                {{ old('active', $item->active) ? 'checked="checked"' : '' }}>
              <label class="custom-control-label" for="active" title="Akun aktif dapat login">Aktif</label>
            </div>
            <div class="text-muted">Produk aktif dapat dijual.</div>
          </div>
          <div class="form-group">
            <label for="notes">Catatan</label>
            <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes" cols="30"
              rows="4">{{ old('notes', $item->notes) }}</textarea>
            @error('notes')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
        </div>
      </div>
    </div>
  </div>
@endSection
@section('footscript')
  <script>
    $(document).ready(function() {
      //js
      function updateProfitMargin() {
        let cost = localeNumberToNumber($('#cost').val());
        let price = localeNumberToNumber($('#price').val());
        let profit = price - cost;
        let text = toLocaleNumber(profit / price * 100, 2);
        $('#profit').val(toLocaleNumber(profit));
        $('#profit-percent').text((text === 'NaN' || text === '-∞' ? '0' : text) + '%');
      }

      Inputmask("decimal", INPUTMASK_OPTIONS).mask("#stock");
      Inputmask("decimal", Object.assign({
        allowMinus: false
      }, INPUTMASK_OPTIONS)).mask("#price,#cost");

      $('.select2').select2();
      $('#cost').change(function() {
        updateProfitMargin();
      });
      $('#price').change(function() {
        updateProfitMargin();
      });
      updateProfitMargin();
      $('.is-invalid').focus();

      $('#supplier-form').submit(function(e) {
        console.log($(this));
        e.preventDefault();
        let frm = $(this);
        $.ajax({
          type: frm.attr('method'),
          url: frm.attr('action'),
          data: frm.serialize(),
          success: function(data) {
            let supplier = data.data;
            var newOption = new Option(supplier.name, supplier.id, true, true);
            $('#supplier_id').append(newOption).trigger('change');
            toastr["info"](data.message);
            frm.trigger("reset");
            $('#supplier-dialog').modal('hide');
          },
          error: function(data) {
            toastr["error"]('Terdapat kesalahan saat menambahkan supplier.');
          },
        });
      });
      $('#category-form').submit(function(e) {
        e.preventDefault();
        let frm = $(this);
        $.ajax({
          type: frm.attr('method'),
          url: frm.attr('action'),
          data: frm.serialize(),
          success: function(data) {
            let category = data.data;
            var newOption = new Option(category.name, category.id, true, true);
            $('#category_id').append(newOption).trigger('change');
            toastr["info"](data.message);
            frm.trigger("reset");
            $('#category-dialog').modal('hide');
          },
          error: function(data) {
            toastr["error"]('Terdapat kesalahan saat menambahkan kategori.');
          },
        });
      });
    });
  </script>
@endsection

@section('modal')
  @include('admin.product.supplier-form')
  @include('admin.product.category-form')
@endsection
