
 <style type="text/css"> 
            table {
                border:0; 
                text-align: center;
                font-size:9pt; 
                font-family:Verdana;
            }
            th {align: center; 
                font-size:12pt;
                font-family:Arial;
                color:#666699;
            }
            .free {
                font-family:Verdana;
                text-align: center;
                background:#00ff00;
            }
            .day { 
                font-family:Verdana;
                text-align: center;
                font-weight: bold;
                color:#666666;
            }
            .dayW {
                font-family:Verdana;
                text-align: center;
                font-weight: bold;
                color:#0000cc;
            }
            .dayd { 
                font-family:Verdana;
                text-align: center;
                color:#666666;
            }
            .dayWd { 
                font-family:Verdana;
                text-align: center;
                color:#0000cc;
            }
            @media only screen and (max-device-width: 480px) {
                table {
                    border:0; 
                    text-align: center;
                    font-size:32px; 
                    font-family:Verdana;
                }
                th {align: center; 
                    font-size:32px;
                    font-family:Arial;
                    color:#666699;
                }
                p {
                    font-size: 40px;
                }
                h1 {
                    font-size: 56px;  
                }

                .free {
                    font-size:32px; 
                    font-family:Verdana;
                    background:#00ff00;
                }
                .day {
                    font-size:32px; 
                    text-align: center;
                    font-weight: bold;
                    color:#666666;
                }
                .dayW {   
                    font-size:32px; 
                    text-align: center;
                    font-weight: bold;
                    color:#0000cc;
                }
                .dayd { 
                    font-size:32px; 
                    text-align: center;
                    color:#666666;
                }
                .dayWd {  
                    font-size:32px; 
                    text-align: center;
                    color:#0000cc;
                }
                .btn-md {
                    padding:4px 9px;
                    font-size:56px;
                    line-height: 1.2;
                }
                a {
                    font-size: 40px;
                }
            }
        </style>
    </head>
    <body>
        <div align="center">
            <h1>Free days from <?php echo  date('M') ." ". date('Y') ?> </h1>

            <?php
            $mm = date('n');
            $yy = date('Y');
            $dd = date('d');
            $monate = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

            echo '<table>';

            for ($reihe = 0; $reihe < 4; $reihe++) {
                echo '<tr>';
                for ($ii = 0; $ii < 2; $ii++) {
                    $this_month = $mm;
                    $day_of_week = date('w', mktime(0, 0, 0, $this_month, 1, $yy));
                    $days_in_month = date('t', mktime(0, 0, 0, $this_month, 1, $yy));
                    echo '<td width="40%" valign=top>';
                    echo '<table align="center">';
                    echo '<th colspan="7" >' . $monate[$this_month - 1] . '</th>';
                    echo '<tr>';
                    echo '<td class="dayW">Su</td>';
                    echo '<td class="day">Mo</td>';
                    echo '<td class="day">Tu</td>';
                    echo '<td class="day">We</td>';
                    echo '<td class="day">Th</td>';
                    echo '<td class="day">Fr</td>';
                    echo '<td class="dayW">Sa</td>';
                    echo '<tr><br>';
                    $i = 0;
                    while ($i < $day_of_week) {
                        echo '<td> </td>';
                        $i++;
                    }
                    $i = 1;
                    while ($i <= $days_in_month) {
                        $ddmmyy = date('Y-m-d', mktime(0, 0, 0, $this_month, $i, $yy));
                        $rest = ($i + $day_of_week) % 7;
                        if (in_array($ddmmyy, $data)) {
                            echo '<td class="free" >';
                        } else {
                            echo '<td  class="dayd" >';
                        }
                        if ($rest == 0 || $rest == 1) {
                            echo '<span class="dayWd">' . $i . '</span>';
                        } else {
                            echo '<span class="dayd">' . $i . '</span>';
                        }
                        echo "</td>\n";
                        if ($rest == 0)
                            echo "</tr>\n<tr>\n";
                        $i++;
                    }
                    echo '</tr>';
                    echo '</table>';
                    echo '</td>';
                    $mm++;
                    if ($mm > 12) {
                        $yy++;
                        $mm = 1;
                    }
                }
                echo '</tr>';
            }

            echo '</table>';
            ?> 
