<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\jabatan;
use DB;
use File;
use Intervention\Image\ImageManagerStatic as Image;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{
  
    public function index(Request $request)
    {
        $users = User::with(['jabatan'])
        ->orderBy('nomor_urut', 'asc');
        if (request()->q != '') {
            $users = $users->where('name', request()->q);
        }
        $users = $users->paginate(1000);
        return new UserCollection($users);
    }

    
    public function store(Request $request)
    {
        $this->validate($request, [
          'name' => 'required',                    
        ]);     
        try {         
            $input = $request->all();  
            $foto = "";
            if($request->hasFile('foto')){             
                $foto =  $this->simpan_file( $request->file('foto') ); 
            }  
            $input['foto'] = $foto ? $foto : 'default.png';
            $input['password'] = bcrypt($request->password);
            $input['role_id'] = 2;
            $input['email_verified_at'] = now();
            $user = User::create($input);
            $user->syncRoles(['admin']);       
            return response()->json(['status' => 'success'], 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
        }  
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

    public function userLists(Request $request)
    {

        $user = User::with(['role'])->orderBy('created_at', 'DESC');

        if (request()->q != '') {
            $user = $user->where('name', 'LIKE', '%' . request()->q . '%')
                         ->orWhereHas('role', function($query) {
                        $query->where('name', 'LIKE', '%' . request()->q . '%');
            });
        }
    
        $user = $user->paginate(request()->per_page);
       

        return new UserCollection($user);
    }


    public function edit($id)
    {
        $user = User::with(['jabatan'])->find($id);
        return response()->json(['status' => 'success', 'data' => $user], 200);
    }

   
    public function update(Request $request, $id)
    {
      
          try {          
              $data = User::find($id);  
              $foto = $data->foto;      
                          
            if($request->hasFile('foto')){  
                !empty($foto) ? Storage::disk('public')->delete('/user/'. $data->foto) : null;
                $foto =  $this->simpan_file( $request->file('foto') ); 
            }              

            if($data) {
                $data->name = $request->name;
                $data->alamat = $request->alamat;
                $data->hp = $request->hp;                
                $data->jabatan_id = $request->jabatan_id;
                $data->status = $request->status;
                $data->nomor_urut = $request->nomor_urut;
                $data->foto = $foto;   
                $data->save();
            }
                          
              return response()->json(['status' => 'success'], 200);
            } catch (Exception $e) {
                return response()->json(['error' => 'Gagal menyimpan data. Cek koneksi internet atau server.'], 500);
            }   
    }


    public function destroy($id)
    {
        $user = User::find($id);
        File::delete(storage_path('app/public/user/' . $user->foto));
        $user->delete();
        return response()->json(['status' => 'success']);
    }

    public function getUserLogin(Request $request)
    {
        $user = request()->user();
        $jabatan = Auth::user()->jabatan_id;
        if($jabatan) {
            $user['jabatan'] = jabatan::find(Auth::user()->jabatan_id)->nama_jabatan;
        }else{
            $user['jabatan'] = 'Menunggu Konfirmasi';
        }
       
        
        $permissions = [];
        foreach (Permission::all() as $permission) {
            if (request()->user()->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }
        $user['permission'] = $permissions;
        return response()->json(['status' => 'success', 'data' => $user]);
    }

    public function ubahPassword(Request $request)
    {
        
        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|min:3|string'
        ]);
        $auth = Auth::user();
 
 // The passwords matches
        if (!Hash::check($request->get('current_password'), $auth->password)) 
        {
            return response()->json(['status' => 'errors']);
        }
 
// Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0) 
        {
            return response()->json(['status' => 'errors']);
        }
 
        $user =  User::find($auth->id);
        $user->password =  Hash::make($request->new_password);
        $user->save();
        return response()->json(['status' => 'success']);
        // return back()->with('success', "Password Changed Successfully");
    }
}
