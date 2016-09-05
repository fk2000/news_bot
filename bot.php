<?php

require_once 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

require_once('apiconfig.php');

// Consumer keyの値
$consumer_key = API_KEY;
// Consumer secretの値
$consumer_secret = API_SECRET;
// Access Tokenの値
$access_token = ACCESS_TOKEN;
// Access Token Secretの値
$access_token_secret = ACCESS_SECRET;


//内容を取得
//改行区切りのテキストファイルから空行無視で取得・改行文字を削除して一行ずつ配列に格納
$url_arr = file("url.txt",FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$tweet_arr = array();
foreach($url_arr AS $url){
    $RSS = simplexml_load_file($url);

    if( $RSS->channel->item[0] ){//RSS2.0

        $site_name = (string)$RSS->channel->title;
        $item  = $RSS->channel->item[0];
        $title = (string)$item->title;
        $link  = (string)$item->link;
        $date  = (string)$item->pubDate;

    }elseif( $RSS->item[0] ){//RSS1.0

        $site_name = (string)$RSS->channel->title;
        $item  = $RSS->item[0];
        $title = (string)$item->title;
        $link  = (string)$item->link;
        //dc:dateのデータはそのままでは取得できないのでchildrenメソッドを呼ぶ
        $date  = (string)$item->children('http://purl.org/dc/elements/1.1/')->date;

    }elseif( $RSS->entry[0] ){//Atom

        $site_name = (string)$RSS->title;
        $item  = $RSS->entry[0];
        $title = (string)$item->title;
        $link  = (string)$item->link->attributes()->href;
        $date  = (string)$item->published;

    }


    //タイトルが長すぎる場合は丸める
    $title = mb_strimwidth($title,0,50,"…",'UTF-8');

    //URLを短縮
    $link  = url_shorten_isgd($link);

    //日付を見やすいように整形
    $date  = date("Y.m.d",strtotime($date));

    //表示させたい形に加工
    $tweet = $title."(".$date."更新)".$link."【".$site_name."】";

    //140文字に丸めて配列に格納
    $tweet_arr[] = mb_substr($tweet,0,140,'UTF-8');


}

// つぶやく
foreach($tweet_arr AS $val){
    $connection = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret); 
    $statues = $connection->post("statuses/update", array("status" => $val));
}



//URL短縮関数
function url_shorten_isgd($before_url=''){

    if(!$before_url){
        return false;
    }

    //リクエスト
    $json = @file_get_contents("http://is.gd/create.php?format=simple&format=json&url=".rawurlencode($before_url));

    //取得したJSONをオブジェクトに変換
    $obj = json_decode($json);

    //返却
    return (isset($obj->shorturl) && !empty($obj->shorturl)) ? $obj->shorturl : false;

}
