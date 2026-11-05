<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Application\UseCase\Assessment\GradeAnswerUseCase;
use App\Application\UseCase\Assessment\ReviewSubmissionUseCase;
use App\Application\UseCase\Assessment\SubmitTaskCommand;
use App\Application\UseCase\Assessment\SubmitTaskUseCase;
use App\Application\UseCase\Assessment\TaskData;
use App\Application\UseCase\Certificate\IssueCertificateUseCase;
use App\Application\UseCase\Course\ChangeCourseStatusCommand;
use App\Application\UseCase\Course\ChangeCourseStatusUseCase;
use App\Application\UseCase\Course\CreateCourseCommand;
use App\Application\UseCase\Course\CreateCourseUseCase;
use App\Application\UseCase\CourseInterest\ToggleCourseInterestUseCase;
use App\Application\UseCase\Enrollment\DeactivateEnrollmentCommand;
use App\Application\UseCase\Enrollment\DeactivateEnrollmentUseCase;
use App\Application\UseCase\Enrollment\EnrollStudentCommand;
use App\Application\UseCase\Enrollment\EnrollStudentUseCase;
use App\Application\UseCase\Lesson\CreateLessonCommand;
use App\Application\UseCase\Lesson\CreateLessonUseCase;
use App\Application\UseCase\Progress\MarkLessonCompletedUseCase;
use App\Application\UseCase\Review\SubmitReviewUseCase;
use App\Application\UseCase\Student\RegisterStudentCommand;
use App\Application\UseCase\Student\RegisterStudentUseCase;
use App\Domain\Assessment\TaskType;
use App\Domain\Course\CourseCategory;
use App\Domain\Course\CourseStatus;
use App\Domain\CourseInterest\InterestType;
use App\Domain\Lesson\LessonType;
use App\Domain\Shared\UploadedFileData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

final class DemoSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('app:make-admin', [
            'username' => 'teacher',
            'password' => 'password',
            '--name' => 'Soliha Ismoilova',
        ]);

        $laravel = $this->course('Laravel Fundamentals', 'Build real web apps with Laravel: routing, validation and testing.', CourseCategory::Programming);
        $laravelLessons = [
            'intro' => $this->lesson($laravel, 'Welcome and setup', LessonType::TEXT, '<p>What you will build and how to set up your environment.</p>'),
            'video' => $this->lesson($laravel, 'Request lifecycle (video)', LessonType::VIDEO),
            'routing' => $this->lesson($laravel, 'Routing and controllers', LessonType::TEXT, '<p>Routes map URLs to controllers.</p>'),
            'quiz' => $this->lesson($laravel, 'Quiz: Laravel basics', LessonType::TASK, task: $this->laravelQuiz()),
            'file' => $this->lesson($laravel, 'Assignment: your first controller', LessonType::TASK, task: new TaskData(TaskType::FILE->value, fileExtensions: ['pdf', 'txt'])),
            'talk' => $this->lesson($laravel, 'Discussion: Eloquent or query builder?', LessonType::TASK, task: new TaskData(TaskType::DISCUSSION->value)),
        ];
        $this->publish($laravel);

        $design = $this->course('UI Design Basics', 'Layout, color and typography for clean interfaces.', CourseCategory::Design);
        $designLessons = [
            $this->lesson($design, 'Layout and spacing', LessonType::TEXT, '<p>Use a spacing scale.</p>'),
            $this->lesson($design, 'Color and contrast', LessonType::TEXT, '<p>Contrast keeps text readable.</p>'),
            $this->lesson($design, 'Quiz: design principles', LessonType::TASK, task: $this->designQuiz()),
            $this->lesson($design, 'Discussion: your favorite interface', LessonType::TASK, task: new TaskData(TaskType::DISCUSSION->value)),
        ];
        $this->publish($design);

        $japanese = $this->course('Japanese for Beginners', 'Hiragana, greetings and first sentences.', CourseCategory::Language);
        $japaneseLessons = [
            $this->lesson($japanese, 'Hiragana: あ行', LessonType::TEXT, '<p>あ い う え お</p>'),
            $this->lesson($japanese, 'Greetings', LessonType::TEXT, '<p>おはよう, こんにちは, こんばんは</p>'),
        ];
        $this->publish($japanese);

        $draft = $this->course('Data Science with Python', 'Work in progress.', CourseCategory::Programming);
        $this->lesson($draft, 'Outline', LessonType::TEXT, '<p>Coming soon.</p>');

        $archived = $this->course('Legacy PHP Practices', 'Kept for reference.', CourseCategory::Programming);
        $this->lesson($archived, 'Old patterns', LessonType::TEXT, '<p>Historic content.</p>');
        $this->publish($archived);
        $this->setStatus($archived, CourseStatus::ARCHIVED);

        $ali = $this->student('Ali', 'Valiyev', 'ali');
        $sara = $this->student('Sara', 'Karimova', 'sara');
        $vali = $this->student('Vali', 'Rahimov', 'vali');
        $mina = $this->student('Mina', 'Tanaka', 'mina');

        foreach ([$ali, $sara] as $student) {
            $this->enroll($laravel, $student);
        }
        $this->enroll($design, $vali);
        $this->enroll($japanese, $mina);

        foreach (['intro', 'video', 'routing'] as $key) {
            $this->complete($laravelLessons[$key], $ali);
        }
        $quizTask = $this->taskId($laravelLessons['quiz']);
        $aliQuiz = $this->submit(new SubmitTaskCommand($quizTask, $ali, answers: $this->quizAnswers($quizTask, true, 'Middleware wraps the request before it reaches the controller.')));
        $openQuestion = (int) DB::table('questions')->where('task_id', $quizTask)->where('question_type', 4)->value('id');
        app(GradeAnswerUseCase::class)->execute($aliQuiz, $openQuestion, 8, 'Good explanation, mention the response path too.');

        $fileTask = $this->taskId($laravelLessons['file']);
        $aliFile = $this->submit(new SubmitTaskCommand($fileTask, $ali, file: $this->pdf()));
        app(ReviewSubmissionUseCase::class)->execute($aliFile, true, 'Clear and well structured.');

        $this->complete($laravelLessons['intro'], $sara);
        $this->submit(new SubmitTaskCommand($quizTask, $sara, answers: $this->quizAnswers($quizTask, false, 'Not sure, maybe it validates input?')));
        $this->submit(new SubmitTaskCommand($fileTask, $sara, file: $this->pdf()));

        $this->complete($designLessons[0], $vali);
        $this->complete($designLessons[1], $vali);
        $designQuiz = $this->taskId($designLessons[2]);
        $this->submit(new SubmitTaskCommand($designQuiz, $vali, answers: $this->quizAnswers($designQuiz, true)));
        $this->submit(new SubmitTaskCommand($this->taskId($designLessons[3]), $vali, text: 'Linear: calm, fast, consistent.'));
        app(IssueCertificateUseCase::class)->execute($vali, $design);
        app(SubmitReviewUseCase::class)->execute($vali, $design, 5, 'Short, practical and clear.');

        $this->complete($japaneseLessons[0], $mina);
        app(DeactivateEnrollmentUseCase::class)->execute(new DeactivateEnrollmentCommand($japanese, $mina));

        $toggle = app(ToggleCourseInterestUseCase::class);
        $toggle->execute($ali, $design, InterestType::LIKE, true);
        $toggle->execute($ali, $japanese, InterestType::PLANNED, true);
        $toggle->execute($sara, $laravel, InterestType::LIKE, true);
        $toggle->execute($vali, $laravel, InterestType::PLANNED, true);
    }

    private function course(string $title, string $description, CourseCategory $category): int
    {
        return app(CreateCourseUseCase::class)
            ->execute(new CreateCourseCommand($title, $description, $category->value))
            ->value();
    }

    private function lesson(int $courseId, string $title, LessonType $type, ?string $content = null, ?TaskData $task = null): int
    {
        return app(CreateLessonUseCase::class)->execute(new CreateLessonCommand(
            courseId: $courseId,
            title: $title,
            description: "{$title}: overview",
            type: $type->value,
            content: $content,
            task: $task,
        ))->value();
    }

    private function publish(int $courseId): void
    {
        $this->setStatus($courseId, CourseStatus::PUBLISHED);
    }

    private function setStatus(int $courseId, CourseStatus $status): void
    {
        app(ChangeCourseStatusUseCase::class)->execute(new ChangeCourseStatusCommand($courseId, $status->value));
    }

    private function student(string $name, string $surname, string $username): int
    {
        return app(RegisterStudentUseCase::class)->execute(new RegisterStudentCommand(
            name: $name,
            surname: $surname,
            email: "{$username}@example.com",
            username: $username,
            password: 'password',
        ))->value();
    }

    private function enroll(int $courseId, int $studentId): void
    {
        app(EnrollStudentUseCase::class)->execute(new EnrollStudentCommand($courseId, $studentId));
    }

    private function complete(int $lessonId, int $studentId): void
    {
        app(MarkLessonCompletedUseCase::class)->execute($lessonId, $studentId);
    }

    private function submit(SubmitTaskCommand $command): int
    {
        return app(SubmitTaskUseCase::class)->execute($command)->value();
    }

    private function taskId(int $lessonId): int
    {
        return (int) DB::table('tasks')->where('lesson_id', $lessonId)->value('id');
    }

    private function pdf(): UploadedFileData
    {
        $path = tempnam(sys_get_temp_dir(), 'demo');
        file_put_contents($path, "%PDF-1.4\n% demo submission\n");

        return new UploadedFileData($path, 'pdf');
    }

    /** @return array<int, array{options: list<int>, text: ?string}> */
    private function quizAnswers(int $taskId, bool $correct, ?string $openText = null): array
    {
        $answers = [];

        foreach (DB::table('questions')->where('task_id', $taskId)->orderBy('sort_order')->get() as $q) {
            $options = DB::table('question_options')
                ->where('question_id', $q->id)
                ->where('is_correct', $correct)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $answers[(int) $q->id] = match ((int) $q->question_type) {
                1 => ['options' => array_slice($options, 0, 1), 'text' => null],
                2 => ['options' => $options, 'text' => null],
                3 => ['options' => [], 'text' => $correct ? $q->correct_answer : 'wrong'],
                default => ['options' => [], 'text' => $openText],
            };
        }

        return $answers;
    }

    private function laravelQuiz(): TaskData
    {
        return new TaskData(TaskType::QUIZ->value, questions: [
            [
                'question' => 'Which file defines web routes?', 'question_type' => 1, 'points' => 2, 'correct_answer' => null,
                'options' => [
                    ['option' => 'routes/web.php', 'is_correct' => true],
                    ['option' => 'config/app.php', 'is_correct' => false],
                    ['option' => 'bootstrap/cache.php', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Which are Eloquent relations?', 'question_type' => 2, 'points' => 3, 'correct_answer' => null,
                'options' => [
                    ['option' => 'hasMany', 'is_correct' => true],
                    ['option' => 'belongsTo', 'is_correct' => true],
                    ['option' => 'jumpsTo', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Command that runs the tests?', 'question_type' => 3, 'points' => 2, 'correct_answer' => 'php artisan test', 'options' => [],
            ],
            [
                'question' => 'Explain what middleware does.', 'question_type' => 4, 'points' => 10, 'correct_answer' => null, 'options' => [],
            ],
        ]);
    }

    private function designQuiz(): TaskData
    {
        return new TaskData(TaskType::QUIZ->value, questions: [
            [
                'question' => 'Minimum text contrast ratio (WCAG AA)?', 'question_type' => 1, 'points' => 5, 'correct_answer' => null,
                'options' => [
                    ['option' => '4.5:1', 'is_correct' => true],
                    ['option' => '1.5:1', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Name the spacing system based on multiples of 8.', 'question_type' => 3, 'points' => 5, 'correct_answer' => '8pt grid', 'options' => [],
            ],
        ]);
    }
}
