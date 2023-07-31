<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'name',
        'profile_pic',
        'logged_in',
        'is_active',
    ];

    // Generate and assign UUID for new users
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = Uuid::uuid4();
        });
    }
    public function coins()
    {
        return $this->hasOne(Coin::class, 'user_id', 'user_id');
    }

    public static function signInWithGoogle($userData)
    {
        $user = static::updateOrCreate(
            ['email' => $userData['email']],
            $userData
        );

        $coin = $user->coins;

        if (!$coin) {
            Coin::create([
                'user_id' => $user->user_id,
                'amount' => 5,
            ]);
        }

        return $user;
    }
}
