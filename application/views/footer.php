		<script src="<?php echo base_url("resources/js/bootstrap.js"); ?>"></script>
		<script src="<?php echo base_url("resources/js/jquery.min.js"); ?>"></script>
		<script src="<?php echo base_url("resources/js/bootstrap.min.js"); ?>"></script>
		<script src="<?php echo base_url("resources/js/show_more-less.js"); ?>"></script>
		<script src="<?php echo base_url("resources/js/jquery-1.12.4.js"); ?>"></script>
		<script src="<?php echo base_url("resources/js/jquery-ui.js"); ?>"></script>

		<script src="<?php echo base_url("resources/js/tags.js"); ?>"></script>
        <script src="<?php echo base_url("resources/js/autofill.js"); ?>"></script>

        <script>
        var selector = '.nav li';

		$(selector).on('click', function(){
		    $(selector).removeClass('active');
		    $(this).addClass('active');
		});

		</script>

	</body>
</html>