@extends('admin._layouts.default', [
    'title' => 'Bersihkan Aktivitas Pengguna',
    'menu_active' => 'system',
    'nav_active' => 'user-activity',
])

@section('content')
  <form action="{{ url('admin/user-activity/clear') }}" id="editor" method="POST">
    @csrf
    <div class="row">
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <label for="user_id">Pengguna</label>
              <select class="form-control custom-select select2" id="user_id" name="user_id">
                <option value="">Semua</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}" {{ $data['user_id'] == $user->id ? 'selected' : '' }}>
                    {{ $user->username }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="type">Jenis Aktifitas</label>
              <select class="form-control custom-select select2" id="type" name="type">
                <option value="">Semua</option>
                @foreach ($types as $type => $label)
                  <option value="{{ $type }}" {{ $data['type'] == $type ? 'selected' : '' }}>
                    {{ $label }}</option>
                @endforeach
              </select>
            </div>
            {{-- <div class="form-group">
            <label for="date_range">Tanggal</label>
          </div> --}}
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-danger">Bersihkan</button>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('footscript')
  <script>
    $(document).ready(function() {
      $('#editor').submit(function(e) {
        if (!confirm('Apakah anda yakin akan membersihkan riwayat aktifitas?')) {
          e.preventDefault();
          return false;
        }

        return true;
      });
    });
  </script>
@endsection
