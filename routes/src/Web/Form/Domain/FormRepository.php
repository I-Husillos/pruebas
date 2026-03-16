<?php

namespace Termosalud\Web\Form\Domain;

interface FormRepository
{
    public function save(Form $form): Form;

    public function findById(int $id): ?Form;

    public function findByKey(string $key): ?Form;

    public function remove(int $id): void;

    /** @return Form[] */
    public function searchByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): array;

    public function countByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): int;

    // Submission methods
    public function saveSubmission(FormSubmission $submission): FormSubmission;

    public function getSubmissions(int $formId, int $perPage = 15): array;

    /** @return FormSubmission[] */
    public function searchSubmissionsByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): array;

    public function countSubmissionsByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): int;
}
