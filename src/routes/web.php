
<?php
Route::group(['namespace' => 'Rockers\PlaidStripe\Http\Controllers', 'middleware' => ['web']], function(){
    Route::get('pay', 'ContactFormController@index');
    Route::post('success', 'ContactFormController@sendMail');
});
