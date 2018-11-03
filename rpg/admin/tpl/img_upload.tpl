<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="fr" />
		<meta http-equiv="content-style-type" content="text/css" />
		<meta http-equiv="imagetoolbar" content="no" />
		<meta name="resource-type" content="document" />
		<meta name="distribution" content="global" />
		<meta name="keywords" content="" />
		<meta name="description" content="" />

		<title>Exitium - Upload d'images'</title>
		<script type="text/javascript" src="{ROOT}rpg/js/session.js"></script>
	</head>

	<body onload="javascript:set_sid('{SID}')">
		
		<h2>Upload d'images</h2>
		
		<p>{INFO}</p>
		
		<form target="upload_iframe" action="img_upload.php?mode={MODE}" method="post" enctype="multipart/form-data" name="myForm">
			<input type="hidden" name="MAX_FILE_SIZE" value="500000" />
			<input type="file" id="img" name="img" ></input><br>
			<input type="submit" value="Valider"></input>
		</form>
		
		<iframe id="upload_iframe" width="500" height="500"></iframe>
		
	</body>
</html>