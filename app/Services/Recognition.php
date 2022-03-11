<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\Pure;

class Recognition
{
    #[Pure] public static function recognize(UploadedFile $image): bool
    {
        //todo: integrate with the recognition service from the AI engineer
        if ($image->getClientOriginalName() == "invalid_image.jpg") {
            return false;
        }

        return true;
    }
}
