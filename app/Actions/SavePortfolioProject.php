<?php

namespace App\Actions;

use App\Models\PortfolioProject;
use App\Support\WebsiteMedia;
use Throwable;

class SavePortfolioProject
{
    public function __construct(private WebsiteMedia $media) {}

    public function handle(array $data, ?PortfolioProject $project = null): PortfolioProject
    {
        $file = $data['thumbnail'] ?? null;
        $remove = (bool) ($data['remove_thumbnail'] ?? false);
        unset($data['thumbnail'], $data['remove_thumbnail']);
        $oldPath = $project?->thumbnail_path;
        $newPath = null;

        if ($file) {
            $newPath = $data['thumbnail_path'] = $this->media->store($file, 'portfolio-thumbnails', 'thumbnail');
        } elseif ($remove) {
            $data['thumbnail_path'] = null;
        }

        try {
            if ($project) {
                $project->update($data);
            } else {
                $project = PortfolioProject::create($data);
            }
        } catch (Throwable $exception) {
            if ($newPath) $this->media->deleteIfUnreferenced($newPath);
            throw $exception;
        }

        if ($newPath || $remove) {
            $this->media->deleteIfUnreferenced($oldPath);
        }

        return $project;
    }

    public function delete(PortfolioProject $project): void
    {
        $path = $project->thumbnail_path;
        $project->delete();
        $this->media->deleteIfUnreferenced($path);
    }
}
