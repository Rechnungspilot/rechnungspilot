<?php

    Route::get('raw/artikel', 'Items\ItemController@raw');
    Route::get('raw/aufgaben', 'Todos\TodoController@raw');
    Route::get('raw/auftraege', 'OrderController@raw');
    Route::get('raw/kontakte', 'Contacts\ContactController@raw');

?>