<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= base_url('assets/images/') . $user->foto; ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= $user->username; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU UTAMA</li>
            <li class="<?php if ($this->uri->segment(1) == 'home') echo 'active'; ?>">
                <a href="<?= site_url('home') ?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="<?php if ($this->uri->segment(1) == 'notadinas') echo 'active'; ?>">
                <a href="<?= site_url('notadinas') ?>">
                    <i class="fa fa-file-archive-o"></i><span>Nota Dinas</span>
                </a>
            </li>
            <li class="treeview <?php if ($this->uri->segment(1) == 'suratkeluar' or $this->uri->segment(1) == 'spacekeluar') echo 'active'; ?>">
                <a href="#">
                    <i class="glyphicon glyphicon-send"></i>
                    <span>Surat Keluar</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('suratkeluar') ?>"><i class="fa fa-circle-o text-aqua"></i> Surat Keluar</a></li>
                    <li><a href="<?= site_url('spacekeluar') ?>"><i class="fa fa-circle-o text-yellow"></i> Space Surat Keluar</a></li>
                </ul>
            </li>
            <li class="treeview <?php if ($this->uri->segment(1) == 'sk' or $this->uri->segment(1) == 'spacesk') echo 'active'; ?>">
                <a href="#">
                    <i class="fa fa-file"></i>
                    <span>Surat Keputusan</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('sk') ?>"><i class="fa fa-circle-o text-aqua"></i> Surat Keputusan</a></li>
                    <li><a href="<?= site_url('spacesk') ?>"><i class="fa fa-circle-o text-yellow"></i> Space SK</a></li>
                </ul>
            </li>
            <li class="treeview <?php if ($this->uri->segment(1) == 'spt' or $this->uri->segment(1) == 'spacespt') echo 'active'; ?>">
                <a href="#">
                    <i class="fa fa-file-text"></i>
                    <span>Surat Perintah Tugas</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= site_url('spt') ?>"><i class="fa fa-circle-o text-aqua"></i> SPT</a></li>
                    <li><a href="<?= site_url('spacespt') ?>"><i class="fa fa-circle-o text-yellow"></i> Space SPT</a></li>
                </ul>
            </li>
            <li class="<?php if ($this->uri->segment(1) == 'chat') echo 'active'; ?>">
                <a href="<?= site_url('chat') ?>">
                    <i class="fa fa-comments"></i><span>Chat</span>
                </a>
            </li>
            <li>
                <a href="" data-toggle="modal" data-target="#logoutModal">
                    <i class="fa fa-power-off"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>