<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class PFFW_Helper_Functions {
    
    /** Print Array Pretty */
    public function print_array_pretty($array, $exactly_detailed = false) {
        if($exactly_detailed) {
            echo '<pre>';
            var_dump($arr);
            echo '</pre>';
        } else {
            echo '<pre>';
            print_r($array);
            echo '</pre>';
        }
    }

    /** Create Alert */
    public function create_alert($message, $type = 'string') {
        if($type == 'array') {
            ?>
            <script>
                alert(<?php $this->print_array_pretty($message) ?>);
            </script>
            <?php
        } else {
            ?>
            <script>
                alert(<?php echo $message ?>);
            </script>
            <?php
        }
    }

}