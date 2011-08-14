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

class XmlToArray
{
   
    var $xml='';
   
    /**
    *   Default Constructor
    *   @param $xml = xml data
    *   @return none
    */
   
    function XmlToArray($xml)
    {
        $this->xml = $xml;   
    }
   
    /**
    *   _struct_to_array($values, &$i)
    *
    *   This is adds the contents of the return xml into the array for easier processing.
    *   Recursive, Static
    *
    *   @access    private
    *   @param    array  $values this is the xml data in an array
    *   @param    int    $i  this is the current location in the array
    *   @return    Array
    */
    function _struct_to_array($values, &$i)
    {
        $child = array();
        if (isset($values[$i]['value'])) array_push($child, $values[$i]['value']);
       
        while ($i++ < count($values)) {
            switch ($values[$i]['type']) {
                case 'cdata':
                array_push($child, $values[$i]['value']);
                break;
               
                case 'complete':
                    $name = $values[$i]['tag'];
                    if(!empty($name)){
                    $child[$name]= ($values[$i]['value'])?($values[$i]['value']):'';
                    if(isset($values[$i]['attributes'])) {                   
                        $child[$name] = $values[$i]['attributes'];
                    }
                }   
              break;
               
                case 'open':
                    $name = $values[$i]['tag'];
                    $size = isset($child[$name]) ? sizeof($child[$name]) : 0;
                    $child[$name][$size] = $this->_struct_to_array($values, $i);
                break;
               
                case 'close':
                return $child;
                break;
            }
        }
        return $child;
    }
   
    /**
    *   createArray($data)
    *
    *   This is adds the contents of the return xml into the array for easier processing.
    *
    *   @access    public
    *   @param    string    $data this is the string of the xml data
    *   @return    Array
    */
    function createArray()
    {
        $xml    = $this->xml;
        $values = array();
        $index  = array();
        $array  = array();
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parse_into_struct($parser, $xml, $values, $index);
        xml_parser_free($parser);
        $i = 0;
        $name = $values[$i]['tag'];
        $array[$name] = isset($values[$i]['attributes']) ? $values[$i]['attributes'] : '';
        $array[$name] = $this->_struct_to_array($values, $i);
        return $array;
    }  
}
?>