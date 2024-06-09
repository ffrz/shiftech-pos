<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserActivityController extends Controller
{
    public function __construct()
    {
        ensure_user_can_access(AclResource::USER_ACTIVITY);
    }

    public function index(Request $request)
    {
        $filter_active = false;

        $filter = [
            'search' => $request->get('search', ''),
            'user_id' => $request->get('user_id', ''),
            'type' => $request->get('type', ''),
        ];

        if ($request->get('action') == 'reset') {
            $filter['type'] = '';
            $filter['user_id'] = '';
        }

        $q = UserActivity::query();

        if (!empty($filter['search'])) {
            $q->where('description', 'like', '%' . $filter['search'] . '%');
            $q->orWhere('name', 'like', '%' . $filter['search'] . '%');
        }

        if (!empty($filter['user_id'])) {
            $q->where('user_id', '=', $filter['user_id']);
            $filter_active = true;
        }

        if (!empty($filter['type'])) {
            $q->where('type', '=', $filter['type']);
            $filter_active = true;
        }

        $q->orderBy('id', 'desc');
        $items = $q->paginate(10);
        $users = User::orderBy('username', 'asc')->get();
        $types = UserActivity::types();

        return view('admin.user-activity.index', compact('items', 'filter', 'users', 'types', 'filter_active'));
    }

    public function show(Request $request, $id = 0)
    {
        $item = UserActivity::findOrFail($id);
        return view('admin.user-activity.show', compact('item'));
    }

    public function delete(Request $request)
    {
        $item = UserActivity::findOrFail($request->post('id', 0));
        $item->delete();
        return redirect('admin/user-activity')->with('info', 'Rekaman log aktivitas <b>#' . $item->id . '</b> telah dihapus.');
    }

    public function clear(Request $request)
    {
        $data = [
            'type' => intval($request->get('type')),
            'user_id' => intval($request->get('user_id')),
        ];

        if ($request->method() == 'POST') {
            $sql = 'DELETE FROM user_activities';
            $where = [];
            
            if (!empty($data['type'])) {
                $where[] = 'type=' . $data['type'];
            }
            
            if (!empty($data['user_id'])) {
                $where[] = 'user_id=' . $data['user_id'];
            }

            if (empty($where)) {
                $sql .= ' WHERE id > 0';
            }
            else {
                $sql .= ' WHERE ' . join(' AND ', $where);
            }

            DB::delete($sql);
            
            return redirect('admin/user-activity')->with('warning', 'Riwayat akititas pengguna telah dihapus.');
        }

        $users = User::orderBy('username', 'asc')->get();
        $types = UserActivity::types();
        return view('admin.user-activity.clear', compact('data', 'users', 'types'));
    }
}
