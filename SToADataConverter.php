<?php
include_once('./simulation_dat_conf.php');
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
        $lCt = 0;
        while (!feof($handle)) {
            if($lCt < 100){
                $buffer = fgets($handle);
                echo $buffer;
                $fileName   = $directory.'/'.'simulation_'.CREATE_DATE.'_'.$sequenceName.'.dat';
                $str        = $buffer;
                writeFile($fileName,$str);
                $lCt++;
            }
            else{
                break;
            }
        }

        /*******************************************
         logファイルをクローズ
        ********************************************/
        fclose($handle);
        writeLog('【file close】:'.$orgFilePath,$sequenceName);


        /*****************
         footer読み込み
        ******************/
        loadConverterFooter();
        writeLog('【end   converter】',$sequenceName);
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

function writeLog($str,$convertCt){
    $str = trim($str);
    $fileName = LOG_FOLDER_NAME.'/'.CREATE_LOG_NAME.$convertCt.'.log';
    $date = date('Y-m-d h:i:s');
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
    }
}
