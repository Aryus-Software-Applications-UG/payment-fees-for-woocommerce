<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

class PFFW_DB_Controller {
    private $wpdb;
	private $fee_table_name;

    public function __construct() {
        $this->wpdb = $GLOBALS['wpdb'];
        $this->create_db_table_for_fees();
        $this->fee_table_name = $this->wpdb->prefix . 'payment_fees_for_woocommerce';
    }

    public function create_db_table_for_fees() {
        $this->_create_db_table_for_fees();
    }

    public function delete_db_table_for_fees() {
        $this->_delete_db_table_for_fees();
    }

    public function insert_fee($gateway, $fee_in_percent, $fee_in_number) {
        $this->_insert_fee($gateway, $fee_in_percent, $fee_in_number);
    }

    public function get_fee_by_payment_method($payment_method) {
        return $this->_get_fee_by_payment_method($payment_method);
    }

    public function payment_method_exists_in_db($payment_method) {
        return $this->_payment_method_exists_in_db($payment_method);
    }



    private function _create_db_table_for_fees() {
        $charset_collate = $this->wpdb->get_charset_collate();
        $table_name = $this->wpdb->prefix . 'payment_fees_for_woocommerce';
	    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		    id int NOT NULL AUTO_INCREMENT,
            payment_method varchar(50) NOT NULL,
            fee_in_percent int(100) NOT NULL,
            fee_in_number int NOT NULL,
            PRIMARY KEY (id)
	    ) $charset_collate;";
	    dbDelta( $sql );
    }

    private function _delete_db_table_for_fees() {
        $table_name = $this->wpdb->prefix . 'payment_fees_for_woocommerce';
        $this->wpdb->query("DROP TABLE IF EXISTS $table_name");
    }

    private function _insert_fee($gateway, $fee_in_percent, $fee_in_number) {
        if($this->payment_method_exists_in_db($gateway)) {
            $this->update_fee($gateway, $fee_in_percent, $fee_in_number);
        } else {
            $this->prepare_row($gateway, $fee_in_percent, $fee_in_number);
        }
    }

    private function update_fee($gateway, $fee_in_percent, $fee_in_number) {
        $this->wpdb->update( 
            $this->fee_table_name,
            array( 
                'fee_in_percent' => $fee_in_percent,   
                'fee_in_number' => $fee_in_number
            ), 
            array( 'payment_method' => $gateway ), 
            array( 
                '%s',
                '%s'  
            ), 
            array( '%s' )
        );
    }

    private function prepare_row($gateway, $fee_in_percent, $fee_in_number) {
        $this->wpdb->query(
            $this->wpdb->prepare(
                "INSERT INTO $this->fee_table_name
                ( payment_method, fee_in_percent, fee_in_number )
                VALUES ( %s, %s, %s )",
                array(
                    $gateway,
                    $fee_in_percent,
                    $fee_in_number
                )
            )
        );
    }

    private function _get_fee_by_payment_method($payment_method) {
        $temp = $this->wpdb->get_results("SELECT `fee_in_percent` FROM $this->fee_table_name where `payment_method` = '$payment_method'");
        return $temp[0]->fee_in_percent;
        
    }

    private function _payment_method_exists_in_db($payment_method) {
        if( $this->wpdb->get_results("SELECT `payment_method` FROM $this->fee_table_name where `payment_method` = '$payment_method'"))  {
            return true;
        } else {
            return false;
        }
    }
}