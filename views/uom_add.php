<div class="row-fluid">		
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon signal"></i><span class="break"></span><b>Add New UOM</b></h2>
			<div class="box-icon">
				<a href="uoms.php"><i class="halflings-icon backward"></i> Back to List</a>
			</div>
		</div>
		<div class="box-content">
			<form class="form-horizontal" method="POST">
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="txtUOMCode">UOM Code</label>
						<div class="controls">
							<input class="input-xlarge" name="txtUOMCode" id="txtUOMCode" disabled type="text" value="[SYSTEM GENERATED]" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="txtDescription">Description</label>
						<div class="controls">
							<input class="input-xlarge" name="txtDescription" id="txtDescription" type="text" placeholder="Description Here..." />
						</div>
					</div>
					<div class="form-actions">
						<input type="submit" class="btn btn-primary" value="Save changes" />
						<a href="uom_add.php" class="btn">Cancel</a>
					</div>
				</fieldset>
				<input type="hidden" name="save" id="save" value="1" />
			</form>
		</div>
	</div>
</div>