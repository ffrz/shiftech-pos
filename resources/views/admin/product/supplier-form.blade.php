<form id="supplier-form" method="POST" action="{{ url('/admin/ajax/add-supplier') }}">
  @csrf
  <div class="modal fade" id="supplier-dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Pemasok</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name">Nama Pemasok</label>
            <input type="text" class="form-control" required id="name" placeholder="Masukkan nama pemasok"
              name="name">
          </div>
          <div class="form-group">
            <label for="phone">No. Telepon</label>
            <input type="text" class="form-control" id="phone" placeholder="Masukkan no telepon pemasok"
              name="phone">
          </div>
          <div class="form-group">
            <label for="address">Alamat</label>
            <textarea class="form-control" name="address" id="address" cols="30" rows="4"></textarea>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-1"></i> Simpan</button>
          <button type="button" id="cancel-add-supplier" class="btn btn-default"><i class="fa fa-xmark mr-1"></i> Batal</button>
        </div>
      </div>
    </div>
  </div>
</form>
