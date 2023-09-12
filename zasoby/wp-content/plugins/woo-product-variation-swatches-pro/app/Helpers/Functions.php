<?php

namespace Rtwpvsp\Helpers;
  
class Functions {  
    public static function check_license() {
        return apply_filters('rtwpvs_check_license', true);
    }
}
