<!doctype html>
<?php include_once('includes/init.php');


$lastConnTeams = getLastConnTeams(10000);

if (isset($_POST["deleteTeams"])){
    $multiple = $_POST['deleteItems'];

    foreach ($multiple as $team) {
        $sql = "delete from table_conn_team where id = $team";
        $prepare = $conn->prepare($sql);
        $prepare->execute();
    }

//    print_r($multiple);

    header("Location:brisanjetimova.php");
}






?>


<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=""> <!--<![endif]-->
<?php

include $head;

?>
<body>

<?php

include $leftmenu;

?>

<!-- Right Panel -->

<div id="right-panel" class="right-panel">

    <!-- Header-->
    <?php include $rightmenu; ?>
    <!-- Header-->


    <div class="content mt-3">
        <div class="animated fadeIn">

            <div class="row">


                <div class="col-lg-12">


                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Poslednji spojeni timovi</strong>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                            <table id="bootstrap-data-table" class="table table-striped table-bordered">

                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Mozzart ime</th>
                                    <th scope="col">Izvor ime</th>
                                    <th scope="col">Izvor</th>
                                    <th scope="col">Sport</th>
                                    <th scope="col" class="text-center">Izaberi</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1;
                                foreach ($lastConnTeams as $item) { ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $item->mozzart; ?></td>
                                    <td><?php echo $item->name; ?></td>
                                    <td><?php echo $allSources[$item->source_id]; ?></td>
                                    <td><?php echo $item->sport; ?></td>
                                    <td class="text-center"><input type="checkbox" name="deleteItems[]" value="<?php echo $item->id ?>"></td>
                                </tr>
                                    <?php $i++;
                                } ?>
                                </tbody>
                                <tr>
                                    <td colspan="6">
                                        <input class="btn btn-danger btn-lg btn-block" type="submit" value="Obriši veze" accesskey="d" name="deleteTeams"/>
                                    </td>
                                </tr>

                            </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div><!-- .animated -->
    </div><!-- .content -->


</div><!-- /#right-panel -->

<!-- Right Panel -->


<script src="assets/js/vendor/jquery-2.1.4.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/plugins.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/lib/chosen/chosen.jquery.min.js"></script>

<script>
    jQuery(document).ready(function () {
        jQuery(".standardSelect").chosen({
            disable_search_threshold: 10,
            no_results_text: "Oops, nothing found!",
            width: "100%"
        });
    });
</script>

<script src="assets/js/lib/data-table/datatables.min.js"></script>
<script src="assets/js/lib/data-table/dataTables.bootstrap.min.js"></script>
<script src="assets/js/lib/data-table/dataTables.buttons.min.js"></script>
<script src="assets/js/lib/data-table/buttons.bootstrap.min.js"></script>
<script src="assets/js/lib/data-table/jszip.min.js"></script>
<script src="assets/js/lib/data-table/pdfmake.min.js"></script>
<script src="assets/js/lib/data-table/vfs_fonts.js"></script>
<script src="assets/js/lib/data-table/buttons.html5.min.js"></script>
<script src="assets/js/lib/data-table/buttons.print.min.js"></script>
<script src="assets/js/lib/data-table/buttons.colVis.min.js"></script>
<script src="assets/js/lib/data-table/datatables-init.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        $('#bootstrap-data-table-export').DataTable();
    } );
</script>

</body>
</html>
