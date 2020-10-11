<?php

namespace App\Traits;

use App\Scopes\HasCompanyScope;
use App\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    public function getTagsStringAttribute()
    {
        return $this->tags->pluck('name')->implode(', ');
    }

    public function getTagsBadgesAttribute() : string
    {
        return $this->tags->implode('badge', ' ');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|\ArrayAccess|\Spatie\Tags\Tag $tags
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyTags(Builder $query, $tags, string $type = null): Builder
    {
        if (! $tags)
        {
            return $query;
        }

        $tagNames = $tags;
        $tags = [];
        foreach (explode(',', $tagNames) as $key => $name) {
            if ($tag = Tag::findFromString($name, $type))
            {
                $tags[] = $tag;
            }
        }

        return $query->whereHas('tags', function (Builder $query) use ($tags) {
            $tagIds = collect($tags)->pluck('id');

            $query->whereIn('tags.id', $tagIds);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array|\ArrayAccess|\Spatie\Tags\Tag $tags
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllTags(Builder $query, $tags, string $type = null): Builder
    {
        if (! $tags)
        {
            return $query;
        }

        $tagNames = is_array($tags) ? $tags : explode(',', $tags);
        $tags = [];
        foreach ($tagNames as $key => $name) {
            if ($tag = Tag::findFromString($name, $type))
            {
                $tags[] = $tag;
            }
        }

        collect($tags)->each(function ($tag) use ($query) {
            $query->whereIn("{$this->getTable()}.{$this->getKeyName()}", function ($query) use ($tag) {
                $query->from('taggables')
                    ->select('taggables.taggable_id')
                    ->where('taggables.tag_id', $tag ? $tag->id : 0);
            });
        });

        return $query;
    }
}