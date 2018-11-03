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

		<title>Exitium - Réponse à l'énigme {RIDDLE_NAME}</title>
	</head>

	<body onload="javascript:set_sid('{SID}');">
		<form name="quest_riddle_form" action="answerriddle.php?sid={SID}&amp;r={RIDDLE_ID}" method="post">
			<div>
				<strong>Intitulé de l'énigme :</strong><br>
				{RIDDLE_DESC}
			</div>
			<br>
			<div>
				<strong>Réponse :</strong><br>
				<input type="text" name="riddle_answer"></input>
			</div>
			<input type="submit" value="Submit">
		</form>
	</body>
</html>