<?php
/* TODO
とりあえずバラバラのループでデータを取得
最終的に同じバッファループ内でデータ取得を切り替えられるように直す
*/
include_once(dirname(__FILE__).'/simulation_conf.php');
    /****************
     定数定義
    *****************/
    define( 'DEBUG' , true );                                   // デバッグ表示の切り替え   true/false
    define( 'CREATE_DATE' , date('Ymd') );                      // 作成年月日を定数化
    define( 'CREATE_FOLDER_NAME' , 'Sample_'.CREATE_DATE.'_' ); // コンバート後のファイルを出力するフォルダ名（の一部）
    define( 'LOG_FOLDER_NAME' , './log');                       // logファイルを出力するフォルダ名
    define( 'CREATE_LOG_NAME' , trim(basename(__FILE__), ".php").'_'.CREATE_DATE.'_' ); // logファイル名（の一部）

    $convertCt = 1; // 1から開始とする

    /****************
     header読み込み
    *****************/
    loadConverterHeader();
    switch($argc)
    {
      case 1:
        // 引数が与えられていないとき
        echo "使用方法: php.exe J:\git\stoaconverter\SToADataConverter.php コンバート元ファイル \n";
        break;
      case 2:
        // 正常に引数が与えられたとき
        $orgFilePath = $argv[1];
        /**************************************************************
         同日にコンバート処理がされているかをlogファイルの有無で判断
        ***************************************************************/
        chdir(dirname(__FILE__));                   // カレントディレクトリをこのPHPファイルの場所に変更
        debug_print( '【getcwd】:'.getcwd());           // カレントワークディレクトリの表示
        $targetDir = LOG_FOLDER_NAME;               // log出力フォルダ名をセット
        checkAndMakeDir($targetDir, '0777', false); // なければ作成
        $lists = fileListInTragetDir($targetDir);   // ファイル一覧を取得
        debug_print("$targetDir====================");
        debug_print($lists);
        debug_print("=======================");
        // CREATE_LOG_NAMEにマッチするlogファイル数を数える
        $Ct = count($lists);
        for($i=0;$i < $Ct;$i++){
            if(preg_match('/^'.CREATE_LOG_NAME.'/', $lists[$i]) == 1){
                if(!DEBUG){ // @DEBUG@ デバッグ表示中はシーケンスのカウントアップをしない
                    $convertCt++;
                }
            }
        }
        $sequenceName = str_pad($convertCt, 2, '0', STR_PAD_LEFT);
        writeLog('【start converter】',$sequenceName);
        // 今回のlogファイルの作成
        writeLog($str,$sequenceName);

        /*******************************************
         コンバートファイルの出力先フォルダの作成
        ********************************************/
        $directory  = CREATE_FOLDER_NAME.$sequenceName;
        checkAndMakeDir($directory);

        /*******************************************
         logファイルをオープン
        ********************************************/
        $handle = fopen($orgFilePath, "r");
        writeLog('【file open】:'.$orgFilePath,$sequenceName);

        /*******************************************
         logファイルのデータを読み出し
        ********************************************/
        while (!feof($handle)) {
            $tBuffer = fgets($handle);
            $buffer[] = trim($tBuffer);
            //数えておく必要のあるものがあれば、ここでカウント
        }

        /*******************************************
         logファイルをクローズ
        ********************************************/
        fclose($handle);
        writeLog('【file close】:'.$orgFilePath,$sequenceName);

        /*******************************************
         simulation_datを書き出し
        ********************************************/
writeLog('simulation_dat start!!!',$sequenceName);
        //書き出しファイル名の設定
        $fileName   = $directory.'/'.'simulation_'.CREATE_DATE.'_'.$sequenceName.'.dat';

        $bufferCt = count($buffer);
        $keys = array_keys($dat_conf);
        $key = array_shift($keys);
        $floorNum = 1;
        $isAnyFloor = false;

        for($iCt=0;$iCt < $bufferCt;$iCt++){
            if(($iCt < 200 && DEBUG) || !DEBUG){
                if($buffer[$iCt] == $key){
//writeLog('【$key】:'.$key,$sequenceName);
                    $downLow    = $dat_conf[$key]['downLow'];                           //データのある行を確認
//writeLog('【$downLow】:'.$downLow,$sequenceName);
                    $isAnyFloor = isset($dat_conf[$key]['isAnyFloor']) ? true : false;  //階数分のデータがあるのか確認

//if($isAnyFloor) writeLog('【$isAnyFloor】:'.$isAnyFloor,$sequenceName);

                    for($floorCt=0;$floorCt < $floorNum;$floorCt++){
                        if(!$isAnyFloor && $floorCt > 0) break;
                        $str = $buffer[$iCt + $downLow + $floorCt]; //データの取得
//writeLog('thisLoopBuffor:'.$str,$sequenceName);
                        $pat = '/('.$dat_conf[$key]['pattern'].')/';
//writeLog('$pat:'.$pat,$sequenceName);
                        //$str = preg_replace($pat, \1, $str);
                        preg_match($pat, $str, $match);
                        array_shift($match);
                        $str = join(', ', $match);
//writeLog('replaced:'.$str,$sequenceName);

                        if($key == FLOOR_NUM_KEY){
                            $str = trim($str,'データ取得成功：');
                            $str = trim($str);
                            $floorNum = intVal($str);       //階数を取得
//writeLog('■$floorNum:'.$floorNum,$sequenceName);
                        }
                                                                    //データを加工する
                        $tmpData[] = $str;
                    }

                    $str = str_pad($dat_conf[$key]['name'], 20, ' ')
                         . str_pad(join(', ', $tmpData)   , 15, ' ')
                         . $dat_conf[$key]['descript'];
                    unset($tmpData);
                    writeFile($fileName,$str);                  //ファイルに書き出し

                    if(count($keys) > 0){
                        $key = array_shift($keys);
                        $isAnyFloor = false;
                    }else{
                        break;
                    }
                }
            }
            else{
                break;
            }
        }
writeLog('simulation_dat end!!!',$sequenceName);

        /*******************************************
         eventLogを書き出し
        ********************************************/
writeLog('eventLog stert!!!',$sequenceName);
        $div = 1;
        //書き出しファイル名の設定
        $eventLogFileName[1]   = $directory.'/'.'eventlog_'.CREATE_DATE.'_'.$sequenceName.'_1.csv';
        $eventLogFileName[2]   = $directory.'/'.'eventlog_'.CREATE_DATE.'_'.$sequenceName.'_2.csv';
        $cagePersonsPast = 0;   //前回カゴ人数

        $startStr       = 'Final Local Time';
        $endStr         = 'EleLife SummarySimulation\s*\d\s*Start';
        $startFinded    = false;
        $headerWritn    = false;

        //headerの設定
        $header     = 'Now----, Next---, Cage, LocalTime--, Pos, hw---------, Dep, Arr, SD, Clt--------, Ton, Tof, Hc-';
$mCt = 0;
        for($iCt=0;$iCt < $bufferCt;$iCt++){
            if(preg_match('/'.$endStr.'/', $buffer[$iCt]) == 1){  //終了文字列行にマッチしたら
                $startFinded = false;   //開始文字列行発見フラグをfalse
                $headerWritn = false;   //ヘッダ行を書き出し完了フラグをFalse
                $div++;
writeLog('■■MATCH！■■',$sequenceName);
writeLog('eventLog pat:/'.$endStr.'/',$sequenceName);
writeLog("eventLog buffer[$iCt]:".$buffer[$iCt],$sequenceName);
                continue;
            }

            if(preg_match('/'.$startStr.'/', $buffer[$iCt]) == 1) {
                $startFinded = true;
writeLog('■■MATCH！■■',$sequenceName);
writeLog('eventLog pat:/'.$startStr.'/',$sequenceName);
writeLog("eventLog buffer[$iCt]:".$buffer[$iCt],$sequenceName);
                continue;
            }
            //開始文字列行にマッチしたら開始文字列行発見フラグをtrue
            if($startFinded != true) continue;  //開始文字列行がfalseなら、コンティニュー
            if($startFinded == true){           //開始文字列行がtrueなら
                if($headerWritn == false){          //ヘッダ行を書き出し完了フラグがfalseなら
                    $headerWritn    = true;             //ヘッダ行を書き出し完了フラグをtrue
writeLog("eventLogFileName[$div]:".$eventLogFileName[$div],$sequenceName);
                    writeFile($eventLogFileName[$div],$header); //headerの書き出し
                }
            }
writeLog('【$key】:'.$key,$sequenceName);
            $str = $buffer[$iCt]; //データの取得
            //データを加工する
            // matchでデータを取得
            //Now, Next, Cage(div), LocalTime(clt), Pos, hw, Dep, Arr,  SD, Clt, Ton, Tof, Hc
            //Now, Next,       itv,          Cycle, Pos, hw, Dep, Arr, Dir, Clt,           Hc
            $eventPat = '/^[^\d]+(\d+\.[A-Z]{4})[^\d]+(\d+\.[A-Z]{4})[^\d]+([0-9\.]+)[^\d]+([0-9\.]+)[^\d]+([0-9\.]+)[^\d]+([0-9\.e\+\-]+)[^\d]+(\d+)[^\d]+(\d+)[^\d]*Dir=([A-Z\-]{2})[^\d]+([0-9\.e\+\-]+)[^\d]+(\d+)/';
            preg_match($eventPat, $str, $match);
writeLog('$eventPat:'.$eventPat,$sequenceName);
writeLog('$str:'.$str,$sequenceName);
            array_shift($match);
            //乗降人数の算出(乗降のタイミングは必ずずれるのでどちらかのみ)
            $ton = 0;//乗車
            $tof = 0;//降車
            $cagePersonsDiff = $match[10] - $cagePersonsPast;
            if ($cagePersonsDiff > 0) { // 前回より増えたら
                $ton = $cagePersonsDiff;    //tonに設定
            } else
            if ($cagePersonsDiff < 0) { // 減ったら
                $tof = abs($cagePersonsDiff);    //tofに設定(マイナスなので絶対値にする)
            }
            $cagePersonsPast = $match[10];  // 今回の値を保存
            
            // 並びかえる
            if(isset($sortedEventData)) unset($sortedEventData);
            $sortedEventData[0]     = $match[0];                                //Now
            $sortedEventData[1]     = $match[1];                                //Next
            $sortedEventData[2]     = str_pad($div, 4, ' ' ,STR_PAD_LEFT);      //Cage(div)
            $sortedEventData[3]     = $match[9];                                //LocalTime(Clt)
            $sortedEventData[4]     = str_pad($match[4], 3, ' ' ,STR_PAD_LEFT); //Pos
            $sortedEventData[5]     = $match[5];                                //hw
            $sortedEventData[6]     = str_pad($match[6], 3, ' ' ,STR_PAD_LEFT); //Dep
            $sortedEventData[7]     = str_pad($match[7], 3, ' ' ,STR_PAD_LEFT); //Arr
            $sortedEventData[8]     = $match[8];                                //SD
            $sortedEventData[9]     = $match[9];                                //Clt
            $sortedEventData[10]    = str_pad($ton, 3, ' ' ,STR_PAD_LEFT);      //Ton
            $sortedEventData[11]    = str_pad($tof, 3, ' ' ,STR_PAD_LEFT);      //Tof
            $sortedEventData[12]    = str_pad($match[10], 3, ' ' ,STR_PAD_LEFT);//Hc
            // カンマで連結
            $str = join(', ', $sortedEventData);
writeLog('sortedEventData（'.$iCt.'）:'.$str,$sequenceName);

//if($mCt > 10) {
//    break;
//}
//$mCt++;

            writeFile($eventLogFileName[$div],$str);                  //ファイルに書き出し
        }
writeLog('eventLog end!!!',$sequenceName);

        /*****************
         footer読み込み
        ******************/
        loadConverterFooter();
        writeLog('【end   converter】',$sequenceName);
        writeLog('--------------------------------------------------------------------------------------------------------------------------------',$sequenceName,false);
        break;
      default:
        break;
    }

    exit;

//===============================================
// functions
//===============================================
function checkAndMakeDir($dirName, $parm='0777', $writeLog=true){
    if (!file_exists($dirName)) {
        mkdir($dirName, $parm, true);
        $str = "【create directory】:$dirName:$parm";
        debug_print($str);
        if ($writeLog) {
            writeLog($str,substr($dirName, -2));
        }
    }
}

function fileListInTragetDir($dirName){
    $drc = dir( $dirName );
    while( $fl = $drc->read() ){
        if( $fl == '.' OR $fl == '..' ){ continue; }
        # filesに追加
        $files[]    = $fl;
    }
    return $files;
}

function writeLog($str,$convertCt,$dateWrite=true){
    $str = trim($str);
    $fileName = LOG_FOLDER_NAME.'/'.CREATE_LOG_NAME.$convertCt.'.log';
    $date = ($dateWrite == true) ? date('Y-m-d h:i:s') : '';
    $str = $date." ".$str;
    writeFile($fileName,$str);
}

function writeFile($fileName,$str){
    $str = trim($str);
    // ファイル作成の為にfopenは行う
    $handle = fopen($fileName, "a");
    if(strlen($str) > 0){
        $str = $str."\r\n";
        debug_print($str);
        fwrite($handle, $str);
    }
    fclose($handle);
}

function loadConverterHeader(){
$str = <<<_DOC_
==========================================================
    ELE-LIFE SimulationData To AnimationData Converter    
----------------------------------------------------------
    created by Miosystem Jinno 2009/01/28
----------------------------------------------------------
Begin
==========================================================
_DOC_;
    print_r($str);echo "\n";
}

function loadConverterFooter(){
$str = <<<_DOC_
==========================================================
    ELE-LIFE SimulationData To AnimationData Converter    
Finish Thenk You
==========================================================
_DOC_;
    print_r($str);echo "\n";
}

function debug_print($str){
    if (DEBUG) {
        print_r($str);echo "\n";
//        var_dump($str);echo "\n";
    }
}

function getPatternMatchData($pat,$sorce){
    $ret = preg_replace('/^('.$pat.')/', "$0", $sorce);
    return $ret;
}