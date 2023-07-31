<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    use HasFactory;

    // Set the table name
    protected $table = 'api_keys';

    // Specify the fillable attributes to allow mass assignment
    protected $fillable = ['provider_name', 'api_key'];

    // Other model configurations or relationships can be defined here
}
