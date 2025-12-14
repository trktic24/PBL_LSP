<?php

namespace App\Policies;

use App\Models\FrIa04a;
use App\Models\User;

class FrIa04aPolicy
{
    // 1. READ (Semua role boleh lihat, tapi Asesi cuma boleh lihat punya sendiri)
    public function view(User $user, FrIa04a $frIa04a)
    {
        if ($user->role->nama_role === 'admin' || $user->role->nama_role === 'asesor') {
            return true; // Admin & Asesor bebas lihat semua
        }
        // Asesi hanya boleh lihat miliknya sendiri
        return $user->id_user === $frIa04a->user_id;
    }

    // 2. CREATE (Hanya Asesi yang boleh buat form baru)
    public function create(User $user)
    {
        return $user->role->nama_role === 'asesi';
    }

    // 3. UPDATE (Asesi edit konten, Asesor edit ttd)
    public function update(User $user, FrIa04a $frIa04a)
    {
        // Asesi boleh edit jika itu miliknya
        if ($user->role->nama_role === 'asesi' && $user->id_user === $frIa04a->user_id) {
            return true;
        }
        // Asesor boleh edit (tapi nanti di controller kita batasi cuma kolom ttd)
        if ($user->role->nama_role === 'asesor') {
            return true;
        }
        return false;
    }

    // 4. DELETE (Hanya Asesi yang boleh hapus form miliknya)
    // Asesor "Delete Tanda Tangan" itu sebenarnya adalah Update (set null), bukan delete record.
    public function delete(User $user, FrIa04a $frIa04a)
    {
        return $user->role->nama_role === 'asesi' && $user->id_user === $frIa04a->user_id;
    }
}