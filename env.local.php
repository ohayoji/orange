<?php
const env = [
    // DB
    'MY_SERVER' => 'db', //docker-compose.ymlのlinksで指定している名前が使用できる
    'USER_NAME' => 'root',
    'PASSWORD' => 'QazxSw',
    'DB_NAME' => 'turba_orange',

    //ホームページ
    'URL_HOME_PAGE' => 'http://localhost:8080/',
    //登録ページ
    'URL_ADD_COMP' => 'http://localhost:8080/admission_comp.php',
    //パスワード再発行ページ
    'URL_PASS_REISSUE' => 'http://localhost:8080/password_reissue.php',
    //ログインページ
    'URL_LOGIN' => 'http://localhost:8080/pages/login.php',
    //スタッフサインアップページ
    'URL_SIGNUP' => 'http://localhost:8080/signup.php',
    //送信専用メールアドレス
    'POSTING_SENDER' => 'rivermouth1103+dummy_1@gmail.com',
    //メインメールアドレス
    'MAIN_SENDER' => 'rivermouth1103+dummy_1@gmail.com',
];
