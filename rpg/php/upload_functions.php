<?php
include_once(__DIR__ . '/../classes/rpgconfig.php');

function upload_image($file, $target, $maxw, $maxh, $max_size, &$final_name, $img_name = '') {
	// Tableaux de donnees
	$tabExt = array('jpg','gif','png','jpeg'); // Extensions autorisees
	$infosImg = array();
	// Variables
	$extension = '';
	$nomImage = '';
	
	/************************************************************
	* Creation du repertoire cible si inexistant
	*************************************************************/
	if( !is_dir($target) ) {
		if( !mkdir($target, 0755) ) {
			exit('Erreur : le répertoire cible ne peut-être créé ! Vérifiez que vous diposiez des droits suffisants pour le faire ou créez le manuellement !');
		}
	}
	/************************************************************
	* Script d'upload
	*************************************************************/
	if(!empty($_POST))
	{
		// On verifie si le champ est rempli
		if( !empty($_FILES[$file]['name']) )
		{
			// Recuperation de l'extension du fichier
			$extension = pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION);
			// On verifie l'extension du fichier
			if(in_array(strtolower($extension),$tabExt))
			{
				// On recupere les dimensions du fichier
				$infosImg = getimagesize($_FILES[$file]['tmp_name']);
				// On verifie le type de l'image
				if($infosImg[2] >= 1 && $infosImg[2] <= 14)
				{
					// On verifie les dimensions et taille de l'image
					if(($infosImg[0] <= $maxw) && ($infosImg[1] <= $maxh) && (filesize($_FILES[$file]['tmp_name']) <= $max_size))
					{
						// Parcours du tableau d'erreurs
						if(isset($_FILES[$file]['error']) && UPLOAD_ERR_OK === $_FILES[$file]['error'])
						{
							// On renomme le fichier
							if($img_name == '') $nomImage = md5(uniqid()) .'.'. $extension;
							else $nomImage = $img_name;
							
							// Si c'est OK, on teste l'upload
							if(move_uploaded_file($_FILES[$file]['tmp_name'], $target.$nomImage))
							{
								$final_name = $nomImage;
								//return $nomImage;
								return 'upload_ok';
							}
							else
							{
								// Sinon on affiche une erreur systeme
								return 'upload_error';
							}
						}
						else
						{
							return 'internal_error';
						}
					}
					else
					{
						// Sinon erreur sur les dimensions et taille de l'image
						return 'dimension_error';
					}
				}
				else
				{
					// Sinon erreur sur le type de l'image
					return 'no_image';
				}
			}
			else
			{
				// Sinon on affiche une erreur pour l'extension
				return 'wrong_extension';
			}
		}
		else
		{
			// Sinon on affiche une erreur pour le champ vide
			return 'no_post';
		}
	}

}

?>