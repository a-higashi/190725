<?php
// 祝日自動取得用にGoogleで取得したAPIキーを記述して下さい。
// 例「AIzayaguWCFVDSpEGXQxAvI-oXBIcT6XJ1ck」 （あくまで例です。こんな形式という意味です。これをそのまま使用できません）
// https://code.google.com/apis/console/ にて「Calendar API」を有効にし、
// 左メニュー「認証情報」の「公開 API へのアクセス」→「キーを作成」→「サーバーキー」で取得できます。
// GoogleカレンダーAPIから祝日を取得
function getHolidays($year)
{
  // json_encode()関数が存在しないなら
  if (!function_exists('json_encode'))
  {
    // JSON.phpを読み込んで
    require_once 'JSON.php';

    // json_encode()関数を定義する
    function json_encode($value)
    {
      $s = new Services_JSON();
      return $s->encodeUnsafe($value);
    }
    // json_decode()関数を定義する
    function json_decode($json, $assoc = false)
    {
      $s = new Services_JSON($assoc ? SERVICES_JSON_LOOSE_TYPE : 0);
      return $s->decode($json);
    }
  }
  // 以降、json_encode(), json_decode() が使用可能
  // global $apiKey;
  $apiKey = 'AIzaSyA1XdLQkGZysSX79Gfq_fY2B53QSyOLBoQ';
  $prev = $year - 1;
  $next = $year + 10;
  $holidays = array();
  $holidays_id = 'outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com'; // mozilla.org版
  // $holidays_id = 'japanese__ja@holiday.calendar.google.com'; // Google 公式版日本語
  // $holidays_id = 'japanese@holiday.calendar.google.com'; // Google 公式版英語
  $url = sprintf('https://www.googleapis.com/calendar/v3/calendars/%s/events?' . 'key=%s&timeMin=%s&timeMax=%s&maxResults=%d&orderBy=startTime&singleEvents=true', $holidays_id, $apiKey, $prev . '-01-01T00:00:00Z', // 取得開始日
  $next . '-12-31T00:00:00Z', // 取得終了日
  150
  // 最大取得数
  );
  if ($results = file_get_contents($url, true))
  {
    // JSON形式で取得した情報を配列に格納
    $results = json_decode($results);
    // 年月日をキー、祝日名を配列に格納
    if ($results->items)
    {
      foreach($results->items as $item)
      {
        $date = strtotime((string)$item->start->date);
        $title = (string)$item->summary;
        // $holidays[date('Y-m-d', $date)] = $title;
        $holidays[date('Y-m-d', $date) ] = date('Y-m-d', $date);
      }
    }
    else
    {
      $holidays = g_holiday();
    }
    // 祝日の配列を並び替え
    ksort($holidays);
  }
  return $holidays;
}
function g_holiday()
{
  $array = array(
    '2013-01-01' => '2013-01-01',
    '2013-01-14' => '2013-01-14',
    '2013-02-11' => '2013-02-11',
    '2013-03-20' => '2013-03-20',
    '2013-04-29' => '2013-04-29',
    '2013-05-03' => '2013-05-03',
    '2013-05-04' => '2013-05-04',
    '2013-05-05' => '2013-05-05',
    '2013-05-06' => '2013-05-06',
    '2013-07-15' => '2013-07-15',
    '2013-09-16' => '2013-09-16',
    '2013-09-23' => '2013-09-23',
    '2013-10-14' => '2013-10-14',
    '2013-11-03' => '2013-11-03',
    '2013-11-04' => '2013-11-04',
    '2013-11-23' => '2013-11-23',
    '2013-12-23' => '2013-12-23',
    '2014-01-01' => '2014-01-01',
    '2014-01-13' => '2014-01-13',
    '2014-02-11' => '2014-02-11',
    '2014-03-21' => '2014-03-21',
    '2014-04-29' => '2014-04-29',
    '2014-05-03' => '2014-05-03',
    '2014-05-04' => '2014-05-04',
    '2014-05-05' => '2014-05-05',
    '2014-05-06' => '2014-05-06',
    '2014-07-21' => '2014-07-21',
    '2014-09-15' => '2014-09-15',
    '2014-09-23' => '2014-09-23',
    '2014-10-13' => '2014-10-13',
    '2014-11-03' => '2014-11-03',
    '2014-11-23' => '2014-11-23',
    '2014-11-24' => '2014-11-24',
    '2014-12-23' => '2014-12-23',
    '2015-01-01' => '2015-01-01',
    '2015-01-12' => '2015-01-12',
    '2015-02-11' => '2015-02-11',
    '2015-03-21' => '2015-03-21',
    '2015-04-29' => '2015-04-29',
    '2015-05-03' => '2015-05-03',
    '2015-05-04' => '2015-05-04',
    '2015-05-05' => '2015-05-05',
    '2015-05-06' => '2015-05-06',
    '2015-07-20' => '2015-07-20',
    '2015-09-21' => '2015-09-21',
    '2015-09-22' => '2015-09-22',
    '2015-09-23' => '2015-09-23',
    '2015-10-12' => '2015-10-12',
    '2015-11-03' => '2015-11-03',
    '2015-11-23' => '2015-11-23',
    '2015-12-23' => '2015-12-23',
    '2016-01-01' => '2016-01-01',
    '2016-01-11' => '2016-01-11',
    '2016-02-11' => '2016-02-11',
    '2016-03-20' => '2016-03-20',
    '2016-03-21' => '2016-03-21',
    '2016-04-29' => '2016-04-29',
    '2016-05-03' => '2016-05-03',
    '2016-05-04' => '2016-05-04',
    '2016-05-05' => '2016-05-05',
    '2016-07-18' => '2016-07-18',
    '2016-08-11' => '2016-08-11',
    '2016-09-19' => '2016-09-19',
    '2016-09-22' => '2016-09-22',
    '2016-10-10' => '2016-10-10',
    '2016-11-03' => '2016-11-03',
    '2016-11-23' => '2016-11-23',
    '2016-12-23' => '2016-12-23',
    '2017-01-01' => '2017-01-01',
    '2017-01-02' => '2017-01-02',
    '2017-01-09' => '2017-01-09',
    '2017-02-11' => '2017-02-11',
    '2017-03-20' => '2017-03-20',
    '2017-04-29' => '2017-04-29',
    '2017-05-03' => '2017-05-03',
    '2017-05-04' => '2017-05-04',
    '2017-05-05' => '2017-05-05',
    '2017-07-17' => '2017-07-17',
    '2017-08-11' => '2017-08-11',
    '2017-09-18' => '2017-09-18',
    '2017-09-23' => '2017-09-23',
    '2017-10-09' => '2017-10-09',
    '2017-11-03' => '2017-11-03',
    '2017-11-23' => '2017-11-23',
    '2017-12-23' => '2017-12-23',
    '2018-01-01' => '2018-01-01',
    '2018-01-08' => '2018-01-08',
    '2018-02-11' => '2018-02-11',
    '2018-02-12' => '2018-02-12',
    '2018-03-21' => '2018-03-21',
    '2018-04-29' => '2018-04-29',
    '2018-04-30' => '2018-04-30',
    '2018-05-03' => '2018-05-03',
    '2018-05-04' => '2018-05-04',
    '2018-05-05' => '2018-05-05',
    '2018-07-16' => '2018-07-16',
    '2018-08-11' => '2018-08-11',
    '2018-09-17' => '2018-09-17',
    '2018-09-23' => '2018-09-23',
    '2018-09-24' => '2018-09-24',
    '2018-10-08' => '2018-10-08',
    '2018-11-03' => '2018-11-03',
    '2018-11-23' => '2018-11-23',
    '2018-12-23' => '2018-12-23',
    '2018-12-24' => '2018-12-24',
    '2019-01-01' => '2019-01-01',
    '2019-01-14' => '2019-01-14',
    '2019-02-11' => '2019-02-11',
    '2019-03-21' => '2019-03-21',
    '2019-04-29' => '2019-04-29',
    '2019-05-03' => '2019-05-03',
    '2019-05-04' => '2019-05-04',
    '2019-05-05' => '2019-05-05',
    '2019-05-06' => '2019-05-06',
    '2019-07-15' => '2019-07-15',
    '2019-08-11' => '2019-08-11',
    '2019-08-12' => '2019-08-12',
    '2019-09-16' => '2019-09-16',
    '2019-09-23' => '2019-09-23',
    '2019-10-14' => '2019-10-14',
    '2019-11-03' => '2019-11-03',
    '2019-11-04' => '2019-11-04',
    '2019-11-23' => '2019-11-23',
    '2019-12-23' => '2019-12-23',
    '2020-01-01' => '2020-01-01',
    '2020-01-13' => '2020-01-13',
    '2020-02-11' => '2020-02-11',
    '2020-03-20' => '2020-03-20',
    '2020-04-29' => '2020-04-29',
    '2020-05-03' => '2020-05-03',
    '2020-05-04' => '2020-05-04',
    '2020-05-05' => '2020-05-05',
    '2020-05-06' => '2020-05-06',
    '2020-07-20' => '2020-07-20',
    '2020-08-11' => '2020-08-11',
    '2020-09-21' => '2020-09-21',
    '2020-09-22' => '2020-09-22',
    '2020-10-12' => '2020-10-12',
    '2020-11-03' => '2020-11-03',
    '2020-11-23' => '2020-11-23',
    '2020-12-23' => '2020-12-23',
    '2021-01-01' => '2021-01-01',
    '2021-01-11' => '2021-01-11',
    '2021-02-11' => '2021-02-11',
    '2021-03-20' => '2021-03-20',
    '2021-04-29' => '2021-04-29',
    '2021-05-03' => '2021-05-03',
    '2021-05-04' => '2021-05-04',
    '2021-05-05' => '2021-05-05',
    '2021-07-19' => '2021-07-19',
    '2021-08-11' => '2021-08-11',
    '2021-09-20' => '2021-09-20',
    '2021-09-23' => '2021-09-23',
    '2021-10-11' => '2021-10-11',
    '2021-11-03' => '2021-11-03',
    '2021-11-23' => '2021-11-23',
    '2021-12-23' => '2021-12-23',
    '2022-01-01' => '2022-01-01',
    '2022-01-10' => '2022-01-10',
    '2022-02-11' => '2022-02-11',
    '2022-03-21' => '2022-03-21',
    '2022-04-29' => '2022-04-29',
    '2022-05-03' => '2022-05-03',
    '2022-05-04' => '2022-05-04',
    '2022-05-05' => '2022-05-05',
    '2022-07-18' => '2022-07-18',
    '2022-08-11' => '2022-08-11',
    '2022-09-19' => '2022-09-19',
    '2022-09-23' => '2022-09-23',
    '2022-10-10' => '2022-10-10',
    '2022-11-03' => '2022-11-03',
    '2022-11-23' => '2022-11-23',
    '2022-12-23' => '2022-12-23',
    '2023-01-01' => '2023-01-01',
    '2023-01-02' => '2023-01-02',
    '2023-01-09' => '2023-01-09',
    '2023-02-11' => '2023-02-11',
    '2023-03-21' => '2023-03-21',
    '2023-04-29' => '2023-04-29',
    '2023-05-03' => '2023-05-03',
    '2023-05-04' => '2023-05-04',
    '2023-05-05' => '2023-05-05',
    '2023-07-17' => '2023-07-17',
    '2023-08-11' => '2023-08-11',
    '2023-09-18' => '2023-09-18',
    '2023-09-23' => '2023-09-23',
    '2023-10-09' => '2023-10-09',
    '2023-11-03' => '2023-11-03',
    '2023-11-23' => '2023-11-23',
    '2023-12-23' => '2023-12-23',
    '2024-01-01' => '2024-01-01',
    '2024-01-08' => '2024-01-08',
    '2024-02-11' => '2024-02-11',
    '2024-02-12' => '2024-02-12',
    '2024-03-20' => '2024-03-20',
    '2024-04-29' => '2024-04-29',
    '2024-05-03' => '2024-05-03',
    '2024-05-04' => '2024-05-04',
    '2024-05-05' => '2024-05-05',
    '2024-05-06' => '2024-05-06',
    '2024-07-15' => '2024-07-15',
    '2024-08-11' => '2024-08-11',
    '2024-08-12' => '2024-08-12',
    '2024-09-16' => '2024-09-16',
    '2024-09-22' => '2024-09-22',
    '2024-09-23' => '2024-09-23',
    '2024-10-14' => '2024-10-14',
    '2024-11-03' => '2024-11-03',
    '2024-11-04' => '2024-11-04',
    '2024-11-23' => '2024-11-23',
    '2024-12-23' => '2024-12-23',
    '2025-01-01' => '2025-01-01',
    '2025-01-13' => '2025-01-13',
    '2025-02-11' => '2025-02-11',
    '2025-03-20' => '2025-03-20',
    '2025-04-29' => '2025-04-29',
    '2025-05-03' => '2025-05-03',
    '2025-05-04' => '2025-05-04',
    '2025-05-05' => '2025-05-05',
    '2025-05-06' => '2025-05-06',
    '2025-07-21' => '2025-07-21',
    '2025-08-11' => '2025-08-11',
    '2025-09-15' => '2025-09-15',
    '2025-09-23' => '2025-09-23',
    '2025-10-13' => '2025-10-13',
    '2025-11-03' => '2025-11-03',
    '2025-11-23' => '2025-11-23',
    '2025-11-24' => '2025-11-24',
    '2025-12-23' => '2025-12-23',
    '2026-01-01' => '2026-01-01',
    '2026-01-12' => '2026-01-12',
    '2026-02-11' => '2026-02-11',
    '2026-03-20' => '2026-03-20',
    '2026-04-29' => '2026-04-29',
    '2026-05-03' => '2026-05-03',
    '2026-05-04' => '2026-05-04',
    '2026-05-05' => '2026-05-05',
    '2026-05-06' => '2026-05-06',
    '2026-07-20' => '2026-07-20',
    '2026-08-11' => '2026-08-11',
    '2026-09-21' => '2026-09-21',
    '2026-09-22' => '2026-09-22',
    '2026-09-23' => '2026-09-23',
    '2026-10-12' => '2026-10-12',
    '2026-11-03' => '2026-11-03',
    '2026-11-23' => '2026-11-23',
    '2026-12-23' => '2026-12-23',
    '2027-01-01' => '2027-01-01',
    '2027-01-11' => '2027-01-11',
    '2027-02-11' => '2027-02-11',
    '2027-03-21' => '2027-03-21',
    '2027-03-22' => '2027-03-22',
    '2027-04-29' => '2027-04-29',
    '2027-05-03' => '2027-05-03',
    '2027-05-04' => '2027-05-04',
    '2027-05-05' => '2027-05-05',
    '2027-07-19' => '2027-07-19',
    '2027-08-11' => '2027-08-11',
    '2027-09-20' => '2027-09-20',
    '2027-09-23' => '2027-09-23',
    '2027-10-11' => '2027-10-11',
    '2027-11-03' => '2027-11-03',
    '2027-11-23' => '2027-11-23',
    '2027-12-23' => '2027-12-23',
    '2028-01-01' => '2028-01-01',
    '2028-01-10' => '2028-01-10',
    '2028-02-11' => '2028-02-11',
    '2028-03-20' => '2028-03-20',
    '2028-04-29' => '2028-04-29',
    '2028-05-03' => '2028-05-03',
    '2028-05-04' => '2028-05-04',
    '2028-05-05' => '2028-05-05',
    '2028-07-17' => '2028-07-17',
    '2028-08-11' => '2028-08-11',
    '2028-09-18' => '2028-09-18',
    '2028-09-22' => '2028-09-22',
    '2028-10-09' => '2028-10-09',
    '2028-11-03' => '2028-11-03',
    '2028-11-23' => '2028-11-23',
    '2028-12-23' => '2028-12-23',
    '2029-01-01' => '2029-01-01',
    '2029-01-08' => '2029-01-08',
    '2029-02-11' => '2029-02-11',
    '2029-02-12' => '2029-02-12',
    '2029-03-20' => '2029-03-20',
    '2029-04-29' => '2029-04-29',
    '2029-04-30' => '2029-04-30',
    '2029-05-03' => '2029-05-03',
    '2029-05-04' => '2029-05-04',
    '2029-05-05' => '2029-05-05',
    '2029-07-16' => '2029-07-16',
    '2029-08-11' => '2029-08-11',
    '2029-09-17' => '2029-09-17',
    '2029-09-23' => '2029-09-23',
    '2029-09-24' => '2029-09-24',
    '2029-10-08' => '2029-10-08',
    '2029-11-03' => '2029-11-03',
    '2029-11-23' => '2029-11-23',
    '2029-12-23' => '2029-12-23',
    '2029-12-24' => '2029-12-24',
    '2030-01-01' => '2030-01-01',
    '2030-01-14' => '2030-01-14',
    '2030-02-11' => '2030-02-11',
    '2030-03-20' => '2030-03-20',
    '2030-04-29' => '2030-04-29',
    '2030-05-03' => '2030-05-03',
    '2030-05-04' => '2030-05-04',
    '2030-05-05' => '2030-05-05',
    '2030-05-06' => '2030-05-06',
    '2030-07-15' => '2030-07-15',
    '2030-08-11' => '2030-08-11',
    '2030-08-12' => '2030-08-12',
    '2030-09-16' => '2030-09-16',
    '2030-09-23' => '2030-09-23',
    '2030-10-14' => '2030-10-14',
    '2030-11-03' => '2030-11-03',
    '2030-11-04' => '2030-11-04',
    '2030-11-23' => '2030-11-23',
    '2030-12-23' => '2030-12-23',
    '2031-01-01' => '2031-01-01',
    '2031-01-13' => '2031-01-13',
    '2031-02-11' => '2031-02-11',
    '2031-03-21' => '2031-03-21',
    '2031-04-29' => '2031-04-29',
    '2031-05-03' => '2031-05-03',
    '2031-05-04' => '2031-05-04',
    '2031-05-05' => '2031-05-05',
    '2031-05-06' => '2031-05-06',
    '2031-07-21' => '2031-07-21',
    '2031-08-11' => '2031-08-11',
    '2031-09-15' => '2031-09-15',
    '2031-09-23' => '2031-09-23',
    '2031-10-13' => '2031-10-13',
    '2031-11-03' => '2031-11-03',
    '2031-11-23' => '2031-11-23',
    '2031-11-24' => '2031-11-24',
    '2031-12-23' => '2031-12-23',
    '2032-01-01' => '2032-01-01',
    '2032-01-12' => '2032-01-12',
    '2032-02-11' => '2032-02-11',
    '2032-03-20' => '2032-03-20',
    '2032-04-29' => '2032-04-29',
    '2032-05-03' => '2032-05-03',
    '2032-05-04' => '2032-05-04',
    '2032-05-05' => '2032-05-05',
    '2032-07-19' => '2032-07-19',
    '2032-08-11' => '2032-08-11',
    '2032-09-20' => '2032-09-20',
    '2032-09-21' => '2032-09-21',
    '2032-09-22' => '2032-09-22',
    '2032-10-11' => '2032-10-11',
    '2032-11-03' => '2032-11-03',
    '2032-11-23' => '2032-11-23',
    '2032-12-23' => '2032-12-23'
  );
  return $array;
}