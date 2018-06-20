<?php include('header.php'); ?>

<div class="container">
        <?php
            if(count($record)>0)
            {
                $Translation = $record->Translation;
                $FauxAmis = $record->FauxAmis;
                $Comment = $record->Comment;
                $TranslationID = $record->FKLanguageID;
            }
            else
            {
                $Translation = "";
                $FauxAmis = "";
                $Comment = "";
                $TranslationID = $this->session->userdata('language_set');
            }

            if($_GET['translation'] == 0)
            {
                $header = "Add Translation for <b>".$term->TermName."</b>";
                $display ="inline";
            }
            else
            {
                $header = "Update <b>".$term->TermName."</b> (".$record->Language.")";
                $display ="none";
            }
        ?>
	<center><h2 class=""><?php echo $header; ?></h2></center><hr>
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
	<div class="well col-md-5 col-center-block">
		<?php echo form_open("home/save_Translation/{$_GET['translation']}/{$_GET['term']}",['class' => 'form-horizontal']);?>
			<!--<div class="row">
            	<div class="form-group">
                    <label for="BaseForm" class="col-lg-3 control-label">Base Form</label>
                    <div class="col-lg-5">
                    <?php
                        $baseForms = array('');
                        if(count($Base_Names)>0)
                        {
	                        foreach($Base_Names as $Base_Name)
	                        {
	                        $baseForms[$Base_Name->BaseFormID]=$Base_Name->BaseName;
	                        }
	                        echo form_dropdown(['class' => 'form-control', 'autocomplete' => 'off','onchange' => "insertParam(this,'baseForm');"],$baseForms,$_GET['baseForm']);
                        }
                        ?>
                    </div>
                    <span><?php echo form_error('BaseForm') ?></span>
                </div>
            </div>
            <div class="row">
            	<div class="form-group">
                    <label for="FKTermID" class="col-lg-3 control-label">Term</label>
                    <div class="col-lg-5">
                    <?php
                        $_term = array('');
                        if(count($Terms)>0)
                        {
                        	foreach($Terms as $Term)
                        	{
                        		$_term[$Term->TermID]=$Term->TermName;
                        	}
                        }
                        echo form_dropdown(['id' => 'FKTermID','name' => 'FKTermID', 'class' => 'form-control',
                                                                'autocomplete' => 'off','onchange' => "insertParam(this,'term');"],$_term,$_GET['term']);
                        ?>
                    </div>
                    <span><?php echo form_error('FKTermID') ?></span>
                </div>
            </div>-->
            <div class="row" style="display:<?php echo $display; ?>;">
            	<div class="form-group">
                    <label for="FKLanguageID" class="col-lg-3 control-label">Language</label>
                    <div class="col-lg-8">
                    <?php
						$lists = array();
						if(count($languages)>0)
                        {
                        	foreach($languages as $record)
							{
								$lists[$record->LanguageID]=$record->Language;
							}
							echo form_dropdown(['name' => 'FKLanguageID','id' => 'FKLanguageID', 'class' => 'form-control',
									'autocomplete' => 'on'],$lists,$TranslationID); 
                        }
						?>
                    </div>
                    <span><?php echo form_error('FKLanguageID') ?></span>
                </div>
            </div>
			<div class="row">
                <div class="form-group">
                    <label for="Translation" class="col-lg-3 control-label">Translation<label class='text-danger'>*</label></label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'Translation', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 100],$Translation); ?>
                    </div>
                    <span><?php echo form_error('Translation') ?></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="FauxAmis" class="col-lg-3 control-label">Faux Amis</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'FauxAmis', 'class' => 'form-control',
                                                    'autocomplete' => 'off'],$FauxAmis); ?>
                    </div>
                    <span><?php echo form_error('FauxAmis') ?></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="Comment" class="col-lg-3 control-label">Comment</label>
                    <div class="col-lg-8">
                        <?php echo form_textarea(['type' => 'text','name' => 'Comment', 'class' => 'form-control','autocomplete' => 'off','rows' => 2],$Comment); ?>
                    </div>

                    <!--<div class="col-lg-1">
                        <a id="TermName" title="Clear Translation" data-value ="0" onclick="insertParam(this,'translation')" class="btn btn-primary round"><span class='btn-label'><i class='glyphicon glyphicon-refresh'></i></a>
                    </div>-->
                    <span><?php echo form_error('Comment') ?></span>
                </div>
            </div>
            <div class="row">
                <?php echo anchor("home/Term?term={$_GET['term']}","Back to list",["class"=>"col-md-3 col-md-offset-6 btn btn-label","title" => "View Terms"]); ?>
                <?php echo form_button(['type' => 'submit','content' => "Save", 'class' => 'col-md-2 btn btn-primary', "title" => "Save Translation"]); ?>
            </div>
		<?php echo form_close(); ?>
	</div>
</div>

<?php include('footer.php'); ?>