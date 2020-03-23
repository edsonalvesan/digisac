<?php

namespace EdsonAlvesan\DigiSac\Objects;

class UserProfilePhotos extends BaseObject
{
    /**
     * {@inheritdoc}
     */
    public function relations()
    {
        return [
            'photos' => PhotoSize::class,
        ];
    }
}
