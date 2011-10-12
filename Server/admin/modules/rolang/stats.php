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
    Ultima modificare a codului : 12.10.2011 22:50
*/

// Poate fi acesat direct fisierul?
if(!defined("IN_MYBB")) {
    	die("This file cannot be accessed directly.");
}

// Se verifica permisiunile paginii
if (!$mybb->admin['permissions']['rolang']['stats'])
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
	"stats" => array(
		"title" => $lang->stats_title,
		"link" => "index.php?module=rolang-stats",
		"description" => $lang->stats_description
	)
);

if(!$mybb->input['action'])
{
	$page->add_breadcrumb_item($lang->mod_name);
	$page->add_breadcrumb_item($lang->stats_title);
    // se verifica daca exista un cache in sistem cu informatii legate de pagina de statistici
    $result = false;
    $stats_cache = $cache->read("rolang_stats");
    if ($stats_cache && is_array($stats_cache) && isset($stats_cache['infos']) && (TIME_NOW - $stats_cache['infos']['timestamp']) < 3 * 86400) {
        $array = $stats_cache['infos']['stats'];
        $result = true;
    }
    else {
        // se creaza cererea catre server-ul de date
        $string_url = $mybb->settings['rolang_server_url']."/server.php?bburl=".urlencode($mybb->settings['bburl'])."&action=get_stats";
        if(($content = fetch_remote_file($string_url)) !== false) 
        {
            $result = true;
            // se decodifica rezultatul primit
            $array = json_decode($content, true);
            $update = array (
                        'infos' => array('stats' => $array, 'timestamp' => TIME_NOW)
                    );
            // daca ramura de statistici exista atunci se adauga la vector
            if ($stats_cache && isset($stats_cache['stats'])) {
                $update['stats'] = $stats_cache['stats'];
            }
            // daca ramura serverului exista atunci se adauga la vector
            if ($stats_cache && isset($stats_cache['server'])) {
                $update['server'] = $stats_cache['server'];
            }
            // se face update la cache
            $cache->update("rolang_stats", $update);
        }
    }
    if($result && is_array($array)) 
    {
        $our_memory = (isset($array['server']['metrics']['memory_usage'])) ? $array['server']['metrics']['memory_usage'] : 0;
        $our_load = (isset($array['server']['metrics']['server_load'])) ? $array['server']['metrics']['server_load'] : 0;
        $our_generate = (isset($array['server']['metrics']['generated_in'])) ? $array['server']['metrics']['generated_in'] : 0;
        $page->extra_header .= "
        <script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
        <script type=\"text/javascript\" src=\"{$mybb->settings['bburl']}/admin/modules/rolang/jscripts/jquery-1.4.4.min.js\"></script>
        <script type=\"text/javascript\" src=\"{$mybb->settings['bburl']}/admin/modules/rolang/jscripts/jquery.gvChart-1.0.1.min.js\"></script>
        <script type=\"text/javascript\">
        gvChartInit();
        jQuery(document).ready(function() {
            jQuery('#rolang_mybb_vers').gvChart({
                chartType: 'BarChart',
                gvSettings: {
                    title: 'Grad de utilizare al unor versiuni de platforme MyBB',
                    vAxis: {title: 'Versiune platforma'},
                    hAxis: {title: 'Numar de utilizari'},
                    height: 300,
                }
            });
            jQuery('#rolang_php_vers').gvChart({
                chartType: 'BarChart',
                gvSettings: {
                    title: 'Grad de utilizare al unor versiuni de PHP',
                    vAxis: {title: 'Versiune'},
                    hAxis: {title: 'Numar de utilizari'},
                    height: 300,
                }
            });
        });
        google.load('visualization', '1', {packages:['gauge']});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Label');
            data.addColumn('number', 'Value');
            data.addRows(1);
            data.setValue(0, 0, 'Memorie');
            data.setValue(0, 1, ".(memory_get_peak_usage(true)/1048576).");

            var chart = new google.visualization.Gauge(document.getElementById('rolang_meter_your_1'));
            var options = {width: 120, height: 120, min: 0, max: 40, redFrom: 30, redTo: 40,
                                                     yellowFrom: 20, yellowTo: 30,
                                                     greenFrom: 0, greenTo: 20,
                       minorTicks: 5};
            chart.draw(data, options);
        
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Label');
            data.addColumn('number', 'Value');
            data.addRows(1);
            data.setValue(0, 0, 'Procesor');
            data.setValue(0, 1, ".get_server_load().");

            var chart = new google.visualization.Gauge(document.getElementById('rolang_meter_your_2'));
            var options = {width: 120, height: 120, min: 0, max: 10, redFrom: 7, redTo: 10,
                                                     yellowFrom: 4, yellowTo: 7,
                                                     greenFrom: 0, greenTo: 4,
                       minorTicks: 5};
            chart.draw(data, options);

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Label');
            data.addColumn('number', 'Value');
            data.addRows(1);
            data.setValue(0, 0, 'Memorie');
            data.setValue(0, 1, ".$our_memory.");

            var chart = new google.visualization.Gauge(document.getElementById('rolang_meter_our_1'));
            var options = {width: 120, height: 120, min: 0, max: 40, redFrom: 30, redTo: 40,
                                                     yellowFrom: 20, yellowTo: 30,
                                                     greenFrom: 0, greenTo: 20,
                       minorTicks: 5};
            chart.draw(data, options);
        
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Label');
            data.addColumn('number', 'Value');
            data.addRows(1);
            data.setValue(0, 0, 'Procesor');
            data.setValue(0, 1, ".$our_load.");

            var chart = new google.visualization.Gauge(document.getElementById('rolang_meter_our_2'));
            var options = {width: 120, height: 120, min: 0, max: 10, redFrom: 7, redTo: 10,
                                                     yellowFrom: 4, yellowTo: 7,
                                                     greenFrom: 0, greenTo: 4,
                       minorTicks: 5};
            chart.draw(data, options);
        }
        </script>";
	    $page->output_header($lang->stats_title);
        // se afiseaza meniul orizontal
        $page->output_nav_tabs($sub_tabs, 'stats');
   	    echo '<div>
        <div class="float_right" style="width:50%;">';
        // se creaza un nou tabel
        $table = new Table;
        $table->construct_header($lang->stats_meters_your_server, array('width' => '50%'));
        $table->construct_header($lang->stats_meters_our_server, array('width' => '50%'));
        $table->construct_row();
        $table->construct_cell('<div align="center"><div style="display:table-cell;" id="rolang_meter_your_1"></div><div style="display:table-cell;" id="rolang_meter_your_2"></div></div>'); 
        $table->construct_cell('<div align="center"><div style="display:table-cell;" id="rolang_meter_our_1"></div><div style="display:table-cell;" id="rolang_meter_our_2"></div></div>');  
   	    $table->construct_row();
        $table->output($lang->stats_meters_title);
        // se creaza al doilea tabel
        $table = new Table;
        $table->construct_header($lang->stats_general_info, array('width' => '70%'));
        $table->construct_header($lang->stats_general_value, array('width' => '30%'));
        $table->construct_row();
        // se creaza vectorul
        if(isset($array['server']['general']))
        {
            foreach($array['server']['general'] as $key => $value) {
                $string = "stats_general_".$key;
                if (in_array($key, array("max_mybb", "min_mybb", "max_php", "min_php"))) {
                    if (intval($value[2]) == 0) {
                        $value = $value[0].".".$value[1].".".$value[3];
                    }
                    else {
                        $value = $value[0].".".$value[1].".".$value[2].$value[3];
                    }
                }
                elseif($key == "total_time") {
                    $value = rolang_set_period($value);
                }
                $table->construct_cell($lang->{$string}, array("class" => "align_center")); 
                $table->construct_cell($value, array("class" => "align_center"));  
           	    $table->construct_row();
            }	
        }
        $table->construct_cell($lang->stats_general_last_update, array("class" => "align_center")); 
        $table->construct_cell(date("d.m.Y H:i:s", intval($array['server']['timestamp'])), array("class" => "align_center"));  
   	    $table->construct_row();
    	// in final se afiseaza tabelul pe ecranul utilizatorului
        $table->output($lang->stats_general_title);
        echo "<p class=\"notice\" style=\"background-image: url(../images/rolang/info.png);background-repeat: no-repeat;background-position: 10px center;\">".$lang->sprintf($lang->stats_generated_in, number_format(floatval($our_generate) * 1000, 2))."</p>";
       	echo '</div><div class="float_left" style="width:48%;">';
        $mybb_keys = array_map(create_function('$key', 'return "<th>MyBB ".$key."</th>";'), array_keys($array['server']['mybb_vers']));
        $mybb_values = array_map(create_function('$value', 'return "<td>".$value."</td>";'), array_values($array['server']['mybb_vers']));
        $mybb_y_axis = implode($mybb_keys);
        $mybb_x_axis = implode($mybb_values);
        echo "<table id=\"rolang_mybb_vers\">
	           <thead>
					<tr>
                        <th></th>
						{$mybb_y_axis}
					</tr>
				</thead>
					<tbody>
					<tr>
						<th>Utilizari</th>
						{$mybb_x_axis}
					</tr>
				</tbody>
			</table>
        ";
        $php_keys = array_map(create_function('$key', 'return "<th>PHP ".$key."</th>";'), array_keys($array['server']['php_vers']));
        $php_values = array_map(create_function('$value', 'return "<td>".$value."</td>";'), array_values($array['server']['php_vers']));
        $php_y_axis = implode($php_keys);
        $php_x_axis = implode($php_values);
        echo "<table id=\"rolang_php_vers\">
	           <thead>
					<tr>
                        <th></th>
						{$php_y_axis}
					</tr>
				</thead>
					<tbody>
					<tr>
						<th>Utilizari</th>
						{$php_x_axis}
					</tr>
				</tbody>
			</table>
        ";
        echo '</div></div>';
    }
    else {
        $page->output_header($lang->stats_title);
        // apare o eroare pe ecran
        $page->output_error("<b>{$lang->stats_error_deny}</b><ul><li style=\"list-style-type: none;\">{$lang->stats_error_deny_desc}</li></ul>");
    }
    // se afiseaza subsolul paginii
	$page->output_footer();
}

function rolang_set_period($time)
{
    if (floatval($time) < 3600) {
        // atunci sunt biti
        return number_format(floatval($time)/60, 2)." minute";
    }
    else {
        // sunt mai mult de 24 de ore?
        if (floatval(floatval($time)/3600) > 24) {
            // atunci sunt mai mult de 24 de ore si se va intoarce in zile
            return number_format(floatval($time)/3600/24, 2). " zile";
        }
        else {
            // sunt mai putin de 24 de ore
            return number_format(floatval($time)/3600, 2). " ore";
        }
    }	
}
?>