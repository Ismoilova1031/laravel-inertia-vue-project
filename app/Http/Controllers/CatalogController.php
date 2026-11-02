<?php

namespace App\Http\Controllers;

use App\Application\UseCase\CourseInterest\GetCatalogUseCase;
use App\Application\UseCase\Student\GetStudentUseCase;
use App\Domain\Student\Exception\StudentNotFoundException;
use App\Http\Resources\CatalogItemResource;
use Inertia\Inertia;
use Inertia\Response;

final class CatalogController extends Controller
{
    public function __construct(
        private readonly GetCatalogUseCase $getCatalog,
        private readonly GetStudentUseCase $getStudent
    ) {}

    public function index(int $studentId): Response
    {
        try{
            $student = $this->getStudent->execute($studentId);
        } catch (StudentNotFoundException) {
            abort(404);
        }

        return Inertia::render('Learn/Catalog', [
            'student' => ['id' => $student->id()->value(), 'fullName' => $student->name()->fullName()],
            'courses' => CatalogItemResource::collection($this->getCatalog->execute($studentId))->resolve(),
        ]);
    }
}