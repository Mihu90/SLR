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
    Ultima modificare a codului : 14.08.2011 00:21
*/
// GENERAL
$l['mod_name'] = "Sistem de Limb&#259; Rom&#226;n&#259;";
$l['mod_menu_item'] = "SLR";
// PERMISIUNI
$l['perm_rolang'] = "Poate administra sec&#355;iunea de configur&#259;ri a SLR-ului?";
$l['perm_infos'] = "Poate administra sec&#355;iunea de Informa&#355;ii?";
$l['perm_news'] = "Poate vedea ultimile nout&#259;&#355;i?";
$l['perm_updates'] = "Poate vedea &#351;i realiza actualiz&#259;ri de limb&#259;?";
$l['perm_team'] = "Poate vedea pagina intitulat&#259; \"Echip&#259;\"?";
// INFORMATII UTILE
$l['infos_title'] = "Informa&#355;ii";
$l['infos_description'] = "Informa&#355;ii despre aceast&#259; modificare.";
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
$l['infos_table_permissions_attention'] = "Aten&#355;ie!";
$l['infos_button_add'] = "Adauga legatura in subsol";
$l['infos_button_remove'] = "Sterge legatura din subsol";
$l['infos_button_process'] = "Se proceseaza cererea...";
$l['infos_button_error'] = "Eroare la realizarea actiunii!";
$l['infos_button_description'] = "<h4>Ad&#259;ugare &#351;i &#351;tergere leg&#259;tur&#259; c&#259;tre MyBB Rom&#226;nia</h4>
<p align=\"justify\">Butonul de mai sus v&#259; ofer&#259; posibilitatea de a ad&#259;uga sau de a &#351;terge foarte u&#351;or leg&#259;tura c&#259;tre noi din cadrul subsolului paginii tale de index. Trebui s&#259; &#351;ti&#355;i c&#259; dac&#259; nu ave&#355;i link &#238;n subsol c&#259;tre noi nu ve&#355;i putea folosi modulul de \"Actualiz&#259;ri\", pentru c&#259; ve&#355;i primi o eroare &#238;n momentul &#238;n care &#238;ncerca&#355;i o verificare posibil&#259; de actualiz&#259;ri. Pentru mai multe detalii legate de modul cum func&#355;ioneaz&#259; butonul v&#259; rug&#259;m s&#259; citi&#355;i documenta&#355;ia oficial&#259;.</p>";
// NOUTATI
$l['news_title'] = "Nout&#259;&#355;i";
$l['news_description'] = "Ultimile nout&#259;&#355;i de pe saitul oficial <a href=\"http://mybb.ro\">MyBB Rom&#226;nia</a>.";
$l['news_table_title'] = "Ultimile 5 nout&#259;&#355;i legate de pachetele de limb&#259;";
$l['news_table_noposts'] = "Nu exist&#259; nout&#259;&#355;i pe serverul oficial.";
$l['news_table_error'] = "Nu s-au putut citi nout&#259;&#355;i de pe serverul specificat. Se poate ca acesta s&#259; fie c&#259;zut!";
// ACTUALIZARI
$l['updates_title'] = "Actualiz&#259;ri";
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
$l['updates_table_list_error'] = "Eroare la conectarea la server sau la prelucrarea datelor de pe acesta.(Verifica&#355;i leg&#259;tura c&#259;tre noi din subsol!)";
$l['updates_table_list_lastcheck_1'] = "Ultima verificare {1}";
$l['updates_table_list_lastcheck_2'] = "Ultima verificare {1} {2}";
$l['updates_table_info_installed'] = "instalat";
$l['updates_table_info_process'] = "se instaleaz&#259;...";
$l['updates_table_errors_name'] = "List&#259; cu posibile erori la instalare";
$l['updates_table_errors_show'] = "arat&#259;";
$l['updates_table_errors_hide'] = "ascunde";
$l['updates_table_errors_error'] = "Eroare";
$l['updates_table_errors_explication'] = "Explica&#355;ie";
$l['updates_table_errors_ver'] = "Nu po&#355;i instala o versiune mai veche dec&#226;t cea curent&#259;.";
$l['updates_table_errors_wri'] = "Nu exist&#259; permisiuni de scriere pe server.";
$l['updates_table_errors_url'] = "Adresa web a pachetului de date nu este corect&#259;.";
$l['updates_table_errors_ext'] = "Pachetul dorit exist&#259; deja pe server.";
// Versiunea 2.1 - Start
$l['updates_table_errors_pcl'] = "Fi&#351;ierul <b>inc/class_pclzip.php</b> nu exist&#259; pe server.";
// Versiunea 2.1 - End
$l['updates_table_errors_zip'] = "Eroare ap&#259;rut&#259; la dezarhivarea pachetului de date.";
$l['updates_table_errors_del'] = "Arhiva nu a putut fi &#351;tears&#259; din sistem.";
// Versiunea 2.1 - Start
$l['updates_table_template_name'] = "Setul de imagini ale traducerii";
$l['updates_table_template_describe'] = "Descriere";
$l['updates_table_template_select'] = "Selector";
$l['updates_table_template_description'] = "Verific&#259; existen&#355;a setului de imagini ale traducerii pentru o tem&#259; din lista din dreapta acestui text.";
$l['updates_table_template_list'] = "<option value=\"{1}\">Alege din lista de mai jos!</option><option value=\"{2}\">Verific&#259;-le pe toate!</option>{3}";
$l['updates_table_template_answer'] = "&#206;n momentul &#238;n care alegi o tem&#259; din lista de mai sus, modificarea va vedea dac&#259; pentru tema aleas&#259; setul de imagini se afl&#259; &#238;n directorul corespunz&#259;tor. Dac&#259; nu exist&#259;, atunci se adaug&#259; setul &#238;n acest director.";
$l['updates_table_template_added'] = "&lt;Tema:{1}&gt;Set de imagini ad&#259;ugat.&lt;/Tema&gt;\n";
$l['updates_table_template_error'] = "&lt;Tema:{1}&gt;Eroare la copierea setului de imagini!&lt;/Tema&gt;\n";
$l['updates_table_template_exist'] = "&lt;Tema:{1}&gt;Set de imagini deja existent!&lt;/Tema&gt;\n";
$l['updates_table_template_nothemes'] = "Nu ai nicio tem&#259; pe forum, &#238;n acest moment!";
$l['updates_table_template_noid'] = "ID-ul temei alese nu exist&#259; &#238;n sistem!";
$l['updates_table_template_nonumber'] = "ID-ul temei alese nu este un num&#259; r!";
// Versiunea 2.1 - End
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
$l['team_license_text'] = "At&#226;t traducerea c&#226;t &#351;i aceast&#259; modificare intr&#259; sub inciden&#355;a aceleia&#351;i licen&#355;e. Licen&#355;a cu care vine traducerea ne confer&#259; dreptul de autori asupra ei. Traducerea apar&#355;ine <a href=\"http://mybb.ro\">MyBB Rom&#226;nia</a> &#351;i nu poate fi modificat&#259; sau redistribuit&#259; dec&#226;t cu acordul celor care au realizat-o. Mai multe date legate de licen&#355;&#259; pot fi vizualizate &#238;n cadrul fi&#351;ierul <b>License.pdf</b> din arhiv&#259;.";
?>