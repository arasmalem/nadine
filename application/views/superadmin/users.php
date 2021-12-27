<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Manajemen User
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manajemen User</li>
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
                <button class="btn btn-primary" data-toggle="modal" data-target="#newUser" title="Tambah User"><i class="fa fa-plus"></i> Tambah</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form method="post" action="<?= site_url('users') ?>" class="form-inline">
                    <div class="input-group col-md-2">
                        <input type="text" class="form-control" placeholder="Masukkan keyword.." name="keyword" autocomplete="off" autofocus required>
                        <span class="input-group-btn">
                            <input class="btn btn-success" type="submit" name="submit" value="Cari">
                        </span>
                    </div>
                    <div class="input-group">
                        <span class="input-group"><a href="<?= site_url('users/reset') ?>" class="btn btn-danger" title="Reset Pencarian">Reset</a></span>
                    </div>
                </form><br />
                <h5>Result(s) : <?= $total_rows . ' data' ?></h5>
                <div class="table-responsive">
                    <table id="table1" class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <center>#</center>
                                </th>
                                <th>
                                    <center>Foto</center>
                                </th>
                                <th>
                                    <center>Username</center>
                                </th>
                                <th>
                                    <center>Nama Lengkap</center>
                                </th>
                                <th>
                                    <center>Bidang</center>
                                </th>
                                <th>
                                    <center>Sub Bidang</center>
                                </th>
                                <th>
                                    <center>Role</center>
                                </th>
                                <th>
                                    <center>Status</center>
                                </th>
                                <th>
                                    <center>Actions</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($total_rows)) { ?>
                                <tr>
                                    <td align='center' colspan="12">
                                        <span class="text text-danger">
                                            <h5>- Data tidak ditemukan -</h5>
                                        </span>
                                    </td>
                                </tr>
                            <?php }  ?>
                            <?php
                            foreach ($all_user as $row) : ?>
                                <tr>
                                    <td align='center'><?= ++$start ?></td>
                                    <td align='center'><img src="<?= base_url('assets/images/') . $row->foto ?>" class="zoom avatar"></td>
                                    <td><?= $row->username ?></td>
                                    <td><?= $row->nama ?></td>
                                    <td align='center'>
                                        <?php
                                        if (@$row->bidang == 0) {
                                            echo '';
                                        } else {
                                            $bidang = $this->Users_model->getBidangById(@$row->bidang);
                                            echo @$bidang->name;
                                        }
                                        ?>
                                    </td>
                                    <td align='center'>
                                        <?php
                                        if (@$row->sub_bidang == 0) {
                                            echo '';
                                        } else {
                                            $subbidang = $this->Users_model->getSubBidangById(@$row->sub_bidang);
                                            echo @$subbidang->name;
                                        }
                                        ?>
                                    </td>
                                    <td align='center'>
                                        <span class="
                                        <?php
                                        if ($row->role == 'Super Admin') {
                                            echo 'label label-warning';
                                        } elseif ($row->role == 'Admin') {
                                            echo 'label label-primary';
                                        } else {
                                            echo 'label label-success';
                                        }
                                        ?>">
                                            <?= $row->role ?>
                                        </span>
                                    </td>
                                    <td align='center'>
                                        <span class="<?= ($row->status == 'Active') ? 'badge bg-navy'  : 'badge bg-red'; ?>">
                                            <?= $row->status ?>
                                        </span>
                                    </td>
                                    <td align='center'>
                                        <a href="" class="text text-primary fa fa-edit" data-toggle="modal" data-target="#editUser<?= $row->id ?>" title="Edit"></a>&nbsp;
                                        <?php if ($row->role != 'Super Admin') { ?>
                                            <a href="" class="text text-danger fa fa-trash" data-toggle="modal" data-target="#delUser<?= $row->id; ?>" title="Delete"></a>&nbsp;
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- pagination -->
                <?= $pagination ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Tambah User -->
<div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah User</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= site_url('users/add'); ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label>Bidang</label>
                        <select class="form-control" name="bidang" id="bid">
                            <option value="0" selected>- Pilih Bidang -</option>
                            <?php foreach ($all_bidang as $row) : ?>
                                <option value="<?= $row->id ?>"><?= $row->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sub Bidang</label>
                        <select class="form-control" name="sub_bidang" id="subid">
                            <option value="0">- Pilih Sub Bidang / Sub Bagian -</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <div class="row">
                            <div class="col-md-2"><input type="radio" class="minimal" name="role" value="User" checked>&nbsp;&nbsp;&nbsp;User</div>
                            <div class="col-md-2"><input type="radio" class="minimal" name="role" value="Admin">&nbsp;&nbsp;&nbsp;Admin</div>
                            <div class="col-md-4"><input type="radio" class="minimal" name="role" value="Super Admin">&nbsp;&nbsp;&nbsp;Super Admin</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div class="row">
                            <div class="col-md-2"><input type="radio" class="minimal" name="status" value="Active" checked>&nbsp;&nbsp;Active</div>
                            <div class="col-md-2"><input type="radio" class="minimal" name="status" value="Banned">&nbsp;&nbsp;Banned</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="file">Foto</label>
                        <input type="file" id="file" name="foto">
                        <p class="help-block" style="color:red;">File foto maksimal berukuran 200kb dan bertipe jpg/jpeg/png</p>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<?php foreach ($all_user as $row) : ?>
    <div class="modal fade" id="editUser<?= $row->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit User</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= site_url('users/edit/') . $row->id; ?>" enctype="multipart/form-data">
                        <input type="hidden" name="old_image" value="<?= $row->foto ?>">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= $row->username ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-danger">Biarkan kosong jika password tidak dirubah</small>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= $row->nama ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Bidang</label>
                            <select class="form-control" name="bidang" id="bidang">
                                <?php if ($row->bidang != 0 and $row->bidang != 123) { ?>
                                    <?php foreach ($all_bidang as $bidang) : ?>
                                        <?php if ($row->bidang === $bidang->id) { ?>
                                            <option value="<?= $bidang->id ?>" selected><?= $bidang->name ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $bidang->id ?>"><?= $bidang->name ?></option>
                                        <?php } ?>
                                    <?php endforeach;
                                } elseif ($row->bidang == 0) { ?>
                                    <option value="0">- Pilih Bidang -</option>
                                <?php } elseif ($row->bidang == 123) { ?>
                                    <option value="123">BADAN KEPEGAWAIAN DAERAH</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Sub Bidang</label>
                            <select class="form-control" name="sub_bidang" id="sub_bidang">
                                <?php @$all_subbidang = $this->Users_model->getAllSubBidang(@$row->bidang); ?>
                                <?php if ($row->bidang != 0 and $row->sub_bidang != 0) { ?>
                                    <?php foreach ($all_subbidang as $sub_bidang) : ?>
                                        <?php if ($row->sub_bidang === $sub_bidang->id) { ?>
                                            <option value="<?= $sub_bidang->id ?>" selected><?= $sub_bidang->name ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $sub_bidang->id ?>"><?= $sub_bidang->name ?></option>
                                        <?php } ?>
                                    <?php endforeach;
                                } elseif ($row->bidang != 0 and $row->bidang != 123 and $row->sub_bidang == 0) { ?>
                                    <option value="0">- Pilih Sub Bidang -</option>
                                <?php } elseif ($row->bidang == 0 and $row->sub_bidang == 0) { ?>
                                    <option value="0">- Pilih Sub Bidang -</option>
                                <?php } elseif ($row->bidang == 123 and $row->sub_bidang == 0) { ?>
                                    <option value="0">- Pilih Sub Bidang -</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <div class="row">
                                <div class="col-md-2"><input type="radio" class="minimal" name="role" value="User" <?php if ($row->role == 'User') echo 'checked'; ?>>
                                    &nbsp;&nbsp;&nbsp;User
                                </div>
                                <div class="col-md-2"><input type="radio" class="minimal" name="role" value="Admin" <?php if ($row->role == 'Admin') echo 'checked'; ?>>
                                    &nbsp;&nbsp;&nbsp;Admin
                                </div>
                                <div class="col-md-4"><input type="radio" class="minimal" name="role" value="Super Admin" <?php if ($row->role == 'Super Admin') echo 'checked'; ?>>
                                    &nbsp;&nbsp;&nbsp;Super Admin
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <div class="row">
                                <div class="col-md-2"><input type="radio" class="minimal" name="status" value="Active" <?php if ($row->status == 'Active') echo 'checked'; ?>>&nbsp;&nbsp;Active</div>
                                <div class="col-md-2"><input type="radio" class="minimal" name="status" value="Banned" <?php if ($row->status == 'Banned') echo 'checked'; ?>>&nbsp;&nbsp;Banned</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="file">Foto</label>
                            <input type="file" id="file" name="foto">
                            <p class="help-block" style="color:red;">Kosongkan jika foto tidak diganti. Ukuran file maksimal 200kb dan bertipe jpg/jpeg/png</p>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Modal Hapus User -->
<?php
foreach ($all_user as $row) :
?>
    <div class="modal fade" id="delUser<?= $row->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><i class="fa fa-exclamation-circle"></i> Konfirmasi</h3>
                </div>
                <div class="modal-body">
                    <h4>Apakah Anda yakin ingin menghapus user <b><?= $row->username ?></b> ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <a href="<?= site_url('users/delete/') . $row->id ?>" class="btn btn-success">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>