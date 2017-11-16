<?php include('header.php'); ?>

    <div class="container" style ="width:60%;margin:auto;">
        <h2 class='text-center'><?php echo $record->TermName.' - '.'<b>'.$record->Translation.'</b>' ?></h2><hr>
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
            endif;
        ?>
        <div style ="width:70%;margin:auto;">
        <p>Please fill all necessary fields to proceed your suggestion.</p>
        <?php echo form_open("home/save_suggestedTerm/{$record->TermID}",['class' => 'form-horizontal']); ?>
        <div class="well">
            <div class="row">
                <div class="form-group">
                    <label for="SuggestedBy" class="col-lg-3 control-label">Full Name<label class='text-danger'>*</label></label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'SuggestedBy', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50]); ?>
                        <span><?php echo form_error('SuggestedBy') ?></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="Email" class="col-lg-3 control-label">Email Address<label class='text-danger'>*</label></label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'email','name' => 'Email', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50]); ?>
                    <span><?php echo form_error('Email') ?></span>
                    </div>
                </div>
            </div>
        </div>  
        <div class="well">
            <fieldset>               
            <div class="row">
                <div class="form-group">
                    <label for="GlossaryEntry" class="col-lg-3 control-label">Glossary Entry</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'GlossaryEntry', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50,'placeholder' => $record->GlossaryEntry]); ?>
                    </div>
                    <span><?php echo form_error('GlossaryEntry') ?></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="Title" class="col-lg-3 control-label">Title</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'Title', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50,'placeholder' => $record->Title]); ?>
                    </div>
                    <span><?php echo form_error('Title') ?></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="DocumentReference" class="col-lg-3 control-label">Document Ref</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'DocumentReference', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50,'placeholder' => $record->DocumentReference]); ?>
                    </div>
                    <span><?php echo form_error('DocumentReference') ?></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="ManualReference" class="col-lg-3 control-label">Manual Ref</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'ManualReference', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 30,'placeholder' => $record->ManualReference]); ?>
                    </div>
                    <span><?php echo form_error('ManualReference') ?></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="Context" class="col-lg-3 control-label">Context</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'Context', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50,'placeholder' => $record->ContextValue]); ?>
                    </div>
                    <span><?php echo form_error('Context') ?></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="FauxAmis" class="col-lg-3 control-label">Faux Amis</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'FauxAmis', 'class' => 'form-control','autocomplete' => 'off','placeholder' => $record->FauxAmis]); ?>
                    </div>
                    <span><?php echo form_error('FauxAmis') ?></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="Note" class="col-lg-3 control-label">Note</label>
                    <div class="col-lg-8">
                        <?php echo form_textarea(['type' => 'text','name' => 'Note', 'class' => 'form-control','autocomplete' => 'off','rows' => 2,'placeholder' => $record->Note]); ?>
                    </div>
                    <span><?php echo form_error('Note') ?></span>
                </div>
            </div>
            </fieldset>
            <div class="row">
                <div class="col-md-6 col-md-offset-6">
                    <?php echo anchor("home/index","Cancel",["class"=>"col-md-5 btn btn-default","title" => "Add Baseform"]); ?>
                    <?php echo form_button(['type' => 'submit','content' => "Proceed", 'class' => 'col-md-5 col-md-offset-1 btn btn-primary']); ?>
                </div>
            </div>
        </div>
        </div>
        <?php echo form_close(); ?>
    </div>

<?php include('footer.php'); ?>