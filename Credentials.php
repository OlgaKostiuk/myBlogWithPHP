<?php

class Credentials
{
    public static function getLogin(): string
    {
        return "admin";
    }

    public static function getPassword(): string
    {
        return password_hash("qwerty", PASSWORD_DEFAULT);
    }
}