<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use InvalidArgumentException;

/**
 * The code inside this class has been copied over from the `symfony/http-foundation` package.
 *
 * @author Christian Schmidt <github@chsc.dk>
 */
final class HeaderUtils
{
    public const DISPOSITION_ATTACHMENT = 'attachment';
    public const DISPOSITION_INLINE = 'inline';

    /**
     * This class should not be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * Generates an HTTP Content-Disposition field-value.
     *
     * @param string $disposition      One of "inline" or "attachment"
     * @param string $filename         A unicode string
     * @param string $filenameFallback A string containing only ASCII characters that
     *                                 is semantically equivalent to $filename. If the filename is already ASCII,
     *                                 it can be omitted, or just copied from $filename
     *
     * @throws InvalidArgumentException
     *
     * @see RFC 6266
     */
    public static function makeDisposition(string $disposition, string $filename, string $filenameFallback = ''): string
    {
        if (!\in_array($disposition, [self::DISPOSITION_ATTACHMENT, self::DISPOSITION_INLINE])) {
            throw new InvalidArgumentException(
                sprintf(
                    'The disposition must be either "%s" or "%s".',
                    self::DISPOSITION_ATTACHMENT,
                    self::DISPOSITION_INLINE
                )
            );
        }

        if ('' === $filenameFallback) {
            $filenameFallback = $filename;
        }

        // filenameFallback is not ASCII.
        if (!preg_match('/^[\x20-\x7e]*$/', $filenameFallback)) {
            throw new InvalidArgumentException('The filename fallback must only contain ASCII characters.');
        }

        // percent characters aren't safe in fallback.
        if (str_contains($filenameFallback, '%')) {
            throw new InvalidArgumentException('The filename fallback cannot contain the "%" character.');
        }

        // path separators aren't allowed in either.
        if (
            str_contains($filename, '/')
            || str_contains($filename, '\\')
            || str_contains($filenameFallback, '/')
            || str_contains($filenameFallback, '\\')
        ) {
            throw new InvalidArgumentException(
                'The filename and the fallback cannot contain the "/" and "\\" characters.'
            );
        }

        $params = ['filename' => $filenameFallback];
        if ($filename !== $filenameFallback) {
            $params['filename*'] = "utf-8''" . rawurlencode($filename);
        }

        return $disposition . '; ' . self::toString($params, ';');
    }

    /**
     * Joins an associative array into a string for use in an HTTP header.
     *
     * The key and value of each entry are joined with "=", and all entries
     * are joined with the specified separator and an additional space (for
     * readability). Values are quoted if necessary.
     *
     * Example:
     *
     *     HeaderUtils::toString(["foo" => "abc", "bar" => true, "baz" => "a b c"], ",")
     *     // => 'foo=abc, bar, baz="a b c"'
     *
     * @param array<string, string|true> $assoc
     */
    public static function toString(array $assoc, string $separator): string
    {
        $parts = [];
        foreach ($assoc as $name => $value) {
            if (true === $value) {
                $parts[] = $name;
            } else {
                $parts[] = $name . '=' . self::quote($value);
            }
        }

        return implode($separator . ' ', $parts);
    }

    /**
     * Encodes a string as a quoted string, if necessary.
     *
     * If a string contains characters not allowed by the "token" construct in
     * the HTTP specification, it is backslash-escaped and enclosed in quotes
     * to match the "quoted-string" construct.
     */
    public static function quote(string $s): string
    {
        if (preg_match('/^[a-z0-9!#$%&\'*.^_`|~-]+$/i', $s)) {
            return $s;
        }

        return '"' . addcslashes($s, '"\\"') . '"';
    }
}
