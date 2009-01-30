<?php
/* TODO
�Ƃ肠�����o���o���̃��[�v�Ńf�[�^���擾
�ŏI�I�ɓ����o�b�t�@���[�v���Ńf�[�^�擾��؂�ւ�����悤�ɒ���
*/
//include_once(dirname(__FILE__).'/simulation_conf.php');
include(dirname(__FILE__).'/simulation_conf.php');

    /****************
     �萔��`
    *****************/
//    define( 'DEBUG' , true );                                   // �f�o�b�O�\���̐؂�ւ�   true/false
    define( 'DEBUG' , false );                                   // �f�o�b�O�\���̐؂�ւ�   true/false
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
        print_r('�yfile open�z:'.$orgFilePath);echo "\n";

        /*******************************************
         log�t�@�C���̃f�[�^��ǂݏo��
        ********************************************/
        while (!feof($handle)) {
            $tBuffer = fgets($handle);
            $buffer[] = trim($tBuffer);
            //�����Ă����K�v�̂�����̂�����΁A�����ŃJ�E���g
        }
        print_r('�yfile reading�z:'.$orgFilePath);echo "\n";

        /*******************************************
         log�t�@�C�����N���[�Y
        ********************************************/
        fclose($handle);
        writeLog('�yfile close�z:'.$orgFilePath,$sequenceName);
        print_r('�yfile close�z:'.$orgFilePath);echo "\n";

        /*******************************************
         simulation_dat�������o��
        ********************************************/
        writeLog('simulation_dat start!!!',$sequenceName);
        print_r('simulation_dat start!!!');echo "\n";
        //�����o���t�@�C�����̐ݒ�
        $fileName   = $directory.'/'.'simulation_'.CREATE_DATE.'_'.$sequenceName.'.dat';

        $bufferCt = count($buffer);
        $keys = array_keys($dat_conf);
        $key = array_shift($keys);
        $floorNum = 1;
        $isAnyFloor = false;

        for($iCt=0;$iCt < $bufferCt;$iCt++){
            if(($iCt < 200 && DEBUG) || !DEBUG){
                if($buffer[$iCt] == $key){
                    writeLog('�y$key�z:'.$key,$sequenceName);
                    $downLow    = $dat_conf[$key]['downLow'];                           //�f�[�^�̂���s���m�F
                    writeLog('�y$downLow�z:'.$downLow,$sequenceName);
                    $isAnyFloor = isset($dat_conf[$key]['isAnyFloor']) ? true : false;  //�K�����̃f�[�^������̂��m�F

                    if($isAnyFloor) writeLog('�y$isAnyFloor�z:'.$isAnyFloor,$sequenceName);

                    for($floorCt=0;$floorCt < $floorNum;$floorCt++){
                        if(!$isAnyFloor && $floorCt > 0) break;
                        $str = $buffer[$iCt + $downLow + $floorCt]; //�f�[�^�̎擾
                        writeLog('thisLoopBuffor:'.$str,$sequenceName);
                        $pat = '/('.$dat_conf[$key]['pattern'].')/';
                        writeLog('$pat:'.$pat,$sequenceName);
                        //$str = preg_replace($pat, \1, $str);
                        preg_match($pat, $str, $match);
                        array_shift($match);
                        $str = join(', ', $match);
                        writeLog('replaced:'.$str,$sequenceName);

                        if($key == FLOOR_NUM_KEY){
                            $str = trim($str,'�f�[�^�擾�����F');
                            $str = trim($str);
                            $floorNum = intVal($str);       //�K�����擾
                            writeLog('��$floorNum:'.$floorNum,$sequenceName);
                        }
                                                                    //�f�[�^�����H����
                        $tmpData[] = $str;
                    }

                    $str = str_pad($dat_conf[$key]['name'], 20, ' ')
                         . str_pad(join(', ', $tmpData)   , 15, ' ')
                         . $dat_conf[$key]['descript'];
                    unset($tmpData);
                    writeFile($fileName,$str);                  //�t�@�C���ɏ����o��

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
        print_r('simulation_dat end!!!');echo "\n";

        /*******************************************
         eventLog�������o��
        ********************************************/
        writeLog('eventLog stert!!!',$sequenceName);
        print_r('eventLog stert!!!');echo "\n";
        $div = 1;
        //�����o���t�@�C�����̐ݒ�
        $eventLogFileName[1]   = $directory.'/'.'eventlog_'.CREATE_DATE.'_'.$sequenceName.'_1.csv';
        $eventLogFileName[2]   = $directory.'/'.'eventlog_'.CREATE_DATE.'_'.$sequenceName.'_2.csv';
        $cagePersonsPast = 0;   //�O��J�S�l��

        $startStr       = 'Final Local Time';
        $endStr         = 'EleLife SummarySimulation\s*\d\s*Start';
        $startFinded    = false;
        $headerWritn    = false;

        //header�̐ݒ�
        $header     = 'Now----, Next---, Cage, LocalTime--, Pos, hw---------, Dep, Arr, SD, Clt--------, Ton, Tof, Hc-';
        // �����o�����[�v�J�n
        for($iCt=0;$iCt < $bufferCt;$iCt++){
            if(preg_match('/'.$endStr.'/', $buffer[$iCt]) == 1){  //�I��������s�Ƀ}�b�`������
                $startFinded = false;   //�J�n������s�����t���O��false
                $headerWritn = false;   //�w�b�_�s�������o�������t���O��False
                $div++;
                writeLog('����MATCH�I����',$sequenceName);
                writeLog('eventLog pat:/'.$endStr.'/',$sequenceName);
                writeLog("eventLog buffer[$iCt]:".$buffer[$iCt],$sequenceName);
                continue;
            }

            if(preg_match('/'.$startStr.'/', $buffer[$iCt]) == 1) {
                $startFinded = true;
                writeLog('����MATCH�I����',$sequenceName);
                writeLog('eventLog pat:/'.$startStr.'/',$sequenceName);
                writeLog("eventLog buffer[$iCt]:".$buffer[$iCt],$sequenceName);
                continue;
            }
            //�J�n������s�Ƀ}�b�`������J�n������s�����t���O��true
            if($startFinded != true) continue;  //�J�n������s��false�Ȃ�A�R���e�B�j���[
            if($startFinded == true){           //�J�n������s��true�Ȃ�
                if($headerWritn == false){          //�w�b�_�s�������o�������t���O��false�Ȃ�
                    $headerWritn    = true;             //�w�b�_�s�������o�������t���O��true
                    writeLog("eventLogFileName[$div]:".$eventLogFileName[$div],$sequenceName);
                    writeFile($eventLogFileName[$div],$header); //header�̏����o��
                }
            }

            $str = $buffer[$iCt]; //�f�[�^�̎擾
            //�f�[�^�����H����
            // match�Ńf�[�^���擾
            //Now, Next, Cage(div), LocalTime(clt), Pos, hw, Dep, Arr,  SD, Clt, Ton, Tof, Hc
            //Now, Next,       itv,          Cycle, Pos, hw, Dep, Arr, Dir, Clt,           Hc
            $eventPat = '/^[^\d]+(\d+\.[A-Z]{4})[^\d]+(\d+\.[A-Z]{4})[^\d]+([0-9\.]+)[^\d]+([0-9\.]+)[^\d]+([0-9\.]+)[^\d]+([0-9\.e\+\-]+)[^\d]+(\d+)[^\d]+(\d+)[^\d]*Dir=([A-Z\-]{2})[^\d]+([0-9\.e\+\-]+)[^\d]+(\d+)/';
            preg_match($eventPat, $str, $match);
            writeLog('$eventPat:'.$eventPat,$sequenceName);
            writeLog('$str:'.$str,$sequenceName);
            array_shift($match);
            //��~�l���̎Z�o(��~�̃^�C�~���O�͕K�������̂łǂ��炩�̂�)
            $ton = 0;//���
            $tof = 0;//�~��
            $cagePersonsDiff = $match[10] - $cagePersonsPast;
            if ($cagePersonsDiff > 0) { // �O���葝������
                $ton = $cagePersonsDiff;    //ton�ɐݒ�
            } else
            if ($cagePersonsDiff < 0) { // ��������
                $tof = abs($cagePersonsDiff);    //tof�ɐݒ�(�}�C�i�X�Ȃ̂Ő�Βl�ɂ���)
            }
            $cagePersonsPast = $match[10];  // ����̒l��ۑ�
            
            // ���т�����
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
            // �J���}�ŘA��
            $str = join(', ', $sortedEventData);
            writeLog('sortedEventData�i'.$iCt.'�j:'.$str,$sequenceName);

            writeFile($eventLogFileName[$div],$str);                  //�t�@�C���ɏ����o��
        }
        writeLog('eventLog end!!!',$sequenceName);
        print_r('eventLog end!!!');echo "\n";

        /*******************************************
         cageCall�������o��
        ********************************************/
        writeLog('cageCall stert!!!',$sequenceName);
        print_r('cageCall stert!!!');echo "\n";
        $div = 1;
        //�����o���t�@�C�����̐ݒ�
        $cagecallFileName[1]   = $directory.'/'.'cagecall_'.CREATE_DATE.'_'.$sequenceName.'_1.csv';
        $cagecallFileName[2]   = $directory.'/'.'cagecall_'.CREATE_DATE.'_'.$sequenceName.'_2.csv';

        $startStr       = 'EleLife SummarySimulation\s*\d\s*Start';
        $endStr         = 'TotalCallWaitIntervalTimeSec';
        $startFinded    = false;
        $headerWritn    = false;

        for($iCt=0;$iCt < $bufferCt;$iCt++){
            if(preg_match('/'.$endStr.'/', $buffer[$iCt + 1]) == 1){  //�I��������s�Ƀ}�b�`������
                $startFinded = false;   //�J�n������s�����t���O��false
                $div++;
                writeLog('��MATCH�IendStr',$sequenceName);
                writeLog('cageCall pat:/'.$endStr.'/',$sequenceName);
                writeLog("cageCall buffer[$iCt]:".$buffer[$iCt],$sequenceName);
                continue;
            }

            if(preg_match('/'.$startStr.'/', $buffer[$iCt]) == 1) {
                $startFinded = true;
                writeLog('��MATCH�IStertStr',$sequenceName);
                writeLog('cageCall pat:/'.$startStr.'/',$sequenceName);
                writeLog("cageCall buffer[$iCt]:".$buffer[$iCt],$sequenceName);
                continue;
            }
            //�J�n������s�Ƀ}�b�`������J�n������s�����t���O��true
            if($startFinded != true) continue;  //�J�n������s��false�Ȃ�A�R���e�B�j���[
            $str = $buffer[$iCt]; //�f�[�^�̎擾
            //�f�[�^�̉��H
            $str = str_pad($str, 95, ' ' ,STR_PAD_LEFT);
            writeFile($cagecallFileName[$div],$str);                  //�t�@�C���ɏ����o��
        }
        writeLog('cageCall end!!!',$sequenceName);
        print_r('cageCall end!!!');echo "\n";

        /*****************
         footer�ǂݍ���
        ******************/
        loadConverterFooter();
        writeLog('�yend   converter�z',$sequenceName);
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
        // files�ɒǉ�
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
    $len = trim($str);
    //$str = trim($str);
    // �t�@�C���쐬�ׂ̈�fopen�͍s��
    $handle = fopen($fileName, "a");
    if(strlen($len) > 0){
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