<?php

$rules['index'] = array(
        '_method' => array(
             'get' => array('id','ame'),
        ),
        'id' => array(
            'required' => 1,
            'filters' => 'int',
            'msg' => '参数错误'
        ),
        'name' => array(
            'required' => 1,
            'filters' => 'trim',
            'msg' => '参数错误'
        )
);

$rules['test'] = array(
    '_method' => array(
        'post' => array('id','nick'),
    ),
    'nick' => array(
        'required' => 1,
        'length' => [1, 20],
        'msg' => '宝贝昵称输入错误'
    ),

    'id' => array(
        'required' => 1,
        'filters' => 'int',
        'msg' => '参数错误'
    ),   
);

return $rules;
