<?php

if (! function_exists('moneyFormat')) {

    /**
     * moneyFormat
     *
     *@param mixed $str
     *@return void
     */

     function moneyFormat($str) {
         return 'Rp '. number_format($str, '0', '', '.');
     }

}

if (! function_exists('setActive')) {

    /**
     * setActive
     *
     * @param  mixed $path
     * @return void
     */
    function setActive($path)
    {
        return Request::is($path . '*') ? ' active' :  '';
    }

}

if (!function_exists('dateID')) {

    /**
     * dateID
     *
     * @param mixed $tanggal
     * @return void
     */

     function dateID($tanggal) {
         $value = Carbon\Carbon::parse($tanggal);
         $parse = $value->locale('id');
         return $parse->translatedFormat('l, d F Y');
     }
}

if (!function_exists('datetimeID')) {

    /**
     * dateID
     *
     * @param mixed $tanggal
     * @return void
     */

     function datetimeID($tanggal) {
         $value = Carbon\Carbon::parse($tanggal);
         $parse = $value->locale('id');
         return $parse->translatedFormat('d F Y - H:i');
     }
}

if (!function_exists('daydatetimeID')) {

    /**
     * dateID
     *
     * @param mixed $tanggal
     * @return void
     */

     function daydatetimeID($tanggal) {
         $value = Carbon\Carbon::parse($tanggal);
         $parse = $value->locale('id');
         return $parse->translatedFormat('l, d F Y - H:i');
     }
}
