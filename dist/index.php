<?php
/* ********************************************
KATSURAGI

Copyright 2018 Arm=Band

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
********************************************* */
/* ********************************************
 *                                            *
 * アプリ設定                                   *
 *                                            *
 ******************************************** */
/* タイムゾーンなど
********************************************* */
date_default_timezone_set('Asia/Tokyo');
const GLOBAL_APP_LANG = 'ja';
const GLOBAL_APP_ENCODE = 'UTF-8';
mb_language(GLOBAL_APP_LANG);
mb_internal_encoding(GLOBAL_APP_ENCODE);

/* ********************************************
 *                                            *
 * エラーメッセージ                              *
 *                                            *
 ******************************************** */
//00 ファイル操作
const ERR_MSG02 = 'err02: ファイル書き込みエラー';
//50 コンテンツ処理
const ERR_MSG50 = 'err50: 編集したい記事が指定されていません。';
const ERR_MSG51 = 'err51: 指定した記事が見付かりませんでした。';
const ERR_MSG52 = 'err52: 削除したい記事が指定されていません。';
//60 ログイン処理
const ERR_MSG60 = 'err60: ログインに失敗しました。';
const ERR_MSG61 = 'err61: ユーザIDまたはパスワードが間違っています。';
//70 インストール
const ERR_MSG70 = 'err70: インストールに失敗しました。ファイルをアップロードし直し、最初からやり直してください。';
//80 入力項目チェック
const ERR_MSG80 = 'err80: 必須項目が入力されていません。';
const ERR_MSG81 = 'err81: 半角英数字以外の文字が入力されているか、文字数が3文字未満か、101文字以上になっています。';
const ERR_MSG82 = 'err82: 半角英数字と記号の中から組み合わせてください。また、文字数は8文字以上100文字以下にしてください。';
const ERR_MSG83 = 'err83: 入力したパスワードが現在のパスワードと一致しません。';
//90 汎用
const ERR_MSG99 = 'err99: 予期しないページからのリクエストです。異なるドメインからの入力の可能性があります。';

/* ********************************************
 *                                            *
 * 定数                                        *
 *                                            *
 ******************************************** */
/* アプリ設定
********************************************* */
//コピーライト
const GLOBAL_APP_AUHTOR = 'アルム＝バンド'; //著者
const GLOBAL_APP_AUHTOR_URL = 'https://lab.ewigleere.net'; //著者サイトのURL
const GLOBAL_APP_COPYRIGHT_YEAR = '2018'; //コピーライト表示の年
const GLOBAL_APP_NAME = 'KATSURAGI'; //アプリ名
const GLOBAL_APP_URL = 'https://lab.ewigleere.net'; //KATSURAGIのURL
const GLOBAL_APP_VERSION = '0.1.2'; //KATSURAGIのバージョン
/* モード設定
********************************************* */
//ログイン
const MODE_LOGIN = 1;
const MODE_UNLOGIN = 0;
//インストール(未インストール: 0, インストール済: 1)
const MODE_INSTALLED = 0;
//操作完了画面のモード
const SHOW_MODE = array(
    "co" => "contents",
    "st" => "settings",
    "lo" => "logout",
    "is" => "installed"
);
/* ********************************************
 *                                            *
 * 変数                                        *
 *                                            *
 ******************************************** */
//エラーフラグ
$errFlg = 0;
$errMsg = [];

/* ********************************************
 *                                            *
 * データフィールド                              *
 *                                            *
 ******************************************** */
/* グローバル設定
********************************************* */
const GLOBAL_SITENAME = 'KATSURAGI'; //サイト名
const GLOBAL_DESCRIPTION = 'It\'s very the simple cms that\'s consist of one file and powered by PHP.'; //説明
const GLOBAL_THEME_COLOR = '#98B948'; //メインカラー
const GLOBAL_AUHTOR = 'admin'; //著者
const GLOBAL_AUHTOR_URL = './'; //著者サイトのURL
const GLOBAL_COPYRIGHT_YEAR = '2018'; //コピーライト表示の年
/* アカウント設定
********************************************* */
const GLOBAL_USER_ID = 'admin';
const GLOBAL_USER_PS = '$2y$10$gUvQWPnunTsGy8R/YApvdeAVv9L1RdIzqLh3VsB.UWXviWk/0GuYm';
/* OGP
********************************************* */
const GLOBAL_OGP_TWITTER_ACCOUNT = ''; //OGP用Twitterアカウント
const GLOBAL_OGP_IMAGE = ''; //OGP画像
define('GLOBAL_OGP_URL', (empty($_SERVER['HTTPS']) ? "http://" : "https://") . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']); //URL //式はconstでは書けないのでdefineで定義
/* コンテンツデータ
********************************************* */
const CONTENTS_DATA = '[]';
//const CONTENTS_DATA = '[{"ti":"test","co":"\u3066\uff53\uff54\u3067\u3059","kw":"test,test2","la":"2018-09-24T14:00:06+09:00"},{"ti":"test2","co":"\u3066\uff53\uff54\uff12","kw":"test2","la":"2018-09-24T14:00:33+09:00"}]';

/* ********************************************
 *                                            *
 * ログイン                                    *
 *                                            *
 ******************************************** */
/* セッション
********************************************* */
function requireUnloginedSession() {
    // セッション開始
    @session_start();
    // ログインしていればフラグ立てる
    if(isset($_SESSION['userid'])) {
        return MODE_LOGIN;
    }
    else {
        return MODE_UNLOGIN;
    }
}
function requireLoginedSession() {
    // セッション開始
    @session_start();
    // ログインしていなければフラグ降ろす
    if(!isset($_SESSION['userid'])) {
        return MODE_UNLOGIN;
    }
    else {
        return MODE_LOGIN;
    }
}
/* トークン
********************************************* */
function generateToken() {
    // セッションIDからハッシュを生成
    return hash('sha256', session_id());
}
function validateToken($token) {
    // 送信されてきた$tokenがこちらで生成したハッシュと一致するか検証
    return $token === generateToken();
}

/* セッション
********************************************* */
$modeLogin = 0;
$modeLogin = requireUnloginedSession();

/* デバッグ
********************************************* */
//デバッグモード
const DEBUG_MODE = false;
//デバッグモードオンならばエラーメッセージ表示
function showErrMsg($e) {
    if(DEBUG_MODE) {
        echo $e->getMessage();
    }
}

/* ********************************************
 *                                            *
 * 汎用関数                                    *
 *                                            *
 ******************************************** */
/* エスケープ
********************************************* */
//HTML
function _h($str) {
    return htmlspecialchars($str, ENT_QUOTES, GLOBAL_APP_ENCODE);
}
//dollar
function _dl($str) {
    return str_replace('$', '\$', $str);
}
//quote
function _q($str) {
    return str_replace('\'', '\\\'', $str);
}
/* 変換
********************************************* */
//JSON
function _je($arr) {
    return json_encode($arr, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}
function _jd($str) {
    return json_decode($str);
}
/* 年表示に関する処理を行う
********************************************* */
//現在の年を出力（コピーライト用）
function copyrightYears($variables) {
    $year = (string)date('Y');
    if($year === $variables) {
        return $year;
    }
    else {
        return $variables . '-' . $year;
    }
}
//日付をUTCから指定方式の文字列に変換する
function dateConvert($dateStr) {
    return date("Y/m/d H:i:s", strtotime($dateStr));
}
/* コンテンツ内容を連想配列にする
********************************************* */
function contentsCreate($title, $contents, $keywords, $lastDate) {
    $content = array(
        'ti' => _h(_q($title)),
        'co' => _h(_q($contents)),
        'kw' => _h(_q($keywords)),
        'la' => _h(_q($lastDate))
    );
    return $content;
}

/* ********************************************
 *                                            *
 * チェック                                    *
 *                                            *
 ******************************************** */
function emptyCheck($str) {
    return empty($str);
}
function lentgthCheck($str, $len) {
    if(mb_strlen($str, GLOBAL_APP_ENCODE) >= $len) {
        return true;
    }
    return false;
}

/* ********************************************
 *                                            *
 * 表示                                        *
 *                                            *
 ******************************************** */
/* コンテンツ更新時・設定完了時の表示
********************************************* */
function dashboardFinished($mode) {
    //パラメータ
    $showMode = _h($mode);
    $finishedLang = _h(GLOBAL_APP_LANG);
    $finishedEncode = _h(GLOBAL_APP_ENCODE);
    $finishedSiteName = _h(GLOBAL_SITENAME);
    $finishedThemeColor = _h(GLOBAL_THEME_COLOR);
    //トークン
    $token = _h(generateToken());
    //表示文字
    $title = '';
    $heading = '';
    $headingIcon = '';
    $contents = '';
    $buttonIcon = '';
    if($mode === SHOW_MODE['co']) {
        $title = 'コンテンツ更新完了';
        $heading = 'Updated!';
        $headingIcon = 'file-signature';
        $contents = 'コンテンツの更新が完了しました。引き続き作業するには「ダッシュボードへ戻る」ボタンをクリックしてください。';
        $button = 'ダッシュボードへ戻る';
        $buttonIcon = 'undo';
    }
    else if($mode === SHOW_MODE['st']) {
        $title = '設定変更完了';
        $heading = 'Settings Finished!';
        $headingIcon = 'cogs';
        $contents = '設定変更が完了しました。引き続き作業するには「ダッシュボードへ戻る」ボタンをクリックしてください。';
        $button = 'ダッシュボードへ戻る';
        $buttonIcon = 'undo';
    }
    header('Content-Type: text/html; charset=UTF-8');
    echo <<< EOF
<!DOCTYPE html>
<html lang="{$finishedLang}">
<head>
    <meta charset="{$finishedEncode}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no,address=no,email=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} | {$finishedSiteName}</title>
    <!-- theme color -->
    <meta name="theme-color" content="{$finishedThemeColor}">
    <!-- css -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body class="{$showMode}" id="{$showMode}">
    <div id="wrapper">
        <!-- main -->
        <main class="main">
            <div class="container-fluid mb-4">
                <div class="pb-2 mt-4 mb-2 border-bottom">
                    <h2><i class="fas fa-fw fa-{$headingIcon}" aria-hidden="true"></i>{$heading}</h2>
                </div>
                <p>{$contents}</p>
            </div>
            <div class="container-fluid">
                <form action="./" method="get">
                    <input type="hidden" name="token" value="{$token}">
                    <input type="hidden" name="dashbordReturn" value="dashbordReturn">
                    <button type="submit" class="btn btn-success"><i class="fas fa-fw fa-{$buttonIcon}" aria-hidden="true"></i>{$button}</button>
                </form>
            </div>
        </main>
        <!-- /main -->
    </div>
</body>
</html>
EOF;

    return true;
}
/* ログアウト時・インストール完了時の表示
********************************************* */
function showFinished($mode) {
    //パラメータ
    $showMode = _h($mode);
    $finishedLang = _h(GLOBAL_APP_LANG);
    $finishedEncode = _h(GLOBAL_APP_ENCODE);
    $finishedSiteName = _h(GLOBAL_SITENAME);
    $finishedThemeColor = _h(GLOBAL_THEME_COLOR);
    //トークン
    $token = _h(generateToken());
    //表示文字
    $title = '';
    $heading = '';
    $headingIcon = '';
    $button = 'サイトを表示';
    $buttonIcon = 'eye';
    if($mode === SHOW_MODE['lo']) {
        $title = 'ログアウトしました';
        $heading = 'Logouted';
        $headingIcon = 'torii-gate';
        $contents = 'ログアウトしました。サイトの閲覧に戻るには「サイトを表示」ボタンをクリックしてください。';
    }
    else if($mode === SHOW_MODE['is']) {
        $title = 'インストール完了!';
        $heading = 'Installed!';
        $headingIcon = 'grin-squint';
        $contents = 'インストールが完了しました。サイトを閲覧するには「サイトを表示」ボタンをクリックしてください。';
    }
    header('Content-Type: text/html; charset=UTF-8');
    echo <<< EOF
<!DOCTYPE html>
<html lang="{$finishedLang}">
<head>
    <meta charset="{$finishedEncode}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no,address=no,email=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} | {$finishedSiteName}</title>
    <!-- theme color -->
    <meta name="theme-color" content="{$finishedThemeColor}">
    <!-- css -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body class="{$showMode}" id="{$showMode}">
    <div id="wrapper">
        <!-- main -->
        <main class="main">
            <div class="container-fluid mb-4">
                <div class="pb-2 mt-4 mb-2 border-bottom">
                    <h2><i class="fas fa-fw fa-{$headingIcon}" aria-hidden="true"></i>{$heading}</h2>
                </div>
                <p>{$contents}</p>
            </div>
            <div class="container-fluid">
                <a href="./" class="btn btn-light mt-3"><i class="fas fa-fw fa-{$buttonIcon}" aria-hidden="true"></i>{$button}</a>
            </div>
        </main>
        <!-- /main -->
    </div>
</body>
</html>
EOF;

    return true;
}
/* ********************************************
 *                                            *
 * 初期化                                      *
 *                                            *
 ******************************************** */
$copyRightYear = copyrightYears(_h(GLOBAL_COPYRIGHT_YEAR));
$appCopyRightYear = copyrightYears(_h(GLOBAL_APP_COPYRIGHT_YEAR));
/* ********************************************
 *                                            *
 * メイン処理                                   *
 *                                            *
 ******************************************** */
$contentsData = _jd(CONTENTS_DATA);
/* インストール済
********************************************* */
if(MODE_INSTALLED) {
    /* ログイン・ログアウト・管理画面
    ********************************************* */
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $loginRequest = filter_input(INPUT_POST, 'loginRequest');
        $logoutRequest = filter_input(INPUT_POST, 'logoutRequest');
        $contentsNewRequest = filter_input(INPUT_POST, 'contentsNewRequest');
        $contentsUpdateRequest = filter_input(INPUT_POST, 'contentsUpdateRequest');
        $contentsDeleteRequest = filter_input(INPUT_POST, 'contentsDeleteRequest');
        $siteRequest = filter_input(INPUT_POST, 'siteRequest');
        $accountRequest = filter_input(INPUT_POST, 'accountRequest');
        /* ログアウト処理
        ********************************************* */
        if(!empty($logoutRequest)) {
            //トークンチェック
            if (!validateToken(filter_input(INPUT_POST, 'token'))) {
                $errMsg[] = ERR_MSG99;
                http_response_code(400); //400 Bad Request
            }
            if(count($errMsg)) { //ログアウトが失敗したとき
                $errFlg = true;
            }
            else {
                setcookie(session_name(), '', 1); //セッション用Cookieの破棄
                session_destroy(); //セッションファイルの破棄
                showFinished(SHOW_MODE['lo']);
                exit();
            }
        }
        /* ログイン処理
        ********************************************* */
        else if(!empty($loginRequest)) {
            //入力を受け取る
            $loginUserid = filter_input(INPUT_POST, 'loginUserID');
            $loginPassword = filter_input(INPUT_POST, 'loginPassword');
            //トークンチェック
            if (!validateToken(filter_input(INPUT_POST, 'token'))) {
                $errMsg[] = ERR_MSG99;
                http_response_code(400); //400 Bad Request
            }
            //ユーザID、パスワードチェック
            if (empty($loginUserid) || $loginUserid !== GLOBAL_USER_ID || empty($loginPassword) ||
                !password_verify($loginPassword, GLOBAL_USER_PS)) {
                $errMsg[] = ERR_MSG61;
            }
            //チェック
            if(count($errMsg)) { //認証が失敗したとき
                $errFlg = true;
                http_response_code(403); //403 Forbidden
            }
            else { //認証が成功したとき
                session_regenerate_id(true); //セッションIDの追跡を防ぐ
                $_SESSION['userid'] = $loginUserid; //ユーザIDをセット
                $modeLogin = requireLoginedSession();
            }
        }
        /* コンテンツ管理
        ********************************************* */
        /* 新規作成
        ********************************************* */
        else if(!empty($contentsNewRequest)) {
            //トークンチェック
            if (!validateToken(filter_input(INPUT_POST, 'token'))) {
                $errMsg[] = ERR_MSG99;
                http_response_code(400); //400 Bad Request
            }
            //タイトル
            $contentsNewTitle = filter_input(INPUT_POST, 'contentsNewTitle');
            //本文
            $contentsNewContents = filter_input(INPUT_POST, 'contentsNewContents');
            //キーワード
            $contentsNewKeywords = filter_input(INPUT_POST, 'contentsNewKeywords');
            //最終更新日付
            $contentsNewLastDate = date('c');
            //チェック
            if(count($errMsg)) {
                $errFlg = true;
            }
            else {
                $contentsArray = _jd(CONTENTS_DATA);
                $contentsArray[] = contentsCreate($contentsNewTitle, $contentsNewContents, $contentsNewKeywords, $contentsNewLastDate); //追加
                $contentJsonStr = _je($contentsArray);
                $readFile = new SplFileObject(__FILE__, 'r');
                //ファイル操作
                $fileData = '';
                while (!$readFile->eof()) {
                    $line = $readFile->fgets();
                    //コンテンツ
                    $line = preg_replace('/^const CONTENTS_DATA = \'(.*)\';/', 'const CONTENTS_DATA = \'' . _q($contentJsonStr) . '\';', $line);
                    $fileData .= $line;
                }
                //ファイル書き込み
                try {
                    $writeFile = new SplFileObject(__FILE__, 'w');
                    $result = $writeFile->fwrite($fileData);
                    if($result == NULL) {
                        throw new Exception(ERR_MSG70);
                    }
                    dashboardFinished(SHOW_MODE['co']); //完了画面表示
                    exit();
                } catch (Exception $e) {
                    echo $e->getMessage(), "\n";
                }
            }
        }
        /* 更新
        ********************************************* */
        else if(!empty($contentsUpdateRequest)) {
            //トークンチェック
            if (!validateToken(filter_input(INPUT_POST, 'token'))) {
                $errMsg[] = ERR_MSG99;
                http_response_code(400); //400 Bad Request
            }
            //ID
            $updateID = -1;
            $contentsUpdateID = filter_input(INPUT_POST, 'contentsUpdateID');
            $contentsArray = _jd(CONTENTS_DATA);
            if(empty($contentsUpdateID) && $contentsUpdateID !== '0') { //IDがパラメータで渡ってこなかったとき
                $errMsg[] = ERR_MSG50;
            }
            else {
                $updateID = (int)$contentsUpdateID;
                if(!array_key_exists($updateID, $contentsArray)) { //指定するIDが存在しなかった場合
                    $errMsg[] = ERR_MSG51;
                }
            }
            //タイトル
            $contentsUpdateTitle = filter_input(INPUT_POST, 'contentsUpdateTitle');
            //本文
            $contentsUpdateContents = filter_input(INPUT_POST, 'contentsUpdateContents');
            //キーワード
            $contentsUpdateKeywords = filter_input(INPUT_POST, 'contentsUpdateKeywords');
            //最終更新日付
            $contentsUpdateLastDate = date('c');
            //チェック
            if(count($errMsg)) {
                $errFlg = true;
            }
            else {
//                $contentsArray = _jd(CONTENTS_DATA);
                $contentsArray[$updateID] = contentsCreate($contentsUpdateTitle, $contentsUpdateContents, $contentsUpdateKeywords, $contentsUpdateLastDate); //更新
                //最終更新日のみ抽出
                foreach ((array) $contentsArray as $key => $value) {
                    $value = (array)$value;
                    $contentsArray[$key] = $value;
                    $sort[$key] = $value['la'];
                }
                //最終更新日降順でソート
                array_multisort($sort, SORT_DESC, $contentsArray);
                //文字列に再変換
                $contentJsonStr = _je($contentsArray);
                $readFile = new SplFileObject(__FILE__, 'r');
                //ファイル操作
                $fileData = '';
                while (!$readFile->eof()) {
                    $line = $readFile->fgets();
                    //コンテンツ
                    $line = preg_replace('/^const CONTENTS_DATA = \'(.*)\';/', 'const CONTENTS_DATA = \'' . _q($contentJsonStr) . '\';', $line);
                    $fileData .= $line;
                }
                //ファイル書き込み
                try {
                    $writeFile = new SplFileObject(__FILE__, 'w');
                    $result = $writeFile->fwrite($fileData);
                    if($result == NULL) {
                        throw new Exception(ERR_MSG70);
                    }
                    dashboardFinished(SHOW_MODE['co']); //完了画面表示
                    exit();
                } catch (Exception $e) {
                    echo $e->getMessage(), "\n";
                }
            }
        }
        /* 削除
        ********************************************* */
        else if(!empty($contentsDeleteRequest)) {
            //トークンチェック
            if (!validateToken(filter_input(INPUT_POST, 'token'))) {
                $errMsg[] = ERR_MSG99;
                http_response_code(400); //400 Bad Request
            }
            //ID
            $deleteID = -1;
            $contentsDeleteID = filter_input(INPUT_POST, 'contentsDeleteID');
            $contentsArray = _jd(CONTENTS_DATA);
            if(empty($contentsDeleteID) && $contentsDeleteID !== '0') { //IDがパラメータで渡ってこなかったとき
                $errMsg[] = ERR_MSG52;
            }
            else {
                $deleteID = (int)$contentsDeleteID;
                if(!array_key_exists($deleteID, $contentsArray)) { //指定するIDが存在しなかった場合
                    $errMsg[] = ERR_MSG51;
                }
            }
            //チェック
            if(count($errMsg)) {
                $errFlg = true;
            }
            else {
                unset($contentsArray[$deleteID]); //削除
                $contentsArray = array_values($contentsArray);
                $contentJsonStr = _je($contentsArray);
                $readFile = new SplFileObject(__FILE__, 'r');
                //ファイル操作
                $fileData = '';
                while (!$readFile->eof()) {
                    $line = $readFile->fgets();
                    //コンテンツ
                    $line = preg_replace('/^const CONTENTS_DATA = \'(.*)\';/', 'const CONTENTS_DATA = \'' . _q($contentJsonStr) . '\';', $line);
                    $fileData .= $line;
                }
                //ファイル書き込み
                try {
                    $writeFile = new SplFileObject(__FILE__, 'w');
                    $result = $writeFile->fwrite($fileData);
                    if($result == NULL) {
                        throw new Exception(ERR_MSG70);
                    }
                    dashboardFinished(SHOW_MODE['co']); //完了画面表示
                    exit();
                } catch (Exception $e) {
                    echo $e->getMessage(), "\n";
                }
            }
        }
        /* サイト設定変更
        ********************************************* */
        else if(!empty($siteRequest)) {
            //トークンチェック
            if (!validateToken(filter_input(INPUT_POST, 'token'))) {
                $errMsg[] = ERR_MSG99;
                http_response_code(400); //400 Bad Request
            }
            //サイト名
            $siteSiteName = filter_input(INPUT_POST, 'siteSiteName');
            if(empty($siteSiteName)) {
                $errMsg[] = ERR_MSG80 . ' (サイト名)';
            }
            //説明
            $siteDescription = filter_input(INPUT_POST, 'siteDescription');
            if(empty($siteDescription)) {
                $siteDescription = GLOBAL_DESCRIPTION; //以前の設定をそのまま引き継ぐ
            }
            //テーマカラー
            $siteThemeColor = filter_input(INPUT_POST, 'siteThemeColor');
            if(empty($siteThemeColor)) {
                $siteThemeColor = GLOBAL_THEME_COLOR; //以前の設定をそのまま引き継ぐ
            }
            //発行年数
            $siteCRYear = filter_input(INPUT_POST, 'siteCRYear');
            if(empty($siteCRYear)) {
                $errMsg[] = ERR_MSG80 . ' (年)';
            }
            //OGP・Twitterアカウント
            $siteOGPTUserID = filter_input(INPUT_POST, 'siteOGPTUserID');
            if(empty($siteOGPTUserID)) {
                $siteOGPTUserID = GLOBAL_OGP_TWITTER_ACCOUNT; //以前の設定をそのまま引き継ぐ
            }
            //OGP・画像
            $siteOGPImage = filter_input(INPUT_POST, 'siteOGPImage');
            if(empty($siteOGPImage)) {
                $siteOGPImage = GLOBAL_OGP_IMAGE; //以前の設定をそのまま引き継ぐ
            }
            //OGP・URL
            $siteOGPURL = filter_input(INPUT_POST, 'siteOGPURL');
            if(empty($siteOGPURL)) {
                $siteOGPURL = GLOBAL_OGP_URL; //以前の設定をそのまま引き継ぐ
            }
            //チェック
            if(count($errMsg)) {
                $errFlg = true;
            }
            else {
                $readFile = new SplFileObject(__FILE__, 'r');
                //ファイル操作
                $fileData = '';
                while (!$readFile->eof()) {
                    $line = $readFile->fgets();
                    //サイト名
                    $line = preg_replace('/^const GLOBAL_SITENAME = \'(.*)\';/', 'const GLOBAL_SITENAME = \'' . _q($siteSiteName) . '\';', $line);
                    //説明
                    $line = preg_replace('/^const GLOBAL_DESCRIPTION = \'(.*)\';/', 'const GLOBAL_DESCRIPTION = \'' . _q($siteDescription) . '\';', $line);
                    //テーマカラー
                    $line = preg_replace('/'. GLOBAL_THEME_COLOR .'/', _q($siteThemeColor), $line);
                    $line = preg_replace('/^const GLOBAL_THEME_COLOR = \'(.*)\';/', 'const GLOBAL_THEME_COLOR = \'' . _q($siteThemeColor) . '\';', $line);
                    //年
                    $line = preg_replace('/^const GLOBAL_COPYRIGHT_YEAR = \'(.*)\';/', 'const GLOBAL_COPYRIGHT_YEAR = \'' . _q($siteCRYear) . '\';', $line);
                    //OGP
                    $line = preg_replace('/^const GLOBAL_OGP_TWITTER_ACCOUNT = \'(.*)\';/', 'const GLOBAL_OGP_TWITTER_ACCOUNT = \'' . _q($siteOGPTUserID) . '\';', $line);
                    $line = preg_replace('/^const GLOBAL_OGP_IMAGE = \'(.*)\';/', 'const GLOBAL_OGP_IMAGE = \'' . _q($siteOGPImage) . '\';', $line);
                    $line = preg_replace('/^define\(\'GLOBAL_OGP_URL\', (.*)\);/', 'define(\'GLOBAL_OGP_URL\', \'' . _q($siteOGPURL) . '\');', $line);
                    $fileData .= $line;
                }
                //ファイル書き込み
                try {
                    $writeFile = new SplFileObject(__FILE__, 'w');
                    $result = $writeFile->fwrite($fileData);
                    if($result == NULL) {
                        throw new Exception(ERR_MSG70);
                    }
                    dashboardFinished(SHOW_MODE['st']); //完了画面表示
                    exit();
                } catch (Exception $e) {
                    echo $e->getMessage(), "\n";
                }
            }
        }
        /* アカウント設定変更
        ********************************************* */
        else if(!empty($accountRequest)) {
            //トークンチェック
            if (!validateToken(filter_input(INPUT_POST, 'token'))) {
                $errMsg[] = ERR_MSG99;
                http_response_code(400); //400 Bad Request
            }
            $accountNewPassword = '';
            //ユーザID
            $accountUserID = filter_input(INPUT_POST, 'accountUserID');
            if(empty($accountUserID)) {
                $errMsg[] = ERR_MSG80 . ' (ユーザID)';
            }
            else {
                if(!preg_match('/\A[a-z\d]{3,100}+\z/i', $accountUserID)) {
                    $errMsg[] = ERR_MSG81 . ' (ユーザID)';
                }
            }
            //ユーザ名
            $accountUserName = filter_input(INPUT_POST, 'accountUserName');
            if(empty($accountUserName)) {
                $accountUserName = $accountUserID; //ユーザIDをユーザ名に
            }
            //サイトURL
            $accountUserSite = filter_input(INPUT_POST, 'accountUserSite');
            if(empty($accountUserSite)) {
                $accountUserSite = GLOBAL_AUHTOR_URL; //以前の設定をそのまま引き継ぐ
            }
            //現在のパスワード
            $accountNowPasswordStr = filter_input(INPUT_POST, 'accountNowPassword');
            if(!empty($accountNowPasswordStr)) { //空欄の場合は変更処理をしない
                if(!password_verify($accountNowPasswordStr, GLOBAL_USER_PS)) { //一致しない場合エラー
                    $errMsg[] = ERR_MSG83;
                }
                //新しいパスワード
                $accountNewPasswordStr = filter_input(INPUT_POST, 'accountNewPassword');
                $accountConfirmPasswordStr = filter_input(INPUT_POST, 'accountConfirmPassword');
                if(empty($accountNewPasswordStr) || empty($accountConfirmPasswordStr)) {
                    $errMsg[] = ERR_MSG80 . ' (新しいパスワードか、再入力の新しいパスワードのいずれか)';
                }
                else {
                    if(!preg_match('/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[!-\/:-@[-`{-~])[!-~]{8,100}+\z/', $accountNewPasswordStr)) { //複雑さを満たしていない
                        $errMsg[] = ERR_MSG82 . ' (新しいパスワード)';
                    }
                    else if($accountNewPasswordStr !== $accountConfirmPasswordStr) { //確認用と一致しない
                        $errMsg[] = ERR_MSG83 . ' (新しいパスワード)';
                    }
                    else { //ハッシュ値に変換
                        $accountNewPassword = password_hash($accountNewPasswordStr, PASSWORD_BCRYPT);
                    }
                }
            }
            //チェック
            if(count($errMsg)) {
                $errFlg = true;
            }
            else {
                $readFile = new SplFileObject(__FILE__, 'r');
                //ファイル操作
                $fileData = '';
                while (!$readFile->eof()) {
                    $line = $readFile->fgets();
                    $line = preg_replace('/^const GLOBAL_USER_ID = \'(.*)\';/', 'const GLOBAL_USER_ID = \'' . _q($accountUserID) . '\';', $line);
                    $line = preg_replace('/^const GLOBAL_AUHTOR = \'(.*)\';/', 'const GLOBAL_AUHTOR = \'' . _q($accountUserName) . '\';', $line);
                    $line = preg_replace('/^const GLOBAL_USER_PS = \'(.*)\';/', 'const GLOBAL_USER_PS = \'' . _dl($accountNewPassword) . '\';', $line);
                    $line = preg_replace('/^const GLOBAL_AUHTOR_URL = \'(.*)\';/', 'const GLOBAL_AUHTOR_URL = \'' . _q($accountUserSite) . '\';', $line);
                    $fileData .= $line;
                }
                //ファイル書き込み
                try {
                    $writeFile = new SplFileObject(__FILE__, 'w');
                    $result = $writeFile->fwrite($fileData);
                    if($result == NULL) {
                        throw new Exception(ERR_MSG70);
                    }
                    dashboardFinished(SHOW_MODE['st']); //完了画面表示
                    exit();
                } catch (Exception $e) {
                    echo $e->getMessage(), "\n";
                }
            }
        }
    }
    /* 通常・バックアップ
    ********************************************* */
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        $backupRequest = filter_input(INPUT_GET, 'backupRequest');
        $dashbordReturn = filter_input(INPUT_GET, 'dashbordReturn');
        /* バックアップ処理
        ********************************************* */
        if(!empty($backupRequest)) {
            //トークンチェック
            if (!validateToken(filter_input(INPUT_GET, 'token'))) {
                $errMsg[] = ERR_MSG99;
                http_response_code(400); //400 Bad Request
            }
            if(count($errMsg)) { //処理が失敗したとき
                $errFlg = true;
            }
            $backupPath = __FILE__; //ファイルパス
            $backupFile = 'backup.php'; //ファイル
            header('Content-Type: application/force-download');
            header('Content-Length: '.filesize($backupPath));
            header('Content-disposition: attachment; filename="'.$backupFile.'"');
            readfile($backupPath);
        }
        /* ダッシュボードへ戻る
        ********************************************* */
        if(!empty($dashbordReturn)) {
            //トークンチェック
            if (!validateToken(filter_input(INPUT_GET, 'token'))) {
                $errMsg[] = ERR_MSG99;
                http_response_code(400); //400 Bad Request
            }
            if(count($errMsg)) { //処理が失敗したとき
                $errFlg = true;
            }
        }
    }
}
/* 未インストール
********************************************* */
else {
    /* インストール処理
    ********************************************* */
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        //postされた内容
        //トークンチェック
        if(!validateToken(filter_input(INPUT_POST, 'token'))) {
            $errMsg[] = ERR_MSG99;
            http_response_code(400); //400 Bad Request
        }
        //サイト名
        $siteName = filter_input(INPUT_POST, 'installSiteName');
        if(empty($siteName)) {
            $errMsg[] = ERR_MSG80 . ' (サイト名)';
        }
        //ユーザID
        $userID = filter_input(INPUT_POST, 'installUserID');
        if(empty($userID)) {
            $errMsg[] = ERR_MSG80 . ' (ユーザID)';
        }
        else {
            if(!preg_match('/\A[a-z\d]{3,100}+\z/i', $userID)) {
                $errMsg[] = ERR_MSG81 . ' (ユーザID)';
            }
        }
        //パスワード
        $password = '';
        $passwordStr = filter_input(INPUT_POST, 'installPassword');
        if(empty($passwordStr)) {
            $errMsg[] = ERR_MSG80 . ' (パスワード)';
        }
        else {
            if(!preg_match('/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[!-\/:-@[-`{-~])[!-~]{8,100}+\z/', $passwordStr)) {
                $errMsg[] = ERR_MSG82 . ' (パスワード)';
            }
            else { //ハッシュ値に変換
                $password = password_hash($passwordStr, PASSWORD_BCRYPT);
            }
        }
        //チェック
        if(count($errMsg)) {
            $errFlg = true;
        }
        else {
            $readFile = new SplFileObject(__FILE__, 'r');
            //ファイル操作
            $fileData = '';
            while (!$readFile->eof()) {
                $line = $readFile->fgets();
                $line = preg_replace('/^const GLOBAL_SITENAME = \'(.*)\';/', 'const GLOBAL_SITENAME = \'' . _q($siteName) . '\';', $line);
                $line = preg_replace('/^const GLOBAL_USER_ID = \'(.*)\';/', 'const GLOBAL_USER_ID = \'' . _q($userID) . '\';', $line);
                $line = preg_replace('/^const GLOBAL_AUHTOR = \'(.*)\';/', 'const GLOBAL_AUHTOR = \'' . _q($userID) . '\';', $line);
                $line = preg_replace('/^const GLOBAL_USER_PS = \'(.*)\';/', 'const GLOBAL_USER_PS = \'' . _dl($password) . '\';', $line);
                $line = preg_replace('/^const MODE_INSTALLED = 0;/', 'const MODE_INSTALLED = 1;', $line);
                $fileData .= $line;
            }
            //ファイル書き込み
            try {
                $writeFile = new SplFileObject(__FILE__, 'w');
                $result = $writeFile->fwrite($fileData);
                if($result == NULL) {
                    throw new Exception(ERR_MSG70);
                }
                showFinished(SHOW_MODE['is']);
                exit();
            } catch (Exception $e) {
                echo $e->getMessage(), "\n";
            }
        }
    }
    /* インストール開始
    ********************************************* */
    else {}
}
?><!DOCTYPE html>
<html lang="<?=_h(GLOBAL_APP_LANG)?>">
<head>
    <meta charset="<?=_h(GLOBAL_APP_ENCODE)?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no,address=no,email=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
if($modeLogin) { //ログイン中
?>
    <title>管理画面 | <?=_h(GLOBAL_SITENAME)?></title>
<?php
}
else { //通常
?>
    <title><?=_h(GLOBAL_DESCRIPTION)?> | <?=_h(GLOBAL_SITENAME)?></title>
<?php
}
?>
    <meta name="description" content="<?=_h(GLOBAL_DESCRIPTION)?>">

    <!-- no cache -->
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="<?=_h(date('c'))?>">

    <!-- theme color -->
    <meta name="theme-color" content="<?=_h(GLOBAL_THEME_COLOR)?>">

    <!-- css -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style type="text/css">
body{line-height:1.6;letter-spacing:.15rem}h1,h2,h3,h4,h5,h6{margin-top:0;margin-bottom:0}ul,ol{margin-bottom:0}.container-fulid .row{margin-left:0;margin-right:0}a,a:link{color:#a248b9;text-decoration:none;-webkit-transition:all 0.3s ease;transition:all 0.3s ease}a:visited{color:#723283;text-decoration:none}a:hover,a:active{color:#883c9c;text-decoration:underline}@-ms-viewport{width:auto;initial-scale:1}ul,ol{-webkit-margin-before:0;-webkit-margin-after:0;-webkit-padding-start:0}.navbar{background-color:#98B948}.navbar.navbar-light .navbar-brand,.navbar.navbar-light .navbar-brand:link,.navbar.navbar-light .navbar-brand:visited,.navbar.navbar-light .navbar-brand:hover,.navbar.navbar-light .navbar-brand:active,.navbar.navbar-light .navbar-brand:focus{color:#98B948}.navbar-light .navbar-toggler{border-color:transparent;background-color:transparent}.navbar-light .navbar-toggler[aria-expanded="true"] .navbar-toggler-icon{background-image:url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(48,48,48,0.5)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M6 6L 24 24M24 6L6 24'/%3E%3C/svg%3E")}.main{background-color:#fff;color:#333}.main p{margin-top:0.4rem;margin-bottom:0.4rem}.footer{clear:both;padding:1rem 0 0.5rem;background-color:#dedede;color:#333}.footer.footerAbsolute{position:absolute;bottom:0;width:100%}.footer .copyRight{display:block;margin:0 auto;font-size:0.8rem;text-align:center}.returnPageTop{display:none;position:fixed;bottom:2%;right:6%;z-index:90}.returnPageTop i{display:block;font-size:22px;width:44px;height:44px;line-height:44px;background-color:#a9c466;border-radius:4px;color:#fff;text-align:center;cursor:pointer;-webkit-transition:all 0.3s ease;transition:all 0.3s ease}.returnPageTop i:hover,.returnPageTop i:active{background-color:#98B948}.main .border-bottom{border-bottom:1px solid #98B948 !important}.main ul{padding-left:1rem}.main ol{padding-left:2rem}

    </style>
<?php
if(!MODE_INSTALLED || $modeLogin) { //未インストールorログイン中
    //インデックスしないようにmetaタグ追加
?>
    <meta name="robots" content="noindex,follow" />
<?php
}
if(MODE_INSTALLED) { //インストール済
?>
    <!-- twitter card -->
    <meta name="twitter:card" content="photo" />
    <meta name="twitter:site" content="@<?=_h(GLOBAL_OGP_TWITTER_ACCOUNT)?>" />
    <meta property="og:type" content="website" />
    <meta name="og:title" content="<?=_h(GLOBAL_SITENAME)?>" />
    <meta name="og:site_name" content="<?=_h(GLOBAL_SITENAME)?>" />
    <meta name="og:description" content="<?=_h(GLOBAL_DESCRIPTION)?>" />
    <meta name="og:image" content="<?=_h(GLOBAL_OGP_IMAGE)?>" />
    <meta name="og:url" content="<?=_h(GLOBAL_OGP_URL)?>" />
<?php
}
?>
</head>
<?php
/* ********************************************
 *                                            *
 * HTTP Status Code Error                     *
 *                                            *
 ******************************************** */
if (http_response_code() >= 400) {
    $statusMessage = '';
    $paragraphMessage = '通信エラーが発生しました。前のページに戻ってやりなおしてください。';
    if(http_response_code() === 400) { $statusMessage = 'Bad Request.'; }
    if(http_response_code() === 401) { $statusMessage = 'Unauthorized'; }
    if(http_response_code() === 403) { $statusMessage = 'Forbidden'; }
    if(http_response_code() === 404) { $statusMessage = 'Not Found'; }
    if(http_response_code() === 418) { $statusMessage = 'I\'m a teapot'; }
    if(http_response_code() === 500) { $statusMessage = 'Internal Server Error'; }
    if(http_response_code() === 502) { $statusMessage = 'Bad Gateway'; }
    if(http_response_code() === 503) { $statusMessage = 'Service Unavailable'; }
    if(http_response_code() === 511) { $statusMessage = 'Network Authentication Required'; }
?>
<body class="error" id="error">
    <div id="wrapper">
        <!-- header -->
        <header class="header">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="./"><?=_h(GLOBAL_SITENAME)?></a>
            </nav>
        </header>
        <!-- /header -->
        <!-- main -->
        <main class="main installMain">
            <div class="container-fluid mb-4">
                <div class="pb-2 mt-4 mb-2 border-bottom">
                    <h2><i class="fas fa-fw fa-exclamation-triangle" aria-hidden="true"></i><?=_h(http_response_code())?> <?=_h($statusMessage)?></h2>
                </div>
                <p><?=_h($paragraphMessage)?></p>
            </div>
            <div class="container-fluid">
                <a href="#" class="btn btn-light mt-3" id="goBack"><i class="fas fa-fw fa-undo" aria-hidden="true"></i>前のページに戻る</a>
            </div>
        </main>
        <!-- /main -->
        <div class="returnPageTop"><i class="fas fa-fw fa-arrow-up" aria-hidden="true"></i></div>
        <!-- footer -->
        <footer class="footer">
            <small class="copyRight">Copyright © <?=_h($copyRightYear)?> <a href="<?=_h(GLOBAL_AUHTOR_URL)?>"><?=_h(GLOBAL_AUHTOR)?></a> All Right Reserved.</small>
            <small class="copyRight">Powered by <a href="<?=_h(GLOBAL_APP_URL)?>"><?=_h(GLOBAL_APP_NAME)?> (ver.<?=_h(GLOBAL_APP_VERSION)?>)</a></small>
        </footer>
        <!-- /footer -->
    </div>
<?php
} //HTTPエラーページ ここまで
/* ********************************************
 *                                            *
 * エラー表示                                   *
 *                                            *
 ******************************************** */
else if($errFlg) { //エラーの場合
?>
<body class="error" id="error">
    <div id="wrapper">
        <!-- header -->
        <header class="header">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#"><?=_h(GLOBAL_SITENAME)?></a>
            </nav>
        </header>
        <!-- /header -->
        <!-- main -->
        <main class="main installMain">
            <div class="container-fluid mb-4">
                <div class="pb-2 mt-4 mb-2 border-bottom">
                    <h2><i class="fas fa-fw fa-exclamation-triangle" aria-hidden="true"></i>Error</h2>
                </div>
                <p>下記エラーがあります。前のページに戻って修正してください。</p>
            </div>
            <div class="container-fluid">
<?php
    if(count($errMsg) > 0) {
?>
                <ul>
<?php
        foreach($errMsg as $msg) {
?>
                    <li><?=$msg?></li>
<?php
        }
?>
                </ul>
<?php
    }
?>
                <a href="#" class="btn btn-light mt-3" id="goBack"><i class="fas fa-fw fa-undo" aria-hidden="true"></i>前のページに戻る</a>
            </div>
        </main>
        <!-- /main -->
        <div class="returnPageTop"><i class="fas fa-fw fa-arrow-up" aria-hidden="true"></i></div>
        <!-- footer -->
        <footer class="footer">
            <small class="copyRight">Copyright © <?=_h($copyRightYear)?> <a href="<?=_h(GLOBAL_AUHTOR_URL)?>"><?=_h(GLOBAL_AUHTOR)?></a> All Right Reserved.</small>
            <small class="copyRight">Powered by <a href="<?=_h(GLOBAL_APP_URL)?>"><?=_h(GLOBAL_APP_NAME)?> (ver.<?=_h(GLOBAL_APP_VERSION)?>)</a></small>
        </footer>
        <!-- /footer -->
    </div>
<?php
} //エラーページ ここまで
/* ********************************************
 *                                            *
 * インストール画面                              *
 *                                            *
 ******************************************** */
else if(!MODE_INSTALLED) { //未インストール
?>
<body class="install" id="install">
    <div id="wrapper">
        <!-- header -->
        <header class="header">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#"><?=_h(GLOBAL_APP_NAME)?></a>
            </nav>
        </header>
        <!-- /header -->
        <!-- main -->
        <main class="main installMain">
            <div class="container-fluid mb-4">
                <div class="pb-2 mt-4 mb-2 border-bottom">
                    <h2><i class="fas fa-fw fa-download" aria-hidden="true"></i>Install</h2>
                </div>
                <p><?=_h(GLOBAL_APP_NAME)?>(ver.<?=_h(GLOBAL_APP_VERSION)?>)をインストールします。下記項目を入力し、「送信」ボタンを押してください。</p>
            </div>
            <div class="container-fluid">
                <form method="post" action="./">
                    <div class="pb-2 mt-4 mb-2 border-bottom">
                        <h3><i class="fas fa-fw fa-globe-americas" aria-hidden="true"></i>Site Settings</h3>
                    </div>
                    <div class="form-group row">
                        <label for="installSiteName" class="col-md-2 col-form-label" data-toggle="tooltip" title="Webサイトの名前です。"><i class="fas fa-fw fa-file-signature" aria-hidden="true"></i>サイト名</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="installSiteName" id="installSiteName" placeholder="<?=_h(GLOBAL_APP_NAME)?>" required="required">
                        </div>
                    </div>
                    <div class="pb-2 mt-4 mb-2 border-bottom">
                        <h3><i class="fas fa-fw fa-id-card" aria-hidden="true"></i>User Settings</h3>
                    </div>
                    <div class="form-group row">
                        <label for="installUserID" class="col-md-2 col-form-label" data-toggle="tooltip" title="管理画面ログイン時に使用します。3文字以上100文字以下の半角英数字で入力してください。"><i class="fas fa-fw fa-id-badge" aria-hidden="true"></i>ユーザID</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="installUserID" id="installUserID" placeholder="<?=_h(GLOBAL_USER_ID)?>" required="required" pattern="^[a-zA-Z\d]{3,100}$">
                            <small class="form-text text-muted">3文字以上100文字以下の半角英数字で入力してください。</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="installPassword" class="col-md-2 col-form-label" data-toggle="tooltip" title="管理画面ログイン時に使用します。8文字以上100文字以下で、半角英数字と記号を組み合わせたものを設定してください。"><i class="fas fa-fw fa-key" aria-hidden="true"></i>パスワード</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="installPassword" id="installPassword" placeholder="" required="required" pattern="^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[!-/:-@[-`{-~])[!-~]{8,100}$">
                            <small class="form-text text-muted">8文字以上100文字以下で、半角英数字と記号を組み合わせてください。</small>
                        </div>
                    </div>
                    <input type="hidden" name="token" value="<?=_h(generateToken())?>">
                    <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-fw fa-paper-plane" aria-hidden="true"></i>送信する</button>
                </form>
            </div>
        </main>
        <!-- /main -->
        <div class="returnPageTop"><i class="fas fa-fw fa-arrow-up" aria-hidden="true"></i></div>
        <!-- footer -->
        <footer class="footer">
            <small class="copyRight">Copyright © <?=_h($appCopyRightYear)?> <a href="<?=_h(GLOBAL_APP_AUHTOR_URL)?>"><?=_h(GLOBAL_APP_AUHTOR)?></a> All Right Reserved.</small>
            <small class="copyRight">Powered by <a href="<?=_h(GLOBAL_APP_URL)?>"><?=_h(GLOBAL_APP_NAME)?> (ver.<?=_h(GLOBAL_APP_VERSION)?>)</a></small>
        </footer>
        <!-- /footer -->
    </div>
<?php
} //インストール画面 ここまで
/* ********************************************
 *                                            *
 * 通常                                        *
 *                                            *
 ******************************************** */
else { //インストール済
    if(!$modeLogin) { //フロントページ
?>
<body class="index" id="index">
    <div id="wrapper">
        <!-- header -->
        <header class="header">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="./"><?=_h(GLOBAL_SITENAME)?></a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="ナビゲーションの切替">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav ml-auto mr-5">
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle mr-5" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ログイン</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <form action="./" method="post">
                                    <div class="form-group mx-2">
                                        <label for="loginUserID"><i class="fas fa-fw fa-id-badge" aria-hidden="true"></i>ユーザID</label>
                                        <input type="text" class="form-control" name="loginUserID" id="loginUserID" placeholder="ユーザID" required="required" pattern="^[a-zA-Z\d]{3,100}$">
                                    </div>
                                    <div class="form-group mx-2">
                                        <label for="loginPassword"><i class="fas fa-fw fa-key" aria-hidden="true"></i>パスワード</label>
<?php
if(DEBUG_MODE) {
?>
                                        <input type="text" class="form-control" name="loginPassword" id="loginPassword" placeholder="パスワード" required="required">
<?php
} else {
?>
                                        <input type="text" class="form-control" name="loginPassword" id="loginPassword" placeholder="パスワード" required="required" pattern="^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[!-/:-@[-`{-~])[!-~]{8,100}$">
<?php
}
?>
                                    </div>
                                    <hr>
                                    <input type="hidden" name="token" value="<?=_h(generateToken())?>">
                                    <input type="hidden" name="loginRequest" value="loginRequest">
                                    <button type="submit" class="btn btn-primary mx-2"><i class="fas fa-fw fa-sign-in-alt" aria-hidden="true"></i>ログイン</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </nav>
        </header>
        <!-- /header -->
        <main class="main my-4">
            <div class="container-fulid">
<?php
if(count($contentsData)) { //コンテンツがある場合
?>
                <div class="row">
<?php
    for($i = count($contentsData) - 1; $i >= 0; $i--) { //逆順でループを回す
        $content = $contentsData[$i];
        $contentStr = _h($content->co);
        $contentContents = preg_replace('(\r\n|\r|\n)', '<br>$1', $contentStr);
        $lastActivityDate = dateConvert($content->la);
?>
                    <div class="xol-md-12 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?=_h($content->ti)?></h3>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><?=$contentContents?></p>
                            </div>
                            <div class="card-footer">
                                <p class="card-text"><span class="mr-2">キーワード:</span><?=_h($content->kw)?></p>
                                <p class="card-text text-right"><i class="fas fa-fw fa-history mr-2" aria-hidden="true"></i><?=_h($lastActivityDate)?></p>
                            </div>
                        </div>
                    </div>
<?php
    }
?>
                </div>
<?php
}
else { //コンテンツがない場合
?>
            <p class="m-3"><i class="fas fa-fw fa-info-circle" aria-hidden="true"></i>コンテンツがありません。</p>
<?php
}
?>
            </div>
        </main>
<?php
    } //フロントページ ここまで
    else { //ログイン中
?>
<body class="dashboard" id="dashboard">
    <div id="wrapper">
        <!-- header -->
        <header class="header">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="./"><?=_h(GLOBAL_SITENAME)?></a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="ナビゲーションの切替">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav ml-auto mr-5">
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle mr-5" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=_h(GLOBAL_AUHTOR)?>さん</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <form action="./" method="post">
                                    <a href="#" class="dropdown-item" id="navbarAccount"><i class="fas fa-fw fa-user" aria-hidden="true"></i>アカウント設定</a>
                                    <hr>
                                    <input type="hidden" name="token" value="<?=_h(generateToken())?>">
                                    <input type="hidden" name="logoutRequest" value="logoutRequest">
                                    <button type="submit" class="btn btn-info mx-2"><i class="fas fa-fw fa-sign-out-alt" aria-hidden="true"></i>ログアウト</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </nav>
        </header>
        <!-- /header -->
        <main class="main my-4">
            <ul class="nav nav-tabs" id="dashboardTabs">
                <li class="nav-item"><a href="#" data-target="#dashboardCarousel" data-slide-to="0" class="nav-link active"><i class="fas fa-fw fa-file-signature" aria-hidden="true"></i>コンテンツ</a></li>
                <li class="nav-item"><a href="#" data-target="#dashboardCarousel" data-slide-to="1" class="nav-link"><i class="fas fa-fw fa-globe-americas" aria-hidden="true"></i>サイト設定</a></li>
                <li class="nav-item"><a href="#" data-target="#dashboardCarousel" data-slide-to="2" class="nav-link" id="tabAccount"><i class="fas fa-fw fa-user" aria-hidden="true"></i>アカウント設定</a></li>
                <li class="nav-item"><a href="#" data-target="#dashboardCarousel" data-slide-to="3" class="nav-link"><i class="fas fa-fw fa-database" aria-hidden="true"></i>バックアップ</a></li>
            </ul>
            <!-- .carousel -->
            <div id="dashboardCarousel" class="carousel dashboardCarousel slide" data-interval="false" data-wrap="false">
                <!-- .carousel-inner -->
                <div class="carousel-inner">
                    <!-- contents -->
                    <div class="carousel-item active">
                        <div class="container-fluid">
                            <div class="pb-2 mt-4 mb-2 border-bottom">
                                <h2><i class="fas fa-fw fa-file-signature" aria-hidden="true"></i>Contents Manage</h2>
                            </div>
                            <p>コンテンツを管理します。</p>
                            <p class="my-3">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#contentsNew"><i class="fas fa-fw fa-pen-nib" aria-hidden="true"></i>新規作成</button>
                            </p>
<?php
if(count($contentsData)) { //コンテンツがある場合
?>
                            <div class="my-4">
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>タイトル</th>
                                                <th>キーワード</th>
                                                <th>最終更新日</th>
                                                <th>修正・削除</th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php
for($i = count($contentsData) - 1; $i >= 0; $i--) { //逆順でループを回す
    $content = $contentsData[$i];
    $lastActivityDate = dateConvert($content->la);
?>
                                            <tr>
                                                <td><?=_h($content->ti)?></td>
                                                <td><?=_h($content->kw)?></td>
                                                <td><?=_h($lastActivityDate)?></td>
                                                <td><button type="button" class="btn btn-primary contentsUpdate_button" data-contentid="<?=_h($i)?>" data-toggle="modal" data-target="#contentsUpdate"><i class="fas fa-fw fa-pen" aria-hidden="true"></i>修正</button><button type="button" class="btn btn-warning ml-3 contentsDelete_button" data-contentid="<?=_h($i)?>" data-toggle="modal" data-target="#contentsDelete"><i class="fas fa-fw fa-eraser" aria-hidden="true"></i>削除</button></td>
                                            </tr>
<?php
}
?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
<?php
}
else { //コンテンツがない場合
?>
                            <p class="my-4"><i class="fas fa-fw fa-info-circle" aria-hidden="true"></i>まだコンテンツがありません。「新規作成」ボタンから、あなただけのコンテンツを作成しましょう！</p>
<?php
}
?>
                        </div>
                        <div class="dataField hidden">
                            <div class="data" id="dataContents" data-contents='<?=_h(_q(CONTENTS_DATA))?>'></div>
                        </div>
                    </div>
                    <!-- /contents -->
                    <!-- site -->
                    <div class="carousel-item">
                        <div class="container-fluid">
                            <div class="pb-2 mt-4 mb-2 border-bottom">
                                <h2><i class="fas fa-fw fa-sitemap" aria-hidden="true"></i>Site Settings</h2>
                            </div>
                            <p>サイトに関する設定を行います。</p>
                            <form action="./" method="post">
                                <h3 class="mt-4 mb-3">サイト設定</h3>
                                <div class="form-group row">
                                    <label for="siteSiteName" class="col-md-2 col-form-label" data-toggle="tooltip" title="Webサイトの名前です。"><i class="fas fa-fw fa-file-signature" aria-hidden="true"></i>サイト名</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="siteSiteName" id="siteSiteName" placeholder="<?=_h(GLOBAL_SITENAME)?>" value="<?=_h(GLOBAL_SITENAME)?>" required="required">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="siteDescription" class="col-md-2 col-form-label" data-toggle="tooltip" title="Webサイトの説明・キャッチフレーズです。titleタグやmetaタグのdescriptionに使用します。"><i class="fas fa-fw fa-pen-fancy" aria-hidden="true"></i>説明</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="siteDescription" id="siteDescription" placeholder="<?=_h(GLOBAL_DESCRIPTION)?>" value="<?=_h(GLOBAL_DESCRIPTION)?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="siteThemeColor" class="col-md-2 col-form-label" data-toggle="tooltip" title="Webサイトのイメージカラーです。"><i class="fas fa-fw fa-palette" aria-hidden="true"></i>テーマカラー</label>
                                    <div class="col-md-10">
                                        <input type="color" class="form-control" name="siteThemeColor" id="siteThemeColor" value="<?=_h(GLOBAL_THEME_COLOR)?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="siteCRYear" class="col-md-2 col-form-label" data-toggle="tooltip" title="Webサイトの発行年数です。コピーライトの表示に使います。"><i class="fas fa-fw fa-calendar" aria-hidden="true"></i>年</label>
                                    <div class="col-md-10">
                                        <input type="number" class="form-control" size="4" name="siteCRYear" id="siteCRYear" placeholder="<?=_h(GLOBAL_COPYRIGHT_YEAR)?>" value="<?=_h(GLOBAL_COPYRIGHT_YEAR)?>" required="required">
                                    </div>
                                </div>
                                <h3 class="mt-4 mb-3">OGP設定</h3>
                                <div class="form-group row">
                                    <label for="siteOGPTUserID" class="col-md-2 col-form-label" data-toggle="tooltip" title="OGPに設定するTwitterアカウントのユーザIDを入力してください。"><i class="fab fa-fw fa-twitter" aria-hidden="true"></i>TwitterユーザID</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="siteOGPTUserID" id="siteOGPTUserID" placeholder="<?=_h(GLOBAL_OGP_TWITTER_ACCOUNT)?>" value="<?=_h(GLOBAL_OGP_TWITTER_ACCOUNT)?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="siteOGPImage" class="col-md-2 col-form-label" data-toggle="tooltip" title="OGPに設定する画像のURLを指定してください。相対パスではなく、絶対パスで指定してください。"><i class="fas fa-fw fa-image" aria-hidden="true"></i>OGP画像</label>
                                    <div class="col-md-10">
                                        <input type="url" class="form-control" name="siteOGPImage" id="siteOGPImage" placeholder="<?=_h(GLOBAL_OGP_IMAGE)?>" value="<?=_h(GLOBAL_OGP_IMAGE)?>">
                                        <small class="form-text text-muted">相対パスではなく、絶対パスで指定してください。</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="siteOGPURL" class="col-md-2 col-form-label" data-toggle="tooltip" title="OGPに設定するWebサイトのURLを指定してください。相対パスではなく、絶対パスで指定してください。"><i class="fas fa-fw fa-link" aria-hidden="true"></i>URL</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="siteOGPURL" id="siteOGPURL" placeholder="<?=_h(GLOBAL_OGP_URL)?>" value="<?=_h(GLOBAL_OGP_URL)?>">
                                        <small class="form-text text-muted">相対パスではなく、絶対パスで指定してください。</small>
                                    </div>
                                </div>
                                <input type="hidden" name="token" value="<?=_h(generateToken())?>">
                                <input type="hidden" name="siteRequest" value="siteRequest">
                                <button type="submit" class="btn btn-success"><i class="fas fa-fw fa-sync-alt" aria-hidden="true"></i>更新</button>
                            </form>
                        </div>
                    </div>
                    <!-- /site -->
                    <!-- account -->
                    <div class="carousel-item">
                        <div class="container-fluid">
                            <div class="pb-2 mt-4 mb-2 border-bottom">
                                <h2><i class="fas fa-fw fa-user" aria-hidden="true"></i>Account Settings</h2>
                            </div>
                            <p>アカウントに関する設定を行います。</p>
                            <form action="./" method="post">
                                <h3 class="mt-4 mb-3">ユーザ設定</h3>
                                <div class="form-group row">
                                    <label for="accountUserID" class="col-md-2 col-form-label" data-toggle="tooltip" title="管理画面ログイン時に使用します。3文字以上100文字以下で、半角英数字と記号を組み合わせたものを設定してください。"><i class="fas fa-fw fa-id-badge" aria-hidden="true"></i>ユーザID</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="accountUserID" id="accountUserID" placeholder="<?=_h(GLOBAL_USER_ID)?>" value="<?=_h(GLOBAL_USER_ID)?>" required="required" pattern="^[a-zA-Z\d]{3,100}$">
                                        <small class="form-text text-muted">3文字以上100文字以下の半角英数字で入力してください。</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="accountUserName" class="col-md-2 col-form-label" data-toggle="tooltip" title="ログインした際の表示や各記事、フッタのコピーライト表示の著者情報として表示します。空欄の場合、ユーザIDを使用します。"><i class="fas fa-fw fa-user" aria-hidden="true"></i>ユーザ名</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="accountUserName" id="accountUserName" placeholder="<?=_h(GLOBAL_AUHTOR)?>" value="<?=_h(GLOBAL_AUHTOR)?>">
                                        <small class="form-text text-muted">空欄の場合、ユーザIDをユーザ名として使用します。</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="accountUserSite" class="col-md-2 col-form-label" data-toggle="tooltip" title="フッタのコピーライト表示に付けるリンクを設定します。"><i class="fas fa-fw fa-link" aria-hidden="true"></i>ユーザのサイト</label>
                                    <div class="col-md-10">
                                        <input type="url" class="form-control" name="accountUserSite" id="accountUserSite" placeholder="<?=_h(GLOBAL_AUHTOR_URL)?>" value="<?=_h(GLOBAL_AUHTOR_URL)?>">
                                    </div>
                                </div>
                                <h3 class="mt-4 mb-3">パスワード変更</h3>
                                <div class="form-group row">
                                    <label for="accountNowPassword" class="col-md-2 col-form-label" data-toggle="tooltip" title="パスワード変更のために、現在のパスワードを確認します。現在のパスワードを入力してください。"><i class="fas fa-fw fa-unlock" aria-hidden="true"></i>現在のパスワード</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="accountNowPassword" id="accountNowPassword" placeholder="現在のパスワード">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="accountNewPassword" class="col-md-2 col-form-label" data-toggle="tooltip" title="変更したい新しいパスワードを入力してください。"><i class="fas fa-fw fa-key" aria-hidden="true"></i>新しいパスワード</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="accountNewPassword" id="accountNewPassword" placeholder="新しいパスワード" pattern="(^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[!-/:-@[-`{-~])[!-~]{8,100}$|^$)">
                                        <small class="form-text text-muted">8文字以上100文字以下で、半角英数字と記号を組み合わせてください。</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="accountConfirmPassword" class="col-md-2 col-form-label" data-toggle="tooltip" title="確認のため、もう一度新しいパスワードを入力してください。"><i class="fas fa-fw fa-key" aria-hidden="true"></i>新しいパスワード(確認)</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="accountConfirmPassword" id="accountConfirmPassword" placeholder="新しいパスワード(確認)" pattern="(^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[!-/:-@[-`{-~])[!-~]{8,100}$|^$)">
                                    </div>
                                </div>
                                <input type="hidden" name="token" value="<?=_h(generateToken())?>">
                                <input type="hidden" name="accountRequest" value="accountRequest">
                                <button type="submit" class="btn btn-success"><i class="fas fa-fw fa-sync-alt" aria-hidden="true"></i>更新</button>
                            </form>
                        </div>
                    </div>
                    <!-- /account -->
                    <!-- backup -->
                    <div class="carousel-item">
                        <div class="container-fluid">
                            <div class="pb-2 mt-4 mb-2 border-bottom">
                                <h2><i class="fas fa-fw fa-database" aria-hidden="true"></i>Backup</h2>
                            </div>
                            <p>サイトのデータをバックアップします。</p>
                            <p class="mb-3">下記の「バックアップ」ボタンを押してダウンロードを実行してください。</p>
                            <form action="./" method="get">
                                <input type="hidden" name="token" value="<?=_h(generateToken())?>">
                                <input type="hidden" name="backupRequest" value="backupRequest">
                                <button type="submit" class="btn btn-success"><i class="fas fa-fw fa-download" aria-hidden="true"></i>バックアップ</button>
                            </form>
                        </div>
                    </div>
                    <!-- /backup -->
                </div>
                <!-- /.carousel-inner -->
            </div>
            <!-- /.carousel -->
        </main>

        <!-- new .modal -->
        <div class="modal fade" id="contentsNew" tabindex="-1" role="dialog" aria-labelledby="contentsNewLabel">
            <div class="modal-dialog modal-lg" role="document">
                <form action="./" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="contentsNewLabel"><i class="fas fa-fw fa-pen-nib" aria-hidden="true"></i>新規作成</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                                <i class="fas fa-fw fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="contentsNewTitle">タイトル</label>
                                <input type="text" class="form-control" name="contentsNewTitle" id="contentsNewTitle">
                            </div>
                            <div class="form-group">
                                <label for="contentsNewContents">本文</label>
                                <textarea class="form-control" name="contentsNewContents" id="contentsNewContents"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="contentsNewKeywords">キーワード</label>
                                <input type="text" class="form-control" name="contentsNewKeywords" id="contentsNewKeywords">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="token" value="<?=_h(generateToken())?>">
                            <input type="hidden" name="contentsNewRequest" value="contentsNewRequest">
                            <button type="submit" class="btn btn-success"><i class="fas fa-fw fa-upload" aria-hidden="true"></i>公開</button>
                        </div><!-- /.modal-footer -->
                    </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /new .modal -->

        <!-- update .modal -->
        <div class="modal fade" id="contentsUpdate" tabindex="-1" role="dialog" aria-labelledby="contentsUpdateLabel">
            <div class="modal-dialog modal-lg" role="document">
                <form action="./" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="contentsUpdateLabel"><i class="fas fa-fw fa-pen-nib" aria-hidden="true"></i>修正</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                                <i class="fas fa-fw fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="contentsUpdateTitle">タイトル</label>
                                <input type="text" class="form-control" name="contentsUpdateTitle" id="contentsUpdateTitle">
                            </div>
                            <div class="form-group">
                                <label for="contentsUpdateContents">本文</label>
                                <textarea class="form-control" name="contentsUpdateContents" id="contentsUpdateContents"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="contentsUpdateKeywords">キーワード</label>
                                <input type="text" class="form-control" name="contentsUpdateKeywords" id="contentsUpdateKeywords">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="contentsUpdateID" id="contentsUpdateID">
                            <input type="hidden" name="token" value="<?=_h(generateToken())?>">
                            <input type="hidden" name="contentsUpdateRequest" value="contentsUpdateRequest">
                            <button type="submit" class="btn btn-success"><i class="fas fa-fw fa-upload" aria-hidden="true"></i>更新</button>
                        </div><!-- /.modal-footer -->
                    </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /update .modal -->

        <!-- delete .modal -->
        <div class="modal fade" id="contentsDelete" tabindex="-1" role="dialog" aria-labelledby="contentsDeleteLabel">
            <div class="modal-dialog modal-lg" role="document">
                <form action="./" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="contentsDeleteLabel"><i class="fas fa-fw fa-eraser" aria-hidden="true"></i>削除</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                                <i class="fas fa-fw fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="contentsDeleteTitle">タイトル</label>
                                <input type="text" class="form-control-plaintext" name="contentsDeleteTitle" id="contentsDeleteTitle" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <p>削除したコンテンツは二度と元には戻せません！本当によろしいですか？</p>
                            <input type="hidden" name="contentsDeleteID" id="contentsDeleteID">
                            <input type="hidden" name="token" value="<?=_h(generateToken())?>">
                            <input type="hidden" name="contentsDeleteRequest" value="contentsDeleteRequest">
                            <button type="submit" class="btn btn-danger"><i class="fas fa-fw fa-eraser" aria-hidden="true"></i>削除</button>
                        </div><!-- /.modal-footer -->
                    </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /delete .modal -->

<?php
    } //ログイン中 ここまで
?>
        <div class="returnPageTop"><i class="fas fa-fw fa-arrow-up" aria-hidden="true"></i></div>
        <!-- footer -->
        <footer class="footer">
            <small class="copyRight">Copyright © <?=_h($appCopyRightYear)?> <a href="<?=_h(GLOBAL_AUHTOR_URL)?>"><?=_h(GLOBAL_AUHTOR)?></a> All Right Reserved.</small>
            <small class="copyRight">Powered by <a href="<?=_h(GLOBAL_APP_URL)?>"><?=_h(GLOBAL_APP_NAME)?> (ver.<?=_h(GLOBAL_APP_VERSION)?>)</a></small>
        </footer>
        <!-- /footer -->
<!-- 条件分岐はここまで、scriptとbody・html閉じタグは共通に -->
<?php
}
?>
    </div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bowser/1.9.4/bowser.min.js"></script>
<script>
function pageTop(){var t=$(".returnPageTop");$(window).on("scroll",function(){400<$(this).scrollTop()?t.fadeIn():t.fadeOut()}),t.on("click",function(){return $("body, html").animate({scrollTop:0},1e3,"easeInOutCirc"),!1})}function pageScroll(){if($("#index").length){var o=parseInt($("#index").attr("data-offset")),a=$("#navbar");a.find("a:not(.dropdown-toggle)").on("click",function(){var t=$(this).attr("href"),e="";e=/^(\.\/|\/)$|^(#)?$/.test(t)?"html":/^(\.\/|\/)#.+/.test(t)?t.slice(RegExp.$1.length):t;var n=$(e).offset().top-o;return $("body, html").animate({scrollTop:n},1e3,"easeInOutCirc"),a.find('.navbar-toggle[data-target="#navbarList"]').click(),!1})}}function footerPosition(){var t=$(".footer");t.height();$("#wrapper").height()+$(".header").height()+t.height()<=window.innerHeight?t.addClass("footerAbsolute"):t.removeClass("footerAbsolute")}$(function(){if(pageTop(),pageScroll(),$('[data-toggle="tooltip"]').tooltip(),$("#error").length&&$("#goBack").on("click",function(){return window.history.back(-1),!1}),$("#dashboard").length){var t=JSON.parse($("#dataContents").attr("data-contents")),e=$("#dashboardTabs");e.find("a").on("click",function(){e.find(".nav-link.active").removeClass("active"),$(this).addClass("active"),footerPosition()}),$("#navbarAccount").on("click",function(){$("#tabAccount").trigger("click")}),$(".contentsUpdate_button").on("click",function(){$("#contentsUpdateTitle").attr("value",""),$("#contentsUpdateContents").text(""),$("#contentsUpdateKeywords").attr("value",""),$("#contentsUpdateID").attr("value","");var n,o=parseInt($(this).attr("data-contentid"));$.each(t,function(t,e){t===o&&(n=e)}),$("#contentsUpdateTitle").attr("value",n.ti),$("#contentsUpdateContents").text(n.co),$("#contentsUpdateKeywords").attr("value",n.kw),$("#contentsUpdateID").attr("value",o)}),$(".contentsDelete_button").on("click",function(){var n,o=parseInt($(this).attr("data-contentid"));$.each(t,function(t,e){t===o&&(n=e)}),$("#contentsDeleteTitle").attr("value",n.ti),$("#contentsDeleteID").attr("value",o)})}}),$(window).on("load resize",function(){footerPosition()});
</script>
</body>
</html>