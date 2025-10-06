<?php

namespace Illuminate\Support\Facades;

interface Auth
{
    /**
     * @return \App\Models\user|false
     */
    public static function loginUsingId(mixed $id, bool $remember = false);

    /**
     * @return \App\Models\user|false
     */
    public static function onceUsingId(mixed $id);

    /**
     * @return \App\Models\user|null
     */
    public static function getUser();

    /**
     * @return \App\Models\user
     */
    public static function authenticate();

    /**
     * @return \App\Models\user|null
     */
    public static function user();

    /**
     * @return \App\Models\user|null
     */
    public static function logoutOtherDevices(string $password);

    /**
     * @return \App\Models\user
     */
    public static function getLastAttempted();
}