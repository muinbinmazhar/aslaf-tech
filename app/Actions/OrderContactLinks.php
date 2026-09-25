<?php

namespace App\Actions;

use App\Models\ContactLink;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderContactLinks
{
    public function reorderPage(array $ids, int $page): void
    {
        DB::transaction(function () use ($ids, $page): void {
            $all = $this->lockedIds();
            $offset = ($page - 1) * 15;
            $current = array_slice($all, $offset, 15);
            $requested = array_map('intval', $ids);

            if (count($current) !== count($requested)
                || array_diff($current, $requested)
                || array_diff($requested, $current)) {
                throw ValidationException::withMessages(['ids' => 'The list changed. Reload the page and try again.']);
            }

            array_splice($all, $offset, count($current), $requested);
            $this->persist($all);
        });
    }

    public function move(ContactLink $contact, string $direction): bool
    {
        return DB::transaction(function () use ($contact, $direction): bool {
            $all = $this->lockedIds();
            $index = array_search((int) $contact->id, $all, true);
            if ($index === false) return false;

            $next = $index + ($direction === 'up' ? -1 : 1);
            if (! isset($all[$next])) return false;

            [$all[$index], $all[$next]] = [$all[$next], $all[$index]];
            $this->persist($all);
            return true;
        });
    }

    private function lockedIds(): array
    {
        return ContactLink::query()->orderBy('sort_order')->orderBy('id')
            ->lockForUpdate()->pluck('id')->map(fn ($id) => (int) $id)->all();
    }

    private function persist(array $ids): void
    {
        foreach ($ids as $position => $id) {
            DB::table('contact_links')->where('id', $id)->update(['sort_order' => $position]);
        }
    }
}
