<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'subcategory', 'folder_guid'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function getPrettyFolderAttribute(): string
    {
        $guid = (string) ($this->folder_guid ?? '');
        if ($guid === '') {
            return 'Onbekend';
        }
        // Verwijder het eerste segment (bijv. 'sociaalwerk-') als er een '-'
        $rest = $guid;
        if (str_contains($guid, '-')) {
            $parts = explode('-', $guid);
            $rest = implode('-', array_slice($parts, 1));
        }
        $rest = str_replace(['-', '_'], ' ', $rest);
        return ucwords($rest);
    }
}
