<?php

    require_once $_SERVER['DOCUMENT_ROOT'] .'/configuration.php';
    require_once $_SERVER['DOCUMENT_ROOT'] .'/weekly/vendor/ORM/ORM.php';

    use weekly\vendor\ORM\ORM;

class Weekly {

    public  $startWeekDay = 'Monday';
    public  $currentWeekDay;
    public  $startDate;
    public  $beforeSaleDate;
    public  $returnPricesDate;
    public  $beforeWeekSale;
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
        $this->returnPricesDate = ( $this->currentWeekDay == $this->startWeekDay ? date('Y-m-d', ( strtotime('now') + ( 60 * 60 * 24 * 7 ) ) ) : null );

        // before week sale date
        $this->beforeWeekSale = ( $this->currentWeekDay == $this->returnPricesDate ? date('Y-m-d', ( strtotime('now') - ( 60 * 60 * 24 * 8 ) ) ) : null );

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

        $this->returnPrices();

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

                        if( !in_array('sku', $line) ){

                            $this->products[$line[0]] = array(
                                'product_sku' => mb_convert_encoding( $line[0], "CP1251", "UTF-8"),
                                'image' => '/' . $this->import . '/' . $this->startDate . '/' . mb_convert_encoding( $line[1], "CP1251", "UTF-8"),
                                'week_sale_price' => mb_convert_encoding( $line[2], "CP1251", "UTF-8")
                            );

                        }

                    }

                }

            }

        }

        $this->productInformation();

    }

    /**
     * get products information
    */
    private function productInformation()
    {

        if( $this->products ) {

            $ORM = new ORM($this->dbConfig);
            $ids = array_keys($this->products);

            $query = null;
            foreach ($ids as $id) {
                $query[] = "'" . $id . "'";
            }



            $result = $ORM::queryAll("SELECT
                                                `p`.`product_id`,
                                                `p`.`product_name`,
                                                `p`.`product_sku`,
                                                `p`.`product_discount_id`,
                                                `x`.`product_id`,
                                                `x`.`category_id`,
                                                `c`.`category_name`,
                                                `c`.`category_id`,
                                                `d`.*,
                                                `pr`.*
                                              FROM `jos_vm_product` AS `p`
                                              INNER JOIN `jos_vm_product_category_xref` AS `x` ON `p`.`product_id` = `x`.`product_id`
                                              INNER JOIN `jos_vm_product_discount` AS `d` ON `p`.`product_discount_id` = `d`.`discount_id`
                                              INNER JOIN `jos_vm_product_price` AS `pr` ON `pr`.`product_id` = `p`.`product_id`
                                              INNER JOIN `jos_vm_category` AS `c` ON `x`.`category_id` = `c`.`category_id`
                                              WHERE `p`.`product_sku` IN (" . implode(',', $query) . ") GROUP BY `p`.`product_id` ");


            if ($result) {


                foreach ($result as $product) {

                    if (isset($this->products[$product['product_sku']])) {

                        $refactorProduct = $this->products[$product['product_sku']];

                        // check product in template week products
                        $checkInWeekSaleTemp = $ORM::query("SELECT * FROM `weekly_temp_products` WHERE `product_id` = '{$product['product_id']}' AND `start_date` = '{$this->startDate}' AND `status` = '1'");


                        $fullPrice  = ceil($product['product_price']);
                        $curDiscount   = (int)$product['amount'];
                        $onePercent = ( $fullPrice / 100 );

                        $prDif = ( $fullPrice['week_sale_price'] - $refactorProduct['week_sale_price'] );
                        $refactorProduct['sale'] = 100 + ceil( $prDif / $onePercent );

                        $newDiscount = ceil($fullPrice - $refactorProduct['week_sale_price'] );
                        $refactorProduct['product_price'] = $product['product_price'];
                        $refactorProduct['name'] = $product['product_name'];
                        $refactorProduct['product_id'] = $product['product_id'];
                        $refactorProduct['url'] = '/' . str_replace(' ', '-', $product['category_name']) . '/' . str_replace(' ', '-', $product['product_name']) . '.html';


                        // save current price
                        if (!$checkInWeekSaleTemp) {

                            $ORM::query("INSERT INTO  `weekly_temp_products`
                                                 (`id`,
                                                  `start_date`,
                                                  `old_full_price`,
                                                  `product_id`,
                                                  `product_sku`,
                                                  `old_product_discount_id`,
                                                  `back_date`,
                                                  `status` )
                                          VALUES (null,
                                                  '{$this->startDate}',
                                                  '{$product['product_price']}',
                                                  '{$product['product_id']}',
                                                  '{$refactorProduct['product_sku']}',
                                                  '{$product['product_discount_id']}',
                                                  '{$this->returnPricesDate}',
                                                  1)");


                            $ORM::query("INSERT INTO `jos_vm_product_discount`
                                                        (`discount_id`,     `amount`,    `is_percent`,   `start_date`,    `end_date`)
                                                 VALUES (    null,      '$newDiscount',        '0',     '" . time() . "',    '0'    )");

                            // check insert operation
                            $checkNewDiscountId = $ORM::query("SELECT `discount_id` FROM `jos_vm_product_discount` WHERE `amount` = '{$newDiscount}'");

                            // set new discount (week price) from the product
                            if ($checkNewDiscountId) {
                                $ORM::query("UPDATE `jos_vm_product` SET `product_discount_id` = '{$checkNewDiscountId['discount_id']}' WHERE `product_id` = '{$product['product_id']}'");
                            }

                        }

                        $this->products[$product['product_sku']] = $refactorProduct;

                    }

                }

            }

        }

    }

    /**
     * get products information
    */
    private function returnPrices()
    {

        if( $this->beforeWeekSale ){

            $ORM = new ORM( $this->dbConfig );
            $ids = array_keys( $this->products );
            $query = null;
            foreach ($ids as $id) {
                $query[] = "'" . $id . "'";
            }


            $result = $ORM::queryAll("SELECT * FROM `weekly_temp_products` WHERE `product_sku` IN ('{$query}') AND `status` = '1' AND `start_date` = '{$this->beforeWeekSale}'");

            if( $result ){

            foreach ( $result as $product ){

                ORM::query("UPDATE `weekly_temp_products` SET `status` = '0' WHERE `product_sku` = '{$product['product_id']}");

//                if( date("Y-m-d", strtotime('now') ) == $this->returnPricesDate && file_exists( $this->beforeWeekSale ) ){
//                    $backupPrices = $ORM::queryAll("SELECT * FROM `weekly_temp_products` WHERE `back_date` = '{$this->returnPricesDate }' AND `status` = '1'");
//
//                    if( $backupPrices ){
//                        foreach ($backupPrices as $item) {
//                            ORM::query("UPDATE `jos_vm_product` SET `product_discount_id` = '{$item['product_discount_id']}' WHERE `product_sku` = '{$item['product_sku']}");
//
//                            $checkBackup = $ORM::query("SELECT `product_discount_id` FROM `jos_vm_product` WHERE `product_sku` = '{$item['product_sku']}'");
//                            if( $checkBackup ){
//                                ORM::query("UPDATE `weekly_temp_products` SET `status` = '0' WHERE `product_sku` = '{$item['product_sku']}");
//                            }
//                        }
//                    }
//
//                }

            }

            }

        }

    }

}