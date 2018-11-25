<!doctype html>
<?php include_once('includes/init.php');
$tableData= null;
$url=('http://192.168.186.21/offer/tabele.json');
$tableData = json_decode(file_get_contents($url));


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
    <div class="breadcrumb"><h3>Fudbal</h3></div>
    <div class="content mt-3">
        <div class="animated fadeIn">

            <div class="row">


                <?php $currentLeague = null; $currentSubtype = null; $currentSport=1;
                foreach ($tableData  as $item) { if ($item->cmpid != $currentLeague) {   if ($currentLeague != null) {
                    ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>

<?php
if($item->sport_id != $currentSport) { $currentSport = $item->sport_id; ?>
</div>
    </div>
    </div>
    <div class="breadcrumb"><h3><?php echo $allSport[$currentSport]; ?></h3></div>
<div class="content mt-3">
    <div class="animated fadeIn">

        <div class="row">
<?php }
?>


<?php }

$currentLeague = $item->cmpid;



?>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <strong class="card-title"><?php echo $item->name ?></strong>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>po</th>
                        <th>Team</th>
                        <th>Ut</th>
                        <th>P</th>
                        <?php echo ($currentSport != 2 && $currentSport != 4 && $currentSport != 6)? "<th>N</th>" : ""?>
                        <th>I</th>
                        <th class="text-center">D:P</th>
                        <th class="text-center">Pn</th>
                    </tr>
                    </thead>
                    <tbody>


                    <?php }
                    if($item->cmpType != 'Team' && $currentSubtype != $item->cmpType){
                    $currentSubtype =$item->cmpType; ?>

                    <tr>
                        <th colspan="8"><?php echo $currentSubtype ?></th>
                    </tr>
                    <?php } ?>


                                     <tr>
                                         <td><?php echo $item->position?></td>
                                         <td class="<?php echo ($item->mozzart != null) ? "" : "text-danger"?>"><?php ($item->mozzart != null) ? $teamname =$item->mozzart : $teamname =$item->team; echo substr($teamname,0, 10)?></td>
                                         <td class="text-center <?php echo ($item->played == $item->won + $item->draw + $item->loses) ? "" : "sufee-alert alert with-close alert-danger alert-dismissible fade show"?>"><?php echo $item->played?></td>
                                         <td class="text-center"><?php echo $item->won?></td>
                                         <?php echo ($currentSport != 2 && $currentSport != 4 && $currentSport != 6)? "<td class='text-center'>$item->draw</td>" : ""?>
                                         <td class="text-center"><?php echo $item->loses?></td>
                                         <td class="text-center font-italic"><?php echo $item->goal_diff?></td>
                                         <td class="text-center font-weight-bold <?php  $tmp = calculatePoints($item->sport_id, $item->won, $item->draw, $item->loses,$item->points); echo ($tmp == 0) ? "" : "sufee-alert alert with-close alert-danger alert-dismissible fade show"?>"><?php echo floatval($item->points)?></td>


                                     </tr>


                    <?php } ?>

                    </tbody>
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

</body>
</html>
