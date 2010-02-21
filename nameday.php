<?php
/*
Plugin Name: Name Day
Description: Name Day, prints the name day (Swedish namnsdag). See the readme for how to configure.
Version: 1.0.5
Author: Thomas L
Plugin URI: http://dev.liajnad.se/nameday
Author URI: http://dev.liajnad.se
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

#require_once(ABSPATH . 'wp-includes/streams.php');
#require_once(ABSPATH . 'wp-includes/gettext.php');

require_once ( dirname(__FILE__) . '/inc.swg-plugin-framework.php');


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
       
  $DB_PREFIX=$wpdb->prefix;
  $table=$DB_PREFIX."z_namedays";
     
  // Check if Table exists 
  if($wpdb->get_var("show tables like '$table'") != $table) 
	{
             $sql = "CREATE TABLE ".$table." ( day int(11) NOT NULL, month int(11) NOT NULL, names varchar(100) NOT NULL, special varchar(30), flagday char(1) DEFAULT 'N' NOT NULL, lang varchar(2) NOT NULL, PRIMARY KEY (day,month,lang));";
             require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
             dbDelta($sql);
                
             define('PLUG_URLPATH', WP_CONTENT_URL.'/plugins/'.plugin_basename( dirname(__FILE__)) );
	     $plugin_dir = PLUG_URLPATH;

	     $myFile = $plugin_dir."/namedays.import";

   	     $fh = fopen($myFile, 'r');

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
  
//  Checks for valid languages
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

 <input type="submit" name="submit" value="submit" id="blocker-button"/>
</form>
<?


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

class NameDay extends NameDay_SWGPluginFramework {



/// This is the actual printout
function print_nameday()
{
  global $wpdb,$DB;

  $day=get_the_time('d');
  $month=get_the_time('m');
  if (!isset($day)) return;

$v = stripslashes($this->g_opt['nameday_calendar']);

if (!isset($day)) return;
$query = "SELECT names FROM $DB where day=$day AND month=$month AND lang='$v';";
$res=$wpdb->get_row($query,ARRAY_N);
$err = mysql_error();
if($err) { echo "Load failed: " . $query . "<BR>" . $err; }

$name=$res[0];


  echo stripslashes($this->g_opt['nameday_pre'])."".$name."".stripslashes($this->g_opt['nameday_post']);

}


function ApplyNameDay() {
}

/**
       * Convert option prior to save ("COPTSave").
         * !!!! This function is used by the framework class !!!!
         */
        function COPTSave($optname) {
                switch ($optname) {
                        case 'mamo_excludedpaths':                            return $this->LinebreakToWhitespace($_POST[$optname]);                        default:                                return $_POST
[$optname];
                } // switch
        }


        /**
         * Convert option before HTML output ("COPTHTML").
         * *NOT* used by the framework class
         */
        function COPTHTML($optname) {
                $optval = $this->g_opt[$optname];
                switch ($optname) {
                        case 'mamo_excludedpaths':
                                return $this->WhitespaceToLinebreak($optval);
                        default:
                                return $optval;
                } // switch
        }

function PluginOptionsPage() {
 $this->AddContentMain(__('Calendar to use',$this->g_info['ShortName']), "
                        <table border='0'><tr>
                                <td width='130'>
                                        <p style='font-weight: bold; line-height: 2em;'>

                        		<select name=nameday_calendar value=se>

					<option value=dk >dk</option>
					<option value=no >no</option>
					<option value=se >se</option>
					</select><br />
                                        </p>
                                </td>
                        </tr></table><h2>Please check language every time you save</h2>
			<br>Selected Language: ".stripslashes($this->g_opt['nameday_calendar'])."
                        ");

                $this->AddContentMain(__('Pre/Post tags',$this->g_info['ShortName']), "
                        <table width='100%' cellspacing='2' cellpadding='5' class='editform'> 
                        <tr valign='center'> 
                                <th align=left width='250px' scope='row'><label for='nameday_pre'>".__('Pre',$this->g_info['nameday_pre']).":</label></th> 
                                <td width='100%'><input style='font-weight:bold;' name='nameday_pre' type='text' id='nameday_pre' value='" . htmlspecialchars(stripslashes($this->g_opt['nameday_pre'])) . "' size='60' />eg. &lt;font color=blue></td>
                        </tr>
                        <tr valign='center'> 
                                <th align=left width='250px' scope='row'><label for='nameday_post'>".__('Post',$this->g_info['nameday_post']).":</label></th> 
                                <td width='100%'><input style='font-weight:bold;' name='nameday_post' type='text' id='nameday_post' value='" . htmlspecialchars(stripslashes($this->g_opt['nameday_post'])) . "' size='60' />eg. &lt;/font></td>
</table>
                        ");


  $this->AddContentMain(__('Usage/Example',$this->g_info['ShortName']), "
                        <table width='100%' cellspacing='2' cellpadding='5' class='editform'> 
                        <tr valign='center'>In your themes php file insert the following code where you want the name to appear.<br><br>
<pre><code>    &lt;? if (function_exists('print_nameday')) { print_nameday(); } ?&gt; </code></pre><br>
Below is an example how it will look<br><br>".

			stripslashes($this->g_opt['nameday_pre'])."Kalle".stripslashes($this->g_opt['nameday_post'])."
</table>
                        ");



		// Sidebar, we can also add individual items...
                $this->PrepareStandardSidebar();
                $this->GetGeneratedOptionsPage();


}


}
// end class



if( !isset($myNameDay)  ) {
        // Create a new instance of your plugin that utilizes the WordpressPluginFramework and initialize the instance.
        $myNameDay = new NameDay();

        $myNameDay->Initialize(
                // 1. We define the plugin information now and do not use get_plugin_data() due to performance.
                array(
                        # Plugin name
                                'Name' =>                       'NameDay',
                        # Author of the plugin
                                'Author' =>             'Thomas Lindholm',
                        # Authot URI
                                'AuthorURI' =>          'http://dev.liajnad.se/',
                        # Plugin URI
                                'PluginURI' =>          'http://dev.liajnad.se/nameday',
                        # Support URI: E.g. WP or plugin forum, wordpress.org tags, etc.
                                'SupportURI' =>         'http://wordpress.org/tags/nameday',
                        # Name of the options for the options database table
                                'OptionName' =>         'NameDay',
                        # Old option names to delete from the options table; newest last please
                                'DeleteOldOpt' =>       array('NameDay1', 'NameDay2'),
                        # Plugin version
                                'Version' =>            '1.0.5',
                        # First plugin version of which we do not reset the plugin options to default;
                        # Normally we reset the plugin's options after an update; but if we for example
                        # update the plugin from version 2.3 to 2.4 und did only do minor changes and
                        # not any option modifications, we should enter '2.3' here. In this example
                        # options are being reset to default only if the old plugin version was < 2.3.
                                'UseOldOpt' =>          '2.3',
                        # Copyright year(s)
                                'CopyrightYear' =>      '2009-2010',
                        # Minimum WordPress version
                                'MinWP' =>                      '2.9',
                        # Do not change; full path and filename of the plugin
                                'PluginFile' =>         __FILE__,
                        # Used for language file, nonce field security, etc.
                                'ShortName' =>          'NameDay',
                        ),

                // 2. We define the plugin option names and the initial options
                array(
                        'nameday_calendar' =>                      'se',
                        'nameday_pre' =>         '<a title=Nameday><font color=blue>',
                        'nameday_post' =>                      '</a></font>',
                ));

}
        $myNameDay->ApplyNameDay();


function print_nameday() {
global $myNameDay;
 $myNameDay->ApplyNameDay();

$myNameDay->print_nameday();
}


			
add_filter('init', 'tz_nameday_init');

register_activation_hook( __FILE__, 'nameday_install' );




?>
