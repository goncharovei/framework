<?php

namespace Foundation\Database;

class QueryBuilder
{
    public function __invoke(): DB
    {
        $db = new DB;
        $db->addConnection($this->getConfig());

        return $db;
    }

    private function getConfig(): array
    {
        $connectionDriver = config('database.default');
        return config('database.connections.' . $connectionDriver);
    }
}