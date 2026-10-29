<?php

namespace App\Http\Controllers;

use App\Application\UseCase\Notification\GetNotificationsUseCase;
use App\Application\UseCase\Notification\MarkNotificationsReadUseCase;
use App\Application\UseCase\Student\GetStudentUseCase;
use App\Domain\Notification\Notification;
use App\Domain\Student\Exception\StudentNotFoundException;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class NotificationController extends Controller
{
    public function __construct(
        private readonly GetStudentUseCase $getStudent,
        private readonly GetNotificationsUseCase $getNotifications,
        private readonly MarkNotificationsReadUseCase $markRead,
    ) {}

    public function index(int $student): Response
    {
        try {
            $s = $this->getStudent->execute($student);
        } catch (StudentNotFoundException) {
            abort(404);
        }

        return Inertia::render('Learn/Notifications', [
            'student' => ['id' => $s->id()->value(), 'fullName' => $s->name()->fullName()],
            'notifications' => $this->getNotifications->execute($student)->map(fn (Notification $n) => [
                'id' => $n->id()->value(),
                'type' => $n->type()->value,
                'title' => $n->title(),
                'description' => $n->description(),
                'courseId' => $n->courseId()?->value(),
                'isRead' => $n->isRead(),
                'createdAt' => $n->createdAt()->format('Y-m-d H:i'),
            ])->values()->all(),
        ]);
    }

    public function read(int $student, int $notification): RedirectResponse
    {
        $this->markRead->execute($student, $notification);

        return back();
    }

    public function readAll(int $student): RedirectResponse
    {
        $this->markRead->execute($student);

        return back();
    }
}