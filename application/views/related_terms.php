<?php include('header.php'); ?>

    <div class="container">
        <h2>Related Terms of <b><?php echo $term->TermName; ?></b></h2><hr>
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
        <div class="col-md-5">
            <div class="row">
                <div class="autocomplete_container">
                    <?php echo form_open("home/save_relatedterm/{$_GET['term']}",['class' => 'form','method' => 'get']); ?>
                    <div class="input-group">
                        <?php echo form_input(['type' => 'text','name' => 'search','id' => 'search', 'class' => 'form-control','autocomplete' => 'off', 'placeholder' => 'Lookup','value' => '','autofocus' => true]); ?>
                        <span class="input-group-btn">
                            <?php echo form_button(['type' => 'submit','class' => 'btn btn-default','content' => "<span class='btn-label'><i class='glyphicon glyphicon-plus'></i></span>"]); ?>
                        </span>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <br>
            <div class="row">
                <table class="table table-striped table-hover ">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Term Name</th>
                            <th></th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php $count = 1;
                            if(count($RelatedTerms)): ?>
                                <?php foreach($RelatedTerms as $RelatedTerm) { ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo anchor("home/Term?term={$RelatedTerm->TermID}", $RelatedTerm->TermName); ?></td>
                                    <td><?php echo anchor("home/delete_RelatedTerm/{$RelatedTerm->FKTermIDParent}/{$RelatedTerm->FKTermIDChild}","<i class='glyphicon glyphicon-remove'></i>",["class"=>"btn btn-danger btn-xs round","onclick" => "return confirm('Are you sure you want delete?')"]); ?></td>
                                </tr>
                                <?php } else: ?>
                                <tr><br><br>No Records Found!</tr>
                            <?php endif; ?>
                        </tbody>
                </table>
            </div>
            <div class="row">
                <?php echo anchor("home/Term?term={$_GET['term']}","Back to <b>{$term->TermName}</b>",["class"=>"col-md-3 btn btn-label"]); ?>
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
    </script>
