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
    Ultima modificare a codului : 14.08.2011 11:52
*/

// Poate fi acesat direct fisierul?
if(!defined("IN_MYBB")) {
    	die("This file cannot be accessed directly.");
}

// Informatii legate de modificare
function rolang_info()
{
	return array(
		"name"		   => "Sistemul de Limb&#259; Rom&#226;n&#259;",
		"description"  => "Aceast&#259; modificare vine cu traducerea pachetului de limb&#259; rom&#226;n&#259; oferit de <a href=\"http://mybb.ro\" target=\"_blank\">MyBB Rom&#226;nia</a>.",
		"website"	   => "http://mybb.ro",
		"author"       => "Echipa MyBB Rom&#226;nia",
		"authorsite"   => "http://mybb.ro",
		"version"	   => "2.1",
		"guid"         => "",
		"compatibility"=> "16*"
	);
}

// Functia de activare a modificarii
function rolang_activate()
{
    	global $db;
        // se creaza setarile
    	$rolang_group = array(
        	"gid" 		=> "NULL",
        	"name" 		=> "rolang_group",
        	"title" 	=> "Sistemul de Limb&#259; Rom&#226;n&#259;",
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
        	"title"          => "Adresa serverului",
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
        // introduce setarile in baza de date
    	$db->insert_query("settings", $rolang_setting_1);
    	$db->insert_query("settings", $rolang_setting_2);
    	$db->insert_query("settings", $rolang_setting_3);
    	$db->insert_query("settings", $rolang_setting_4);
        // reseteaza toate setarile
    	rebuild_settings();
        // schimba permisiunile modificarii
       	change_admin_permission("rolang", false, 1);
        change_admin_permission("rolang", "infos", 1);
        change_admin_permission("rolang", "news", 1);
        change_admin_permission("rolang", "updates", 1);
        change_admin_permission("rolang", "team", 1);
        // la activare se adauga legatura
        rolang_runcmd('add');
}

// Functia de dezactivare a modificarii
function rolang_deactivate()
{
    	global $db;
        // se sterg setarile din baza de date
	    $db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name = 'rolang_group'");
    	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name LIKE 'rolang_server_%'");
        // la dezactivare se sterge si un eventual cache din sistem
        $db->delete_query("datacache", "title = 'rolang_updates'"); 
        // se reseteaza toate setarile
    	rebuild_settings();
        // schimba permisiunile modificarii
       	change_admin_permission("rolang", false, -1);
        change_admin_permission("rolang", "infos", -1);
        change_admin_permission("rolang", "news", -1);
        change_admin_permission("rolang", "updates", -1);
        change_admin_permission("rolang", "team", -1);
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
?>