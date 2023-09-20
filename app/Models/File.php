<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'path',
        'user_id',
        'name',
        'type',
        'size',
        'imported'
    ];

    use HasFactory;

    /**
     * Set file to imported.
     *
     * @return void
     */
    public function setToImported(): void
    {
        $this->imported = true;
        $this->save();
    }

    /**
     * Return the status of the file.
     *
     * @return bool
     */
    public function isImported(): bool
    {
        return $this->imported;
    }
}