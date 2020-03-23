<?php

namespace EdsonAlvesan\DigiSac\Objects;


class CallbackQuery extends BaseObject
{
    /**
     * @inheritdoc
     */
    public function relations()
    {
        return [
            'from' => User::class,
            'message' => Message::class,
        ];
    }
}
