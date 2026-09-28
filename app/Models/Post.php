<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'link', 'status', 'is_featured', 'user_id'];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /** Link blog tujuan, sudah dinormalisasi (https:// ditambahkan bila skema hilang). */
    public function externalLink(): ?string
    {
        $link = trim((string) $this->link);

        if ($link === '') {
            return null;
        }

        // Skema selain http/https (mis. javascript:) dibuang agar tidak jadi tautan yang dieksekusi.
        if (preg_match('#^[a-z][a-z0-9+.\-]*:#i', $link) === 1) {
            return preg_match('#^https?://#i', $link) === 1 ? $link : null;
        }

        return 'https://' . ltrim($link, '/');
    }

    public function hasExternalLink(): bool
    {
        return $this->externalLink() !== null;
    }

    /** Tujuan klik "Baca": link blog bila ada, jika tidak halaman detail internal. */
    public function readUrl(): string
    {
        return $this->externalLink() ?? route('posts.show', $this);
    }
}
