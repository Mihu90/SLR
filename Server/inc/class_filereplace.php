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
    Ultima modificare a codului : 18.09.2011 21:46
*/

class FileReplace
{
    private $keys = array();
    private $list = '';
    private $patterns = array();
    private $content = "";
    private $case_sensitive = true;

    // constructorul clasei
    function __construct($keys, $case_sensitive = true, $list = '$') 
    {  
        $this->keys = $keys;
        $this->case_sensitive = $case_sensitive;
        $this->list = $list;
    }
    // functia care afiseaza o lista cu pattern-urile create de functia de inlocuire
    function get() 
    {
        return $this->patterns;
    }
    // functia care afiseaza continutul variabilei $content
    function display()
    {
        return $this->content;
    }
    // functia care produce cautarea si inlocuirea datelor dintr-un fisier
    function replace($file)
    {
        // se intoarce continutul fisierului
        $content = file_get_contents($file); 
        // pentru fiecare pereche de texte ce vor fi inlocuite 
        $found = 0;
        foreach ($this->keys as $key => $array) 
        {
            $array['search'] = addcslashes($array['search'], $this->list);
            // este case sensitive?
            if($this->case_sensitive) {
                $pattern = "/".$array['search']."/s";
            }
            else {
                $pattern = "/".$array['search']."/si";
            }
            // se adauga pattern-ul in vector
            array_push($this->patterns, $pattern);
            // se realizeaza inlocuirea
            $content = preg_replace($pattern, $array['replace'], $content);  
            $found++;  
        }
        // se salveaza continutul
        $this->content = $content;
        // se scriu datele in fisier doar daca s-a intrat cel putin odata in structura repetitiva                            
        if ($found > 0) {
            return $this->write($file, $content);
        }
        else {
            return false;
        }
    }
    // functia care realizeaza scrierea datelor in fisier
    private function write($file, $data)
    {           
        if(is_writable($file)) {
            $fp = fopen($file, "w");
            fwrite($fp, $data);
            fclose($fp);    
            return true;
        }
        else {
            return false;  
        }
    }
}
?>