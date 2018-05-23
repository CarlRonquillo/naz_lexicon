<?php include('header.php'); ?>

	<?php 
        (null !== ($this->session->userdata('language_set')) ? $DefaultLanguage = $this->session->userdata('language_set') : $DefaultLanguage = 1);
    ?>

	<div class="container">
		<div style ="width:90%;margin:auto;">
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
				<div class="autocomplete_container col-md-7">
					<?php echo form_open("home/search",['class' => 'form','method' => 'get','id' => 'frm_dictionary']); ?>
					<div class="input-group">
						<?php echo form_input(['type' => 'text','name' => 'search','id' => 'search', 'class' => 'form-control','autocomplete' => 'off', 'placeholder' => 'Lookup','value' => $searchValue,'autofocus' => true]); ?>
						<span class="input-group-btn">
							<?php echo form_button(['type' => 'submit','class' => 'btn btn-default','content' => "<span class='btn-label'><i class='glyphicon glyphicon-search'></i></span>"]); ?>
						</span>
					</div>
				</div>
			    <div class="form-group col-md-5">
			    	<div class="form-group">
				      <label for="Language" class="col-md-4 control-label">Set target language:</label>
				     	<div class="col-md-8">
				    		<?php
								$lists = array();
								foreach($languages as $record)
								{
									$lists[$record->LanguageID]=$record->Language;
								}
							echo form_dropdown(['name' => 'Language','id' => 'Language', 'class' => 'form-control',
								'autocomplete' => 'on', 'onchange' => "insertParam(this,'Language');"],$lists,(isset($_GET['Language']) ? $_GET['Language'] : $DefaultLanguage)); ?>
				    	</div>
			    	</div>
			    </div>
	    	</div>
	    	<!--<div class="row">
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
		    </div>-->
		    <div style="display:<?php echo $display;?>">
				<div class="col-md-7">
					<?php if(count($_record)): ?>
						<?php foreach($_record as $record) { ?>
				 	<div class="panel">
					    <div class="panel-heading">
					      	<h3>
					          	<?php echo $record->TermName; ?> - <i><b><?php echo $record->Translation; ?></b></i>
					     	</h3>
					    </div>
				    <div id="<?php echo $record->TranslationID; ?>">
				      <div class="panel-body">
				      	<?php if(!empty($record->GlossaryEntry)) { ?>
					        <p><?php echo $record->GlossaryEntry; ?></p>
				        <?php } ?>

				        <?php if(!empty($record->Title)) { ?>
					        <p><?php echo $record->Title; ?></p>
				        <?php } ?>

				        <?php if(!empty($record->DocumentReference)) { ?>
					        <p><i>Ref: </i><?php echo $record->DocumentReference; ?></p>
				        <?php } ?>

				        <?php if(!empty($record->ContextValue)) { ?>
					        <p><i>Context: </i><?php echo $record->ContextValue; ?></p>
				        <?php } ?>

				        <?php if(!empty($record->FauxAmis)) { ?>
					        <p><i>Faux Amis: </i><?php echo $record->FauxAmis; ?></p>
				        <?php } ?>

				        <?php if(!empty($record->Note)) { ?>
					        <p><i>Note: </i><?php echo $record->Note; ?> <br></p>
				        <?php } ?>
					        <?php echo anchor("home/Suggest/{$record->TermID}",'edit',['id' => 'adminOnly']); ?>
				      </div>
				    </div>
				  </div>
				  <?php } ?>
				  	<br><br>
					<div class="see-also">
					    <?php if(count($related_words_ID)): ?>
					    	<div class="row">
					    		<p>see also</p>
					    	</div>
					    		<?php foreach($related_words_ID as $word) { ?>
					    			<?php echo anchor("home/search?search={$word['TermName']}&Language={$DefaultLanguage}",$word['TermName']). ', '; ?>
					    		<?php } ?>
					    <?php endif; ?>
					</div>

				</div>

			</div>
			<?php if(count($records)): ?>
			<div class="well col-md-5">
				<div id="term-results">
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
					<?php foreach($records as $record) { ?>
			 	<div class="panel">
				    <div class="panel-heading">
				      	<h4 class="panel-title">
				        	<a class="accordion-toggle collapsed" data-toggle="collapse" href="#<?php echo $record->TranslationID; ?>">
				          		<?php echo $record->TermName; ?> - <i><b><?php echo $record->Translation; ?></b></i>
				        	</a>
				     	</h4>
				    </div>
			    <div id="<?php echo $record->TranslationID; ?>" class="panel-collapse collapse">
			      <div class="panel-body">
			      	<?php if(!empty($record->GlossaryEntry)) { ?>
				        <p><?php echo $record->GlossaryEntry; ?></p>
			        <?php } ?>

			        <?php if(!empty($record->Title)) { ?>
				        <p><?php echo $record->Title; ?></p>
			        <?php } ?>

			        <?php if(!empty($record->DocumentReference)) { ?>
				        <p><i>Ref: </i><?php echo $record->DocumentReference; ?></p>
			        <?php } ?>

			        <?php if(!empty($record->ContextValue)) { ?>
				        <p><i>Context: </i><?php echo $record->ContextValue; ?></p>
			        <?php } ?>

			        <?php if(!empty($record->FauxAmis)) { ?>
				        <p><i>Faux Amis: </i><?php echo $record->FauxAmis; ?></p>
			        <?php } ?>

			        <?php if(!empty($record->Note)) { ?>
				        <p><i>Note: </i><?php echo $record->Note; ?> <br></p>
			        <?php } ?>
				        <?php echo anchor("home/Suggest/{$record->TermID}",'edit',['id' => 'adminOnly']); ?>
			      </div>
			    </div>
			  </div>
			  <?php } ?>
			  <?php endif; ?>
			  	<?php if(count($records_more)): ?>
			  	<div class="text-container">
		    		<div class="text-content short-text">
		    			<div class="panel-group" id="accordion">
		    				<?php foreach($records_more as $record) { ?>
							 	<div class="panel">
								    <div class="panel-heading">
								      	<h4 class="panel-title">
								        	<a class="accordion-toggle collapsed" data-toggle="collapse" href="#<?php echo $record->TranslationID; ?>">
								          		<?php echo $record->TermName; ?> - <i><b><?php echo $record->Translation; ?></b></i>
								        	</a>
								     	</h4>
								    </div>
							    <div id="<?php echo $record->TranslationID; ?>" class="panel-collapse collapse">
							      <div class="panel-body">
							      	<?php if(!empty($record->GlossaryEntry)) { ?>
								        <p><?php echo $record->GlossaryEntry; ?></p>
							        <?php } ?>

							        <?php if(!empty($record->Title)) { ?>
								        <p><?php echo $record->Title; ?></p>
							        <?php } ?>

							        <?php if(!empty($record->DocumentReference)) { ?>
								        <p><i>Ref: </i><?php echo $record->DocumentReference; ?></p>
							        <?php } ?>

							        <?php if(!empty($record->ContextValue)) { ?>
								        <p><i>Context: </i><?php echo $record->ContextValue; ?></p>
							        <?php } ?>

							        <?php if(!empty($record->FauxAmis)) { ?>
								        <p><i>Faux Amis: </i><?php echo $record->FauxAmis; ?></p>
							        <?php } ?>

							        <?php if(!empty($record->Note)) { ?>
								        <p><i>Note: </i><?php echo $record->Note; ?> <br></p>
							        <?php } ?>
							        <?php echo anchor("home/Suggest/{$record->TermID}",'edit',['id' => 'adminOnly']); ?>
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
				<div>
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
		    		<?php echo form_open("home/save_suggestTranslation/{$_GET['search']}/{$DefaultLanguage}",['class' => 'form-horizontal','type' => 'POST']); ?>
		    		<div class="row">
                    	<div class="form-group">
                        	<label for="SuggestedBy" class="col-lg-2 control-label">Full Name</label>
                            	<div class="col-lg-7">
                                	<?php echo form_input(['type' => 'text','name' => 'SuggestedBy', 'class' => 'form-control','autocomplete' => 'off']); ?>
                                </div>
                                <span><?php echo form_error('SuggestedBy') ?></span>
                        </div>
                    </div>
					<div class="row">
                    	<div class="form-group">
                        	<label for="suggested_Translation" class="col-lg-2 control-label">Translation</label>
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

	$( document ).ready(function() {
		var x = document.getElementsByName("adminOnly");
	    var admin = <?php echo (null!==($this->session->userdata('Username')) ? '1' : '0' )?>;
	    var i;
		for (i = 0; i < x.length; i++) {
			if(admin)
			{
		    	x[i].style.display = "block";
			}
			else
			{
				x[i].style.display = "none";
			}
		}
	});

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