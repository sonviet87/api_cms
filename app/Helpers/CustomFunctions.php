<?php
namespace App\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CustomFunctions{
    /**
    * Check "Booleanic" Conditions
    *
    * @param  [mixed]  $variable  Can be anything (string, bol, integer, etc.)
    * @return [boolean]           Returns TRUE  for "1", "true", "on" and "yes"
    *                             Returns FALSE for "0", "false", "off" and "no"
    *                             Returns NULL otherwise.
    */
    public static function isEnabled($variable)
    {
        if (!isset($variable)) return null;
        return filter_var($variable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /**
    * Check "Date" Valid
    *
    * @param  [mixed]  $variable  Can be anything (string, bol, integer, etc.)
    * @return [boolean]           Returns TRUE  for "Y-m-d"
    *                             Returns FALSE for unvalid date
    *                             Returns NULL otherwise.
    */
    public static function isValidDate($variable)
    {
        if (!isset($variable)) return null;
        try{
            if (Carbon::createFromFormat('Y-m-d', $variable) !== false) {
                return true;
            }else{
                return false;
            }
        }catch(\Exception $e){
            return false;
        }
    }

    /**
     * @param $title
     * @param $locale
     * @return string
     */
    public static function locale_slug($title,$locale='vi'){
        if(in_array($locale, ['vi', 'en'])){
            $slug = Str::slug($title, '-');
        }else{
            $slug = preg_replace('/\s+/u', '-', trim($title));
        }

        return $slug;
    }
    /**
     * @param $string
     * @return string
     */
    public static function filterPathUrl($url){
        return parse_url($url)['path'];

    }

}
