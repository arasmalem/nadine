<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2022 <a href="http://bakorwilpamekasan.jatimprov.go.id" target="_blank">Bakorwil Pamekasan</a></strong>
</footer>

<!-- jQuery 3 -->
<script src="<?= base_url('assets/AdminLTE/') ?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url('assets/AdminLTE/') ?>bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= base_url('assets/AdminLTE/') ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- datepicker -->
<script src="<?= base_url('assets/AdminLTE/') ?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets/AdminLTE/') ?>dist/js/adminlte.min.js"></script>
<!-- Select2 -->
<script src="<?= base_url('assets/AdminLTE/') ?>bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- Checkbox -->
<script src="<?= base_url('assets/AdminLTE/') ?>plugins/iCheck/icheck.min.js"></script>
<!-- ChartJS -->
<script src="<?= base_url('assets/AdminLTE/') ?>bower_components/chart.js/Chart.js"></script>
<!-- zTree -->
<script src="<?= base_url('assets/zTree/js/') ?>jquery.ztree.core-3.5.js"></script>
<!-- Sweetalert -->
<script src="<?= base_url('assets/AdminLTE/') ?>plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= base_url('assets/flashdata.js') ?>"></script>

<!-- page script -->
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2();

        // Date picker
        $('.datepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#bid').change(function() {
            var pId = $('#bid').val();
            $.ajax({
                type: "POST",
                url: "<?= site_url("users/getListSubid"); ?>",
                data: "pId=" + pId,
                success: function(data) {
                    $('#subid').html(data);
                }
            });
        });

        $('#bidang').change(function() {
            var pId = $('#bidang').val();
            $.ajax({
                type: "POST",
                url: "<?= site_url("users/getListSubid"); ?>",
                data: "pId=" + pId,
                success: function(data) {
                    $('#sub_bidang').html(data);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajax({
            url: "<?= site_url('unitkerja/unit') ?>",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var setting = {
                    data: {
                        simpleData: {
                            enable: true
                        }
                    }
                };

                var zNodes = data;

                $.fn.zTree.init($("#treeUnit"), setting, zNodes);
            },
            error: function(data) {
                //  alert(data);
            }
        })
    });

    function onClick(e, treeId, treeNode) {
        var zTree = $.fn.zTree.getZTreeObj("treeUnit"),
            nodes = zTree.getSelectedNodes(),
            v = "";
        nodes.sort(function compare(a, b) {
            return a.id - b.id;
        });
        for (var i = 0, l = nodes.length; i < l; i++) {
            v += nodes[i].name + ",";
        }
        if (v.length > 0) v = v.substring(0, v.length - 1);
        var cityObj = $("#citySel");
        cityObj.attr("value", v);
    }

    function showMenu() {
        var cityObj = $("#citySel");
        var cityOffset = $("#citySel").offset();
        $("#menuContent").css({
            left: cityOffset.left + "px",
            top: cityOffset.top + cityObj.outerHeight() + "px"
        }).slideDown("fast");

        $("body").bind("mousedown", onBodyDown);
    }

    function hideMenu() {
        $("#menuContent").fadeOut("fast");
        $("body").unbind("mousedown", onBodyDown);
    }

    function onBodyDown(event) {
        if (!(event.target.id == "menuBtn" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length > 0)) {
            hideMenu();
        }
    }
</script>

</body>

</html>