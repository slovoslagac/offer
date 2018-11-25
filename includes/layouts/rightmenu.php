<!-- Header-->
<?php
if(isset($_POST['refresPage'])){
    refreshTable();

    header("Refresh:0");
}
?>
<header id="header" class="header">

    <div class="header-menu">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <div class="col-sm-7">
                <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                <div class="header-left"></div>
                <button type="submit" class="btn btn-primary" name="refresPage" id="refeshPage"><i class="fa fa-refresh"></i> Osveži tabele</button>
            </div>

            <div class="col-sm-5">
                <div class="user-area dropdown float-right">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="user-avatar rounded-circle" src="images/admin.jpg" alt="User Avatar">
                    </a>

                </div>

            </div>
        </form>
    </div>

</header><!-- /header -->
<!-- Header-->


<!--<script type="text/javascript">-->
<!--    $.ajax({ url: '../refresh.php',-->
<!--        data: {function2call: 'refreshTable', otherkey:otherdata},-->
<!--        type: 'post',-->
<!--        success: function(output) {-->
<!--            alert(output);-->
<!--        }-->
<!--    });-->
<!---->
<!---->
<!--</script>-->