<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * These functions handles assets or files display. They need the config file (custom.php) to be loaded.
 * 
 * @package TAC Foundation Class
 */

/**
 * Return the title of the site with optional suffix
 * 
 * @param string the suffix to the title e.g. SiteTitle [- mysuffix]
 * @return string base_title + suffix
 */    
function get_title($suffix='')
{
	$ci = &get_instance();
	$title = $ci->config->item('base_title');

	return "$title$suffix";
}

/**
 * Return external defined path e.g. http://www.example.org/project/[path]
 * 
 * @param string the defined path array from the config
 * @param string the filename to append to the path (optional)
 * @return string http://www.example.org/project/[path] + [optional filename] 
 */
function get_ext_path($type, $fname='')
{
	$ci = &get_instance();
	$base_url = $ci->config->item('base_url');

	if($ci->config->item($type) == false)
	{
		// $type is not defined inside the custom config,
		//  set the path as the defined
		$user_path = $type;
	}
	else
	{
		$user_path = $ci->config->item($type);
	}

	$path = "$base_url$user_path/$fname";

	// Remove the last trailing slash (in case $fname is blank)
	return preg_replace('/\/$/', '', $path);
}


/**
 * Return external defined path e.g. http://www.example.org/project/[path]
 * 
 * @param string the defined path array from the config
 * @param string the filename to append to the path (optional)
 * @return string http://www.example.org/project/[path] + [optional filename] 
 */
function get_internal_views_path($fname='')
{
	$ci = &get_instance();
	$base_url = $ci->config->item('base_url');

	if($ci->config->item($type) == false)
	{
		// $type is not defined inside the custom config,
		//  set the path as the defined
		$user_path = $type;
	}
	else
	{
		$user_path = $ci->config->item($type);
	}

	$user_path = 'system/application/views';

	$path = "$base_url$user_path/$fname";

	// Remove the last trailing slash (in case $fname is blank)
	return preg_replace('/\/$/', '', $path);
}


/**
 * Return internal defined path e.g. /home/www/project/[path]
 * 
 * @param string the defined path array from the config or the actual dir name
 * @param string the filename to append to the path (optional)
 * @return string /home/www/project/[path] + [optional filename] 
 */
function get_int_path($type, $fname='')
{
	$ci = &get_instance();

	$base_dir = dirname($_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_NAME']);
	$base_dir = str_replace('//', '/', $base_dir);
	// Windows compatibility, remove drive letter (C:, D:, etc)
	$base_dir = preg_replace('/^([A-Z]):/', '', $base_dir);

	if($ci->config->item($type) == false)
	{
		// $type is not defined inside the custom config,
		//  set the path as the defined
		$user_path = $type;
	}
	else
	{
		$user_path = $ci->config->item($type);
	}

	$path = "$base_dir/$user_path/$fname";

	// Remove the last trailing slash (in case $fname is blank)
	return preg_replace('/\/$/', '', $path);
}


/**
 * Reads XML file, and remove any whitespaces between nodes.
 * 
 * @param   string  The xml filename, with or without .xml extension
 * @return  string  The XML file content as one long string
 */
function get_xml_contents($fname)
{
	$fname = preg_replace("/\.xml$/", '', $fname);

	$xml = file_get_contents("$fname.xml");
	$xml = preg_replace('/>\s+</', '><', $xml);

	return $xml;
}


/**
 * Generate <img src=.. tag
 * 
 * @param string image filename (may include path)
 * @return string <img src=filename...
 */
function disp_img($fname)
{
	return "<img src=\"$fname\" border=\"0\" />";
}

/**
 * Generate <script language='javascript' src=... tag
 * 
 * @param string JS filename (may include path)
 * @return string <script language='javascript' src=filename
 */
function disp_js($fname)
{
	// Remove .js from the filename (if any)
	$fname = preg_replace("/\.js$/", '', $fname);

	return "<script language=\"javascript\" type=\"text/javascript\"  src=\"$fname.js\"></script>";
}

/**
 * Generate <link rel='stylesheet' type='text/css' href=... tag
 * 
 * @param   string  CSS filename (may include path)
 * @return  string  The <link rel="... string
 */
function disp_css($fname)
{
	// Remove .css from the filename (if any)
	$fname = preg_replace("/\.css$/", '', $fname);

	return "<link rel=\"stylesheet\" type=\"text/css\" href=\"$fname.css\" />";
}

/**
 * Generate JS script to display interactive flash without user click. This need the AC_RunActiveContent.js to be included.
 * The root of the .swf is the views folder 
 * 
 * @param   string  The filename of swf, with or without the .swf extension.
 * @param   int     The width of the swf.
 * @param   int     The height of the swf.
 * @param   string  The background color of the swf. (optional)
 * 
 * @return  string  The full javascript strings.
 */
function disp_swf($fname, $width, $height, $bgcolor='#FFFFFF')
{
	// Remove .swf from the filename (if any)
	$fname = preg_replace("/\.swf$/", '', $fname);

	// Sanitize width and height
	$width  = (int)$width;
	$height = (int)$height;

	return "<script type=\"text/javascript\">".
	"AC_FL_RunContent( 'codebase','http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0','width','$width','height','$height','id','$fname','align','middle','src','$fname','quality','high','bgcolor','$bgcolor','name','$fname','allowscriptaccess','sameDomain','pluginspage','http://www.macromedia.com/go/getflashplayer','movie','$fname' ); //end AC code".
	"</script>";
}

/**
 * Return sanitized string for output
 * 
 * @param string the string to be sanitized.
 * @return string sanitized string
 */
function safe_output($output)
{
	$output = htmlentities($output);
	$output = stripslashes($output);

	return $output;
}


/**
 * Display the base path (points to the views folder).
 * 
 * @param   string  Any string to be appended to the base
 * @return string the <base href=... tag
 */
function disp_base()
{
	// Get the system path
	#previous
	//preg_match("/(\w+)\/helpers$/", dirname(__FILE__), $matches);
	//return "<base href=\"". base_url() ."$matches[1]/application/views/\" /></base>";

	#modified
	return "<base href=\"". base_url() ."application/views/\" />";
}


/**
 * Display the base path (points to the views folder).
 * 
 * @param   string  Any string to be appended to the base
 * @return string the <base href=... tag
 */
function disp_views(){
	#modified
	return base_url()."application/views/";
}


/**
 * Display doctype (http://www.w3.org/QA/2002/04/Web-Quality)
 * 
 * @param string xhtml or html
 * @param string strict, trans (transitional), frame (frameset).
 * 
 * @return string the doctype 
 */
function disp_doctype($type='xhtml', $subtype='trans')
{
	$doctype = array();

	// HTML doctypes
	$doctype['html']['strict'] = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN "http://www.w3.org/TR/html4/strict.dtd">';
	$doctype['html']['trans']  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
	$doctype['html']['frame']  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';

	// XHTML doctypes
	$doctype['xhtml']['strict'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
	$doctype['xhtml']['trans']  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	$doctype['xhtml']['frame']  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';

	if(empty($doctype[$type][$subtype]))
	{
		// Invalid type/subtype, return xhtml, trans as default
		return $doctype['xhtml']['trans'];
	}
	else
	{
		return $doctype[$type][$subtype];
	}
}

/**
 * Simple date format validation according to dd-mm-yyyy format
 * 
 * @param  string  the date to validate
 * @return boolean whether date is valid
 */
function date_validate($date)
{
	$status = false;

	// Check for the dash
	if(substr_count($date, '-') == 2)
	{
		list($date, $month, $year) = explode('-', $date);

		// Check whether they are numbers
		if(is_numeric($date) && is_numeric($month) && is_numeric($year))
		{
			// Check minimum and maximum values
			if(($date >= 1 && $date <= 31) && ($month >= 1  && $month <= 12) && ($year >= date('Y')))
			{
				$status = true;
			}
		}
	}

	return $status;
}

/**
 * Chmod the files and directories under $dir owned by Apache to $mode (recursive)
 * 
 * @param string initial directory (absolute). 
 * @param octal the chmod mode
 */
function chmod2($dir, $mode=0777)
{
	if ($handle = opendir($dir))
	{
		while (false !== ($file = readdir($handle)))
		{
			if ($file != "." && $file != "..")
			{
				chmod("$dir/$file", $mode);

				if(is_dir("$dir/$file"))
				{
					chmod2("$dir/$file");
				}
			}
		}
		closedir($handle);
	}
}

/**
 * Date conversion from dd-mm-yyyy to unix timestamp
 * 
 * @param  string  the date to convert
 * @return int  converted date
 */
function to_timestamp($date)
{
	$timestamp = 0;

	if(!empty($date))
	{
		if($this->_date_validate($date))
		{
			list($day, $month, $year) = explode('-', $date);

			$day   = (int)$day;
			$month = (int)$month;
			$year  = (int)$year;

			$timestamp = mktime(0, 0, 0, $month, $day, $year);
		}
	}

	return $timestamp;
}

/**
 * Date conversion from unix timestamp to dd-mm-yyyy
 * 
 * @param  int  the timestamp to convert
 * @return string  converted date
 */
function from_timestamp($timestamp)
{
	if(!empty($timestamp))
	{
		return date("d-m-Y", (int)$timestamp);
	}
	else
	{
		return '';
	}
}

function get_extension($filename)
{
	$x = explode('.', $filename);

	return '.'.end($x);
}

function get_filename($filename)
{
	$x = explode('.', $filename);

	return $x[0];
}

function calculate_date($date)
{
	$date = explode(' ',$date);
	$date_day = explode('-',$date[0]);

	$date = $date_day[2].'-'.str_pad($date_day[1], 2, "0", STR_PAD_LEFT).'-'.str_pad($date_day[0], 2, "0", STR_PAD_LEFT).' '.$date[1];
	
	return $date;
}

function array2json($arr) {
    if(function_exists('json_encode')) return json_encode($arr); //Lastest versions of PHP already has this functionality.
    $parts = array();
    $is_list = false;

    //Find out if the given array is a numerical array
    $keys = array_keys($arr);
    $max_length = count($arr)-1;
    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
        $is_list = true;
        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
            if($i != $keys[$i]) { //A key fails at position check.
                $is_list = false; //It is an associative array.
                break;
            }
        }
    }

    foreach($arr as $key=>$value) {
        if(is_array($value)) { //Custom handling for arrays
            if($is_list) $parts[] = array2json($value); /* :RECURSION: */
            else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
        } else {
            $str = '';
            if(!$is_list) $str = '"' . $key . '":';

            //Custom handling for multiple data types
            if(is_numeric($value)) $str .= $value; //Numbers
            elseif($value === false) $str .= 'false'; //The booleans
            elseif($value === true) $str .= 'true';
            else $str .= '"' . addslashes($value) . '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Object?)

            $parts[] = $str;
        }
    }
    $json = implode(',',$parts);
    
    if($is_list) return '[' . $json . ']';//Return numerical JSON
    return '{' . $json . '}';//Return associative JSON
} 



?>
