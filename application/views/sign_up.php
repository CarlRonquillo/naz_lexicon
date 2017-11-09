<?php include('header.php'); ?>

<div class="container">
    <div style ="width:50%;margin:auto;">
        <h2 class="text-center">Sign Up</h2><hr>
        <?php 
            if($error = $this->session->flashdata('response')):
            {                       
        ?>
        <p class="text-success text-center">
            <span class ="glyphicon glyphicon-info-sign"></span>
            <?php echo $error; ?>
        </p>
        <?php 
        }
            endif
        ?>
        <div class="well">
            <?php echo form_open("home/save_Account",['class' => 'form-horizontal']); ?>
            <div class="row">
                <div class="form-group">
                    <label for="FirstName" class="col-lg-3 control-label">First name</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'FirstName', 'class' => 'form-control',
                                                    'autocomplete' => 'off']); ?>
                    <span><?php echo form_error('FirstName') ?></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="LastName" class="col-lg-3 control-label">Last name</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'text','name' => 'LastName', 'class' => 'form-control',
                                                    'autocomplete' => 'off']); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="Username" class="col-lg-3 control-label">User name</label>
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
                <div class="form-group">
                    <label for="PassConf" class="col-lg-3 control-label">Confirm Password</label>
                    <div class="col-lg-8">
                        <?php echo form_input(['type' => 'password','name' => 'PassConf', 'class' => 'form-control',
                                                    'autocomplete' => 'off']); ?>
                    <span><?php echo form_error('PassConf') ?></span>
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