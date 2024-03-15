<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item_name'=>'required',
            'item_description'=>'required',
            'item_image'=>'file|max:10048|required'
        ]);

        if ($request->file('item_image')) {
            $imageFile = $request->file('item_image');
            $imageName = uniqid() . '_' . $imageFile->getClientOriginalName();
            $imagePath = $imageFile->storeAs('menu-images', $imageName, 'public');
            $validatedData['item_image'] = $imagePath;
        }

        $storedData = Menu::create($validatedData);
        return response()->json([
            'status'=>200,
            'message'=>'Data berhasil ditambahkan',
            'data'=>$storedData
        ]);
    }

    public function getMenus()
    {
        $menus = Menu::latest()->get();
        return response()->json([
            'status'=>200,
            'message'=>"Menu Kami",
            'Menus'=>$menus
        ]);
    }

    public function detail(Menu $menu){
        return response()->json([
            'status'=>200,
            'data'=>$menu
        ]);
    }

    public function update(Menu $menu, Request $request)
    {
        $validatedData = $request->validate([
            'item_name' => 'required',
            'item_description' => 'required',
            'item_image' => 'file|max:10048|nullable'
        ]);
    
       
        if ($request->hasFile('item_image')) {
            $imageFile = $request->file('item_image');
            $imageName = uniqid() . '_' . $imageFile->getClientOriginalName();
            $imagePath = $imageFile->storeAs('menu-images', $imageName, 'public');
            $validatedData['item_image'] = $imagePath;
    
          
            Storage::delete($menu->item_image);
        } else {
            
            $validatedData['item_image'] = $menu->item_image;
        }
    
         $menu->update($validatedData);
      
        
        return response()->json([
            'status' => 200,
            'message' => 'Data diperbarui',
            'data' => $menu
        ]);
    }

    public function destroy(Menu $menu){
        Storage::delete($menu->item_image);
        $menu->delete();
        return response()->json([
            'status'=>200,
            'message'=>"Data berhasil dihapus"
        ]);
    }
    
}
