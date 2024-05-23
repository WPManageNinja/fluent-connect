<?php

namespace FluentConnect\Framework\Support;

use FluentConnect\Framework\Foundation\App;

class Path
{
    /**
     * Get WordPress dir path.
     * 
     * @return string Abs WP dir path
     */
    public static function wp()
    {
        return static::resolve(App::make('path') . '../../..');
    }

    /**
     * Get WordPress dir path.
     * 
     * @return string Abs WP dir path
     */
    public static function plugin()
    {
        return App::make()['path'];
    }

    /**
     * Get plugin dir path.
     *
     * @param string $segment folder name (i.e: app.Models)
     * @param bool $isFile whether the segment is for filename.
     * @return string Abs plugin dir path
     */
    public static function of($segment, $isFile = false)
    {
        $app = App::make();

        if (isset($app['path.' . $segment])) {
            $path = $app['path.' . $segment];
        } else {
            $ds = DIRECTORY_SEPARATOR;

            if (str_contains($segment, '.')) {
                if (!str_contains($segment, $ds)) {
                    $segment = str_replace('.', $ds, $segment);
                }
            }

            $path = static::implode(
                $app['path'], ...explode($ds, $segment)
            );

            // If not directory exists then check if the path was
            // given for a filename so let's replace the last 
            // seperator with a dot to make the last part
            // a file name and check if file exists.
            if ($isFile || !file_exists($path)) {
                if (
                    file_exists($filePath = substr_replace(
                        $path, '.', strrpos($path, $ds), 1
                    ))
                ) return $filePath;
            }
        }

        return $path;
    }

    /**
     * Determines whether the given path is an absolute path.
     *
     * @param string $path The path to check.
     * @return bool True if the path is absolute, false otherwise.
     */
    public static function isAbsolute($path)
    {
        return path_is_absolute($path);
    }

    /**
     * Determines whether the given path is an relative path.
     *
     * @param string $path The path to check.
     * @return bool True if the path is relative, false otherwise.
     */
    public static function isRelative($path)
    {
        return !path_is_absolute($path);
    }
    /**
     * Joins two filesystem paths together.
     *
     * For example, 'give me $path relative to $base'.
     * If the $path is absolute, then the
     * full path will be returned.
     *
     * @param string $base Base path.
     * @param string $path Path relative to $base.
     * @return string The path with the base or absolute path.
     */
    public static function join($base, $path)
    {
        return path_join($base, $path);
    }

    /**
     * Normalizes a filesystem path.
     *
     * On windows systems, replaces backslashes with forward slashes
     * and forces upper-case drive letters.
     * Allows for two leading slashes for Windows network shares, but
     * ensures that all other duplicate slashes are reduced to a single.
     *
     * @param string $path Path to normalize.
     * @return string Normalized path.
     */
    public static function normalize($path)
    {
        return wp_normalize_path($path);
    }

    /**
     * Implodes path segments using the appropriate directory separator.
     * 
     * @param  string[] $paths
     * @return string
     */
    public static function implode(...$paths)
    {
        $firstSegment = array_shift($paths);

        $allSegments = implode(DIRECTORY_SEPARATOR, array_map(function($path) {
            return trim($path, DIRECTORY_SEPARATOR);
        }, $paths));

        return implode(DIRECTORY_SEPARATOR, [
            rtrim($firstSegment, DIRECTORY_SEPARATOR), $allSegments
        ]);
    }

    /**
     * Resolves an absolute path from one or more path segments.
     *
     * @param string[] $paths Path segments to resolve.
     * @return string The resolved absolute path.
     */
    public static function resolve(...$paths)
    {
        return realpath(static::implode(...$paths));
    }

    /**
     * Returns the directory name of a path.
     *
     * @param string $path The path.
     * @return string The directory name.
     */
    public static function dirname($path)
    {
        return dirname($path);
    }

    /**
     * Returns the last portion of a path, optionally removing a provided extension.
     *
     * @param string $path The path.
     * @param string|null $suffix Optional extension to remove.
     * @return string The last portion of the path.
     */
    public static function basename($path, $suffix = null)
    {
        $baseName = basename($path);

        return $suffix ? rtrim($baseName, $suffix) : $baseName;
    }

    /**
     * Returns the extension of the path.
     *
     * @param string $path The path.
     * @return string The extension.
     */
    public static function extname($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Finds the closest dir/file from upward.
     * 
     * @param  string $path
     * @return string|null
     */
    public static function closest($path)
    {
        $count = 0;

        $cwd = dirname(debug_backtrace(false, 1)[0]['file']) . '/../';

        while (!file_exists($target = $cwd . $path)) {

            $cwd .= '../';
            
            if (($count++) > 25) break;
        }

        return (string) realpath($target);
    }

    /**
     * Breaks down a path into an an array like:
     * root, dir, base, ext, and name.
     *
     * @param string $path The path to parse.
     * @return array An associative array with path information.
     */
    public static function parse($path)
    {
        $info = pathinfo($path);

        return [
            'dir' => isset($info['dirname']) ? $info['dirname'] : null,
            'root' => isset($info['dirname']) ? $info['dirname'] : null,
            'base' => isset($info['basename']) ? $info['basename'] : null,
            'name' => isset($info['filename']) ? $info['filename'] : null,
            'ext' => isset($info['extension']) ? '.' . $info['extension'] : null,
        ];
    }
}

