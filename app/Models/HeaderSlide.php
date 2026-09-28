<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeaderSlide extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'cta_label',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Link tujuan slide, sudah dinormalisasi (https:// ditambahkan bila skema
     * hilang). Aturannya sama persis dengan Post supaya tidak ada dua
     * definisi "link yang aman" di project ini.
     */
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

    public function readUrl(): string
    {
        return $this->externalLink() ?? '#';
    }

    public function buttonLabel(): string
    {
        $label = trim((string) $this->cta_label);

        return $label === '' ? 'Pelajari Lebih' : $label;
    }

    /** Ringsan singkat untuk slide banner; HTML dibuang supaya tidak bocor ke markup. */
    public function excerpt(int $limit = 150): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $this->description)) ?? '');

        return \Illuminate\Support\Str::limit($text, $limit);
    }
}
