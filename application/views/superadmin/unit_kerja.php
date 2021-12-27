<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Unit Kerja
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Unit Kerja</li>
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
        <div class="box">
            <div class="box-header">
                <a class="btn btn-primary" href="<?= site_url('UnitKerja/addunitkerja') ?>"><i class="fa fa-plus"></i> Tambah</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="menuContent" class="menuContent">
                    <ul id="treeUnit" class="ztree"></ul>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->