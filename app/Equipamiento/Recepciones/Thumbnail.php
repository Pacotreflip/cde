<?php

namespace Ghi\Equipamiento\Recepciones;

use Intervention\Image\Facades\Image;

class Thumbnail
{
    public function make($origen, $destino)
    {
        return Image::make($origen)
            ->resize(200, 200)
            ->save($destino);
    }
}
