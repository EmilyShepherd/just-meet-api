<?php

namespace JustMeet;

use Composer\Script\Event;

class DatabaseSetup
{
    public function rebuild(Event $event)
    {
        $path = __DIR__ . '/../app/Resources/database.sql';

        `cat $path | mysql -u root`;
    }
}
