<?php include('header.php'); ?>

    <div class="container">
        <h2>Term</h2><hr>
        <?php 
            if($error = $this->session->flashdata('response')):
            {                                                           
        ?>
        <p class="<?php echo (null!==($this->session->flashdata('class')) ? 'text-danger' : 'text-success') ?>">
            <span class ="glyphicon glyphicon-info-sign"></span>
            <?php echo $error; ?>
        </p>
        <?php 
        }
            endif
        ?>

        <div class="well col-md-5">
            <div class="row">
                <div class="autocomplete_container">
                    <?php echo form_open("home/existing_term_search",['class' => 'form','method' => 'get','id' => 'frm_dictionary']); ?>
                    <div class="input-group">
                        <?php echo form_input(['type' => 'text','name' => 'search','id' => 'search', 'class' => 'form-control','autocomplete' => 'off', 'placeholder' => 'Search Term','value' => (isset($_GET['search']) ? $_GET['search'] : ''),'autofocus' => true]); ?>
                        <span class="input-group-btn">
                            <?php echo form_button(['type' => 'submit','class' => 'btn btn-default','content' => "<span class='btn-label'><i class='glyphicon glyphicon-search'></i></span>"]); ?>
                        </span>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover ">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Term</th>
                        <th>Glossary Entry</th>
                        <th></th>
                    </tr>
                </thead>
                    <tbody>
                        <?php $count = 1;
                            if(isset($Searched_Terms)): ?>
                                <?php foreach($Searched_Terms as $Term) { ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><a style="cursor:pointer" data-value ="<?php echo $Term->TermID; ?>" onclick="insertParam(this,'term')"><?php echo $Term->TermName; ?></a>
                                                <!--<?php echo anchor("",$Term->TermName,array('data-value' => '{$Term->TermID}', 'onclick' => "insertParam(this,'term')"));?>--></td>
                                                <td><?php echo $Term->GlossaryEntry; ?></td>
                                    <td><?php echo anchor("home/add_BaseformHasTerm/{$Term->TermID}/{$_GET['baseForm']}/{$_GET['search']}","<i class='glyphicon glyphicon-plus'></i>",["class"=>"btn btn-success btn-xs round","onclick" => "return confirm('Are you sure you want add?')"]); ?></td>
                                </tr>
                                    <?php } else: ?>
                                <tr>No Records Found!</tr>
                                <?php endif; ?>
                    </tbody>
            </table>
        </div>
        <div class="col-md-6 col-md-offset-1">
            <fieldset>
            <div class="row">
                <div class="form-group">
                    <label for="BaseForm" class="col-lg-2 control-label">Base Form</label>
                    <div class="col-lg-5">
                            <?php
                            $baseForms = array('');
                            foreach($Base_Names as $Base_Name)
                            {
                            $baseForms[$Base_Name->BaseFormID]=$Base_Name->BaseName;
                            }
                            echo form_dropdown(['id' => 'baseForm','name' => 'baseForm', 'class' => 'form-control','autocomplete' => 'off','onchange' => "insertParam(this,'baseForm')"],$baseForms,(isset($_GET['baseForm']) ? $_GET['baseForm'] : 0));
                        ?>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-hover ">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Term</th>
                        <th>Glossary Entry</th>
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
                                                <td><?php echo $Term->GlossaryEntry; ?></td>
                                                <!--<td><?php echo $Term->CommonUsage; ?></td>-->
                                    <td><?php echo anchor("home/delete_BaseformHasTerm/{$Term->TermID}/{$_GET['baseForm']}/{$_GET['search']}","<i class='glyphicon glyphicon-remove'></i>",["class"=>"btn btn-danger btn-xs round","onclick" => "return confirm('Are you sure you want delete?')"]); ?></td>
                                            </tr>
                                    <?php } else: ?>
                                <tr>No Records Found!</tr>
                                <?php endif; ?>
                    </tbody>
            </table>
        </div>
        <?php echo form_close(); ?>
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


    <script>
        $( function() {
            var availableTags = <?php if(isset($terms)) {echo json_encode($terms);} ?>;
            $( "#search" ).autocomplete({
                source: availableTags
            });
        } );

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