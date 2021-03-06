<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $useSoftDeletes = true;

    protected $allowedFields = ['username', 'fullname', 'slug', 'email', 'password', 'birth_date', 'avatar', 'role', 'email_verified_at', 'created_at', 'updated_at', 'deleted_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function allUserAdmin($keyword, $filter = null)
    {
        $builder = $this->table('users');
        $builder->select('id, username, slug, email, avatar, role, email_verified_at, created_at, deleted_at');

        // Jika ada pencarian
        if ($keyword) {
            $builder->groupStart()->like('id', $keyword)->orLike('username', $keyword)->orLike('fullname', $keyword)->orLike('email', $keyword)->groupEnd();
        }

        // Jika ada filter
        if ($filter) {
            if ($filter == 'Admin') {
                $builder->groupStart()->where('role', 1)->orWhere('role', 2)->groupEnd()->withDeleted();
            } else if ($filter == 'User') {
                $builder->where('role', 3)->withDeleted();
            } else if ($filter == 'Diaktifkan') {
                $builder->where('email_verified_at !=', null)->withDeleted();
            } else if ($filter == 'Belum Diaktifkan') {
                $builder->where('email_verified_at', null)->withDeleted();
            } else if ($filter == 'Dihapus') {
                $builder->onlyDeleted();
            } else {
                $builder->withDeleted();
            }
        }

        return $builder;
    }
}
