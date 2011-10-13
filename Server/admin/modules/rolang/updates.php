<?php
/*
    @author     : Surdeanu Mihai ;
    @date       : 12 octombrie 2011 ;
    @version    : 2.2 ;
    @mybb       : compatibilitate cu MyBB 1.6 (orice versiuni) ;
    @description: Modificare pentru Sistemul de Limba Romana ;
    @homepage   : http://mybb.ro ! Te rugam sa ne vizitezi pentru ca nu ai ce pierde!
    @copyright  : Licenta speciala. Pentru mai multe detalii te rugam sa citesti sectiunea Licenta din cadrul fisierului 
                ReadME.pdf care poate fi gasit in acest pachet. Iti multumim pentru intelegere!
    ====================================
    Ultima modificare a codului : 12.10.2011 20:58
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
rolang_include_lang("admin");

// Afisarea meniului paginii
$sub_tabs = array(
	"updates" => array(
		"title" => $lang->updates_title,
		"link" => "index.php?module=rolang-updates",
		"description" => $lang->updates_description
	),
	"upgrades" => array(
		"title" => $lang->updates_upgrade_title,
		"link" => "index.php?module=rolang-updates&amp;action=upgrades",
		"description" => $lang->updates_upgrade_description
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
            echo "<textarea style='width: 100%; margin: 0; padding: 0; border:1px solid #999999;' readonly='true'>".$result."</textarea>";
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
            echo "<textarea style='width: 100%; margin: 0; padding: 0; border:1px solid #999999;' readonly='true'>".$result."</textarea>";
    }
	// se iese fortat
    exit(); 
}

// Ruleaza in momentul in care se cere de catre user instalarea unui update
// Codul a fost rescris fata de versiunea 2.1, ultima modificare realizandu-se la data de 10.09.2011
if($mybb->input['ajax'] && $mybb->input['action'] == "install_updates")
{
    try {
        // vector cu informatii legate despre datele descarcate de pe serverul nostru
        $infos = array();
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
        if (intval($mybb->input['version']) < intval($langinfo['version'])) {
            throw new Exception("ver");       
        }
        // se poate scrie pe server?
        if (!is_writable(MYBB_ROOT."inc/languages/") && (!$images || !is_writable(MYBB_ROOT."images/"))) {
            throw new Exception("wri");        
        }
        // se decodifica adresa pachetului
        $url = base64_decode($mybb->input['archive_lang']);
        // pachetul lingvistic va fi descarcat de aici ...
        $url1 = $mybb->settings['rolang_server_url']."/server.php?bburl=".urlencode($mybb->settings['bburl'])."&action=get_archive_lang&hash=".urlencode($mybb->input['hash']);
        $image = base64_decode($mybb->input['archive_img']);
        // pachetul setului de imagini va fi descarcat de aici ...
        $image1 = $mybb->settings['rolang_server_url']."/server.php?bburl=".urlencode($mybb->settings['bburl'])."&action=get_archive_img&hash=".urlencode($mybb->input['hash']);
        // verifica adresa URL a pachetului si daca este serverul activ
        if (!rolang_websiteUp($url) || ($images && !rolang_websiteUp($image))) {
            throw new Exception("url");       
        }
        else
        {
            // se intoarce numele arhivei din URL
            $filename = basename($url);
            $filename1 = basename($image);
            // mai exista un fisier cu acest nume?
            if (file_exists(MYBB_ROOT."inc/languages/".$filename) || ($images && file_exists(MYBB_ROOT."images/".$filename1))) {
                throw new Exception("ext");       
            }
            // se descarca pachetul lingvistic de pe server
            $file = fopen(MYBB_ROOT."inc/languages/".$filename, 'w');
            $init = curl_init($url1);
            curl_setopt($init, CURLOPT_FILE, $file);
            $data = curl_exec($init);
            // daca nu apar erori se intorc cateva informatii legate de transferul datelor
            if(!curl_errno($init)) {
                $infos['speed_lang'] = curl_getinfo($init, CURLINFO_SPEED_DOWNLOAD);
                $infos['time_lang'] = curl_getinfo($init, CURLINFO_TOTAL_TIME);
            }
            curl_close($init);
            fclose($file);
            // se descarcat si arhiva setului de imagini?
            if ($images)
            {
                $file = fopen(MYBB_ROOT."images/".$filename1, 'w');
                $init = curl_init($image1);
                curl_setopt($init, CURLOPT_FILE, $file);
                $data = curl_exec($init);
                // daca nu apar erori se intorc cateva informatii legate de transferul datelor
                if(!curl_errno($init)) {
                    $infos['speed_img'] = curl_getinfo($init, CURLINFO_SPEED_DOWNLOAD);
                    $infos['time_img'] = curl_getinfo($init, CURLINFO_TOTAL_TIME);
                }
                curl_close($init);
                fclose($file);
            }
            // inainte de dezarhivare se verifica daca clasa ZipArchive exista pe server
            if (class_exists('ZipArchive')) 
            {
                // daca exista se va folosi ca si metoda de dezarhivare clasa ZipArchive
                $zip = new ZipArchive;
                if ($zip->open(MYBB_ROOT."inc/languages/".$filename) === true) 
                {
                    // se extrage arhiva lingvistica
                    $zip->extractTo(MYBB_ROOT."inc/languages/");
                    $zip->close();
                    // se extrage si arhiva setului de imagini
                    if ($images)
                    {
                        $zip1 = new ZipArchive;  
                        if ($zip1->open(MYBB_ROOT."images/".$filename1) === true) {
                            // se extrage
                            $zip1->extractTo(MYBB_ROOT."images/");
                            $zip1->close();
                        }
                        else {       
                            // daca apare o eroare se sterge arhiva descarcata de pe server si se afiseaza o eroare pe ecran
                            unlink(MYBB_ROOT."images/".$filename1); 
                            throw new Exception("zip");                  
                        }
                    }
                    // se sterge arhiva lingvistica descarcata de pe server
                    if (unlink(MYBB_ROOT."inc/languages/".$filename) && (!$images || unlink(MYBB_ROOT."images/".$filename1))) {
                        if (($mybb->settings['rolang_server_setlanguage'] == 1) && ($mybb->settings['bblanguage'] != "romanian") && file_exists(MYBB_ROOT."inc/languages/romanian.php")) {
                            $db->update_query("settings", array("value" => "romanian"), "name = 'bblanguage'");
                            // se reconstruiesc setarile
                            rebuild_settings();
                        }
                        echo "<font color='green'>".$lang->updates_table_info_installed."</font>"; 
                        // se introduce log-ul in baza de date
                        if(isset($infos['speed_img']) && isset($infos['time_img'])) {
                            $speed_total = number_format((floatval($infos['speed_lang']) + floatval($infos['speed_img']))/2, 2, ".", "");
                            $time_total = number_format(floatval($infos['time_lang']) + floatval($infos['time_img']), 2);
                        }
                        else {
                            $speed_total = number_format(floatval($infos['speed_lang']), 2, ".", "");
                            $time_total = number_format(floatval($infos['time_lang']), 2);                       
                        }
                        // se adauga log-ul in sistem
                        if (function_exists("rolang_add_log")) {
                            rolang_add_log("<font color=green>".$lang->updates_table_info_log."</font>", $lang->sprintf($lang->updates_table_info_log_desc, intval($mybb->input['version']), rolang_get_size($speed_total), $time_total), $mybb->user['uid']);
                        }
                        // de asemenea se incarca si trimiterea unor date catre server
                        if (function_exists("rolang_send_server_info") && $mybb->settings['rolang_server_send_stats'] == 1) {
                            rolang_send_server_info();
                        }
                    }
                    else {
                        // a aparut o eroare la stergerea arhivelor de pe server
                        throw new Exception("del");
                    }
                }
                else {
                    // in cazul in care dezarhivarea a esuat se sterge arhiva lingvistica de pe server
                    unlink(MYBB_ROOT."inc/languages/".$filename);   
                    throw new Exception("zip");
                }
            }
            else 
            {
                // daca nu exista clasa ZipArchive atunci se va folosi clasa definita de noi
                if (!file_exists(MYBB_ROOT."inc/class_pclzip.php")) {
                    // se sterg arhivele de pe server
                    if (file_exists(MYBB_ROOT."inc/languages/".$filename))
                        unlink(MYBB_ROOT."inc/languages/".$filename); 
                    if (file_exists(MYBB_ROOT."images/".$filename1))
                        unlink(MYBB_ROOT."images/".$filename1); 
                    // se defineste o exceptie
                    throw new Exception("pcl");
                }
                else {
           	        require_once MYBB_ROOT."inc/class_pclzip.php";
                }
                // daca clasa cu care lucram nu exista atunci se intoarce iar o eroare
                if (!class_exists('PclZip')) {
                    // se sterg arhivele de pe server
                    if (file_exists(MYBB_ROOT."inc/languages/".$filename))
                        unlink(MYBB_ROOT."inc/languages/".$filename); 
                    if (file_exists(MYBB_ROOT."images/".$filename1))
                        unlink(MYBB_ROOT."images/".$filename1);              
                    throw new Exception("pcl");                
                }
                // se incearca extragerea primei arhive
                $zip = new PclZip(MYBB_ROOT."inc/languages/".$filename);
                if ($zip->extract(PCLZIP_OPT_PATH, MYBB_ROOT."inc/languages/") != 0) 
                {
                    // daca reuseste...
                    // se dezarhiveaza si pachetul de imagini ?
                    if ($images) {
                        $zip1 = new PclZip(MYBB_ROOT."images/".$filename1); 
                        if ($zip->extract(PCLZIP_OPT_PATH, MYBB_ROOT."images/") == 0) {
                            // se sterge arhiva descarcata de pe server
                            unlink(MYBB_ROOT."images/".$filename1); 
                            // se creaza exceptia
                            throw new Exception("zip");                   
                        }
                    }
                    // se sterge arhiva descarcata de pe server
                    if (unlink(MYBB_ROOT."inc/languages/".$filename) && (!$images || unlink(MYBB_ROOT."images/".$filename1))) 
                    {
                        if (($mybb->settings['rolang_server_setlanguage'] == 1) && ($mybb->settings['bblanguage'] != "romanian") && file_exists(MYBB_ROOT."inc/languages/romanian.php")) {
                            $db->update_query("settings", array("value" => "romanian"), "name = 'bblanguage'");
                            // se reconstruiesc setarile
                            rebuild_settings();
                        }
                        echo "<font color='green'>".$lang->updates_table_info_installed."</font>"; 
                        // se introduce log-ul in baza de date
                        if(isset($infos['speed_img']) && isset($infos['time_img'])) {
                            $speed_total =  number_format((floatval($infos['speed_lang']) + floatval($infos['speed_img']))/2, 2, ".", "");
                            $time_total = number_format(floatval($infos['time_lang']) + floatval($infos['time_img']), 2);
                        }
                        else {
                            $speed_total = number_format(floatval($infos['speed_lang']), 2, ".", "");
                            $time_total = number_format(floatval($infos['time_lang']), 2);                       
                        }
                        // se adauga log-ul in sistem
                        if (function_exists("rolang_add_log")) {
                            rolang_add_log("<font color=green>".$lang->updates_table_info_log."</font>", $lang->sprintf($lang->updates_table_info_log_desc, intval($mybb->input['version']), rolang_get_size($speed_total), $time_total), $mybb->user['uid']);
                        }
                        // de asemenea se incarca si trimiterea unor date catre server
                        if (function_exists("rolang_send_server_info") && $mybb->settings['rolang_server_send_stats'] == 1) {
                            rolang_send_server_info();
                        }                    }
                    else {
                        // nu s-au putut sterge fisierele
                        throw new Exception("del");
                    }
                } 
                else {
                    // in cazul in care dezarhivarea a esuat se sterge arhiva de pe server
                    unlink(MYBB_ROOT."inc/languages/".$filename); 
                    // eroare la dezarhivare
                    throw new Exception("zip");
                }
            }
        }
    }
    catch (Exception $e) 
    {
        // aceasta sectiune de cod ruleaza in momentul in care apare o eroare, exceptie
        echo "<font color=\"red\">".strtolower($lang->updates_table_errors_error).":".$e->getMessage()."</font>";
        // se introduce log-ul in baza de date
        if (function_exists("rolang_add_log")) {
            rolang_add_log("<font color=red>".$lang->updates_table_errors_error."</font>", $lang->sprintf($lang->mod_exception_update, $e->getMessage()), $mybb->user['uid']);
        }
        // se iese fortat
        exit();
    }
    // se asigura iesirea fortata
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
        a.rolang_get_help {
            display: block; height: 16px; width: 16px; text-indent: -999px; text-decoration: none; overflow: hidden; padding: 0px; margin: 0px;
        }
        a.rolang_get_help {
	       background: transparent url(../images/rolang/help.png);
            cursor: help;
            float: right;
        }
    </style>
   	<script type="text/javascript" src="../jscripts/scriptaculous.js?load=effects"></script>
	<script type="text/javascript">
        Event.observe(document, "dom:loaded", function() {
            // evenimentul pentru a apare casuta de ajutor
            $$(\'.rolang_get_help\').invoke(\'observe\', \'click\', function(e) {
                if($(this.rel)) {
                    Effect.toggle(this.rel, \'Blind\');
                }
                Event.stop(e);
            });
            $$(\'.rolang_close_help\').invoke(\'observe\', \'click\', function(e) {
                Effect.toggle(this.up(3), \'Blind\');
                Event.stop(e);
            });
            // in mod implicit casuta de ajutor nu este afisata pe ecran
            $$(\'.rolang_images_help\').invoke(\'hide\');
        });	   
	</script>';
	echo '<div>
	<div class="float_right" style="width:50%;" id="table_right">';
    // in cazul in care se poate citi date din cache se trece la afisarea lor
    $updates = $mybb->cache->read("rolang_updates");
   	if($updates) {
		rolang_displayUpdates($updates, false);
	}
    // tabel cu erorile posibile la instalarea unei actualizari    
   	$table = new Table;
	$table->construct_header($lang->updates_table_errors_error, array('width' => '30%', 'class' => 'align_center'));
	$table->construct_header($lang->updates_table_errors_explication, array('width' => '70%'));
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
    // se creaza selectorul de teme
    $query = $db->simple_select("themes", "tid,name", "", array("order_by" => 'name', "order_dir" => 'ASC'));
	$options = "";
	while ($row = $db->fetch_array($query)) {
        $options .= "<option value=\"".intval($row['tid'])."\">".$db->escape_string($row['name'])."</option>";
    }
	echo '<div class="form_button_wrapper" style="padding: 10px;">
		<input type="button" name="check" id="check_button" value="'.$lang->updates_button_check.'" class="submit_button" onclick="checkUpdates();" />
        <span id="timer"></span>
	</div>
    <div class="form_button_wrapper" style="padding: 10px;">'.$lang->sprintf($lang->updates_button_mybbversion, $mybb->version).'</div>
	<br />';
	echo '<div id="rolang_images_help" class="rolang_images_help">';
	$form_container = new FormContainer('<a name="help">'.$lang->logs_prune_help.'</a><span style="float:right">[<a href="#" class="rolang_close_help" >'.$lang->logs_prune_help_close.'</a>]</span>');
    $form_container->output_cell($lang->updates_table_template_help);
	$form_container->construct_row();
	$form_container->end();
	echo '</div>';
    // tabel pentru setul de imagini
   	$tabel = new Table;
	$tabel->construct_header($lang->updates_table_template_describe, array('width' => '65%'));
	$tabel->construct_header($lang->updates_table_template_select, array('width' => '35%', 'class' => 'align_center'));
    $tabel->construct_cell($lang->updates_table_template_description);
    $tabel->construct_cell("<select name=\"selector\" id=\"selector\" size=\"1\" onchange=\"add_template_images();\">".$lang->sprintf($lang->updates_table_template_list, -1, 0, $options)."</select>");
    $tabel->construct_row();  
    $tabel->construct_cell("<font id=\"add_temp_images\">".$lang->updates_table_template_answer."</font>", array('colspan' => 2));
    $tabel->construct_row();
    $tabel->output($lang->updates_table_template_name."<a href=\"#help\" class=\"rolang_get_help\" rel=\"rolang_images_help\">&nbsp;</a>");  
	echo '</div>
	<div class="float_left" style="width:48%;">';
	echo $lang->updates_page_description;
	echo '</div></div>';
	echo '
<script type="text/javascript">
<!--
	var check_for_lang = "'.$lang->updates_button_check.'";
	var checking_lang = "'.$lang->updates_button_check_process.'";
    // evenimentul care ruleaza dupa incarcarea datelor in pagina
    Event.observe(window, \'load\', function() {
        // functia pentru verificarea timpului
        checkTime();
        // tabelul cu erori
        $(\'error_table\').hide();
        $(\'error_table_el\').update(\'['.$lang->updates_table_errors_show.']\');
        // evenimentul care in momentul in care se a click pe tabelul de erori
        Event.observe(\'error_table_el\', \'click\', function(){
            $(\'error_table\').toggle();
            if($(\'error_table\').visible()){
                $(\'error_table_el\').update(\'['.$lang->updates_table_errors_hide.']\');
            } else {
                $(\'error_table_el\').update(\'['.$lang->updates_table_errors_show.']\');
            }
        });
        var link = $(\'install_link\');
        // eveniment pentru variabila "link"
        link.observe(\'click\', function(event) {
            if (this.disabled) {
                event.stop();
            }
        });
        // se intoarce timestamp-ul curent
        var now = Math.round(+new Date() / 1000);
        // se calculeaza diferenta de timp ramasa
        var last_check = 0;
        if ($(\'last_check\') != undefined) {
            last_check = Number($(\'last_check\').innerHTML);
        }
        var diff = now - last_check;
        // diferenta de timp este mai mare de o ora
        if (diff > 3600) {
            // pentru fiecare tag "a" ce are id-ul "install_link"
            $$("a:#install_link").each(function(obj) {
                obj.href = "javascript: dialog_confirm(\'Pentru a instala o versiune ce a fost verificata acum mai bine de o ora va trebui sa faci o reverificare. Doresti sa o faci acum?\');";
            });
        }
    });
    // functia care afiseaza o eroare pe ecran
    function dialog_confirm(message)
    {
        if (confirm(message)) {
            // se apeleaza functia de verificare a unor actualizari
            checkUpdates();
        }
        // altfel nu se face nimic
    }
    // functia care verifica actualizari
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
        // dupa ce este apelata aceasta functie se apeleaza automat si functia pentru timp
        $(\'check_button\').hide();
        countDown(1800);
	}
    // functia care adauga sabloane in sistem
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
    // functia care instaleaza o actualizare
	function installUpdate(hash,url,images,vers)
	{
		$("install_progress_"+hash).innerHTML = "'.$lang->updates_table_info_process.'";
		new Ajax.Request("index.php?module=rolang-updates",
		{
			parameters: { ajax: 1, action: "install_updates", archive_lang : url, archive_img : images, version : vers, hash : hash },
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
    // functia care calculeaza timpul ramas pana la o noua posibila actualizare
    function countDown(remain) {
        var
            countdown = document.getElementById("timer"),
            timer = setInterval( function () {
                countdown.innerHTML = "<p align=\'justify\' style=\'margin: 0px;\'>'.$lang->updates_table_info_message_1.'<b>" + Math.floor(remain/60) + "</b>'.$lang->updates_table_info_message_2.'<b>" + (remain%60 < 10 ? "0": "") + remain %60 + "</b>'.$lang->updates_table_info_message_3.'</p>";
                if (--remain < 0 ) { 
                    $(\'check_button\').show();
                    countdown.innerHTML = "";
                    clearInterval(timer); 
                }
            }, 1000);
    }
    // functia care verifica timpul ramas
    function checkTime() {    
        // se intoarce timestamp-ul curent
        var now = Math.round(+new Date() / 1000);
        // se calculeaza diferenta de timp ramasa
        var last_check = 0;
        if ($(\'last_check\') != undefined) {
            last_check = Number($(\'last_check\').innerHTML);
        }
        var diff = now - last_check;
        // 30 de minute se va astepta
        if (diff <= 1800) {
            $(\'check_button\').hide();
            countDown(1800 - diff);
        }
    }
// -->
</script>';
    // se afiseaza subsolul paginii
	$page->output_footer();
}
elseif ($mybb->input['action'] == "upgrades")
{
    // se importa o actualizare?
    if($mybb->request_method == "post")
    {
        if(!$_FILES['local_file'] && !$mybb->input['url']) {
            $errors[] = $lang->updates_import_missing_url;
        }
        if(!$errors)
        {
            // exista deja un fisier incarcat?
            if($_FILES['local_file']['error'] != 4)
            {
                // probleme la incarcarea fisierului
                if($_FILES['local_file']['error'] != 0)
                {
                    $errors[] = $lang->updates_import_uploadfailed.$lang->updates_import_uploadfailed_detail;
                    switch($_FILES['local_file']['error']) 
                    {
                        case 1: // UPLOAD_ERR_INI_SIZE
                            $errors[] = $lang->updates_import_uploadfailed_php1;
                            break;
                        case 2: // UPLOAD_ERR_FORM_SIZE
                            $errors[] = $lang->updates_import_uploadfailed_php2;
                            break;
                        case 3: // UPLOAD_ERR_PARTIAL
                            $errors[] = $lang->updates_import_uploadfailed_php3;
                            break;
                        case 6: // UPLOAD_ERR_NO_TMP_DIR
                            $errors[] = $lang->updates_import_uploadfailed_php6;
                            break;
                        case 7: // UPLOAD_ERR_CANT_WRITE
                            $errors[] = $lang->updates_import_uploadfailed_php7;
                            break;
                        default:
                            $errors[] = $lang->sprintf($lang->updates_import_uploadfailed_phpx, $_FILES['local_file']['error']);
                            break;
                    }
                }
                if(!$errors)
                {
                    // s-a gasit fisierul temporar
                    if(!is_uploaded_file($_FILES['local_file']['tmp_name'])) {
                        $errors[] = $lang->updates_import_uploadfailed_lost;
                    }
                    // se obtine continutul fisierului
                    $contents = @file_get_contents($_FILES['local_file']['tmp_name']);
                    // se sterge fisierul temporar, daca acest lucru este posibil
                    @unlink($_FILES['local_file']['tmp_name']);
                    // exista continut nevid?
                    if(!trim($contents)) {
                        $errors[] = $lang->updates_import_uploadfailed_nocontents;
                    }
                }
            }
            elseif (!empty($mybb->input['url']))
            {
                // se intoarce continutul fisierului de la adresa web specificata
                $contents = @fetch_remote_file($mybb->input['url']);
                if(!$contents) {
                    $errors[] = $lang->updates_import_local_file;
                }
            }
            else {
                // UPLOAD_ERR_NO_FILE
                $errors[] = $lang->updates_import_uploadfailed_php4;
            }
			// daca in acest moment nu exista erori se trece la crearea fisierului modulului
            if(!$errors)
            {
                $result = rolang_make_module($contents);
                if($result) {
                    flash_message($lang->updates_import_success, 'success');
                    admin_redirect("index.php?module=rolang-updates&amp;action=upgrades");
                }
                else {
                    $errors[] = $lang->updates_import_error_make;
                }
            }
        }
    }
    // se adauga breadcrumb-urile
    $page->add_breadcrumb_item($lang->mod_name);
	$page->add_breadcrumb_item($lang->updates_upgrade_title);
	$page->output_header($lang->updates_upgrade_title);
	// se afiseaza taburile paginii
	$page->output_nav_tabs($sub_tabs, 'upgrades');
	echo "<p class=\"notice\" style=\"background-image: url(../images/rolang/info.png);background-repeat: no-repeat;background-position: 10px center;\">{$lang->updates_upgrade_notice}</p>";
	// se intoarce vectorul cu upgrade-uri posibile
	$upgrades = rolang_get_upgrades();	
	// se creaza tabelul cu upgrade-urile
	$table = new Table;
	$table->construct_header($lang->updates_upgrade_table_name, array('width' => '70%'));
	$table->construct_header($lang->updates_upgrade_table_controls, array('width' => '30%', 'class' => 'align_center'));
    // daca exista cel putin 1 upgrade disponibil
	if (count($upgrades) > 0)
	{
		foreach($upgrades as $upgrade)
		{
            // nume de cod
			$codename = str_replace(".php", "", $upgrade);
            // daca fisierul exista se include, astfel se trece la urmatorul
            if (!file_exists(MYBB_ROOT."admin/modules/rolang/upgrades/".$upgrade)) {
                continue;
            }
            else {
                require_once MYBB_ROOT."admin/modules/rolang/upgrades/".$upgrade;
            }
            // functia cu informatii despre upgrade
			$infofunc = $codename."_info";
            // daca nu exista se trece la urmatorul fisier
			if(!function_exists($infofunc)) {
				continue;
			}
			// se intorc informatiile despre upgrade
			$upgradeinfo = $infofunc();
			// se afiseaza datele in tabel
			$table->construct_cell("<strong>{$upgradeinfo['name']}</strong> ({$upgradeinfo['version']})<br /><small>{$upgradeinfo['description']}</small><br /><i><small>Creat de {$upgradeinfo['author']}</small></i>");
            // se creaza un meniu de optiuni
            $popup = new PopupMenu("upgrade_{$upgradeinfo['version']}", $lang->mod_table_options);
            // se creaza optiunile
            $popup->add_item($lang->updates_upgrade_table_option_run, "index.php?module=rolang-updates&amp;action=run&amp;upgrade_file=".$codename."&amp;my_post_key={$mybb->post_code}");
            $popup->add_item($lang->updates_upgrade_table_option_del, "index.php?module=rolang-updates&amp;action=delete_file&amp;upgrade_file=".$codename."&amp;my_post_key={$mybb->post_code}");
            // lista cu optiuni
			$table->construct_cell($popup->fetch(), array('class' => 'align_center'));
			// se construieste randul
			$table->construct_row();
		}
	}
	else
	{
        // altfel se afiseaza un mesaj prin care suntem anuntati ca nu exista upgrade-uri posibile
		$table->construct_cell($lang->updates_upgrade_table_without, array('class' => 'align_center', 'colspan' => 2));
		$table->construct_row();
	}
	// se afiseaza tabelul pe ecran
	$table->output($lang->updates_upgrade_table_title);   
    // sectiunea de import a unui modul 
    // exista erori?
    if($errors) 
    {
        $page->output_inline_error($errors);
        if($mybb->input['import'] == 1) {
            $import_checked[1] = "";
            $import_checked[2] = "checked=\"checked\"";
        }
        else {
            $import_checked[1] = "checked=\"checked\"";
            $import_checked[2] = "";
        }
    }
    else {
        $import_checked[1] = "checked=\"checked\"";
        $import_checked[2] = "";
    }
    // se genereaza formularul
    $form = new Form("index.php?module=rolang-updates&amp;action=upgrades", "post", "", 1);
    $actions = '<script type="text/javascript">
        function checkAction(id) {
            var checked = \'\';
            $$(\'.\'+id+\'s_check\').each(function(e) {
                if(e.checked == true) {
                    checked = e.value;
                }
            });
            $$(\'.\'+id+\'s\').each(function(e) {
                Element.hide(e);
            });
            if($(id+\'_\'+checked)) {
                Element.show(id+\'_\'+checked);
            }
        }
        </script>
<dl style="margin-top: 0; margin-bottom: 0; width: 35%;">
<dt><label style="display: block;"><input type="radio" name="import" value="0" '.$import_checked[1].' class="imports_check" onclick="checkAction(\'import\');" style="vertical-align: middle;" /> '.$lang->updates_import_localfile.'</label></dt>
<dd style="margin-top: 0; margin-bottom: 0; width: 100%;" id="import_0" class="imports">
<table cellpadding="4">
    <tr>
        <td>'.$form->generate_file_upload_box("local_file", array('style' => 'width: 230px;')).'</td>
    </tr>
</table>
</dd>	
<dt><label style="display: block;"><input type="radio" name="import" value="1" '.$import_checked[2].' class="imports_check" onclick="checkAction(\'import\');" style="vertical-align: middle;" /> '.$lang->updates_import_url.'</label></dt>
<dd style="margin-top: 0; margin-bottom: 0; width: 100%;" id="import_1" class="imports">
<table cellpadding="4">
<tr>
    <td>'.$form->generate_text_box("url", $mybb->input['file']).'</td>
</tr>
</table></dd>
</dl>
<script type="text/javascript">checkAction(\'import\');</script>';
    $form_container = new FormContainer($lang->updates_import_module);
    $form_container->output_row($lang->updates_import_from, $lang->updates_import_from_desc, $actions, 'file');
    $form_container->end();
    // se creaza butoanele
    $buttons[] = $form->generate_submit_button($lang->updates_import_button);
    // se afiseaza butoanele formularului
    $form->output_submit_wrapper($buttons);
    $form->end();
    // se afiseaza subsolul paginii
   	$page->output_footer();
}
elseif ($mybb->input['action'] == 'run')
{
	if($mybb->input['no']) {
        // administratorul nu a confirmat
		admin_redirect("index.php?module=rolang-updates&amp;action=upgrades");
	}
    // se verifica cererea
	if($mybb->request_method == "post")
	{
        // este cererea efectuata autentica?
		if(!isset($mybb->input['my_post_key']) || $mybb->post_code != $mybb->input['my_post_key']) {
			$mybb->request_method = "get";
			flash_message($lang->admin_table_options_error, 'error');
			admin_redirect("index.php?module=rolang-updates&amp;action=upgrades");
		}
		// fisierul care va rula ca si upgrade
		$upgrade = $db->escape_string($mybb->input['upgrade_file']);
        // se verifica daca exista fisierul pe server
        if (!file_exists(MYBB_ROOT."admin/modules/rolang/upgrades/".$upgrade.".php")) {
            // daca nu exista se afiseaza o eroare
			flash_message($lang->updates_upgrade_run_file, 'error');
			admin_redirect("index.php?module=rolang-updates&amp;action=upgrades");
        }			
        // daca exista se include															 
		require_once MYBB_ROOT."admin/modules/rolang/upgrades/".$upgrade.".php";
		$runfunc = $upgrade."_run";
        // exista functia de realizare a upgrade-ului
		if(!function_exists($runfunc)) {
            // daca nu exista se iese
			continue;
		}
		// altfel se apeleaza aceasta functie
		if ($runfunc()) {
            // daca returneaza "true" atunci totul e bine
            $message = $lang->sprintf($lang->updates_upgrade_run_success, "<b>".$upgrade."</b>");
            // exista functia pentru log-uri?
            if (function_exists("rolang_add_log")) {
                // se adauga un log in sistem
                rolang_add_log("<font color=green>".$lang->infos_button_type_log."<font>", $message, $mybb->user['uid']);
            }
            flash_message($message, 'success');
        }
        else {
            // daca returneaza "false" atunci se afiseaza o eroare
            flash_message($lang->updates_upgrade_run_error, 'error');
        }
        admin_redirect("index.php?module=rolang-updates&amp;action=upgrades");
	}
	else {
        // pagina de confirmare
        $page->add_breadcrumb_item($lang->mod_name);
        $page->add_breadcrumb_item($lang->updates_upgrade_title, 'index.php?module=rolang-updates&amp;action=upgrades');
        // se afiseaza antetul paginii	
        $page->output_header($lang->updates_upgrade_title);
		// se prelucreaza input-ul
		$mybb->input['upgrade_file'] = htmlspecialchars($mybb->input['upgrade_file']);
		$form = new Form("index.php?module=rolang-updates&amp;action=run&amp;upgrade_file=".str_replace(".php", "", $mybb->input['upgrade_file'])."&amp;my_post_key={$mybb->post_code}", 'post');
		echo "<div class=\"confirm_action\">\n";
		echo "<p>{$lang->updates_upgrade_run_confirm}</p>\n";
		echo "<br />\n";
		echo "<p class=\"buttons\">\n";
		echo $form->generate_submit_button($lang->yes, array('class' => 'button_yes'));
		echo $form->generate_submit_button($lang->no, array("name" => "no", 'class' => 'button_no'));
		echo "</p>\n";
		echo "</div>\n";
		$form->end();
        // se afiseaza subsolul paginii
        $page->output_footer();
	}
}
elseif ($mybb->input['action'] == 'delete_file')
{
	if($mybb->input['no']) {
        // administratorul nu a confirmat
		admin_redirect("index.php?module=rolang-updates&amp;action=upgrades");
	}
    // se verifica cererea
	if($mybb->request_method == "post")
	{
        // este cererea efectuata autentica?
		if(!isset($mybb->input['my_post_key']) || $mybb->post_code != $mybb->input['my_post_key']) {
			$mybb->request_method = "get";
			flash_message($lang->admin_table_options_error, 'error');
			admin_redirect("index.php?module=rolang-updates&amp;action=upgrades");
		}
		// fisierul care va rula ca si upgrade
		$upgrade = $db->escape_string($mybb->input['upgrade_file']);
        // se verifica daca exista fisierul pe server
        if (!file_exists(MYBB_ROOT."admin/modules/rolang/upgrades/".$upgrade.".php")) {
            // daca nu exista se afiseaza o eroare
			flash_message($lang->updates_import_uploadfailed_lost, 'error');
			admin_redirect("index.php?module=rolang-updates&amp;action=upgrades");
        }			
		// daca exista se incearca stergerea fisierului
		if (unlink(MYBB_ROOT."admin/modules/rolang/upgrades/".$upgrade.".php")) {
            // daca returneaza "true" atunci totul e bine - fisierul s-a sters
            $message = $lang->sprintf($lang->updates_upgrade_delete_success, $upgrade.".php");
            if (function_exists("rolang_add_log")) {
                // se adauga un log in sistem
                rolang_add_log("<font color=green>".$lang->infos_button_type_log."</font>", $message, $mybb->user['uid']);
            }
            flash_message($message, 'success');
        }
        else {
            // daca returneaza "false" atunci se afiseaza o eroare
            flash_message($lang->sprintf($lang->updates_upgrade_delete_error, $upgrade.".php"), 'error');
        }
        admin_redirect("index.php?module=rolang-updates&amp;action=upgrades");
	}
	else {
        // pagina de confirmare
        $page->add_breadcrumb_item($lang->mod_name);
        $page->add_breadcrumb_item($lang->updates_upgrade_title, 'index.php?module=rolang-updates&amp;action=upgrades');
        // se afiseaza antetul paginii	
        $page->output_header($lang->updates_upgrade_title);
		// se prelucreaza input-ul
		$mybb->input['upgrade_file'] = htmlspecialchars($mybb->input['upgrade_file']);
		$form = new Form("index.php?module=rolang-updates&amp;action=delete_file&amp;upgrade_file=".str_replace(".php", "", $mybb->input['upgrade_file'])."&amp;my_post_key={$mybb->post_code}", 'post');
		echo "<div class=\"confirm_action\">\n";
		echo "<p>{$lang->updates_upgrade_delete_confirm}</p>\n";
		echo "<br />\n";
		echo "<p class=\"buttons\">\n";
		echo $form->generate_submit_button($lang->yes, array('class' => 'button_yes'));
		echo $form->generate_submit_button($lang->no, array("name" => "no", 'class' => 'button_no'));
		echo "</p>\n";
		echo "</div>\n";
		$form->end();
        // se afiseaza subsolul paginii
        $page->output_footer();
	}
}

// Functie care afiseaza eventualele update-uri de pe server
function rolang_displayUpdates($data, $echo)
{
	global $lang, $mybb, $plugins, $db;
    // Se include fisierul de limba
    rolang_include_lang("admin");
    // tabel nou
	$table = new Table;
	$table->construct_header($lang->updates_table_list_translation, array('width' => '50%'));
	$table->construct_header($lang->updates_table_list_version, array('width' => '30%', 'class' => 'align_center'));
	$table->construct_header($lang->updates_table_list_install, array('width' => '20%', 'class' => 'align_center'));
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
			$extra = "<div class=\"float_right\"><span class=\"smalltext\">".$lang->sprintf($lang->updates_table_list_lastcheck_1, my_date($mybb->settings['dateformat'], $data['dateline']), $data['dateline'])."</span></div>";
		}
		else {
			// se afiseaza timpul ultimei verificari
			$extra = "<div class=\"float_right\"><span class=\"smalltext\">".$lang->sprintf($lang->updates_table_list_lastcheck_2, my_date($mybb->settings['dateformat'], $data['dateline']), my_date($mybb->settings['timeformat'], $data['dateline']), $data['dateline'])."</span></div>";
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
	$contents = fetch_remote_file($mybb->settings['rolang_server_url']."/server.php?bburl=".urlencode($mybb->settings['bburl']));
    if (empty($contents))
        return false;
    // de asemenea se incarca si trimiterea unor date catre server
    if (function_exists("rolang_send_server_info")) {
        rolang_send_server_info();
    }
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

// Functia care intoarce marimea unui fisier in functie de numarul de biti
function rolang_get_size($size)
{
    $standard = floatval(1024);
    if (floatval($size) < $standard) {
        // atunci sunt biti
        return $size." bytes";
    }
    else {
        // sunt mai mult de 1024 de biti
        if (floatval(floatval($size)/1024) > $standard) {
            // atunci este de ordinul MB-itilor si se iau doar doua zecimale semnificative
            return number_format(floatval($size)/1024/1024, 2). " MB";
        }
        else {
            // este de ordinul KB-itilor si se iau doar doua zecimale semnificative
            return number_format(floatval($size)/1024, 2). " KB";
        }
        // restul nu ne intereseaza pentru ca nu pot fi incarcate fisiere mai mari de 1 GB
    }	
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

// Functia care intoarce upgrade-urile existente in folderul "upgrades"
function rolang_get_upgrades()
{
    // vector cu lista de upgrade-uri
	$upgrades_list = array();
	// directorul cu posibile upgrade-uri
	$dir = @opendir(MYBB_ROOT.'admin/modules/rolang/upgrades/');
	// se cauta in acest director
	if($dir)
	{
        // se citeste fisier cu fisier
		while($file = readdir($dir))
		{
			if($file == '.' || $file == '..')
				continue;
			// daca este fisier...
			if(!is_dir(MYBB_ROOT.'admin/modules/rolang/upgrades/'.$file))
			{
                // se intoarce extensia fisierului
				$ext = get_extension($file);
                // daca e PHP atunci e bine
				if($ext == 'php') {
				    // se adauga in lista
					$upgrades_list[] = $file;
				}
			}
		}
        // se sorteaza alfabetic vectorul
		@sort($upgrades_list);
        // se inchide directorul de lucru
		@closedir($dir);
	}
	// se returneaza vectorul astfel obtinut
	return $upgrades_list;
}

// Functia care realizeaza procesul de import al unui modul dintr-un fisier XML
function rolang_make_module($xml)
{
	global $mybb, $db;
	// se include clasa de prelucrare a unui fisier XML
	require_once MYBB_ROOT."inc/class_xml.php";
    // se parseaza fisierul
	$parser = new XMLParser($xml);
    // se obtine arborele din fisierul XML
	$tree = $parser->get_tree();
    // daca arborele nu este vector atunci se returneaza "fals"
	if(!is_array($tree) || !is_array($tree['module'])) {
		return false;
	}
	// se obtine vectorul cu datele noului modul
	$module = $tree['module'];
    // se obtin atributele modulului
	$filename = $module['attributes']['name'];
    // se deschide fisierul in care se vor scrie informatiile
    if (!($file = fopen(MYBB_ROOT."admin/modules/rolang/upgrades/".basename($filename).".php", 'w'))) {
        return false;
    }
    else {
        $part_1 = "<?php\n/*\nThis file was generated with \"SLR\" modification.\n*/\n//BEGIN - SECURITY\nif(!defined(\"IN_MYBB\")) {\n\tdie(\"Direct initialization of this file is not allowed.\");\n}\n//END - SECURITY\n";
        // se scrie prima portiune de cod
        fwrite($file, $part_1);
        // urmeaza scrierea datelor prinvind carligele modulului
        $part_2 = "//BEGIN - HOOKS\n";
        if(is_array($module['hooks'])) {
            foreach($module['hooks'] as $info => $value) {
                if($info == "tag" || $info == "value") {
				    continue;
                }
                $part_2 .= "\$plugins->add_hook(\"{$info}\", \"{$value['value']}\");\n";
            }
            $part_2 .= "//END - HOOKS\n";
        }
        // se scrie a doua portiune de cod
        fwrite($file, $part_2);
        // in fine urmeaza a treia portiune de cod
        $part_3 = "//BEGIN - FUNCTIONS\n";
        // se obtin functiile din cadrul fisierului XML
        if(!empty($module['functions']['function'])) {		
            $functions = $module['functions']['function'];
            if(is_array($functions)) {
                // modulul are o singura functie?
                if(array_key_exists("attributes", $functions)) {
                    $functions = array($functions);
                }
            }
            foreach($functions as $function) {
                $part_3 .= "function ".$filename."_".$function['attributes']['name']."(".$function['attributes']['params'].")\n{\n".$function['value']."\n}\n";    
			}
            $part_3 .= "//END - FUNCTIONS\n?>";
        }
        // se scrie ultima portiune de cod
        fwrite($file, $part_3);
    }
    // se inchide fisierul de lucru
    fclose($file);
    // totul a decurs bine
    return true;
}
?>