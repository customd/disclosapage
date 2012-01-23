<?php # An invisible form, which is used to pass information to the Disclosapage addon. ?>
<div style="display: none;">
	<? echo form_open('', array('id' => 'disclosapage')); ?>
		<? echo form_input("page_url", ''); ?>
		<? echo form_input("new_state", ''); ?>
	<? echo form_close(); ?>
</div>