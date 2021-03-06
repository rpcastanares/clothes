<div class="row-fluid">		
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon cog"></i><span class="break"></span><b>Add New Control No</b></h2>
			<div class="box-icon">
				<a href="controlnos.php"><i class="halflings-icon backward"></i> Back to List</a>
			</div>
		</div>
		<div class="box-content">
			<form class="form-horizontal" method="POST">
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="txtDescription">Description</label>
						<div class="controls">
							<input class="input-xlarge" name="txtDescription" id="txtDescription" type="text" placeholder="Description Here..." />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="txtType">Control Type</label>
						<div class="controls">
							<input class="input-xlarge" name="txtType" id="txtType" type="text" placeholder="Control Type Here..." />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="txtCode">Control Code</label>
						<div class="controls">
							<input class="input-xlarge" name="txtCode" id="txtCode" type="text" placeholder="Control Code Here..." />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="txtNoOfDigit">No. Of Digit</label>
						<div class="controls">
							<input class="input-xlarge" name="txtNoOfDigit" id="txtNoOfDigit" type="text" placeholder="No. Of Digit Here..." />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="txtRemarks">Remarks</label>
						<div class="controls">
							<textarea name="txtRemarks" id="txtRemarks" style="resize: none; width: 270px;"></textarea>
						</div>
					</div>
					<div class="form-actions">
						<input type="submit" class="btn btn-primary" value="Save changes" />
						<a href="controlno_add.php" class="btn">Cancel</a>
					</div>
				</fieldset>
				<input type="hidden" name="save" id="save" value="1" />
			</form>
		</div>

	</div>
</div>