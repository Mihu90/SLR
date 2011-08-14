<?php
/*
    @author     : Surdeanu Mihai ;
    @date       : 15 august 2011 ;
    @version    : 2.1 ;
    @mybb       : compatibilitate cu MyBB 1.6 (orice versiuni) ;
    @description: Modificare pentru Sistemul de Limba Romana ;
    @homepage   : http://mybb.ro ! Te rugam sa ne vizitezi pentru ca nu ai ce pierde!
    @copyright  : Licenta speciala. Pentru mai multe detalii te rugam sa citesti sectiunea Licenta din cadrul fisierului 
                ReadME.pdf care poate fi gasit in acest pachet. Iti multumim pentru intelegere!
    ====================================
    Ultima modificare a codului : 12.08.2011 18:01
*/

// Poate fi acesat direct fisierul?
if(!defined("IN_MYBB")) {
    	die("This file cannot be accessed directly.");
}

// Se verifica daca exista permisiuni
if (!$mybb->admin['permissions']['rolang']['infos'])
{
    $page->output_header($lang->access_denied);
    $page->add_breadcrumb_item($lang->access_denied, "index.php?module=home-index");
    $page->output_error("<b>{$lang->access_denied}</b><ul><li style=\"list-style-type: none;\">{$lang->access_denied_desc}</li></ul>");
    $page->output_footer();
    exit;
}

// Se include fisierul de limba
rolang_include_lang("global");

$sub_tabs = array(
	"infos" => array(
		"title" => $lang->infos_title,
		"link" => "index.php?module=rolang",
		"description" => $lang->infos_description
	)
);

if($mybb->input['ajax'] && $mybb->input['action'] == "change_template")
{
    // exista functiile necesare modificarii template-urilor?
    if (function_exists("rolang_checkCopyright") && function_exists("rolang_runcmd"))
    {
        // se sterge linkul?
        if (rolang_checkCopyright('footer') && rolang_runcmd('remove')) {
            echo "true_add";
            exit();
        }
        // se adauga linkul
        if (!rolang_checkCopyright('footer') && rolang_runcmd('add'))
        {
            echo "true_remove";
            exit();
        }
        // daca actiunea nu e corecta apare eroare
        echo "false";
    }
    else {
        // daca nu exista apare eroare
        echo "false";
    }
	// se iese fortat
    exit(); 
}

if(!$mybb->input['action'])
{
	$page->add_breadcrumb_item($lang->mod_name);
	$page->add_breadcrumb_item($lang->infos_title);
	$page->output_header($lang->infos_title);

	$page->output_nav_tabs($sub_tabs, 'infos');

    // citeste posibile update-uri din sistemul de cache-ul
    $updates = $mybb->cache->read("rolang_updates");
    
	echo '<div>
	<div class="float_right" style="width:50%;" id="table_right">';

    $perm1 = substr(sprintf('%o', fileperms(MYBB_ROOT."images")), -4);
    $perm2 = substr(sprintf('%o', fileperms(MYBB_ROOT."inc/languages")), -4);
    $nec1 = "0755";
    $nec2 = "0755";
    
    $set1 = ""; $set2 = "";
    if (intval($perm1) < intval($nec1)) {
        $set1 = " <font color='red'>(<b>".$lang->infos_table_permissions_attention."</b>)</font>";    
    }
    if (intval($perm2) < intval($nec2)) {   
        $set2 = " <font color='red'>(<b>".$lang->infos_table_permissions_attention."</b>)</font>";  
    }
    if (empty($set1) && empty($set2)) {
        $message = "<font color='green'>".$lang->infos_table_permissions_set."</font>";
    }
    else {
        $message = "<font color='red'>".$lang->infos_table_permissions_unset."</font>";
    }
    
	$table = new Table;
	$table->construct_header("<small>".$lang->infos_table_permissions_folder."</small>", array('width' => '50%', 'class' => 'align_center'));
	$table->construct_header("<small>".$lang->infos_table_permissions_curent."</small>", array('width' => '25%', 'class' => 'align_center'));
	$table->construct_header("<small>".$lang->infos_table_permissions_recommended."</small>", array('width' => '25%', 'class' => 'align_center'));

    $table->construct_cell("images", array('class' => 'align_center'));
    $table->construct_cell($perm1.$set1, array('class' => 'align_center'));
    $table->construct_cell($nec1, array('class' => 'align_center'));
    $table->construct_row();
    
    $table->construct_cell("inc/languages", array('class' => 'align_center'));
    $table->construct_cell($perm2.$set2, array('class' => 'align_center'));
    $table->construct_cell($nec2, array('class' => 'align_center'));
    $table->construct_row();
    
    $table->construct_cell("<b>".$message."</b>", array('class' => 'align_center', 'colspan' => 3, 'style' => 'background: #ADCBE6 url(../images/rolang/info.png) no-repeat 15px center;'));
    $table->construct_row();

	$table->output($lang->infos_table_permissions_name);
    
    if (function_exists("rolang_checkCopyright") && rolang_checkCopyright('footer')) {
   	echo '
	<div class="form_button_wrapper" style="padding: 10px;">
		<input type="button" name="check" id="copyright_button" value="'.$lang->infos_button_remove.'" class="submit_button" onclick="javascript:copyright();" />
	</div>
	<br />';        
    }
    else {
   	echo '
	<div class="form_button_wrapper" style="padding: 10px;">
		<input type="button" name="check" id="copyright_button" value="'.$lang->infos_button_add.'" class="submit_button" onclick="javascript:copyright();" />
	</div>
	<br />';
    }
    
   	echo $lang->infos_button_description;
 
	echo '</div>
	<div class="float_left" style="width:48%;">';
	
    // se poate sa nu fie instalata o traducere in limba romana
    if (file_exists(MYBB_ROOT."inc/languages/romanian.php"))
        require_once MYBB_ROOT."inc/languages/romanian.php";
    else
        $langinfo['version'] = "-";
    
    // exista posibilitatea de a utiliza clasa ZipArchive
    if (class_exists('ZipArchive'))
        $zip = "<font color=\"green\">".$lang->infos_table_information_your_server_defined."</font>";
    else
        $zip = "<font color=\"red\">".$lang->infos_table_information_your_server_indefined."</font>";
    
    // se construieste tabelul
	$table = new Table;
	$table->construct_header("<small>".$lang->infos_table_information_info."</small>", array('width' => '60%', 'class' => 'align_center'));
	$table->construct_header("<small>".$lang->infos_table_information_value."</small>", array('width' => '40%', 'class' => 'align_center'));

    $table->construct_cell($lang->infos_table_information_mybbversion, array('class' => 'align_center'));
    $table->construct_cell($mybb->version, array('class' => 'align_center'));
    $table->construct_row();
    
    $table->construct_cell($lang->infos_table_information_langversion, array('class' => 'align_center'));
    $table->construct_cell($langinfo['version'], array('class' => 'align_center'));
    $table->construct_row();
    
    $table->construct_cell($lang->infos_table_information_server, array('class' => 'align_center'));
    $table->construct_cell(rolang_websiteUp($mybb->settings['rolang_server_url']), array('class' => 'align_center'));
    $table->construct_row();
    
    $table->construct_cell($lang->infos_table_information_your_server, array('class' => 'align_center'));
    $table->construct_cell($lang->sprintf($lang->infos_table_information_your_server_value, phpversion(), $zip), array('class' => 'align_center'));
    $table->construct_row();

	$table->output($lang->infos_table_information_name);
    
	echo $lang->infos_page_description;
	echo '</div></div>';

	echo '
<script type="text/javascript">
<!--
	var add_template = "'.$lang->infos_button_add.'";
	var remove_template = "'.$lang->infos_button_remove.'";
   
	function copyright()
	{       
		$("copyright_button").value = "'.$lang->infos_button_process.'";

		new Ajax.Request("index.php?module=rolang-infos",
		{
			parameters: { ajax: 1, action: "change_template" },
			onComplete: function(data)
			{ 
				if(data.responseText == "false")
				{
                    $("copyright_button").value = "'.$lang->infos_button_error.'";
				}
                else {
                    if(data.responseText == "true_add") {
                        $("copyright_button").value = add_template;
				    }
                    if(data.responseText == "true_remove") {
                        $("copyright_button").value = remove_template;
                    }
                }
			}
		});
	}
    
// -->
</script>';

	$page->output_footer();
}

// Functie de verificare al unui sait
function rolang_websiteUp($url)
{
    $agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $page = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpcode >= 200 && $httpcode < 300) 
        return "<img src=\"../images/rolang/active.png\" boder=\"0\"/>";
    else 
        return "<img src=\"../images/rolang/delete.png\" boder=\"0\"/>";
}
?>