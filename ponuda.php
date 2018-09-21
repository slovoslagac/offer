<!doctype html>
<?php include_once('includes/init.php');

if (isset($_POST["hockey"])) {
    session_start();
    $season = $_POST['season'];
    $league = $_POST['league'];
    $_SESSION["season"] = $season;
    $_SESSION["league"] = $league;

    header("Location:results.php");
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

                <div class="col-xs-6 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Skidanje hokeja</strong>
                        </div>
                        <div class="card-body">
                            <form method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>" name="hockey">
                                <select data-placeholder="Izaberi sezonu" class="standardSelect" tabindex="1" name="season" required>
                                    <option value=""></option>
                                    <option value="2018-2019">2018-2019</option>
                                </select>
                                <hr>
                                <div class="form-group">
                                    <label for="league" class="control-label mb-1">Unesi ligu</label>
                                    <input id="league" type="text" class="form-control" aria-required="true" aria-invalid="false" name="league" required>
                                    <small class="form-text text-muted">ex. belarus/extraliga</small>
                                </div>
                                <button type="submit" class="btn btn-primary float-right" name="hockey">Skini ponudu</button>
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

</body>
</html>
