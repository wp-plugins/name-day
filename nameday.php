<?php
/*
Plugin Name: Name Day
Description: Name Day, prints the name day (Swedish namnsdag). See the readme for how to configure.
Version: 1.0.2
Author: Thomas L
Plugin URI: http://www.liajnad.se/nameday
Author URI: http://www.liajnad.se
*/

/*  

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

*/

require_once(ABSPATH . 'wp-includes/streams.php');
require_once(ABSPATH . 'wp-includes/gettext.php');

# require_once(ABSPATH . 'wp-includes/pluggable.php');


$DB_PREFIX=$wpdb->prefix;
$DB=$DB_PREFIX."z_namedays";

function tz_nameday_init() {
	$tz_name = get_option('tz_nameday');
	if ($tz_name == null) {
		$defaults = array();
	}
}

function tz_nameday_page()
  {
  global $wpdb,$DB;
  
          global $wpdb;

	
        $DB_PREFIX=$wpdb->prefix;
        $table=$DB_PREFIX."z_namedays";
      
        if($wpdb->get_var("show tables like '$table'") != $table) {

                $sql = "CREATE TABLE ".$table." ( day int(11) NOT NULL, month int(11) NOT NULL, names varchar(100) NOT NULL, special varchar(30), flagday char(1) DEFAULT 'N' NOT NULL, lang varchar(2) NOT NULL, PRIMARY KEY (day,month,lang));";
                
                
               
    
                require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
                dbDelta($sql);
                
         define('PLUG_URLPATH', WP_CONTENT_URL.'/plugins/'.plugin_basename( dirname(__FILE__)) );
	     $plugin_dir = PLUG_URLPATH;

		 $myFile = $plugin_dir."/namedays.import";

			$fh = fopen($myFile, 'r');
			#while (!feof($fh)) {
			#  $theData .= fread($fh, 8192);
			#}
			#fclose($fh);

			if ($fh) {
 			   while (!feof($fh)) {
  			      $buffer = fgets($fh, 4096);
					$q=explode("|",$buffer);
    			   $query="insert into $table values($q[0],$q[1],'$q[2]','$q[3]','$q[4]','$q[5]');";
    			   $wpdb->query($query);
 			}       
  
    		fclose($fh);
}}
  
#Checks for valid languages
$query = "select distinct(lang) from $DB order by lang";
$res=$wpdb->get_results($query,ARRAY_N);




$err = mysql_error();
if($err) { echo "Load failed: " . $query . "<BR>" . $err; }




  echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/name-day/admin.css" />';
  
  $v = get_option('tz_nameday');
  
	  if (isset($_POST['submit'])) {

		  echo('<div >Your new settings were saved.</div>');
	
	  } else {
		  echo('<div ></div>');
	  }
?>
<div >
	<div >
	<h2>Name Day plugin Setttings</h2>
		<p>
		Please select which calendar to use (Currently only swedish installed by default).
		</p>
	</div>
	
		<?php
// Lets save the data....

	if (isset($_POST['submit'])) {
		  // let's rock and roll
		  

		  unset($_POST['submit']);
			$a = array();
			}
			
			if (isset($_POST['nameday_lang'])) {
			$a['nameday_lang'] = $_POST['nameday_lang']; 
			}
			
				
			if (isset($_POST['bypass-rss'])) {
				$a['bypass-rss'] = 1;
			} else {
				$a['bypass-rss'] = 0;
			}
			
				
			if (isset($_POST['bypass-xml'])) {
				$a['bypass-xml'] = 1;
			} else {
				$a['bypass-xml'] = 0;
			}
			
			if (isset($_POST['nameday_pre'])) {
			$a['nameday_pre'] = $_POST['nameday_pre']; 
			}
			
			if (isset($_POST['nameday_post'])) {
			$a['nameday_post'] = $_POST['nameday_post']; 
			}
			
			
		if (isset($_POST['nameday_lang'])) {
			$a['nameday_lang'] = $_POST['nameday_lang']; 
			
			  $values = serialize($a);
             update_option('tz_nameday', $values);
		}
			
			$v = get_option('tz_nameday');
			if (!is_array($v)) {
				$v = unserialize($v);
			}
			
				if (!isset($v['nameday_pre'])) {
			$v['apa'] = 0;
			}	
			
				if (!isset($v['nameday_post'])) {
			$v['apa'] = 0;
			}	
			
			?>
				  <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
				  
			<select name="nameday_lang" value="se">
			<?
            foreach ($res as $lang) {
                 					if($lang[0] == $v[nameday_lang])
                                               $selected = 'selected="selected"';
                                      else
                                             $selected = '';

                                        $option = '<option value="'.$lang[0].'" '.$selected.'>';
                                        $option .= $lang[0];
                                        $option .= '</option>';
                                        echo $option;
                                }
                        echo "</select>";
                        
                 
			
	
            
   
?>
<br /><br />
<div>If you want any special html code or other text to appear before and after the list of names use this fields.</div>
<div >Pre<small> ([&lt;font color=blue>)</small></div>
		<div >&nbsp; <input text="text" size="20" name="nameday_pre" type="text" value="<?php $str = $v['nameday_pre']; echo stripslashes($str); ?>" /></div>

	<div >Post<small> (&lt;/font>])</small></div>
		<div >&nbsp; <input text="text" size="20" name="nameday_post" type="text" value="<?php $str = $v['nameday_post']; echo stripslashes($str); ?>" /></div>
<br /><br /

<div><? 		echo "<br>Example: ".$v['nameday_pre']."Mary, Peter".$v['nameday_post']."<br>"; ?></div>
 <input type="submit" name="submit" value="submit" id="blocker-button"/>
</form>
<?


}



function get_nameday($day, $month)
{
global $wpdb,$DB;

$v = get_option('tz_nameday');
if (!is_array($v)) {
				$v = unserialize($v);
			}

#$month=date("m");
#$day=date("d");
if (!isset($day)) return;
#$day=24;
#$month=12;
$query = "SELECT names FROM $DB where day=$day AND month=$month AND lang='$v[nameday_lang]';";
#echo $query;

$res=$wpdb->get_row($query,ARRAY_N);
$err = mysql_error();
if($err) { echo "Load failed: " . $query . "<BR>" . $err; }

return $res[0];

}

/// This is the actual printout
function print_nameday()
{
  global $wpdb,$DB;

  $v = get_option('tz_nameday');
  if (!is_array($v)) {
				$v = unserialize($v);
			}
  $day=get_the_time('d');
  $month=get_the_time('m');
  if (!isset($day)) return;
  echo $v[nameday_pre]."".get_nameday($day, $month)."".$v[nameday_post];
  #echo "Disabled";
}

function tz_nameday_options_menu() {
	add_options_page('Tz NameDay', 'Name Day', 9, __FILE__, tz_nameday_page);
}


//function to create the table on plugin activation
// add_action('activate_nameday/nameday.php','nameday_install');
function nameday_install () {
        global $wpdb;

	
        $DB_PREFIX=$wpdb->prefix;
        $table=$DB_PREFIX."z_namedays";
	
        $query="drop table $table;";
    	$wpdb->query($query);
        if($wpdb->get_var("show tables like '$table'") != $table) {

                $sql = "CREATE TABLE ".$table." ( day int(11) NOT NULL, month int(11) NOT NULL, names varchar(100) NOT NULL, special varchar(30), flagday char(1) DEFAULT 'N' NOT NULL, lang varchar(2) NOT NULL, PRIMARY KEY (day,month,lang));";
                
                
               
    
                require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
                dbDelta($sql);
                
         define('PLUG_URLPATH', WP_CONTENT_URL.'/plugins/'.plugin_basename( dirname(__FILE__)) );
	     $plugin_dir = PLUG_URLPATH;

		 $myFile = $plugin_dir."/namedays.import";

			$fh = fopen($myFile, 'r');
			#while (!feof($fh)) {
			#  $theData .= fread($fh, 8192);
			#}
			#fclose($fh);

			if ($fh) {
 			   while (!feof($fh)) {
  			      $buffer = fgets($fh, 4096);
					$q=explode("|",$buffer);
    			   $query="insert into $table values($q[0],$q[1],'$q[2]','$q[3]','$q[4]','$q[5]');";
    			   $wpdb->query($query);
 			}       
  
    		fclose($fh);
}


        }
}

add_filter( 'plugin_action_links', 'tz_nameday_plugin_actions', 10, 2 );
function tz_nameday_plugin_actions($links, $file){
        static $this_plugin;

        if( !$this_plugin ) $this_plugin = plugin_basename(__FILE__);

        if( $file == $this_plugin ){
                $settings_link = '<a href="options-general.php?page=name-day/nameday.php">' . __('Settings') . '</a>';
                $links = array_merge( array($settings_link), $links); // before other links
        }
        return $links;
}


			
add_filter('init', 'tz_nameday_init');

add_action('admin_menu', 'tz_nameday_options_menu'); 
register_activation_hook( __FILE__, 'nameday_install' );



?>
