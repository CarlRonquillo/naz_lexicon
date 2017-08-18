		<script src="<?php echo base_url("resources/js/bootstrap.js"); ?>"></script>
		<script src="<?php echo base_url("resources/js/jquery.min.js"); ?>"></script>
		<script src="<?php echo base_url("resources/js/bootstrap.min.js"); ?>"></script>
		<script src="<?php echo base_url("resources/js/show_more-less.js"); ?>"></script>
		<script src="<?php echo base_url("resources/js/jquery-1.12.4.js"); ?>"></script>
		<script src="<?php echo base_url("resources/js/jquery-ui.js"); ?>"></script>

	  	<script>
			$( function() {
		    var availableTags = <?php echo json_encode($terms);?>;
		    $( "#search" ).autocomplete({
		      source: availableTags
		    });
		  } );
		</script>

	</body>
</html>