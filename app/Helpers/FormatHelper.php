<?php

// app/Helpers/FormatHelper.php

if (!function_exists('formatBySetup')) {
    /**
     * Format angka untuk rate dengan separator ribuan dan desimal.
     *
     * @param  float  $value
     * @return string
     */
    function formatBySetup($value)
    {
        $setup = \App\Models\Setup::get()->first();
        $decimalSeparator = $setup->decimal_separator;
        $thousandSeparator = $setup->thousand_separator;
        return number_format($value, 2, $decimalSeparator, $thousandSeparator);
    }
}
