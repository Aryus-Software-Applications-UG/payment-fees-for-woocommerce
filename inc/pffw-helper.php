<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PFFW_Helper_Functions {
    
    /** Print Array Pretty */
    public function print_array_pretty($array, $exactly_detailed = false) {
        if($exactly_detailed) {
            echo esc_html('<pre>');
            var_dump($arr);
            echo esc_html('<pre>');
        } else {
            echo esc_html('<pre>');
            print_r($array);
            echo esc_html('<pre>');
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
                alert(<?php echo esc_html($message) ?>);
            </script>
            <?php
        }
    }

}