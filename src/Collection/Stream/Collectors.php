<?php

declare(strict_types=1);

namespace Munus\Collection\Stream;

use Munus\Collection\GenericList;
use Munus\Collection\Map;
use Munus\Collection\Set;
use Munus\Collection\Stream\Collector\GenericCollector;
use Munus\Tuple\Tuple2;

final class Collectors
{
    /**
     * @return Collector<mixed,GenericList>
     */
    public static function toList(): Collector
    {
        return GenericCollector::of(GenericList::empty(), function (GenericList $list, $value): GenericList {
            return $list->append($value);
        });
    }

    /**
     * @return Collector<mixed,Set>
     */
    public static function toSet(): Collector
    {
        return GenericCollector::of(Set::empty(), function (Set $set, $value): Set {
            return $set->add($value);
        });
    }

    /**
     * @template T
     * @template U
     *
     * @param callable(T):string $keyMapper
     * @param ?callable(T):U     $valueMapper
     *
     * @return Collector<T,Map>
     */
    public static function toMap(callable $keyMapper, ?callable $valueMapper = null): Collector
    {
        $valueMapper ??= fn ($value) => $value;

        return GenericCollector::of(Map::empty(), /** @param T $value */ function (Map $map, $value) use ($keyMapper, $valueMapper): Map {
            return $map->put($keyMapper($value), $valueMapper($value));
        });
    }

    /**
     * @return Collector<mixed,int|float>
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
     * @return Collector<mixed,string>
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
     * @return Collector<mixed,int>
     */
    public static function counting(): Collector
    {
        return GenericCollector::of(0, /** @param T $value */ function (int $count, $value): int {return ++$count; });
    }

    /**
     * @return Collector<mixed,int|float|Tuple2>
     */
    public static function averaging(): Collector
    {
        return new GenericCollector(new Tuple2(0, 0), /** @param T $value */ function (Tuple2 $acc, $value): Tuple2 {
            if (!is_numeric($value)) {
                throw new \InvalidArgumentException(sprintf('Could not convert %s to number', (string) $value));
            }

            return new Tuple2($acc[0] + $value, $acc[1] + 1);
        }, function (Tuple2 $acc): int|float {
            if ($acc[1] === 0) {
                return 0;
            }

            return $acc[0] / $acc[1];
        });
    }
}
