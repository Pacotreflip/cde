<?php

namespace Ghi\Domain\Core\App;

use League\Fractal\Serializer\ArraySerializer;

class SimpleSerializer extends ArraySerializer
{
    /**
     * {@inheritdoc}
     */
    public function collection($resourceKey, array $data)
    {
        return $data;
    }
}
