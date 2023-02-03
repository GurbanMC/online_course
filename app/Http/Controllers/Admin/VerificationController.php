<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:255',
            'status' => 'nullable|integer|between:0,1',
        ]);
        $q = $request->q ?: null;
        $f_status = $request->has('status') ? $request->status : null;

        $objs = Verification::when($q, function ($query, $q) {
            return $query->where(function ($query) use ($q) {
                $query->orWhere('code', 'like', '%' . $q . '%');
                $query->orWhere('phone', 'like', '%' . $q . '%');
                $query->orWhere('created_at', 'like', '%' . $q . '%');
                $query->orWhere('updated_at', 'like', '%' . $q . '%');
            });
        })
            ->when(isset($f_status), function ($query) use ($f_status) {
                return $query->where('status', $f_status);
            })
            ->orderBy('id', 'desc')
            ->paginate(50)
            ->withQueryString();

        return view('admin.verification.index')
            ->with([
                'objs' => $objs,
            ]);
    }
}
