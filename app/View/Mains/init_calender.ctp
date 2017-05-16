  <?php
      // カレンダーの年月をタイムスタンプを使って指定
      if (isset($_GET["date"]) && $_GET["date"] != "") {
       $date_timestamp = $_GET["date"]; //ゲットできたらゲットしたdate使用
      } else {
       $date_timestamp = time();//なければ今の時間
      }
      $month = date("m", $date_timestamp);
      $year = date("Y", $date_timestamp);
      $first_date = mktime(0, 0, 0, $month, 1, $year);
      $last_date = mktime(0, 0, 0, $month + 1, 0, $year);
      // 最初の日と最後の日の｢日にち」の部分だけ数字で取り出す。
      $first_day = date("j", $first_date);
      $last_day = date("j", $last_date);
      // 全ての日の曜日を得る。
      for($day = $first_day; $day <= $last_day; $day++) {
       $day_timestamp = mktime(0, 0, 0, $month, $day, $year);
       $week[$day] = date("w", $day_timestamp);
      }
  ?>
