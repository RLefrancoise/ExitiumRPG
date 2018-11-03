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

		<title>Exitium - Roman</title>

		<link rel="stylesheet" type="text/css" href="rpg/css/viewpdf{SD_CSS}.css" />
		<link href="extlibs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!--link href="extlibs/bootstrap-select/dist/css/bootstrap-select.min" rel="stylesheet"-->
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->

	    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	    <!-- Include all compiled plugins (below), or include individual files as needed -->
	    <script src="extlibs/bootstrap/js/bootstrap.min.js"></script>
	    <!--script src="extlibs/bootstrap-select/dist/js/bootstrap-select.min.js"></script-->

		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript" src="rpg/js/windows.js"></script>
		<script type="text/javascript" src="rpg/js/pdf.js"></script>
		<script type="text/javascript">
		   put_menu_and_chatbox('{SID}');
		   PDFJS.workerSrc = '{URL}rpg/js/pdf.worker.js';
		   var totalPage = null;
		   var numPage = 0;
		   var scale = 1.5;
		   var oPdf = null;
		   var currentCanvas = "canvas";

		   function previousPage() {
		   		if(numPage < 1) return;
		   		numPage = numPage - 2;
		   		oPdf.then(renderPDF);
		   }

		   function nextPage() {
		   		if(numPage >= totalPage - 1) return;
		   		numPage = numPage + 2;
		   		oPdf.then(renderPDF);
		   }

		   function renderPage(page){
				var viewport = page.getViewport(scale);
				// Le canvas qui contiendra le rendu du PDF
				var canvas = document.getElementById(currentCanvas);
				var context = canvas.getContext('2d');
				// On définit la taille du canvas pour lui appliquer la taille du PDF
				canvas.height = viewport.height;
				canvas.width = viewport.width;

				document.getElementById('chapters').style.width = canvas.width;


				// Contexte de rendu avec le contexte 2D du canvas et le viewport pour la page PDF à afficher
				var renderContext = {
				  canvasContext: context,
				  viewport: viewport
				};
				// On lance le rendu de la page
				page.render(renderContext);

				//if(currentCanvas == "canvas") currentCanvas = "canvas2";
				//else currentCanvas = "canvas";
			}

			function erasePage() {
				var canvas = document.getElementById(currentCanvas);
				var context = canvas.getContext('2d');
				context.fillStyle = "#FFFFFF";
				context.fillRect(0, 0, canvas.width, canvas.height);
			}

		   	function renderPDF(pdf){
			  	// au premier appel de la fonction, on récupère le nombre de pages
			  	if(totalPage == null){
					totalPage = pdf.numPages;
			  	}

			  	if(numPage < 1) numPage = 1;
			  	if(numPage > totalPage) numPage = totalPage;

				pdf.getPage(numPage).then(function(page) {
					currentCanvas = "canvas"
					renderPage(page);
					if( (numPage + 1) <= totalPage) {
						pdf.getPage(numPage + 1).then(function(page) {
							currentCanvas = "canvas2";
							renderPage(page);
						});
					} else {
						currentCanvas = "canvas2";
						erasePage();
					}

				});
			}

			function changePDF(pdf) {
				console.log(pdf);

				document.getElementById('chapters').blur();

				totalPage = null;
				numPage = 0;

				oPdf = PDFJS.getDocument('pdf.php?sid={SID}&f=' + encodeURIComponent(pdf));

				oPdf.then(renderPDF);
			}

			function zoom_out(value) {
				scale = scale - value;
				if(scale < 0.5) scale = 0.5;

				oPdf.then(renderPDF);
			}

		   function zoom_in(value) {
				scale = scale + value;

				oPdf.then(renderPDF);
			}

		   //oPdf = PDFJS.getDocument('pdf.php?sid={SID}&f=ch0-ch5');

		   //oPdf.then(renderPDF);


			var chapters = null;

			function read_chapters(data) {
				//var d = data.split('<!--');
				console.log(data);
				//d = JSON.parse(d[0]);
				var d = JSON.parse(data);

				var options = '';

				for(k in d) {
					options += '<option value="' + k + '">' + d[k].chapter + ' - ' + d[k].title + '</option>';
				}

				document.getElementById('chapters').innerHTML = options;
				console.log(options);
				//$('#chapters').selectpicker();
			}


			function load_chapters() {
				var xhr = getXMLHttpRequest();

				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {


						if( (xhr.status == 200 || xhr.status == 0) ) {

							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue !');
							} else if(string_starts_with(xhr.responseText, "{")) {
								read_chapters(xhr.responseText);

								var chapters = document.getElementById('chapters');


								chapters.selectedIndex = 0;

								changePDF(chapters.options[chapters.selectedIndex].value);


							} else {
								alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
							}

						} else {
							alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
						}
					}

				};

				xhr.open("GET", "viewpdf.php?sid={SID}&mode=getchapters", true);
				xhr.send(null);
			}

		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');" style="">




		<script type="text/javascript">
			function checkEventObj(_event_) {
				if(window.event) return window.event;

				return _event_;
			}

			window.document.onkeydown = function(_event_) {

				var e = checkEventObj(_event_);

				switch(e.keyCode) {

					//LEFT
					case 37:
						document.getElementById('previous').click();
						return true;

					//RIGHT
					case 39:
						document.getElementById('next').click();
						return true;

					// numpad +
					case 107:
						document.getElementById('zoom_more').click();
						return true;

					//numpad -
					case 109:
						document.getElementById('zoom_less').click();
						return true;

				}

				return false;
			};

			load_chapters();
		</script>
		<p><a id="exit_link" href="{BACK_LINK}">Revenir à l'index</a></p>


		<p style="text-align: center;">
			<select id="chapters" class="form-control" style="max-width:500px;margin:auto;" onchange="changePDF(this.options[this.selectedIndex].value)"></select><br>
			<canvas id="canvas" style="border:5px inset blue"></canvas>
			<canvas id="canvas2" style="border:5px inset blue"></canvas><br>
			<input type="button" class="btn btn-default" id="previous" value="Page précédente" onclick="previousPage()"/>
			<input type="button" class="btn btn-default" id="next" value="Page suivante" onclick="nextPage()"/>
			<input type="button" class="btn btn-default" id="zoom_less" value="Zoom-" onclick="zoom_out(0.5)"/>
			<input type="button" class="btn btn-default" id="zoom_more" value="Zoom+" onclick="zoom_in(0.5)"/>
		</p>

	</body>
</html>