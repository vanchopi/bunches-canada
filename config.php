<?php

class Weekly {

    public $start;
    public $status;
    public $products;
    private $domain;

    /**
     * Weekly constructor.
     */
    public function __construct()
    {

        $this->domain =  ( isset($_SERVER['HTTPS'] ) ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'];

        $this->products = array(

            'BBB514'	=> '45',
            'BBB483'	=> '45',
            'BBB348'	=> '45',
            'BBB22R'	=> '45',
            'BBB367'	=> '30',
            'BBB368'	=> '30',
            'BB72'	    => '30',
            'BBB341'	=> '40',
            'BBB343'    => '40',
            'BBB378'	=> '30',
            'BBB364'	=> '45',
            'BBB390'	=> '45',
            'BBB493'	=> '45',
            'BBB310'	=> '45',
            'BBB361'	=> '40',

        );

        $this->status = self::setStart();

    }

    /**
     * check weekly
     * @return boolean
    */
    public function setStart()
    {
        if( count($this->products) > 0 ){
            return true;
        } else {
            return false;
        }
    }

    /**
     * redirect
    */
    public function redirect()
    {

        /***************************** DD ****************************/
        echo
            '<hr><pre style="color:#000">LINE: ', __LINE__, '<br>FILE: ', __FILE__, '<hr>',
            var_dump($_SERVER['REQUEST_URI']),
            '<hr>', var_dump(debug_backtrace()), '</pre>',
        die();
        /***************************** DD ****************************/

        if( !self::setStart() ) {
            header('Location: '. $this->domain,true, 301);
        }

    }


}