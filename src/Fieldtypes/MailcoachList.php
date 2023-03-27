<?php

namespace Spatie\StatamicMailcoach\Fieldtypes;

use Illuminate\Support\Collection;
use Spatie\MailcoachSdk\Exceptions\ResourceNotFound;
use Spatie\MailcoachSdk\Facades\Mailcoach;
use Spatie\MailcoachSdk\Resources\EmailList;
use Statamic\Fieldtypes\Relationship;

class MailcoachList extends Relationship
{
    public function getIndexItems($request): Collection
    {
        $emailLists = Mailcoach::emailLists();

        $all = collect();
        do {
            foreach($emailLists as $emailList) {
                $all->push($emailList);
            }
        } while($emailLists = $emailLists->next());

        return $all->map(fn (EmailList $emailList) => ['id' => $emailList->uuid, 'title' => $emailList->name]);
    }

    protected function toItemArray($id): array
    {
        if (! $id) {
            return [];
        }

        try {
            if (! $list = Mailcoach::emailList($id)) {
                return [];
            }
        } catch (ResourceNotFound) {
            return [];
        }

        return [
            'id' => $list->uuid,
            'title' => $list->name,
        ];
    }
}
