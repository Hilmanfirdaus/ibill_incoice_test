<?php
/* Smarty version 3.1.30, created on 2018-04-30 22:11:05
  from "C:\xampp\htdocs\root\ui\theme\ibilling\calendar.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5ae73209e83e29_29638770',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c35e04746e5d1ddea79ff5adf2881d3c33a0b45c' => 
    array (
      0 => 'C:\\xampp\\htdocs\\root\\ui\\theme\\ibilling\\calendar.tpl',
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
function content_5ae73209e83e29_29638770 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:sections/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>



<div class="row">



    <div class="col-md-12">



        <div class="panel panel-default" style="min-height: 400px;" id="calendar_wrap">

            <div class="panel-body">


                <div id="calendar"></div>


            </div>
        </div>
    </div>



</div>

<div id="modal_add_event" class="modal fade-scale" tabindex="-1" data-width="800" style="display: none;">
    <form id="ib_modal_form">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="modal_title"><?php echo $_smarty_tpl->tpl_vars['_L']->value['Add Event'];?>
</h4>
    </div>
    <div class="modal-body">
        <div class="row">





                <div class="form-group col-md-12">
                    <label for="title"><?php echo $_smarty_tpl->tpl_vars['_L']->value['Event Name'];?>
</label>
                    <input type="text" class="form-control" id="title" name="title" value="" required>
                </div>



                <div class="form-group col-md-6">
                    <label for="start"><?php echo $_smarty_tpl->tpl_vars['_L']->value['Start Date'];?>
</label>
                    <input type="text" class="form-control datepicker" id="start" placeholder="Select Date" name="start" value="<?php echo $_smarty_tpl->tpl_vars['mdate']->value;?>
">
                </div>

                <div class="form-group col-md-6" id="start_time_div">
                    <label for="start_time"><?php echo $_smarty_tpl->tpl_vars['_L']->value['Start Time'];?>
</label>
                    <div class="input-group clockpicker">

                        <input type="text" id="start_time" name="start_time" class="form-control" value="09:30">
                        <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
                    </div>
                </div>



                <div class="form-group col-md-6">
                    <label for="end"><?php echo $_smarty_tpl->tpl_vars['_L']->value['End Date'];?>
</label>
                    <input type="text" class="form-control datepicker" id="end" name="end" value="">
                </div>

                <div class="form-group col-md-6" id="end_time_div">
                    <label for="end_time"><?php echo $_smarty_tpl->tpl_vars['_L']->value['End Time'];?>
</label>
                    <div class="input-group clockpicker">

                        <input type="text" class="form-control" id="end_time" name="end_time" value="11:30">
                        <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
                    </div>
                </div>



                <div class="form-group col-md-12">

                    <input class="i-checks" type="checkbox" name="all_day_event" value="yes" id="all_day_event">
                    <label for="all_day_event"><?php echo $_smarty_tpl->tpl_vars['_L']->value['All day event'];?>
</label>


                </div>


            <div class="form-group col-md-12">
                <label for="color"><?php echo $_smarty_tpl->tpl_vars['_L']->value['Color'];?>
</label>
                <input type="text" class="form-control color" id="color" name="color" value="#2196f3">
            </div>


                <div class="form-group col-md-12">
                    <label for="description"><?php echo $_smarty_tpl->tpl_vars['_L']->value['Description'];?>
</label>
                    <textarea id="description" name="description" class="form-control" rows="5"></textarea>
                </div>



<input type="hidden" id="ib_act" name="ib_act" value="create">
<input type="hidden" id="event_id" name="event_id" value="0">






        </div>
    </div>
    <div class="modal-footer">
        <a href="#" id="btn_del_event" class="btn btn-danger"><i class="fa fa-trash"></i> <?php echo $_smarty_tpl->tpl_vars['_L']->value['Delete'];?>
</a>
        <button type="button" data-dismiss="modal" class="btn btn-warning"><?php echo $_smarty_tpl->tpl_vars['_L']->value['Close'];?>
</button>
        <button type="submit" id="btn_save_event" class="btn btn-primary"><?php echo $_smarty_tpl->tpl_vars['_L']->value['Submit'];?>
</button>
    </div>
    </form>
</div>

<div class="md-fab-wrapper">
    <a class="md-fab md-fab-primary waves-effect waves-light add_event" href="#">
        <i class="fa fa-plus"></i>
    </a>
</div>

<?php $_smarty_tpl->_subTemplateRender("file:sections/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
