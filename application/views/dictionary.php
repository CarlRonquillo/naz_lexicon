<?php include('header.php'); ?>

	<div class="container">
		<div style ="width:80%;margin:auto;">
			<?php
				if(isset($_GET['search']))
				{
					$searchValue= $_GET['search'];
					$display = "block";
					$display2 = "none";
				}
				else
				{
				    $searchValue = NULL;
				    $display = "none";
				    $display2 = "block";
				}
			?>
			<div  id="home_header" style="display:<?php echo $display2 ?>;">
				<center><img id="home_header" alt="Brand" style="filter:invert(90%);width: 80px;" class="img-responsive" src="<?php echo base_url("resources/images/Nazarene Logo-White.png"); ?>">
					<h1 style="font-family:'Constantia';font-size: 60px;">NAZARENE LEXICON</h1>
					<hr><h2>What do you want to know?</h2>
				</center>
				<br>
			</div>
			<div class="row">
				<div class="autocomplete_container col-md-8">
					<?php echo form_open("home/search",['class' => 'form','method' => 'get','id' => 'frm_dictionary']); ?>
					<div class="input-group">
						<?php echo form_input(['type' => 'text','name' => 'search','id' => 'search', 'class' => 'form-control','autocomplete' => 'off', 'placeholder' => 'Lookup','value' => $searchValue,'autofocus' => true]); ?>
						<span class="input-group-btn">
							<?php echo form_button(['type' => 'submit','class' => 'btn btn-default','content' => "<span class='btn-label'><i class='glyphicon glyphicon-search'></i></span>"]); ?>
						</span>
					</div>
				</div>
			    <div class="form-group col-md-4">
			    	<div class="form-group">
				      <label for="Language" class="col-md-3 control-label">Target Language:</label>
				     	<div class="col-md-9">
				    		<?php
					    		if(isset($_GET['Language']))
					    		{
								    $LanguageID=htmlentities($_GET['Language']);
								}
								else
								{
				    				$LanguageID = 1;
								}

								$lists = array();
								foreach($languages as $record)
								{
									$lists[$record->LanguageID]=$record->Language;
								}
							echo form_dropdown(['name' => 'Language','id' => 'Language', 'class' => 'form-control',
								'autocomplete' => 'on', 'onchange' => "getval(this);"],$lists,$LanguageID); ?>
				    	</div>
			    	</div>
			    </div>
	    	</div>
	    	<div class="row">
		    	<div class="form-group col-md-12">
			    	<div class="radio">
						<label class="col-md-2">
				        	<?php echo form_radio('option', 'Exact Match', TRUE); ?>
				            Exact Match
				        </label>
				    </div>
				    <div class="radio">
				          <label class="col-md-5">
							<?php echo form_radio('option', 'Manual Term', False); ?>
				            Manual Term
						</label>
					</div>
			    </div>
		    </div>
		    <div style="display:<?php echo $display;?>">
			<div class="col-md-8">
				<div class="row">
			    	<div class="col-md-12">
			    		<p class="term">
				    		<?php if(isset($baseName['BaseName']))
					    		{
					    			echo $baseName['BaseName'];
					    		}
				    		?>
			    		</p>
			    	</div>
			    </div>
			<div class="panel-group" id="accordion">
				<?php if(count($records)): ?>
					<?php foreach($records as $record) { ?>
			 	<div class="panel">
				    <div class="panel-heading">
				      	<h4 class="panel-title">
				        	<a class="accordion-toggle collapsed" data-toggle="collapse" href="#<?php echo $record->TermID; ?>">
				          		<?php echo $record->TermName; ?> (part of speech) - <i><b><?php echo $record->Translation; ?></b></i>
				        	</a>
				     	</h4>
				    </div>
			    <div id="<?php echo $record->TermID; ?>" class="panel-collapse collapse">
			      <div class="panel-body">
			        <p><?php echo $record->GlossaryEntry; ?> 
			        <br><a>edit</a></p>

			        <p><i>Ref: </i><?php echo $record->DocumentReference; ?> 
			        <br><a>edit</a></p>

			        <p><i>Note: </i><?php echo $record->Note; ?> <br>
			        <a>edit</a><a>reply</a><a>add a new note</a></p>

			        <p><i>Context: </i><?php echo $record->ContextValue; ?> 
			        <br><a>edit</a></p>

			        <p><i>Common Usage: </i><?php echo $record->CommonUsage; ?> 
			        <br><a>edit</a></p>

			        <p><i>Core Term: </i><?php echo $record->CoreTerm; ?> 
			        <br><a>edit</a></p>

			        <p><i>Faux Amis: </i><?php echo $record->FauxAmis; ?> 
			        <br><a>edit</a></p>
			      </div>
			    </div>
			  </div>
			  <?php } ?>
			  	<?php if(count($records_more)): ?>
			  	<div class="text-container">
		    		<div class="text-content short-text">
		    			<div class="panel-group" id="accordion">
		    				<?php foreach($records_more as $record) { ?>
							 	<div class="panel">
								    <div class="panel-heading">
								      	<h4 class="panel-title">
								        	<a class="accordion-toggle collapsed" data-toggle="collapse" href="#<?php echo $record->TermID; ?>">
								          		<?php echo $record->TermName; ?> (part of speech) - <i><b><?php echo $record->Translation; ?></b></i>
								        	</a>
								     	</h4>
								    </div>
							    <div id="<?php echo $record->TermID; ?>" class="panel-collapse collapse">
							      <div class="panel-body">
							        <p><?php echo $record->GlossaryEntry; ?> 
							        <br><a>edit</a></p>
							        <p>REF: <?php echo $record->DocumentReference; ?> 
							        <br><a>edit</a></p>
							        <p>NOTE: <?php echo $record->Note; ?> <br>
							        <a>edit</a><a>reply</a><a>add a new note</a></p>
							      </div>
							    </div>
							  </div>
							<?php } ?>
						</div>
					</div>
					<div class="show-more">
						<a href="#">Show more</a>
					</div>
				</div>
				<?php endif; ?>
			</div>

			</div>
			<div class="see-also col-md-4">
			    <?php if(count($related_words_ID)): ?>
			    	<div class="row">
			    		<p>see also</p>
			    	</div>
			    	<ul>
			    		<?php foreach($related_words_ID as $word) { ?>
			    			<li>
			    				<?php echo anchor("home/search?search={$word['TermName']}&Language={$LanguageID}",$word['TermName']); ?>
			    			</li>
			    		<?php } ?>
			    	</ul>
			    <?php endif; ?>
			</div>
		    <?php else: echo $prompt; endif; ?>
			<?php echo form_close(); ?>
				<?php 
			    if($error = $this->session->flashdata('suggestion_response')):
			    {
			    ?>
			    <p class="text-success">
			        <span class ="glyphicon glyphicon-info-sign"></span>
			        <?php echo $error; ?>
			    </p>
			    <?php 
			    }
			        endif;
			    ?>
		    	<div class= "well" id="suggestTranslation" style="display:none;">
		    		<?php echo form_open("home/save_suggestTranslation/{$_GET['search']}/{$LanguageID}",['class' => 'form-horizontal','type' => 'POST']); ?>
					<div class="row">
                    	<div class="form-group">
                        	<label for="suggested_Translation" class="col-lg-2 control-label">Translation:</label>
                            	<div class="col-lg-7">
                                	<?php echo form_input(['type' => 'text','name' => 'suggested_Translation', 'class' => 'form-control','autocomplete' => 'off']); ?>
                                </div>
                                <div class="col-lg-2">
                                    <?php echo form_button(['type' => 'submit','content' => "Suggest", 'class' => 'btn btn-primary', "title" => "Save Term"]); ?>
                                </div>
                                <span><?php echo form_error('suggested_Translation') ?></span>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
				</div>
		</div>
		</div>
	</div>

	<?php 
		$terms = array();
		if(isset($termNames))
		{
			foreach($termNames as $term)
			{
				$terms[] = $term['TermName'];
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

	function showHide() {
	    var x = document.getElementById("suggestTranslation");
	    if (x.style.display === "none") {
	        x.style.display = "block";
	    } else {
	        x.style.display = "none";
	    }
	}
</script>