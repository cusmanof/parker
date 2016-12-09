<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('locations_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($locations->id) ? $locations->id : '';

?>
<div class='admin-box'>
    <h3>locations</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('location') ? ' error' : ''; ?>">
                <?php echo form_label(lang('locations_field_location') . lang('bf_form_label_required'), 'location', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='location' type='text' required='required' name='location' maxlength='64' value="<?php echo set_value('location', isset($locations->location) ? $locations->location : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('location'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('test1') ? ' error' : ''; ?>">
                <?php echo form_label(lang('locations_field_test1'), 'test1', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='test1' type='text' name='test1' maxlength='64' value="<?php echo set_value('test1', isset($locations->test1) ? $locations->test1 : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('test1'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('test2') ? ' error' : ''; ?>">
                <?php echo form_label(lang('locations_field_test2'), 'test2', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='test2' type='text' name='test2' maxlength='64' value="<?php echo set_value('test2', isset($locations->test2) ? $locations->test2 : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('test2'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('locations_action_create'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/settings/locations', lang('locations_cancel'), 'class="btn btn-warning"'); ?>
            
        </fieldset>
    <?php echo form_close(); ?>
</div>