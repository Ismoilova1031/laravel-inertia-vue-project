<?php

namespace App\Domain\Lesson;

use App\Domain\Lesson\Exception\LessonNotFoundException;

final class LessonSequence
{
    public static function move(array $lessons, LessonId $id, int $position): array
    {
        $ordered = array_values($lessons);

        $index = null;
        foreach ($ordered as $i => $lesson) {
            if ($lesson->id()?->value() === $id->value()) {
                $index = $i;
                break;
            }
        }

        if ($index === null) {
            throw LessonNotFoundException::withId($id);
        }

        $moving = array_splice($ordered, $index, 1)[0];
        $position = max(1, min($position, count($ordered) + 1));
        array_splice($ordered, $position - 1, 0, [$moving]);

        return self::renumber($ordered);
    }

    public static function arrange(array $lessons, array $orderedIds): array
    {
        $byId = [];
        foreach ($lessons as $lesson) {
            $byId[$lesson->id()->value()] = $lesson;
        }

        $ordered = [];
        foreach ($orderedIds as $id) {
            if (! isset($byId[$id])) {
                throw LessonNotFoundException::withId(new LessonId($id));
            }
            $ordered[] = $byId[$id];
            unset($byId[$id]);
        }

        foreach ($byId as $rest) {
            $ordered[] = $rest;
        }

        return self::renumber($ordered);
    }

    public static function renumber(array $ordered): array
    {
        $changed = [];

        foreach (array_values($ordered) as $i => $lesson) {
            $position = new SortOrder($i + 1);

            if (! $lesson->sortOrder()->equals($position)) {
                $lesson->moveTo($position);
                $changed[] = $lesson;
            }
        }

        return $changed;
    }
}
