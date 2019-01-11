<?php

namespace CupOfTea\Support;

use CupOfTea\Package\Package;
use CupOfTea\Support\Exception\JsonDecodeException;
use CupOfTea\Support\Exception\JsonEncodeException;
use CupOfTea\Package\Contracts\Package as PackageContract;

class Json implements PackageContract
{
    use Package;
    
    /**
     * Package Vendor.
     *
     * @const string
     */
    const VENDOR = 'CupOfTea';
    
    /**
     * Package Name.
     *
     * @const string
     */
    const PACKAGE = 'Json';
    
    /**
     * Package Version.
     *
     * @const string
     */
    const VERSION = '0.0.0';
    
    /**
     * Default options for encoding JSON.
     * 
     * @var int
     */
    protected static $encodeOptions = 0;
    
    /**
     * Default options for decoding JSON.
     * 
     * @var int
     */
    protected static $decodeOptions = 0;
    
    /**
     * Set the default options for encoding JSON.
     * 
     * @param  int  $options
     * @return void
     */
    public static function encodeOptions($options = 0)
    {
        static::$encodeOptions = $options;
    }
    
    /**
     * Encode JSON.
     * 
     * @param  mixed  $data
     * @param  int    $options
     * @param  int    $depth
     * @return string
     * @throws \CupOfTea\ApiLib\Exception\JsonEncodeException when an error occurs during the encoding
     */
    public static function encode($data, $options = -1, $depth = 512)
    {
        $result = json_encode($data, $options === -1 ? static::$encodeOptions : $options, $depth);
        
        if ($e = json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonEncodeException(json_last_error_msg());
        }
        
        return $result;
    }
    
    /**
     * Set the default options for decoding JSON.
     * 
     * @param  int  $options
     * @return void
     */
    public static function decodeOptions($options = 0)
    {
        static::$decodeOptions = $options;
    }
    
    /**
     * Decode JSON.
     * 
     * @param  string $json
     * @param  bool   $assoc
     * @param  int    $depth
     * @param  int    $options
     * @return mixed
     * @throws \CupOfTea\ApiLib\Exception\JsonDecodeException when an error occurs during the decoding
     */
    public static function decode($json, $assoc = false, $depth = 512, $options = -1)
    {
        $result = json_decode($json, $assoc, $depth, $options === -1 ? static::$decodeOptions : $options);
        
        if ($e = json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonDecodeException(json_last_error_msg());
        }
        
        return $result;
    }
}
