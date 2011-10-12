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
    Ultima modificare a codului : 12.10.2011 22:49
*/

// se face legatura cu core-ul MyBB
// ne aflam in MyBB
define("IN_MYBB", 1);
// se include fisierul global.php
require_once "./global.php";
// acest fisier intoarce informatii despre versiunile de traduceri disponibile cat si diverse date statistice
if ( isset($_GET["bburl"]) && ($url = parse_url(urldecode($_GET["bburl"]))) != false )
{
    // cererea vine pentru modulul de statistici?
    if ( isset($_GET["action"]) ) {
        // modul de statistici
        switch ($_GET["action"]) 
        {
            case "send_stats" :
                // sunt trimise date catre server
                $mybb_version = isset($_GET["mybb_version"]) ? urldecode($_GET["mybb_version"]) : "";
                // se prelucreaza versiunea de mybb
                $mybb_version = str_replace('.', '', $mybb_version);
                if (strlen($mybb_version) == 3)
                    $mybb_version = $mybb_version[0].$mybb_version[1]."0".$mybb_version[2];
                $php_version = isset($_GET["php_version"]) ? urldecode($_GET["php_version"]) : "";
                // se prelucreaza versiunea PHP
                $php_version = str_replace('.', '', $php_version);
                if (strlen($php_version) == 3)
                    $php_version = $php_version[0].$php_version[1]."0".$php_version[2];
                $server_software = isset($_GET["server_software"]) ? urldecode($_GET["server_software"]) : "";
                $hostname = isset($_GET['hostname']) ? urldecode($_GET['hostname']) : "";
                $useragent = isset($_GET['useragent']) ? urldecode($_GET['useragent']) : "";
                $array = array(
                    "host"              => $db->escape_string($url['host']),
                    "requests"          => 0,
                    "date_first"        => TIME_NOW,
                    "date_last"         => TIME_NOW,
                    "have_data"         => 1,
                    "version_mybb"      => intval($mybb_version),
                    "version_php"       => intval($php_version),
                    "server_soft"       => $db->escape_string($server_software),
                    "host_company"      => $db->escape_string($hostname),
                    "user_agent"        => $db->escape_string($useragent)
                );
                // exista domeniul in baza de date
                if ($db->num_rows($db->simple_select("rolang_stats", "sid", "host = '".$db->escape_string($url['host'])."'")) > 0) {
                    unset($array['host'], $array['requests'], $array['date_first']);
                    // atunci se face update
                    $db->update_query("rolang_stats", $array, "host = '".$db->escape_string($url['host'])."'");       
                }
                else {
                    // se insereaza un nou rand in baza de date
                    $db->insert_query("rolang_stats", $array);
                }
                break;
            case "get_stats":
                // se intorc date catre client
                $stats = $mybb->cache->read("rolang_stats");
                // daca exista un cache cu statisticile si el nu are mai mult de 3 zile (3*24*60*60)
                if($stats && isset($stats['server']) && is_array($stats['server']) && isset($stats['server']['timestamp']) && (TIME_NOW - intval($stats['server']['timestamp'])) < 259200) {
                    // datele sunt preluate din cache
                    if (isset($stats['stats'])) {
                        unset($stats['stats']);
                    }
                    // se afiseaza vectorul pe ecran
                    echo json_encode($stats);
                }
                else {
                    $start_get = microtime(true);
                    // se realizeaza cateva interogari SQL
                    $query1 = $db->query("SELECT COUNT(host) AS number_hosts,  COUNT(DISTINCT version_mybb) AS number_mybb, COUNT(DISTINCT version_php) AS number_php, MAX(version_mybb) AS max_mybb, MIN(version_mybb) AS min_mybb, MAX(version_php) AS max_php, MIN(version_php) AS min_php, SUM(requests) AS number_requests, (MAX(date_last) - MIN(date_first)) AS total_time FROM ".TABLE_PREFIX."rolang_stats WHERE have_data = '1'");
                    $query2 = $db->query("SELECT version_mybb, COUNT(sid) AS number FROM ".TABLE_PREFIX."rolang_stats GROUP BY version_mybb ORDER BY COUNT(sid) DESC LIMIT 5");
                    $query3 = $db->query("SELECT version_php, COUNT(sid) AS number FROM ".TABLE_PREFIX."rolang_stats GROUP BY version_php ORDER BY COUNT(sid) DESC LIMIT 5");
                    $general_stats = $db->fetch_array($query1);
                    $version_mybb = array();
                    while ($row = $db->fetch_array($query2)) {
                        $version_mybb[$row['version_mybb']] = $row['number'];
                    }
                    $version_php = array();
                    while ($row = $db->fetch_array($query3)) {
                        $version_php[$row['version_php']] = $row['number'];
                    }
                    if (function_exists("memory_get_peak_usage")) {
                        // se intoarce memoria RAM (in MB) utilizata de catre server cu doua zecimale exacte
                        $memory_usage = number_format(memory_get_peak_usage(true)/1048576, 2);
                    }
                    else {
                        $memory_usage = 0;
                    }
                    if (function_exists("get_server_load")) {
                        // se intoarce timpul de incarcare al serverului cu doua zecimale exacte
                        $server_load = get_server_load();
                    }
                    else {
                        $server_load = 0;
                    }
                    $end_get = microtime(true);
                    $array = array(
                        "server"    => array(
                                            "general"   => $general_stats,
                                            "mybb_vers" => $version_mybb,
                                            "php_vers"  => $version_php,
                                            "metrics"   => array(
                                                            "memory_usage" => $memory_usage,
                                                            "server_load"  => $server_load,
                                                            "generated_in" => number_format(($end_get - $start_get), 5)
                                                         ),
                                            "timestamp" => TIME_NOW
                                       )
                    );
                    // se afiseaza vectorul cu statistici pe ecran
                    echo json_encode($array);
                    // si se face update la cache
                    if (isset($stats['stats']) && is_array($stats['stats']))
                        $array['stats'] = $stats['stats'];
                    if (isset($stats['infos']) && is_array($stats['infos']))
                        $array['infos'] = $stats['infos'];
                    // se face update
                    $mybb->cache->update("rolang_stats", $array);
                }
                break;
            case "get_archive_lang" :
                $path = $_SERVER['DOCUMENT_ROOT']."/inc/plugins/rolang/uploads/";
                // este setat hash-ul fisierului?
                if (!isset($_GET['hash'])) {
                    exit();
                }
                // se realizeaza interogarea bazei de date
                $query = $db->simple_select("rolang_updates", "archive_lang", "hash = '".$db->escape_string($_GET['hash'])."'", array("limit" => 1));
                if ($row = $db->fetch_array($query)) 
                {
                    $file = basename(base64_decode($row['archive_lang']));
                    $full_path = $path.$file;
                    // se descarca un fisier
                    require_once MYBB_ROOT."inc/class_throttler.php";
                    // se creaza o noua configurare
                    new DownloadLimit(20480, 5, 15360, true);
                    // exista fisierul si poate fi deschis ?
                    if (file_exists($full_path) && ($filed = fopen($full_path, "r"))) 
                    {
                        // marimea fisierului este de ... bytes
                        $filesize = filesize($full_path);
                        // se seteaza antetul paginii web
                        header("Content-type: application/octet-stream");
                        header("Content-Disposition: filename=\"".$file."\"");
                        header("Content-length: $filesize");
                        header("Cache-control: private");
                        // se afiseaza continutul
                        while(!feof($filed)) {
                            $buffer = fread($filed, 2048);
                            echo $buffer;
                        }
                        fclose();
                    }
                }
                // altfel nu se intampla nimic
                break;
            case "get_archive_img" :
                $path = $_SERVER['DOCUMENT_ROOT']."/inc/plugins/rolang/uploads/";
                // este setat hash-ul fisierului?
                if (!isset($_GET['hash'])) {
                    exit();
                }
                // se realizeaza interogarea bazei de date
                $query = $db->simple_select("rolang_updates", "archive_img", "hash = '".$db->escape_string($_GET['hash'])."'", array("limit" => 1));
                if ($row = $db->fetch_array($query)) 
                {
                    $file = basename(base64_decode($row['archive_img']));
                    $full_path = $path.$file;
                    // se descarca un fisier
                    require_once MYBB_ROOT."inc/class_throttler.php";
                    // se creaza o noua configurare
                    new DownloadLimit(20480, 5, 15360, true);
                    // exista fisierul si poate fi deschis ?
                    if (file_exists($full_path) && ($filed = fopen($full_path, "r"))) 
                    {
                        // marimea fisierului este de ... bytes
                        $filesize = filesize($full_path);
                        // se seteaza antetul paginii web
                        header("Content-type: application/octet-stream");
                        header("Content-Disposition: filename=\"".$file."\"");
                        header("Content-length: $filesize");
                        header("Cache-control: private");
                        // se afiseaza continutul
                        while(!feof($filed)) {
                            $buffer = fread($filed, 2048);
                            echo $buffer;
                        }
                        fclose();
                    }
                }
                // altfel nu se intampla nimic
                break;
        }
    }
    else {
        // altfel sunt afisate versiunile de traduceri disponibile si activate
        $versions = $mybb->cache->read("rolang_releases");
        $releases = "";
        // daca exista un cache cu versiunile si el nu are mai mult de 3 zile (3*24*60*60)
        if($versions && isset($versions['timestamp']) && (TIME_NOW - intval($versions['timestamp'])) < 259200) {
            // datele sunt citite din el
            $releases = $versions['releases'];
        }
        else {
            // datele sunt citite din baza de date
            $query = $db->simple_select("rolang_updates", "*", "active = '1'", array("order_by" => "for_version", "order_dir" => "DESC", "limit" => 25));
            if ($db->num_rows($query) == 0) {
                echo "";
                // se iese fortat
                exit();    
            }
            // se formeaza string-ul final
            while ($release = $db->fetch_array($query)) {
                $releases .= "<realizare><pentru>".$db->escape_string($release['for'])."</pentru><destinat>".intval($release['for_version'])."</destinat><compatibil>".$db->escape_string($release['for_compatibility'])."</compatibil><hash>".$db->escape_string($release['hash'])."</hash><legatura>".$db->escape_string($release['archive_lang'])."</legatura><imagini>".$db->escape_string($release['archive_img'])."</imagini><data>".intval($release['date'])."</data></realizare>\n";
            }
            // apoi se incearca un update al cache-ul cu noi informatii
            $versions = array(
                "releases"  => $releases,
                "timestamp" => TIME_NOW
            );
            $mybb->cache->update("rolang_releases", $versions);
        }
        // se realizeaza o statistica
        $stats = $mybb->cache->read("rolang_stats");
        if($stats && isset($stats['stats']) && isset($stats['stats']['temp']) && isset($stats['stats']['timestamp'])) 
        {
            // datele sunt citite din el
            $requests = $stats['stats']['temp'];
            // daca domeniul exista deja atunci se face o incrementare
            if (isset($requests[$url['host']]) && is_numeric($requests[$url['host']])) {
                $requests[$url['host']] = intval($requests[$url['host']]) + 1;  
            }
            else {
                // daca nu exista se creaza o cheie cu domeniul
                $requests[$url['host']] = 1;
            }
            // daca se depaseste un numar de 100 de cereri atunci se face update in baza de date
            $limit1 = is_numeric($mybb->settings['rolang_server_limit_hosts']) ? intval($mybb->settings['rolang_server_limit_hosts']) : 10;
            $limit2 = is_numeric($mybb->settings['rolang_server_limit_requests']) ? intval($mybb->settings['rolang_server_limit_requests']) : 50;
            if (count($requests) >= $limit1 || array_sum($requests) >= $limit2) 
            {
                // se face update in baza de date
                // pentru fiecare sait din vector
                foreach ($requests as $key => $value)
                {
                    // exista randul in baza de date ?
                    if ($db->num_rows($db->simple_select("rolang_stats", "sid", "host = '".$db->escape_string($key)."'")) > 0) {
                        // atunci se face update
                        $db->query("UPDATE ".TABLE_PREFIX."rolang_stats SET requests = requests + ".intval($value)." WHERE host = '".$db->escape_string($key)."'");       
                    }
                    else {
                        // altfel se insereaza un nou rand in baza de date
                        $domain = array(
                            "host"              => $db->escape_string($key),
                            "requests"          => intval($value),
                            "have_data"         => 0
                        );
                        $db->insert_query("rolang_stats", $domain);
                    }
                    
                }
                // se goleste cache-ul
                $statistics = array(
                    "stats"     => array(
                                        "temp"      => array(),
                                        "timestamp" => TIME_NOW
                                   ),
                    
                );
            }
            else {
                // altfel se face update in cache
                $statistics = array(
                    "stats"     => array(
                                        "temp"      => $requests,
                                        "timestamp" => TIME_NOW
                                   )
                );
            }
            if (isset($stats['server']) && is_array($stats['server']))
                $statistics['server'] = $stats['server'];
            if (isset($stats['infos']) && is_array($stats['infos']))
                $statistics['infos'] = $stats['infos'];
            $mybb->cache->update("rolang_stats", $statistics);
        }
        else {
            // nu exista cache-ul creat
            // se creaza unul
            $requests[$url['host']] = 1;
            $statistics = array(
                "stats"     => array(
                                    "temp"      => $requests,
                                    "timestamp" => TIME_NOW
                               )
            );
            if (isset($stats['server']) && is_array($stats['server']))
                $statistics['server'] = $stats['server'];
            if (isset($stats['infos']) && is_array($stats['infos']))
                $statistics['infos'] = $stats['infos'];
            $mybb->cache->update("rolang_stats", $statistics);
        }
        // se afiseaza continutul XML
        header("Content-type: text/xml"); 
        echo "<mybbromania><traducere><versiuni>".$releases."</versiuni></traducere></mybbromania>";
    }
}
else {
    // nu se afiseaza nimic
    echo "";
}
// se iese fortat
exit();
?>