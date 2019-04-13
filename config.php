<?php

    require_once $_SERVER['DOCUMENT_ROOT'] .'/configuration.php';
    require_once $_SERVER['DOCUMENT_ROOT'] .'/weekly/vendor/ORM/ORM.php';

    use weekly\vendor\ORM\ORM;

class Weekly {

    public  $start;
    public  $stop;
    public  $status;
    public  $root;
    public  $import = 'products';
    public  $products = array(

        'BBB514' => array( 'sale' => '45', 'image' => 'f6b4d628b8a4b35900452bf3adfe65b3.jpg'),
        'BBB483' => array( 'sale' => '45', 'image' => '35dcfc4b5c64f26b33cbaa0aa995a7ca.jpg'),
        'BBB348' => array( 'sale' => '45', 'image' => '512e1d2250ede5c4103f2b4095ce3424.jpg'),
        'BBB22R' => array( 'sale' => '45', 'image' => 'a21ea168df44bba253ddf49f913556e5.jpg'),
        'BBB367' => array( 'sale' => '30', 'image' => 'c3ea5b3ebb3cc211b6959c9c97d62a29.jpg'),
        'BBB368' => array( 'sale' => '30', 'image' => '2451a962e029177fcbc4a530e09b0134.jpg'),
        'BBB341' => array( 'sale' => '40', 'image' => '2c02462451c114ee2b7e4ec62ae0e24d.jpg'),
        'BBB343' => array( 'sale' => '40', 'image' => '5ce3db4039286d60658c01c02a096fc7.jpg'),
        'BBB378' => array( 'sale' => '30', 'image' => 'd0dcfbc24f6f37529af3bf4c3bf135b0.jpg'),
        'BBB390' => array( 'sale' => '45', 'image' => '61fc9a4e5be6a13c11e3a2ba321b2a6d.jpg'),
        'BBB493' => array( 'sale' => '45', 'image' => '65b118beaebec7bb63f9ee82a45e0be1.jpg'),
        'BBB310' => array( 'sale' => '45', 'image' => '9d39a496659e4b071c51a47ae7e0b5d6.jpg'),
        'BBB361' => array( 'sale' => '40', 'image' => '715a794cc3b64142eb4a3683e052fba0.jpg'),

    );
    private $domain;
    private $jConfig;
    private $dbConfig;

    /**
     * Weekly constructor.
     */
    public function __construct()
    {

        $this->start = date('Y-m-d', strtotime('last sunday'));
        $this->stop  = date('Y-m-d', ( $this->start + ( 60 * 60 * 24 * 7 ) ));

        $this->root   = $_SERVER['DOCUMENT_ROOT'];
        $this->domain =  ( isset($_SERVER['HTTPS'] ) ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'];

        $this->status = self::setStart();

        $this->jConfig = new  JConfig();
        $this->dbConfig =  array(
            'server'    => $this->jConfig->host,
            'database'  => $this->jConfig->db,
            'user'      => $this->jConfig->user,
            'password'  => $this->jConfig->password,
            'port'      => '3306',
            'type'      => 'mysql',
            'encode'    => 'utf8',
            'socket'    => ''
        );

        $this->getProductInformation();

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

        if( !self::setStart() ) {
            header('Location: '. $this->domain,true, 301);
            die();
        }

    }


    /**
     * import sale products and setup new prices
    */
    private function import()
    {
        $path       = $this->root . '/' . $this->import . '/' . $this->start;
        $checkInit  = $path . '/1.txt';
        $sale       = $path . '/sale.csv';

        if( !file_exists( $checkInit ) ) {
            if( file_exists( $sale ) ) {

                $products = fopen( $sale, 'r' );

                if( $products ){

                    $count = count( $products );

                    while ( ( $data = fgetcsv($products, 300, "\n" ) ) !== FALSE ) {

                        for ( $c = 0; $c < $count; $c++ ) {

                            $line = explode(',', $data[$c] );

                            foreach( $line as $key => $value ){

                                $vUtf = mb_convert_encoding( $value, "CP1251", "UTF-8");

                                /***************************** VV ****************************/
                                echo
                                    '<hr><pre style="color:#000">LINE: ', __LINE__, '<br>FILE: ', __FILE__, '<hr>',
                                    var_dump($vUtf), '</pre>';
                                /***************************** VV ****************************/

                            }

                        }

                    }

                }

            }
        }

    }

    /**
     * get products information
    */
    private function getProductInformation()
    {
        if( $this->products ){

            $ORM = new ORM( $this->dbConfig );
            $ids = array_keys( $this->products );
            $query = null;
            foreach ($ids as $id) {
                $query[] = "'" . $id . "'";
            }

            $result = $ORM::queryAll("SELECT
                                                `p`.`product_id`,
                                                `p`.`product_name`,
                                                `p`.`product_sku`,
                                                `x`.`product_id`,
                                                `x`.`category_id`,
                                                `c`.`category_name`,
                                                `c`.`category_id`
                                              FROM `jos_vm_product` AS `p`
                                              INNER JOIN `jos_vm_product_category_xref` AS `x` ON `p`.`product_id` = `x`.`product_id`
                                              INNER JOIN `jos_vm_category` AS `c` ON `x`.`category_id` = `c`.`category_id`
                                              WHERE `p`.`product_sku` IN (" . implode(',', $query) . ") GROUP BY `p`.`product_id` " );

            if( $result ){
                foreach ( $result as $product ){
                    if( isset( $this->products[$product['product_sku']] ) ){
                        $this->products[$product['product_sku']]['url'] = '/' . str_replace(' ', '-', $product['category_name']) . '/' . str_replace(' ', '-', $product['product_name']) . '.html';
                        $this->products[$product['product_sku']]['name'] = $product['product_name'];
                    }
                }
            }

        }
    }


}