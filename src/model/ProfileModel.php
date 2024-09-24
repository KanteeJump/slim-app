<?php

declare(strict_types=1);

namespace app\model;

use Illuminate\Database\Eloquent\Model;

class ProfileModel extends Model
{
    protected $table = "usuarios";

    protected $fillable = ["nombre", "email", "password"];

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes["password"] = password_hash($value, PASSWORD_DEFAULT);
    }
}
