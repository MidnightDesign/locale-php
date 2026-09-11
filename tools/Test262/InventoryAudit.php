<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class InventoryAudit
{
    /**
     * @param array<string, mixed> $corpus
     * @param list<string>         $translatedAssertionIds
     *
     * @return array{complete: bool, reasons: list<string>}
     */
    public static function run(array $corpus, array $translatedAssertionIds): array
    {
        $reasons = [];
        $fixtures = $corpus['fixtures'] ?? null;
        $fixtureCount = $corpus['fixtureCount'] ?? null;
        $detectedAssertionCount = $corpus['detectedAssertionCount'] ?? null;
        if (!is_array($fixtures) || !is_int($fixtureCount) || count($fixtures) !== $fixtureCount) {
            return ['complete' => false, 'reasons' => ['The fixture inventory count is incomplete.']];
        }

        $paths = [];
        $assertionIds = [];
        $detected = 0;
        foreach ($fixtures as $fixture) {
            if (
                !is_array($fixture)
                || !is_string($fixture['path'] ?? null)
                || !is_string($fixture['status'] ?? null)
                || !is_string($fixture['reason'] ?? null)
                || !is_array($fixture['detectedAssertions'] ?? null)
            ) {
                $reasons[] = 'A fixture inventory entry is incomplete.';
                continue;
            }

            $path = $fixture['path'];
            if (isset($paths[$path])) {
                $reasons[] = sprintf('Fixture path "%s" is duplicated.', $path);
            }
            $paths[$path] = true;

            if ($fixture['detectedAssertions'] === []) {
                $reasons[] = sprintf('Fixture "%s" has no inventoried assertion identity.', $path);
            }
            foreach ($fixture['detectedAssertions'] as $assertion) {
                ++$detected;
                if (
                    !is_array($assertion)
                    || !is_string($assertion['id'] ?? null)
                    || !is_int($assertion['line'] ?? null)
                    || !is_int($assertion['column'] ?? null)
                    || !is_string($assertion['call'] ?? null)
                    || !is_string($assertion['sha256'] ?? null)
                    || !is_string($assertion['status'] ?? null)
                ) {
                    $reasons[] = sprintf('Fixture "%s" has an incomplete assertion identity.', $path);
                    continue;
                }

                $id = $assertion['id'];
                if (isset($assertionIds[$id])) {
                    $reasons[] = sprintf('Assertion identity "%s" is duplicated.', $id);
                }
                $assertionIds[$id] = true;
            }

            if ($fixture['status'] === 'translation_gap') {
                $scope = $fixture['unresolvedAssertionScope'] ?? null;
                if (
                    !is_array($scope)
                    || !is_string($scope['id'] ?? null)
                    || ($scope['status'] ?? null) !== 'translation_gap'
                    || !is_string($scope['reason'] ?? null)
                ) {
                    $reasons[] = sprintf('Fixture "%s" lacks its unresolved assertion scope.', $path);
                }
            }
        }

        if (!is_int($detectedAssertionCount) || $detected !== $detectedAssertionCount) {
            $reasons[] = 'The detected assertion total does not match the inventory entries.';
        }

        $translatedIdSet = array_fill_keys($translatedAssertionIds, true);
        foreach ($translatedIdSet as $id => $_) {
            if (!isset($assertionIds[$id])) {
                $reasons[] = sprintf('Translated assertion "%s" is absent from the source inventory.', $id);
            }
        }
        foreach ($fixtures as $fixture) {
            if (
                !is_array($fixture)
                || !in_array($fixture['status'] ?? null, ['translated', 'partially_translated'], true)
                || !is_array($fixture['detectedAssertions'] ?? null)
            ) {
                continue;
            }
            foreach ($fixture['detectedAssertions'] as $assertion) {
                if (
                    is_array($assertion)
                    && is_string($assertion['id'] ?? null)
                    && !isset($translatedIdSet[$assertion['id']])
                ) {
                    $reasons[] = sprintf('Source assertion "%s" is absent from translated evidence.', $assertion['id']);
                }
            }
        }

        return ['complete' => $reasons === [], 'reasons' => array_values(array_unique($reasons))];
    }
}
