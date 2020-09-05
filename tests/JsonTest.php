<?php
declare(strict_types = 1);

namespace Tests\Innmind\Json;

use Innmind\Json\{
    Json,
    Exception\SyntaxError,
    Exception\MaximumDepthExceeded,
    Exception\StateMismatch,
    Exception\CharacterControlError,
    Exception\MalformedUTF8,
    Exception\MalformedUTF16,
    Exception\RecursiveReference,
    Exception\InfiniteOrNanCannotBeEncoded,
    Exception\ValueCannotBeEncoded,
};
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    public function testEncode()
    {
        $json = Json::encode(['foo' => 'bar']);

        $this->assertSame('{"foo":"bar"}', $json);
    }

    public function testEncodeWithOption()
    {
        $json = Json::encode(['foo' => 'bar'], JSON_PRETTY_PRINT);

        $expectedJson = <<<'JSON'
{
    "foo": "bar"
}
JSON;
        $this->assertSame($expectedJson, $json);
    }

    public function testEncodeWithMaxDepth()
    {
        $json = Json::encode(['foo' => 'bar'], 0, 1);

        $this->assertSame('{"foo":"bar"}', $json);
    }

    public function testThrowWhenExceedingSpecifiedMaxDepth()
    {
        $this->expectException(MaximumDepthExceeded::class);

        Json::encode(['foo' => ['bar' => 'baz']], 0, 1);
    }

    public function testDecode()
    {
        $content = Json::decode('{"foo":"bar"}');

        $this->assertSame(['foo' => 'bar'], $content);
    }

    public function testThrowOnSyntaxError()
    {
        $this->expectException(SyntaxError::class);

        Json::decode('{"foo"');
    }

    public function testThrowOnMaximumDepthExceeded()
    {
        $this->expectException(MaximumDepthExceeded::class);
        $depth = 0;

        $array = ($deepen = function(array $array) use (&$deepen, &$depth): array {
            if ($depth >= 512) {
                return $array;
            }

            $depth++;

            return $deepen(['foo' => $array]);
        })(['foo' => 'bar']);

        Json::encode($array);
    }

    public function testThrowOnStateMismatch()
    {
        $this->expectException(StateMismatch::class);

        Json::decode('{"foo":"bar"]');
    }

    public function testThrowOnMalformedUTF8OrCharacterControlError()
    {
        try {
            Json::decode('{"foo":"'.random_bytes(42).'"}');
            $this->fail('it should throw');
        } catch (MalformedUTF8 | CharacterControlError $e) {
            $this->assertTrue(true);
        }
    }

    public function testThrowOnCharacterControlError()
    {
        $this->expectException(CharacterControlError::class);

        Json::decode(chr(23));
    }

    public function testThrowOnMalformedUTF16()
    {
        $this->expectException(MalformedUTF16::class);

        Json::decode('["\ude00\ud83d"]');
    }

    public function testThrowOnRecursiveReference()
    {
        $this->expectException(RecursiveReference::class);
        $array = ['foo' => 'bar', 'bar' => null];
        $array['bar'] = &$array;

        Json::encode($array);
    }

    public function testThrowOnInfiniteOrNanCannotBeEncoded()
    {
        $this->expectException(InfiniteOrNanCannotBeEncoded::class);

        Json::encode(['foo' => INF]);
    }

    public function testThrowOnValueCannotBeEncoded()
    {
        $this->expectException(ValueCannotBeEncoded::class);

        Json::encode(['foo' => tmpfile()]);
    }
}
