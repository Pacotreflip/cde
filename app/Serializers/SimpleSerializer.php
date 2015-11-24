<?php

namespace Ghi\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class SimpleSerializer extends ArraySerializer
{
    /**
     * [collection description]
     *
     * @param string $resourceKey
     * @param  array $data
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return $data;
    }
}
