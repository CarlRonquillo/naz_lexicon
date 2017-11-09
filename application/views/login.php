<?php include('header.php'); ?>

<div class="container">
    <div style ="width:40%;margin:auto;">
        <h2 class="text-center">Login</h2><hr>
        <?php 
            if($error = $this->session->flashdata('response')):
            {                       
        ?>
        <p class="text-danger text-center">
            <span class ="glyphicon glyphicon-info-sign"></span>
            <?php echo $error; ?>
        </p>
        <?php 
        }
            endif
        ?>
        <div class="well">
            <?php echo form_open("home/login_validation",['class' => 'form-horizontal']); ?>
            <div class="row">
                <div class="form-group">
                    <label for="Username" class="col-lg-3 control-label">Username</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'Username', 'class' => 'form-control',
                                                    'autocomplete' => 'off']); ?>
                    <span><?php echo form_error('Username') ?></span>
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="Password" class="col-lg-3 control-label">Password</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'password','name' => 'Password', 'class' => 'form-control',
                                                    'autocomplete' => 'off']); ?>
                    <span><?php echo form_error('Password') ?></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php echo form_button(['type' => 'submit','class' => 'col-lg-3 col-lg-offset-8 btn btn-primary','content' => "Submit"]); ?>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>