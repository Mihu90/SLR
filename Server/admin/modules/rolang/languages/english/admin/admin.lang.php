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
    Ultima modificare a codului : 04.10.2011 18:17
*/
// GENERAL
$l['mod_name'] = "Sistem de Limb&#259; Rom&#226;n&#259;";
$l['mod_menu_item'] = "SLR";
$l['mod_exception_update'] = "S-a &#238;ncercat realizarea unei actualiz&#259;ri dar nu s-a putut deoarece a ap&#259;rut eroarea : {1}.";
$l['mod_table_options'] = "Op&#355;iuni";
$l['mod_legend'] = "Legend&#259;";
// PERMISIUNI
$l['perm_rolang'] = "Poate administra sec&#355;iunea de configur&#259;ri a SLR-ului?";
$l['perm_infos'] = "Poate administra sec&#355;iunea de Informa&#355;ii?";
$l['perm_news'] = "Poate vedea ultimile nout&#259;&#355;i?";
$l['perm_updates'] = "Poate vedea &#351;i realiza actualiz&#259;ri de limb&#259;?";
$l['perm_logs'] = "Poate vedea &#351;i administra sec&#355;iunea \"Jurnal\"?";
$l['perm_stats'] = "Poate accesa pagina \"Statistici\"?";
$l['perm_team'] = "Poate vedea pagina intitulat&#259; \"Echip&#259;\"?";
$l['perm_admin'] = "Poate accesa sec&#355;iunea de administra&#355;ie a modific&#259;rii?";
// INFORMATII UTILE
$l['infos_title'] = "Informa&#355;ii";
$l['infos_description'] = "Informa&#355;ii de baz&#259; despre aceast&#259; modificare.";
$l['infos_page_description'] = "<h4>Informa&#355;ii utile despre modificare</h4>
<p align=\"justify\">&#206;n cadrul primul tabel intitulat \"Informa&#355;ii utile\" ve&#355;i putea vedea o serie de date legate de versiunile curente de MyBB &#351;i de limb&#259; rom&#226;n&#259; (dac&#259; ave&#355;i una instalat&#259;) c&#226;t &#351;i starea serverului de update-uri. Dac&#259; acest server nu func&#355;ioneaz&#259; la parametri normali atunci dvs. nu ve&#355;i putea realiza update-uri utiliz&#226;nd pagina \"Actualiz&#259;ri\". Adresa web a serverului poate fi schimbat&#259; prin accesarea grupului de set&#259;ri \"Sistemul de Limb&#259; Rom&#226;n&#259;\" din zona de configur&#259;ri.</p>
<p align=\"justify\">Cel de-al doilea tabel se refer&#259; la permisiunile pe care trebuie s&#259; le &#238;ndeplineasc&#259; unele directoare pentru ca un eventual update s&#259; poat&#259; fi instalat cu succes pe server.</p>";
$l['infos_table_information_name'] = "Informa&#355;ii utile";
$l['infos_table_information_info'] = "Informa&#355;ie";
$l['infos_table_information_value'] = "Valoare";
$l['infos_table_information_mybbversion'] = "Versiune curent&#259; de MyBB";
$l['infos_table_information_langversion'] = "Versiune curent&#259; de Limb&#259; Rom&#226;n&#259;";
$l['infos_table_information_server'] = "Stare server de date";
$l['infos_table_information_your_server'] = "Detalii despre serverul t&#259;u";
$l['infos_table_information_your_server_value'] = "Versiune PHP : <b>{1}</b><br> Clasa <i>ZipArchive</i> : <b>{2}</b>";
$l['infos_table_information_your_server_defined'] = "definit&#259;";
$l['infos_table_information_your_server_indefined'] = "nedefinit&#259;";
$l['infos_table_permissions_name'] = "Permisiune";
$l['infos_table_permissions_folder'] = "Director";
$l['infos_table_permissions_curent'] = "Curent&#259;";
$l['infos_table_permissions_recommended'] = "Recomandat&#259;";
$l['infos_table_permissions_set'] = "Permisiunile sunt bine setate.";
$l['infos_table_permissions_unset'] = "Permisiunile nu sunt setate cum trebuie.";
$l['infos_button_add'] = "Adauga legatura in subsol";
$l['infos_button_remove'] = "Sterge legatura din subsol";
$l['infos_button_process'] = "Se proceseaza cererea...";
$l['infos_button_error'] = "Eroare la realizarea actiunii!";
$l['infos_button_type_log'] = "Ac&#355;iune realizat&#259;";
$l['infos_button_add_log'] = "S-a ad&#259;ugat cu succes leg&#259;tura c&#259;tre noi, &#238;n subsolul forumului dvs.";
$l['infos_button_remove_log'] = "S-a &#351;ters cu succes leg&#259;tura din subsolul forumului dvs.";
$l['infos_button_description'] = "<h4>Ad&#259;ugare &#351;i &#351;tergere leg&#259;tur&#259; c&#259;tre MyBB Rom&#226;nia</h4>
<p align=\"justify\">Butonul de mai sus v&#259; ofer&#259; posibilitatea de a ad&#259;uga sau de a &#351;terge foarte u&#351;or leg&#259;tura c&#259;tre noi, din cadrul subsolului paginii dvs. de index. Trebui s&#259; &#351;ti&#355;i c&#259; dac&#259; nu ave&#355;i aceast&#259; leg&#259;tura &#238;n subsol, c&#259;tre noi, nu ve&#355;i putea folosi modulul de \"Actualiz&#259;ri\", pentru c&#259; ve&#355;i primi o eroare &#238;n momentul &#238;n care &#238;ncerca&#355;i o verificare posibil&#259; de actualiz&#259;ri. Pentru mai multe detalii legate de modul cum func&#355;ioneaz&#259; butonul v&#259; rug&#259;m s&#259; citi&#355;i documenta&#355;ia oficial&#259;.</p>";
// NOUTATI
$l['news_title'] = "Nout&#259;&#355;i";
$l['news_description'] = "Ultimile nout&#259;&#355;i de pe saitul oficial <a href=\"http://mybb.ro\">MyBB Rom&#226;nia</a>.";
$l['news_table_title'] = "Ultimile 5 nout&#259;&#355;i legate de pachetele de limb&#259;";
$l['news_table_noposts'] = "Nu exist&#259; nout&#259;&#355;i pe serverul oficial.";
$l['news_table_error'] = "Nu s-au putut citi nout&#259;&#355;i de pe serverul specificat. Se poate ca acesta s&#259; fie c&#259;zut!";
// ACTUALIZARI
$l['updates_title_menu'] = "Actualiz&#259;ri";
// Actualizare traducere
$l['updates_title'] = "Traducere";
$l['updates_description'] = "Pagin&#259; &#238;n care pot fi administrate diverse versiuni de traduceri.";
$l['updates_page_description'] = "<h4>Caut&#259; versiuni mai noi</h4>
<p align=\"justify\">&#206;n momentul &#238;n care ape&#351;i pe butonul de verificare a update-urilor, modificarea se conecteaz&#259; la serverul mam&#259; &#351;i va &#238;ncepe s&#259; compare versiunile de limb&#259; care exist&#259; pe server cu versiunea ta. Totodat&#259; aceast&#259; aplica&#355;ie &#355;ine cont &#351;i de versiunea de MyBB, select&#226;nd din baza de date numai versiunile de limb&#259; rom&#226;n&#259; care sunt mai noi dec&#226;t ceea ce ai tu momentan.</p>
<p align=\"justify\">Este important de &#351;tiut faptul c&#259; aplica&#355;ia poate &#238;ntoarce mai multe versiuni. Adic&#259; dac&#259; tu ai pe server versiunea 1.6.4, s&#259; zicem, iar pe server exist&#259; versiunile 1.6.5 &#351;i 1.6.6 atunci ambele versiuni pot fi instalate. Depinde pe care dore&#351;ti s&#259; o instalezi. Ca idee <u>noi recomand&#259;m</u> s&#259; se instaleze cea mai nou&#259; versiune de limb&#259;, compatibil&#259; cu cea de MyBB.</p>
<p align=\"justify\">Dup&#259; ce verifici existen&#355;a unor update-uri posibile &#351;i dup&#259; ce &#238;&#355;i apare cel pu&#355;in o versiune instalabil&#259; &#238;n partea din dreapta, vei avea posibilitatea s&#259; o instalezi pe forum. Acest lucru se poate face foarte u&#351;or ap&#259;s&#226;nd pe leg&#259;tura \"acum\" din dreptul versiunii de limb&#259;.</p>
<h4>Nu neglija erorile!</h4>
<p align=\"justify\">&#206;n al doilea tabel de pe aceast&#259; pagin&#259; ave&#355;i o list&#259; cu erorile care pot ap&#259;rea dup&#259; ce &#238;ncepe&#355;i procesul de instalare al unei versiuni de limb&#259;. Ordinea din tabel corespunde cu ordinea verific&#259;rii de c&#259;tre script a erorilor posibile.</p>
<h4>Imaginile temei tale!</h4>
<p align=\"justify\">&#206;n cazul &#238;n care ave&#355;i probleme cu imaginile traducerii, ce nu sunt afi&#351;ate pe forum, atunci va trebui s&#259; fi&#355;i sigur c&#259; le ave&#355;i instalate / ad&#259;ugate &#238;n cadrul temei dvs. Acest lucru se poate face cu ajutorul informa&#355;iilor din ultimul tabel.</p>";
$l['updates_button_check'] = "Verifica pentru eventuale actualizari";
$l['updates_button_check_process'] = "Se verifica pentru update-uri...";
$l['updates_button_mybbversion'] = "Versiunea curent&#259; a MyBB-ul t&#259;u este <b>{1}</b>.";
$l['updates_table_list_name'] = "List&#259; cu actualiz&#259;ri posibile";
$l['updates_table_list_translation'] = "Traducere pentru";
$l['updates_table_list_version'] = "Versiune ( Dat&#259; )";
$l['updates_table_list_install'] = "Instaleaz&#259;";
$l['updates_table_list_hover'] = "Compatibil&#259; cu versiunile urm&#259;toare de MyBB : <b>{1}</b>.";
$l['updates_table_list_install_start'] = "acum";
$l['updates_table_list_compatible'] = "Traducerea pe care o ai acum pe server e compatibil&#259; cu cea de MyBB.";
$l['updates_table_list_nocompatible'] = "Nu de&#355;ii o traducere compatibil&#259; cu cea de MyBB, &#238;n momentul de fa&#355;&#259;.";
$l['updates_table_list_nofound'] = "Nu s-au g&#259;sit posibile update-uri pe server.";
$l['updates_table_list_error'] = "<p align=\"justify\" style=\"margin: 0px;\">A ap&#259;rut o eroare la conectarea alica&#355;iei la server sau la prelucrarea datelor de pe acesta.(Se poate s&#259; nu existe &#238;nc&#259; versiuni de traduceri pe server!)</p>";
$l['updates_table_list_lastcheck_1'] = "Ultima verificare : {1} <span id=\"last_check\" style=\"display: none;\">{2}</span>";
$l['updates_table_list_lastcheck_2'] = "Ultima verificare : {1} {2} <span id=\"last_check\" style=\"display: none;\">{3}</span>";
$l['updates_table_info_installed'] = "instalat";
$l['updates_table_info_process'] = "<small>se instaleaz&#259;...</small><br><img src='../images/rolang/installing.gif' border='0'/>";
$l['updates_table_info_message_1'] = "Pentru a mai putea verifica eventuale actualiz&#259;ri de pe server-ul oficial va trebui s&#259; mai a&#351;tep&#355;i o perioad&#259; de timp. Au mai r&#259;mas ";
$l['updates_table_info_message_2'] = " minute &#351;i ";
$l['updates_table_info_message_3'] = " de secunde p&#226;n&#259; c&#226;nd butonul de verificare va ap&#259;rea &#238;n locul acestui mesaj.";
$l['updates_table_info_log'] = "Actualizare reu&#351;it&#259;";
$l['updates_table_info_log_desc'] = "Procesul de actualizare la versiunea {1} s-a realizat cu succes! Datele au fost desc&#259;rcate de pe server cu o vitez&#259; medie de {2}/secund&#259; timp de {3} secunde.";
$l['updates_table_errors_name'] = "List&#259; cu posibile erori la instalare";
$l['updates_table_errors_show'] = "arat&#259;";
$l['updates_table_errors_hide'] = "ascunde";
$l['updates_table_errors_error'] = "Eroare";
$l['updates_table_errors_explication'] = "Explica&#355;ie";
$l['updates_table_errors_ver'] = "Nu po&#355;i instala o versiune mai veche dec&#226;t cea curent&#259;.";
$l['updates_table_errors_wri'] = "Nu exist&#259; permisiuni de scriere pe server.";
$l['updates_table_errors_url'] = "Adresa web a pachetului de date nu este corect&#259;.";
$l['updates_table_errors_ext'] = "Pachetul dorit exist&#259; deja pe server.";
$l['updates_table_errors_pcl'] = "Fi&#351;ierul <b>inc/class_pclzip.php</b> nu exist&#259; pe server.";
$l['updates_table_errors_zip'] = "Eroare ap&#259;rut&#259; la dezarhivarea pachetului de date.";
$l['updates_table_errors_del'] = "Arhiva nu a putut fi &#351;tears&#259; din sistem.";
$l['updates_table_template_name'] = "Setul de imagini ale traducerii";
$l['updates_table_template_describe'] = "Descriere";
$l['updates_table_template_select'] = "Selector";
$l['updates_table_template_help'] = "<p align=\"justify\" style=\"margin: 0px\">Setul de imagini este foarte important pentru o tem&#259;. Asta pentru c&#259; dac&#259; folosi&#355;i o traducere &#238;mpreun&#259; cu o tem&#259; ce nu are un set de imagini corespunz&#259;tor traducerii dvs., atunci imaginile de pe forum nu vor putea fi vizualizate. Cu ajutorul tabelului de mai jos pute&#355;i rezolva foarte simplu aceast&#259; problem&#259;. &#206;n cazul &#238;n care &#351;i dup&#259; copierea setului de imagini, problema persist&#259;, v&#259; rug&#259;m s&#259; ne contacta&#355;i. V&#259; mul&#355;umim pentru &#238;n&#355;elegere!</p>";
$l['updates_table_template_description'] = "<p align=\"justify\" style=\"margin: 0px\">Verific&#259; existen&#355;a setului de imagini ale traducerii pentru o tem&#259; din lista din dreapta acestui text.</p>";
$l['updates_table_template_list'] = "<option value=\"{1}\">Alege din lista de mai jos!</option><option value=\"{2}\">Verific&#259;-le pe toate!</option>{3}";
$l['updates_table_template_answer'] = "<p align=\"justify\" style=\"margin: 0px\">&#206;n momentul &#238;n care alegi o tem&#259; din lista de mai sus, modificarea va vedea dac&#259; pentru tema aleas&#259; setul de imagini se afl&#259; &#238;n directorul corespunz&#259;tor. Dac&#259; nu exist&#259;, atunci se adaug&#259; setul &#238;n acest director.</p>";
$l['updates_table_template_added'] = "&lt;Tema:{1}&gt;Set de imagini ad&#259;ugat.&lt;/Tema&gt;\n";
$l['updates_table_template_error'] = "&lt;Tema:{1}&gt;Eroare la copierea setului de imagini!&lt;/Tema&gt;\n";
$l['updates_table_template_exist'] = "&lt;Tema:{1}&gt;Set de imagini deja existent!&lt;/Tema&gt;\n";
$l['updates_table_template_nothemes'] = "Nu ai nicio tem&#259; pe forum, &#238;n acest moment!";
$l['updates_table_template_noid'] = "ID-ul temei alese nu exist&#259; &#238;n sistem!";
$l['updates_table_template_nonumber'] = "ID-ul temei alese nu este un num&#259; r!";
// Actualizare modificare
$l['updates_upgrade_title'] = "Modificare";
$l['updates_upgrade_description'] = "Pagin&#259; &#238;n care pot fi administrate diverse actualiz&#259;ri ale acestei modific&#259;ri.";
$l['updates_upgrade_notice'] = "&#206;nainte de a efectua o actualizare a modific&#259;rii v&#259; rug&#259;m s&#259; face&#355;i un <a href=\"index.php?module=tools-backupdb&action=backup\" target=\"_blank\">backup al bazei de date</a> a forumului dvs. V&#259; mul&#355;umim pentru &#238;n&#355;elegere!";
$l['updates_upgrade_table_title'] = "Versiuni disponibile";
$l['updates_upgrade_table_name'] = "Actualizare";
$l['updates_upgrade_table_controls'] = "Controale";
$l['updates_upgrade_table_option_run'] = "Ruleaz&#259; acum";
$l['updates_upgrade_table_option_del'] = "&#350;terge intrarea";
$l['updates_upgrade_table_without'] = "Nu exist&#259; versiuni disponibile pe server p&#226;n&#259; &#238;n acest moment.";
$l['updates_upgrade_run_confirm'] = "Confirmi ac&#355;iunea de actualizare a modific&#259;rii?";
$l['updates_upgrade_run_file'] = "Actualizarea aleas&#259; nu exist&#259; pe server.";
$l['updates_upgrade_run_error'] = "Actualizarea modific&#259;rii nu s-a putut realiza cu succes.";
$l['updates_upgrade_run_success'] = "Actualizarea modific&#259;rii {1} s-a realizat cu succes.";
$l['updates_upgrade_delete_confirm'] = "Confirmi ac&#355;iunea de &#351;tergere a fi&#351;ierului ales?";
$l['updates_upgrade_delete_success'] = "Fi&#351;ierul {1} a fost &#351;ters cu succes de pe server.";
$l['updates_upgrade_delete_error'] = "Fi&#351;ierul {1} nu a putut fi &#351;ters de pe server.";
$l['updates_import_localfile'] = "Fi&#351;ier local";
$l['updates_import_url'] = "URL";
$l['updates_import_module'] = "Adaug&#259; o actualizare";
$l['updates_import_from'] = "Import&#259; din";
$l['updates_import_button'] = "Adauga actualizare";
$l['updates_import_from_desc'] = "Selecteaz&#259; o actualizare pe care dore&#351;ti s&#259; o urci pe server. Po&#355;i alege un fi&#351;ier din calculatorul t&#259;u sau unul de la o anumit&#259; adres&#259; URL.";
$l['updates_import_error_make'] = "Cel pu&#355;in o eroare a ap&#259;rut la scrierea datelor pe disc.";
$l['updates_import_missing_url'] = "Te rug&#259;m s&#259; introduci o actualizare pentru a fi importat&#259;!";
$l['updates_import_local_file'] = "Fi&#351;ierul ales nu a putut fi deschis. Oare exist&#259;? Te rug&#259;m s&#259; verifici &#351;i s&#259; &#238;ncerci din nou.";
$l['updates_import_uploadfailed'] = "Eroare la &#238;nc&#259;rcare. Te rug&#259;m s&#259; re&#238;ncerci!";
$l['updates_import_uploadfailed_detail'] = "Detalii eroare :";
$l['updates_import_uploadfailed_php1'] = "Fi&#351;ierul &#238;nc&#259;rcat a dep&#259;&#351;it directiva 'upload_max_filesize' din php.ini. Te rug&#259;m s&#259; contactezi de urgen&#355;&#259; un administrator.";
$l['updates_import_uploadfailed_php2'] = "Fi&#351;ierul este prea mare pentru a fi urcat pe server.";
$l['updates_import_uploadfailed_php3'] = "Fi&#351;ierul ales pentru &#238;nc&#259;rcare nu a putut fi urcat &#238;n totalitate.";
$l['updates_import_uploadfailed_php4'] = "Niciun fi&#351;ier nu a fost &#238;nc&#259;rcat pe server!";
$l['updates_import_uploadfailed_php6'] = "Directorul temporar lipse&#351;te de pe server.";
$l['updates_import_uploadfailed_php7'] = "Nu s-a putut scrie fi&#351;ierul pe disc. Te rug&#259;m s&#259; contactezi c&#226;t mai repede un administrator.";
$l['updates_import_uploadfailed_phpx'] = "S-a returnat urm&#259;toarea eroare: {1}. Te rug&#259;m s&#259; contactezi un administrator c&#226;t mai repede cu putin&#355;&#259;.";
$l['updates_import_uploadfailed_lost'] = "Fi&#351;ierul specificat nu a putut fi g&#259;sit pe server!";
$l['updates_import_uploadfailed_nocontents'] = "Nu exist&#259; con&#355;inut pentru fi&#351;ierul ales pentru a fi urcat sau pentru adresa URL specificat&#259;.";
$l['updates_import_success'] = "Actualizarea selectat&#259; a fost importat&#259; cu succes. Ar trebui s&#259; o vezi &#238;n tabelul de mai jos.";
// LOG-URI
$l['logs_title'] = "Jurnal";
$l['logs_description'] = "Aici po&#355;i vedea tot con&#355;inutul jurnalului acestei aplica&#355;ii.";
$l['logs_table_username'] = "Nume de utilizator";
$l['logs_table_type'] = "Tip";
$l['logs_table_data'] = "Mesaj";
$l['logs_table_date'] = "Data";
$l['logs_table_actions'] = "Ac&#355;iuni";
$l['logs_table_options_delete'] = "&#350;terge intrarea";
$l['logs_table_options_delete_invalid'] = "Jurnalul specificat pentru a fi &#351;tears nu exist&#259; &#238;n baza de date.";
$l['logs_table_options_delete_confirm'] = "Confirmi ac&#355;iunea de &#351;tergere a jurnalului cu id-ul <b>{1}</b> din sistem?";
$l['logs_table_options_delete_deleted'] = "Ac&#355;iunea de &#351;tergere a jurnalului cu id-ul {1} s-a realizat cu succes.";
$l['logs_table_without'] = "Con&#355;inutul jurnalului acestei modific&#259;ri este gol.";
$l['logs_table_prune_title'] = "&#350;terge r&#226;nduri din jurnal";
$l['logs_table_prune_field'] = "C&#226;mp";
$l['logs_table_prune_test'] = "Test";
$l['logs_table_prune_value'] = "Valoare";
$l['logs_prune_help'] = "Ajutor";
$l['logs_prune_help_close'] = "&#206;nchide";
$l['logs_prune_help_content'] = "Utiliz&#226;nd condi&#355;iile de mai jos pute&#355;i filtra foarte bine ce r&#226;nduri din jurnal le vre&#355;i sau nu &#351;terse. C&#226;mpurile valide, ce le pute&#355;i folosi pentru filtrare sunt urm&#259;toarele : \"user id\", \"type\", \"data\" &#351;i \"date\". Po&#355;i ad&#259;uga c&#226;te condi&#355;ii vrei. Nu exist&#259; o limit&#259;. Pentru a mai ad&#259;uga o condi&#355;ie va trebui s&#259; dai un clic pe imaginea din dreapta primului r&#226;nd. De asemenea, dac&#259; ai ad&#259;ugat prea multe condi&#355;ii, le po&#355;i scoate r&#226;nd pe r&#226;nd ap&#259;s&#226;nd tot pe imaginea din drepta.<br/><br/>
Fiecare condi&#355;ie are 3 p&#259;r&#355;i:<br/><br/>
<b>C&#226;mp</b>: reprezint&#259; numele c&#226;mpului din baza de date asupra c&#259;ruia dore&#351;ti s&#259; aplici un filtru. Fiecare c&#226;mp are un tip specific &#351;i necesit&#259; un anumit tip de valoare.
<ul>
	<li>ID utilizator : intreg</li>
	<li>Tip jurnal: text</li>
	<li>Mesaj jurnal: text</li>
	<li>Data: data</li>
</ul><br/>
<b>Test</b>: reprezint&#259; operatorul ales pentru a realiza compara&#355;ia dintre c&#226;mp &#351;i valoarea introdus&#259; &#238;n condi&#355;ie. Unele teste se aplic&#259; pentru numere, altele pentru texte, de aceea trebuie s&#259; fi&#355;i aten&#355;i la c&#226;mpul ales!
<ul>
<li>operatori care se aplic&#259; la <u>numere</u><ul>
	<li>== / != : se testeaz&#259; dac&#259; cele dou&#259; valori sunt egale sau diferite.</li>
	<li>Nul / Diferit de Nul : se testeaz&#259; dac&#259; o valoarea este nul&#259; sau nu.</li>
	<li>Gol / Diferit de Gol : se testeaz&#259; dac&#259; un anumit c&#226;mp este gol sau nu.</li>
	<li>&lt; / &gt; : se testeaz&#259; dac&#259; o valoare este mai mic&#259; sau mai mare dec&#226;t alta.</li>
	<li>&#206;n / &#206;nafar&#259; : se testeaz&#259; dac&#259; o valoare din cadrul unui c&#226;mp este inclus&#259; sau nu &#238;n alta. (lista de valori va avea ca &#351;i separator virgula)</li>
	<li>Ca &#351;i / Diferit de : se testeaz&#259; dac&#259; o valoare seam&#259;n&#259; cu alta.</li>
</ul></li>
<li>operatori care se aplic&#259; la <u>texte</u> (&#351;iruri de caractere)<ul>
	<li>== / != : se testeaz&#259; dac&#259; cele dou&#259; valori sunt egale sau diferite.</li>
	<li>Nul / Diferit de Nul : se testeaz&#259; dac&#259; o valoarea este nul&#259; sau nu.</li>
	<li>Gol / Diferit de Gol : se testeaz&#259; dac&#259; un anumit c&#226;mp este gol sau nu.</li>
	<li>&lt; / &gt; : se testeaz&#259; dac&#259; o valoare este mai mic&#259; sau mai mare dec&#226;t alta. Cum suntem la &#351;iruri de caractere compara&#355;ie se face &#238;n func&#355;ie de alfabet.</li>
	<li>&#206;n / &#206;nafar&#259; : se testeaz&#259; dac&#259; o valoare din cadrul unui c&#226;mp al bazei de date este inclus &#238;n lista de valori definit&#259; de utilizator (valorile fiind desp&#259;r&#355;ite prin virgul&#259;). Dac&#259; vrei s&#259; incluzi &#351;i virgula ca &#351;ir de caractere, atunci va trebui ca &#238;ntreg &#351;irul s&#259; fie cuprins &#238;ntre ghilimele.</li>
	<li>Ca &#351;i / Diferit de : se testeaz&#259; dac&#259; valoarea c&#226;mpului se potrive&#351;te cu ceea ce ai definit tu. Po&#355;i utiliza semnul asterisk (*) pentru un pattern. De exemplu: *abc</li>
</ul></li>
<li>operatori care se aplic&#259; la <u>date</u><ul>
	<li>== / != : se testeaz&#259; dac&#259; cele dou&#259; valori sunt egale sau diferite.</li>
	<li>Nul / Diferit de Nul : se testeaz&#259; dac&#259; o valoarea este nul&#259; sau nu.</li>
	<li>Gol / Diferit de Nul : se testeaz&#259; dac&#259; un anumit c&#226;mp este gol sau nu.</li>
	<li>&lt; / &gt; : se testeaz&#259; dac&#259; o valoare este mai mic&#259; sau mai mare dec&#226;t alta.</li>
	<li>&#206;n / &#206;nafar&#259; : se testeaz&#259; dac&#259; o valoare din cadrul unui c&#226;mp este inclus&#259; sau nu &#238;n alta. (lista de valori va avea ca &#351;i separator virgula)</li>
	<li>Ca &#351;i / Diferit de : se testeaz&#259; dac&#259; o valoare seam&#259;n&#259; cu alta.</li>
</ul></li>
</ul><br/>
<b>Valoare</b>: reprezint&#259; valoarea pe care vrei s&#259; s&#259; o compari cu cea a c&#226;mpului din baza de date.
<ul>
	<li>poate fi <u>num&#259;r</u> :<ul>
		<li>== / != : o valoare numeric&#259; (De exemplu : 1 sau 3)</li>
		<li>Nu / Diferit de Nul : f&#259;r&#259; valoare</li>
		<li>Gol / Diferit de Gol : f&#259;r&#259; valoare</li>
		<li>&lt; / &gt; : o valoare numeric&#259; (De exemplu : 0 sau 100)</li>
		<li>&#206;n / &#206;nafar&#259; : o list&#259; cu valori numerice (De exemplu : 1,2,3,4).</li>
		<li>Ca &#351;i / Diferit de : nu se poate aplica</li>
	</ul></li>
	<li>poate fi <u>text</u> :<ul>
		<li>== / != : un &#351;ir de caractere (De exemplu: abcd sau hydf)</li>
		<li>Nul / Diferit de Nul : f&#259;r&#259; valoare</li>
		<li>Gol / Diferit de Gol : f&#259;r&#259; valoare</li>
		<li>&lt; / &gt; : un &#351;ir de caractere (De exemplu: abcd or hydf)</li>
		<li>&#206;n / &#206;nafar&#259; : o list&#259; cu &#351;iruri de caractere (De exemplu: abcd,hydf,\"abcd,hydf\").</li>
		<li>Ca &#351;i / Diferit de : un anumit pattern (De exemplu: abc*f)</li>
	</ul></li>
	<li>poate fi <u>dat&#259;</u> :<ul>
		<li>Este important de &#351;tiut faptul c&#259; o dat&#259; are dou&#259; formate:<ul>
			<li>una absolut&#259;: adic&#259; de genul zi-luna-an sau luna/zi/an.</li>
			<li>&#351;i una relativ&#259;: ea reprezint&#259; o dat&#259; relativ&#259; fa&#355;&#259; de cea curent&#259;. De exemplu: -1 week (&#238;nseamn&#259; cu o s&#259;pt&#259;m&#226;n&#259; &#238;n urm&#259;), +2 week (&#238;nseamn&#259; peste dou&#259; s&#259;pt&#259;m&#226;ni de acum), today (ziua de ast&#259;zi). Pentru a nu &#238;nt&#226;mpina probleme &#238;n utilizarea datelor relative, v&#259; &#238;nvit&#259;m s&#259; citi&#355;i informa&#355;iile de <a href=\"http://php.net/manual/en/function.strtotime.php\">aici</a>.</li>
		</ul></li>
		<li>== / != : o dat&#259; (De exemplu: 03-12-2010)</li>
		<li>Nul / Diferit de Nul : f&#259;r&#259; valoare</li>
		<li>Gol / Diferit de Gol : f&#259;r&#259; valoare</li>
		<li>&lt; / &gt; : o dat&#259; (De exemplu: +1 month)</li>
		<li>&#206;n / &#206;nafar&#259; : o list&#259; cu date (De exemplu: 02-12-2011,04-12-2011).</li>
		<li>Ca &#351;i / Diferit de : nu se poate aplica</li>
	</ul></li>
</ul>";
$l['logs_prune_error_nocondition'] = "Nu ai definit nicio condi&#355;ie pentru a putea &#238;ncepe procesul de &#351;tergere.";
$l['logs_prune_succes_one'] = "Un num&#259;r de <b>un r&#226;nd</b> din jurnal a fost &#351;ters cu succes.";
$l['logs_prune_succes_more'] = "Un num&#259;r de <b>{1} r&#226;nduri</b> din jurnal au fost &#351;terse cu succes.";
// STATISTICI
$l['stats_title'] = "Statistici";
$l['stats_description'] = "C&#226;teva date statistice legate de aceast&#259; aplica&#355;ie &#351;i de tot ceea ce implic&#259; ea.";
$l['stats_error_deny'] = "Interogarea serverului de date a e&#351;uat!";
$l['stats_error_deny_desc'] = "Procesul de interogare al server-ul de date a e&#351;uat. Acest lucru se poate &#238;nt&#226;mpla din mai multe motive : fie server-ul nu este activ, fie adresa sa nu este una corect&#259;."; 
$l['stats_meters_title'] = "Informa&#355;ii despre server-ul";
$l['stats_meters_your_server'] = "t&#259;u";
$l['stats_meters_our_server'] = "nostru";
$l['stats_general_title'] = "Statistici generale";
$l['stats_general_info'] = "Informa&#355;ie";
$l['stats_general_value'] = "Valoare";
$l['stats_general_number_hosts'] = "Num&#259;r total de domenii";
$l['stats_general_number_mybb'] = "Num&#259;r total de versiuni MyBB diferite";
$l['stats_general_number_php'] = "Num&#259;r total de versiuni PHP diferite";
$l['stats_general_max_mybb'] = "Cea mai nou&#259; versiune de MyBB folosit&#259;";
$l['stats_general_min_mybb'] = "Cea mai veche versiune de MyBB folosit&#259;";
$l['stats_general_max_php'] = "Cea mai nou&#259; versiune de PHP folosit&#259;";
$l['stats_general_min_php'] = "Cea mai veche versiune de PHP folosit&#259;";
$l['stats_general_number_requests'] = "Num&#259;r total de interog&#259;ri ale server-ului";
$l['stats_general_total_time'] = "Interog&#259;rile au fost efectuate &#238;ntr-un interval de";
$l['stats_general_last_update'] = "Ultima actualizare a datelor de pe server";
$l['stats_generated_in'] = "Date statistice generate &#238;n <b>{1}</b> milisecunde.";
// ECHIPA
$l['team_title'] = "Echip&#259;";
$l['team_description'] = "Detalii despre echipa care a lucrat la acest proiect.";
$l['team_page_description'] = "<h4>Despre acest proiect</h4>
<p align=\"justify\">Proiectul de traducere a platformei MyBB &#238;n limba rom&#226;n&#259; a fost &#238;nceput de <i>Ovidiu</i> acum c&#226;&#355;iva ani. El a tradus &#238;n decursul unei perioade destul de mari de timp partea de interfa&#355;&#259; cu utilizatorul a platformei. &#206;ns&#259; acesta avea s&#259; fie doar &#238;nceputul...</p><p align=\"justify\">&#206;ncep&#226;nd cu anul 2010 am &#238;nceput s&#259; traducem toat&#259; platforma. La &#238;nceput <i>Mihai</i> a fost cel care a ad&#259;ugat diacritice traducerii, iar <i>Radu</i> &#351;i <i>&#350;tefan</i> s-au ocupat de partea de administrare. Traducerea este &#238;n acest moment &#238;n propor&#355;ie de {1}% terminat&#259;.</p>
<p align=\"justify\">Acest lucru nu se putea realiza f&#259;r&#259; o dorin&#355;&#259; &#351;i un spirit de munc&#259; cum rar s-a mai v&#259;zut. &#354;in pe aceast&#259; cale s&#259; le mul&#355;umesc tuturor celor care au f&#259;cut posibil&#259; existen&#355;a acestei traduceri c&#226;t &#351;i celor care au contribuit la testarea &#351;i raportarea unor probleme legate de exprimare / gramatic&#259;. Nu trebuie neglijat faptul c&#259; ace&#351;ti oameni au practicat o munc&#259; voluntar&#259;. V&#259; mul&#355;umesc &#238;nc&#259; odat&#259;!</p>
<p align=\"right\">Cu respect, <i>Surdeanu Mihai</i>.</p>
<table><tr>
<td><form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">
<input type=\"hidden\" name=\"cmd\" value=\"_donations\">
<input type=\"hidden\" name=\"business\" value=\"office@mybb.ro\">
<input type=\"hidden\" name=\"lc\" value=\"RO\">
<input type=\"hidden\" name=\"item_name\" value=\"MyBB Romania - Donation\">
<input type=\"hidden\" name=\"no_note\" value=\"0\">
<input type=\"hidden\" name=\"currency_code\" value=\"EUR\">
<input type=\"hidden\" name=\"bn\" value=\"PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest\">
<input type=\"image\" src=\"https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif\" border=\"0\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online!\">
<img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\">
</form></td>
<td><small>Dac&#259; &#238;&#355;i place acest&#259; modificare te invit&#259;m s&#259; faci o dona&#355;ie c&#259;tre MyBB Rom&#226;nia. &#206;&#355;i mul&#355;umim mult pentru gestul f&#259;cut.</small></td>
</tr></table>";
$l['team_table_name'] = "Nume &#351;i Prenume";
$l['team_table_function'] = "Func&#355;ie";
$l['team_table_function_developer'] = "dezvoltator";
$l['team_table_function_translator'] = "traduc&#259;tor";
$l['team_table_contribution'] = "Contribu&#355;ie";
$l['team_table_contribution_frontend'] = "Front End";
$l['team_table_contribution_admincp'] = "Admin CP";
$l['team_table_contribution_plugin'] = "Plugin";
$l['team_license_name'] = "Licen&#355;&#259;";
$l['team_license_version'] = "<i>Versiune Licen&#355;&#259; : {1}</i>";
$l['team_license_text'] = "<p align=\"justify\" style=\"margin: 0px\">At&#226;t traducerea c&#226;t &#351;i aceast&#259; modificare intr&#259; sub inciden&#355;a aceleia&#351;i licen&#355;e. Licen&#355;a cu care vine traducerea ne confer&#259; dreptul de autori asupra ei. Traducerea apar&#355;ine <a href=\"http://mybb.ro\">MyBB Rom&#226;nia</a> &#351;i nu poate fi modificat&#259; sau redistribuit&#259; dec&#226;t cu acordul celor care au realizat-o. Mai multe date legate de licen&#355;&#259; pot fi vizualizate &#238;n cadrul fi&#351;ierul <b>License.pdf</b> din arhiv&#259;.</p>";
// ADMINISTRARE
$l['admin_title'] = "Administrare";
$l['admin_version_title'] = "Versiuni";
$l['admin_version_description'] = "Prin intermediul acestei pagini pute&#355;i s&#259; v&#259; face&#355;i un server pentru aplica&#355;ia de fa&#355;&#259;.";
$l['admin_table_title'] = "Versiuni de traduceri";
$l['admin_table_without'] = "Momentan nu exist&#259; nicio versiune de traducere &#238;n baza de date.";
$l['admin_table_user'] = "Nume de utilizator";
$l['admin_table_active'] = "Versiune activ&#259;?";
$l['admin_table_version'] = "Versiune";
$l['admin_table_name'] = "Nume traducere";
$l['admin_table_compatibility'] = "Compatibil cu versiunile";
$l['admin_table_date'] = "Data public&#259;rii";
$l['admin_table_actions'] = "Ac&#355;iuni";
$l['admin_table_options_delete'] = "&#350;terge versiunea";
$l['admin_table_options_edit'] = "Editeaz&#259; versiunea";
$l['admin_table_options_status'] = "Schimb&#259; status";
$l['admin_table_options_error'] = "Cererea efectuat&#259; nu este autentic&#259;!";
$l['admin_table_options_status_noperm'] = "Nu ai permisiuni pentru a schimba status-ul acestei versiuni de traducere.";
$l['admin_table_options_status_changed'] = "Status-ul versiunii cu id-ul <b>{1}</b> a fost schimbat cu succes.";
$l['admin_table_options_status_confirm'] = "Confirmi ac&#355;iunea de schimbare a status-ului versiunii cu id-ul <b>{1}</b> din sistem?";
$l['admin_table_options_delete_invalid'] = "Versiunea de traducere specificat&#259; pentru a fi &#351;tears&#259; nu exist&#259; &#238;n baza de date.";
$l['admin_table_options_delete_deleted'] = "Versiunea de traducere cu id-ul <b>{1}</b> a fost &#351;tears&#259; cu succes din baza de date.";
$l['admin_table_options_delete_confirm'] = "Confirmi ac&#355;iunea de &#351;tergere a versiunii cu id-ul <b>{1}</b> din sistem?";
$l['admin_legend_message'] = "Mesaj";
$l['admin_actions_help_content'] = "Utiliz&#226;nd condi&#355;iile de mai jos pute&#355;i alege foarte u&#351;or ce ac&#355;iuni dori&#355;i s&#259; face&#355;i cu versiunile de traduceri existente &#238;n baza de date. C&#226;mpurile valide, ce le pute&#355;i folosi pentru filtrare sunt urm&#259;toarele : \"aid\", \"uid\", \"active\", \"for\", \"for_version\", \"for_compatibility\", \"archive_lang_size\", \"archive_img_size\" &#351;i \"date\". Po&#355;i ad&#259;uga c&#226;te condi&#355;ii vrei. Nu exist&#259; niciun fel de limitare de acest gen. Pentru a mai ad&#259;uga o condi&#355;ie va trebui s&#259; dai un clic pe imaginea din dreapta primului r&#226;nd. De asemenea, dac&#259; ai ad&#259;ugat prea multe condi&#355;ii, le po&#355;i scoate r&#226;nd pe r&#226;nd ap&#259;s&#226;nd tot pe imaginea din drepta.<br/><br/>
Fiecare condi&#355;ie are 3 p&#259;r&#355;i:<br/><br/>
<b>C&#226;mp</b>: reprezint&#259; numele c&#226;mpului din baza de date asupra c&#259;ruia dore&#351;ti s&#259; aplici un filtru. Fiecare c&#226;mp are un tip specific &#351;i necesit&#259; un anumit tip de valoare.
<ul>
	<li>ID versiune : &#238;ntreg</li>
	<li>ID utilizator : &#238;ntreg</li>
	<li>Status versiune : &#238;ntreg</li>
	<li>Nume versiune : text</li>
	<li>Versiune : &#238;ntreg</li>
	<li>Compatibilitate : &#238;ntreg</li>
	<li>Dimensiune arhiv&#259; lingvistic&#259; : &#238;ntreg</li>
	<li>Dimensiune arhiv&#259; set de imagini : &#238;ntreg</li>
	<li>Dat&#259; : data</li>
</ul><br/>
<b>Test</b>: reprezint&#259; operatorul ales pentru a realiza compara&#355;ia dintre c&#226;mp &#351;i valoarea introdus&#259; &#238;n condi&#355;ie. Unele teste se aplic&#259; pentru numere, altele pentru texte, de aceea trebuie s&#259; fi&#355;i aten&#355;i la c&#226;mpul ales!
<ul>
<li>operatori care se aplic&#259; la <u>numere</u><ul>
	<li>== / != : se testeaz&#259; dac&#259; cele dou&#259; valori sunt egale sau diferite.</li>
	<li>Nul / Diferit de Nul : se testeaz&#259; dac&#259; o valoarea este nul&#259; sau nu.</li>
	<li>Gol / Diferit de Gol : se testeaz&#259; dac&#259; un anumit c&#226;mp este gol sau nu.</li>
	<li>&lt; / &gt; : se testeaz&#259; dac&#259; o valoare este mai mic&#259; sau mai mare dec&#226;t alta.</li>
	<li>&#206;n / &#206;nafar&#259; : se testeaz&#259; dac&#259; o valoare din cadrul unui c&#226;mp este inclus&#259; sau nu &#238;n alta. (lista de valori va avea ca &#351;i separator virgula)</li>
	<li>Ca &#351;i / Diferit de : se testeaz&#259; dac&#259; o valoare seam&#259;n&#259; cu alta.</li>
</ul></li>
<li>operatori care se aplic&#259; la <u>texte</u> (&#351;iruri de caractere)<ul>
	<li>== / != : se testeaz&#259; dac&#259; cele dou&#259; valori sunt egale sau diferite.</li>
	<li>Nul / Diferit de Nul : se testeaz&#259; dac&#259; o valoarea este nul&#259; sau nu.</li>
	<li>Gol / Diferit de Gol : se testeaz&#259; dac&#259; un anumit c&#226;mp este gol sau nu.</li>
	<li>&lt; / &gt; : se testeaz&#259; dac&#259; o valoare este mai mic&#259; sau mai mare dec&#226;t alta. Cum suntem la &#351;iruri de caractere compara&#355;ie se face &#238;n func&#355;ie de alfabet.</li>
	<li>&#206;n / &#206;nafar&#259; : se testeaz&#259; dac&#259; o valoare din cadrul unui c&#226;mp al bazei de date este inclus &#238;n lista de valori definit&#259; de utilizator (valorile fiind desp&#259;r&#355;ite prin virgul&#259;). Dac&#259; vrei s&#259; incluzi &#351;i virgula ca &#351;ir de caractere, atunci va trebui ca &#238;ntreg &#351;irul s&#259; fie cuprins &#238;ntre ghilimele.</li>
	<li>Ca &#351;i / Diferit de : se testeaz&#259; dac&#259; valoarea c&#226;mpului se potrive&#351;te cu ceea ce ai definit tu. Po&#355;i utiliza semnul asterisk (*) pentru un pattern. De exemplu: *abc</li>
</ul></li>
<li>operatori care se aplic&#259; la <u>date</u><ul>
	<li>== / != : se testeaz&#259; dac&#259; cele dou&#259; valori sunt egale sau diferite.</li>
	<li>Nul / Diferit de Nul : se testeaz&#259; dac&#259; o valoarea este nul&#259; sau nu.</li>
	<li>Gol / Diferit de Nul : se testeaz&#259; dac&#259; un anumit c&#226;mp este gol sau nu.</li>
	<li>&lt; / &gt; : se testeaz&#259; dac&#259; o valoare este mai mic&#259; sau mai mare dec&#226;t alta.</li>
	<li>&#206;n / &#206;nafar&#259; : se testeaz&#259; dac&#259; o valoare din cadrul unui c&#226;mp este inclus&#259; sau nu &#238;n alta. (lista de valori va avea ca &#351;i separator virgula)</li>
	<li>Ca &#351;i / Diferit de : se testeaz&#259; dac&#259; o valoare seam&#259;n&#259; cu alta.</li>
</ul></li>
</ul><br/>
<b>Valoare</b>: reprezint&#259; valoarea pe care vrei s&#259; s&#259; o compari cu cea a c&#226;mpului din baza de date.
<ul>
	<li>poate fi <u>num&#259;r</u> :<ul>
		<li>== / != : o valoare numeric&#259; (De exemplu : 1 sau 3)</li>
		<li>Nu / Diferit de Nul : f&#259;r&#259; valoare</li>
		<li>Gol / Diferit de Gol : f&#259;r&#259; valoare</li>
		<li>&lt; / &gt; : o valoare numeric&#259; (De exemplu : 0 sau 100)</li>
		<li>&#206;n / &#206;nafar&#259; : o list&#259; cu valori numerice (De exemplu : 1,2,3,4).</li>
		<li>Ca &#351;i / Diferit de : nu se poate aplica</li>
	</ul></li>
	<li>poate fi <u>text</u> :<ul>
		<li>== / != : un &#351;ir de caractere (De exemplu: abcd sau hydf)</li>
		<li>Nul / Diferit de Nul : f&#259;r&#259; valoare</li>
		<li>Gol / Diferit de Gol : f&#259;r&#259; valoare</li>
		<li>&lt; / &gt; : un &#351;ir de caractere (De exemplu: abcd or hydf)</li>
		<li>&#206;n / &#206;nafar&#259; : o list&#259; cu &#351;iruri de caractere (De exemplu: abcd,hydf,\"abcd,hydf\").</li>
		<li>Ca &#351;i / Diferit de : un anumit pattern (De exemplu: abc*f)</li>
	</ul></li>
	<li>poate fi <u>dat&#259;</u> :<ul>
		<li>Este important de &#351;tiut faptul c&#259; o dat&#259; are dou&#259; formate:<ul>
			<li>una absolut&#259;: adic&#259; de genul zi-luna-an sau luna/zi/an.</li>
			<li>&#351;i una relativ&#259;: ea reprezint&#259; o dat&#259; relativ&#259; fa&#355;&#259; de cea curent&#259;. De exemplu: -1 week (&#238;nseamn&#259; cu o s&#259;pt&#259;m&#226;n&#259; &#238;n urm&#259;), +2 week (&#238;nseamn&#259; peste dou&#259; s&#259;pt&#259;m&#226;ni de acum), today (ziua de ast&#259;zi). Pentru a nu &#238;nt&#226;mpina probleme &#238;n utilizarea datelor relative, v&#259; &#238;nvit&#259;m s&#259; citi&#355;i informa&#355;iile de <a href=\"http://php.net/manual/en/function.strtotime.php\">aici</a>.</li>
		</ul></li>
		<li>== / != : o dat&#259; (De exemplu: 03-12-2010)</li>
		<li>Nul / Diferit de Nul : f&#259;r&#259; valoare</li>
		<li>Gol / Diferit de Gol : f&#259;r&#259; valoare</li>
		<li>&lt; / &gt; : o dat&#259; (De exemplu: +1 month)</li>
		<li>&#206;n / &#206;nafar&#259; : o list&#259; cu date (De exemplu: 02-12-2011,04-12-2011).</li>
		<li>Ca &#351;i / Diferit de : nu se poate aplica</li>
	</ul></li>
</ul>";
$l['admin_actions_title'] = "Ac&#355;iuni &#238;n mas&#259;";
$l['admin_actions_field'] = "C&#226;mp";
$l['admin_actions_test'] = "Test";
$l['admin_actions_value'] = "Valoare";
$l['admin_actions_search'] = "Caut&#259; versiuni";
$l['admin_actions_change'] = "Schimb&#259; status";
$l['admin_actions_delete'] = "&#350;terge versiuni";
$l['admin_actions_switch'] = "&#206;n dreapta acestui text g&#259;se&#351;ti o list&#259; cu ac&#355;iunile posibile care pot fi efectuate cu ajutorul acestui formular. Alege una!";
$l['admin_actions_error_nocondition'] = "Nu ai definit nicio condi&#355;ie pentru a putea procesa ac&#355;iunea aleas&#259;.";
$l['admin_actions_delete_no'] = "Nu s-a g&#259;sit nici m&#259;car o versiune de traducere care s&#259; poat&#259; fi &#351;tears&#259; din sistem.";
$l['admin_actions_delete_one'] = "O versiune de traducere a fost &#351;tears&#259; cu succes.";
$l['admin_actions_delete_more'] = "Unui num&#259;r de {1} versiuni de traduceri au fost &#351;terse cu succes.";
$l['admin_actions_change_no'] = "Nu s-a g&#259;sit nici m&#259;car o versiune de traducere c&#259;reia s&#259; i se schimbe status-ul.";
$l['admin_actions_change_one'] = "Unei versiuni de traducere i-a fost schimbat status-ul cu succes.";
$l['admin_actions_change_more'] = "Unui num&#259;r de {1} versiuni de traduceri i-au fost schimbate status-ul cu succes.";
$l['admin_actions_search_no'] = "Nu s-a g&#259;sit nicio versiune de traducere care s&#259; corespunda condi&#355;iilor tale.";
$l['admin_actions_search_success'] = "S-a g&#259;sit o versiune de traducere cu urm&#259;toarele specifica&#355;ii :<hr>Nume versiune : <i>{1}</i><br>Versiune : <i>{2}</i><br>Compatibil cu : <i>{3}</i><br>Leg&#259;tura c&#259;tre arhiva lingvistic&#259; : <a href=\"{4}\" target=\"_blank\">aici</a> (M&#259;rime arhiv&#259; : <i>{5}</i>)<br>Leg&#259;tura c&#259;tre arhiva setului de imagini : <a href=\"{6}\" target=\"_blank\">aici</a> (M&#259;rime arhiv&#259; : <i>{7}</i>)<br>";
$l['admin_tests_null'] = "Nul";
$l['admin_tests_null_no'] = "Diferit de Nul";
$l['admin_tests_empty'] = "Gol";
$l['admin_tests_empty_no'] = "Diferit de Gol";
$l['admin_tests_in'] = "&#206;n";
$l['admin_tests_in_no'] = "&#206;nafar&#259;";
$l['admin_tests_like'] = "Ca &#351;i";
$l['admin_tests_like_no'] = "Diferit de";
$l['admin_fields_userid'] = "ID utilizator";
$l['admin_fields_aid'] = "ID versiune";
$l['admin_fields_active'] = "Status versiune";
$l['admin_fields_name'] = "Nume versiune";
$l['admin_fields_version'] = "Versiune";
$l['admin_fields_compatibility'] = "Compatibilitate";
$l['admin_fields_size_link'] = "Dimensiune arhiv&#259; lingvistic&#259;";
$l['admin_fields_size_image'] = "Dimensiune arhiv&#259; set de imagini";
$l['admin_fields_size_date'] = "Dat&#259;";
$l['admin_button_submit'] = "Trimite";
$l['admin_button_reset'] = "Reseteaza";
// Adaugare versiune
$l['admin_add_title'] = "Adaug&#259; versiune";
$l['admin_add_description'] = "Prin intermediul formularului de mai jos po&#355;i ad&#259;uga o versiune de traducere pe serverul t&#259;u.";
$l['admin_form_name'] = "Nume versiune";
$l['admin_add_name_desc'] = "Introdu un nume pentru versiunea de traducere pe care dore&#351;ti s&#259; o adaugi.";
$l['admin_form_version'] = "Versiune traducere";
$l['admin_add_version_desc'] = "Versiunea traducerii trebuie s&#259; fie un num&#259;r &#238;ntreg, de 4 cifre. Exemplu : 1604.";
$l['admin_form_compatibility'] = "Compatibil cu";
$l['admin_add_compatibility_desc'] = "Cu ce versiuni de MyBB va fi compatibil&#259; aceast&#259; traducere? Dac&#259; se va introduce mai mult de o versiune, se va utiliza ca &#351;i separator virgula! Exemplu : 1604,1605.";
$l['admin_form_link'] = "Arhiva lingvistic&#259;";
$l['admin_add_link_desc'] = "Selecta&#355;i arhiva <b>zip</b> care urmeaz&#259; s&#259; fie folosit&#259; pe post de fi&#351;iere lingvistice.";
$l['admin_form_image'] = "Arhiva setului de imagini";
$l['admin_add_image_desc'] = "Selecta&#355;i arhiva <b>zip</b> care urmeaz&#259; s&#259; fie utilizat&#259; pe post de fi&#351;iere de imagine ale traducerii. <b>Dac&#259; dore&#351;ti s&#259; copiezi arhiva de la o versiune mai veche &#351;i s&#259; nu mai &#238;ncarci una pe server atunci va trebui s&#259; ape&#351;i <a href=\"#\" class=\"rolang_copy_toogle\" rel=\"rolang_copy_imageset\">aici</a></b>.";
$l['admin_form_copyset'] = "Copiaz&#259; setul de imagini de la versiunea";
$l['admin_add_copyset_desc'] = "Nu este nevoie s&#259; &#238;ncarci de fiecare dat&#259; o arhiv&#259; cu setul de imagini pe server. Po&#355;i copia arhiva de la o alt&#259; versiune de traducere mai veche.";
$l['admin_form_active'] = "Este activ&#259; versiunea?";
$l['admin_add_active_desc'] = "Dac&#259; versiunea nu este activ&#259; atunci ea nu poate fi desc&#259;rcat&#259; de c&#259;tre useri &#351;i instalat&#259; pe sistemul lor. Dac&#259; butonul \"Yes\" nu este selectabil atunci &#238;nseamn&#259; c&#259; nu ai permisiuni pentru a activa o versiune.";
// Procesare date
$l['admin_upload_exceeded'] = "Limita de &#238;nc&#259;rcare PHP a fost dep&#259;&#351;it&#259;. Valoarea maxim&#259; este {1}.";
$l['admin_upload_empty'] = "Exist&#259; cel pu&#355;in un c&#226;mp &#238;n cadrul formularului pe care l-ai trimis, necompletat! Va trebui s&#259;-l completezi.";
$l['admin_upload_wrongversion'] = "Versiunea sau compatibilitatea pe care ai introdus-o nu are un format corect.";
$l['admin_upload_wrongactive'] = "C&#226;mpul de activare trebuie s&#259; aib&#259; doar valorile 0 sau 1!";
$l['admin_upload_copyset'] = "Setul de imagini nu a putut fi copiat deoarece nu s-a g&#259;sit versiunea {1} &#238;n baza de date.";
$l['admin_upload_copyset1'] = "Setul de imagini nu a putut fi copiat deoarece nu s-a g&#259;sit pe server o versiune mai mic&#259; sau egal&#259; cu {1} pentru a putea copia arhiva.";
$l['admin_upload_copyset2'] = "C&#226;mpul op&#355;ional de copiere a unui set de imagini de la o alt&#259; versiune, nu are o valoare numeric&#259;.";
$l['admin_upload_error'] = "La &#238;nc&#259;rcarea fi&#351;ierului <b>{1}</b>, pe server, a ap&#259;rut eroarea urm&#259;toare : <b>{2}</b>.";
$l['admin_upload_success'] = "A fost ad&#259;ugat&#259; cu succes o nou&#259; versiune a traducerii &#238;n baza de date. Aceasta are id-ul <b>{1}</b>.";
// Editare versiune
$l['admin_edit_title'] = "Editeaz&#259; versiune";
$l['admin_edit_description'] = "Prin intermediul formularului de mai jos po&#355;i edita o versiune de traducere existent&#259; pe serverul t&#259;u.";
$l['admin_edit_name_desc'] = "Introdu noul nume al versiunii de traducere. &#206;l po&#355;i l&#259;sa <b>necompletat</b> dac&#259; dore&#351;ti <b>s&#259; nu-i modifici valoarea</b>.";
$l['admin_edit_version_desc'] = "Po&#355;i alege s&#259; la&#351;i necompletat acest c&#226;mp dac&#259; dore&#351;ti s&#259; nu modifici vechea valoare a versiunii.";
$l['admin_edit_compatibility_desc'] = "Specific&#259; noua compatibilitate a versiunii de fa&#355;&#259;. La fel ca &#351;i pentru c&#226;mpurile de mai sus, dac&#259; nu introduci nicio valoare atunci nu se va actualiza aceast&#259; informa&#355;ie.";
$l['admin_edit_link_desc'] = "Dac&#259; nu specifici nicio valoare pentru c&#226;mpul de mai jos atunci se va &#238;ncerca &#238;nc&#259;rcarea unei noi arhive, altfel se va p&#259;stra vechea arhiv&#259;.";
$l['admin_edit_image_desc'] = "Dac&#259; nu specifici nicio valoare pentru c&#226;mpul de mai jos atunci se va &#238;ncerca &#238;nc&#259;rcarea unei noi arhive, altfel se va p&#259;stra vechea arhiv&#259;. De asemenea po&#355;i alege s&#259; copiezi arhiva de la o versiune anterioar&#259;, ap&#259;s&#226;nd <a href=\"#\" class=\"rolang_copy_toogle\" rel=\"rolang_copy_imageset\">aici</a>.";
$l['admin_edit_old_archive'] = "Leg&#259;tura c&#259;tre vechea arhiv&#259; o g&#259;se&#351;ti <a class=\"rolang_get_archive\" href=\"{1}\" target=\"_blank\">aici<span><b>Nume fi&#351;ier :</b> {2}</span></a>.";
$l['admin_edit_error_id'] = "Nu ai specificat o versiune de traducere pentru a o putea edita sau ea nu exist&#259; &#238;n baza noastr&#259; de date.";
$l['admin_edit_error_version'] = "Versiunea introdus&#259; are un format gre&#351;it.";
$l['admin_edit_error_compatibility'] = "Compatibilitatea introdus&#259; nu are un format corect.";
$l['admin_edit_success'] = "Procesul de editare al versiunii cu id-ul {1} s-a realizat cu succes.";
// Vizualizare si creare arhiva
$l['admin_archive_title'] = "Arhive";
$l['admin_archive_description'] = "Aceast&#259; pagin&#259; &#238;&#355;i ofer&#259; posibilitatea de a crea, administra &#351;i &#351;terge arhive de limb&#259; de pe server-ul t&#259;u!";
$l['admin_archive_table_title'] = "Arhivele din sistem";
$l['admin_archive_table_name'] = "Nume arhiv&#259;";
$l['admin_archive_table_cdate'] = "Data cre&#259;rii";
$l['admin_archive_table_mdate'] = "Data modific&#259;rii";
$l['admin_archive_table_size'] = "M&#259;rime arhiv&#259;";
$l['admin_archive_table_options_delete'] = "&#350;terge arhiva";
$l['admin_archive_table_options_used'] = "Este utilizat&#259;?";
$l['admin_archive_table_without'] = "Nu exist&#259; nici m&#259;car o arhiv&#259; \"zip\" &#238;n cadrul directorului \"uploads\" de pe server.";
$l['admin_archive_delete_confirm'] = "Confirmi ac&#355;iunea de &#351;tergere a fi&#351;ierului \"{1}\" de pe disc?";
$l['admin_archive_delete_error'] = "Arhiva nu poate fi &#351;tears&#259; deoarece e utilizat&#259; de o versiune de traducere.";
$l['admin_archive_delete_success'] = "&#350;tergerea fi&#351;ierului \"{1}\" de pe disc s-a realizat cu succes.";
$l['admin_archive_used_confirm'] = "Confirmi ac&#355;iunea de verficare a utiliz&#259;rii a arhivei \"{1}\"?";
$l['admin_archive_used_no'] = "Aceast&#259; arhiv&#259; nu este utilizat&#259; de nicio versiune de traducere existent&#259; pe server.";
$l['admin_archive_used_yes'] = "Aceast&#259; arhiv&#259; este utilizat&#259; de versiunea de traducere cu id-ul {1}. Nu o ve&#355;i putea &#351;terge!";
$l['admin_archive_add_title'] = "Creaz&#259; arhiv&#259;";
$l['admin_archive_add_name'] = "Numele arhivei care se va crea";
$l['admin_archive_add_name_desc'] = "Numele arhivei care se va crea din fi&#351;ierele specificate mai jos. Caractere permise : cifre, litere si \"_\". Lungime : minim 4 caractere, maxim 32.";
$l['admin_archive_add_path'] = "Loca&#355;ia fi&#351;ierelor din list&#259;";
$l['admin_archive_add_path_desc'] = "Acest c&#226;mp are dou&#259; roluri deosebit de importante. Unul la m&#226;na, v&#259; ajut&#259; s&#259; nu mai scrie&#355;i pentru fiecare fi&#351;ier din list&#259; calea relativ&#259;, doi la m&#226;n&#259; nu include directoarele aferente c&#259;ii &#238;n arhiva dvs. Dac&#259; c&#226;mpul din drepata este pe un fundal verde atunci totul e OK, altfel exist&#259; erori la cale!";
$l['admin_archive_add_path_location'] = "Loca&#355;ie absolut&#259; : ";
$l['admin_archive_add_files'] = "List&#259; de fi&#351;iere &#351;i / sau directoare";
$l['admin_archive_add_files_desc'] = "Introdu o list&#259;, ce are ca &#351;i separator virgula, de fi&#351;iere &#351;i / sau directoare ce vor fi arhivate. Caractere permise : cifre, litere, \"_\", \".\" &#351;i \"/\". Lungime : minim 1 caracter.";
$l['admin_archive_add_overwrite'] = "Se suprascrie arhiva?";
$l['admin_archive_add_overwrite_desc'] = "Ce se &#238;nt&#226;mpl&#259; dac&#259; o arhiv&#259; cu acela&#351;i nume exist&#259; deja pe server?";
$l['admin_archive_add_overwrite_label'] = "Datele se suprascriu doar dac&#259; este bifat&#259; aceast&#259; valoare!";
$l['admin_archive_add_replace'] = "&#206;nainte de arhivare se realizeaz&#259; &#351;i codificare a textului?";
$l['admin_archive_add_replace_desc'] = "Dore&#351;ti s&#259; se efectueze c&#226;teva c&#259;ut&#259;ri &#351;i &#238;nlocuiri a unor date din fi&#351;iere &#238;nainte de a fi arhivate?";
$l['admin_archive_add_replace_label'] = "Dac&#259; op&#355;iunea e bifat&#259; va ap&#259;rea un nou r&#226;nd &#238;n tabel, &#238;n care ve&#355;i putea introduce datele folosite la codificare.";
$l['admin_archive_add_data'] = "Specific&#259; codificarea...";
$l['admin_archive_add_data_desc'] = "Dac&#259; fi&#351;ierele din arhiv&#259; au diacritice codificarea este necesar&#259;! &#206;n mod <b>&#238;mplicit</b> se face o codificare de la <b>UTF-8</b> la <b>HTML-ENTITIES</b>. Utilizare : \"UTF-8:HTML-ENTITIES\".";
$l['admin_archive_process_name'] = "Arhiva nu are setat un nume, sau valoarea introdus&#259; are un format gre&#351;it.";
$l['admin_archive_process_files'] = "Nu ai specificat niciun element &#238;n cadrul listei de fi&#351;iere, sau valoarea introdus&#259; are un format gre&#351;it.";
$l['admin_archive_process_over'] = "Nu ai setat o valoare corect&#259; pentru c&#226;mpul de suprascriere a datelor!";
$l['admin_archive_process_success'] = "Arhiva dorit&#259; a fost creat&#259; cu succes. Ea ar trebui deja s&#259; apar&#259; &#238;n tabelul de mai jos!";
$l['admin_archive_process_error'] = "Arhiva dorit&#259; nu a putut fi creat&#259;. Se poate s&#259; nu ai clasa 'ZipArchive', directorul 'uploads' s&#259; nu aib&#259; permisunile necesare sau arhiva s&#259; fi fost goal&#259;!";
$l['admin_archive_legend_m1'] = "Anun&#355;&#259; o eroare la specificarea c&#259;ii fi&#351;ierelor din list&#259;.";
$l['admin_archive_legend_m2'] = "Calea fi&#351;ierelor din list&#259; este, din punct de vedere teoretic, una corect&#259;.";
$l['admin_archive_legend_m3'] = "Calea este corect&#259;, iar fiecare director din ea exist&#259;.";
$l['admin_log_add_success'] = "S-a ad&#259;ugat o nou&#259; versiune de traducere ce are id-ul {1}.";
$l['admin_log_edit_success'] = "Ac&#355;iunea de editare a versiunii cu id-ul {1} s-a realizat cu succes.";
$l['admin_log_delete_success'] = "A fost &#351;tears&#259; cu succes arhiva {1} din sistem.";
$l['admin_log_create_success'] = "A fost creat&#259; arhiva cu numele de {1}.zip .";
?>