<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEUserGroup;
use App\Http\Requests\AdminLTE\API\BrandingPOSTRequest;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    
    public function get(Request $request)
    {
        $objectAdminLTE = new AdminLTE();
        $brand_data = $objectAdminLTE->getBrandData();
        return $brand_data;
    }
    
    public function post(BrandingPOSTRequest $request)
    {
        $objectAdminLTE = new AdminLTE();
        $brand_data = $objectAdminLTE->getBrandData();
        $temp = explode('storage/', $brand_data['logo']);
        $old_logo_path = $temp[1];

        $brand_name = $request->input('name');
        $logo = $request->input('logo');
        $file_name = $request->input('file_name');

        if ('logo_not_changed' != $file_name) {
            $temp = explode('.', $file_name);
            $file_name = $objectAdminLTE->convertNameToFileName($temp[0]) . '.' . $temp[1];

            $logo_path = 'adminltebrand/logo/' . $file_name;

            if ($old_logo_path != $logo_path) {
                
                $photoURI = $logo;
                
                $dataPosition = strpos($photoURI, 'base64,', 11);
                
                $photoURI = substr($photoURI, ($dataPosition + 7));
                $photoURI = str_replace(' ', '+', $photoURI);
                $photoData = base64_decode($photoURI);
                            
                Storage::disk('local')->put('public/' . $logo_path, $photoData);
                Storage::disk('local')->delete('public/' .$old_logo_path);
            } 
        } else {
            $logo_path = $old_logo_path;
        }
        
        $brand_json = '{"name": "' . $brand_name . '", "logo": "' . $logo_path . '"}';
        
        Storage::disk('local')->put('config/brand_json.php', $brand_json);
        
        return ['message' => "Success"];
    }
    
}
