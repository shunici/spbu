<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
class RolePermissionController extends Controller

{

    public function getAllRole()
    {
        $roles = Role::all();
        return response()->json(['status' => 'success', 'data' => $roles]);
    }

    public function getAllPermission()
    {
        $permission = Permission::all();
        return response()->json(['status' => 'success', 'data' => $permission]);
    }

    public function getRolePermission(Request $request)
    {   
        $hasPermission = DB::table('role_has_permissions')
            ->select('permissions.name')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_id', $request->role_id)->get();
        return response()->json(['status' => 'success', 'data' => $hasPermission]);
    }

  
    public function setRolePermission(Request $request)
    {
        $this->validate($request, [
            'role_id' => 'required|exists:roles,id'
        ]);

        $role = Role::find($request->role_id);
        $role->revokePermissionTo('lihat'); 
        $role->revokePermissionTo('edit');  
        $role->revokePermissionTo('hapus'); 
        $role->revokePermissionTo('lihat edit hapus');        
        $total_array = count($request->permissions);        
        if($total_array > 0) {
            $role->syncPermissions($request->permissions);
        }
        return response()->json(['status' => 'success']);
    }

    public function setRoleUser(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'role' => 'required'
        ]);

        $user = User::find($request->user_id);
        if($user){
            $user->role_id=  $request->role['id_role'];
            $user->save();
        }        
        $user->syncRoles([$request->role]);
        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $data = User::findOrFail($id);
        $foto = $data->foto;

        if($request->hasFile('foto')){  
            !empty($foto) ? Storage::disk('public')->delete('/user/'. $data->foto) : null;
             $foto =  $this->simpan_file( $request->file('foto') ); 
        }              
        if($data) {
            $data->alamat = $request->alamat;
            $data->hp = $request->hp;
            $data->name = $request->name;
            $data->foto = $foto;
            $data->save();
        }

        return response()->json(['status' => $foto]);
    }
    

    private function simpan_file($foto)
    {
     $ori_name = $foto->getClientOriginalName();
     $images = $ori_name. time() . '.' . $foto->getClientOriginalExtension();
     $path = public_path('storage/user');
         if(!File::isDirectory($path)) {
             File::makeDirectory($path, 0777, true, true);
         }
         $width = 600; // your max width
         $height = 450; // your max height

         $img = Image::make($foto); //kurangi resolusi
         $img->height() > $img->width() ? $width=null : $height=null;
         $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
         $img->save($path. '/' .$images);                     
     return $images;
    }
}
