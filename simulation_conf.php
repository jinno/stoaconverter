<?php
$dat_conf['・シミュレーション時間']['name']             = 'TransitTime:';
$dat_conf['・シミュレーション時間']['descript']         = '//シミュレーション時間';
$dat_conf['・シミュレーション時間']['downLow']          = 1;
$dat_conf['・シミュレーション時間']['pattern']          = '[0-9\.]+';

$dat_conf['・建物数']['name']                           = 'BuildNum:';
$dat_conf['・建物数']['descript']                       = '//建物数';
$dat_conf['・建物数']['downLow']                        = 1;
$dat_conf['・建物数']['pattern']                        = '[0-9\.]+';

$dat_conf['・エレベータ数']['name']                     = 'ElevNum:';
$dat_conf['・エレベータ数']['descript']                 = '//エレベータ数';
$dat_conf['・エレベータ数']['downLow']                  = 2;
$dat_conf['・エレベータ数']['pattern']                  = '[0-9\.]+';

$dat_conf['・建物最高階床数']['name']                   = 'FloorJoist:';
$dat_conf['・建物最高階床数']['descript']               = '//階床数';
$dat_conf['・建物最高階床数']['downLow']                = 1;
$dat_conf['・建物最高階床数']['pattern']                = '[0-9\.]+';
define('FLOOR_NUM_KEY', '・建物最高階床数');

$dat_conf['・交通パターン']['name']                     = 'TrafficPattern:';
$dat_conf['・交通パターン']['descript']                 = '//交通パターン(1.出勤時�@,2.出勤時�A,3.退勤時�@,4.退勤時�A,5.平常時,6.混雑時)';
$dat_conf['・交通パターン']['downLow']                  = 1;
$dat_conf['・交通パターン']['pattern']                  = '[0-9\.]+';

$dat_conf['・上り集中率']['name']                       = 'UpJamRate:';
$dat_conf['・上り集中率']['descript']                   = '//上り集中率';
$dat_conf['・上り集中率']['downLow']                    = 1;
$dat_conf['・上り集中率']['pattern']                    = '[0-9\.]+';

$dat_conf['・下り集中率']['name']                       = 'DownJamRate:';
$dat_conf['・下り集中率']['descript']                   = '//下り集中率';
$dat_conf['・下り集中率']['downLow']                    = 1;
$dat_conf['・下り集中率']['pattern']                    = '[0-9\.]+';

$dat_conf['・乗降割合']['name']                         = 'BoardingRate:';
$dat_conf['・乗降割合']['descript']                     = '//乗降割合';
$dat_conf['・乗降割合']['downLow']                      = 1;
$dat_conf['・乗降割合']['pattern']                      = '[0-9\.]+';

$dat_conf['・居住者数']['name']                         = 'InhabitantNum:';
$dat_conf['・居住者数']['descript']                     = '//居住者数';
$dat_conf['・居住者数']['downLow']                      = 2;
$dat_conf['・居住者数']['isAnyFloor']                   = true;
$dat_conf['・居住者数']['pattern']                      = '[0-9\.]{6,}';
//add

$dat_conf['・各階高']['name']                           = 'FloorHeight:';
$dat_conf['・各階高']['descript']                       = '//階高';
$dat_conf['・各階高']['downLow']                        = 2;
$dat_conf['・各階高']['isAnyFloor']                     = true;
$dat_conf['・各階高']['pattern']                        = '[0-9\.]{6,}';
//add

$dat_conf['・かご数']['name']                           = 'CageNum:';
$dat_conf['・かご数']['descript']                       = '//かご数';
$dat_conf['・かご数']['downLow']                        = 2;
$dat_conf['・かご数']['pattern']                        = '\d+$';

$dat_conf['・かごデータ(分速)']['name']                 = 'VelMin:';
$dat_conf['・かごデータ(分速)']['descript']             = '//分速';
$dat_conf['・かごデータ(分速)']['downLow']              = 1;
$dat_conf['・かごデータ(分速)']['pattern']              = '[0-9\.]+$';

$dat_conf['・かごデータ(加減速距離)']['name']           = 'AccDist:';
$dat_conf['・かごデータ(加減速距離)']['descript']       = '//加減速距離';
$dat_conf['・かごデータ(加減速距離)']['downLow']        = 1;
$dat_conf['・かごデータ(加減速距離)']['pattern']        = '[0-9\.]+$';

$dat_conf['・かごデータ(加減速時間)']['name']           = 'AccTime:';
$dat_conf['・かごデータ(加減速時間)']['descript']       = '//加減速時間';
$dat_conf['・かごデータ(加減速時間)']['downLow']        = 1;
$dat_conf['・かごデータ(加減速時間)']['pattern']        = '[0-9\.]+$';

$dat_conf['・かごデータ(扉開時間)']['name']             = 'DoorOpenTime:';
$dat_conf['・かごデータ(扉開時間)']['descript']         = '//扉開時間';
$dat_conf['・かごデータ(扉開時間)']['downLow']          = 1;
$dat_conf['・かごデータ(扉開時間)']['pattern']          = '[0-9\.]+$';

$dat_conf['・かごデータ(扉閉時間)']['name']             = 'DoorCloseTime:';
$dat_conf['・かごデータ(扉閉時間)']['descript']         = '//扉閉時間';
$dat_conf['・かごデータ(扉閉時間)']['downLow']          = 1;
$dat_conf['・かごデータ(扉閉時間)']['pattern']          = '[0-9\.]+$';

$dat_conf['・かごデータ(デッドタイム)']['name']         = 'DeadTime:';
$dat_conf['・かごデータ(デッドタイム)']['descript']     = '//デッドタイム';
$dat_conf['・かごデータ(デッドタイム)']['downLow']      = 1;
$dat_conf['・かごデータ(デッドタイム)']['pattern']      = '[0-9\.]+$';

$dat_conf['・かごデータ(乗車定員)']['name']             = 'Capacity:';
$dat_conf['・かごデータ(乗車定員)']['descript']         = '//乗車定員';
$dat_conf['・かごデータ(乗車定員)']['downLow']          = 1;
$dat_conf['・かごデータ(乗車定員)']['pattern']          = '[0-9\.]+$';

?>