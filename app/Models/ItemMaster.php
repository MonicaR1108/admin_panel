<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ItemMaster extends Model
{
    protected $table = 'item_master';
    protected $primaryKey = 'ID';

    public $timestamps = false;

    protected $fillable = [
        'ItemName',
        'Description',
        'Status',
        'Category',
        'Price',
        'Created_by',
        'Created_on',
        'updated_by',
        'updated_on',
    ];

    public static function idToToken(int $id): string
    {
        $encrypted = Crypt::encryptString((string) $id);

        return rtrim(strtr($encrypted, '+/', '-_'), '=');
    }

    public static function tokenToId(string $token): int
    {
        $encrypted = strtr($token, '-_', '+/');
        $pad = strlen($encrypted) % 4;
        if ($pad) {
            $encrypted .= str_repeat('=', 4 - $pad);
        }

        $id = Crypt::decryptString($encrypted);

        if (! is_numeric($id)) {
            throw new \RuntimeException('Invalid token.');
        }

        return (int) $id;
    }
}
