<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();

        return view('usuarios', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('Portugal2026*'), 
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado con éxito.');
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($id);
        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function updateAccesos(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'nullable|array'
        ]);

        $user = User::findOrFail($id);
        $user->role_id = $request->role_id;
        $user->save();

        if ($user->role) {
            $nuevosPermisos = $request->input('permissions', []);
            
            $role = Role::findOrFail($user->role_id);
            $role->permissions = json_encode($nuevosPermisos);
            $role->save();
        }

        return redirect()->route('usuarios.index')->with('success', 'Rol y permisos granulares actualizados correctamente.');
    }

    public function destroy($id)
    {
        if ((int)$id === (int)auth()->id()) {
            return redirect()->route('usuarios.index')->withErrors('No puedes eliminar tu propia cuenta en sesión.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario removido del sistema correctamente.');
    }

    public function tienePermiso(string $permission): bool
    {
        if ($this->role && $this->role->permissions) {
            $permisos = json_decode($this->role->permissions, true);
            return is_array($permisos) && in_array($permission, $permisos);
        }
        return false;
    }
}