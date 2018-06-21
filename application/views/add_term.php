<?php include('header.php'); ?>

    <div class="container"  style ="width:80%;margin:auto;">
        <?php
            if(count($record)>0)
            {
                $TermName = $record->TermName;
                $GlossaryEntry = $record->GlossaryEntry;
                $CommonUsage = $record->CommonUsage;
                $Title = $record->Title;
                $DocumentReference = $record->DocumentReference;
                $ContextValue = $record->ContextValue;
                $FauxAmis = $record->term_FauxAmis;
                $Note= $record->Note;
                $ManualReference = $record->ManualReference;
            }
            else
            {
                $TermName = "";
                $GlossaryEntry = "";
                $CommonUsage = "";
                $Title = "";
                $DocumentReference = "";
                $ContextValue = "";
                $FauxAmis = "";
                $Note = "";
                $ManualReference = "";
                $relatedTerms ="";
            }

            if($_GET['term'] == 0)
            {
                $header = "Add Term";
                $display = "none";
                $center = "col-center-block";
                $visibility = "hidden";
            }
            else
            {
                $header = "Update <b>".$TermName."</b>";
                $display = "inline";
                $center = "";
                $visibility = "visible";
            }
        ?>
        <center></canvas><h2><?php echo $header; ?></h2></center>
        <hr>
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
        <div class="well col-md-5  <?php echo $center; ?>">
            <?php echo form_open("home/save_Term/{$_GET['term']}",['class' => 'form-horizontal','id' => 'frm_dictionary']); ?>
            <fieldset>
            <!--<div class="row">
                <div class="form-group">
                    <label for="BaseForm" class="col-lg-3 control-label">Base Form</label>
                    <div class="col-lg-5">
                            <?php
                            $baseForms = array('');
                            foreach($Base_Names as $Base_Name)
                            {
                                $baseForms[$Base_Name->BaseFormID]=$Base_Name->BaseName;
                            }
                            echo form_dropdown(['id' => 'BaseForm','name' => 'BaseForm', 'class' => 'form-control','autocomplete' => 'off','onchange' => "insertParam(this,'baseForm')"],$baseForms,$_GET['baseForm']);
                        ?>
                    </div>
                </div>
            </div>-->
                                    
            <div class="row">
                <div class="form-group">
                <label for="TermName" class="col-lg-3 control-label">Term Name<label class='text-danger'>*</label></label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'TermName', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 100],$TermName); ?>
                    </div>
                    <span><?php echo form_error('TermName') ?></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="GlossaryEntry" class="col-lg-3 control-label">Glossary Entry</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'GlossaryEntry', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50],$GlossaryEntry); ?>
                    </div>
                    <span><?php echo form_error('GlossaryEntry') ?></span>
                </div>
            </div>
                                    <!--<div class="row">
                                        <div class="form-group">
                                            <label for="CommonUsage" class="col-lg-3 control-label">Common Usage</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'CommonUsage', 'class' => 'form-control','autocomplete' => 'off'],$CommonUsage); ?>
                                            </div>
                                            <span><?php echo form_error('CommonUsage') ?></span>
                                        </div>
                                    </div>-->
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="Title" class="col-lg-3 control-label">Title</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'Title', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50],$Title); ?>
                                            </div>
                                            <span><?php echo form_error('Title') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="DocumentReference" class="col-lg-3 control-label">Document Ref</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'DocumentReference', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50],$DocumentReference); ?>
                                            </div>
                                            <span><?php echo form_error('DocumentReference') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="ManualReference" class="col-lg-3 control-label">2017 Manual Reference</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'ManualReference', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 30],$ManualReference); ?>
                                            </div>
                                            <span><?php echo form_error('ManualReference') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="FauxAmis" class="col-lg-3 control-label">Faux Amis</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'FauxAmis', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 30],$FauxAmis); ?>
                                            </div>
                                            <span><?php echo form_error('FauxAmis') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="ContextValue" class="col-lg-3 control-label">Context</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'ContextValue', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50],$ContextValue); ?>
                                            </div>
                                            <span><?php echo form_error('ContextValue') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="Note" class="col-lg-3 control-label">Note</label>
                                            <div class="col-lg-8">
                                                <?php echo form_textarea(['type' => 'text','name' => 'Note', 'class' => 'form-control','autocomplete' => 'off','rows' => 2],$Note); ?>
                                            </div>
                                            <span><?php echo form_error('Note') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="RelatedTerms" class="col-lg-3 control-label">Related Terms</label>
                                            <div class="col-lg-8">
                                                <?php echo anchor("home/Related_Terms?term={$_GET['term']}",$relatedTerms); ?>
                                                <!--<?php echo form_input(['name' => 'RelatedTerms', 'class' => 'form-control','id' => 'ms1']); ?>
                                                <?php echo form_input(['name' => 'RelatedTerms', 'class' => 'form-control','id' => 'RelatedTerms','value' => $relatedTerms,'readonly' => 'true']); ?>
                                                <?php echo form_input(['name' => 'DeletedTerms', 'class' => 'form-control','id' => 'DeletedTerms']); ?>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <?php echo anchor("home/delete_term/{$_GET['term']}/term/TermID","delete term",["class"=>"col-md-4 btn btn-label","onclick" => "return confirm('Are you sure you want delete?')","style" => "visibility:{$visibility}"]); ?>
                                        <!--<div class="col-md-1 col-md-offset-8">
                                            <a id="TermName" title="Clear Update" data-value ="0" onclick="insertParam(this,'term')" class="btn btn-primary round"><span class='btn-label'><i class='glyphicon glyphicon-refresh'></i></a>
                                        </div> -->
                                        <?php echo anchor("home/Terms?Language={$this->session->userdata('language_set')}","Back to list",["class"=>"col-md-3 btn btn-default","title" => "View Terms"]); ?>
                                        <?php echo form_button(['type' => 'submit','content' => "SAVE", 'class' => 'col-md-3 col-md-offset-1 btn btn-primary', "title" => "Save Term"]); ?>
                                    </div>
                                </fieldset>
                            <?php echo form_close(); ?>
        </div>
        <div class="col-md-7" style="display:<?php echo $display; ?>;">
            <?php echo anchor("home/Translation?term={$_GET['term']}&translation=0","Add translation",["class"=>"col-md-3 btn btn-default"]); ?>
            <table class="table table-striped table-hover ">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Translation</th>
                        <th>Language</th>
                        <th>Faux Amis</th>
                        <th>Comment</th>
                        <th></th>
                    </tr>
                </thead>
                    <tbody>
                        <?php $count = 1;
                        if(count($Translations)): ?>
                            <?php foreach($Translations as $Translation) { ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo anchor("home/Translation?term={$_GET['term']}&translation={$Translation->TranslationID}", $Translation->Translation); ?></td>
                                <td><?php echo $Translation->Language; ?></td>
                                <td><?php echo $Translation->FauxAmis; ?></td>
                                <td><?php echo $Translation->Comment; ?></td>
                                <td><?php echo anchor("home/delete_translation/{$Translation->TranslationID}/{$Translation->FKTermID}","<i class='glyphicon glyphicon-remove'></i>",["class"=>"btn btn-danger btn-xs round","onclick" => "return confirm('Are you sure you want delete?')"]); ?></td>
                            </tr>
                            <?php } else: ?>
                            <tr><br><br>No Records Found!</tr>
                        <?php endif; ?>
                    </tbody>
            </table>
        </div>
                    <br>
    </div>

    <?php 
        $terms = array();
        if(isset($termNames))
        {
            foreach($termNames as $term)
            {
                $terms[$term['TermID']] = $term['TermName'];
            }
        }
    ?>

<?php include('footer.php'); ?>

<script>
    $(function(){
        $("#RelatedTerms").tags({
            requireData: true,
            unique: true
        }).autofill({
            data:<?php if(isset($terms)) {echo json_encode(array_values($terms));} ?>
        })
    });
</script>