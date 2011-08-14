<?php
/*
    @author     : Surdeanu Mihai ;
    @date       : 10 august 2011 ;
    @version    : 2.0 ;
    @mybb       : compatibilitate cu MyBB 1.6 (orice versiuni) ;
    @description: Modificare pentru Sistemul de Limba Romana ;
    @homepage   : http://mybb.ro ! Te rugam sa ne vizitezi pentru ca nu ai ce pierde!
    @copyright  : Licenta speciala. Pentru mai multe detalii te rugam sa citesti sectiunea Licenta din cadrul fisierului 
                ReadME.pdf care poate fi gasit in acest pachet. Iti multumim pentru intelegere!
    ====================================
    Ultima modificare a codului : 05.08.2011 19:24
*/

// Poate fi acesat direct fisierul?
if(!defined("IN_MYBB")) {
    	die("This file cannot be accessed directly.");
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

// Functie de legatura
function rolang_meta()
{
	global $page, $lang, $plugins;
    // se include fisierul de limba
    rolang_include_lang("global");
    // meniul din partea stanga-jos
	$sub_menu = array();
	$sub_menu['10'] = array("id" => "infos", "title" => $lang->infos_title, "link" => "index.php?module=rolang");
	$sub_menu['20'] = array("id" => "news", "title" => $lang->news_title, "link" => "index.php?module=rolang-news");
	$sub_menu['30'] = array("id" => "updates", "title" => $lang->updates_title, "link" => "index.php?module=rolang-updates");
	$sub_menu['40'] = array("id" => "team", "title" => $lang->team_title, "link" => "index.php?module=rolang-team");
	// linia urmatoare de cod permite adaugarea unei noi legaturi in meniu
    $plugins->run_hooks_by_ref("admin_rolang_menu", $sub_menu);
	// adauga legatura in meniul principa;
	$page->add_menu_item($lang->mod_menu_item, "rolang", "index.php?module=rolang", 60, $sub_menu);
	// daca totul decurge bine se intoarce "adevarat"
	return true;
}

// Functie de tip handler
function rolang_action_handler($action)
{
    global $page, $plugins;
	// definirea modulului activ
	$page->active_module = "rolang";
	// definirea tuturor actiunilor cu care lucram
	$actions = array(
		'infos' => array('active' => 'infos', 'file' => 'infos.php'),
		'news' => array('active' => 'news', 'file' => 'news.php'),
		'updates' => array('active' => 'updates', 'file' => 'updates.php'),
		'team' => array('active' => 'team', 'file' => 'team.php')

	);
	// in cadrul linie de cod ce urmeaza se pot adauga actiuni noi
	$plugins->run_hooks_by_ref("admin_rolang_action_handler", $actions);
    // ce actiune este activa in acest moment?
	if(isset($actions[$action]))
	{
		$page->active_action = $actions[$action]['active'];
		return $actions[$action]['file'];
	}
	else
	{
		$page->active_action = "infos";
		return "infos.php";
	}
}

// Functie ce stabilieste permisiunile de lucru
function rolang_admin_permissions()
{
	global $lang, $plugins;
    // se include fisierul de limba
    rolang_include_lang("global");
	// permisiunile standard
	$admin_permissions = array(
        "rolang"	  => $lang->perm_rolang,
		"infos"       => $lang->perm_infos,
		"news"        => $lang->perm_news,
		"updates"     => $lang->perm_updates,
		"team"        => $lang->perm_team
	);
	// adauga si alte permisiuni
	$plugins->run_hooks_by_ref("admin_rolang_permissions", $admin_permissions);
	// vectorul cu informatiile permisiunilor
	return array("name" => "SLR", "permissions" => $admin_permissions, "disporder" => 60);
}

?>