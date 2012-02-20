<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
	<head>
		<title>Error</title>
	</head>
	<body>
		<h1>Error</h1>
		<p>Sorry, an unexpected error occurred.</p>

<?php if(ini_get('display_errors') == '1') { ?>
		<h2>Cause</h2>
		<p>
			<span style="color: #f00;"><?php echo CIGI_ERROR_REASON; ?></span><br />
			File: <span style="color: #f00;"><?php echo CIGI_ERROR_FILE; ?></span><br />
			Line: <span style="color: #f00;"><?php echo CIGI_ERROR_LINE; ?></span><br />
		</p>
<?php } ?>
	</body>
</html>
