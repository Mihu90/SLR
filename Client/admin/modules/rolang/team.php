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
    =============
    Motto : A new generation of plugins...
*/

    // Poate fi acesat direct fisierul?
    if(!defined("IN_MYBB")) {
    	die("This file cannot be accessed directly.");
    }

    // Se verifica daca exista permisiuni
    if (!$mybb->admin['permissions']['rolang']['team'])
    {
        $page->output_header($lang->access_denied);
        $page->add_breadcrumb_item($lang->access_denied, "index.php?module=home-index");
        $page->output_error("<b>{$lang->access_denied}</b><ul><li style=\"list-style-type: none;\">{$lang->access_denied_desc}</li></ul>");
        $page->output_footer();
        exit;
    }
    
    // Se include fisierul de limba
    rolang_include_lang("global");

    // Meniu
    $sub_tabs = array(
        "team" => array(
            "title" => $lang->team_title,
            "link" => "index.php?module=rolang-team",
            "description" => $lang->team_description
            )
    );
    $page->add_breadcrumb_item($lang->mod_name);
    $page->add_breadcrumb_item($lang->team_title);
    $page->output_header($lang->team_title);

    $page->output_nav_tabs($sub_tabs, 'team');

    echo '<div><div class="float_right" style="width:50%;">';

    $table = new Table;
    $table->construct_header("<small>".$lang->team_table_name."</small>", array('width' => '50%', 'class' => 'align_center'));
    $table->construct_header("<small>".$lang->team_table_function."</small>", array('width' => '20%', 'class' => 'align_center'));
    $table->construct_header("<small>".$lang->team_table_contribution."</small>", array('width' => '30%', 'class' => 'align_center'));

    $table->construct_cell("<strong>Maris Ovidiu</strong>", array('class' => 'align_center'));
    $table->construct_cell($lang->team_table_function_translator, array('class' => 'align_center'));
    $table->construct_cell($lang->team_table_contribution_frontend, array('class' => 'align_center'));
    $table->construct_row();
       
    $table->construct_cell("<strong>Rusu &#350;tefan</strong>", array('class' => 'align_center'));
    $table->construct_cell($lang->team_table_function_translator, array('class' => 'align_center'));
    $table->construct_cell($lang->team_table_contribution_admincp, array('class' => 'align_center'));
    $table->construct_row();
    
    $table->construct_cell("<strong>Surdeanu Mihai</strong>", array('class' => 'align_center'));
    $table->construct_cell($lang->team_table_function_developer, array('class' => 'align_center'));
    $table->construct_cell($lang->team_table_contribution_plugin, array('class' => 'align_center'));
    $table->construct_row();
    
    $table->construct_cell("<strong>Ungureanu Radu</strong>", array('class' => 'align_center'));
    $table->construct_cell($lang->team_table_function_translator, array('class' => 'align_center'));
    $table->construct_cell($lang->team_table_contribution_admincp, array('class' => 'align_center'));
    $table->construct_row();
    
    $table->output($lang->team_title);

    $table = new Table;
    $table->construct_header("<small>".$lang->sprintf($lang->team_license_version, "1.0")."</small>");
   
    $table->construct_cell("<p align=\"justify\">".$lang->team_license_text."</p>");
    $table->construct_row();
    
    $table->output($lang->team_license_name);
    
    // Testam daca exista o versiune de limba romana pe serverul tau
    if (file_exists(MYBB_ROOT."inc/languages/romanian.php")) {
        // Daca exista atunci trebuie sa intoarcem procentajul de finalizare al versiunii pe care o ai
        require_once MYBB_ROOT."inc/languages/romanian.php";
        // Este setat un procent pentru traducere?
        if (isset($langinfo['procentage'])) {
            // Procentajul va fi afisat cu doua zecimale exacte, iar ca si virgula se va folosi "." (punctul)
            $procentaj = number_format(floatval($langinfo['procentage']), 2, '.', '');
        }
        else {
            // Daca nu este setat un procent atunci se va afisa un minus "-"
            $procentaj = "-";
        }
    }
    else {
        // Daca fisierul nu exista atunci se va afisa tot un minus "-"
        $procentaj = "-";
    }
        
    echo '</div><div class="float_left" style="width:48%;">';
    echo $lang->sprintf($lang->team_page_description, $procentaj);
    echo '</div></div>';
    
    // Se afiseaza subsolul paginii
    $page->output_footer();
?>