<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h3 class="modal-title"><i class="fa fa-exclamation-circle"></i> Konfirmasi</h3>
            </div>
            <div class="modal-body">
                <h4>Apakah Anda yakin ingin keluar dari aplikasi ?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="<?= site_url('auth/logout'); ?>" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</div>