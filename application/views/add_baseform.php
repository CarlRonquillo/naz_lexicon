<?php include('header.php'); ?>

<div class="container">
    <?php
        if(count($record)>0)
        {
            $Inflection = $record->InflectionName;
            $PartOfSpeechID = $record->PartOfSpeechID;
        }
        else
        {
            $Inflection = "";
            $PartOfSpeechID = 0;
        }

        if($_GET['inflection'] == 0)
        {
            $header = "Base Form";
        }
        else
        {
            $header = "Update inflection: <b>".$Inflection."</b>";
        }

    ?>
	<div class="row">
		<h2 id="base-header" ><?php echo $header; ?></h2><hr>
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
        <div class="well col-md-6">
            <?php echo form_open("home/save_BaseName/{$_GET['baseForm']}",['class' => 'form-horizontal','name' => 'frmBaseName']); 
            if(!isset($BaseName))
            {
                $BaseName = "";
            }?>
            <div class="row">
                <div class="form-group">
                    <label for="BaseName" class="col-lg-3 control-label">Base Form</label>
                    <div class="col-lg-5">
                        <?php echo form_input(['id' => 'BaseName', 'type' => 'text','name' => 'BaseName', 'class' => 'form-control',
                                                            'autocomplete' => 'off','maxlength' => 100, 'value' => $BaseName])?>
                        <span><?php echo form_error('BaseName') ?></span>
                    </div>
                    <div class="col-lg-1">
                        <?php echo form_button(['id' => 'saveBaseName','name' => 'saveBaseName', 'type' => 'submit','content' => "<i class='glyphicon glyphicon-floppy-disk'></i>", 'class' => 'btn btn-primary round', "title" => "Save Baseform",'value' => '0']); ?>
                    </div>
                    <div class="col-lg-1">
                        <a id="TermName" title="Clear Base Form" data-value ="1" onclick="insertParam(this,'baseForm')" class="btn btn-primary round"><span class='btn-label'><i class='glyphicon glyphicon-refresh'></i></a>
                    </div>
                </div>
            </div>
                <?php echo form_close(); ?>
                <hr>
                <?php echo form_open("home/save_Inflection/{$_GET['baseForm']}/{$_GET['inflection']}",['class' => 'form-horizontal','name' => 'Inflection']); ?>
            <div class="row">
                <div class="form-group">
                    <label for="InflectionName" class="col-lg-3 control-label">Inflection</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'InflectionName', 'class' => 'form-control',
                                                    'autocomplete' => 'off','maxlength' => 50],$Inflection); ?>
                    </div>
                    <span><?php echo form_error('InflectionName') ?></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="PartOfSpeech" class="col-lg-3 control-label">Part of Speech</label>
                        <div class="col-lg-4">
                        <?php
                            $PartOf_Speech = array();
                            foreach($PartOfSpeech as $speech)
                            {
                                $PartOf_Speech[$speech->PartOfSpeechID]=$speech->PartOfSpeechValue;
                            }
                                echo form_dropdown(['name' => 'PartOfSpeech', 'class' => 'form-control','autocomplete' => 'off'],$PartOf_Speech,$PartOfSpeechID);
                        ?>
                        </div>
                	<div class="col-lg-1">
                        <?php echo form_button(['type' => 'submit','content' => "<i class='glyphicon glyphicon-floppy-disk'></i>", 'class' => 'btn btn-primary round', "title" => "Save Inflection"]); ?>
                    </div>
                    <div class="col-lg-1">
                        <a id="TermName" title="Clear Inflection" data-value ="0" onclick="insertParam(this,'inflection')" class="btn btn-primary round"><span class='btn-label'><i class='glyphicon glyphicon-refresh'></i></a>
                    </div>
                </div>
        	</div>
                <?php echo form_close(); ?>
        </div>
        <div class="col-md-6">
            <div class="">
                <?php
                if(!empty($_GET['baseName']))
                { ?>
                        <div class="form-group">    
                            <h3><b style="margin-right:25px;"><?php echo $_GET['baseName']; ?></b>
                            <?php echo form_button(['id' => 'update-basename','name' => 'update-basename', 'content' => "<i class='glyphicon glyphicon glyphicon-pencil'></i>", 'class' => 'btn btn-success round', "title" => "Edit Basename",'onclick' => "updateBasename('{$_GET['baseName']}')",]);?>
                            <?php echo anchor("home/delete_baseform/{$_GET['baseForm']}/baseform/BaseFormID","<i class='glyphicon glyphicon-remove'></i>",["class"=>"btn btn-danger round","onclick" => "return confirm('Are you sure you want delete this basename?')"]); ?></h3>
                        </div>
            <?php } ?>
            </div>
            <div class="row">
            	<div class="form-group">
                    <label for="BaseForm" class="col-lg-2 control-label">Base Form</label>
                    <div class="autocomplete_container col-lg-5">
                        <?php echo form_open("home/get_BaseID",['class' => 'form','method' => 'post','id' => 'frm_dictionary']); ?>
                        <div class="input-group">
                            <?php echo form_input(['type' => 'text','name' => 'base','id' => 'base', 'class' => 'form-control','autocomplete' => 'off', 'placeholder' => 'Lookup','autofocus' => true]); ?>
                            <span class="input-group-btn">
                                <?php echo form_button(['type' => 'submit','class' => 'btn btn-default','content' => "<span class='btn-label'><i class='glyphicon glyphicon-circle-arrow-right'></i></span>"]); ?>
                                <!--<a id="TermName" title="Clear Inflection" data-value ="0" onclick="insertParam('#base','baseForm')" class="btn btn-default"><span class='btn-label'><i class='glyphicon glyphicon-circle-arrow-right'></i></span></a>-->
                            </span>
                        </div>
                        <?php
                            $baseForms = array();
                            foreach($Base_Names as $Base_Name)
                            {
                                $baseForms[$Base_Name->BaseFormID]=$Base_Name->BaseName;
                            }
                            //echo form_dropdown(['id' => 'BaseForm','name' => 'BaseForm', 'class' => 'form-control','autocomplete' => 'off','onchange' => "getval(this,'BaseForm','baseForm');"],$baseForms,$_GET['baseForm']);
                        ?>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                	<tr>
	                    <th>#</th>
	                    <th>Inflection</th>
	                    <th>Part of Speech</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1;
                    if(count($Inflections)): ?>
	                    <?php foreach($Inflections as $Inflection) { ?>
	                    <tr>
	                        <td><?php echo $count++; ?></td>
	                        <td><a style="cursor:pointer" data-value ="<?php echo $Inflection->InflectionID; ?>" onclick="insertParam(this,'inflection')"><?php echo $Inflection->InflectionName; ?></a>
                            <!--<?php echo anchor("home/edit_host/",$Inflection->InflectionName);?></td>-->
	                        <td><?php echo $Inflection->PartOfSpeechValue; ?></td>
                            <td><?php echo anchor("home/delete_baseform/{$Inflection->InflectionID}/inflection/InflectionID","<i class='glyphicon glyphicon-remove'></i>",["class"=>"btn btn-danger btn-xs round","onclick" => "return confirm('Are you sure you want delete?')"]); ?></td>
	                    </tr>
	                    <?php } else: ?>
	                    <tr>No Records Found!</tr>
                    <?php endif; ?>
                </tbody>
            </table>
   		</div>
    </div>
    <div class="row">
    	<?php echo anchor("home/Term?baseForm={$_GET['baseForm']}&term=0","Term",["class"=>"col-md-1 col-md-offset-11 btn btn-primary","title" => "Add Term"]); ?>
    </div><br>
</div>

    <script type="text/javascript"> 
        function getval(sel,method,variable)
        {
            var base_url = "<?php echo base_url(); ?>"
            location.replace(base_url + 'index.php/home/' + method + '?' + variable + '=' + sel.value + "&inflection=0");
        }

        function updateBasename(baseName)
        {
            $("#base-header").html("Update Base Form: <b>"+ baseName +"</b>");
            $("#BaseName").val(baseName);
            $("#saveBaseName").val(1);
        }

        function insertParam(sel, variable)
        {
            var kvp = document.location.search.substr(1).split('&');
            key = encodeURI(variable);
            value = $(sel).data("value");

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
        $( function() {
            var availableTags = <?php if(isset($baseForms)) {echo json_encode(array_values($baseForms));} ?>;
            $( "#base" ).autocomplete({
                source: availableTags
            });
        } );
    </script>