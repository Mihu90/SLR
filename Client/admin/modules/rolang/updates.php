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
    Ultima modificare a codului : 14.08.2011 11:53
*/

// Poate fi acesat direct fisierul?
if(!defined("IN_MYBB")) {
    	die("This file cannot be accessed directly.");
}

// Se verifica daca exista permisiuni
if (!$mybb->admin['permissions']['rolang']['updates'])
{
    $page->output_header($lang->access_denied);
    $page->add_breadcrumb_item($lang->access_denied, "index.php?module=home-index");
    $page->output_error("<b>{$lang->access_denied}</b><ul><li style=\"list-style-type: none;\">{$lang->access_denied_desc}</li></ul>");
    $page->output_footer();
    exit;
}

// Se include fisierul de limba
rolang_include_lang("global");

// Afisarea meniului paginii
$sub_tabs = array(
	"updates" => array(
		"title" => $lang->updates_title,
		"link" => "index.php?module=rolang-updates",
		"description" => $lang->updates_description
	)
);

// Ruleaza in momentul in care se cere de catre user verificarea update-urilor
if($mybb->input['ajax'] && $mybb->input['action'] == "get_updates")
{
    // se intoarce o lista cu posibile actualizari
	$updates = rolang_checkForUpdates();
    if ($updates != false) {
        $updates['succes'] = 1;
    }
    else {
        $updates['succes'] = 0;
    }
    // timpul actualizarii
	$updates['dateline'] = TIME_NOW;
    // lista cu actualizari este salvata in cache
	$mybb->cache->update("rolang_updates", $updates);
    // se afiseaza pe ecranul utilizatorului posibile actualizari
	rolang_displayUpdates($updates, false);
	// se iese fortat
    exit(); 
}

// Ruleaza in momentul in care se cere de catre user o verificare a setului de imagini a template-urilor
if($mybb->input['ajax'] && $mybb->input['action'] == "add_template_images")
{
    switch(intval($mybb->input['tid']))
    {
        case -1 :
            // nu se afiseaza nimic
            echo $lang->updates_table_template_answer;
        break;
        case 0 :
            // se verifica toate temele
            $query = $db->simple_select("themes", "tid,name,properties", "", array("order_by" => 'name', "order_dir" => 'ASC'));
            // s-ar putea sa nu exista nicio tema pe forum
            $counted = true;
            $result = "";
            while ($row = $db->fetch_array($query)) 
            {
                $error = false;
                $prop = unserialize($row['properties']);
                $imgdir = $prop['imgdir'];
                // daca unul dintre directoarele mama nu exista incepe procesul de copiere
                if (!file_exists(MYBB_ROOT.$imgdir."/romanian/") || !file_exists(MYBB_ROOT.$imgdir."/groupimages/romanian/")) {
                    // copierea directoarelor se face doar daca e nevoie (daca un director exista nu mai este copiat)
                    // mai intai verificam daca exista directorul 'groupimages' pe server
                    if (!file_exists(MYBB_ROOT.$imgdir."/groupimages/")) {
                        // daca nu exista se creaza directorul
                        if (!mkdir(MYBB_ROOT.$imgdir."/groupimages", 0755))
                            $error = true;
                    }
                    if (!$error && (file_exists(MYBB_ROOT.$imgdir."/romanian/") || rolang_add_templateset("romanian", $imgdir)) && (file_exists(MYBB_ROOT.$imgdir."/groupimages/romanian/") || rolang_add_templateset("groupimages/romanian", $imgdir))) {
                        // s-a realizat cu succes
                        $result .= $lang->sprintf($lang->updates_table_template_added, htmlspecialchars($row['name']));
                    }
                    else {
                        // a aparut o eroare la copierea unui set
                        $result .= $lang->sprintf($lang->updates_table_template_error, htmlspecialchars($row['name']));
                    }
                }
                else {
                    // setul de imagini deja exista in tema
                    $result .= $lang->sprintf($lang->updates_table_template_exist, htmlspecialchars($row['name']));
                }
                // s-a gasit cel putin o tema
                $counted = false;
            }
            if ($counted) {
                $result .= $lang->updates_table_template_nothemes;
            }
            echo "<textarea cols='10' rows='1' readonly='true'>".$result."</textarea>";
        break;
        default :
            $error = false;
            // se verifica daca input-ul este altceva in afara de numar
            if (!is_numeric($mybb->input['tid']))
                $error = true;
            if (!$error) 
            {
                // se verifica daca tema cu id-ul ales exista in sistem
                $query = $db->simple_select("themes", "tid,name,properties", "tid = ".intval($mybb->input['tid']), array("order_by" => 'name', "order_dir" => 'ASC', "limit" => 1));
                if ($db->num_rows($query) > 0) 
                {
                    $row = $db->fetch_array($query);
                    $result = "";
                    $prop = unserialize($row['properties']);
                    $imgdir = $prop['imgdir'];
                    // daca unul dintre directoarele mama nu exista incepe procesul de copiere
                    if (!file_exists(MYBB_ROOT.$imgdir."/romanian/") || !file_exists(MYBB_ROOT.$imgdir."/groupimages/romanian/")) {
                        // copierea directoarelor se face doar daca e nevoie (daca un director exista nu mai este copiat)
                        // mai intai verificam daca exista directorul 'groupimages' pe server
                        if (!file_exists(MYBB_ROOT.$imgdir."/groupimages/")) {
                            // daca nu exista se creaza directorul
                            if (!mkdir(MYBB_ROOT.$imgdir."/groupimages", 0755))
                                $error = true;
                        }
                        if (!$error && (file_exists(MYBB_ROOT.$imgdir."/romanian/") || rolang_add_templateset("romanian", $imgdir)) && (file_exists(MYBB_ROOT.$imgdir."/groupimages/romanian/") || rolang_add_templateset("groupimages/romanian", $imgdir))) {
                            // s-a realizat cu succes
                            $result .= $lang->sprintf($lang->updates_table_template_added, htmlspecialchars($row['name']));
                        }
                        else {
                            // a aparut o eroare la copierea unui set
                            $result .= $lang->sprintf($lang->updates_table_template_error, htmlspecialchars($row['name']));
                        }
                    }
                    else {
                        // setul de imagini deja exista in tema
                        $result .= $lang->sprintf($lang->updates_table_template_exist, htmlspecialchars($row['name']));
                    }
                
                }
                else {
                    $result = $lang->updates_table_template_noid;
                }
            }
            else {
                $result = $lang->updates_table_template_nonumber; 
            }
            echo "<textarea cols='10' rows='1' readonly='true'>".$result."</textarea>";
    }
	// se iese fortat
    exit(); 
}

// Ruleaza in momentul in care se cere de catre user instalarea unui update
if($mybb->input['ajax'] && $mybb->input['action'] == "install_updates")
{
    // verifica pentru existenta unei versiuni mai vechi
    if (file_exists(MYBB_ROOT."inc/languages/romanian.php")) {
        require_once MYBB_ROOT."inc/languages/romanian.php";
        $images = false;
    }
    else {
        // daca nu exista inca o versiune de traducere atunci se considera versiunea 0 ca implicita
        $langinfo['version'] = 0;
        $images = true;
    }
    if (!$images && ($mybb->settings['rolang_server_images'] == 1)) {
        $images = true;
    }
    if (intval($mybb->input['version']) < intval($langinfo['version']))
    {
        echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":ver</font>";  
        exit();        
    }
    // se poate scrie pe server?
    if (!is_writable(MYBB_ROOT."inc/languages/") && (!$images || !is_writable(MYBB_ROOT."images/")))
    {
        echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":wri</font>";  
        exit(); 
    }
    // se decodifica adresa pachetului
    $url = base64_decode($mybb->input['link']);
    $image = base64_decode($mybb->input['image']);
    // verifica adresa URL a pachetului
    if (!rolang_websiteUp($url) || ($images && !rolang_websiteUp($image)))
    {
        echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":url</font>";  
        exit(); 
    }
    else
    {
        // se intoarce numele arhivei din URL
        $filename = basename($url);
        $filename1 = basename($image);
        // mai exista un fisier cu acest nume?
        if (file_exists(MYBB_ROOT."inc/languages/".$filename) || ($images && file_exists(MYBB_ROOT."images/".$filename1)))
        {
            echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":ext</font>";  
            exit(); 
        }
        // se descarca fisierul pe server
        $file = fopen(MYBB_ROOT."inc/languages/".$filename, 'w');
        $init = curl_init($url);
        curl_setopt($init, CURLOPT_FILE, $file);
        $data = curl_exec($init);
        curl_close($init);
        fclose($file);
        if ($images)
        {
            $file = fopen(MYBB_ROOT."images/".$filename1, 'w');
            $init = curl_init($image);
            curl_setopt($init, CURLOPT_FILE, $file);
            $data = curl_exec($init);
            curl_close($init);
            fclose($file);
        }
        // inainte de dezarhivare se verifica daca clasa ZipArchive exista pe server
        if (class_exists('ZipArchive')) {
            // daca exista se va folosi ca si metoda de dezarhivare clasa ZipArchive
            $zip = new ZipArchive;
            if ($zip->open(MYBB_ROOT."inc/languages/".$filename) === true) 
            {
                $zip->extractTo(MYBB_ROOT."inc/languages/");
                $zip->close();
                if ($images)
                {
                    $zip1 = new ZipArchive;  
                    if ($zip1->open(MYBB_ROOT."images/".$filename1) === true) {
                        $zip1->extractTo(MYBB_ROOT."images/");
                        $zip1->close();
                    }
                    else {
                        echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":zip</font>"; 
                        // se sterge arhiva descarcata de pe server
                        unlink(MYBB_ROOT."images/".$filename1); 
                        exit();                   
                    }
                }
                // se sterge arhiva descarcata de pe server
                if (unlink(MYBB_ROOT."inc/languages/".$filename) && (!$images || unlink(MYBB_ROOT."images/".$filename1))) {
                    if (($mybb->settings['rolang_server_setlanguage'] == 1) && ($mybb->settings['bblanguage'] != "romanian") && file_exists(MYBB_ROOT."inc/languages/romanian.php")) {
                        $db->query("UPDATE ".TABLE_PREFIX."settings SET value = 'romanian' WHERE name = 'bblanguage'");
                        // se reconstruiesc setarile
                        rebuild_settings();
                    }
                    echo "<font color='green'>".$lang->updates_table_info_installed."</font>"; 
                }
                else {
                    echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":del</font>"; 
                    exit();
                }
            }
            else {
                echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":zip</font>"; 
                // in cazul in care dezarhivarea a esuat se sterge arhiva de pe server
                unlink(MYBB_ROOT."inc/languages/".$filename);   
                exit();
            }
        }
        else {
            // daca nu exista clasa ZipArchive atunci se va folosi clasa definita de noi
            if (!file_exists(MYBB_ROOT."inc/class_pclzip.php")) {
                echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":pcl</font>"; 
                // se sterge arhiva de pe server
                unlink(MYBB_ROOT."inc/languages/".$filename); 
                exit();
            }
            else {
           	    require_once MYBB_ROOT."inc/class_pclzip.php";
            }
            // daca clasa cu care lucram nu exista atunci se intoarce iar o eroare
            if (!class_exists('PclZip')) {
                echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":pcl</font>"; 
                // se sterge arhiva de pe server
                unlink(MYBB_ROOT."inc/languages/".$filename);                
                exit();                
            }
            $zip = new PclZip(MYBB_ROOT."inc/languages/".$filename);
            if ($zip->extract(PCLZIP_OPT_PATH, MYBB_ROOT."inc/languages/") != 0) {
                // totul a decurs bine
                // se dezarhiveaza si pachetul de imagini ?
                if ($images) {
                    $zip1 = new PclZip(MYBB_ROOT."images/".$filename1); 
                    if ($zip->extract(PCLZIP_OPT_PATH, MYBB_ROOT."images/") == 0) {
                        // ceva nu e bine
                        echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":zip</font>"; 
                        // se sterge arhiva descarcata de pe server
                        unlink(MYBB_ROOT."images/".$filename1); 
                        exit();                   
                    }
                }
                // se sterge arhiva descarcata de pe server
                if (unlink(MYBB_ROOT."inc/languages/".$filename) && (!$images || unlink(MYBB_ROOT."images/".$filename1))) {
                    if (($mybb->settings['rolang_server_setlanguage'] == 1) && ($mybb->settings['bblanguage'] != "romanian") && file_exists(MYBB_ROOT."inc/languages/romanian.php")) {
                        $db->query("UPDATE ".TABLE_PREFIX."settings SET value = 'romanian' WHERE name = 'bblanguage'");
                        // se reconstruiesc setarile
                        rebuild_settings();
                    }
                    echo "<font color='green'>".$lang->updates_table_info_installed."</font>"; 
                }
                else {
                    echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":del</font>"; 
                    exit();
                }
            } 
            else {
                // a aparut eroare la dezarhivare
                echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":zip</font>"; 
                // in cazul in care dezarhivarea a esuat se sterge arhiva de pe server
                unlink(MYBB_ROOT."inc/languages/".$filename); 
                exit();
            }
        }
    }
	exit(); 
}

if(!$mybb->input['action'])
{
	$page->add_breadcrumb_item($lang->mod_name);
	$page->add_breadcrumb_item($lang->updates_title);
	$page->output_header($lang->updates_title);

	// se afiseaza taburile paginii
	$page->output_nav_tabs($sub_tabs, 'updates');

    // cod CSS necesar rularii tooltip-ului
    echo '<style type="text/css">
		.tooltip {
			border-bottom: 1px dotted #000000; outline: none;
			cursor: hand; text-decoration: none;
			position: relative;
		}
		.tooltip span {
			margin-left: -999em;
			position: absolute;
		}
		.tooltip:hover span {
			border-radius: 5px 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px; 
			box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.1); -webkit-box-shadow: 5px 5px rgba(0, 0, 0, 0.1); -moz-box-shadow: 5px 5px rgba(0, 0, 0, 0.1);
            font-size: 12px;
			position: absolute; right: 1em; top: 2em; z-index: 99;
			margin-left: 0; width: 250px;
		}
		.tooltip:hover img {
			border: 0; margin: -10px 0 0 -55px;
			float: right;
		}
		.tooltip:hover em {
			font-family: Candara, Tahoma, Geneva, sans-serif; font-size: 1px;
			display: block; padding: 0.2em 0 0.6em 0;
		}
		.classic { 
            padding: 0.8em 1em; 
        }
		.custom { 
            padding: 0.5em 0.8em 0.8em 2em; 
        }
		* html a:hover { 
            background: transparent; 
        }
		.classic {
            background: #99CCFF; 
            border: 1px solid #6699FF; 
        }
    </style>';
      
	echo '<div>
	<div class="float_right" style="width:50%;" id="table_right">';
    
    // in cazul in care se poate citi date din cache se trece la afisarea lor
    $updates = $mybb->cache->read("rolang_updates");
   	if($updates) {
		rolang_displayUpdates($updates, false);
	}

    // se creaza selectorul de teme
    $query = $db->simple_select("themes", "tid,name", "", array("order_by" => 'name', "order_dir" => 'ASC'));
	$options = "";
	while ($row = $db->fetch_array($query)) {
        $options .= "<option value=\"".intval($row['tid'])."\">".$db->escape_string($row['name'])."</option>";
    }
    
	echo '<div class="form_button_wrapper" style="padding: 10px;">
		<input type="button" name="check" id="check_button" value="'.$lang->updates_button_check.'" class="submit_button" onclick="checkUpdates();" />
	</div>
    <div class="form_button_wrapper" style="padding: 10px;">'.$lang->sprintf($lang->updates_button_mybbversion, $mybb->version).'</div>
	<br />';
       
    // tabel cu erorile posibile la instalarea unei actualizari    
   	$table = new Table;
	$table->construct_header("<small>".$lang->updates_table_errors_error."</small>", array('width' => '30%', 'class' => 'align_center'));
	$table->construct_header("<small>".$lang->updates_table_errors_explication."</small>", array('width' => '70%'));

    $table->construct_cell("<font color='red'>".strtolower($lang->updates_table_errors_error).":ver</font>", array('class' => 'align_center'));
    $table->construct_cell($lang->updates_table_errors_ver);
    $table->construct_row();
    
    $table->construct_cell("<font color='red'>".strtolower($lang->updates_table_errors_error).":wri</font>", array('class' => 'align_center'));
    $table->construct_cell($lang->updates_table_errors_wri);
    $table->construct_row();
    
    $table->construct_cell("<font color='red'>".strtolower($lang->updates_table_errors_error).":url</font>", array('class' => 'align_center'));
    $table->construct_cell($lang->updates_table_errors_url);
    $table->construct_row();
    
    $table->construct_cell("<font color='red'>".strtolower($lang->updates_table_errors_error).":ext</font>", array('class' => 'align_center'));
    $table->construct_cell($lang->updates_table_errors_ext);
    $table->construct_row();
    
    $table->construct_cell("<font color='red'>".strtolower($lang->updates_table_errors_error).":pcl</font>", array('class' => 'align_center'));
    $table->construct_cell($lang->updates_table_errors_pcl);
    $table->construct_row();
    
    $table->construct_cell("<font color='red'>".strtolower($lang->updates_table_errors_error).":zip</font>", array('class' => 'align_center'));
    $table->construct_cell($lang->updates_table_errors_zip);
    $table->construct_row();
    
    $table->construct_cell("<font color='red'>".strtolower($lang->updates_table_errors_error).":del</font>", array('class' => 'align_center'));
    $table->construct_cell($lang->updates_table_errors_del);
    $table->construct_row();


	$table->output($lang->updates_table_errors_name."<div style='float:right;'><a id='error_table_el' border='0'></a></div>", 1, "general\" id=\"error_table");

    // tabel pentru setul de imagini
   	$tabel = new Table;
	$tabel->construct_header("<small>".$lang->updates_table_template_describe."</small>", array('width' => '65%'));
	$tabel->construct_header("<small>".$lang->updates_table_template_select."</small>", array('width' => '35%', 'class' => 'align_center'));
    
    $tabel->construct_cell($lang->updates_table_template_description);
    $tabel->construct_cell("<select name=\"selector\" id=\"selector\" size=\"1\" onchange=\"add_template_images();\">".$lang->sprintf($lang->updates_table_template_list, -1, 0, $options)."</select>");
    $tabel->construct_row();
        
    $tabel->construct_cell("<font id=\"add_temp_images\">".$lang->updates_table_template_answer."</font>", array('class' => 'align_center', 'colspan' => 2));
    $tabel->construct_row();
    
    $tabel->output($lang->updates_table_template_name);
    
	echo '</div>
	<div class="float_left" style="width:48%;">';
	echo $lang->updates_page_description;
	echo '</div></div>';

	echo '
<script type="text/javascript">
<!--
	var check_for_lang = "'.$lang->updates_button_check.'";
	var checking_lang = "'.$lang->updates_button_check_process.'";
    var link = $(\'install_link\');
   
    Event.observe(window, \'load\', function() {
        $(\'error_table\').hide();
        $(\'error_table_el\').update(\'['.$lang->updates_table_errors_show.']\');
        Event.observe(\'error_table_el\', \'click\', function(){
            $(\'error_table\').toggle();
            if($(\'error_table\').visible()){
                $(\'error_table_el\').update(\'['.$lang->updates_table_errors_hide.']\');
            } else {
                $(\'error_table_el\').update(\'['.$lang->updates_table_errors_show.']\');
            }
        });
    });
	function checkUpdates()
	{
		$("check_button").value = checking_lang;

		new Ajax.Request("index.php?module=rolang-updates",
		{
			parameters: { ajax: 1, action: "get_updates" },
			onComplete: function(data)
			{ 
				if(data.responseText)
				{
					if($("version_table") == null)
					{
						$("table_right").insert({"top" : data.responseText});
					}
					else
					{
						$("version_table").up("div").replace(data.responseText);
					}
				}
			}
		});

		$("check_button").value = check_for_lang;
	}
    
   	function add_template_images()
	{
		var value = document.getElementById(\'selector\').value;
		
        new Ajax.Request("index.php?module=rolang-updates",
		{
			parameters: { ajax: 1, action: "add_template_images", tid: value },
			onComplete: function(data)
			{ 
				if(data.responseText) {
                    $("add_temp_images").innerHTML = data.responseText;
				}
			}
		});
	}
    
    link.observe(\'click\', function(event) {
        if (this.disabled) {
            event.stop();
        }
    });
	function installUpdate(hash,url,images,vers)
	{
		$("install_progress_"+hash).innerHTML = "'.$lang->updates_table_info_process.'";
        
		new Ajax.Request("index.php?module=rolang-updates",
		{
			parameters: { ajax: 1, action: "install_updates", link : url, image : images, version : vers },
			onComplete: function(data)
			{ 
		        if(data.responseText)
				{
                    $("install_progress_"+hash).innerHTML = data.responseText;
                    if (data.responseText == "<font color=\'green\'>'.$lang->updates_table_info_installed.'</font>")
                    {
                        link.disabled = true;
                    }
                }
			}
		});
	}
// -->
</script>';

	$page->output_footer();
}

// Functie care afiseaza eventualele update-uri de pe server
function rolang_displayUpdates($data, $echo)
{
	global $lang, $mybb, $plugins, $db;
    // Se include fisierul de limba
    rolang_include_lang("global");
    // tabel nou
	$table = new Table;
	$table->construct_header("<small>".$lang->updates_table_list_translation."</small>", array('width' => '50%'));
	$table->construct_header("<small>".$lang->updates_table_list_version."</small>", array('width' => '30%', 'class' => 'align_center'));
	$table->construct_header("<small>".$lang->updates_table_list_install."</small>", array('width' => '20%', 'class' => 'align_center'));
    // s-a putut conecta la server si intoarce lista?
	if($data['succes'] == 1)
	{
        // se prelucreaza versiunea de mybb
        $versiune_mybb = str_replace('.', '', $mybb->version);
        if (strlen($versiune_mybb) == 3)
            $versiune_mybb = $versiune_mybb[0].$versiune_mybb[1]."0".$versiune_mybb[2];
        // verifica pentru o versiune anterioara de limba
        if (file_exists(MYBB_ROOT."inc/languages/romanian.php")) {
            require_once MYBB_ROOT."inc/languages/romanian.php";
        }
        else {
            $langinfo['version'] = 0;
        }
        $gasit = false;
        $message = "<font color='red'>".$lang->updates_table_list_nocompatible."</font>"; 
        // se intoarce lista de actualizari posibile de pe server
        foreach($data[0]['versiuni'][0] as $versiune)
        {
            foreach($versiune as $realizare)
            {
                $vector_compatibil = explode(",", $realizare['compatibil']);
                if (intval($langinfo['version']) < intval($realizare['destinat']) && in_array($versiune_mybb, $vector_compatibil))
                {
                    $table->construct_cell($db->escape_string($realizare['pentru']), array('class' => 'align_center'));
                    $table->construct_cell("<div class=\"tooltip\">".intval($realizare['destinat'])." ( ".my_date($mybb->settings['dateformat'], intval($realizare['data']))." )<span class=\"classic\" style=\"color: #000000;\"><div class=\"float_left\">".$lang->sprintf($lang->updates_table_list_hover, $realizare['compatibil'])."</div></span></div>");
                    $table->construct_cell("<a id=\"install_link\" href=\"javascript: installUpdate('".$realizare['hash']."','".$realizare['legatura']."','".$realizare['imagini']."','".intval($realizare['destinat'])."');\"><b id=\"install_progress_".$realizare['hash']."\">".$lang->updates_table_list_install_start."</b></a>", array('class' => 'align_center'));
                    $table->construct_row();
                    $gasit = true;
                }
                if (intval($realizare['destinat']) == intval($langinfo['version']) && in_array($langinfo['version'], $vector_compatibil))
                {
                    $message = "<font color='green'>".$lang->updates_table_list_compatible."</font>";  
                }
            }
		}
        if (!$gasit)
        {
            // daca nu s-a gasit niciun update posibil pe server se intoarce mesajul urmator
            $table->construct_cell($lang->updates_table_list_nofound, array('class' => 'align_center', 'colspan' => 3));
            $table->construct_row();      
        }
        $table->construct_cell("<b>".$message."</b>", array('class' => 'align_center', 'colspan' => 3, 'style' => 'background: #ADCBE6 url(../images/rolang/info.png) no-repeat 15px center;text-align:right;'));
        $table->construct_row(); 
	}
    else {
        // in cazul in care se intoarce "false" de la functia "rolang_checkForUpdates" atunci se va afisa acest mesaj
        $table->construct_cell($lang->updates_table_list_error, array('class' => 'align_center', 'colspan' => 3, 'style' => 'color:red;'));
        $table->construct_row();
    }
    // informatii aditionale
	$extra = '';
	if($data['dateline'])
	{
		if((TIME_NOW  - $data['dateline']) > 86400) {
			// se afiseaza data ultimei verificari
			$extra = "<div class=\"float_right\"><span class=\"smalltext\">".$lang->sprintf($lang->updates_table_list_lastcheck_1, my_date($mybb->settings['dateformat'], $data['dateline']))."</span></div>";
		}
		else {
			// se afiseaza timpul ultimei verificari
			$extra = "<div class=\"float_right\"><span class=\"smalltext\">".$lang->sprintf($lang->updates_table_list_lastcheck_2, my_date($mybb->settings['dateformat'], $data['dateline']), my_date($mybb->settings['timeformat'], $data['dateline']))."</span></div>";
		}
	}
    // se afiseaza tabelul pe ecran
	$table->output($extra.$lang->updates_table_list_name, 1, "general\" id=\"version_table", $echo);
}

// Verifica pentru actualizari posibile
function rolang_checkForUpdates()
{
    global $mybb;
    // verificari legate de server
    if (empty($mybb->settings['rolang_server_url']) || $mybb->settings['rolang_server_enable'] == 0) {
        return false;
    }
    // exista clasa de prelucrari de date?
    if (!file_exists(MYBB_ROOT."inc/class_xmltoarray.php"))
        return false;
    // daca exista se va folosi
	require_once MYBB_ROOT."inc/class_xmltoarray.php";
    // se intoarce continutul de pe sait
	$contents = fetch_remote_file($mybb->settings['rolang_server_url']."/get_updates.php?bburl=".$mybb->settings['bburl']);
    if (empty($contents))
        return false;
    // se creaza obiect
    $xmlObj = new XmlToArray($contents);
    // din fisierul XML => un vector de date
    $arrayData = $xmlObj->createArray(); 
    // se intoarce din vectorul respectiv doar traducerile
    return $arrayData['mybbromania']['traducere'];
}

// Functie care realizeaza o insertie a setului de imagini al unui template
function rolang_add_templateset($path, $imgdir, $options = array('folderPermission' => 0755, 'filePermission' => 0644))
{
    if (!is_dir(MYBB_ROOT."images/".$path) || !is_dir(MYBB_ROOT.$imgdir))
        return false;
    return rolang_copyDirectory(MYBB_ROOT."images/".$path, MYBB_ROOT.$imgdir."/".$path, $options);
}

// Functie care creaza in mod recursiv un director pe server cu anumite permisiuni
function rolang_makeDirectory($dir, $mode = 0777, $recursive = true) 
{
    if(is_null($dir) || $dir === "") {
        return false;
    }
    if(is_dir($dir) || $dir === "/") {
        return true;
    }
    if(rolang_makeDirectory(dirname($dir), $mode, $recursive)) {
        return mkdir($dir, $mode);
    }
    return false;
}

// Functie care copie continutul unui fisier sau director intr-altul
function rolang_copyDirectory($source, $dest, $options = array('folderPermission' => 0755, 'filePermission' => 0644))
{
	$result = false;
	if (!isset($options['noTheFirstRun'])) {
		$source = str_replace('\\','/', $source);
		$dest = str_replace('\\','/', $dest);
		$options['noTheFirstRun'] = true;
	}
    // este fisier sau director sursa ?
	if (is_file($source)) 
    {
        if ($dest[strlen($dest)-1] == '/') {
            if (!file_exists($dest)) {
                rolang_makeDirectory($dest, $options['folderPermission'], true);
			}
			$__dest = $dest."/".basename($source);
        } 
        else {
			$__dest = $dest;
		}
		if (!file_exists($__dest)) {
			$result = copy($source, $__dest);
			chmod($__dest, $options['filePermission']);
        }
    } 
    elseif(is_dir($source))
    {
        // daca este director ?
        if ($dest[strlen($dest)-1] == '/') {
            if ($source[strlen($source)-1]=='/') {
				// copie doar continutul
			} 
            else {
				// copie atat continutul cat si parintele
				$dest = $dest.basename($source);
				@mkdir($dest);
				chmod($dest, $options['filePermission']);
			}
		} 
        else {
            if ($source[strlen($source)-1] == '/') {
				// copie parintele si continutul cu un nou nume
				@mkdir($dest, $options['folderPermission']);
				chmod($dest, $options['folderPermission']);
			} 
            else {
				// copie continutul
				@mkdir($dest, $options['folderPermission']);
				chmod($dest, $options['folderPermission']);
			}
		}
		$dirHandle = opendir($source);
		while($file = readdir($dirHandle))
		{
			if($file!="." && $file!="..") {
				$__dest = $dest."/".$file;
				$__source = $source."/".$file;
				if ($__source != $dest) {
					$result = rolang_copyDirectory($__source, $__dest, $options);
				}
			}
		}
		closedir($dirHandle);
		
	} 
    else {
		$result = false;
	}
	return $result;
}

// Functie care verifica daca un sait este activ
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
        return true;
    else 
        return false;
}
?>