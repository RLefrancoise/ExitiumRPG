<?php

function get_directory_files($d) {

	$directory = scandir($d);
	if(!$directory) return false;
	
	/*foreach($directory as $f) {
		if(is_dir($f)) { array_merge($directory, get_directory_files($f)); }
	}*/
	
	return $directory;
}

function scan_Dir($dir) {
    $arrfiles = array();
    if (is_dir($dir)) {
        if ($handle = opendir($dir)) {
            chdir($dir);
            while (false !== ($file = readdir($handle))) { 
                if ($file != "." && $file != "..") { 
                    if (is_dir($file)) { 
                        $arr = scan_Dir($file);
                        foreach ($arr as $value) {
                            $arrfiles[] = $file."/".$value;
                        }
                    } else {
                        $arrfiles[] = /*$dir."/".*/$file;
                    }
                }
            }
            chdir("../");
        }
        closedir($handle);
    }
    return $arrfiles;
}

?>