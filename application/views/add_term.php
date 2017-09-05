<?php include('header.php'); ?>

    <div class="container">
        <?php
            if(count($record)>0)
            {
                $TermName = $record->TermName;
                $CoreTerm = $record->CoreTerm;
                $GlossaryEntry = $record->GlossaryEntry;
                $CommonUsage = $record->CommonUsage;
                $Title = $record->Title;
                $DocumentReference = $record->DocumentReference;
                $ContextValue = $record->ContextValue;
                $Note= $record->Note;
            }
            else
            {
                $TermName = "";
                $CoreTerm = "";
                $GlossaryEntry = "";
                $CommonUsage = "";
                $Title = "";
                $DocumentReference = "";
                $ContextValue = "";
                $Note = "";
            }

            if($_GET['term'] == 0)
            {
                $header = "Add Term";
            }
            else
            {
                $header = "Update <b>".$TermName."</b>";
            }
        ?>
        <h2><?php echo $header; ?></h2><hr>
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
        <div class="well col-md-5">
            <?php echo form_open("home/save_Term/{$baseID}/{$_GET['term']}",['class' => 'form-horizontal','name' => '']); ?>
            <fieldset>
            <div class="row">
                <div class="form-group">
                    <label for="BaseForm" class="col-lg-3 control-label">Base Form</label>
                    <div class="col-lg-5">
                                        <?php
                            $baseForms = array();
                            foreach($Base_Names as $Base_Name)
                            {
                            $baseForms[$Base_Name->BaseFormID]=$Base_Name->BaseName;
                            }
                            echo form_dropdown(['id' => 'BaseForm','name' => 'BaseForm', 'class' => 'form-control','autocomplete' => 'off','onchange' => "insertParam(this,'baseForm')"],$baseForms,$_GET['baseForm']);
                        ?>
                    </div>
                </div>
            </div>
                                    
            <div class="row">
                <div class="form-group">
                <label for="TermName" class="col-lg-3 control-label">Term Name</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'TermName', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 100],$TermName); ?>
                    </div>
                    <div class="col-lg-1">
                        <a id="TermName" title="Clear Update" data-value ="0" onclick="insertParam(this,'term')" class="btn btn-primary round"><span class='btn-label'><i class='glyphicon glyphicon-refresh'></i></a>
                    </div>
                    <span><?php echo form_error('TermName') ?></span>
                </div>
            </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="CoreTerm" class="col-lg-3 control-label">Core Term</label>
                                            <div class="col-lg-3">
                                                <?php echo form_input(['type' => 'text','name' => 'CoreTerm', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 1],$CoreTerm); ?>
                                            </div>
                                            <span><?php echo form_error('CoreTerm') ?></span>
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
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="CommonUsage" class="col-lg-3 control-label">Common Usage</label>
                                            <div class="col-lg-8">
                                                <?php echo form_input(['type' => 'text','name' => 'CommonUsage', 'class' => 'form-control','autocomplete' => 'off'],$CommonUsage); ?>
                                            </div>
                                            <span><?php echo form_error('CommonUsage') ?></span>
                                        </div>
                                    </div>
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
                                                <!--<?php echo form_input(['name' => 'RelatedTerms', 'class' => 'form-control','id' => 'ms1']); ?>-->
                                                <?php echo form_input(['name' => 'RelatedTerms', 'class' => 'form-control','id' => 'RelatedTerms','value' => $relatedTerms]); ?>
                                                <?php echo form_input(['name' => 'DeletedTerms', 'class' => 'form-control','id' => 'DeletedTerms']); ?>
                                            </div>
                                            <div class="col-lg-1">
                                                <?php echo form_button(['type' => 'submit','content' => "<i class='glyphicon glyphicon-floppy-disk'></i>", 'class' => 'btn btn-primary round next-step', "title" => "Save Term"]); ?>
                                            </div>
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
                                      <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 1;
                                            if(count($Terms)): ?>
                                        <?php foreach($Terms as $Term) { ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td><a style="cursor:pointer" data-value ="<?php echo $Term->TermID; ?>" onclick="insertParam(this,'term')"><?php echo $Term->TermName; ?></a>
                                                <!--<?php echo anchor("",$Term->TermName,array('data-value' => '{$Term->TermID}', 'onclick' => "insertParam(this,'term')"));?>--></td>
                                                <td><?php echo $Term->CoreTerm; ?></td>
                                                <td><?php echo $Term->GlossaryEntry; ?></td>
                                                <td><?php echo $Term->CommonUsage; ?></td>
                                                <td><?php echo anchor("home/delete_term/{$Term->TermID}/term/TermID/0/{$_GET['baseForm']}","<i class='glyphicon glyphicon-remove'></i>",["class"=>"btn btn-danger btn-xs round","onclick" => "return confirm('Are you sure you want delete?')"]); ?></td>
                                            </tr>
                                        <?php } else: ?>
                                            <tr>No Records Found!</tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
        <div class="row">
            <div class="col-md-3 col-md-offset-9">
                <?php echo anchor("home/BaseForm?baseForm={$_GET['baseForm']}&inflection=0","Base Form",["class"=>"col-md-5 btn btn-default","title" => "Add Translation"]); ?>
                <?php echo anchor("home/Translation?baseForm={$_GET['baseForm']}&term={$first_TermID}&translation=0","Translation",["class"=>"col-md-6 col-md-offset-1 btn btn-primary","title" => "Add Translation"]); ?>
            </div>
        </div><br>
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

    <script>
        function getval(sel,method,variable)
        {
            var base_url = "<?php echo base_url(); ?>"
            location.replace(base_url + 'index.php/home/' + method + '?' + variable + '=' + sel.value);
        }

        function insertParam(sel, variable)
        {
            var kvp = document.location.search.substr(1).split('&');
            key = encodeURI(variable);

            if(variable == 'term')
            {
                value = $(sel).data("value");
            }
            else
            {
                value = sel.value;

                key2 = 'term';
                value2 = 0;

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

            if(i<0) {kvp[kvp.length] = [key,value].join('=');}

            //this will reload the page, it's likely better to store this until finished
            document.location.search = kvp.join('&');
        }
    </script>

<?php include('footer.php'); ?>

<script>
    $(function(){
        $("#RelatedTerms").tags({
            requireData: true,
            unique: true,
            deletedInput: 'DeletedTerms'
        }).autofill({
            data:<?php if(isset($terms)) {echo json_encode(array_values($terms));} ?>
        })
    });
</script>