<?php

namespace Termosalud\Web\Form\Infrastructure\Persistence;

use App\Models\Form as EloquentForm;
use App\Models\FormSubmission as EloquentSubmission;
use Termosalud\Web\Form\Domain\Form;
use Termosalud\Web\Form\Domain\FormRepository;
use Termosalud\Web\Form\Domain\FormSubmission;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;

class EloquentFormRepository implements FormRepository
{
    public function save(Form $form): Form
    {
        $data = [
            'name' => $form->name,
            'key' => $form->key,
            'recipient_email' => $form->recipientEmail,
            'fields' => $form->fields,
            'active' => $form->isActive,
        ];

        if ($form->id) {
            $model = EloquentForm::find($form->id);
            $model->update($data);
        } else {
            $model = EloquentForm::create($data);
        }

        return $this->toDomain($model);
    }

    public function findById(int $id): ?Form
    {
        $model = EloquentForm::find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findByKey(string $key): ?Form
    {
        $model = EloquentForm::where('key', $key)->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function remove(int $id): void
    {
        EloquentForm::destroy($id);
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria);
        // We ensure we load withCount submissions to map to domain properly if possible, 
        // though Criteria usually overrides query. We will append withCount manually here.
        $query = $this->matching($eloquentCriteria)->withCount('submissions');
        $models = $query->get();

        return collect($models)->map(fn($m) => $this->toDomain($m))->toArray();
    }

    public function countByCriteria(Criteria $criteria): int
    {
        // Create criteria without pagination for counting
        $countCriteria = new Criteria(
            $criteria->filters(),
            $criteria->order(),
            null,
            null
        );
        
        $eloquentCriteria = EloquentCriteriaConverter::convert($countCriteria);
        $query = $this->matching($eloquentCriteria);

        return $query->count();
    }

    public function saveSubmission(FormSubmission $submission): FormSubmission
    {
        $data = [
            'form_id' => $submission->formId,
            'data' => $submission->data,
            'ip_address' => $submission->ipAddress,
            'user_agent' => $submission->userAgent,
            'status' => $submission->status,
        ];

        $model = EloquentSubmission::create($data);

        return new FormSubmission(
            $model->id,
            $model->form_id,
            $model->data,
            $model->ip_address,
            $model->user_agent,
            $model->status
        );
    }

    public function getSubmissions(int $formId, int $perPage = 15): array
    {
        $paginator = EloquentSubmission::where('form_id', $formId)
            ->latest()
            ->paginate($perPage);

        return [
            'data' => collect($paginator->items())->map(function ($m) {
                return [
                    'id' => $m->id,
                    'data' => $m->data,
                    'created_at' => $m->created_at->format('d/m/Y H:i'),
                    'ip_address' => $m->ip_address,
                    'status' => $m->status,
                ];
            })->toArray(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'total' => $paginator->total(),
        ];
    }

    public function searchSubmissionsByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria);
        $query = $this->matchingSubmission($eloquentCriteria);
        $models = $query->get();

        return collect($models)->map(fn($m) => $this->toSubmissionDomain($m))->toArray();
    }

    public function countSubmissionsByCriteria(Criteria $criteria): int
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria);
        $query = $this->matchingSubmission($eloquentCriteria);

        return $query->count();
    }

    private function toSubmissionDomain(EloquentSubmission $model): FormSubmission
    {
        return new FormSubmission(
            $model->id,
            $model->form_id,
            $model->data,
            $model->ip_address,
            $model->user_agent,
            $model->status,
            $model->created_at ? $model->created_at->format('Y-m-d H:i:s') : null
        );
    }

    private function toDomain(EloquentForm $model): Form
    {
        return new Form(
            $model->id,
            $model->name,
            $model->key,
            $model->recipient_email,
            $model->fields ?? [],
            (bool) $model->active,
            $model->submissions_count ?? 0
        );
    }

    private function matching($criteria)
    {
        $criteria = is_array($criteria) === false ? [$criteria] : $criteria;

        return array_reduce($criteria, static function ($query, $criteria) {
            $criteria->each(static function ($method) use ($query) {
                call_user_func_array([$query, $method->name], $method->parameters);
            });

            return $query;
        }, EloquentForm::query());
    }

    private function matchingSubmission($criteria)
    {
        $criteria = is_array($criteria) === false ? [$criteria] : $criteria;

        return array_reduce($criteria, static function ($query, $criteria) {
            $criteria->each(static function ($method) use ($query) {
                call_user_func_array([$query, $method->name], $method->parameters);
            });

            return $query;
        }, EloquentSubmission::query());
    }

}