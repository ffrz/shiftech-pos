@extends('admin._layouts.default', [
    'title' => 'Laporan-laporan',
    'menu_active' => 'report',
    'nav_active' => 'report',
])

@section('content')
  <div class="card">
    <div class="card-body">
      <section>
        <h5>Laporan Rincian Pengeluaran</h5>
        <form action="?" method="GET">
          <div class="form-group">
            <label for="report-period">Periode:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="far fa-calendar-alt"></i>
                </span>
              </div>
              <input class="form-control float-right" id="report-period" type="text" name="period">
            </div>
            @error('period')
              <span class="text-danger">
                {{ $message }}
              </span>
            @enderror
          </div>
          <div class="form-group">
            <button class="btn btn-sm btn-primary" type="submit" title="Cetak Laporan">
              <i class="fa fa-print"></i> Cetak Laporan
            </button>
          </div>
        </form>
      </section>
    </div>
  </div>
@endSection

@section('footscript')
  <script>
    $(document).ready(function() {
      $('#report-period').daterangepicker({
        locale: {
            format: DATE_FORMAT
        }
      });
    });
  </script>
@endsection
