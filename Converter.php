<?php

    $names[]   = 'BISAII';
    $names[]   = 'BISAII777';
    $names[]   = 'CHOUSHISHI';
    $names[]   = 'GIFUIWATATA';
    $names[]   = 'GLOBALHANDA';
    $names[]   = 'GLOBALKIRA';
    $names[]   = 'HONNKANN';
    $names[]   = 'ISHIOKAKA';
    $names[]   = 'MANNBA';
    $names[]   = 'MDOOZONENE';
    $names[]   = 'MDOOZONENE2';
    $names[]   = 'MDTSUSHIMAMA';
    $names[]   = 'MENTE';
    $names[]   = 'PALOTTSUSHIMA';
    $names[]   = 'SHIROYAMAMACEO';
    $names[]   = 'TOMITATA';

    $names[]   = 'GLOBAL-HANDA';
    $names[]   = 'KAGAMI';
    $names[]   = 'KANRIBU';
    $names[]   = 'suuchi';
    $names[]   = 'CHOUSHI';
    $names[]   = 'TIME';
    $names[]   = 'CEO';
    $names[]   = 'MD-oozone2';
    $names[]   = 'kugi';
    $names[]   = 'samura';
    $names[]   = 'bisai';
    $names[]   = 'bisai777';
    $names[]   = 'md-oozone';
    $names[]   = 'bisaioonishi';
    $names[]   = 'bisia';##�ł��ԈႦ���ۂ�
    $names[]   = 'minte';##�ł��ԈႦ���ۂ�
    $names[]   = 'honbu2';##�ł��ԈႦ���ۂ�
    $names[]   = 'hommkann';##�ł��ԈႦ���ۂ�
    $names[]   = 'md-tushima';##�ł��ԈႦ���ۂ�
    $names[]   = 'mdTSUSHIMA';
    $names[]   = 'tsushimat';##�e�X�g�H�H
    $names[]   = 'tsushimaa';##�e�X�g�H�H
    $names[]   = 'thushimat';##�e�X�g�H�H
    $names[]   = 'thushimaa';##�e�X�g�H�H
    $names[]   = 'manbam';##�e�X�g�H�H
    $names[]   = 'manbaa';##�e�X�g�H�H
    $names[]   = 'mdOOZONE';##�ł��ԈႦ�H�H�@�e�X�g�H�H
    $names[]   = 'mdOOZONE2';##�ł��ԈႦ�H�H�@�e�X�g�H�H
    $names[]   = 'MIKADO';##�e�X�g�H�H

#    define( 'SAVEFILE' , './output.log' );
#$name = $names[19];
#    $reg    = "/^(\d{1,3}\.){3}\d{1,3},\s{$name},/i";
#        $reg    = "/^(\d{1,3}\.){3}\d{1,3},\s{$name},\s\d{4}\/\d{2}\/\d{2},\s\d{2}:\d{2}:\d{2},\sW3SVC1,\sPOPPY,\s192\.168\.30\.17,\s\d{1,5},\s\d{1,5},\s\d{1,5},\s\d{1,5},\s\d{1,5},\s.{3,4},\s.*\/(login\.asp|HqMain\.asp|HallMain\.asp),/i";
#    $reg    = "/^192.168.11.14,\s/i";

    $directory  = './data';

for($iCt=0;$iCt < count($names);$iCt++){
#    $savefile = $name;
    $savefile = $names[$iCt];
    $reg    = "/^(\d{1,3}\.){3}\d{1,3},\s{$savefile},\s\d{4}\/\d{2}\/\d{2},\s\d{2}:\d{2}:\d{2},\sW3SVC1,\sPOPPY,\s192\.168\.30\.17,\s\d{1,5},\s\d{1,5},\s\d{1,5},\s\d{1,5},\s\d{1,5},\s.{3,4},\s.*\/(login\.asp|HqMain\.asp|HallMain\.asp|HqMenu\.asp),/i";
    $files  = array();

    $drc = dir( $directory );
    while( $fl = $drc->read() ){
        if( $fl == '.' OR $fl == '..' ){ continue; }
        # files�ɒǉ�
        $files[]    = $directory . "/" . $fl;
    }

    sort( $files );

    for ( $i = 0 ; $i < count( $files ) ; $i++ ) {
        getRow( $files[$i] , $reg ,$savefile);
    }
}

    function getRow( $file , $reg ,$savefile)
    {
        echo $file . "::".$savefile."<BR>";
        $fp     = fopen( $file , 'r' )
            or die( 'end' );
        $result = array();

        while ( !feof( $fp ) ) {
            $row = fgets( $fp , 1024 );
            if ( preg_match( $reg , $row ) ) {
                $result[]   = $row;
                echo $row . "::".$savefile."<BR>";
            }
        }
        fclose( $fp );
//        if ( count( $result ) > 0 ) {
            writeFile( join( "" , $result ) ,$savefile);
//        }
    }


    function writeFile( $row ,$savefile)
    {
#        $fp = fopen( SAVEFILE , "a" );
        $fp = fopen( "./".$savefile.".log" , "a" );
        fwrite( $fp , $row );
        fclose( $fp );
    }

    exit;
    echo "�ȉ��\�[�X<BR />";
    echo "<PRE>";


    echo '
    $file   = \'./list.txt\';

    $name   = \'kugi\';

    $fp     = fopen( $file , \'r\' );
    // IP�A�h���X����n�܂�R�����A�X�y�[�X�A�����i���O�j�A�R����
    $reg    = "/^(\d{1,3}\.){3}\d{1,3},\s{$name},/";

    while ( !feof( $fp ) ) {
        $row = fgets( $fp , 1024 );
        if ( preg_match( $reg , $row ) ) {
            echo  "&lt;font color=red&gt;hit&lt;/font&gt;";
        }
        echo $row . "&lt;BR&gt;\n";
    }
    fclose( $fp );
    ';



    echo "</PRE>";
    exit;



    $str[]    = "�����@�G���x�[�^";
    $str[]    = "���� �G���x�[�^";
    $str[]    = "�����G���x�[�^";
    $str[]    = "����aaa�G���x�[�^";
    echo "���K�\��      /����.*�G���x�[�^/<BR>";
    echo "<HR>";

    foreach( $str as $word ) {
        echo $word . " =====&gt;<br>";
        if ( preg_match( "/����.*�G���x�[�^/" , $word ) ) {
            echo "�@�@�@hit<BR>\n";
        } else {
            echo "�@�@�@not hit<BR>\n";
        }
    }
