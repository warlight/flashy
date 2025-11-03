<?php

namespace App\Services;

use App\Models\Link;

class SlugGeneratorService
{
    protected const POSSIBLE_SYMBOLS = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_";
    protected const SLUG_LENGTH = 40;

    public static function generateUniqueSlug(): string
    {
        do {
            $slug = self::generateSlugString();
        } while (!self::checkSlugIsUnique($slug));
        return $slug;
    }

    protected static function generateSlugString(): string
    {
        $slug = "";

        while (strlen($slug) < self::SLUG_LENGTH) {
            $symbolIndex = rand(0, (strlen(self::POSSIBLE_SYMBOLS) - 1));
            $slug .= self::POSSIBLE_SYMBOLS[$symbolIndex];
        }

        return $slug;
    }

    public static function checkSlugIsUnique(string $slug): bool
    {
        return Link::where('slug', $slug)->doesntExist();
    }
}
