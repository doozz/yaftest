<?php
namespace Components\Utils;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

class Jwt {
    public static function make() {
       $token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
            ->setAudience('http://example.org') // Configures the audience (aud claim)
            ->setId(time(), true) // Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
            ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
            ->setExpiration(time() + 3600) // Configures the expiration time of the token (exp claim)
            ->set('uid', 1) // Configures a new claim, called "uid"
            ->getToken(); // Retrieves the generated token

        $token->getHeaders(); // Retrieves the token headers
        $token->getClaims(); // Retrieves the token claims

        //echo $token->getHeader('jti'); // will print "4f1g23a12aa"
        //echo $token->getClaim('iss'); // will print "http://example.com"
        //echo $token->getClaim('uid'); // will print "1"
        return $token; // The string representation of the object is a JWT string (pretty easy, right?)
    }
  
}