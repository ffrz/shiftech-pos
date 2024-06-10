@php
  $title = ($item->id ? 'Edit' : 'Tambah') . ' Transaksi Keuangan';
@endphp

@extends('admin._layouts.default', [
    'title' => $title,
    'menu_active' => 'finance',
    'nav_active' => 'cash-transaction',
    'form_action' => url('admin/cash-transaction/edit/' . (int) $item->id),
])

@section('right-menu')
  <li class="nav-item">
    <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-save mr-1"></i> Simpan</button>
    <a onclick="return confirm('Batalkan perubahan?')" class="btn btn-default"
      href="{{ url('/admin/cash-transaction/') }}"><i class="fas fa-cancel mr-1"></i>Batal</a>
  </li>
@endSection

@section('content')
  <div class="row">
    <div class="col-md-4">
      <div class="card card-primary">
        <div class="card-body">
          <div class="form-group">
            <label for="account_id">Akun</label>
            <select class="custom-select select2 @error('account_id') is-invalid @enderror" id="account_id"
              name="account_id">
              <option value="" {{ !$item->account_id ? 'selected' : '' }}>-- Pilih Akun --</option>
              @foreach ($accounts as $account)
                <option value="{{ $account->id }}"
                  {{ old('account_id', $item->account_id) == $account->id ? 'selected' : '' }}>
                  {{ $account->idFormatted() }} - {{ $account->name }}
                </option>
              @endforeach
            </select>
            @error('account_id')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="category_id">Kategori</label>
            <select class="custom-select select2 @error('category_id') is-invalid @enderror" id="category_id"
              name="category_id">
              <option value="" {{ !$item->category_id ? 'selected' : '' }}>-- Pilih Kategori --</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                  {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
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
            <label for="date" class="col-form-label">Tanggal:</label>
            <input autofocus type="date" class="form-control @error('date') is-invalid @enderror" id="date"
              name="date" value="{{ old('date', $item->date) }}">
            @error('date')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="description">Deskripsi</label>
            <input type="text" class="form-control @error('description') is-invalid @enderror" autofocus
              id="description" placeholder="Deskripsi" name="description"
              value="{{ old('description', $item->description) }}">
            @error('description')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="type" class="col-form-label">Jenis</label>
            <div class="form-group clearfix">
              <div class="icheck-primary d-inline mr-2">
                <input type="radio" id="radioPrimary1" name="type" value="income"
                  {{ old('type', $item->type) == 'income' ? 'checked' : '' }}>
                <label for="radioPrimary1">Pemasukan
                </label>
              </div>
              <div class="icheck-primary d-inline">
                <input type="radio" id="radioPrimary2" name="type" value="expense"
                {{ old('type', $item->type) == 'expense' ? 'checked' : '' }}>
                <label for="radioPrimary2">Pengeluaran
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="amount">Jumlah</label>
            <input type="text" class="form-control col-md-5 text-right @error('amount') is-invalid @enderror"
              id="amount" placeholder="Jumlah pengeluaran" name="amount"
              value="{{ format_number(old('amount', $item->amount)) }}">
            @error('amount')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
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
      Inputmask("decimal", Object.assign({
        allowMinus: true
      }, INPUTMASK_OPTIONS)).mask("#amount");
    });
  </script>
@endSection
