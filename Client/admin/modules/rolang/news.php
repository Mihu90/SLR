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
    Ultima modificare a codului : 05.08.2011 18:27
*/

// Poate fi acesat direct fisierul?
if(!defined("IN_MYBB")) {
    die("This file cannot be accessed directly.");
}
    
// Se verifica daca exista permisiuni
if (!$mybb->admin['permissions']['rolang']['news'])
{
    $page->output_header($lang->access_denied);
    $page->add_breadcrumb_item($lang->access_denied, "index.php?module=home-index");
    $page->output_error("<b>{$lang->access_denied}</b><ul><li style=\"list-style-type: none;\">{$lang->access_denied_desc}</li></ul>");
    $page->output_footer();
    exit;
}

// Se include fisierul de limba
rolang_include_lang("global");

//Menu
$sub_tabs = array(
    "news" => array(
        "title" => $lang->news_title,
        "link" => "index.php?module=rolang-news",
        "description" => $lang->news_description
        )
    );
    
// Ruleaza in momentul in care se cere de catre user verificarea update-urilor
if($mybb->input['ajax'] && $mybb->input['action'] == "get_news")
{
    // se afiseaza pe ecranul utilizatorului posibile actualizari
	rolang_displayNews(false);  
	// se iese fortat
    exit(); 
}
if(!$mybb->input['action'])
{
    $page->add_breadcrumb_item($lang->mod_name);
    $page->add_breadcrumb_item($lang->news_title);
    $page->output_header($lang->news_title);

    $page->output_nav_tabs($sub_tabs, 'news');

    echo '<div id="news_loading"></div>';
	echo '<div class="float_right" style="width:100%;" id="table_center">';
	echo '</div>';
	echo '<script type="text/javascript">
    <!--
        var container_div = $(\'news_loading\');
        Event.observe(window, \'load\', function() {
            getNews();
        });
	    function getNews()
	    {
            new Ajax.Request("index.php?module=rolang-news",
            {
                parameters: { ajax: 1, action: "get_news" },
                onLoading: function() {
                    container_div.update(\'<center><img src="../images/rolang/loading.gif"/></center>\');
                },
                onComplete: function(data) { 
                    container_div.update(\'\');
                    $("table_center").insert({"top" : data.responseText});
                }
            });
        }
    // --></script>';

    // se afiseaza subsolul paginii	
    $page->output_footer();  
}

// Functie care produce afisarea noutatilot
function rolang_displayNews($echo)
{
    global $lang;
    // Se include fisierul de limba
    rolang_include_lang("global");
    $url = "http://mybb.ro/syndication.php?fid=49&limit=5";
    // datele sunt introduse intr-un tabel
    $table = new Table;
    $table->construct_header($lang->news_table_title, array("colspan" => 2, "width" => "100%"));
    $table->construct_row();
    // se verifica daca este un URL valid!
    if (rolang_validateRSS($url)) 
    {
        $doc = new DOMDocument();
        // se citesc date de pe server
        $doc->load($url);
        // la inceput lista este vida
        $list = array();
        foreach ($doc->getElementsByTagName('item') as $node) {
            $itemRSS = array ( 
                'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue
            );
            // se adauga item-ul in lista
            array_push($list, $itemRSS);
        }
        // sunt elemente in vector
        if(count($list) > 0) {
            // daca sunt atunci se creeaza un rand in tabel pentru fiecare element
            foreach($list as $vector) {
                $table->construct_cell("<a href='".$vector['link']."'>".$vector['title']."</a>", array("class" => "align_center", "style" => "background: #FFC;")); 
                $table->construct_cell($vector['date'], array("class" => "align_center", "style" => "background: #FFC;"));  
           	    $table->construct_row();
                $table->construct_cell($vector['desc'], array("colspan" => 2, "width" => "100%"));
           	    $table->construct_row();
            }	
        }
        else {
            // daca nu sunt atunci se creeaza un rand cu un mesaj special
            $table->construct_cell($lang->news_table_noposts, array("colspan" => 2, "width" => "100%", "class" => "align_center"));
           	$table->construct_row();
        }
    }
    else {
        // se va afisa un mesaj de eroare
        $table->construct_cell($lang->news_table_error, array("colspan" => 2, "width" => "100%", "class" => "align_center"));
       	$table->construct_row();
    }
    // in cele din urma se afiseaza tabelul pe ecran
    $table->output($lang->news_title, 1, "general\" id=\"news_table", $echo);
}

// Functie care valideaza URL-ul de noutati
function rolang_validateRSS($sFeedURL)
{
    $sValidator = 'http://feedvalidator.org/check.cgi?url=';   
    if( $sValidationResponse = @file_get_contents($sValidator . urlencode($sFeedURL)) )
    {
        if( stristr( $sValidationResponse , 'This is a valid RSS feed' ) !== false ) {
            return true;
        }
        else {
            return false;
        }
    }
    else {
        return false;
    }
}
?>