<?php
include_once(dirname(__FILE__).'/simulation_dat_conf.php');
    /****************
     �萔��`
    *****************/
    define( 'DEBUG' , true );                                   // �f�o�b�O�\���̐؂�ւ�   true/false
    define( 'CREATE_DATE' , date('Ymd') );                      // �쐬�N������萔��
    define( 'CREATE_FOLDER_NAME' , 'Sample_'.CREATE_DATE.'_' ); // �R���o�[�g��̃t�@�C�����o�͂���t�H���_���i�̈ꕔ�j
    define( 'LOG_FOLDER_NAME' , './log');                       // log�t�@�C�����o�͂���t�H���_��
    define( 'CREATE_LOG_NAME' , trim(basename(__FILE__), ".php").'_'.CREATE_DATE.'_' ); // log�t�@�C�����i�̈ꕔ�j

    $convertCt = 1; // 1����J�n�Ƃ���

    /****************
     header�ǂݍ���
    *****************/
    loadConverterHeader();
    switch($argc)
    {
      case 1:
        // �������^�����Ă��Ȃ��Ƃ�
        echo "�g�p���@: php.exe J:\git\stoaconverter\SToADataConverter.php �R���o�[�g���t�@�C�� \n";
        break;
      case 2:
        // ����Ɉ������^����ꂽ�Ƃ�
        $orgFilePath = $argv[1];
        /**************************************************************
         �����ɃR���o�[�g����������Ă��邩��log�t�@�C���̗L���Ŕ��f
        ***************************************************************/
        chdir(dirname(__FILE__));                   // �J�����g�f�B���N�g��������PHP�t�@�C���̏ꏊ�ɕύX
        debug_print( '�ygetcwd�z:'.getcwd());           // �J�����g���[�N�f�B���N�g���̕\��
        $targetDir = LOG_FOLDER_NAME;               // log�o�̓t�H���_�����Z�b�g
        checkAndMakeDir($targetDir, '0777', false); // �Ȃ���΍쐬
        $lists = fileListInTragetDir($targetDir);   // �t�@�C���ꗗ���擾
        debug_print("$targetDir====================");
        debug_print($lists);
        debug_print("=======================");
        // CREATE_LOG_NAME�Ƀ}�b�`����log�t�@�C�����𐔂���
        $Ct = count($lists);
        for($i=0;$i < $Ct;$i++){
            if(preg_match('/^'.CREATE_LOG_NAME.'/', $lists[$i]) == 1){
                if(!DEBUG){ // @DEBUG@ �f�o�b�O�\�����̓V�[�P���X�̃J�E���g�A�b�v�����Ȃ�
                    $convertCt++;
                }
            }
        }
        $sequenceName = str_pad($convertCt, 2, '0', STR_PAD_LEFT);
        writeLog('�ystart converter�z',$sequenceName);
        // �����log�t�@�C���̍쐬
        writeLog($str,$sequenceName);

        /*******************************************
         �R���o�[�g�t�@�C���̏o�͐�t�H���_�̍쐬
        ********************************************/
        $directory  = CREATE_FOLDER_NAME.$sequenceName;
        checkAndMakeDir($directory);

        /*******************************************
         log�t�@�C�����I�[�v��
        ********************************************/
        $handle = fopen($orgFilePath, "r");
        writeLog('�yfile open�z:'.$orgFilePath,$sequenceName);

        /*******************************************
         log�t�@�C���̃f�[�^��ǂݏo��
        ********************************************/
        while (!feof($handle)) {
            $tBuffer = fgets($handle);
            $buffer[] = trim($tBuffer);
            //�K���������Ă����K�v�̂�����̂�����΁A�����ŃJ�E���g
        }

        /*******************************************
         simulation_dat�������o��
        ********************************************/
        //�����o���t�@�C�����̐ݒ�
        $fileName   = $directory.'/'.'simulation_'.CREATE_DATE.'_'.$sequenceName.'.dat';

        $bufferCt = count($buffer);
        $keys = array_keys($dat_conf);
        $key = array_shift($keys);

        for($iCt=0;$iCt < $bufferCt;$iCt++){
            if(($iCt < 100 && DEBUG) || !DEBUG){
                if($buffer[$iCt] == $key){
                    $downLow = $dat_conf[$key]['downLow'];
                    $str = $buffer[$iCt + $downLow];
                    writeFile($fileName,$str);
                    if(count($keys) > 0){
writeLog('count($keys):'.count($keys),$sequenceName);
                        $key = array_shift($keys);
                    }else{
                        break;
                    }
                }
            }
            else{
                break;
            }
        }

        /*******************************************
         log�t�@�C�����N���[�Y
        ********************************************/
        fclose($handle);
        writeLog('�yfile close�z:'.$orgFilePath,$sequenceName);


        /*****************
         footer�ǂݍ���
        ******************/
        loadConverterFooter();
        writeLog('�yend   converter�z',$sequenceName);
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
        $str = "�ycreate directory�z:$dirName:$parm";
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
        # files�ɒǉ�
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
    // �t�@�C���쐬�ׂ̈�fopen�͍s��
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
