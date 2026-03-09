<?php

namespace Termosalud\Web\User\Infrastructure\Persistence;

use App\Models\User as EloquentModel;
use Illuminate\Support\Facades\Hash;
use Termosalud\Web\User\Domain\User;
use Termosalud\Web\User\Domain\UserRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;

class EloquentUserRepository implements UserRepository
{
    public function save(User $user, ?string $password = null): User
    {
        $data = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        if ($password) {
            $data['password'] = Hash::make($password);
        }

        if ($user->id) {
            $model = EloquentModel::find($user->id);
            $model->update($data);
        } else {
            $model = EloquentModel::create($data);
        }
        
        $model->load('roles');

        $model->load('roles');

        return $this->toDomain($model);
    }

    public function findById(int $id): ?User
    {
        $model = EloquentModel::with('roles')->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $model = EloquentModel::where('email', $email)->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function delete(int $id): void
    {
        EloquentModel::destroy($id);
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria);
        $query = $this->matching($eloquentCriteria);
        $models = $query->with('roles')->get();

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

    public function syncRoles(int $userId, array $roles): void
    {
        $model = EloquentModel::find($userId);
        $rolesForGuard = collect($roles)->map(
            fn($role) => \Spatie\Permission\Models\Role::findByName($role, 'web')
        )->all();

        $model->syncRoles($rolesForGuard);
    }

    private function toDomain(EloquentModel $model): User
    {
        return new User(
            $model->id,
            $model->name,
            $model->email,
            $model->roles->pluck('name')->toArray()
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
        }, EloquentModel::query());
    }
}
