<?php include('header.php'); ?>

	<div id="container" style ="width:63%;margin:auto;">
		<div class="row">
			<div class="col-md-2">
				<?php echo anchor("home/BaseForm?baseForm=1&inflection=0","Add to Vocabulary",["class"=>"btn btn-primary btn-sm"]); ?>
			</div>
			<div class="autocomplete_container col-md-8">
				<?php echo form_open("home/Terms",['class' => 'form','method' => 'get','id' => 'frm_dictionary']); ?>
					<div class="input-group">
						<?php echo form_input(['type' => 'text','name' => 'search','id' => 'search', 'class' => 'form-control',
																'autocomplete' => 'off', 'placeholder' => 'Lookup'],$searched); ?>
						<span class="input-group-btn">
							<?php echo form_button(['type' => 'submit','class' => 'btn btn-default','content' => "<span class='btn-label'><i class='glyphicon glyphicon-search'></i></span>"]); ?>
						</span>
					</div>
				<?php echo form_close(); ?>
			</div>
			<div class="col-md-2">
				<?php echo anchor("home/Terms","<i class='glyphicon glyphicon-refresh'></i>",["class"=>"btn btn-success round",'title' => 'Clear Search']); ?>
			</div>			
			<div class="col-md-2"></div>
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
				      <th>Term</th>
				      <th>Base Form</th>
				      <th>Glossary Entry</th>
				      <th>Reference</th>
				      <th></th>
				    </tr>
			 	</thead>
			 	<tbody>
				    <?php $count = 1;
				    		if(count($terms)): ?>
	  					<?php foreach($terms as $term) { ?>
			    			<tr>
			    				<td><?php echo $count++; ?></td>
			    				<td><?php echo anchor("home/Term?baseForm={$term->BaseFormID}&term={$term->TermID}",$term->TermName);?></td>
			    				<td><?php echo $term->BaseName; ?></td>
			    				<td><?php echo $term->GlossaryEntry; ?></td>
			    				<td><?php echo $term->DocumentReference; ?></td>
			    				<td><?php echo anchor("home/delete_term/{$term->TermID}/term/TermID/1","<i class='glyphicon glyphicon-remove'></i>",["class"=>"btn btn-danger btn-xs round","onclick" => "return confirm('Are you sure you want delete?')"]); ?></td>
			    			</tr>
						<?php } else: ?>
							<tr>No Records Found!</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

	<?php 
		$terms = array();
		if(isset($termNames))
		{
			foreach($termNames as $termName)
			{
				$terms[] = $termName['TermName'];
			}
		}
	?>

<?php include('footer.php'); ?>

<script>
	$( function() {
		var availableTags = <?php if(isset($terms)) {echo json_encode($terms);} ?>;
		$( "#search" ).autocomplete({
		    source: availableTags
		});
	} );
</script>