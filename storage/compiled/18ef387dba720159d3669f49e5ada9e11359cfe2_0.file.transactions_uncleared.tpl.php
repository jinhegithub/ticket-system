<?php
/* Smarty version 3.1.33, created on 2018-12-10 19:28:49
  from '/Users/razib/Documents/valet/suite/ui/theme/default/transactions_uncleared.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5c0f04c183fc30_38731036',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '18ef387dba720159d3669f49e5ada9e11359cfe2' => 
    array (
      0 => '/Users/razib/Documents/valet/suite/ui/theme/default/transactions_uncleared.tpl',
      1 => 1544488126,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c0f04c183fc30_38731036 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13629753485c0f04c1838cb9_85459749', "style");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8988244145c0f04c1839d69_61268997', "content");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7727529575c0f04c183f3c5_05204523', "script");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['layouts_admin']->value));
}
/* {block "style"} */
class Block_13629753485c0f04c1838cb9_85459749 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'style' => 
  array (
    0 => 'Block_13629753485c0f04c1838cb9_85459749',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
ui/lib/footable/css/footable.core.min.css" />
<?php
}
}
/* {/block "style"} */
/* {block "content"} */
class Block_8988244145c0f04c1839d69_61268997 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_8988244145c0f04c1839d69_61268997',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


    <div class="row">
        <div class="col-md-12">

            <div class="panel">
                <div class="panel-body">

                    <form class="form-horizontal" method="post" action="">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="fa fa-search"></span>
                                    </div>
                                    <input type="text" name="name" id="foo_filter" class="form-control" placeholder="<?php echo $_smarty_tpl->tpl_vars['_L']->value['Search'];?>
..."/>

                                </div>
                            </div>

                        </div>
                    </form>

                    <table class="table table-bordered table-hover sys_table footable" data-filter="#foo_filter" data-page-size="50">
                        <thead>
                        <tr>
                            <th data-sort-ignore="true">&nbsp;</th>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['transactions']->value, 'transaction');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['transaction']->value) {
?>
                            <tr>
                                <td>
                                    <a class="btn btn-primary" href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
transactions/mark-cleared/<?php echo $_smarty_tpl->tpl_vars['transaction']->value->id;?>
" data-toggle="tooltip" data-placement="top" title="<?php echo $_smarty_tpl->tpl_vars['_L']->value['Mark as cleared'];?>
"><i class="fa fa-check"></i> </a>
                                </td>
                                <td  data-value="<?php echo $_smarty_tpl->tpl_vars['transaction']->value->id;?>
">
                                    <?php echo date($_smarty_tpl->tpl_vars['config']->value['df'],strtotime($_smarty_tpl->tpl_vars['transaction']->value->date));?>

                                </td>
                                <td><?php echo $_smarty_tpl->tpl_vars['transaction']->value->account;?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['transaction']->value->description;?>
</td>
                                <td><?php echo formatCurrency($_smarty_tpl->tpl_vars['transaction']->value->amount,$_smarty_tpl->tpl_vars['transaction']->value->currency_iso_code);?>
</td>

                            </tr>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="5">
                                <ul class="pagination">
                                </ul>
                            </td>
                        </tr>
                        </tfoot>

                    </table>
                </div>
            </div>

        </div>
    </div>

<?php
}
}
/* {/block "content"} */
/* {block "script"} */
class Block_7727529575c0f04c183f3c5_05204523 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'script' => 
  array (
    0 => 'Block_7727529575c0f04c183f3c5_05204523',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
ui/lib/footable/js/footable.all.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        $(function () {
            $('.footable').footable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    <?php echo '</script'; ?>
>
<?php
}
}
/* {/block "script"} */
}
