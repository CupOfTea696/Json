<?php

use CupOfTea\Support\Json;
use PHPUnit\Framework\TestCase;
use Illuminate\Filesystem\Filesystem;
use CupOfTea\Support\Exception\JsonDecodeException;
use CupOfTea\Support\Exception\JsonEncodeException;

class JsonTest extends TestCase
{
    protected $file;
    
    protected function setUp()
    {
        $this->file = new Filesystem;
    }
    
    public function testCanEncodeData()
    {
        $data = [
            'id' => 123,
            'name' => 'John Doe',
            'roles' => ['Admin', 'User'],
        ];
        
        $this->assertEquals('{"id":123,"name":"John Doe","roles":["Admin","User"]}', Json::encode($data));
    }
    
    public function testCanSetDefaultEncodeOptions()
    {
        Json::encodeOptions(JSON_PRETTY_PRINT);
        
        $data = [
            'id' => 123,
        ];
        
        $this->assertEquals("{\n    \"id\": 123\n}", Json::encode($data));
    }
    
    public function testCanOverrideDefaultEncodeOptions()
    {
        $data = [
            'uri' => 'http://example.com',
        ];
        
        $this->assertEquals("{\n    \"uri\": \"http:\/\/example.com\"\n}", Json::encode($data));
        $this->assertEquals('{"uri":"http://example.com"}', Json::encode($data, JSON_UNESCAPED_SLASHES));
    }
    
    public function testEncodeInvalidDataThrowsException()
    {
        $this->expectException(JsonEncodeException::class);
        
        Json::encode([
            'id' => INF,
        ]);
    }
    
    public function testCanDecodeJson()
    {
        $json = $this->file->get(__DIR__ . '/fixtures/data.json');
        $expected = (object) [
            'id' => 123,
            'name' => 'John Doe',
            'roles' => ['Admin', 'User'],
        ];
        
        $this->assertEquals($expected, Json::decode($json));
    }
    
    public function testCanDecodeJsonToAssoc()
    {
        $json = $this->file->get(__DIR__ . '/fixtures/data.json');
        $expected = [
            'id' => 123,
            'name' => 'John Doe',
            'roles' => ['Admin', 'User'],
        ];
        
        $this->assertEquals($expected, Json::decode($json, true));
    }
    
    public function testCanSetDefaultDecodeOptions()
    {
        Json::decodeOptions(JSON_BIGINT_AS_STRING);
        
        $json = '{"id":' . (PHP_INT_MAX - 1) . '}';
        
        $this->assertEquals((object) [
            'id' => (string) (PHP_INT_MAX - 1),
        ], Json::decode($json));
    }
    
    public function testCanOverrideDefaultDecodeOptions()
    {
        $json = '{"id":' . (PHP_INT_MAX - 1) . '}';
        
        $this->assertEquals([
            'id' => (PHP_INT_MAX - 1),
        ], Json::decode($json, null, 512, JSON_OBJECT_AS_ARRAY));
    }
    
    public function testDecodeInvalidSyntaxThrowsException()
    {
        $json = $this->file->get(__DIR__ . '/fixtures/invalidSyntax.json');
        
        $this->expectException(JsonDecodeException::class);
        $this->expectExceptionMessage('Syntax error');
        
        Json::decode($json);
    }
}
