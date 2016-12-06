<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('freedays_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($freedays->id) ? $freedays->id : '';

?>
<div class='admin-box'>
    <h3>freedays</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('owner') ? ' error' : ''; ?>">
                <?php echo form_label(lang('freedays_field_owner') . lang('bf_form_label_required'), 'owner', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='owner' type='text' required='required' name='owner' maxlength='64' value="<?php echo set_value('owner', isset($freedays->owner) ? $freedays->owner : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('owner'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('baylocation') ? ' error' : ''; ?>">
                <?php echo form_label(lang('freedays_field_baylocation') . lang('bf_form_label_required'), 'baylocation', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='baylocation' type='text' required='required' name='baylocation' maxlength='64' value="<?php echo set_value('baylocation', isset($freedays->baylocation) ? $freedays->baylocation : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('baylocation'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('datefree') ? ' error' : ''; ?>">
                <?php echo form_label(lang('freedays_field_datefree'), 'datefree', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='datefree' type='text' name='datefree' maxlength='30' value="<?php echo set_value('datefree', isset($freedays->datefree) ? $freedays->datefree : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('datefree'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('freedays_action_create'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/settings/freedays', lang('freedays_cancel'), 'class="btn btn-warning"'); ?>
            
        </fieldset>
    <?php echo form_close(); ?>
</div>