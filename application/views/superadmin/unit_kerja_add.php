<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Tambah Unit Kerja
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('Home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Tambah Unit Kerja</li>
        </ol>
        <!-- Flashdata -->
        <?php if ($this->session->flashdata('success')) { ?>
            <div class="flash-data" data-status="success" data-flashdata="<?= $this->session->flashdata('success') ?>"></div>
        <?php } elseif ($this->session->flashdata('failed')) { ?>
            <div class="flash-data" data-status="failed" data-flashdata="<?= $this->session->flashdata('failed') ?>"></div>
        <?php } ?>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-body">
                        <form method="post" action="<?= site_url('unitkerja/add') ?>">
                            <div class="form-group">
                                <label for="pid">Id Parent</label>
                                <!-- <input type="text" class="form-control" id="pid" name="pid"> -->
                                <select class="form-control select2" name="pid">
                                    <?php foreach ($unit as $u) : ?>
                                        <option value="<?= $u->id ?>"><?= $u->id . ' - ' . $u->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id">Id</label>
                                <input type="text" class="form-control" id="id" name="id">
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama Unit Kerja</label>
                                <input type="text" class="form-control" id="nama" name="nama">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <a href="<?= site_url('unitkerja') ?>" class="btn btn-danger">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->