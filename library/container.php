<?php 

try {
    //mysql
    $dbConfig = $this->config["db"]->toArray();
    if (!is_array($dbConfig["name"])) {
        $container->singleton('mysql', function (){
            return PdoMysql::getInstance($this->config["db"], $this->config["db"]["name"]);
        });  
    } else {
        foreach ( $dbConfig as $value) {
            $container->singleton('mysql_'.$value, function (){
                return PdoMysql::getInstance($this->config["db"], $value);
            });
        }
    }

    //redis
    // $redisConfig = $this->config["redis"]->toArray();
    // if (!is_array($redisConfig["db"])) {
    //     $container->singleton("redis", function (){
    //         return PdoMysql::getInstance($this->config["redis"], $this->config["redis"]["db"]);
    //     });  
    // } else {
    //     foreach ( $this->config["redis"]["db"] as $value) {
    //         $container->singleton('redis_'.$value, function() {
    //             return RedisDb::getInstance($this->config["redis"], $value);
    //         });
    //     }
    // }

    //helper
    $container->singleton('helper', function() {
        return new Helper();
    }); 
} catch(\Exception $e) {
    var_dump($e);exit;
}