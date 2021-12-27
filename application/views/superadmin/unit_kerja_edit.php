<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit Unit Kerja
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Edit Unit Kerja</li>
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
                        <?php foreach ($unit as $row) : ?>
                            <form method="post" action="<?= site_url('unitkerja/edit/') . $row->id ?>">
                                <div class="form-group">
                                    <label for="pid">Id Parent</label>
                                    <input type="text" class="form-control" id="pid" name="pid" value="<?= $row->pId ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="id">Id</label>
                                    <input type="text" class="form-control" id="id" name="id" value="<?= $row->id ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Unit Kerja</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $row->name ?>" required>
                                </div>
                    </div>
                    <div class="modal-footer">
                        <a href="<?= site_url('unitkerja/delete/') . $row->id ?>" class="btn btn-danger">Hapus</a>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                    </form>
                <?php endforeach; ?>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->