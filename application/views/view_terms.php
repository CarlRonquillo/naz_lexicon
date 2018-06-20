<?php include('header.php'); ?>

	<?php 
        (null !== ($this->session->userdata('language_set')) ? $DefaultLanguage = $this->session->userdata('language_set') : $DefaultLanguage = 1);
    ?>

	<div id="container" style ="width:55%;margin:auto;">
		<div class="row">
			<br>
			<div class="autocomplete_container col-md-10 col-md-offset-1">
				<?php echo form_open("home/Terms",['class' => 'form','method' => 'get','id' => 'frm_dictionary']); ?>
					<div class="input-group">
						<?php echo form_input(['type' => 'text','name' => 'search','id' => 'search', 'class' => 'form-control',
																'autocomplete' => 'off', 'placeholder' => 'Lookup'],$searched); ?>
						<span class="input-group-btn">
							<?php echo form_button(['type' => 'submit','class' => 'btn btn-default','content' => "<span class='btn-label'><i class='glyphicon glyphicon-search'></i></span>"]); ?>
						</span>
					</div>
			</div>
		</div>
		<br>
		<div class="row col-md-offset-1">
			<div class="form-group col-md-7">
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
								'autocomplete' => 'on', 'onchange' => "insertParam(this,'Language');"],$lists,$_GET['Language']); ?>
				    </div>
			    </div>
			</div>
			<div class="col-md-1">
				<?php echo anchor("home/Terms?Language={$this->session->userdata('language_set')}","<i class='glyphicon glyphicon-refresh'></i>",["class"=>"btn btn-success round",'title' => 'Clear Search']); ?>
			</div>
			<div class="col-md-2">
				<?php echo anchor("home/Term?term=0","Add New Term",["class"=>"btn btn-primary btn-sm"]); ?>
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
				      <th>Term</th>
				      <th>Translation</th>
				    </tr>
			 	</thead>
			 	<tbody>
				    <?php $count = 1;
				    		if(count($terms)): ?>
	  					<?php foreach($terms as $term) { ?>
			    			<tr>
			    				<td><?php echo $count++; ?></td>
			    				<td><?php echo anchor("home/Term?term={$term->TermID}",$term->TermName);?></td>
			    				<td>
			    					<?php if(!empty($term->Translation))
			    					{
			    						echo anchor("home/Translation?term={$term->TermID}&translation={$term->TranslationID}","<b>".$term->Translation."</b>");
			    					}
			    					else
			    					{
			    						echo anchor("home/Translation?term={$term->TermID}&translation=0","<i>-add translation here-</i>");
			    					} ?>
			    				</td>
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

	function insertParam(sel, variable)
		{
		    key = encodeURI(variable);
		    value = encodeURI(sel.value);

            var kvp = document.location.search.substr(1).split('&');

            if(key == 'translation')
            {
                value = $(sel).data("value");
            }

            var i=kvp.length; var x; while(i--) 
            {
                x = kvp[i].split('=');

                if (x[0]==key)
                {
                    x[1] = value;
                    kvp[i] = x.join('=');
                    break;
                }
            }

            /*if(key != 'term')
            {
                key2 = 'term';                      
                value2 = <?php echo $first_TermID; ?>;

                //term change!!!!!!!!!!!!
                var i=kvp.length; var x; while(i--) 
                {
                    x = kvp[i].split('=');

                    if (x[0]==key2)
                    {
                        x[1] = value2;
                        kvp[i] = x.join('=');
                        break;
                    }
                }
            }*/

		    if(i<0) {kvp[kvp.length] = [key,value].join('=');}

		    //this will reload the page, it's likely better to store this until finished
		    document.location.search = kvp.join('&');
		}
</script>