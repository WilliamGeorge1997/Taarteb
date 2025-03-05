<?php

namespace Modules\Session\Service;

use Modules\Session\App\Models\Session;

class SessionService
{
    function findAll($data = [])
    {
        $sessions = Session::query()
            ->available()->orderByDesc('created_at');
        return getCaseCollection($sessions, $data);
    }

    function findById($id)
    {
        $session = Session::available()->findOrFail($id);
        return $session;
    }

    function findBy($key, $value)
    {
        $session = Session::available()->where($key, $value)->get();
        return $session;
    }

    function create($data)
    {
        if (auth('user')->user()->hasRole('School Manager'))
            $data['school_id'] = auth('user')->user()->school_id;
        $session = Session::create($data);
        return $session;
    }

    function update($session, $data)
    {
        $session->update($data);
        return $session;
    }

    function delete($session)
    {
        $session->delete();
    }
}
