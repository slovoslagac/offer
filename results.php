<!doctype html>
<?php include_once('includes/init.php');
session_start();

if (isset($_SESSION['league'])) {
    if($_SESSION["sport"] == 4 && $_SESSION["source"] == 11 && isset($_SESSION['season'])){
        $data = getHockey($_SESSION['league'], $_SESSION['season']);
        $sport = $_SESSION["sport"];
        $source = $_SESSION["source"];

    }
    else if ($_SESSION["sport"] == 1 && $_SESSION["source"] == 13)
    {
        $data1 = getBetexplorer($_SESSION['league']);
        $data2= getBetexplorerMatchResult($_SESSION['league']);
        $data = array_merge($data1,$data2);
        $sport = $_SESSION["sport"];
        $source = $_SESSION["source"];


    }
    unset($_SESSION);
}


if (isset($_POST['offer'])) {
    $del = 'truncate ulaz_new';
    $del1 = 'truncate ulaz_results';
    $del2 = 'truncate ulaz_details';
    $update = 'call import_statistic_data()';
    $prep = $conn->prepare($del);
    $prep->execute();
    $prep = $conn->prepare($del1);
    $prep->execute();
    $prep = $conn->prepare($del2);
    $prep->execute();


    foreach ($data as $item ){
        $newmatch = new offerMatch();
        $newmatch = $item; $newmatch->setAttr("sportid", $sport); $newmatch->setAttr("source", $source);
        $newmatch->add_match();
        unset($newmatch);
    }

    $prep = $conn->prepare($update);
    $prep->execute();

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

                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Data Table</strong>
                        </div>
                        <div class="card-body">
                            <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Datum</th>
                                    <th>Round</th>
                                    <th>Domaćin</th>
                                    <th>Gost</th>
                                    <th>Rezultat</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data as $item) { ?>
                                    <tr>
                                        <td><?php echo $item->datetime ?></td>
                                        <td><?php echo (isset($item->round))? $item->round : "-:-" ?></td>
                                        <td><?php echo $item->hometeam ?></td>
                                        <td><?php echo $item->awayteam ?></td>
                                        <td><?php echo (isset($item->ft))? $item->ft : "-:-" ?></td>
                                    </tr>

                                <?php } ?>

                                </tbody>

                            </table>
                            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                                <button type="submit" class="btn btn-primary float-right" name="offer">Sačuvaj ponudu</button>
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
    $(document).ready(function () {
        $('#bootstrap-data-table-export').DataTable();
    });
</script>


</body>
</html>
