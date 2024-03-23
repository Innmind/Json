<?php
declare(strict_types = 1);

namespace Innmind\Json;

use Innmind\Json\Exception\{
    MaximumDepthExceeded,
    StateMismatch,
    CharacterControlError,
    Exception,
    SyntaxError,
    MalformedUTF8,
    RecursiveReference,
    InfiniteOrNanCannotBeEncoded,
    ValueCannotBeEncoded,
    PropertyCannotBeEncoded,
    MalformedUTF16,
};
use Innmind\Immutable\Maybe;

final class Json
{
    private function __construct()
    {
    }

    /**
     * @psalm-pure
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public static function decode(string $string)
    {
        try {
            return \json_decode(
                $string,
                true,
                512,
                \JSON_THROW_ON_ERROR,
            );
        } catch (\JsonException $e) {
            throw self::wrap($e);
        }
    }

    /**
     * @psalm-pure
     *
     * @return Maybe<mixed>
     */
    public static function maybeDecode(string $string): Maybe
    {
        try {
            return Maybe::just(self::decode($string));
        } catch (Exception $e) {
            return Maybe::nothing();
        }
    }

    /**
     * @psalm-pure
     *
     * @param mixed $content
     * @param int<1, 2147483647> $depth
     *
     * @throws \Exception
     */
    public static function encode($content, int $options = 0, int $depth = 512): string
    {
        try {
            return \json_encode(
                $content,
                $options | \JSON_THROW_ON_ERROR,
                $depth,
            );
        } catch (\JsonException $e) {
            throw self::wrap($e);
        }
    }

    /**
     * @psalm-pure
     */
    private static function wrap(\JsonException $e): \Exception
    {
        return match ($e->getCode()) {
            \JSON_ERROR_DEPTH => new MaximumDepthExceeded(
                $e->getMessage(),
                0,
                $e,
            ),
            \JSON_ERROR_STATE_MISMATCH => new StateMismatch(
                $e->getMessage(),
                0,
                $e,
            ),
            \JSON_ERROR_CTRL_CHAR => new CharacterControlError(
                $e->getMessage(),
                0,
                $e,
            ),
            \JSON_ERROR_SYNTAX => new SyntaxError(
                $e->getMessage(),
                0,
                $e,
            ),
            \JSON_ERROR_UTF8 => new MalformedUTF8(
                $e->getMessage(),
                0,
                $e,
            ),
            \JSON_ERROR_RECURSION => new RecursiveReference(
                $e->getMessage(),
                0,
                $e,
            ),
            \JSON_ERROR_INF_OR_NAN => new InfiniteOrNanCannotBeEncoded(
                $e->getMessage(),
                0,
                $e,
            ),
            \JSON_ERROR_UNSUPPORTED_TYPE => new ValueCannotBeEncoded(
                $e->getMessage(),
                0,
                $e,
            ),
            \JSON_ERROR_INVALID_PROPERTY_NAME => new PropertyCannotBeEncoded(
                $e->getMessage(),
                0,
                $e,
            ),
            \JSON_ERROR_UTF16 => new MalformedUTF16(
                $e->getMessage(),
                0,
                $e,
            ),
            default => $e,
        };
    }
}
