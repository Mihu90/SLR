<?php
/*
    @author     : Surdeanu Mihai ;
    @date       : 09 octombrie 2011 ;
    @version    : 2.2 ;
    @mybb       : compatibilitate cu MyBB 1.6 (orice versiuni) ;
    @description: Modificare pentru Sistemul de Limba Romana ;
    @homepage   : http://mybb.ro ! Te rugam sa ne vizitezi pentru ca nu ai ce pierde!
    @copyright  : Licenta speciala. Pentru mai multe detalii te rugam sa citesti sectiunea Licenta din cadrul fisierului 
                ReadME.pdf care poate fi gasit in acest pachet. Iti multumim pentru intelegere!
    ====================================
    Ultima modificare a codului : 02.10.2011 17:08
*/

// Poate fi acesat direct fisierul?
if(!defined("IN_MYBB")) {
    	die("This file cannot be accessed directly.");
}

// Se verifica daca exista permisiuni
if (!$mybb->admin['permissions']['rolang']['admin'])
{
    $page->output_header($lang->access_denied);
    $page->add_breadcrumb_item($lang->access_denied, "index.php?module=home-index");
    $page->output_error("<b>{$lang->access_denied}</b><ul><li style=\"list-style-type: none;\">{$lang->access_denied_desc}</li></ul>");
    $page->output_footer();
    exit;
}

// Se include fisierul de limba
rolang_include_lang("admin");

$sub_tabs = array(
	"version" => array(
		"title" => $lang->admin_version_title,
		"link" => "index.php?module=rolang-admin",
		"description" => $lang->admin_version_description
	),
	"add" => array(
        "title" => $lang->admin_add_title,
        "link" => 'index.php?module=rolang-admin&amp;action=add',
        "description" => $lang->admin_add_description
	),
    "edit" => array(
        "title" => $lang->admin_edit_title,
        "link" => 'index.php?module=rolang-admin&amp;action=edit',
        "description" => $lang->admin_edit_description
    ),
    "archive" => array(
        "title" => $lang->admin_archive_title,
        "link" => 'index.php?module=rolang-admin&amp;action=archive',
        "description" => $lang->admin_archive_description
    )
);

// Ruleaza in momentul in care se cere de catre user verificarea unui path
if($mybb->input['ajax'] && $mybb->input['action'] == "check_path")
{
    // exista directorul pe server?
    $path = str_replace("MYBB_ROOT/", "", $mybb->input['path']);
    if ($path != "MYBB_ROOT" && !is_dir(MYBB_ROOT.$path)) {
        echo 0;
    }
    else {
        echo 1;
    }
	// se iese fortat
    exit(); 
}

// pagina principala ?
if (!$mybb->input['action'])
{
    // daca nu este definita nicio actiune se afiseaza informatiile de baza
	$page->add_breadcrumb_item($lang->mod_name);
    // pentru inceput este adaugat un breadcrumb
    $page->add_breadcrumb_item($lang->admin_title, 'index.php?module=rolang-admin');
    // se prelucreaza datele
    if($mybb->request_method == 'post')
	{
        verify_post_check($mybb->input['my_post_key']);
        // conditiile de lucru
        $conditions = rolang_build_condition_array();
        // ce se intampla daca nu e definita nicio conditie?
        if (count($conditions) == 0) {
            flash_message($lang->admin_actions_error_nocondition, 'error');
            // se face redirect
            admin_redirect("index.php?module=rolang-admin");  
        }
       	// vector cu conditiile prelucrate
        $sql = array();
        // convertire
        $sql_tests = array(
            'eq' => " = ",
			'neq' => " <> ",
			'empty' => " = ''",
			'notempty' => " <> ''",
			'null' => " IS NULL",
			'notnull' => " IS NOT NULL",
			'gt' => " > ",
			'lt' => " < ",
			'lte' => " <= ",
			'gte' => " >= ",
			'in' => " IN",
			'nin' => " NOT IN",
			'like' => " LIKE ",
			'nlike' => " NOT LIKE "
		);
        // pentru fiecare conditie din lista trimisa
        foreach($conditions as $condition)
        {
            // verificam daca testul e "in" sau "nin"
            if($condition['test'] == "in" || $condition['test'] == "nin")
            {
                $values = array();
                if(preg_match_all("/[^,\"']+|\"([^\"]*)\"|'([^']*)'/si", $condition['value'], $matches))
                {
                    $condition['value'] = array();
                    foreach($matches[0] as $value) {
                        $condition['value'][] = str_replace(array("\"","'"),"",$value);
                    }
                }
            }
            // alte campuri
            $field = $condition['field'];
            $sql_test = isset($sql_tests[$condition['test']]) ? $sql_tests[$condition['test']] : " = ";
            $field_type = isset($fields[$condition['field']]) ? $fields[$condition['field']]['type'] : "string";
            $values = $condition['value'];
            if(!is_array($values)) {
                $values = array($values);
            }
            // se prelucreaza valorile introduse in functie de tipul campului
            $clean_values = array();
            switch($field_type)
            {
            case 'date':
                // daca e de acest tip se poate sa fie numeric sau sa fie precizata o data relativa
				foreach($values as $value) 
                {
					if(is_numeric($condition['value'])) {
						$clean_values[] = intval($condition['value']);
					}
					else {
						$clean_values[] = strtotime($condition['value']);
					}
				}
				break;
			case 'int':
				foreach($values as $value) {
					$clean_values[] = intval($value);
				}
				break;
			case 'string':
				foreach($values as $value) {
				    // linia urmatoare are rolul de a securiza eventualele atacuri de tip SQL Injection
					$clean_values[] = "'".$db->escape_string($value)."'";
				}
				break;
            }
            // teste aditionale
            if($condition['test'] == 'in' || $condition['test'] == 'nin') {
                $clean_values = "(".implode(',', $clean_values).")";
            }
            elseif($condition['test'] == 'empty' || $condition['test'] == 'notempty' || $condition['test'] == 'null' || $condition['test'] == 'notnull') {
                $clean_values = "";
            }
            else {
                $clean_values = str_replace('*', '%', $clean_values[0]);
            }
            // testele SQL
            $sql[] = $field.$sql_test.$clean_values;
        }
        // se construieste interogarea si se ruleaza in functie de actiunea aleasa
        $action = $db->escape_string($mybb->input['rolang_switch']);
        $where = implode(" AND ", $sql);
        switch ($action) 
        {
            case "delete" :
                // mai intai se realizeaza o selectie si se intoarce din baza de date toate randurile care vor fi sterse
                $query_link = $db->query("SELECT DISTINCT archive_lang FROM ".TABLE_PREFIX."rolang_updates WHERE {$where}");
                $query_image = $db->query("SELECT DISTINCT archive_img FROM ".TABLE_PREFIX."rolang_updates WHERE archive_img NOT IN(SELECT DISTINCT t1.archive_img FROM (SELECT archive_img FROM ".TABLE_PREFIX."rolang_updates WHERE !({$where})) AS t1)");
                // randurile gasite sunt sterse
                $db->delete_query("rolang_updates", $where); 
                // cate randuri au fost afectate de procesul de stergere?
                $rows = intval($db->affected_rows());
                switch ($rows)
                {
                    case 0 :
                        flash_message($lang->admin_actions_delete_no, 'error');
                        break;
                    case 1 :
                        flash_message($lang->admin_actions_delete_one, 'success');
                        break;
                    default:
                        flash_message($lang->sprintf($lang->admin_actions_delete_more, $rows), 'success');
                        break;
                }
                // se sterg fisierele necesare
                //1. in primul rand arhivele lingvistice
                while ($row = $db->fetch_array($query_link)) {
                    $filename = basename(base64_decode($row['archive_lang']));
                    if (file_exists(MYBB_ROOT."inc/plugins/rolang/uploads/".$filename)) {
                        @unlink(MYBB_ROOT."inc/plugins/rolang/uploads/".$filename);
                    }
                }
                //2. in al doilea rand arhivele cu seturile de imagini
                while ($row = $db->fetch_array($query_image)) {
                    $filename = basename(base64_decode($row['archive_img']));
                    if (file_exists(MYBB_ROOT."inc/plugins/rolang/uploads/".$filename)) {
                        @unlink(MYBB_ROOT."inc/plugins/rolang/uploads/".$filename);
                    }
                }
                break;
            case "change" :
                // randurile gasite sunt schimbate ca si status
                $db->update_query("rolang_updates", array("active" => "!active"), "{$where}");
                $rows = intval($db->affected_rows());
                switch ($rows)
                {
                    case 0 :
                        flash_message($lang->admin_actions_change_no, 'error');
                        break;
                    case 1 :
                        flash_message($lang->admin_actions_change_one, 'success');
                        break;
                    default:
                        flash_message($lang->sprintf($lang->admin_actions_change_more, $rows), 'success');
                        break;
                }
                break;
            default :
                // se cauta o anumita versiune
                $select = $db->simple_select("rolang_updates", "*", "{$where}", array("limit" => 1));
                if ($db->num_rows($select) > 0) {
                    // s-a gasit o versiune
                    $row = $db->fetch_array($select);
                    flash_message($lang->sprintf($lang->admin_actions_search_success, $db->escape_string($row['for']), intval($row['for_version']), $db->escape_string($row['for_compatibility']), base64_decode($row['archive_lang']), rolang_get_size($row['archive_lang_size']), base64_decode($row['archive_img']), rolang_get_size($row['archive_img_size'])), 'success');
                }   
                else {
                    // nu s-a gasit nicio versiune
                    flash_message($lang->admin_actions_search_no, 'error');
                }
        }
        // se face un redirect
		admin_redirect("index.php?module=rolang-admin");   
	}
    // se afiseaza antetul paginii, adaugandu-se mai intai un extra cod in antet		
    $page->extra_header .= '
    <style type="text/css">
    a.rolang_add_filter, a.rolang_delete_filter, a.rolang_get_help {
        display: block; height: 16px; width: 16px; text-indent: -999px; text-decoration: none; overflow: hidden; padding: 0px; margin: 0px;
    }
    a.rolang_add_filter {
        background: transparent url(../images/rolang/add.png);
	}
    a.rolang_delete_filter {
	    background: transparent url(../images/rolang/delete.png);
    }
    a.rolang_get_help {
	    background: transparent url(../images/rolang/help.png);
        cursor: help;
        float: right;
    }
    .rolang_field, .rolang_test, .rolang_value, .rolang_switch {
        width: 100%;
    }
    </style>
	<script type="text/javascript" src="../jscripts/scriptaculous.js?load=effects"></script>
	<script type="text/javascript">
        var rolang_deleteFilter = function(e) {
            var deleteRow = this.up(1);
            Effect.Fade(deleteRow, {\'duration\': 1, \'afterFinish\': function(){ deleteRow.remove(); }});
            Event.stop(e);
        };
        Event.observe(document, "dom:loaded", function() {
            // evenimentul pentru adaugarea unui filtru la stergerea log-urilor
            Event.observe(\'rolang_add_filter\', \'click\', function(e) {
                var addRow = this.up(1);
                var cloneRow = addRow.cloneNode(true);
                cloneRow.removeClassName(\'first\');
                cloneRow.hide();
                cloneRow.select(".first").invoke(\'removeClassName\', \'first\');
                var fieldValue = addRow.select(\'.rolang_field\')[0].value;
                var testValue = addRow.select(\'.rolang_test\')[0].value;
                cloneRow.select(\'.rolang_field\')[0].value = fieldValue;
                cloneRow.select(\'.rolang_test\')[0].value = testValue;
                cloneRow.select(\'.rolang_add_filter\').invoke(\'replace\',\'<a href="#" class="rolang_delete_filter">&nbsp;</a>\');
                cloneRow.select(\'.rolang_delete_filter\').invoke(\'observe\', \'click\', rolang_deleteFilter);
                addRow.select(\'.rolang_field\').each(function(f){ f.value = \'\'; });
                addRow.select(\'.rolang_test\').each(function(f){ f.value = \'\'; });
                addRow.select(\'.rolang_value\').each(function(f){ f.value = \'\'; });
                addRow.insert({\'after\': cloneRow});
                Effect.Appear(cloneRow, {\'duration\': 1});
                Event.stop(e);
	        });
            // evenimentul pentru stergerea unui rand din filtru
            $$(\'.rolang_delete_filter\').invoke(\'observe\', \'click\', rolang_deleteFilter);
            // evenimentul pentru a apare casuta de ajutor
            $$(\'.rolang_get_help\').invoke(\'observe\', \'click\', function(e) {
                if($(this.rel)) {
                    Effect.toggle(this.rel, \'Blind\');
                }
                Event.stop(e);
            });
            $$(".rolang_close_help").invoke("observe", "click", function(e) {
                Effect.toggle(this.up(3), "Blind");
                Event.stop(e);
            });
            // in mod implicit casuta de ajutor nu este afisata pe ecran
            $("rolang_prune_help").hide();
        });	   
	</script>';
	$page->output_header($lang->admin_title);
    // se afiseaza meniul orizontal
    $page->output_nav_tabs($sub_tabs, 'version');
    // se realizeaza paginarea in vederea afisarii tabelului cu toate versiunile
    $per_page = 10; // in mod implicit
    if($mybb->input['page'] && intval($mybb->input['page']) > 1) {
        $mybb->input['page'] = intval($mybb->input['page']);
        $start = ($mybb->input['page'] * $per_page) - $per_page;
    }
    else {
        $mybb->input['page'] = 1;
        $start = 0;
    }
    // acum paginarea este in regula, se trece la obtinerea datelor din tabel
    $query = $db->simple_select("rolang_updates", "COUNT(aid) as updates");
    // variabila ce retine numarul de randuri obtinute din interogare
    $total_rows = $db->fetch_field($query, "updates");
    // se construieste tabelul se urmeaza sa fie afisat
    $table = new Table;
    $table->construct_header($lang->admin_table_user, array('width' => '15%'));
    $table->construct_header($lang->admin_table_active, array('width' => '12%'));
    $table->construct_header($lang->admin_table_version, array('width' => '10%'));
    $table->construct_header($lang->admin_table_name, array('width' => '20%'));
    $table->construct_header($lang->admin_table_compatibility, array('width' => '20%'));
    $table->construct_header($lang->admin_table_date, array('width' => '15%', 'class' => 'align_center'));
    $table->construct_header($lang->admin_table_actions, array('width' => '8%', 'class' => 'align_center'));
    // se creaza interogarea
    $query = $db->query("
        SELECT u.*, u.username AS userusername, a.*
        FROM ".TABLE_PREFIX."rolang_updates a
        LEFT JOIN ".TABLE_PREFIX."users u ON (u.uid = a.uid)
        ORDER BY a.date DESC LIMIT {$start}, {$per_page}
    ");
    // se creaza tabelul rand cu rand
    while ($update = $db->fetch_array($query))
    {
        $active = "nu";
        if (intval($update['active']) == 1) {
            $active = "da";
        }
        $table->construct_cell(build_profile_link(htmlspecialchars_uni($update['username']), intval($update['uid'])), array('class' => 'align_center'));
        $table->construct_cell($active, array('class' => 'align_center'));
        $table->construct_cell($db->escape_string($update['for_version']), array('class' => 'align_center'));
        $table->construct_cell($db->escape_string($update['for']), array('class' => 'align_center'));
        $table->construct_cell($db->escape_string($update['for_compatibility']), array('class' => 'align_center'));
        $table->construct_cell(my_date($mybb->settings['dateformat'], intval($update['date']), '', false).", ".my_date($mybb->settings['timeformat'], intval($update['date'])), array('class' => 'align_center'));
        $popup = new PopupMenu("version_{$update['aid']}", $lang->mod_table_options);
        $popup->add_item($lang->admin_table_options_edit, "index.php?module=rolang-admin&amp;action=edit&amp;aid=".intval($update['aid']));
        $popup->add_item($lang->admin_table_options_status, "index.php?module=rolang-admin&amp;action=status_update&amp;aid=".intval($update['aid']));
        $popup->add_item($lang->admin_table_options_delete, "index.php?module=rolang-admin&amp;action=delete_update&amp;aid=".intval($update['aid']));
        $table->construct_cell($popup->fetch(), array('style' => "text-align:center"));
        $table->construct_row();
    }
    // in cazul in care nu a existat niciun rand intors din baza de date atunci se afiseaza un mesaj central
    if($table->num_rows() == 0) {
        $table->construct_cell($lang->admin_table_without, array('class' => 'align_center', 'colspan' => 7));
        $table->construct_row();
    }
    // in final se afiseaza tabelul pe ecranul utilizatorului
    $table->output($lang->admin_table_title);
    // se realizeaza paginarea
    echo draw_admin_pagination($mybb->input['page'], $per_page, $total_rows, "index.php?module=rolang-admin&amp;page={page}");
    // formular prin care pot fi sterse o serie de log-uri
	echo '<div id="rolang_prune_help" class="rolang_prune_help">';
	$form_container = new FormContainer('<a name="help">'.$lang->logs_prune_help.'</a><span style="float:right">[<a href="#" class="rolang_close_help" >'.$lang->logs_prune_help_close.'</a>]</span>');
    $form_container->output_cell($lang->admin_actions_help_content);
	$form_container->construct_row();
	$form_container->end();
	echo '</div>';
    // formular prin care pot fi sterse o serie de log-uri
	$form = new Form("index.php?module=rolang-admin", "post", "admin");
	// se genereaza o cheie de tip post
	echo $form->generate_hidden_field("my_post_key", $mybb->post_code);
	// numele formularului	
	$form_container = new FormContainer($lang->admin_actions_title."<a href=\"#help\" class=\"rolang_get_help\" rel=\"rolang_prune_help\">&nbsp;</a>");
	$form_container->output_row_header($lang->admin_actions_field, array("style" => "width: 35%"));
	$form_container->output_row_header($lang->admin_actions_test, array("style" => "width: 35%"));
	$form_container->output_row_header($lang->admin_actions_value, array("style" => "width: 28%"));
	$form_container->output_row_header("&nbsp;", array("class" => "align_center", "style" => "width: 2%"));
	// se returneaza toate campurile posibile
    $fields = rolang_get_fields();
	$field_select = array('' => '');
	foreach($fields as $key => $field) {
        $field_select[$key] = $field['title'];
	}
    // se genereaza list-ul cu campurile
	$form_container->output_cell(
	    $form->generate_select_box('field[]', $field_select, array(), array('class' => 'rolang_field'))
	);
    // se genereaza list-ul cu posibile teste
	$form_container->output_cell(
        $form->generate_select_box('test[]', rolang_get_tests(), array(), array('class' => 'rolang_test'))
	);
	// se genereaza textbox-ul cu valoarea de test	
	$form_container->output_cell(
        $form->generate_text_box('value[]', "", array('class' => 'rolang_value'))
	);
	// in fine, urmeaza si imaginea	
	$form_container->output_cell(
        '<a href="#" class="rolang_add_filter" id="rolang_add_filter">&nbsp;</a>', array('style' => "text-align:center")
	);
	$form_container->construct_row();
    $form_container->output_cell(
        $lang->admin_actions_switch,
        array('colspan' => 2)
	);
    $form_container->output_cell(
        $form->generate_select_box("rolang_switch", array("search" => $lang->admin_actions_search, "change" => $lang->admin_actions_change, "delete" => $lang->admin_actions_delete), array(), array('class' => 'rolang_switch')),
        array('colspan' => 2)
	);
    $form_container->construct_row();
    // se afiseaza formularul pe ecran			
	$form_container->end();
    // butoanele din cadrul formularului		
	$buttons = array();
	$buttons[] = $form->generate_submit_button($lang->admin_button_submit);
	$buttons[] = $form->generate_reset_button($lang->admin_button_reset);
	$form->output_submit_wrapper($buttons);
	$form->end();
    // in fine se afiseaza si subsolul paginii
    $page->output_footer();
}
elseif ($mybb->input['action'] == 'delete_update')
{
    if($mybb->input['no']) {
        // userul nu a mai confirmat
        admin_redirect("index.php?module=rolang-admin");
    }
    // se verifica cererea
    if($mybb->request_method == "post")
    {
        // daca codul cererii nu e corect atunci se afiseaza o eroare pe ecran
        if(!isset($mybb->input['my_post_key']) || $mybb->post_code != $mybb->input['my_post_key']) {
            $mybb->request_method = "get";
            flash_message($lang->admin_table_options_error, 'error');
            admin_redirect("index.php?module=rolang-admin");
        }
        // exista id-ul update-ului specificat in cerere in sistem?
        if (!($version = $db->fetch_array($db->simple_select('rolang_updates', 'archive_lang,archive_img', 'aid = '.intval($mybb->input['aid']), array('limit' => 1))))) {
            flash_message($lang->admin_table_options_delete_invalid, 'error');
            admin_redirect('index.php?module=rolang-admin');
        }
        else {				
            // daca se ajunge pe aceasta ramura inseamna ca se poate sterge update-ul
            $db->delete_query('rolang_updates', 'aid = '.intval($mybb->input['aid']));
            // pe langa stergerea versiunii din baza de date se vor sterge si fisierele de pe disk
            if (file_exists(MYBB_ROOT."inc/plugins/rolang/uploads/".basename(base64_decode($version['archive_lang'])))) {
                @unlink(MYBB_ROOT."inc/plugins/rolang/uploads/".basename(base64_decode($version['archive_lang'])));
            }
            if (file_exists(MYBB_ROOT."inc/plugins/rolang/uploads/".basename(base64_decode($version['archive_img']))) && $db->num_rows($db->simple_select('rolang_updates', 'date', 'archive_img = \''.$version['archive_img'].'\'')) == 0) {
                // trebuie facuta o verificare in plus deoarece se poate ca acceasi arhiva sa fie folosita de alt update
                @unlink(MYBB_ROOT."inc/plugins/rolang/uploads/".basename(base64_decode($version['archive_img'])));
            }
            // se afiseaza pe ecran un mesaj precum totul s-a realizat cu succes
            flash_message($lang->sprintf($lang->admin_table_options_delete_deleted, intval($mybb->input['aid'])), 'success');
            admin_redirect('index.php?module=rolang-admin');
        }
    }
    else {
        // pagina de confirmare
        $page->add_breadcrumb_item($lang->mod_name);
        $page->add_breadcrumb_item($lang->admin_title, 'index.php?module=rolang-admin');
        // se afiseaza antetul paginii	
        $page->output_header($lang->admin_title);
        // se converteste inputul la intreg
        $mybb->input['aid'] = intval($mybb->input['aid']);
        $form = new Form("index.php?module=rolang-admin&amp;action=delete_update&amp;aid={$mybb->input['aid']}&amp;my_post_key={$mybb->post_code}", 'post');
        echo "<div class=\"confirm_action\">\n";
        echo "<p>".$lang->sprintf($lang->admin_table_options_delete_confirm, $mybb->input['aid'])."</p>\n";
        echo "<br />\n";
        echo "<p class=\"buttons\">\n";
        echo $form->generate_submit_button($lang->yes, array('class' => 'button_yes'));
        echo $form->generate_submit_button($lang->no, array("name" => "no", 'class' => 'button_no'));
        echo "</p>\n";
        echo "</div>\n";
        $form->end();
        // in fine se afiseaza si subsolul paginii
        $page->output_footer();
    }
}
elseif ($mybb->input['action'] == 'status_update')
{
    if($mybb->input['no']) {
        // userul nu a mai confirmat
        admin_redirect("index.php?module=rolang-admin");
    }
    // se verifica cererea
    if($mybb->request_method == "post")
    {
        // daca codul cererii nu e corect atunci se afiseaza o eroare pe ecran
        if(!isset($mybb->input['my_post_key']) || $mybb->post_code != $mybb->input['my_post_key']) {
            $mybb->request_method = "get";
            flash_message($lang->admin_table_options_error, 'error');
            admin_redirect("index.php?module=rolang-admin");
        }
        // se testeaza daca exista drepturi pentru a activa o versiune
        $groups = explode(',', $mybb->settings['rolang_server_add_groups']);
        if (in_array($mybb->user['uid'], $groups)) {
            flash_message($lang->admin_table_options_status_noperm, 'error');
            admin_redirect("index.php?module=rolang-admin");
        }
        // mai departe se incearca schimbarea statusului update-ului
        $db->update_query("rolang_updates", array("active" => "!active"), "aid = '".intval($mybb->input['aid'])."'");
        // totul s-a realizat cu succes
        flash_message($lang->sprintf($lang->admin_table_options_status_changed, intval($mybb->input['aid'])), 'success');
        admin_redirect('index.php?module=rolang-admin');
    }
    else {
        // pagina de confirmare
        $page->add_breadcrumb_item($lang->mod_name);
        $page->add_breadcrumb_item($lang->admin_title, 'index.php?module=rolang-admin');
        // se afiseaza antetul paginii	
        $page->output_header($lang->admin_title);
        // se converteste inputul la intreg
        $mybb->input['aid'] = intval($mybb->input['aid']);
        $form = new Form("index.php?module=rolang-admin&amp;action=status_update&amp;aid={$mybb->input['aid']}&amp;my_post_key={$mybb->post_code}", 'post');
        echo "<div class=\"confirm_action\">\n";
        echo "<p>".$lang->sprintf($lang->admin_table_options_status_confirm, $mybb->input['aid'])."</p>\n";
        echo "<br />\n";
        echo "<p class=\"buttons\">\n";
        echo $form->generate_submit_button($lang->yes, array('class' => 'button_yes'));
        echo $form->generate_submit_button($lang->no, array("name" => "no", 'class' => 'button_no'));
        echo "</p>\n";
        echo "</div>\n";
        $form->end();
        // in fine se afiseaza si subsolul paginii
        $page->output_footer();
    }
}
elseif ($mybb->input['action'] == 'add')
{
    // pagina de adaugare a unei versiuni de traducere
	if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') { 
		flash_message($lang->sprintf($lang->admin_upload_exceeded, ini_get('post_max_size')), 'error');
		admin_redirect("index.php?module=rolang-admin&amp;action=add");
	} 
    // se adauga versiunea in baza de date ?
	if ($mybb->request_method == "post")
	{		
        // exista campuri necompletate?
		if (empty($mybb->input['for']) && empty($mybb->input['for_version']) && empty($mybb->input['for_compatibility']))
		{
			flash_message($lang->admin_upload_empty, 'error');
			admin_redirect("index.php?module=rolang-admin&amp;action=add");
		}
        // se verifica versiunea si compatibilitatea
        if (!preg_match("/^(\d{4})$/", $mybb->input['for_version']) || !preg_match("/^(\d{4})(,(\d{4}))*$/", $mybb->input['for_compatibility']))
		{
            flash_message($lang->admin_upload_wrongversion, 'error');
            admin_redirect("index.php?module=rolang-admin&amp;action=add");
		}
        // se verifica valoarea campului "active"
		if (intval($mybb->input['active']) != 0 && intval($mybb->input['active']) != 1)
		{
			flash_message($lang->admin_upload_wrongactive, 'error');
			admin_redirect("index.php?module=rolang-admin&amp;action=add");
		}
        // se verifica fisierele incarcate pe server
		$link = basename($_FILES['archive_lang']['name']);
		$images = basename($_FILES['archive_img']['name']);
        // variabila prin care se verifica daca sunt erori
        $error = false;
		// se incarca prima arhiva
		$link_file = rolang_upload_attachment($_FILES['archive_lang']);
		// se verifica daca nu cumva au aparut erori
		if($link_file['error'])
		{
            $error = true;
			flash_message($lang->sprintf($lang->admin_upload_error, $_FILES['archive_lang']['name'], $link_file['error']), 'error');
			admin_redirect("index.php?module=rolang-admin&amp;action=add");
		}
        $image_file = array();
        if (empty($_FILES['archive_img']['name']) && is_numeric($mybb->input['copy']))
        {
            // in acest caz se incearca sa se intoarca din baza de date un set de imagini valid
            if (strlen($mybb->input['copy']) == 4) {
                // in acest caz exista o valoare exacta
                if (!($version = $db->fetch_array($db->simple_select('rolang_updates', 'archive_img,archive_img_size', "for_version = '".intval($mybb->input['copy'])."'"))))
                {
                    @unlink(MYBB_ROOT."inc/plugins/rolang/uploads/".$link_file['filename']);
     			    flash_message($lang->sprintf($lang->admin_upload_copyset, intval($mybb->input['copy'])), 'error');
                    admin_redirect("index.php?module=rolang-admin&amp;action=add");
                }
                else {
                    $image_file['filename'] = basename(base64_decode($version['archive_img']));
                    $image_file['filesize'] = $version['archive_img_size'];
                }
            }   
            elseif (intval($mybb->input['copy']) < 0) {
                if (!($version = $db->fetch_array($db->simple_select('rolang_updates', 'archive_img,archive_img_size', "for_version <= '".(intval($mybb->input['for_version']) + intval($mybb->input['copy']))."'", array('order_by' => 'for_version', 'order_dir' => 'DESC', 'limit' => 1)))))
                {
                    @unlink(MYBB_ROOT."inc/plugins/rolang/uploads/".$link_file['filename']);
     			    flash_message($lang->sprintf($lang->admin_upload_copyset1, (intval($mybb->input['for_version']) + intval($mybb->input['copy']))), 'error');
                    admin_redirect("index.php?module=rolang-admin&amp;action=add");
                }
                else {
                    $image_file['filename'] = basename(base64_decode($version['archive_img']));
                    $image_file['filesize'] = $version['archive_img_size'];
                }             
            }
            else {
                @unlink(MYBB_ROOT."inc/plugins/rolang/uploads/".$link_file['filename']);
 			    flash_message($lang->admin_upload_copyset2, 'error');
                admin_redirect("index.php?module=rolang-admin&amp;action=add");
            }
        }
        else {
            // se incarca cea de-a doua arhiva
		    $image_file = rolang_upload_attachment($_FILES['archive_img']);
            if($image_file['error'])
            {
                // daca la al doilea fisier apare o eroare atunci se va sterge primul fisier incarcat
                if (!$error) {
                    @unlink(MYBB_ROOT."inc/plugins/rolang/uploads/".$link_file['filename']);
                }
                $error = true;
			    flash_message($lang->sprintf($lang->admin_upload_error, $_FILES['archive_img']['name'], $image_file['error']), 'error');
			    admin_redirect("index.php?module=rolang-admin&amp;action=add");          
            }
        }
		if(!$error) 
        {
            // totul este OK!
		    $update = array(
                "uid"               => $mybb->user['uid'],
                "active"            => intval($mybb->input['active']),
                "for"               => $db->escape_string($mybb->input['for']),
                "for_version"	    => intval($mybb->input['for_version']),
		        "for_compatibility"	=> $db->escape_string($mybb->input['for_compatibility']),
                "hash"		        => md5("rolang_".intval($mybb->input['for_version'])."_".uniqid(rand(), true)),
                "archive_lang"      => base64_encode($mybb->settings['bburl']."/inc/plugins/rolang/uploads/".$db->escape_string($link_file['filename'])),
                "archive_lang_size" => intval($link_file['filesize']),
                "archive_img"       => base64_encode($mybb->settings['bburl']."/inc/plugins/rolang/uploads/".$db->escape_string($image_file['filename'])),
                "archive_img_size"  => intval($image_file['filesize']),
                "date"			    => TIME_NOW
		    );
            // se introduce vectorul ca rand nou in tabela modificarii din baza de date
            $id = $db->insert_query("rolang_updates", $update);
			// se adauga un jurnal in sistem
            if (function_exists("rolang_add_log")) {
                rolang_add_log("<font color=green>".$lang->infos_button_type_log."</font>", $lang->sprintf($lang->admin_log_add_success, $id), $mybb->user['uid']);
            }
            // se afiseaza pe ecran un mesaj
            flash_message($lang->sprintf($lang->admin_upload_success, intval($id)), 'success');
            admin_redirect("index.php?module=rolang-admin");
        }
	}
	else {	
        // pentru inceput este adaugat un breadcrumb
        $page->add_breadcrumb_item($lang->mod_name);
        $page->add_breadcrumb_item($lang->admin_title, 'index.php?module=rolang-admin');
        // se testeaza daca exista drepturi pentru a activa o versiune
        $groups = explode(',', $mybb->settings['rolang_server_add_groups']);
        if (in_array($mybb->user['uid'], $groups)) {
            $is_active = "true";
        }
        else {
            $is_active = "false";
        }
        // se afiseaza antetul paginii, adaugandu-se mai intai un extra cod in antet	
        $page->extra_header .= '
        <script type="text/javascript" src="../jscripts/scriptaculous.js?load=effects"></script>
        <script type="text/javascript">
            Event.observe(document, "dom:loaded", function() {
                $$(\'.rolang_copy_toogle\').invoke(\'observe\', \'click\', function(e) {
                    if($(this.rel)) {
                        Effect.toggle(this.rel, \'appear\', { duration: 1.0 });
                    }
                    Event.stop(e);
                });
                // este activ butonul de activare ?
                $(\'active-yes\').disabled = '.$is_active.';
                // in mod implicit randul de copiere a setului de imagini nu se va afisa pe ecran
                $(\'rolang_copy_imageset\').hide();
            });	   
        </script>';	
        $page->output_header($lang->admin_title);
        // se afiseaza meniul orizontal
        $page->output_nav_tabs($sub_tabs, 'add');
		$form = new Form("index.php?module=rolang-admin&amp;action=add", "post", "admin", 1);
		$form_container = new FormContainer($lang->admin_add_title);
		$form_container->output_row($lang->admin_form_name, $lang->admin_add_name_desc, $form->generate_text_box('for', '', array('id' => 'for')), 'for');
		$form_container->output_row($lang->admin_form_version, $lang->admin_add_version_desc, $form->generate_text_box('for_version', '', array('id' => 'for_version')), 'for_version');
		$form_container->output_row($lang->admin_form_compatibility, $lang->admin_add_compatibility_desc, $form->generate_text_box('for_compatibility', '', array('id' => 'for_compatibility')), 'for_compatibility');
		$form_container->output_row($lang->admin_form_link, $lang->admin_add_link_desc, $form->generate_file_upload_box("archive_lang", array('style' => 'width: 200px;')), 'archive_lang');
		$form_container->output_row($lang->admin_form_image, $lang->admin_add_image_desc, $form->generate_file_upload_box("archive_img", array('style' => 'width: 200px;')), 'archive_img');
        $form_container->output_row($lang->admin_form_copyset, $lang->admin_add_copyset_desc, $form->generate_text_box('copy', '', array('id' => 'copy')), 'copy', '', array('id' => 'rolang_copy_imageset'));
        $form_container->output_row($lang->admin_form_active, $lang->admin_add_active_desc, $form->generate_yes_no_radio('active', 0, true, array("id" => 'active-yes'), ""), 'active');
		$form_container->end();
        // se creaza butoanele formularului de adaugare
		$buttons = "";
		$buttons[] = $form->generate_submit_button($lang->admin_button_submit);
		$buttons[] = $form->generate_reset_button($lang->admin_button_reset);
		$form->output_submit_wrapper($buttons);
		$form->end();
        // in fine se afiseaza si subsolul paginii
        $page->output_footer();
	}
}
elseif ($mybb->input['action'] == 'edit')
{
    // pagina de editare a unei versiuni de traducere
	if ($mybb->request_method == "post")
	{
        // se verifica campul "aid"		
		$aid = intval($mybb->input['aid']);
		if ($aid <= 0 || (!($update = $db->fetch_array($db->simple_select('rolang_updates', '*', "aid = $aid")))))
		{
			flash_message($lang->admin_edit_error_id, 'error');
			admin_redirect("index.php?module=rolang-admin");
		}
        // se verifica versiunea si compatibilitatea
        if (!empty($mybb->input['for_version']) && !preg_match("/^(\d{4})$/", $mybb->input['for_version']))
		{
            flash_message($lang->admin_edit_error_version, 'error');
            admin_redirect("index.php?module=rolang-admin&amp;action=edit&amp;aid={$aid}");
		}
        if (!empty($mybb->input['for_compatibility']) && !preg_match("/^(\d{4})(,(\d{4}))*$/", $mybb->input['for_compatibility']))
		{
			flash_message($lang->admin_edit_error_compatibility, 'error');
			admin_redirect("index.php?module=rolang-admin&amp;action=edit&amp;aid={$aid}");
		}
        // se verifica valoarea campului "active"
		if (intval($mybb->input['active']) != 0 && intval($mybb->input['active']) != 1)
		{
			flash_message($lang->admin_upload_wrongactive, 'error');
			admin_redirect("index.php?module=rolang-admin&amp;action=edit&amp;aid={$aid}");
		}
        $update_array = array(
            "uid"               => $mybb->user['uid'],
            "active"            => intval($mybb->input['active']),
            "for"               => $db->escape_string($mybb->input['for']),
            "for_version"	    => intval($mybb->input['for_version']),
            "for_compatibility"	=> $db->escape_string($mybb->input['for_compatibility']),
            "archive_lang"      => base64_encode($mybb->settings['bburl']."/inc/plugins/rolang/uploads/".$db->escape_string($file1['filename'])),
            "archive_lang_size" => intval($file1['filesize']),
            "archive_img"       => base64_encode($mybb->settings['bburl']."/inc/plugins/rolang/uploads/".$db->escape_string($file2['filename'])),
            "archive_img_size"  => intval($file2['filesize']),
            "date"			    => TIME_NOW
        );
        // se verifica daca unele campuri sunt necompletate
        if (empty($mybb->input['for'])) {
            unset($update_array['for']);
        }
        if (empty($mybb->input['for_version'])) {
            unset($update_array['for_version']);
        }
        if (empty($mybb->input['for_compatibility'])) {
            unset($update_array['for_compatibility']);
        }
		// se incarca fisierele?
		$link = basename($_FILES['archive_lang']['name']);
		$image = basename($_FILES['archive_img']['name']);
		$file1 = array();
		if (!empty($link))
		{
            // se incarca un nou fisier
            // mai intai se sterge cel vechi
			@unlink(MYBB_ROOT."inc/plugins/rolang/uploads/".basename(base64_decode($update['archive_lang'])));
			// se urca atasamentul pe server
			$file1 = rolang_upload_attachment($_FILES['archive_lang']);
            // au aparut erori?
            if($file1['error']) {
				flash_message($lang->sprintf($lang->admin_upload_error, $_FILES['archive_lang']['name'], $file1['error']), 'error');
				admin_redirect("index.php?module=rolang-admin&amp;action=edit&amp;aid={$aid}");
			}
		}
		else {
            // se pastreaza vechea arhiva
			$file1['filename'] = basename(base64_decode($update['archive_lang']));
			$file1['filesize'] = intval($update['archive_lang_size']);
		}
		$file2 = array();
		if (!empty($image))
		{
            // fisier nou cu set de imagini
            // se sterge vechiul fisier
			@unlink(MYBB_ROOT."inc/plugins/rolang/uploads/".basename(base64_decode($update['archive_img'])));
			// se urca atasamentul pe server
			$file2 = rolang_upload_attachment($_FILES['archive_img']);
            // au aparut erori?
            if($file2['error']) {
				flash_message($lang->sprintf($lang->admin_upload_error, $_FILES['archive_img']['name'], $file2['error']), 'error');
				admin_redirect("index.php?module=rolang-admin&amp;action=edit&amp;aid={$aid}");
			}
		}
		else { 
            // se pastreaza vechea arhiva
			$file2['filename'] = basename(base64_decode($update['archive_img']));
			$file2['filesize'] = intval($update['archive_img_size']);
		}
		// daca totul e in regula se trece la realizarea update-ului
		$db->update_query("rolang_updates", $update_array, "aid = $aid");
		// totul s-a realizat cu succes
        if (function_exists("rolang_add_log")) {
            rolang_add_log("<font color=green>".$lang->infos_button_type_log."</font>", $lang->sprintf($lang->admin_log_edit_success, $aid), $mybb->user['uid']);
        }
		flash_message($lang->sprintf($lang->admin_edit_success, $aid), 'success');
		admin_redirect("index.php?module=rolang-admin&amp;action=edit&amp;aid={$aid}");
	}
	else {
        // pentru inceput este adaugat un breadcrumb
        $page->add_breadcrumb_item($lang->mod_name);
        $page->add_breadcrumb_item($lang->admin_title, 'index.php?module=rolang-admin');
        // se testeaza daca exista drepturi pentru a activa o versiune
        $groups = explode(',', $mybb->settings['rolang_server_add_groups']);
        if (in_array($mybb->user['uid'], $groups)) {
            $is_active = "true";
        }
        else {
            $is_active = "false";
        }
        // se afiseaza antetul paginii, adaugandu-se mai intai un extra cod in antet	
        $page->extra_header .= '
        <script type="text/javascript" src="../jscripts/scriptaculous.js?load=effects"></script>
        <style type="text/css">
        a.rolang_get_archive {
            position: relative;
            z-index: 24; 
            text-decoration: none
        }
        a.rolang_get_archive {
            z-index:25; 
        }
        a.rolang_get_archive span {
            display: none
        }
        a.rolang_get_archive:hover span { 
            display: block;
            position: absolute;
            top:2em; left:2em; width:35em;
            border: 1px solid #463E41;
            background-color: #AFC7C7; 
            color: #566D7E;
            text-align: center
        }
        </style>
        <script type="text/javascript">
            Event.observe(document, "dom:loaded", function() {
                $$(\'.rolang_copy_toogle\').invoke(\'observe\', \'click\', function(e) {
                    if($(this.rel)) {
                        Effect.toggle(this.rel, \'appear\', { duration: 1.0 });
                    }
                    Event.stop(e);
                });
                // este activ butonul de activare ?
                $(\'active-yes\').disabled = '.$is_active.';
                // in mod implicit randul de copiere a setului de imagini nu se va afisa pe ecran
                $(\'rolang_copy_imageset\').hide();
            });	   
        </script>';	
        $page->output_header($lang->admin_title);
        // se afiseaza meniul orizontal
        $page->output_nav_tabs($sub_tabs, 'edit');
		// exista id-ul versiunii in baza de date ?	
        $aid = intval($mybb->input['aid']);
        // se verifica daca exista versiunea in baza de date si se intoarce randul ales
		if ($aid <= 0 || !($update = $db->fetch_array($db->simple_select("rolang_updates", "*", "aid = '$aid'", array("limit" => 1))))) {
			flash_message($lang->admin_edit_error_id, 'error');
			admin_redirect("index.php?module=rolang-admin");
		}
		// se creaza formularul
		$form = new Form("index.php?module=rolang-admin&amp;action=edit&amp;aid=".$aid, "post", "admin", 1);
		// se creaza afisajul pe ecran
		$form_container = new FormContainer($lang->admin_edit_title);
		$form_container->output_row($lang->admin_form_name, $lang->admin_edit_name_desc, $form->generate_text_box('for', htmlspecialchars_uni($update['for']), array('id' => 'for')), 'for');
		$form_container->output_row($lang->admin_form_version, $lang->admin_edit_version_desc, $form->generate_text_box('for_version', intval($update['for_version']), array('id' => 'for_version')), 'for_version');
        $form_container->output_row($lang->admin_form_compatibility, $lang->admin_edit_compatibility_desc, $form->generate_text_box('for_compatibility', htmlspecialchars_uni($update['for_compatibility']), array('id' => 'for_compatibility')), 'for_compatibility');
		$form_container->output_row($lang->admin_form_link, $lang->admin_edit_link_desc, $form->generate_file_upload_box("archive_lang", array('style' => 'width: 200px;'))."&nbsp;&nbsp;&nbsp;".$lang->sprintf($lang->admin_edit_old_archive, base64_decode($update['archive_lang']), basename(base64_decode($update['archive_lang']))), 'archive_lang');
		$form_container->output_row($lang->admin_form_image, $lang->admin_edit_image_desc, $form->generate_file_upload_box("archive_img", array('style' => 'width: 200px;'))."&nbsp;&nbsp;&nbsp;".$lang->sprintf($lang->admin_edit_old_archive, base64_decode($update['archive_img']), basename(base64_decode($update['archive_img']))), 'archive_img');
        $form_container->output_row($lang->admin_form_copyset, $lang->admin_add_copyset_desc, $form->generate_text_box('copy', '', array('id' => 'copy')), 'copy', '', array('id' => 'rolang_copy_imageset'));
        $form_container->output_row($lang->admin_form_active, $lang->admin_add_active_desc, $form->generate_yes_no_radio('active', intval($update['active']), true, array("id" => 'active-yes'), ""), 'active');
		$form_container->end();
		$buttons = "";
		$buttons[] = $form->generate_submit_button($lang->admin_button_submit);
		$buttons[] = $form->generate_reset_button($lang->admin_button_reset);
		$form->output_submit_wrapper($buttons);
		$form->end();
        // se afiseaza subsolul paginii cu formularul de editare
        $page->output_footer();
	}
}
elseif ($mybb->input['action'] == 'archive')
{
    // se adauga o arhiva in sistem
	if ($mybb->request_method == "post")
	{
        // se verifica ca nu cumva sa fie campuri necompletate
        if (empty($mybb->input['archive_name']) || !preg_match("/^([_0-9a-zA-Z]{4,32})$/", $mybb->input['archive_name']))
		{
            flash_message($lang->admin_archive_process_name, 'error');
            admin_redirect("index.php?module=rolang-admin&amp;action=archive");
		}
        if (empty($mybb->input['archive_files']) || !preg_match("/^([_\.\/0-9a-zA-Z]{1,})(,([_\.\/0-9a-zA-Z]{1,}))*$/", $mybb->input['archive_files']))
		{
			flash_message($lang->admin_archive_process_files, 'error');
			admin_redirect("index.php?module=rolang-admin&amp;action=archive");
		}
        // se verifica valoarea campului "active"
		if (intval($mybb->input['archive_overwrite']) != 0 && intval($mybb->input['archive_overwrite']) != 1)
		{
			flash_message($lang->admin_archive_process_over, 'error');
			admin_redirect("index.php?module=rolang-admin&amp;action=archive");
		}
        // optiunile implicite
        $from = "UTF-8";
        $to = "HTML-ENTITIES";
        if (intval($mybb->input['archive_replace']) == 1) 
        {
            // se aplica un preg_match
            preg_match_all("/^([\-0-9a-zA-Z]{1,}):([\-0-9a-zA-Z]{1,})$/", $mybb->input['archive_data'], $matches, PREG_SET_ORDER);
            // valorile sunt setate
            if (isset($matches[0][1])) {
                $from = $matches[0][1];    
            }
            if (isset($matches[0][2])) {
                $to = $matches[0][2];
            }
        }
        // se creaza vectorul de fisiere si / sau directoare
        $files = explode(",", $mybb->input['archive_files']);
        // se creaza destinatia
        $destination = MYBB_ROOT."inc/plugins/rolang/uploads/".$mybb->input['archive_name'].".zip";
        // este suprascrisa?
        $overwrite = intval($mybb->input['archive_overwrite']);
        // se schimba directorul de lucru
        $path_current = getcwd();
        $path_new = $mybb->input['archive_path'];
        chdir($path_new);
        if (class_exists("ZipArchive") && function_exists("rolang_create_zip") && rolang_create_zip($files, $destination, $overwrite, intval($mybb->input['archive_replace']), $from, $to)) {
            // totul s-a realizat cu succes
            if (function_exists("rolang_add_log")) {
                rolang_add_log("<font color=green>".$lang->infos_button_type_log."</font>", $lang->sprintf($lang->admin_log_create_successs, $mybb->input['archive_name']), $mybb->user['uid']);
            }
            flash_message($lang->admin_archive_process_success, 'success');
        }
        else {
            // a aparut o eroare
            flash_message($lang->admin_archive_process_error, 'error');
        }
        // se revine la directorul initial
        chdir($path_current);
        // se face redirectionarea
		admin_redirect("index.php?module=rolang-admin&amp;action=archive");
	}
	else {
        // pentru inceput este adaugat un breadcrumb
        $page->add_breadcrumb_item($lang->mod_name);
        $page->add_breadcrumb_item($lang->admin_title, 'index.php?module=rolang-admin');
        // se afiseaza antetul paginii, adaugandu-se mai intai un extra cod in antet	
        $page->extra_header .= '
        <style type="text/css">
        a.rolang_form_switch {
            display: block; height: 16px; width: 16px; text-indent: -999px; text-decoration: none; overflow: hidden; padding: 0px; margin: 0px;
            background: transparent url(../images/rolang/change.png);
            float: right;
        }
        </style>
       	<script type="text/javascript" src="../jscripts/scriptaculous.js?load=effects"></script>
        <script type="text/javascript">
            Event.observe(document, "dom:loaded", function() {
                $(\'archive_path\').observe(\'keyup\', function() {
                    var pageLocation = window.location.href;
                    var newLocation = $(\'archive_path\').getValue();
                    var locationArray = pageLocation.split("/");
                    var newArray = newLocation.split("/");
                    // se sterge prima portiune din cod si a doua
                    locationArray.shift();
                    locationArray.shift();
                    locationArray[0] = "MYBB_ROOT";
                    // se sterge ultima portiune din string
                    locationArray.pop();
                    // pentru fiecare valoare din newArray se efectueaza prelucrari
                    var error = 0;
                    var count = newArray.length;
                    for (i = 0; i < count; i++) {
                        if (newArray[i] == "..") {
                            // ne ducem cu un director inapoi
                            // se scoate ultima valoare din vector doar daca exista cel putin 2 valori
                            if (locationArray.length > 1) {
                                locationArray.pop();
                            } 
                            else {
                                error = 1;
                            }
                        }
                        else if (newArray[i] != ".") {
                            // se adauga calea
                            locationArray.push(newArray[i]);
                        }
                    }
                    if (error == 1) {
                        $(\'current_path\').setStyle({ color: \'#CC0000\' });
                        $(\'submit_button\').hide();
                    }
                    else {
                        $(\'current_path\').setStyle({ color: \'#009900\' });
                        $(\'submit_button\').show();
                    }
                    $(\'current_path\').update(locationArray.join(\'/\'));
                }); 
                // se face o verificare amanuntita
                $(\'archive_path\').observe(\'change\', function() {
                    var path = $(\'current_path\');
                    if (path.getStyle(\'color\') != "#CC0000") {
                        // se realizeaza o cerere Ajax
                        new Ajax.Request("index.php?module=rolang-admin", {
                            parameters: { ajax: 1, action: "check_path", path : path.innerHTML },
                            onComplete: function(data) {
                                if (data.responseText == "1") {
                                    path.setStyle({ color: \'#006600\' });
                                    $(\'submit_button\').show();
                                }
                                else {
                                    path.setStyle({ color: \'#CC0000\' });
                                    $(\'submit_button\').hide();
                                }
                            }
                        });              
                    }
                });
                // se verifica numele arhivei
                $(\'archive_name\').observe(\'change\', function() {
                    var value = $(\'archive_name\').getValue();
                    if (value.match(/^([_0-9a-zA-Z]{4,32})$/)) {
                        $(\'archive_name\').setStyle({ background: \'#BCF5A9\' });
                    }
                    else {
                        $(\'archive_name\').setStyle({ background: \'#F5A9BC\' });
                    }
                });
                // se verifica lista de fisiere pentru un format corect
                $(\'archive_files\').observe(\'change\', function() {
                    var value = $(\'archive_files\').getValue();
                    if (value.match(/^([_\.\/0-9a-zA-Z]{1,})(,([_\.\/0-9a-zA-Z]{1,}))*$/)) {
                        $(\'archive_files\').setStyle({ background: \'#BCF5A9\' });
                    }
                    else {
                        $(\'archive_files\').setStyle({ background: \'#F5A9BC\' });
                    }
                });
                // afiseaza / ascunde randul special din tabel
	            if($(\'archive_replace\'))
                {
                    Event.observe(\'archive_replace\', \'click\', function(e){
                    if(this.checked) {
                        $(\'archive_replace_data\').appear();   
                    }
                    else {
                        $(\'archive_replace_data\').fade();
                    }
                    });
                }
                // se afiseaza / ascunde formularul de creare a unei arhive
                $$(\'.rolang_form_switch\').invoke(\'observe\', \'click\', function(e) {
                    if($(this.rel)) {
                        Effect.toggle(this.rel, \'Slide\', { duration: 2.0 });
                    }
                    Event.stop(e);
                });
                $(\'archive_replace_data\').hide();
                $(\'rolang_form_div\').hide(); 
            });	   
        </script>';	
        // se adauga antetul paginii
        $page->output_header($lang->admin_title);
        // se afiseaza meniul orizontal
        $page->output_nav_tabs($sub_tabs, 'archive');
        // se realizeaza paginarea in vederea afisarii tabelului cu toate arhivele de pe server
        $per_page = 10; // in mod implicit
        if($mybb->input['page'] && intval($mybb->input['page']) > 1) {
            $mybb->input['page'] = intval($mybb->input['page']);
            $start = ($mybb->input['page'] * $per_page) - $per_page;
        }
        else {
            $mybb->input['page'] = 1;
            $start = 0;
        }
        // acum paginarea este in regula, se trece la obtinerea datelor din tabel
        $archives = rolang_get_archives(MYBB_ROOT."inc/plugins/rolang/uploads");
        // variabila ce retine numarul de randuri obtinute din interogare
        $total_rows = count($archives);
        // se construieste tabelul se urmeaza sa fie afisat
        $table = new Table;
        $table->construct_header($lang->admin_archive_table_name, array('width' => '30%'));
        $table->construct_header($lang->admin_archive_table_cdate, array('width' => '20%'));
        $table->construct_header($lang->admin_archive_table_mdate, array('width' => '20%'));
        $table->construct_header($lang->admin_archive_table_size, array('width' => '15%'));
        $table->construct_header($lang->admin_table_actions, array('width' => '15%', 'class' => 'align_center'));
        // cand se va opri structura repetitiva
        $end = $start + $per_page;
        if ($total_rows < $end) {
            $end = $total_rows;
        }
        // se creaza tabelul rand cu rand
        for ($i = $start; $i < $end; $i++)
        {
            // se intorc informatii despre arhiva
            $info = stat(MYBB_ROOT."inc/plugins/rolang/uploads/".$archives[$i]);
            $name = basename($archives[$i]);
            $table->construct_cell($name, array('class' => 'align_center'));
            $table->construct_cell(my_date($mybb->settings['dateformat'], $info['ctime'], '', false).", ".my_date($mybb->settings['timeformat'], $info['ctime']), array('class' => 'align_center'));
            $table->construct_cell(my_date($mybb->settings['dateformat'], $info['mtime'], '', false).", ".my_date($mybb->settings['timeformat'], $info['mtime']), array('class' => 'align_center'));
            $table->construct_cell(rolang_get_size($info['size']), array('class' => 'align_center'));
            $popup = new PopupMenu("archive_{$i}", $lang->mod_table_options);
            $popup->add_item($lang->admin_archive_table_options_used, "index.php?module=rolang-admin&amp;action=is_used&amp;file=".$db->escape_string($name));
            $popup->add_item($lang->admin_archive_table_options_delete, "index.php?module=rolang-admin&amp;action=delete_archive&amp;file=".$db->escape_string($name));
            $table->construct_cell($popup->fetch(), array('style' => "text-align:center"));
            $table->construct_row();
        }
        // in cazul in care nu a existat niciun rand intors, atunci se afiseaza un mesaj central
        if($table->num_rows() == 0) {
            $table->construct_cell($lang->admin_archive_table_without, array('class' => 'align_center', 'colspan' => 5));
            $table->construct_row();
        }
        // in final se afiseaza tabelul pe ecranul utilizatorului
        $table->output($lang->admin_archive_table_title."<a href=\"#\" class=\"rolang_form_switch\" rel=\"rolang_form_div\">Test</a>");
        // se realizeaza paginarea
        echo draw_admin_pagination($mybb->input['page'], $per_page, $total_rows, "index.php?module=rolang-admin&amp;action=archive&amp;page={page}");
	    echo '<div id="rolang_form_div" class="rolang_form_div">';
		// se creaza formularul
		$form = new Form("index.php?module=rolang-admin&amp;action=archive", "post", "admin", 1);
		// se creaza afisajul pe ecran
		$form_container = new FormContainer($lang->admin_archive_add_title);
		$form_container->output_row($lang->admin_archive_add_name, $lang->admin_archive_add_name_desc, $form->generate_text_box('archive_name', '', array('id' => 'archive_name'))."<img id=\"img_name\" src=\"\"/>", 'archive_name');
        $form_container->output_row($lang->admin_archive_add_path, $lang->admin_archive_add_path_desc, $form->generate_text_box('archive_path', '', array('id' => 'archive_path'))."&nbsp;&nbsp;&nbsp;".$lang->admin_archive_add_path_location."<span style=\"color: rgb(0, 102, 0);\" id=\"current_path\">MYBB_ROOT/admin/</span>", 'archive_path');
        $form_container->output_row($lang->admin_archive_add_files, $lang->admin_archive_add_files_desc, $form->generate_text_box('archive_files', '', array('id' => 'archive_files')), 'archive_files');
		$form_container->output_row($lang->admin_archive_add_replace, $lang->admin_archive_add_replace_desc, $form->generate_check_box('archive_replace', 1, $lang->admin_archive_add_replace_label, array('id' => 'archive_replace', 'checked' => false)), 'archive_replace');
        $form_container->output_row($lang->admin_archive_add_data, $lang->admin_archive_add_data_desc, $form->generate_text_box('archive_data', '', array('id' => 'archive_data')), 'archive_data', '', array('id' => 'archive_replace_data'));
		$form_container->output_row($lang->admin_archive_add_overwrite, $lang->admin_archive_add_overwrite_desc, $form->generate_check_box('archive_overwrite', 1, $lang->admin_archive_add_overwrite_label, array('id' => 'archive_overwrite', 'checked' => true)), 'archive_overwrite');
		$form_container->end();
		$buttons = "";
		$buttons[] = $form->generate_submit_button($lang->admin_button_submit, array('id' => 'submit_button'));
		$buttons[] = $form->generate_reset_button($lang->admin_button_reset);
		$form->output_submit_wrapper($buttons);
		$form->end();
        // se afiseaza si o legenda
        echo '<fieldset>
        <legend>'.$lang->mod_legend.'</legend>
        <font color="#CC0000">'.$lang->admin_legend_message.'</font> &bull; '.$lang->admin_archive_legend_m1.'<br />
        <font color="#009900">'.$lang->admin_legend_message.'</font> &bull; '.$lang->admin_archive_legend_m2.'<br />
        <font color="#006600">'.$lang->admin_legend_message.'</font> &bull; '.$lang->admin_archive_legend_m3.'
        </fieldset>';
        echo '</div>';
        // se afiseaza subsolul paginii cu formularul de editare
        $page->output_footer();
	}
}
elseif ($mybb->input['action'] == 'is_used')
{
    if($mybb->input['no']) {
        // userul nu a mai confirmat
        admin_redirect("index.php?module=rolang-admin&amp;action=archive");
    }
    // se verifica cererea
    if($mybb->request_method == "post")
    {
        // daca codul cererii nu e corect atunci se afiseaza o eroare pe ecran
        if(!isset($mybb->input['my_post_key']) || $mybb->post_code != $mybb->input['my_post_key']) {
            $mybb->request_method = "get";
            flash_message($lang->admin_table_options_error, 'error');
            admin_redirect("index.php?module=rolang-admin&amp;action=archive");
        }
        $search = base64_encode($mybb->settings['bburl']."/inc/plugins/rolang/uploads/".$db->escape_string($mybb->input['file']));
        // este utilizata arhiva de o versiune?
        if (!($archive = $db->fetch_array($db->simple_select('rolang_updates', 'aid', "archive_lang = '".$search."' OR archive_img = '".$search."'", array('limit' => 1))))) {
            flash_message($lang->admin_archive_used_no, 'success');
        }
        else {				
            // este utilizata de o versiune de traducere
            flash_message($lang->sprintf($lang->admin_archive_used_yes, $archive['aid']), 'success');
        }
        admin_redirect('index.php?module=rolang-admin&amp;action=archive');
    }
    else {
        // pagina de confirmare
        $page->add_breadcrumb_item($lang->mod_name);
        $page->add_breadcrumb_item($lang->admin_title, 'index.php?module=rolang-admin');
        // se afiseaza antetul paginii	
        $page->output_header($lang->admin_title);
        // se converteste inputul la intreg
        $mybb->input['file'] = $db->escape_string($mybb->input['file']);
        $form = new Form("index.php?module=rolang-admin&amp;action=is_used&amp;file={$mybb->input['file']}&amp;my_post_key={$mybb->post_code}", 'post');
        echo "<div class=\"confirm_action\">\n";
        echo "<p>".$lang->sprintf($lang->admin_archive_used_confirm, $mybb->input['file'])."</p>\n";
        echo "<br />\n";
        echo "<p class=\"buttons\">\n";
        echo $form->generate_submit_button($lang->yes, array('class' => 'button_yes'));
        echo $form->generate_submit_button($lang->no, array("name" => "no", 'class' => 'button_no'));
        echo "</p>\n";
        echo "</div>\n";
        $form->end();
        // in fine se afiseaza si subsolul paginii
        $page->output_footer();
    }
}
elseif ($mybb->input['action'] == 'delete_archive')
{
    if($mybb->input['no']) {
        // userul nu a mai confirmat
        admin_redirect("index.php?module=rolang-admin&amp;action=archive");
    }
    // se verifica cererea
    if($mybb->request_method == "post")
    {
        // daca codul cererii nu e corect atunci se afiseaza o eroare pe ecran
        if(!isset($mybb->input['my_post_key']) || $mybb->post_code != $mybb->input['my_post_key']) {
            $mybb->request_method = "get";
            flash_message($lang->admin_table_options_error, 'error');
            admin_redirect("index.php?module=rolang-admin&amp;action=archive");
        }
        $search = base64_encode($mybb->settings['bburl']."/inc/plugins/rolang/uploads/".$db->escape_string($mybb->input['file']));
        // este utilizata arhiva de o versiune?
        if ($db->num_rows($db->simple_select('rolang_updates', 'aid', "archive_lang = '".$search."' OR archive_img = '".$search."'", array('limit' => 1))) > 0) {
            flash_message($lang->admin_archive_delete_error, 'error');
            admin_redirect('index.php?module=rolang-admin&amp;action=archive');
        }
        else {				
            // se sterge fisierul de pe disc
            if (file_exists(MYBB_ROOT."inc/plugins/rolang/uploads/".$mybb->input['file']) && unlink(MYBB_ROOT."inc/plugins/rolang/uploads/".$mybb->input['file'])) {
                if (function_exists("rolang_add_log")) {
                    rolang_add_log("<font color=green>".$lang->infos_button_type_log."</font>", $lang->sprintf($lang->admin_log_delete_success, $mybb->input['file']), $mybb->user['uid']);
                }
            }
            // se afiseaza pe ecran un mesaj precum totul s-a realizat cu succes
            flash_message($lang->sprintf($lang->admin_archive_delete_success, $mybb->input['file']), 'success');
            admin_redirect('index.php?module=rolang-admin&amp;action=archive');
        }
    }
    else {
        // pagina de confirmare
        $page->add_breadcrumb_item($lang->mod_name);
        $page->add_breadcrumb_item($lang->admin_title, 'index.php?module=rolang-admin');
        // se afiseaza antetul paginii	
        $page->output_header($lang->admin_title);
        // se converteste inputul la intreg
        $mybb->input['file'] = $db->escape_string($mybb->input['file']);
        $form = new Form("index.php?module=rolang-admin&amp;action=delete_archive&amp;file={$mybb->input['file']}&amp;my_post_key={$mybb->post_code}", 'post');
        echo "<div class=\"confirm_action\">\n";
        echo "<p>".$lang->sprintf($lang->admin_archive_delete_confirm, $mybb->input['file'])."</p>\n";
        echo "<br />\n";
        echo "<p class=\"buttons\">\n";
        echo $form->generate_submit_button($lang->yes, array('class' => 'button_yes'));
        echo $form->generate_submit_button($lang->no, array("name" => "no", 'class' => 'button_no'));
        echo "</p>\n";
        echo "</div>\n";
        $form->end();
        // in fine se afiseaza si subsolul paginii
        $page->output_footer();
    }
}

// Functia care intoarce campurile posibile la stergerea unor log-uri
function rolang_get_fields()
{
    global $lang;
    $fields = array(
        'aid' => array(
            'title' => $lang->admin_fields_aid,
            'type' => 'int'
        ),
		'uid' => array(
			'title' => $lang->admin_fields_userid,
			'type' => 'int'
		),
		'active' => array(
			'title' => $lang->admin_fields_active,
			'type' => 'int'
		),
		'for' => array(
			'title' => $lang->admin_fields_name,
			'type' => 'string'
		),
		'for_version' => array(
			'title' => $lang->admin_fields_version,
			'type' => 'int'
		),
		'for_compatibility' => array(
			'title' => $lang->admin_fields_compatibility,
			'type' => 'string'
		),
		'archive_lang_size' => array(
			'title' => $lang->admin_fields_size_link,
			'type' => 'int'
		),
		'archive_img_size' => array(
			'title' => $lang->admin_fields_size_image,
			'type' => 'int'
		),
		'date' => array(
			'title' => $lang->admin_fields_size_date,
			'type' => 'date'
		)
	);
    // se intorc rezultatele functiei	
	return $fields;
}

// Functia care intoarce testele posibile la stergerea unor log-uri
function rolang_get_tests()
{
    global $lang;
    return array(
        ''          => "",
		'eq'        => "==",
		'neq'       => "!=",
		'null'      => $lang->admin_tests_null,
		'notnull'   => $lang->admin_tests_null_no,
		'empty'     => $lang->admin_tests_empty,
		'notempty'  => $lang->admin_tests_empty_no,
		'gt'        => ">",
		'lt'        => "<",
		'gte'       => "=>",
		'lte'       => "<=",
		'in'        => $lang->admin_tests_in,
		'nin'       => $lang->admin_tests_in_no,
		'like'      => $lang->admin_tests_like,
		'nlike'     => $lang->admin_tests_like_no
	);
}

// Functia prin care se incarca pe server un fisier
function rolang_upload_attachment($attachment)
{
	global $db, $theme, $templates, $pid, $tid, $forum, $mybb, $lang, $plugins, $cache;
	// se incarca fisierul de limba
	$lang->load('messages');
    // exista eorri posibile
	if(isset($attachment['error']) && $attachment['error'] != 0)
	{
		$ret['error'] = $lang->error_uploadfailed.$lang->error_uploadfailed_detail;
		switch($attachment['error'])
		{
			case 1: // UPLOAD_ERR_INI_SIZE
				$ret['error'] .= $lang->error_uploadfailed_php1;
				break;
			case 2: // UPLOAD_ERR_FORM_SIZE
				$ret['error'] .= $lang->error_uploadfailed_php2;
				break;
			case 3: // UPLOAD_ERR_PARTIAL
				$ret['error'] .= $lang->error_uploadfailed_php3;
				break;
			case 4: // UPLOAD_ERR_NO_FILE
				$ret['error'] .= $lang->error_uploadfailed_php4;
				break;
			case 6: // UPLOAD_ERR_NO_TMP_DIR
				$ret['error'] .= $lang->error_uploadfailed_php6;
				break;
			case 7: // UPLOAD_ERR_CANT_WRITE
				$ret['error'] .= $lang->error_uploadfailed_php7;
				break;
			default:
				$ret['error'] .= $lang->sprintf($lang->error_uploadfailed_phpx, $attachment['error']);
				break;
		}
		return $ret;
	}
	// este incarcat fisierul
	if(!is_uploaded_file($attachment['tmp_name']) || empty($attachment['tmp_name']))
	{
		$ret['error'] = $lang->error_uploadfailed.$lang->error_uploadfailed_php4;
		return $ret;
	}
	$ext = get_extension($attachment['name']);
	// se verifica daca extensia este permisa
	$query = $db->simple_select("attachtypes", "*", "extension = '".$db->escape_string($ext)."'");
	$attachtype = $db->fetch_array($query);
	if(!$attachtype['atid'])
	{
		$ret['error'] = $lang->error_attachtype;
		return $ret;
	}
	//se verifica marimea
	if($attachment['size'] > $attachtype['maxsize'] * 1024 && $attachtype['maxsize'] != "")
	{
		$ret['error'] = $lang->sprintf($lang->error_attachsize, $attachtype['maxsize']);
		return $ret;
	}
	// daca totul este in regula se muta fisierul
	$filename = "download_".$mybb->user['uid']."_".TIME_NOW."_".md5(uniqid(rand(), true)).".".$ext;
	// se incarca fisierul pe server
	require_once MYBB_ROOT.'inc/functions_upload.php';
	$file = upload_file($attachment, MYBB_ROOT."inc/plugins/rolang/uploads/", $filename);
	// au aparut erori?
	if($file['error'])
	{
		$ret['error'] = $lang->error_uploadfailed.$lang->error_uploadfailed_detail;
		switch(intval($file['error']))
		{
			case 1:
				$ret['error'] .= $lang->error_uploadfailed_nothingtomove;
				break;
			case 2:
				$ret['error'] .= $lang->error_uploadfailed_movefailed;
				break;
		}
		return $ret;
	}
	// exista fisierul incarcat pe server?
	if(!file_exists(MYBB_ROOT."inc/plugins/rolang/uploads/".$filename))
	{
		$ret['error'] = $lang->error_uploadfailed.$lang->error_uploadfailed_detail.$lang->error_uploadfailed_lost;
		return $ret;
	}
    // se genereaza vectorul cu fisierul incarcat
	$downloadarray = array(
		"filename"    => $filename,
		"filesize"    => intval($file['size']),
	);
    // se returneaza vectorul nou creat	
	return $downloadarray;
}

// Se reazeaza conversia unui text
function rolang_convert_entities($text) 
{
    return preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]+);/', 'rolang_convert_entity', $text);
}

// Functia de mai jos converteste toate entitatile HTML ce prezinta nume in entitati numerice
function rolang_convert_entity($matches) 
{
    static $table = array('quot' => '&#34;',
                        'amp' => '&#38;',
                        'lt' => '&#60;',
                        'gt' => '&#62;',
                        'OElig' => '&#338;',
                        'oelig' => '&#339;',
                        'Scaron' => '&#352;',
                        'scaron' => '&#353;',
                        'Yuml' => '&#376;',
                        'circ' => '&#710;',
                        'tilde' => '&#732;',
                        'ensp' => '&#8194;',
                        'emsp' => '&#8195;',
                        'thinsp' => '&#8201;',
                        'zwnj' => '&#8204;',
                        'zwj' => '&#8205;',
                        'lrm' => '&#8206;',
                        'rlm' => '&#8207;',
                        'ndash' => '&#8211;',
                        'mdash' => '&#8212;',
                        'lsquo' => '&#8216;',
                        'rsquo' => '&#8217;',
                        'sbquo' => '&#8218;',
                        'ldquo' => '&#8220;',
                        'rdquo' => '&#8221;',
                        'bdquo' => '&#8222;',
                        'dagger' => '&#8224;',
                        'Dagger' => '&#8225;',
                        'permil' => '&#8240;',
                        'lsaquo' => '&#8249;',
                        'rsaquo' => '&#8250;',
                        'euro' => '&#8364;',
                        'fnof' => '&#402;',
                        'Alpha' => '&#913;',
                        'Beta' => '&#914;',
                        'Gamma' => '&#915;',
                        'Delta' => '&#916;',
                        'Epsilon' => '&#917;',
                        'Zeta' => '&#918;',
                        'Eta' => '&#919;',
                        'Theta' => '&#920;',
                        'Iota' => '&#921;',
                        'Kappa' => '&#922;',
                        'Lambda' => '&#923;',
                        'Mu' => '&#924;',
                        'Nu' => '&#925;',
                        'Xi' => '&#926;',
                        'Omicron' => '&#927;',
                        'Pi' => '&#928;',
                        'Rho' => '&#929;',
                        'Sigma' => '&#931;',
                        'Tau' => '&#932;',
                        'Upsilon' => '&#933;',
                        'Phi' => '&#934;',
                        'Chi' => '&#935;',
                        'Psi' => '&#936;',
                        'Omega' => '&#937;',
                        'alpha' => '&#945;',
                        'beta' => '&#946;',
                        'gamma' => '&#947;',
                        'delta' => '&#948;',
                        'epsilon' => '&#949;',
                        'zeta' => '&#950;',
                        'eta' => '&#951;',
                        'theta' => '&#952;',
                        'iota' => '&#953;',
                        'kappa' => '&#954;',
                        'lambda' => '&#955;',
                        'mu' => '&#956;',
                        'nu' => '&#957;',
                        'xi' => '&#958;',
                        'omicron' => '&#959;',
                        'pi' => '&#960;',
                        'rho' => '&#961;',
                        'sigmaf' => '&#962;',
                        'sigma' => '&#963;',
                        'tau' => '&#964;',
                        'upsilon' => '&#965;',
                        'phi' => '&#966;',
                        'chi' => '&#967;',
                        'psi' => '&#968;',
                        'omega' => '&#969;',
                        'thetasym' => '&#977;',
                        'upsih' => '&#978;',
                        'piv' => '&#982;',
                        'bull' => '&#8226;',
                        'hellip' => '&#8230;',
                        'prime' => '&#8242;',
                        'Prime' => '&#8243;',
                        'oline' => '&#8254;',
                        'frasl' => '&#8260;',
                        'weierp' => '&#8472;',
                        'image' => '&#8465;',
                        'real' => '&#8476;',
                        'trade' => '&#8482;',
                        'alefsym' => '&#8501;',
                        'larr' => '&#8592;',
                        'uarr' => '&#8593;',
                        'rarr' => '&#8594;',
                        'darr' => '&#8595;',
                        'harr' => '&#8596;',
                        'crarr' => '&#8629;',
                        'lArr' => '&#8656;',
                        'uArr' => '&#8657;',
                        'rArr' => '&#8658;',
                        'dArr' => '&#8659;',
                        'hArr' => '&#8660;',
                        'forall' => '&#8704;',
                        'part' => '&#8706;',
                        'exist' => '&#8707;',
                        'empty' => '&#8709;',
                        'nabla' => '&#8711;',
                        'isin' => '&#8712;',
                        'notin' => '&#8713;',
                        'ni' => '&#8715;',
                        'prod' => '&#8719;',
                        'sum' => '&#8721;',
                        'minus' => '&#8722;',
                        'lowast' => '&#8727;',
                        'radic' => '&#8730;',
                        'prop' => '&#8733;',
                        'infin' => '&#8734;',
                        'ang' => '&#8736;',
                        'and' => '&#8743;',
                        'or' => '&#8744;',
                        'cap' => '&#8745;',
                        'cup' => '&#8746;',
                        'int' => '&#8747;',
                        'there4' => '&#8756;',
                        'sim' => '&#8764;',
                        'cong' => '&#8773;',
                        'asymp' => '&#8776;',
                        'ne' => '&#8800;',
                        'equiv' => '&#8801;',
                        'le' => '&#8804;',
                        'ge' => '&#8805;',
                        'sub' => '&#8834;',
                        'sup' => '&#8835;',
                        'nsub' => '&#8836;',
                        'sube' => '&#8838;',
                        'supe' => '&#8839;',
                        'oplus' => '&#8853;',
                        'otimes' => '&#8855;',
                        'perp' => '&#8869;',
                        'sdot' => '&#8901;',
                        'lceil' => '&#8968;',
                        'rceil' => '&#8969;',
                        'lfloor' => '&#8970;',
                        'rfloor' => '&#8971;',
                        'lang' => '&#9001;',
                        'rang' => '&#9002;',
                        'loz' => '&#9674;',
                        'spades' => '&#9824;',
                        'clubs' => '&#9827;',
                        'hearts' => '&#9829;',
                        'diams' => '&#9830;',
                        'nbsp' => '&#160;',
                        'iexcl' => '&#161;',
                        'cent' => '&#162;',
                        'pound' => '&#163;',
                        'curren' => '&#164;',
                        'yen' => '&#165;',
                        'brvbar' => '&#166;',
                        'sect' => '&#167;',
                        'uml' => '&#168;',
                        'copy' => '&#169;',
                        'ordf' => '&#170;',
                        'laquo' => '&#171;',
                        'not' => '&#172;',
                        'shy' => '&#173;',
                        'reg' => '&#174;',
                        'macr' => '&#175;',
                        'deg' => '&#176;',
                        'plusmn' => '&#177;',
                        'sup2' => '&#178;',
                        'sup3' => '&#179;',
                        'acute' => '&#180;',
                        'micro' => '&#181;',
                        'para' => '&#182;',
                        'middot' => '&#183;',
                        'cedil' => '&#184;',
                        'sup1' => '&#185;',
                        'ordm' => '&#186;',
                        'raquo' => '&#187;',
                        'frac14' => '&#188;',
                        'frac12' => '&#189;',
                        'frac34' => '&#190;',
                        'iquest' => '&#191;',
                        'Agrave' => '&#192;',
                        'Aacute' => '&#193;',
                        'Acirc' => '&#194;',
                        'Atilde' => '&#195;',
                        'Auml' => '&#196;',
                        'Aring' => '&#197;',
                        'AElig' => '&#198;',
                        'Ccedil' => '&#199;',
                        'Egrave' => '&#200;',
                        'Eacute' => '&#201;',
                        'Ecirc' => '&#202;',
                        'Euml' => '&#203;',
                        'Igrave' => '&#204;',
                        'Iacute' => '&#205;',
                        'Icirc' => '&#206;',
                        'Iuml' => '&#207;',
                        'ETH' => '&#208;',
                        'Ntilde' => '&#209;',
                        'Ograve' => '&#210;',
                        'Oacute' => '&#211;',
                        'Ocirc' => '&#212;',
                        'Otilde' => '&#213;',
                        'Ouml' => '&#214;',
                        'times' => '&#215;',
                        'Oslash' => '&#216;',
                        'Ugrave' => '&#217;',
                        'Uacute' => '&#218;',
                        'Ucirc' => '&#219;',
                        'Uuml' => '&#220;',
                        'Yacute' => '&#221;',
                        'THORN' => '&#222;',
                        'szlig' => '&#223;',
                        'agrave' => '&#224;',
                        'aacute' => '&#225;',
                        'acirc' => '&#226;',
                        'atilde' => '&#227;',
                        'auml' => '&#228;',
                        'aring' => '&#229;',
                        'aelig' => '&#230;',
                        'ccedil' => '&#231;',
                        'egrave' => '&#232;',
                        'eacute' => '&#233;',
                        'ecirc' => '&#234;',
                        'euml' => '&#235;',
                        'igrave' => '&#236;',
                        'iacute' => '&#237;',
                        'icirc' => '&#238;',
                        'iuml' => '&#239;',
                        'eth' => '&#240;',
                        'ntilde' => '&#241;',
                        'ograve' => '&#242;',
                        'oacute' => '&#243;',
                        'ocirc' => '&#244;',
                        'otilde' => '&#245;',
                        'ouml' => '&#246;',
                        'divide' => '&#247;',
                        'oslash' => '&#248;',
                        'ugrave' => '&#249;',
                        'uacute' => '&#250;',
                        'ucirc' => '&#251;',
                        'uuml' => '&#252;',
                        'yacute' => '&#253;',
                        'thorn' => '&#254;',
                        'yuml' => '&#255;'

                        );
    // s-a gasit entitati ?
    return isset($table[$matches[1]]) ? $table[$matches[1]] : '';
}

// Functia urmatoare realizari prelucrari asupra unor fisiere din cadrul unor directoare
function rolang_search_replace($file, $content, $from, $to)
{
    // se realizeaza convertirea
    $content = mb_convert_encoding($content, $to, $from); 
    $content = rolang_convert_entities($content);
    // se poate scrie?
    if (is_writable($file)) {
        if (!$handle = fopen($file, 'w')) {
            return false;
        }
        // se scriu datele in fisier
        if (fwrite($handle, $content) === FALSE) {
            return false;
        }
        // se inchide fisierul de lucru
        fclose($handle);
        // se returneaza "true"
        return true;
    } 
    else {
        return false;
    }

}

// Functia creaza o arhiva zip utilizand clasa ZipArchive
function rolang_create_zip($files = array(), $destination = '', $overwrite = false, $replaced = false, $from, $to) 
{
    // daca arhiva exista si nu este setata o suprascriere apare eroare
    if(file_exists($destination) && !$overwrite) { 
        return false; 
    }
    // se creaza o clasa speciala
    class Zipper extends ZipArchive 
    {
        public function addDir($path, $replaced = false, $from, $to) 
        {
            $this->addEmptyDir($path);
            $nodes = glob($path . '/*');
            foreach ($nodes as $node) {
                if (is_dir($node)) {
                    $this->addDir($node);
                } 
                else if (is_file($node)) {
                    if ($replaced) {
                        // se realizeaza cateva inlocuiri doar fisierelor cu extensia "php"
                        if(substr_count($node, '.php')) {
                            $file_contents = file_get_contents($node);
                            // se apeleaza functia de inlocuire
                            rolang_search_replace($node, $file_contents, $from, $to);
                        }
                    }
                    $this->addFile($node);
                }
            }
        }
    }
    // variabile de lucru
    $valid_files = array();
    // este un vector?
    if(is_array($files)) {
        foreach($files as $file) {
            // se verifica daca fisierul exista pe server sau este director
            if(file_exists($file) || is_dir($file)) {
                $valid_files[] = $file;
            }
        }
    }
    // daca exista cel putin un fisier sau director de arhivat
    if(count($valid_files)) {
        // se creaza arhiva
        $zip = new Zipper();
        if($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
            return false;
        }
        // se adauga directoarele sau fisierele
        foreach($valid_files as $file) {
            if (is_file($file)) {
                if ($replaced) {
                    // se realizeaza cateva inlocuiri doar fisierelor cu extensia "php"
                    if(substr_count($file, '.php')) {
                        $file_contents = file_get_contents($file);
                        // se apeleaza functia de inlocuire
                        rolang_search_replace($file, $file_contents, $from, $to);
                    }
                }
                // se adauga fisierul
                $zip->addFile($file, $file);
            }
            else {
                // atunci e director cu siguranta
                $zip->addDir($file, $replaced, $from, $to);
            }
        }
        // dupa crearea arhivei se inchide
        $zip->close();
        // rezultatul intors depinde de faptul daca arhiva exista
        return file_exists($destination);
    }
    else {
        // daca vectorul este nul atunci se returneaza "false"
        return false;
    }
}

// Functia care intoarce toate arhivele din cadrul folderului "uploads"
function rolang_get_archives($directory) 
{
    // se creaza un vector de rezultate
    $results = array();
    // se creaza un handler pentru arhiva
    $handler = opendir($directory);
    // se deschide directorul si se cauta prin el
    while ($file = readdir($handler))
    {
        // teste
        if ($file != "." && $file != ".." && substr_count($file, '.zip')) {
            $results[] = $file;
        }
    }
    // se inchide directorul de lucru
    closedir($handler);
    // gata!
    return $results;
}

// Functia care construieste conditiile formularului de pe prima pagina
function rolang_build_condition_array()
{
    global $mybb;
    $array = array();
    $field_count = count($mybb->input['field']);
	for($i = 0; $i < $field_count; $i++)
	{
		if(!empty($mybb->input['field'][$i]))
		{
			$array[] = array(
				'field' => $mybb->input['field'][$i],
				'test'  => $mybb->input['test'][$i],
				'value' => $mybb->input['value'][$i]
			);
		}
	}
	return $array;
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
?>