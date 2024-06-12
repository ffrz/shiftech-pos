@php
  $title = ($item->id ? 'Edit' : 'Tambah') . ' Pengeluaran';
@endphp

@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'expense',
    'nav_active' => 'expense',
    'form_action' => url('admin/expense/edit/' . (int) $item->id),
])

@section('right-menu')
  <li class="nav-item">
    <button class="btn btn-primary mr-1" type="submit"><i class="fas fa-save mr-1"></i> Simpan</button>
    <a class="btn btn-default" href="{{ url('/admin/expense/') }}" onclick="return confirm('Batalkan perubahan?')"><i
        class="fas fa-cancel mr-1"></i>Batal</a>
  </li>
@endSection

@section('content')
  <div class="row">
    <div class="col-lg-4">
      <div class="card card-primary">
        <div class="card-body">
          <div class="form-group">
            <label class="col-form-label" for="date">Tanggal:</label>
            <input class="form-control @error('date') is-invalid @enderror" id="date" name="date" type="date" value="{{ old('date', $item->date) }}" autofocus>
            @error('date')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="category_id">Kategori
              <button class="btn btn-sm btn-default plus-btn" data-toggle="modal" data-target="#category-dialog" type="button" title="Tambah">
                <i class="fa fa-plus"></i>
              </button>
            </label>
            <select class="custom-select select2 @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
              <option value="" {{ !$item->category_id ? 'selected' : '' }}>-- Pilih Kategori --</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            @error('category_id')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="description">Deskripsi</label>
            <input class="form-control @error('description') is-invalid @enderror" id="description" name="description" type="text"
              value="{{ old('description', $item->description) }}" autofocus placeholder="Contoh: Listrik Januari">
            @error('description')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="amount">Jumlah</label>
            <input class="form-control col-md-5 text-right @error('amount') is-invalid @enderror" id="amount" name="amount" type="text"
              value="{{ old('amount', format_number($item->amount)) }}" placeholder="Jumlah pengeluaran">
            @error('amount')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="notes">Catatan</label>
            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" cols="30" rows="4">{{ old('notes', $item->notes) }}</textarea>
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
    Inputmask("decimal", Object.assign({
      allowMinus: false
    }, INPUTMASK_OPTIONS)).mask("#amount");
    $('.select2').select2();
    $('#cancel-add-category').click(function() {
      $('#category-dialog').modal('hide');
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
  </script>
@endsection

@section('modal')
  @include('admin.expense.category-form')
@endsection
