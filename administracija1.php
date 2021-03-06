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

$connTeams = getConnTeams($sportId);
$mozzTeams = getMozzartTeams($sportId);
$offer = getCurrentOffer();
$connLeagues = getConnLinks();
$indexes = array();

if(isset($_POST['saveLeague'])){
    $mozzart = $_POST['mozzLeague'];
    $tmplink = new competitionLink();
    $tmplink->setAttr('competition_id', $mozzart);
    $tmplink->setAttr('link', $_POST['link']);
    $tmplink->addLink();

    header("Location:administracija1.php");
}


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


            <div class="col-sm-6">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Dodajavanje novih liga</strong>
                        </div>
                        <div class="card-body">

                            <select data-placeholder="Izaberi ligu...." class="standardSelect" tabindex="1" name="mozzLeague">
                                <option></option>
                                <?php foreach ($connLeagues as $league) { ?>
                                    <option value="<?php echo $league->id?>"><?php echo $league->mozzart ?></option>

                                <?php } ?>
                            </select>
                            <hr>
                            <div class="form-group">
                                <input type="text" id="link" placeholder="https://www.betexplorer.com/......https://int.soccerway.com/...." required="" class="form-control" name="link">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-sm" name="saveLeague">
                                <i class="fa fa-dot-circle-o"></i> Sačuvaj
                            </button>
                            <button type="submit" class="btn btn-danger btn-sm" name="cancelLeague">
                                <i class="fa fa-ban"></i> Odustani
                            </button>
                        </div>
                    </div>


                </form>
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

</body>
</html>
