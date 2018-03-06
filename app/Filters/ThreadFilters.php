<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular'];

    /**
     * Filter the query by a given username
     *
     * @param string $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        $this->builder->where('user_id', $user->id)->latest();
    }

    /**
     * Filter the query according to most popular threads.
     *
     * @return mixed
     */
    public function popular()
    {
        $this->builder->getQuery()->orders = [];
        $this->builder->orderBy('replies_count', 'desc');
    }
}
