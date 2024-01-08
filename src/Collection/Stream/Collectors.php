<?php

declare(strict_types=1);

namespace Munus\Collection\Stream;

use Munus\Collection\GenericList;
use Munus\Collection\Map;
use Munus\Collection\Set;
use Munus\Collection\Stream\Collector\GenericCollector;
use Munus\Tuple;
use Munus\Tuple\Tuple2;

final class Collectors
{
    /**
     * @template T
     *
     * @return Collector<T,GenericList>
     */
    public static function toList(): Collector
    {
        return GenericCollector::of(GenericList::empty(), /** @param T $value */ function (GenericList $list, $value): GenericList {
            return $list->append($value);
        });
    }

    /**
     * @template T
     *
     * @return Collector<T,Set>
     */
    public static function toSet(): Collector
    {
        return GenericCollector::of(Set::empty(), /** @param T $value */ function (Set $set, $value): Set {
            return $set->add($value);
        });
    }

    /**
     * @template T
     *
     * @param callable(T):string $keyProvider
     *
     * @return Collector<T,Map>
     */
    public static function toMap(callable $keyProvider): Collector
    {
        return GenericCollector::of(Map::empty(), /** @param T $value */ function (Map $map, $value) use ($keyProvider): Map {
            return $map->put($keyProvider($value), $value);
        });
    }

    /**
     * @template T
     *
     * @return Collector<T,int|float>
     */
    public static function summing(): Collector
    {
        return GenericCollector::of(0,
            /**
             * @param int|float $sum
             * @param T         $value
             *
             * @return int|float
             */
            function ($sum, $value) {
                if (!is_numeric($value)) {
                    throw new \InvalidArgumentException(sprintf('Could not convert %s to number', $value));
                }

                return $sum + $value;
            }
        );
    }

    /**
     * @template T
     *
     * @return Collector<T,string>
     */
    public static function joining(string $glue = ''): Collector
    {
        return new GenericCollector('', /** @param T $value */ function (string $text, $value) use ($glue): string {
            return $text.$glue.(string) $value;
        }, function (string $text) use ($glue): string {
            return ltrim($text, $glue);
        });
    }

    /**
     * @template T
     *
     * @return Collector<T,int>
     */
    public static function counting(): Collector
    {
        return GenericCollector::of(0, /** @param T $value */ function (int $count, $value): int {return ++$count; });
    }

    /**
     * @template T
     *
     * @return Collector<T,int|float>
     */
    public static function averaging(): Collector
    {
        return new GenericCollector(Tuple::of(0, 0), /** @param T $value */ function (Tuple2 $acc, $value): Tuple2 {
            if (!is_numeric($value)) {
                throw new \InvalidArgumentException(sprintf('Could not convert %s to number', (string) $value));
            }

            return Tuple::of($acc[0] + $value, $acc[1] + 1);
        }, /** @return int|float */ function (Tuple2 $acc) {
            if ($acc[1] === 0) {
                return 0;
            }

            return $acc[0] / $acc[1];
        });
    }
}
