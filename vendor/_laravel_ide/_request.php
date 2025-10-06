<?php

namespace Illuminate\Http;

interface Request
{
    /**
     * @return \App\Models\user|null
     */
    public function user($guard = null);
}