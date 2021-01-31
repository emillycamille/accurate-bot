<?php

namespace Tests\Concerns;

use Illuminate\Support\Str;
use OwowAgency\Snapshots\MatchesSnapshots as BaseMatchesSnapshots;

trait MatchesSnapshots
{
    use BaseMatchesSnapshots;

    /*
     * Determine the directory where snapshots are stored.
     */
    protected function getSnapshotDirectory(): string
    {
        $path = Str::after(static::class, 'Tests\\');

        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);

        return base_path('tests/'.dirname($path).'/snapshots');
    }
}
