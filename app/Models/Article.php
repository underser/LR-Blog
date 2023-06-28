<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'full_text'
    ];

    /**
     * Store image files into the filesystem.
     *
     * @param UploadedFile $file
     * @param array $types
     *
     * @return void
     */
    public function storeImage(UploadedFile $file, array $types = ['main', 'thumbnail']): void
    {
        /** @TODO Remove old image if exists */
        if (in_array('main', $types)) {
             $this->image = $file->storePubliclyAs(
                'article/' . $this->id . '/images/main',
                $file->hashName(),
                'public'
            );
        }

        if (in_array('thumbnail', $types)) {
            Storage::disk('public')->makeDirectory('article/' . $this->id . '/images/thumbnail');
            Image::make($file)->resize(32, 32)->save(
                storage_path('app/public/article/' . $this->id . '/images/thumbnail/') . $file->hashName()
            );
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
