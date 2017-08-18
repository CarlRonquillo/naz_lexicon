<?php include('header.php'); ?>

    <div class="container">
        <h2>Term</h2><hr>
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
        <div class="well col-md-5">
            <?php echo form_open("home/save_Term/{$baseID}",['class' => 'form-horizontal','name' => '']); ?>
            <fieldset>
            <div class="row">
                <div class="form-group">
                    <?php
                        $baseForms = array();
                        foreach($Base_Names as $Base_Name)
                        {
                        $baseForms[$Base_Name->BaseFormID]=$Base_Name->BaseName;
                        }
                        echo form_dropdown(['id' => 'BaseForm','name' => 'BaseForm', 'class' => 'col-lg-3 form-control',
                                                                'autocomplete' => 'off','onchange' => "getval(this,'Add_Term','baseForm');"],$baseForms,$_GET['baseForm']);
                    ?>
                </div>
            </div>
                                    
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
                                            <div class="col-lg-8">
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
                                                <td><?php echo anchor("home/edit_term/{$Term->TermID}",$Term->TermName);?></td>
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

        <div class="col-md-3 col-md-offset-9">
            <?php echo anchor("home/Add_BaseForm?baseForm={$_GET['baseForm']}","Previous",["class"=>"col-md-6 btn btn-default","title" => "Add Translation"]); ?>
            <?php echo anchor("home/Add_Translation?baseForm={$_GET['baseForm']}&term={$first_TermID}","Next",["class"=>"col-md-6 btn btn-primary","title" => "Add Translation"]); ?>
        </div>
    </div>

    <script type="text/javascript"> 
        function getval(sel,method,variable)
        {
            var base_url = "<?php echo base_url(); ?>"
            location.replace(base_url + 'index.php/home/' + method + '?' + variable + '=' + sel.value);
        }
    </script>

<?php include('footer.php'); ?>