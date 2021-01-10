<?php
declare(strict_types = 1);

namespace Innmind\Json;

use Innmind\Json\Exception\{
    MaximumDepthExceeded,
    StateMismatch,
    CharacterControlError,
    SyntaxError,
    MalformedUTF8,
    RecursiveReference,
    InfiniteOrNanCannotBeEncoded,
    ValueCannotBeEncoded,
    PropertyCannotBeEncoded,
    MalformedUTF16,
};

final class Json
{
    private function __construct()
    {
    }

    /**
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
     * @param mixed $content
     *
     * @throws \Exception
     */
    public static function encode($content, int $options = 0, int $depth = 512): string
    {
        try {
            return \json_encode(
                $content,
                $options | \JSON_THROW_ON_ERROR,
                $depth
            );
        } catch (\JsonException $e) {
            throw self::wrap($e);
        }
    }

    private static function wrap(\JsonException $e): \Exception
    {
        switch ($e->getCode()) {
            case \JSON_ERROR_DEPTH:
                return new MaximumDepthExceeded(
                    $e->getMessage(),
                    0,
                    $e
                );

            case \JSON_ERROR_STATE_MISMATCH:
                return new StateMismatch(
                    $e->getMessage(),
                    0,
                    $e
                );

            case \JSON_ERROR_CTRL_CHAR:
                return new CharacterControlError(
                    $e->getMessage(),
                    0,
                    $e
                );

            case \JSON_ERROR_SYNTAX:
                return new SyntaxError(
                    $e->getMessage(),
                    0,
                    $e
                );

            case \JSON_ERROR_UTF8:
                return new MalformedUTF8(
                    $e->getMessage(),
                    0,
                    $e
                );

            case \JSON_ERROR_RECURSION:
                return new RecursiveReference(
                    $e->getMessage(),
                    0,
                    $e
                );

            case \JSON_ERROR_INF_OR_NAN:
                return new InfiniteOrNanCannotBeEncoded(
                    $e->getMessage(),
                    0,
                    $e
                );

            case \JSON_ERROR_UNSUPPORTED_TYPE:
                return new ValueCannotBeEncoded(
                    $e->getMessage(),
                    0,
                    $e
                );

            case \JSON_ERROR_INVALID_PROPERTY_NAME:
                return new PropertyCannotBeEncoded(
                    $e->getMessage(),
                    0,
                    $e
                );

            case \JSON_ERROR_UTF16:
                return new MalformedUTF16(
                    $e->getMessage(),
                    0,
                    $e
                );

            default:
                return $e;
        }
    }
}
