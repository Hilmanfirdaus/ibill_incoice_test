<?php
/* Smarty version 3.1.30, created on 2018-04-30 10:35:49
  from "C:\xampp\htdocs\root\application\plugins\notes\views\list.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5ae729c5de3530_99929048',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '47e07babe8468122ae797784f0b899b4891971c0' => 
    array (
      0 => 'C:\\xampp\\htdocs\\root\\application\\plugins\\notes\\views\\list.tpl',
      1 => 1474949471,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ae729c5de3530_99929048 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="row">

        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
notes/init/add/" class="btn btn-primary mb-md"><i class="fa fa-plus"></i> Add New Note </a>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th width="70%">Title</th>
                            <th>Manage</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['notes']->value, 'note');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['note']->value) {
?>

                            <tr>

                                <td><?php echo $_smarty_tpl->tpl_vars['note']->value['id'];?>
</td>

                                <td><a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
notes/init/edit/<?php echo $_smarty_tpl->tpl_vars['note']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['note']->value['title'];?>
</a> </td>

                                <td>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
notes/init/edit/<?php echo $_smarty_tpl->tpl_vars['note']->value['id'];?>
/" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
notes/init/delete/<?php echo $_smarty_tpl->tpl_vars['note']->value['id'];?>
/" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete </a>
                                </td>
                            </tr>

                            <?php
}
} else {
?>


                            <tr>

                                <td colspan="3">No Notes Found</td>

                            </tr>

                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>


                        </tbody>
                    </table>

                </div>
            </div>
        </div>


</div><?php }
}
