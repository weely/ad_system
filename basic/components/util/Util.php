<?php
namespace app\components\util;

class Util{

    public static function ExportCsv($data, $title_arr, $filename='') {
        ini_set("max_execution_time", "3600");

        $csv_data = '';
        /** 标题 */
        $nums = count($title_arr);

        for ($i = 0; $i < $nums - 1; ++$i) {
            $csv_data .= $title_arr[$i] . ',';
        }
        if ($nums > 0) {
            $csv_data .= $title_arr[$nums - 1] . "\r\n";
        }

        foreach ($data as $k => $row) {
            $_tmp_csv_data = '';
            foreach ($row as $key => $r){
                $row[$key] = str_replace("\"","\"\"", $r);

                if ($_tmp_csv_data == '') {
                    $_tmp_csv_data = $row[$key];
                } else {
                    $_tmp_csv_data .= ','. $row[$key];
                }
            }
            $csv_data .= $_tmp_csv_data . "\r\n";
            unset($data[$k]);
        }

        $csv_data = mb_convert_encoding($csv_data, "cp936", "UTF-8");
        $file_name = empty($file_name) ? date('Y-m-d-H-i-s', time()) : $file_name;
        if(preg_match('/MSIE/i', $_SERVER['HTTP_USER_AGENT'])){
            $file_name = urlencode($file_name);
            $file_name = iconv('UTF-8', 'GBK//IGNORE', $file_name);
        }
        $file_name = $file_name . '.csv';
        header('Content-Type: application/download');
        header("Content-type:text/csv;");
        header("Content-Disposition:attachment;filename=" . $file_name);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $csv_data;
        exit();

    }

}