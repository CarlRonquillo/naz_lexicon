<?php include('header.php'); ?>

    <div class="container">
    <div class="row">
        <section>
        <div class="wizard">
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation" class="disabled">
                        <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Baseform">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-book"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="active">
                        <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Term">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-comment"></i>
                            </span>
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Translation">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-picture"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-ok"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <!--<form role="form">-->
                <div class="tab-content">
                    <br>
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
                    <div class="tab-pane active" role="tabpanel" id="step1">
                        <div class="container">
                        <div class="row">
                                <div class="well col-md-6">
                                    <?php echo form_open('home/save_BaseName',['class' => 'form-horizontal','name' => 'frmBaseName']); 
                                        if(!isset($BaseName))
                                        {
                                            $BaseName = "";
                                        }?>
                                        <div class="row">
                                            <div class="form-group">
                                                    <label for="BaseName" class="col-lg-3 control-label">Base Form</label>
                                                    <div class="col-lg-5">
                                                        <?php echo form_input(['type' => 'text','name' => 'BaseName', 'class' => 'form-control',
                                                            'autocomplete' => 'off','maxlength' => 100, 'value' => $BaseName])?>
                                                        <span><?php echo form_error('BaseName') ?></span>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <?php echo form_button(['type' => 'submit','content' => "<i class='glyphicon glyphicon-floppy-disk'></i>", 'class' => 'btn btn-primary round', "title" => "Save Baseform"]); ?>
                                                    </div>
                                            </div>
                                        </div>
                                    <?php echo form_close(); ?>
                                    <hr>
                                    <?php echo form_open("home/save_Inflection/{$_GET['baseForm']}",['class' => 'form-horizontal','name' => 'Inflection']); ?>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="InflectionName" class="col-lg-3 control-label">Inflection</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'InflectionName', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50]); ?>
                                            </div>
                                            <span><?php echo form_error('InflectionName') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="partofspeech" class="col-lg-3 control-label">Part of Speech</label>
                                            <div class="col-lg-4">
                                                <?php
                                                    $PartOf_Speech = array();
                                                    foreach($partofspeech as $speech)
                                                        {
                                                          $PartOf_Speech[$speech->partofspeechID]=$speech->partofspeechValue;
                                                        }
                                                    echo form_dropdown(['name' => 'partofspeech', 'class' => 'form-control',
                                                                        'autocomplete' => 'off'],$PartOf_Speech);
                                                ?>
                                            </div>
                                            <div class="col-lg-4">
                                                <?php echo form_button(['type' => 'submit','content' => "<i class='glyphicon glyphicon-floppy-disk'></i>", 'class' => 'btn btn-primary round', "title" => "Save Inflection"]); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="BaseForm" class="col-lg-2 control-label">Base Form</label>
                                        <div class="col-lg-5">
                                            <?php
                                                $baseForms = array();
                                                foreach($Base_Names as $Base_Name)
                                                {
                                                    $baseForms[$Base_Name->BaseFormID]=$Base_Name->BaseName;
                                                }
                                                    echo form_dropdown(['id' => 'BaseForm','name' => 'BaseForm', 'class' => 'form-control',
                                                                'autocomplete' => 'off','onchange' => "getval(this,'AddTerm','baseForm');"],$baseForms,$_GET['baseForm']);
                                                ?>
                                        </div>
                                        <span><?php echo form_error('BaseForm') ?></span>
                                    </div>
                                </div>
                                <table class="table table-striped table-hover ">
                                <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Inflection</th>
                                      <th>Part of Speech</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 1;
                                            if(count($Inflections)): ?>
                                        <?php foreach($Inflections as $Inflection) { ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td><?php echo anchor("home/edit_host/",$Inflection->InflectionName);?></td>
                                                <td><?php echo $Inflection->partofspeechValue; ?></td>
                                            </tr>
                                        <?php } else: ?>
                                            <tr>No Records Found!</tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-primary next-step">Save and continue</button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="step2">
                    <div class="container">
                        <div class="well col-md-5">
                            <?php echo form_open("home/save_Term/{$_GET['baseForm']}",['class' => 'form-horizontal','name' => '']); ?>
                                <fieldset>
                                    <legend><strong><?php echo $Baseform; ?></strong></legend>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="TermName" class="col-lg-3 control-label">Term Name</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'TermName', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 100]); ?>
                                            </div>
                                            <span><?php echo form_error('TermName') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="CoreTerm" class="col-lg-3 control-label">Core Term</label>
                                            <div class="col-lg-3">
                                                <?php echo form_input(['type' => 'text','name' => 'CoreTerm', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 1]); ?>
                                            </div>
                                            <span><?php echo form_error('CoreTerm') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="GlossaryEntry" class="col-lg-3 control-label">Glossary Entry</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'GlossaryEntry', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50]); ?>
                                            </div>
                                            <span><?php echo form_error('GlossaryEntry') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="CommonUsage" class="col-lg-3 control-label">Common Usage</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'CommonUsage', 'class' => 'form-control',
                                                    'autocomplete' => 'off']); ?>
                                            </div>
                                            <span><?php echo form_error('CommonUsage') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="Title" class="col-lg-3 control-label">Title</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'Title', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50]); ?>
                                            </div>
                                            <span><?php echo form_error('Title') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="DocumentReference" class="col-lg-3 control-label">Document Ref</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'DocumentReference', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50]); ?>
                                            </div>
                                            <span><?php echo form_error('DocumentReference') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="ContextValue" class="col-lg-3 control-label">Context</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'ContextValue', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50]); ?>
                                            </div>
                                            <span><?php echo form_error('ContextValue') ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="Note" class="col-lg-3 control-label">Note</label>
                                            <div class="col-lg-7">
                                                <?php echo form_textarea(['type' => 'text','name' => 'Note', 'class' => 'form-control','autocomplete' => 'off','rows' => 2]); ?>
                                            </div>
                                            <div class="col-lg-1">
                                                <?php echo form_button(['type' => 'submit','content' => "<i class='glyphicon glyphicon-floppy-disk'></i>", 'class' => 'btn btn-primary round next-step', "title" => "Save Term"]); ?>
                                            </div>
                                            <span><?php echo form_error('Note') ?></span>
                                        </div>
                                    </div>
                                </fieldset>
                            <?php echo form_close(); ?>
                        </div>
                        <div class="col-md-7">
                            <table class="table table-striped table-hover ">
                                <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Term</th>
                                      <th>Core Term</th>
                                      <th>Glossary Entry</th>
                                      <th>Common Usage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 1;
                                            if(count($Terms)): ?>
                                        <?php foreach($Terms as $Term) { ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td><?php echo anchor("home/edit_host/",$Term->TermName);?></td>
                                                <td><?php echo $Term->CoreTerm; ?></td>
                                                <td><?php echo $Term->GlossaryEntry; ?></td>
                                                <td><?php echo $Term->CommonUsage; ?></td>
                                            </tr>
                                        <?php } else: ?>
                                            <tr>No Records Found!</tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-default prev-step">Previous</button></li>
                            <li><button type="button" class="btn btn-primary next-step">Save and continue</button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="step3">
                        <h3>Step 3</h3>
                        <p>This is step 3</p>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-default prev-step">Previous</button></li>
                            <li><button type="button" class="btn btn-default next-step">Skip</button></li>
                            <li><button type="button" class="btn btn-primary btn-info-full next-step">Save and continue</button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="complete">
                        <h3>Complete</h3>
                        <p>You have successfully completed all steps.</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <!--</form>-->
        </div>
    </section>
   </div>
</div>

    <script src="<?php echo base_url("resources/js/jquery-1.10.2.min.js"); ?>"></script>
    <script src="<?php echo base_url("resources/js/bootstrap.min.js"); ?>"></script>
    <script src="<?php echo base_url("resources/js/wizard.js"); ?>"></script>

    <script type="text/javascript"> 
        function getval(sel,method,variable)
        {
            var base_url = "<?php echo base_url(); ?>"
            location.replace(base_url + 'index.php/home/' + method + '?' + variable + '=' + sel.value);
        }
    </script>

</body>
</html>
