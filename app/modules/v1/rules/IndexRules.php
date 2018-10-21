<?php

$rules['index'] = [
        '_method' => 'get',
        '_params' => ['id','name'],
        'id' => [
            'required' => 1,
            'int' => 1,
            'filters' => 'intval',
            'msg' => '参数错误{id}'
        ],
        'name' => [
            'required' => 1,
            'date'=>1,
            'filters' => 'trim',
            'msg' => '参数错误{name}'
        ]
];

return $rules;
