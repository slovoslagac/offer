<!doctype html>
<?php include_once('includes/init.php');

$sportId = 4;


if (isset($_POST['f'])) {
    $sportId = 1;
}
if (isset($_POST['k'])) {
    $sportId = 2;
}
if (isset($_POST['h'])) {
    $sportId = 4;
}
if (isset($_POST['o'])) {
    $sportId = 6;
}
if (isset($_POST['r'])) {
    $sportId = 7;
}
if (isset($_POST['v'])) {
    $sportId = 9;
}
if (isset($_POST['rg'])) {
    $sportId = 10;
}
if (isset($_POST['ft'])) {
    $sportId = 69;
}
if (isset($_POST['af'])) {
    $sportId = 58;
}

$connTeams = getConnTeams($sportId);
$mozzTeams = getMozzartTeams($sportId);
$offer = getCurrentOffer();
$indexes = array();



if (isset($_POST["saveteam"])) {
    foreach ($_POST['source_team'] as $index => $inv) {
        if ($_POST['mozz_team'][$index] == 0) {
            continue;
        }

        $indexes[] = $index;
    }
    $datatmp = array();
    foreach ($indexes as $index) {
        $tmp = array();
        $tmp['conn'] = $_POST['source_team'][$index];
        $tmp['mozz'] = $_POST['mozz_team'][$index];

        $datatmp[] = $tmp;
    }

    foreach ($datatmp as $d) {


        $mozzartTeamID = $d['mozz'];
        $srcTeamID = $d['conn'];


        $sql = "insert into table_conn_team ( init_team_id, table_team_id ) values ";


        $sql .= "(" . $mozzartTeamID . "," . $srcTeamID . ") ON DUPLICATE KEY UPDATE init_team_id=init_team_id";


        $prepare = $conn->prepare($sql);
        $prepare->execute();

    }
    $conn = null;

    header("Location:administracija.php");
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

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Trenutna ponuda za današnji kladioničarski dan</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Liga</th>
                                    <th scope="col">Vreme osvežavanja</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1;
                                foreach ($offer as $item) { ?>
                                    <tr>
                                        <th scope="row"><?php echo $i ?></th>
                                        <th scope="row"><?php echo $item->mozzart ?></th>
                                        <th scope="row"><?php echo $item->timerefresh ?></th>
                                    </tr>


                                    <?php $i++;
                                } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">

                        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary" name="f">Fudbal</button>
                                <button type="submit" class="btn btn-success" name="k">Košar</button>
                                <button type="submit" class="btn btn-danger" name="h">Hokej</button>
                                <button type="submit" class="btn btn-info" name="o">Odboj</button>
                                <button type="submit" class="btn btn-warning" name="r">Rukom</button>
                                <button type="submit" class="btn btn-primary" name="v">Vater</button>
                                <button type="submit" class="btn btn-success" name="rg">Ragbi</button>
                                <button type="submit" class="btn btn-danger" name="ft">Futs</button>
                                <button type="submit" class="btn btn-info" name="af">Am.Fu</button>
                            </div>
                        </form>

                        <div class="card-body">
                            <table class="table table-striped">
                                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                                    <?php $currentCountry = null;
                                    foreach ($connTeams

                                    as $item) {
                                    if (array_key_exists($item->country_id, $mozzTeams)){
                                    $tempMozz = $mozzTeams[$item->country_id];
                                    if ($item->country_id != $currentCountry) {
                                    $currentCountry = $item->country_id;
                                    ?>
                                    <thead>
                                    <tr>
                                        <th scope="col" colspan="4"><?php echo $item->country ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php } ?>
                                    <tr>
                                        <td><input type="hidden" name="source_team[]" value="<?php echo $item->id ?>"><?php echo $item->name ?></td>
                                        <td>
                                            <select name="mozz_team[]">
                                                <option></option>
                                                <?php foreach ($tempMozz as $team) { ?>
                                                    <option value="<?php echo $team->id ?>"><?php echo $team->name ?></option>

                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <?php } else {
                                        $tempMozz = array();
                                    }
                                    } ?>

                                    <tr>
                                        <td colspan="2">
                                            <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sačuvaj" accesskey="x" name="saveteam"/>
                                        </td>
                                    </tr>
                                    </tbody>

                                </form>
                            </table>

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
