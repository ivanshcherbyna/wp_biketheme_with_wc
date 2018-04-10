<?php $container_class = ($this->field['accordion'])? ' accordion' : ''; ?>
<div class="ll-repeateble-container <?php echo $container_class; ?>" data-fixed="<?php echo $this->field['fixed']; ?>" data-max="<?php echo $this->field['max']; ?>">
	<div class="repeatable-content" data-name="<?php echo $this->parent->args['opt_name'] . '[' . $this->field['id'] . ']' ?>">
		<?php
		if(!$this->field['fixed'] && $this->field['max'] && count($this->value) > $this->field['max']) {
			$this->value = array_slice( $this->value, 0, (int)$this->field['max'] );
		}

		if(
			!$this->field['fixed'] && !$this->field['max'] ||
			$this->field['fixed'] > 0 && count($this->value) == $this->field['fixed'] ||
			$this->field['max'] && count($this->value) <= $this->field['max']
		) {

			if(!empty($this->value)) {
				foreach ($this->value as $key => $value) {
					$this->ll_render_fields($key);
				}
			}
			if(count($this->value) == 0 || $this->value == ''){
				$key = 0;
			} else {
				$key = max(array_keys($this->value)) + 1;
			}

		} else {
			for ($i=0; $i < (int) $this->field['fixed']; $i++) { 
				$this->ll_render_fields($i);
			}
		}

		if(
			($this->field['min'] > 0 &&
				(!$this->field['max'] || $this->field['max'] > $this->field['min']) &&
				(count($this->value) < $this->field['min']) || $this->value == '') && 
			!$this->field['fixed']
		) {
			if($this->value == '')
				$req = $this->field['min'];
			else
				$req = $this->field['min'] - count($this->value);
			
			for ($i=0; $i < $req; $i++) { 
				$this->ll_render_fields($key);
				$key++;
			}
		}

		?>
	</div>
<?php $this->_renter_template($key); ?>
<?php if(!$this->field['fixed']) { 
	$disabled = ($this->field['max'] && count($this->value) >= (int) $this->field['max'])? 'disabled="disabled"' : '';
?>

	<div class="repeatable-controls button-controls">
		<button class="button button-primary add-button" <?php echo $disabled; ?>><?php echo $this->field['add_button'] ?></button>
	</div>
<?php } ?>
</div>