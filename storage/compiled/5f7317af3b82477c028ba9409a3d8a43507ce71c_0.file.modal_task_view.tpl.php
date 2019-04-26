<?php
/* Smarty version 3.1.33, created on 2019-04-26 13:48:49
  from '/home/pscope/public_html/ui/theme/default/modal_task_view.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5cc2bee9457b22_31281429',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5f7317af3b82477c028ba9409a3d8a43507ce71c' => 
    array (
      0 => '/home/pscope/public_html/ui/theme/default/modal_task_view.tpl',
      1 => 1553599355,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5cc2bee9457b22_31281429 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>
        <?php echo $_smarty_tpl->tpl_vars['d']->value->title;?>

    </h3>
</div>
<div class="modal-body" style="font-size: 14px;">

    <div class="row">


        <div class="col-md-12">
            <a href="javascript:void(0)" class="btn btn-danger c_delete" id="d_<?php echo $_smarty_tpl->tpl_vars['d']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['_L']->value['Delete'];?>
</a>
            <a href="javascript:void(0)" class="btn btn-warning c_edit" id="e_<?php echo $_smarty_tpl->tpl_vars['d']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['_L']->value['Edit'];?>
</a>
            <hr>



            <div class="row">
                <div class="col-md-6">
                    <h4><?php echo $_smarty_tpl->tpl_vars['_L']->value['Description'];?>
</h4>
                    <hr>
                    <?php if ($_smarty_tpl->tpl_vars['d']->value->description == '') {?>
                        <p><?php echo $_smarty_tpl->tpl_vars['_L']->value['No Data Available'];?>
</p>
                    <?php } else { ?>
                        <?php echo $_smarty_tpl->tpl_vars['d']->value->description;?>

                    <?php }?>
                </div>
                <div class="col-md-6 text-right">

                    <p><strong><?php echo $_smarty_tpl->tpl_vars['_L']->value['Due Date'];?>
:</strong> <?php echo date($_smarty_tpl->tpl_vars['config']->value['df'],strtotime($_smarty_tpl->tpl_vars['d']->value->due_date));?>
</p>
                    <?php if ($_smarty_tpl->tpl_vars['contact']->value) {?>
                        <p><strong>Related customer:</strong> <a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
contacts/view/<?php echo $_smarty_tpl->tpl_vars['contact']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['contact']->value->account;?>
</a></p>
                    <?php }?>

                    <?php if ($_smarty_tpl->tpl_vars['ticket']->value) {?>
                        <p><strong>Ticket:</strong> <a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
tickets/admin/view/<?php echo $_smarty_tpl->tpl_vars['ticket']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['ticket']->value->tid;?>
</a></p>
                    <?php }?>

                </div>
            </div>



        </div>



    </div>

</div>
<div class="modal-footer">

    <input type="hidden" id="task_id" name="task_id" value="<?php echo $_smarty_tpl->tpl_vars['d']->value->id;?>
">
    <button type="button" data-dismiss="modal" class="btn btn-danger"><?php echo $_smarty_tpl->tpl_vars['_L']->value['Close'];?>
</button>
</div><?php }
}
