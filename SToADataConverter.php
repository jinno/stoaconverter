<?php
    /****************
     ������
    *****************/
    define( 'DEBUG' , true );                                   // �ǥХå�ɽ�����ڤ��ؤ�   true/false
    define( 'CREATE_DATE' , date('Ymd') );                      // ����ǯ�����������
    define( 'CREATE_FOLDER_NAME' , 'Sample_'.CREATE_DATE.'_' ); // ����С��ȸ�Υե��������Ϥ���ե����̾�ʤΰ�����
    define( 'LOG_FOLDER_NAME' , 'log');                         // log�ե��������Ϥ���ե����̾
    define( 'CREATE_LOG_NAME' , trim(basename(__FILE__), ".php").'_'.CREATE_DATE.'_' ); // log�ե�����̾�ʤΰ�����

    $convertCt = 1; // 1���鳫�ϤȤ���

    /****************
     header�ɤ߹���
    *****************/
    loadConverterHeader();

    /**************************************************************
     Ʊ���˥���С��Ƚ���������Ƥ��뤫��log�ե������̵ͭ��Ƚ��
    ***************************************************************/
    chdir(dirname(__FILE__));                   // �����ȥǥ��쥯�ȥ�򤳤�PHP�ե�����ξ����ѹ�
    debug_print( 'getcwd:'.getcwd());           // �����ȥ���ǥ��쥯�ȥ��ɽ��
    $targetDir = LOG_FOLDER_NAME;               // log���ϥե����̾�򥻥å�
    checkAndMakeDir($targetDir, '0777', false); // �ʤ���к���
    $lists = fileListInTragetDir($targetDir);   // �ե�������������
    debug_print("$targetDir====================");
    debug_print($lists);
    debug_print("=======================");
    // CREATE_LOG_NAME�˥ޥå�����log�ե�������������
    $Ct = count($lists);
    for($i=0;$i < $Ct;$i++){
        if(preg_match('/^'.CREATE_LOG_NAME.'/', $lists[$i]) == 1){
            if(!DEBUG){ // @DEBUG@ �ǥХå�ɽ����ϥ������󥹤Υ�����ȥ��åפ򤷤ʤ�
                $convertCt++;
            }
        }
    }
    $sequenceName = str_pad($convertCt, 2, '0', STR_PAD_LEFT);
    // �����log�ե�����κ���
    writeLog($str,$sequenceName);

    /*******************************************
     ����С��ȥե�����ν�����ե�����κ���
    ********************************************/
    $directory  = CREATE_FOLDER_NAME.$sequenceName;
    checkAndMakeDir($directory);

    /*****************
     footer�ɤ߹���
    ******************/
    loadConverterFooter();
    exit;

//===============================================
// functions
//===============================================
function checkAndMakeDir($dirName, $parm='0777', $writeLog=true){
    if (!file_exists($dirName)) {
        mkdir($dirName, $parm, true);
        $str = "create directory-->$dirName:$parm";
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
        # files���ɲ�
        $files[]    = $fl;
    }
    return $files;
}

function writeLog($str,$convertCt){
    $str = trim($str);
    // �ե���������ΰ٤�fopen�ϹԤ�
    $handle = fopen(LOG_FOLDER_NAME.'/'.CREATE_LOG_NAME.$convertCt.'.log', "a");
    if(strlen($str) > 0){
        $date = date('Y-m-d h:i:s');
        $str = $date." ".$str."\r\n";
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


function _exec($cmd) 
{ 
   $WshShell = new COM("WScript.Shell"); 
   $cwd = getcwd(); 
   if (strpos($cwd,' ')) 
   {  if ($pos = strpos($cmd, ' ')) 
      {  $cmd = substr($cmd, 0, $pos) . '" ' . substr($cmd, $pos); 
      } 
      else 
      {  $cmd .= '"'; 
      } 
      $cwd = '"' . $cwd; 
   }   
   $oExec = $WshShell->Run("cmd /C \" $cwd\\$cmd\"", 0,true); 
   
   return $oExec == 0 ? true : false; 
} 
