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
                $TranslationID = 0;
            }

            if($_GET['translation'] == 0)
            {
                $header = "Add Translation";
            }
            else
            {
                $header = "Update <b>".$Translation."</b>";
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
            endif
        ?>
	<div class="well col-md-5">
		<?php echo form_open("home/save_Translation/{$_GET['baseForm']}/{$_GET['term']}/{$_GET['translation']}",['class' => 'form-horizontal']);?>
			<div class="row">
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
            </div>
            <div class="row">
            	<div class="form-group">
                    <label for="FKLanguageID" class="col-lg-3 control-label">Language</label>
                    <div class="col-lg-5">
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
                    <div class="col-lg-1">
                        <?php echo form_button(['type' => 'submit','content' => "<i class='glyphicon glyphicon-floppy-disk'></i>", 'class' => 'btn btn-primary round next-step', "title" => "Save Translation"]); ?>
                    </div>
                    <div class="col-lg-1">
                        <a id="TermName" title="Clear Translation" data-value ="0" onclick="insertParam(this,'translation')" class="btn btn-primary round"><span class='btn-label'><i class='glyphicon glyphicon-refresh'></i></a>
                    </div>
                    <span><?php echo form_error('Comment') ?></span>
                </div>
            </div>
		<?php echo form_close(); ?>
	</div>
	<div class="col-md-7">
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
	                        <td><a style="cursor:pointer" data-value ="<?php echo $Translation->TranslationID; ?>" onclick="insertParam(this,'translation')"><?php echo $Translation->Translation; ?></a></td>
	                        <td><?php echo $Translation->Language; ?></td>
	                        <td><?php echo $Translation->FauxAmis; ?></td>
	                        <td><?php echo $Translation->Comment; ?></td>
                            <td><?php echo anchor("home/delete_translation/{$Translation->TranslationID}","<i class='glyphicon glyphicon-remove'></i>",["class"=>"btn btn-danger btn-xs round","onclick" => "return confirm('Are you sure you want delete?')"]); ?></td>
	                    </tr>
	                    <?php } else: ?>
	                    <tr>No Records Found!</tr>
                    <?php endif; ?>
                </tbody>
            </table>
   		</div>
    <div class="row">
        <div class="col-md-3 col-md-offset-9">
            <?php echo anchor("home/Term?baseForm={$_GET['baseForm']}&term=0","Term",["class"=>"col-md-5 btn btn-default","title" => "Add Translation"]); ?>
            <?php echo anchor("home/Terms","View List",["class"=>"col-md-6 col-md-offset-1 btn btn-primary","title" => "View Terms"]); ?>
        </div>
    </div><br>
</div>

	<script type="text/javascript"> 
        function getval(sel,method,variable)
        {
            var base_url = "<?php echo base_url(); ?>"
            location.replace(base_url + 'index.php/home/' + method + '?' + variable + '=' + sel.value);
        }

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

        //function codeAddress() {
        //    alert("<?php echo $first_TermID; ?>");
        //}
        //window.onload = codeAddress;

        /*function codeAddress() {
            if (! localStorage.justOnce) {
                localStorage.setItem("justOnce", "true");
                
                key2 = 'term';
                value2 = 5;

                var kvp = document.location.search.substr(1).split('&');

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

                if(i<0) {kvp[kvp.length] = [key,value].join('=');}

                //this will reload the page, it's likely better to store this until finished
                document.location.search = kvp.join('&');
            }
        }
        window.onload = codeAddress;*/


    </script>

<?php include('footer.php'); ?>