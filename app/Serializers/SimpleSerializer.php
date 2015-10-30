<?php

namespace Ghi\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class SimpleSerializer extends ArraySerializer
{
    /**
     * [collection description]
     * @param  [type] $resourceKey [description]
     * @param  array  $data        [description]
     * @return [type]              [description]
     */
    public function collection($resourceKey, array $data)
    {
        return $data;
    }
}
