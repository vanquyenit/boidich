<html>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
<body>
<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
$timeZone = 7.0;

function INT($d) {
    return floor($d);
}

function jdFromDate($dd, $mm, $yy) {

    $a = INT((14 - $mm) / 12);
    $y = $yy + 4800 - $a;
    $m = $mm + 12 * $a - 3;
    $jd = $dd + INT((153 * $m + 2) / 5) + 365 * $y + INT($y / 4) - INT($y / 100) + INT($y / 400) - 32045;
    if ($jd < 2299161) {
        $jd = $dd + INT((153* $m + 2)/5) + 365 * $y + INT($y / 4) - 32083;
    }
    return $jd;
}

function jdToDate($jd) {

    if ($jd > 2299160) { // After 5/10/1582, Gregorian calendar
        $a = $jd + 32044;
        $b = INT((4*$a+3)/146097);
        $c = $a - INT(($b*146097)/4);
    } else {
        $b = 0;
        $c = $jd + 32082;
    }
    $d = INT((4*$c+3)/1461);
    $e = $c - INT((1461*$d)/4);
    $m = INT((5*$e+2)/153);
    $day = $e - INT((153*$m+2)/5) + 1;
    $month = $m + 3 - 12*INT($m/10);
    $year = $b*100 + $d - 4800 + INT($m/10);
    //echo "day = $day, month = $month, year = $year\n";
    return array($day, $month, $year);
}

function getNewMoonDay($k, $timeZone) {

    $T = $k/1236.85; // Time in Julian centuries from 1900 January 0.5
    $T2 = $T * $T;
    $T3 = $T2 * $T;
    $dr = M_PI/180;
    $Jd1 = 2415020.75933 + 29.53058868*$k + 0.0001178*$T2 - 0.000000155*$T3;
    $Jd1 = $Jd1 + 0.00033*sin((166.56 + 132.87*$T - 0.009173*$T2)*$dr); // Mean new moon
    $M = 359.2242 + 29.10535608*$k - 0.0000333*$T2 - 0.00000347*$T3; // Sun's mean anomaly
    $Mpr = 306.0253 + 385.81691806*$k + 0.0107306*$T2 + 0.00001236*$T3; // Moon's mean anomaly
    $F = 21.2964 + 390.67050646*$k - 0.0016528*$T2 - 0.00000239*$T3; // Moon's argument of latitude
    $C1=(0.1734 - 0.000393*$T)*sin($M*$dr) + 0.0021*sin(2*$dr*$M);
    $C1 = $C1 - 0.4068*sin($Mpr*$dr) + 0.0161*sin($dr*2*$Mpr);
    $C1 = $C1 - 0.0004*sin($dr*3*$Mpr);
    $C1 = $C1 + 0.0104*sin($dr*2*$F) - 0.0051*sin($dr*($M+$Mpr));
    $C1 = $C1 - 0.0074*sin($dr*($M-$Mpr)) + 0.0004*sin($dr*(2*$F+$M));
    $C1 = $C1 - 0.0004*sin($dr*(2*$F-$M)) - 0.0006*sin($dr*(2*$F+$Mpr));
    $C1 = $C1 + 0.0010*sin($dr*(2*$F-$Mpr)) + 0.0005*sin($dr*(2*$Mpr+$M));

    if ($T < -11) {
        $deltat= 0.001 + 0.000839*$T + 0.0002261*$T2 - 0.00000845*$T3 - 0.000000081*$T*$T3;
    } else {
    $deltat= -0.000278 + 0.000265*$T + 0.000262*$T2;
    };
    $JdNew = $Jd1 + $C1 - $deltat;
    //echo "JdNew = $JdNew\n";
    return INT($JdNew + 0.5 + $timeZone/24);
}

function getSunLongitude($jdn, $timeZone) {

    $T = ($jdn - 2451545.5 - $timeZone/24) / 36525; // Time in Julian centuries from 2000-01-01 12:00:00 GMT
    $T2 = $T * $T;
    $dr = M_PI/180; // degree to radian
    $M = 357.52910 + 35999.05030*$T - 0.0001559*$T2 - 0.00000048*$T*$T2; // mean anomaly, degree
    $L0 = 280.46645 + 36000.76983*$T + 0.0003032*$T2; // mean longitude, degree
    $DL = (1.914600 - 0.004817*$T - 0.000014*$T2)*sin($dr*$M);
    $DL = $DL + (0.019993 - 0.000101*$T)*sin($dr*2*$M) + 0.000290*sin($dr*3*$M);
    $L = $L0 + $DL; // true longitude, degree
    //echo "\ndr = $dr, M = $M, T = $T, DL = $DL, L = $L, L0 = $L0\n";
    // obtain apparent longitude by correcting for nutation and aberration
    $omega = 125.04 - 1934.136 * $T;
    $L = $L - 0.00569 - 0.00478 * sin($omega * $dr);
    $L = $L*$dr;
    $L = $L - M_PI*2*(INT($L/(M_PI*2))); // Normalize to (0, 2*PI)
 return INT($L/M_PI*6);
}

function getLunarMonth11($yy, $timeZone) {

    $off = jdFromDate(31, 12, $yy) - 2415021;
    $k = INT($off / 29.530588853);
    $nm = getNewMoonDay($k, $timeZone);
    $sunLong = getSunLongitude($nm, $timeZone); // sun longitude at local midnight

    if ($sunLong >= 9) {
        $nm = getNewMoonDay($k-1, $timeZone);
    }

 return $nm;
}

function getLeapMonthOffset($a11, $timeZone) {

    $k = INT(($a11 - 2415021.076998695) / 29.530588853 + 0.5);
    $last = 0;
    $i = 1; // We start with the month following lunar month 11
    $arc = getSunLongitude(getNewMoonDay($k + $i, $timeZone), $timeZone);

    do {
        $last = $arc;
        $i = $i + 1;
        $arc = getSunLongitude(getNewMoonDay($k + $i, $timeZone), $timeZone);
    } while ($arc != $last && $i < 14);
    return $i - 1;
}

/* Comvert solar date dd/mm/yyyy to the corresponding lunar date */
function convertSolar2Lunar($dd, $mm, $yy, $timeZone) {

    $dayNumber = jdFromDate($dd, $mm, $yy);
    $k = INT(($dayNumber - 2415021.076998695) / 29.530588853);
    $monthStart = getNewMoonDay($k+1, $timeZone);

    if ($monthStart > $dayNumber) {
        $monthStart = getNewMoonDay($k, $timeZone);
    }

    $a11 = getLunarMonth11($yy, $timeZone);
    $b11 = $a11;

    if ($a11 >= $monthStart) {
        $lunarYear = $yy;
        $a11 = getLunarMonth11($yy-1, $timeZone);
    } else {
        $lunarYear = $yy+1;
        $b11 = getLunarMonth11($yy+1, $timeZone);
    }

    $lunarDay = $dayNumber - $monthStart + 1;
    $diff = INT(($monthStart - $a11)/29);
    $lunarLeap = 0;
    $lunarMonth = $diff + 11;

    if ($b11 - $a11 > 365) {

        $leapMonthDiff = getLeapMonthOffset($a11, $timeZone);

        if ($diff >= $leapMonthDiff) {

            $lunarMonth = $diff + 10;

            if ($diff == $leapMonthDiff) {
                $lunarLeap = 1;
            }   
        }
    }

    if ($lunarMonth > 12) {
        $lunarMonth = $lunarMonth - 12;
    }

    if ($lunarMonth >= 11 && $diff < 4) {
        $lunarYear -= 1;
    }
    return array($lunarDay, $lunarMonth, $lunarYear, $lunarLeap);
}

/* Convert a lunar date to the corresponding solar date */
function convertLunar2Solar($lunarDay, $lunarMonth, $lunarYear, $lunarLeap, $timeZone) {

    if ($lunarMonth < 11) {
        $a11 = getLunarMonth11($lunarYear-1, $timeZone);
        $b11 = getLunarMonth11($lunarYear, $timeZone);
    } else {
        $a11 = getLunarMonth11($lunarYear, $timeZone);
        $b11 = getLunarMonth11($lunarYear+1, $timeZone);
    }

    $k = INT(0.5 + ($a11 - 2415021.076998695) / 29.530588853);
    $off = $lunarMonth - 11;

    if ($off < 0) {
        $off += 12;
    }

    if ($b11 - $a11 > 365) {
        $leapOff = getLeapMonthOffset($a11, $timeZone);
        $leapMonth = $leapOff - 2;

        if ($leapMonth < 0) {
            $leapMonth += 12;
        }
        if ($lunarLeap != 0 && $lunarMonth != $leapMonth) {
            return array(0, 0, 0);
        } else if ($lunarLeap != 0 || $off >= $leapOff) {
            $off += 1;
        }
    }

    $monthStart = getNewMoonDay($k + $off, $timeZone);
    return jdToDate($monthStart + $lunarDay - 1);
}

function can($sodu) {

    $arr = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
    return $arr[$sodu];
}

function chi($sodu) {

    $arr = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
    return $arr[$sodu];
}


// Can chi của năm

function canNam($yy) {

    $sodu = ($yy + 6) % 10;
    return can($sodu);
}

function chiNam($yy) {

    $sodu = ($yy + 8) % 12;
    return chi($sodu);
}


//Can chi tháng

function canThang($mm, $dd) {

    $sodu = ($dd* 12+ $mm + 3) % 10;
    return can($sodu);
}

function chiThang($mm) {

    $N = ($mm + 1) % 12;
    return chi($N);
}


//Can chi của ngày

function canNgay($jd) {

    $sodu = INT(($jd + 9) % 10);

    return $sodu;
}

function chiNgay($jd) {

    $sodu = ($jd + 1) % 12;
    return $sodu;
}

function canGio($jd) {

    $sodu = ($jd - 1) * 2 % 10;
    return $sodu;
}


//Đổi giờ Dương lịch sang giờ Âm lịch
// $mm mảng tháng Âm lịch
// $hh là giờ dương
// $ii phút dương
// Trả về giờ $key

function gioAmLich($mm, $hh, $ii) {

    $hi = ($hh * 60) + $ii;
    $arTime = [0, 2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22];
    $arMonth = [
        1     => 30,
        2     => 40,
        3     => 50,
        4     => 60,
        5     => 70,
        6     => 60,
        7     => 50,
        8     => 40,
        9     => 30,
        10    => 20,
        11    => 10,
        12    => 20
    ];

    foreach ($arTime as $key => $value) {
        $value1 = $arMonth[$arrLichAm[1]] + $value * 60;
        $value2 = $value1 + 2 * 60;

        if($value1 <= $hi && $hi < $value2) {
            return $key;
        }
    }
}

function nguHanh($input) {

    $arr = ['Kim', 'Thuỷ', 'Mộc', 'Hoả', 'Thổ'];
    return $arr[$input];
}

function tuongKhac($input) {

    $arrKhac = [
        0 => 2,
        1 => 3,
        2 => 4,
        3 => 0,
        4 => 1 
    ];
    return $arrKhac[$input];
}

function tuongSinh($input) {
    
    $arSinh = [
        0 => 1,
        1 => 2,
        2 => 3,
        3 => 4,
        4 => 0
    ];
    return $arrSinh[$input];
}

//Vượng tướng

function vuongTuong($input) {

    $arr = ['Vượng', 'Tướng', 'Hưu', 'Tù', 'Tử'];
    return $arr[$input];
}


// Bốn mùa vượng tướng

function vuong($mm) {

    $arr = [
        1 => 2,
        2 => 2,
        3 => 4,
        4 => 3,
        5 => 3,
        6 => 4,
        7 => 0,
        8 => 0,
        9 => 4,
        10 => 1,
        11 => 1,
        12 => 4
    ];
    return $arr[$mm];
}

function tuong($mm) {

    $arr = [
        1 => 3,
        2 => 3,
        3 => 0,
        4 => 4,
        5 => 4,
        6 => 0,
        7 => 1,
        8 => 1,
        9 => 0,
        10 => 3,
        11 => 3,
        12 => 0
    ];
    return $arr[$mm];
}

function huu($mm) {
    
    $arr = [
        1 => 1,
        2 => 1,
        3 => 3,
        4 => 2,
        5 => 2,
        6 => 3,
        7 => 4,
        8 => 4,
        9 => 3,
        10 => 0,
        11 => 0,
        12 => 3
    ];
    return $arr[$mm];
}

function tu($mm) {
    
    $arr = [
        1 => 0,
        2 => 0,
        3 => 2,
        4 => 1,
        5 => 1,
        6 => 2,
        7 => 3,
        8 => 3,
        9 => 2,
        10 => 4,
        11 => 4,
        12 => 2
    ];
    return $arr[$mm];
}

function tuw($mm) {

    $arr = [
        1 => 4,
        2 => 4,
        3 => 1,
        4 => 0,
        5 => 0,
        6 => 1,
        7 => 2,
        8 => 2,
        9 => 1,
        10 => 3,
        11 => 3,
        12 => 1
    ];
    return $arr[$mm];
}

function mua($mm) {

    switch($mm){
        case $mm < 4 : return 'Xuân';
        case $mm < 7 : return 'Hạ';
        case $mm < 10 : return 'Thu';
        default: return 'Đông';
    }
}

function batquai($input) {

    $arr = ['Càn', 'Khảm', 'Cấn', 'Chấn', 'Tốn', 'Ly', 'Khôn', 'Đoài'];
    return $arr[$input];
}

function batmon($input) {

    $arr = ['Khai', 'Hưu', 'Sanh', 'Thương', 'Đổ', 'Kiển', 'Tử', 'Kinh'];
    return $arr[$input];
}

// Khởi tháng nào tại cung nào
// $mm mảng tháng Âm lịch
// Trả về cung $arr

function thangkhoi($mm) {

    $arr = [
        1 => 2,
        2 => 3,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 5,
        7 => 6,
        8 => 7,
        9 => 7,
        10 => 0,
        11 => 1,
        12 => 1
    ];
    return $arr[$mm];
}

// Độn quẻ bát môn
// $dd ngày âm lịch
// $mm tháng âm lịch
// $hh giờ âm lịch
// Trả về cung $cung

function donque($dd, $mm, $hh) {

    $thangKhoi = thangkhoi($mm);
    $cung = ($dd + $hh + $thangKhoi - 1) % 8;
    return $cung;
}


function khongvong($jd) {
    $canNgay = canNgay($jd);
    $can = 9 - $canNgay;
    $chiNgay = chiNgay($jd);
    $ngay = $chiNgay + $can;

    if($ngay > 11) {
        $ngay = $ngay - 11;
    }

    $ngay1 = $ngay + 1;
    if($ngay1 > 11) {
        $ngay1 = $ngay1 - 11;
    }

    $arr = [
        0 => 1,
        1 => 2,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 4,
        6 => 5,
        7 => 6,
        8 => 6,
        9 => 7,
        10 => 0,
        11 => 0
    ];
    return array($ngay, $ngay1, $arr[$ngay], $arr[$ngay1]);
}

function GetInfoDate($dd, $mm, $yy, $hh, $ii, $timeZone) {

    $al = convertSolar2Lunar($dd, $mm, $yy, $timeZone);
    $jd = jdFromDate($dd, $mm, $yy);
    $gio = gioAmLich($al[1], $hh, $ii);
    $chiGio = chi($gio);
    $canGio = can(canGio($jd));
    $canNgay = can(canNgay($jd));
    $chiNgay = chi(chiNgay($jd));
    $canThang = canThang($al[1], $al[2]);
    $chiThang = chiThang($al[1]);
    $canNam = canNam($al[2]);
    $chiNam = chiNam($al[2]);
    $khongvong = khongvong($jd);
    if ($al[3] == 1) {
        $nhuan = " Nhuận";
    } else {
        $nhuan = "";
    }
    if($al[1] == 3 || $al[1] == 6 || $al[1] == 9 || $al[1] == 12) {
        $chuyen = " (Tứ quý)";
    } else {
        $chuyen = "";
    }
if($khongvong[2] == $khongvong[3]){
    $khongvongmon = batmon($khongvong[2]);
    $khongvongque = batquai($khongvong[2]);
} else {
    $khongvongmon = batmon($khongvong[2]). ' - '.batmon($khongvong[3]);
    $khongvongque = batquai($khongvong[2]). ' - '.batquai($khongvong[3]);
}

    $arr = [
        'duong_lich' => "$dd/$mm/$yy",
        'am_lich' => "$al[0]/$al[1]/$al[2]$nhuan",
        'gio_am' => "Giờ $canGio $chiGio",
        'ngay_am' => "Ngày $canNgay $chiNgay",
        'thang_am' => "Tháng $canThang $chiThang",
        'nam_am' => "Năm $canNam $chiNam",
        'mua' => 'Mùa ' . mua($al[1]) . " - ". nguHanh(vuong($al[1])).$chuyen,
        'vuong_tuong' => [
            0 => nguHanh(vuong($al[1])) ." - ". vuongTuong(vuong($al[1])),
            1 => nguHanh(tuong($al[1])) ." - ". vuongTuong(tuong($al[1])),
            2 => nguHanh(huu($al[1])) ." - ". vuongTuong(huu($al[1])),
            3 => nguHanh(tu($al[1])) ." - ". vuongTuong(tu($al[1])),
            4 => nguHanh(tuw($al[1])) ." - ". vuongTuong(tuw($al[1])),
        ],
        'khong_vong' => [
            'chi' => chi($khongvong[0]).' - '.chi($khongvong[1]),
            'cung' => $khongvongmon,
            'Que' => $khongvongque
        ],
        'don_que' => batmon(donque($al[0], $al[1], $gio))
    ];

    return $arr;
}

?>


<?php
$dd = $_REQUEST['dd'];
$mm = $_REQUEST['mm'];
$yy = $_REQUEST['yy'];
$hh = $_REQUEST['hh'];
$ii = $_REQUEST['ii'];
$date_array = getdate();
if ($dd == 0) $dd = $date_array['mday'];
if ($mm == 0) $mm = $date_array['mon'];
if ($yy == 0) $yy = $date_array['year'];
if ($hh == 0) $hh = $date_array['hours'];
if ($ii == 0) $ii = $date_array['minutes'];

echo "<p><form action=\"\" method=\"POST\">\n";
echo "Ng&#224;y: <input name=\"dd\" size=2 value=\"$dd\">\n";
echo "Th&#225;ng: <input name=\"mm\" size=2 value=\"$mm\">\n";
echo "N&#259;m: <input name=\"yy\" size=4 value=\"$yy\">\n<br>";
echo "Giờ: <input name=\"hh\" size=4 value=\"$hh\">\n";
echo "Phút: <input name=\"ii\" size=4 value=\"$ii\">\n<br>";
echo "<input type=\"submit\">\n";
echo "</form>\n";

echo "<pre>";
print_r(GetInfoDate($dd, $mm, $yy, $hh, $ii, 7.0));
echo "</pre>";

?>
<!-- <form>
  <div class="form-row">
    <div class="col-1">
      <input type="text" value="<?php echo $dd; ?>" class=" form-control form-control-sm" placeholder="Ngày">
    </div>
    <div class="col-1">
      <input type="text" value="<?php echo $mm; ?>" class=" form-control form-control-sm" placeholder="Tháng">
    </div>
    <div class="col-1">
      <input type="text" value="<?php echo $yy; ?>" class=" form-control form-control-sm" placeholder="Năm">
    </div>
    <div class="col-1">
        <button type="submit">Submit</button>
    </div>
  </div>
</form> -->
</body>
</html>