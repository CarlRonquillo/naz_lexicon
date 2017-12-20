<?php include('header.php'); ?>

	<?php 
        (null !== ($this->session->userdata('language_set')) ? $DefaultLanguage = $this->session->userdata('language_set') : $DefaultLanguage = 1);
    ?>

	<div id="container" style ="width:63%;margin:auto;">
		<div class="row">
			<div class="col-md-2">
				<?php echo anchor("home/BaseForm?baseForm=0&inflection=0","Add to Vocabulary",["class"=>"btn btn-primary btn-sm"]); ?>
			</div>
			<div class="autocomplete_container col-md-4">
				<?php echo form_open("home/Terms",['class' => 'form','method' => 'get','id' => 'frm_dictionary']); ?>
					<div class="input-group">
						<?php echo form_input(['type' => 'text','name' => 'search','id' => 'search', 'class' => 'form-control',
																'autocomplete' => 'off', 'placeholder' => 'Lookup'],$searched); ?>
						<span class="input-group-btn">
							<?php echo form_button(['type' => 'submit','class' => 'btn btn-default','content' => "<span class='btn-label'><i class='glyphicon glyphicon-search'></i></span>"]); ?>
						</span>
					</div>
			</div>
			<div class="form-group col-md-5">
			    <div class="form-group">
				    <label for="Language" class="col-md-3 control-label">Set target language:</label>
				    <div class="col-md-9">
				    	<?php
							$lists = array();
							foreach($languages as $record)
							{
								$lists[$record->LanguageID]=$record->Language;
							}
						echo form_dropdown(['name' => 'Language','id' => 'Language', 'class' => 'form-control',
								'autocomplete' => 'on', 'onchange' => "getval(this);"],$lists,$_GET['Language']); ?>
				    </div>
			    </div>
			</div>
			<div class="col-md-1">
				<?php echo anchor("home/Terms?Language={$this->session->userdata('language_set')}","<i class='glyphicon glyphicon-refresh'></i>",["class"=>"btn btn-success round",'title' => 'Clear Search']); ?>
			</div>			
			<div class="col-md-2"></div>
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
				      <th>Term</th>
				      <th>Translation</th>
				      <th>Base Forms</th>
				      <th>Glossary Entry</th>
				      <th>Reference</th>
				    </tr>
			 	</thead>
			 	<tbody>
				    <?php $count = 1;
				    		if(count($terms)): ?>
	  					<?php foreach($terms as $term) { ?>
			    			<tr>
			    				<td><?php echo $count++; ?></td>
			    				<td><?php echo anchor("home/Translation?baseForm={$term->BaseFormID}&term={$term->TermID}&translation={$term->TranslationID}",$term->TermName);?></td>
			    				<td>
			    					<?php if(!empty($term->Translation))
			    					{
			    						echo $term->Translation;
			    					}
			    					else
			    					{
			    						echo anchor("home/Translation?baseForm={$term->BaseFormID}&term={$term->TermID}&translation=0","<i>add translation here</i>");
			    					} ?>
			    				</td>
			    				<td><?php echo $term->Basenames; ?></td>
			    				<td><?php echo $term->GlossaryEntry; ?></td>
			    				<td><?php echo $term->DocumentReference; ?></td>
			    			</tr>
						<?php } else: ?>
							<tr>No Record/s Found! <?php echo (isset($prompt) ? $prompt : ''); ?></tr>
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