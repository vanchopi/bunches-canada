<?php

    require_once $_SERVER['DOCUMENT_ROOT'] .'/configuration.php';
    require_once $_SERVER['DOCUMENT_ROOT'] .'/weekly/vendor/ORM/ORM.php';

    use weekly\vendor\ORM\ORM;

class Weekly {

    public  $startWeekDay = 'Monday';
    public  $currentWeekDay;
    public  $startDate;
    public  $returnPricesDate;
    public  $status;
    public  $root;
    public  $import = 'weekly/products';
    public  $weekFolder;
    public  $products = array();
    private $domain;
    private $jConfig;
    private $dbConfig;

    /**
     * Weekly constructor.
     */
    public function __construct()
    {

        // get today date
        $this->currentWeekDay   = date('l', strtotime('now'));

        // init sale date
        $this->startDate        = ( $this->currentWeekDay == $this->startWeekDay ? date('Y-m-d', strtotime('now')) : date('Y-m-d', strtotime('last ' . $this->startWeekDay)) );

        // set date to return standard prices
        $this->returnPricesDate = ( $this->currentWeekDay == $this->startWeekDay ? date('Y-m-d', ( strtotime('now') - ( 60 * 60 * 24 * 7 ) ) ) : null );

        // site root folder
        $this->root   = $_SERVER['DOCUMENT_ROOT'];

        // site protocol
        $this->domain =  ( isset($_SERVER['HTTPS'] ) ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'];

        // path to current week sale
        $this->weekFolder = $this->root . '/' . $this->import . '/' . ( isset($_GET['check']) && $_GET['check'] ? $_GET['check'] : $this->startDate ) . '/';

        // get Joomla config
        $this->jConfig = new  JConfig();

        // set database settings
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

        // check sale from current week
        $this->setStart();

    }

    /**
     * check weekly
     * @return boolean
    */
    public function setStart()
    {

        if( file_exists( $this->weekFolder ) ){
            $this->status = true;
            return true;
        } else {
            $this->status = false;
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
    public function products()
    {

        // get sale products from sale.csv
        $sale = $this->weekFolder . '/sale.csv';

        if( is_readable( $sale ) ) {

            $products = fopen( $sale, 'r' );

            if( $products ){

                $count = count( $products );

                while ( ( $data = fgetcsv($products, 300, "\n" ) ) !== FALSE ) {

                    for ( $c = 0; $c < $count; $c++ ) {

                        $line = explode(',', $data[$c] );

                        if( !in_array('current_price', $line) ){

                            $this->products[$line[0]] = array(
                                'sale' => mb_convert_encoding( $line[2], "CP1251", "UTF-8"),
                                'image' => '/' . $this->import . '/' . $this->startDate . '/' . mb_convert_encoding( $line[3], "CP1251", "UTF-8")
                            );

                        }

                    }

                }

            }

        }

        if( count( $this->products ) > 0 ){
            $this->getProductInformation();
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