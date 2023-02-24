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
    /**
     * @param $string
     * @return string
     */
    public static function vnToStr ($str){

        $unicode = array(

            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

            'd'=>'đ',

            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

            'i'=>'í|ì|ỉ|ĩ|ị',

            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',

            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'D'=>'Đ',

            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',

            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

        );

        foreach($unicode as $nonUnicode=>$uni){

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);

        }
        $str = str_replace(' ','_',$str);

        return $str;

    }

}
