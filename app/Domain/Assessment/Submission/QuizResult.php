<?php

namespace App\Domain\Assessment\Submission;

final class QuizResult
{
    private function __construct(
        public readonly SubmissionStatus $status,
        public readonly ?int $score,
    ) {}

    public static function of(array $answers): self
    {
        foreach($answers as $answer){
            if(!$answer->isGraded()){
                return new self(SubmissionStatus::PENDING, null);
            }
        }

        return new self(
            SubmissionStatus::ACCEPTED,
            array_sum(array_map(fn (StudentAnswer $answer) => $answer->score(), $answers)),
        );
    }
}