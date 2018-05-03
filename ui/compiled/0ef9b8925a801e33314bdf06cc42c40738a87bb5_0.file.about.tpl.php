<?php
/* Smarty version 3.1.30, created on 2018-04-30 22:16:55
  from "C:\xampp\htdocs\root\ui\theme\ibilling\about.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5ae73367c7d504_82069282',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0ef9b8925a801e33314bdf06cc42c40738a87bb5' => 
    array (
      0 => 'C:\\xampp\\htdocs\\root\\ui\\theme\\ibilling\\about.tpl',
      1 => 1474949471,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sections/header.tpl' => 1,
    'file:sections/footer.tpl' => 1,
  ),
),false)) {
function content_5ae73367c7d504_82069282 (Smarty_Internal_Template $_smarty_tpl) {
?>

<?php $_smarty_tpl->_subTemplateRender("file:sections/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="row">
    <div class="col-md-12">
        <div id="updateProgressbar" class="progress" style="display: none;">
            <div class="progress progress-striped active">
                <div class="progress-bar" id="ib_progressing" role="progressbar" data-transitiongoal="10"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="ibox float-e-margins" id="ib_box">
            <div class="ibox-title">
                <h5>iBilling Build - <?php echo $_smarty_tpl->tpl_vars['_c']->value['build'];?>
</h5>

            </div>
            <div class="ibox-content" id="ibox_update">

                

                

                <button type="button" id="make_update" class="cls_update btn btn-danger">Update</button>


            </div>
        </div>

        <?php if ($_smarty_tpl->tpl_vars['app_stage']->value == 'Demo') {?>

<input type="hidden" name="purchase_code" id="purchase_code" value="">

            <?php } else { ?>

            <div class="ibox float-e-margins" id="ib_box">

                <div class="ibox-content">


                    <form role="form" name="accadd" method="post" action="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
settings/activate_license_post/">





                        <div class="form-group">
                            <label for="purchase_code">Purchase Code</label>
                            <input type="text" required class="form-control" id="purchase_code" name="purchase_code" value="<?php echo $_smarty_tpl->tpl_vars['_c']->value['purchase_code'];?>
">
                            <span class="help-block"><a href="#" target="_blank">Posted by Dospel & GanjaParker</a> </span>
                        </div>





                        <button type="submit" id="btn_save" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $_smarty_tpl->tpl_vars['_L']->value['Save'];?>
</button>
                    </form>




                </div>
            </div>

        <?php }?>



    </div>

    <div class="col-md-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <label for="resp"><?php echo $_smarty_tpl->tpl_vars['_L']->value['Response'];?>
</label>

            </div>
            <div class="ibox-content">

                <form class="form-horizontal push-10-t push-10" method="post" onsubmit="return false;">

                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="form-material floating">
                                <textarea class="form-control ib_resp" id="resp" name="resp" rows="9"></textarea>
                                <label for="api_header_resp">Response</label>
                            </div>

                        </div>
                    </div>

                </form>




            </div>
        </div>



    </div>



</div>


<?php $_smarty_tpl->_subTemplateRender("file:sections/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
