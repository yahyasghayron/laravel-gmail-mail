<?php

use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Dacastro4\LaravelGmail\GmailConnection;
use Dacastro4\LaravelGmail\LaravelGmailClass;
use Illuminate\Support\Facades\Route;
use Dacastro4\LaravelGmail\Services\Message\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/oauth/gmail', function () {
    return LaravelGmail::redirect();
});

Route::get('/oauth/gmail/callback', function () {
    $response = LaravelGmail::makeToken();
    $client = new GuzzleHttp\Client();
    $res = $client->get('https://gmail.googleapis.com/gmail/v1/users/me/profile', ['headers'=>['Authorization' =>  'Bearer '.$response['access_token']]]);

    echo $res->getBody();
    // return redirect()->to('/emails');
  /*   $messages = LaravelGmail::message()->subject('test')->unread()->preload()->take(1)->all();
foreach ( $messages as $message ) {
    $body = $message->getHtmlBody();
    $subject = $message->getSubject();
    dump($message);
} */
});

Route::get('/oauth/gmail/logout', function () {
    LaravelGmail::logout(); //It returns exception if fails
    return redirect()->to('/');
});
Route::get('/emails', function () {
    $messages = LaravelGmail::message()->unread()->preload()->take(2)->all();
    // $message = LaravelGmail::message()->preload()->get('17598dc19ba3bba7');
    $message2 = LaravelGmail::message()->in('INBOX')->preload()->take(2)->all();
  
    dump($message2);
    foreach ($message2 as $message) {
        $body = $message->getHtmlBody();
        $subject = $message->getSubject();
        $user = $message->getUser();
        
        echo($body);
    }
});

Route::get('/sendemail', function () {
    $to = 'reciver@gmail.com' ;
    $from = 'sender@gmail.com' ;
    //$bcc = 'bbc@gmail.com';
    $message = "<h2>laravel test </h2><p>test message from laravel gmail test app</p>";
    $mail = new Mail;
    $mail->to($to, $name = null);
    $mail->from($from, $name = 'test laravel');
    //$mail->bcc( $bcc, $name = null );

    $mail->message('test message from laravel gmail test app');
    $mail->send();
});