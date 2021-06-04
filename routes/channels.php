<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//private(two parameter: first->channel name,  second->check receptable user(true or false))
//Broadcast::channel('App.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

//presence(channel and user)
Broadcast::channel('notification-channel', function ($user) {
    return $user;
});

Broadcast::channel('kitchen-channel', function ($user) {
    return $user;
});

Broadcast::channel('pay-channel', function ($user) {
    return $user;
});
Broadcast::channel('attend-channel', function ($user) {
    return $user;
});
Broadcast::channel('changecount-channel', function ($user) {
    return $user;
});