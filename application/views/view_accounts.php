<?php include('header.php'); ?>

	<div id="container" style ="width:40%;margin:auto;">
		<div class="row">
			<div class="col-md-6">
				<?php echo form_open("home/Terms",['class' => 'form','method' => 'get','id' => 'frm_dictionary']); ?>
					<div class="input-group">
						<?php echo form_input(['type' => 'text','name' => 'search','id' => 'search', 'class' => 'form-control',
																'autocomplete' => 'off', 'placeholder' => 'Lookup']); ?>
						<span class="input-group-btn">
							<?php echo form_button(['type' => 'submit','class' => 'btn btn-default','content' => "<span class='btn-label'><i class='glyphicon glyphicon-search'></i></span>"]); ?>
						</span>
					</div>
			</div>		
			<?php echo form_close(); ?>
		</div>
		<br>
		<div class="row">
			<?php 
            if($error = $this->session->flashdata('response')):
	        {                       
		        ?>
		        <p class="text-success">
		            <span class ="glyphicon glyphicon-info-sign"></span>
		            <?php echo $error; ?>
		        </p>
		        <?php 
	        }
	            endif
	        ?>
			<table class="table table-striped table-hover ">
				<thead>
				    <tr>
				      <th>#</th>
				      <th>Username</th>
				      <th>First Name</th>
				      <th>Last Name</th>
				      <th></th>
				    </tr>
			 	</thead>
			 	<tbody>
				    <?php $count = 1;
				    		if(count($accounts)): ?>
	  					<?php foreach($accounts as $account) { ?>
			    			<tr>
			    				<td><?php echo $count++; ?></td>
			    				<td><?php echo anchor("home/EditAccount/{$account->ID}",$account->Username);?></td>
			    				<td><?php echo $account->FirstName; ?></td>
			    				<td><?php echo $account->LastName; ?></td>
			    				<td><?php echo anchor("home/delete_account/$account->ID}","<i class='glyphicon glyphicon-remove'></i>",["class"=>"btn btn-danger btn-xs round","onclick" => "return confirm('Are you sure you want delete?')"]); ?></td>
			    			</tr>
						<?php } else: ?>
							<tr>No Record/s Found! <?php echo (isset($prompt) ? $prompt : ''); ?></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

<?php include('footer.php'); ?>