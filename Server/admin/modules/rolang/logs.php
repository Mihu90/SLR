<?php
/*
    @author     : Surdeanu Mihai ;
    @date       : 28 septembrie 2011 ;
    @version    : 2.2 ;
    @mybb       : compatibilitate cu MyBB 1.6 (orice versiuni) ;
    @description: Modificare pentru Sistemul de Limba Romana ;
    @homepage   : http://mybb.ro ! Te rugam sa ne vizitezi pentru ca nu ai ce pierde!
    @copyright  : Licenta speciala. Pentru mai multe detalii te rugam sa citesti sectiunea Licenta din cadrul fisierului 
                ReadME.pdf care poate fi gasit in acest pachet. Iti multumim pentru intelegere!
    ====================================
    Ultima modificare a codului : 16.09.2011 20:55
*/

// Poate fi acesat direct fisierul?
if(!defined("IN_MYBB")) {
    	die("This file cannot be accessed directly.");
}

// Se verifica daca exista permisiuni
if (!$mybb->admin['permissions']['rolang']['logs'])
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
	"logs" => array(
		"title" => $lang->logs_title,
		"link" => "index.php?module=rolang-logs",
		"description" => $lang->logs_description
	)
);

if(!$mybb->input['action'])
{
	$page->add_breadcrumb_item($lang->mod_name);
	$page->add_breadcrumb_item($lang->logs_title);
    // se prelucreaza datele
    if($mybb->request_method == 'post')
	{
        verify_post_check($mybb->input['my_post_key']);
        // conditiile de lucru
        $conditions = rolang_build_condition_array();
        // ce se intampla daca nu e definita nicio conditie?
        if (count($conditions) == 0) {
            flash_message($lang->logs_prune_error_nocondition, 'error');
            // se face redirect
            admin_redirect("index.php?module=rolang-logs");  
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
        // se construieste interogarea si se ruleaza
        $where = implode(" AND ", $sql);
        $db->delete_query("rolang_logs", $where); 
        $rows = intval($db->affected_rows());
        if ($rows == 1) 
            flash_message($lang->logs_prune_succes_one, 'success');
        else 
            flash_message($lang->sprintf($lang->logs_prune_succes_more, $rows), 'success');
        // se face un redirect
		admin_redirect("index.php?module=rolang-logs");   
	}
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
    .rolang_field, .rolang_test, .rolang_value {
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
            $$(\'.rolang_close_help\').invoke(\'observe\', \'click\', function(e) {
                Effect.toggle(this.up(3), \'Blind\');
                Event.stop(e);
            });
            // in mod implicit casuta de ajutor nu este afisata pe ecran
            $$(\'.rolang_prune_help\').invoke(\'hide\');
        });	   
	</script>';
	$page->output_header($lang->logs_title);
    // se afiseaza meniul orizontal
	$page->output_nav_tabs($sub_tabs, 'logs');
    // se realizeaza paginarea in vederea afisarii tabelului
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
	$query = $db->simple_select("rolang_logs", "COUNT(lid) as logs");
    // variabila ce retine numarul de randuri obtinute din interogare
	$total_rows = $db->fetch_field($query, "logs");
    // se construieste tabelul se urmeaza sa fie afisat
	$table = new Table;
    $table->construct_header($lang->logs_table_username, array('width' => '15%'));
    $table->construct_header($lang->logs_table_type, array('width' => '10%'));
	$table->construct_header($lang->logs_table_data, array('width' => '50%'));
	$table->construct_header($lang->logs_table_date, array('width' => '15%', 'class' => 'align_center'));
	$table->construct_header($lang->logs_table_actions, array('width' => '10%', 'class' => 'align_center'));
	// se creaza interogarea
	$query = $db->query("
        SELECT u.*, l.*
	    FROM ".TABLE_PREFIX."rolang_logs l
        LEFT JOIN ".TABLE_PREFIX."users u ON (u.uid = l.uid)
        ORDER BY l.date DESC LIMIT {$start}, {$per_page}
	");
	// se creaza tabelul rand cu rand
	while ($log = $db->fetch_array($query))
    {
        $table->construct_cell(build_profile_link(htmlspecialchars_uni($log['username']), intval($log['uid'])), array('class' => 'align_center'));
        $table->construct_cell($db->escape_string($log['type']), array('class' => 'align_center'));
        $table->construct_cell($db->escape_string($log['data']));
        $table->construct_cell(my_date($mybb->settings['dateformat'], intval($log['date']), '', false).", ".my_date($mybb->settings['timeformat'], intval($log['date'])), array('class' => 'align_center'));
        $popup = new PopupMenu("log_{$log['lid']}", $lang->mod_table_options);
        $popup->add_item($lang->logs_table_options_delete, "index.php?module=rolang-logs&amp;action=delete&amp;lid=".intval($log['lid']));
        $table->construct_cell($popup->fetch(), array('class' => 'align_center'));
        $table->construct_row();
	}
	// in cazul in care nu a existat niciun rand intors din baza de date atunci se afiseaza un mesaj central
	if($table->num_rows() == 0) {
        $table->construct_cell($lang->logs_table_without, array('class' => 'align_center', 'colspan' => 5));
        $table->construct_row();
	}
	// in final se afiseaza tabelul pe ecranul utilizatorului
	$table->output($lang->logs_title);
    // se realizeaza paginarea
	echo draw_admin_pagination($mybb->input['page'], $per_page, $total_rows, "index.php?module=rolang-logs&amp;page={page}");
	echo '<div id="rolang_prune_help" class="rolang_prune_help">';
	$form_container = new FormContainer('<a name="help">'.$lang->logs_prune_help.'</a><span style="float:right">[<a href="#" class="rolang_close_help" >'.$lang->logs_prune_help_close.'</a>]</span>');
    $form_container->output_cell($lang->logs_prune_help_content);
	$form_container->construct_row();
	$form_container->end();
	echo '</div>';
    // formular prin care pot fi sterse o serie de log-uri
	$form = new Form("index.php?module=rolang-logs", "post", "logs");
	// se genereaza o cheie de tip post
	echo $form->generate_hidden_field("my_post_key", $mybb->post_code);
	// numele formularului	
	$form_container = new FormContainer($lang->logs_table_prune_title."<a href=\"#help\" class=\"rolang_get_help\" rel=\"rolang_prune_help\">&nbsp;</a>");
	$form_container->output_row_header($lang->logs_table_prune_field, array("style" => "width: 35%"));
	$form_container->output_row_header($lang->logs_table_prune_test, array("style" => "width: 35%"));
	$form_container->output_row_header($lang->logs_table_prune_value, array("style" => "width: 28%"));
	$form_container->output_row_header("&nbsp;", array("class" => "align_center", "style" => "width: 2%"));
	// se returneaza toate campurile posbilie
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
	// se afiseaza formularul pe ecran			
	$form_container->end();
    // butoanele din cadrul formularului		
	$buttons = array();
	$buttons[] = $form->generate_submit_button("Submit");
	$buttons[] = $form->generate_reset_button("Reset");
	$form->output_submit_wrapper($buttons);
	$form->end();
    // se afiseaza subsolul paginii
	$page->output_footer();
}
elseif ($mybb->input['action'] == 'delete')
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
            admin_redirect("index.php?module=rolang-logs");
        }
        // exista id-ul update-ului specificat in cerere in sistem?
        if (!($version = $db->fetch_array($db->simple_select('rolang_logs', 'type', 'lid = '.intval($mybb->input['lid']), array('limit' => 1))))) {
            flash_message($lang->logs_table_options_delete_invalid, 'error');
            admin_redirect('index.php?module=rolang-logs');
        }
        else {				
            // daca se ajunge pe aceasta ramura inseamna ca se poate sterge update-ul
            $db->delete_query('rolang_logs', 'lid = '.intval($mybb->input['lid']));
            // se afiseaza pe ecran un mesaj precum totul s-a realizat cu succes
            flash_message($lang->sprintf($lang->logs_table_options_delete_deleted, intval($mybb->input['lid'])), 'success');
            admin_redirect('index.php?module=rolang-logs');
        }
    }
    else {
        // pagina de confirmare
        $page->add_breadcrumb_item($lang->mod_name);
        $page->add_breadcrumb_item($lang->logs_title, 'index.php?module=rolang-logs');
        // se afiseaza antetul paginii	
        $page->output_header($lang->admin_title);
        // se converteste inputul la intreg
        $mybb->input['lid'] = intval($mybb->input['lid']);
        $form = new Form("index.php?module=rolang-logs&amp;action=delete&amp;lid={$mybb->input['lid']}&amp;my_post_key={$mybb->post_code}", 'post');
        echo "<div class=\"confirm_action\">\n";
        echo "<p>".$lang->sprintf($lang->logs_table_options_delete_confirm, $mybb->input['lid'])."</p>\n";
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

// Functia care construieste conditiile de stergere a log-urilor
function rolang_build_condition_array()
{
    global $mybb;
    $array = array();
    $field_count = count($mybb->input['field']);
	for($i=0; $i < $field_count; $i++)
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

// Functia care intoarce campurile posibile la stergerea unor log-uri
function rolang_get_fields()
{
    $fields = array(
		'uid' => array(
			'title' => "ID utilizator",
			'type' => 'int'
		),
		'type' => array(
			'title' => "Tip jurnal",
			'type' => 'string'
		),
		'data' => array(
			'title' => "Mesaj jurnal",
			'type' => 'string'
		),
		'date' => array(
			'title' => "Data",
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
?>