<?php

    return [

        'host' => [

            'local' => [
                'root' => 'http://topic.com',
                'cdn' => 'http://cdn.topic.com',
            ],

            'online' => [
                'root' => 'http://topicbus.cn',
                'cdn' => 'http://cdn.topicbus.cn',
            ],
        ],

        'MailService' => 'http://cuilongyun.win:8088',

        'zh' => [
            'course' => '课程',
        ],

        'view' => [
            'front' => [
                'template' => 'online',
                'index' => 'vipp',
                'list' => 'vipp',
                'detail' => 'vipp'
            ],
        ],

        'website' => [
            'front' => [
                'prefix' => 'org'
            ],
        ],

        'common' => [
            'module' => [
                0 => 'default',
                1 => 'product',
                2 => 'article',
                3 => 'activity',
                4 => 'survey',
                5 => 'slide',
            ],

            'sort' => [
                0 => 'default',
                1 => 'product',
                2 => 'article',
                3 => 'activity',
                4 => 'survey',
                5 => 'slide',
            ],
        ],


    ];
