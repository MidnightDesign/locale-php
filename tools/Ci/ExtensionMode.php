<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

final class ExtensionMode
{
    /**
     * @param list<string> $eligibleNativePaths
     * @param list<string> $exercisedNativePaths
     * @return array{mode: string, intlLoaded: bool, eligibleNativePaths: list<string>, exercisedNativePaths: list<string>, fallbackReason: string|null}
     */
    public static function trace(
        string $mode,
        bool $intlLoaded,
        array $eligibleNativePaths,
        array $exercisedNativePaths,
    ): array {
        if (!in_array($mode, ['absent', 'disabled', 'native'], true)) {
            throw new \InvalidArgumentException(sprintf('Unknown extension mode: %s.', $mode));
        }
        if ($mode === 'absent' && $intlLoaded) {
            throw new \RuntimeException('The absent extension mode requires ext-intl to be absent.');
        }
        if ($mode !== 'absent' && !$intlLoaded) {
            throw new \RuntimeException(sprintf('The %s extension mode requires ext-intl to be loaded.', $mode));
        }
        if ($mode !== 'native' && $exercisedNativePaths !== []) {
            throw new \RuntimeException(sprintf('The %s extension mode must not exercise native paths.', $mode));
        }

        $missingPaths = array_values(array_diff($eligibleNativePaths, $exercisedNativePaths));
        if ($mode === 'native' && $missingPaths !== []) {
            throw new \RuntimeException(sprintf('Eligible native paths were not exercised: %s.', implode(
                ', ',
                $missingPaths,
            )));
        }

        $fallbackReason = match ($mode) {
            'absent' => 'ext-intl-absent',
            'disabled' => 'native-acceleration-disabled',
            'native' => $eligibleNativePaths === [] ? 'no-native-path-implemented' : null,
        };

        return [
            'mode' => $mode,
            'intlLoaded' => $intlLoaded,
            'eligibleNativePaths' => $eligibleNativePaths,
            'exercisedNativePaths' => $exercisedNativePaths,
            'fallbackReason' => $fallbackReason,
        ];
    }
}
