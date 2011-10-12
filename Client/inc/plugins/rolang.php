<?php
/*
    @author     : Surdeanu Mihai ;
    @date       : 9 octombrie 2011 ;
    @version    : 2.2 ;
    @mybb       : compatibilitate cu MyBB 1.6 (orice versiuni) ;
    @description: Modificare pentru Sistemul de Limba Romana ;
    @homepage   : http://mybb.ro ! Te rugam sa ne vizitezi pentru ca nu ai ce pierde!
    @copyright  : Licenta speciala. Pentru mai multe detalii te rugam sa citesti sectiunea Licenta din cadrul fisierului 
                ReadME.pdf care poate fi gasit in acest pachet. Iti multumim pentru intelegere!
    ====================================
    Ultima modificare a codului : 28.09.2011 17:59
*/
    
// Poate fi acesat direct fisierul?
if(!defined("IN_MYBB")) {
    	die("This file cannot be accessed directly.");
}

// Aplicatia va fi folosita si pe post de server?
define("ROLANG_SERVER", 0); // 0 - aplicatia nu va fi folosita pe post de server; 1 - aplicatia este folosita pe post de server
/* Pentru a functiona la parametri normali serverul trebuie sa aiba pe langa
 * valoarea 1 la "ROLANG_SERVER" si cele doua fisiere, unul de administrare
 * si unul de legatura cu aplicatia clientului. */
// Variabila $admin verifica daca este activ serverul aplicatiei?
$is_server = defined("ROLANG_SERVER") && ROLANG_SERVER && file_exists(MYBB_ROOT."/admin/modules/rolang/admin.php") && file_exists(MYBB_ROOT."/server.php");

// Carlige de legatura cu core-ul MyBB
/* Interfata cu utilizatorul*/
$plugins->add_hook("index_start", "rolang_check_translation");

// Informatii legate de modificare
function rolang_info()
{
	return array(
		"name"		   => "Sistemul de Limb&#259; Rom&#226;n&#259;",
		"description"  => "Aceast&#259; modificare vine cu traducerea pachetului de limb&#259; rom&#226;n&#259; oferit de <a href=\"http://mybb.ro\" target=\"_blank\">MyBB Rom&#226;nia</a>.",
		"website"	   => "http://mybb.ro",
		"author"       => "Echipa MyBB Rom&#226;nia",
		"authorsite"   => "http://mybb.ro",
		"version"	   => "2.2",
		"guid"         => "",
		"compatibility"=> "16*"
	);
}

// Functia de instalare a modificarii
function rolang_install()
{
    global $db, $is_server;
    // inainte de a crea eventuale tabele vom vedea ce colocatie avem...
	$collation = $db->build_create_table_collation();
	// daca tabelul cu log-uri exista atunci nu se va mai crea
	if(!$db->table_exists("rolang_logs")) {
        // daca nu exista se purcede la crearea lui
        $db->write_query("CREATE TABLE `".TABLE_PREFIX."rolang_logs` (
            `lid` bigint(30) UNSIGNED NOT NULL auto_increment,
            `uid` bigint(30) UNSIGNED NOT NULL default '1',
            `type` varchar(256) NOT NULL default '',
            `data` TEXT NOT NULL,
            `date` bigint(30) UNSIGNED NOT NULL default '0',
            PRIMARY KEY  (`lid`), KEY(`date`)
                ) ENGINE=MyISAM{$collation}");
    }    
    // daca administrarea este permisa se mai adauga un tabel in baza de date
	if($is_server && !$db->table_exists("rolang_updates")) {
        // daca nu exista se purcede la crearea lui
        $db->write_query("CREATE TABLE `".TABLE_PREFIX."rolang_updates` (
            `aid` bigint(30) UNSIGNED NOT NULL auto_increment,
            `uid` bigint(30) UNSIGNED NOT NULL default '1',
            `active` int(1) UNSIGNED NOT NULL default '0',
            `for` varchar(256) NOT NULL default '',
            `for_version` int(4) UNSIGNED NOT NULL default '0',
            `for_compatibility` text NOT NULL,
            `hash` varchar(32) NOT NULL default '',
            `archive_lang` text NOT NULL,
            `archive_lang_size` bigint(30) UNSIGNED NOT NULL default '0',
            `archive_img` text NOT NULL,       
            `archive_img_size` bigint(30) UNSIGNED NOT NULL default '0',    
            `date` bigint(30) UNSIGNED NOT NULL default '0',
            PRIMARY KEY  (`aid`), KEY(`date`)
                ) ENGINE=MyISAM{$collation}");
    }
   	if($is_server && !$db->table_exists("rolang_stats")) {
        $db->write_query("CREATE TABLE `".TABLE_PREFIX."rolang_stats` (
            `sid` bigint(30) UNSIGNED NOT NULL auto_increment,
            `host` varchar(256) NOT NULL default '',
            `requests` bigint(30) UNSIGNED NOT NULL default '0',
            `date_first` bigint(30) UNSIGNED NOT NULL default '0',
            `date_last` bigint(30) UNSIGNED NOT NULL default '0',
            `have_data` int(1) UNSIGNED NOT NULL default '0',
            `version_mybb` int(4) UNSIGNED NOT NULL default '0',
            `version_php` int(4) UNSIGNED NOT NULL default '0',
            `server_soft` varchar(128) NOT NULL default '',
            `host_company` varchar(128) NOT NULL default '',
            `user_agent` varchar(128) NOT NULL default '',
            PRIMARY KEY  (`sid`), KEY(`host`)
                ) ENGINE=MyISAM{$collation}");
    }   
}

// Functia care verifica daca modificarea e instalata
function rolang_is_installed()
{
    global $db, $is_server;
    // exista tabelele aplicatiei ?
	if ($db->table_exists('rolang_logs') && (!$is_server || $db->table_exists('rolang_updates')) && (!$is_server || $db->table_exists('rolang_stats'))) {
        return true;
	}
	else {
        return false;
	}
}

// Functia prin care se dezinstaleaza o modificare
function rolang_uninstall()
{
    global $db, $is_server;
    // daca tabela "rolang_logs" exista in baza de date atunci se sterge!
	if ($db->table_exists('rolang_logs')) {
        $db->drop_table('rolang_logs');    
    }
    // daca tabela "rolang_updates" exista in baza si partea de administrare e activata, atunci se sterge
    if ($is_server && $db->table_exists('rolang_updates')) {
        $db->drop_table('rolang_updates');
    }
    if ($is_server && $db->table_exists('rolang_stats')) {
        $db->drop_table('rolang_stats');
    }
}

// Functia de activare a modificarii
function rolang_activate()
{
    global $db, $is_server;
    // se creaza setarile
   	$rolang_group = array(
   	    "gid" 		    => "NULL",
   	    "name" 		    => "rolang_group",
       	"title" 	    => "Sistemul de Limb&#259; Rom&#226;n&#259;",
       	"description" 	=> "Aici po&#355;i activa &#351;i administra aceast&#259; modificare.",
       	"disporder" 	=> "1",
       	"isdefault" 	=> "no",
   	);
   	$db->insert_query("settinggroups", $rolang_group);    
   	$gid = $db->insert_id();
   	$rolang_setting_1 = array(
   	    "sid"            => "NULL",
       	"name"           => "rolang_server_enable",
       	"title"          => "Server pentru actualiz&#259;ri?",
       	"description"    => "Dore&#351;ti s&#259; folose&#351;ti serverul nostru de date pentru eventualele update-uri automate ale traducerii?",
       	"optionscode"    => "yesno",
       	"value"          => "1",
       	"disporder"      => "1",
       	"gid"            => intval($gid),
   	);
   	$rolang_setting_2 = array(
   	    "sid"            => "NULL",
   	    "name"           => "rolang_server_url",
       	"title"          => "Adresa server-ului",
       	"description"    => "Introdu mai jos adresa web a server-ului folosit pentru eventuale actualiz&#259;ri.",
       	"optionscode"    => "text",
       	"value"          => "http://mybb.ro",
        "disporder"      => "2",
        "gid"            => intval($gid),
   	);
   	$rolang_setting_3 = array(
   	    "sid"            => "NULL",
       	"name"           => "rolang_server_setlanguage",
       	"title"          => "Dup&#259; instalarea unui pachet de limb&#259; se &#238;ncearc&#259; setarea forumului pe rom&#226;n&#259;?",
       	"description"    => "Dori&#355;i ca dup&#259; realizarea unei actualiz&#259;ri de limb&#259 rom&#226;n&#259;, forumul s&#259; fie setat, &#238;n mod implicit, pe aceast&#259; limb&#259?",
       	"optionscode"    => "yesno",
       	"value"          => "0",
       	"disporder"      => "3",
       	"gid"            => intval($gid),
   	);
   	$rolang_setting_4 = array(
   	    "sid"            => "NULL",
       	"name"           => "rolang_server_images",
       	"title"          => "Se actualizeaz&#259; &#351;i fi&#351;ierele de tip imagine la un update?",
       	"description"    => "Pe l&#226;ng&#259; actualizarea fi&#351;ierelor de limb&#259; se produce &#351;i actualizarea fi&#351;ierelor de tip imagine, la o instalare?",
       	"optionscode"    => "select\n0=Doar &#238;n cazul &#238;n care nu a mai existat o alt&#259; versiune.\n1=Da, la orice update.",
       	"value"          => "0",
       	"disporder"      => "4",
       	"gid"            => intval($gid),
   	);
   	$rolang_setting_5 = array(
   	    "sid"            => "NULL",
       	"name"           => "rolang_server_send_stats",
        "title"          => "C&#226;nd se trimit datele statistice ale server-ului dvs. c&#259;tre server-ul nostru?",
        "description"    => "Datele statistice se trimit c&#259;tre server-ul nostru pentru a realiza o ampl&#259; panoram&#259; asupra celor care utilizeaz&#259; aceast&#259; modificare.",
        "optionscode"    => "select\n0=Doar &#238;n cazul &#238;n care se realizeaz&#259; o verificare a posibilelor actualiz&#259;ri de pe server.\n1=&#350;i &#238;n cazul realiz&#259;rii unei verific&#259;ri dar &#351;i &#238;n cazul instal&#259;rii unei actualiz&#259;ri.",
        "value"          => "0",
        "disporder"      => "5",
        "gid"            => intval($gid),
   	);
   	$rolang_setting_6 = array(
   	    "sid"            => "NULL",
       	"name"           => "rolang_server_add_groups",
       	"title"          => "Utilizatorii urm&#259;tori nu vor putea activa o versiune de traducere :",
       	"description"    => "Urm&#259;torii membrii (reprezenta&#355;i prin id-ul lor) nu vor putea beneficia de facilitatea de a activa o versiune de traducere. Dac&#259; dori&#355;i s&#259; introduce&#355;i mai mult de un membru va trebui s&#259; utiliza&#355;i ca &#351;i separator virgula.",
       	"optionscode"    => "text",
       	"value"          => "0",
       	"disporder"      => "6",
       	"gid"            => intval($gid),
   	);
   	$rolang_setting_7 = array(
   	    "sid"            => "NULL",
   	    "name"           => "rolang_server_limit_hosts",
       	"title"          => "Seteaz&#259; un num&#259;r limit&#259; pentru domenii :",
       	"description"    => "P&#226;n&#259; la acest num&#259;r toate cererile de actualizare efectuate de c&#259;tre un client serverului sunt ad&#259;ugate &#238;n cache, iar dac&#259; limita se atinge informa&#355;iile atunci sunt ad&#259;ugate &#351;i &#238;n baza de date.",
       	"optionscode"    => "text",
       	"value"          => "10",
       	"disporder"      => "7",
       	"gid"            => intval($gid),
   	);
   	$rolang_setting_8 = array(
   	    "sid"            => "NULL",
       	"name"           => "rolang_server_limit_requests",
       	"title"          => "Seteaz&#259; un num&#259;r limit&#259; pentru cereri :",
       	"description"    => "P&#226;n&#259; la acest num&#259;r toate cererile de actualizare efectuate de c&#259;tre un client serverului sunt ad&#259;ugate &#238;n cache, iar dac&#259; limita se atinge informa&#355;iile atunci sunt ad&#259;ugate &#351;i &#238;n baza de date.",
       	"optionscode"    => "text",
       	"value"          => "50",
       	"disporder"      => "8",
       	"gid"            => intval($gid),
   	);
    // introduce setarile in baza de date
   	$db->insert_query("settings", $rolang_setting_1);
   	$db->insert_query("settings", $rolang_setting_2);
   	$db->insert_query("settings", $rolang_setting_3);
   	$db->insert_query("settings", $rolang_setting_4);
   	$db->insert_query("settings", $rolang_setting_5);
    // este activ modulul de administrare?
    if ($is_server) {
        $db->insert_query("settings", $rolang_setting_6);   
        $db->insert_query("settings", $rolang_setting_7); 
        $db->insert_query("settings", $rolang_setting_8); 
    }
    // reseteaza toate setarile
   	rebuild_settings();
    // schimba permisiunile modificarii
   	change_admin_permission("rolang", true, 1);
    change_admin_permission("rolang", "infos", 1);
    change_admin_permission("rolang", "news", 1);
    change_admin_permission("rolang", "updates", 1);
    change_admin_permission("rolang", "logs", 1);
    change_admin_permission("rolang", "stats", 1);
    change_admin_permission("rolang", "team", 1);
    if ($is_server)
        change_admin_permission("rolang", "admin", 1);   
    // la activare se adauga legatura
    rolang_runcmd('add');
}

// Functia de dezactivare a modificarii
function rolang_deactivate()
{
    	global $db, $is_server;
        // se sterg setarile din baza de date
	    $db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name = 'rolang_group'");
    	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name LIKE 'rolang_server_%'");
        // la dezactivare se sterge si un eventual cache din sistem
        $db->delete_query("datacache", "title = 'rolang_updates'"); 
        if ($is_server) {
            $db->delete_query("datacache", "title = 'rolang_releases'"); 
            $db->delete_query("datacache", "title = 'rolang_stats'");  
        }
        // se reseteaza toate setarile
    	rebuild_settings();
        // schimba permisiunile modificarii
       	change_admin_permission("rolang", false, -1);
        change_admin_permission("rolang", "infos", -1);
        change_admin_permission("rolang", "news", -1);
        change_admin_permission("rolang", "updates", -1);
        change_admin_permission("rolang", "logs", -1);
        change_admin_permission("rolang", "stats", -1);
        change_admin_permission("rolang", "team", -1);
        if ($is_server)
            change_admin_permission("rolang", "admin", -1);        
        // la dezactivare se sterge legatura
        rolang_runcmd('remove');
}

// Daca functia de includere a fisierului de limba nu exista deja se creeaza
if (!function_exists("rolang_include_lang"))
{
    // functia care permite lucrul cu fisiere de limba
    function rolang_include_lang($file, $reset = false, $options = array())
    {
        global $lang;
        // este resetat pachetul de limba ?
        if($reset) {
            $lang->set_path(MYBB_ROOT."inc/languages");
            return true;
        }
	    if(!$options['datahandler']) {
            // daca fisierul de limba este un handler
		    $options['datahandler'] = false;
        }
        if(!$options['hide_errors']) {
            // sunt ascunse eventualele erori
            $options['hide_errors'] = false;
        }
        // se seteaza noul director de limba
	    $lang->set_path(MYBB_ROOT."admin/modules/rolang/languages");
        // se incarca stringurile de limba din acele fisiere
        $lang->load($file, $options['datahandler'], $options['hide_errors']);
        // se revine la directorul mama
        $lang->set_path(MYBB_ROOT."inc/languages");
    }
}

// Functia de adaugare a unui log in baza de date
function rolang_add_log($type, $data, $user = 1)
{
    global $db;
    // se creaza randul care va fi introdus in baza de date
   	$log = array(
   	    "uid"   => intval($user),
       	"type"  => $db->escape_string($type),
        "data"  => $db->escape_string($data),
       	"date"  => TIME_NOW,
   	);
    // se introduce in baza de date si se intoarce id-ul sau
   	return $db->insert_query("rolang_logs", $log);  
}

// Functia de adaugare si stergere a copyright-ului catre MyBB Romania
function rolang_runcmd($action)
{
    global $mybb, $db;
    if ($action == "add")
    {
        // se adauga un link in subsol
       	require MYBB_ROOT."/inc/adminfunctions_templates.php";  
        find_replace_templatesets("footer", '#<!-- End powered by -->#', 'Traducere : <a href="http://www.mybb.ro" target="_blank" alt="Traducere MyBB Romania">MyBB Romania</a>.<!-- End powered by -->');   
        return true;
    }
    else if ($action == "remove")
    {
        // se sterse linkul catre noi din subsol
   	    require MYBB_ROOT."/inc/adminfunctions_templates.php";  
        find_replace_templatesets("footer", '#'.preg_quote('Traducere : <a href="http://www.mybb.ro" target="_blank" alt="Traducere MyBB Romania">MyBB Romania</a>.').'#', '', 0);   
        return true;
    }
    return false;
}

// Functia care verifica daca exista copyright catre noi pe forum
function rolang_checkCopyright()
{
    global $mybb;
    // se intoarce din db template-ul specificat
    $content = file_get_contents($mybb->settings['bburl']."/index.php");
    // se verifica daca exista un anumit cod HTML in cadrul acestui template
    if ($content != false && strpos($content, "<a href=\"http://www.mybb.ro\" target=\"_blank\" alt=\"Traducere MyBB Romania\">") === false) {
        return false;
    }
    else {
        return true;
    }
}

// Functia de mai jos trimite date statistice despre MyBB tau serverul mama
function rolang_send_server_info()
{
    global $mybb;
    $info = array();
    // autentificare
    $info['bburl'] = urlencode($mybb->settings['bburl']);
    $info['action'] = "send_stats";
    // se include un fisier MyBB
    require_once MYBB_ROOT."inc/functions_serverstats.php";
    $phpinfo = parse_php_info();
    // 1. Versiunea curenta a MyBB-ului tau
    $info['mybb_version'] = $mybb->version;
    // 2. Versiune de PHP
    $info['php_version'] = phpversion();
    // 3. Server Software
    $info['server_software'] = $_SERVER['SERVER_SOFTWARE'];
    // 4. URL Host
	$info['hosturl'] = $info['hostname'] = "unknown/local";
	if($_SERVER['HTTP_HOST'] == 'localhost') {
		$info['hosturl'] = $info['hostname'] = "localhost";
	}
	// 5. Se intoarce compania unde e hostat saitul
	if(strpos($_SERVER['HTTP_HOST'], ".") !== false)
	{
		$host_url = "http://www.whoishostingthis.com/".str_replace(array('http://', 'www.'), '', $_SERVER['HTTP_HOST']);
		$hosting = fetch_remote_file($host_url);
		if($hosting)
		{
			preg_match('#We believe \<a href\="http:\/\/www.whoishostingthis.com\/linkout\/\?t\=[0-9]&url\=?([^"]*)" (title="([^"]*)" )target\=\_blank\>([^<]*)\<\/a\>#ism', $hosting, $matches);
			$info['hosturl'] = "unknown/no-url";
			if(isset($matches[1]) && strlen(trim($matches[1])) != 0 && strpos($matches[1], '.') !== false)
			{
				$info['hosturl'] = strtolower($matches[1]);
			}
			else if(isset($matches[3]) && strlen(trim($matches[3])) != 0 && strpos($matches[3], '.') !== false)
			{
				$info['hosturl'] = strtolower($matches[3]);
			}
			if(isset($matches[4]) && strlen(trim($matches[4])) != 0)
			{
				$info['hostname'] = $matches[4];
			}
			elseif(isset($matches[3]) && strlen(trim($matches[3])) != 0)
			{
				$info['hostname'] = $matches[3];
			}
			elseif(isset($matches[2]) && strlen(trim($matches[2])) != 0)
			{
				$info['hostname'] = str_replace(array('title=', '"'), '', $matches[2][0]);
			}
			elseif(strlen(trim($info['hosturl'])) != 0 && $info['hosturl'] != "unknown/no-url")
			{
				$info['hostname'] = $info['hosturl'];
			}
			else
			{
				$info['hostname'] = "unknown/no-name";
			}
		}
	}
    // 6. HTPP User Agent
	if(isset($_SERVER['HTTP_USER_AGENT'])) {
		$info['useragent'] = $_SERVER['HTTP_USER_AGENT'];
	}
    
    // se creaza adresa web inspre care va fi trimisa cererea
   	$string = "";
	$amp = "";
	foreach($info as $key => $value) {
		$string .= $amp.$key."=".urlencode($value);
        $amp = "&";
	}
	$server_stats_url = $mybb->settings['rolang_server_url']."/server.php?".$string;
	$result = false;
	if(fetch_remote_file($server_stats_url) !== false) {
	   $result = true;
	}
    return $result;
}

// Functia de mai jos verifica daca ai o traducere cu copyright sau fara
function rolang_check_translation()
{
    global $mybb;
    // daca se apeleaza modulul de verificare din browser
    if ($mybb->input['action'] == "rolang_check")
    {
        $answer = $mybb->input['type'];
        $mod_info = rolang_info();
        // se include fisierul de limba
        $our = false;
        if (file_exists(MYBB_ROOT."inc/languages/romanian.php")) {
            include(MYBB_ROOT."inc/languages/romanian.php");
            if (isset($langinfo['procentage'])) {
                $our = true;
            }
        }
        else {
            $langinfo['version'] = "-";  
        }
        // mai intai se veriica daca exista un link catre noi in subsolul paginii
        // in mod implicit legatura nu exista
        $link = false;
        if (function_exists("rolang_checkCopyright") && rolang_checkCopyright('footer')) {
            // atunci inseamna ca exista legatura
            $link = true;
        }
        // daca copyright-ul nu exista are sens sa mergem si sa verificam daca exista o traducere folosita de limba romana
        if (!$link) 
        {
            // in mod implicit traducerea nu este a noastra!
            if ($langinfo['version'] != "-" && $our) {
                // atunci traducerea e a noastra si nu are copyright
                // vectorul ce va fi afisat pe ecran
                $data_check = array(
                    "date_of_check"     => TIME_NOW,
                    "mod_version"       => $mod_info['version'],
                    "lang_version"      => $langinfo['version'],
                    "our_work"          => intval($our),
                    "result_of_check"   => 0
                );
                // in functie de tipul ales, se afiseaza pe ecran rezultatul
                switch ($answer) {
                    case "json" :
                        $display = json_encode($data_check);
                        break;
                    default :
                        $display = print_r($data_check, true);
                }
                output_page($display);
            }       
            else {
                // traducerea nu e a noastra!
                // vectorul ce va fi afisat pe ecran
                $data_check = array(
                    "date_of_check"     => TIME_NOW,
                    "mod_version"       => $mod_info['version'],
                    "lang_version"      => $langinfo['version'],
                    "our_work"          => intval($our),
                    "result_of_check"   => 1
                );
                // in functie de tipul ales, se afiseaza pe ecran rezultatul
                switch ($answer) {
                    case "json" :
                        $display = json_encode($data_check);
                        break;
                    default :
                        $display = print_r($data_check, true);
                }
                output_page($display);
            }
        }
        else {
            // altfel se intoarce pe ecran faptul ca totul e bine
            // vectorul ce va fi afisat pe ecran
            $data_check = array(
                "date_of_check"     => TIME_NOW,
                "mod_version"       => $mod_info['version'],
                "lang_version"      => $langinfo['version'],
                "our_work"          => intval($our),
                "result_of_check"   => 1
            );
            // in functie de tipul ales, se afiseaza pe ecran rezultatul
            switch ($answer) {
                case "json" :
                    $display = json_encode($data_check);
                    output_page($display);
                    break;
                default :
                    $display = print_r($data_check, true);
                    output_page($display);
            }
        }
        // se intrerup alte verificari pentru a nu deforma rezultatul
        exit();
    }
    // pe cealalta ramura nu se intampla nimic pentru ca nu intra in atributiile acestui plugin
}
?>