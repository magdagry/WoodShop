<?php

namespace Rtwpvgp\Helpers;
  
class Functions {  
    public static function check_license() {
        return apply_filters('rtwpgs_check_license', true);
    }
}
