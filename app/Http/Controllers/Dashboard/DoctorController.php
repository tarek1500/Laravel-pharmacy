<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Doctor;
use App\Pharmacy;
use Symfony\Component\Console\Input\Input;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors =  Doctor::all();

        return view('doctors',[
            "doctors" => $doctors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pharmacies = Pharmacy::all();
        return view('addnewdoctor',[
            "pharmacies" => $pharmacies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pharmacyName = $request->pharmacy_name;
        $pharmacy = Pharmacy::where ('name',$pharmacyName)->get('id');
        $pharmacyId = $pharmacy["0"]["id"];

        $this->validate($request , [
            'name' => 'required|min:3|unique:doctors',
            'email'=> 'required|unique:doctors',
            'password' => 'required | min:6',
            'national_id' => 'required | min:14 | max:14 | unique:doctors',
            'avatar_image' => 'required'
        ]);

        $doctorImage = $request->avatar_image;
        $ImageName = time().$doctorImage->getClientOriginalName();
        $doctorImage->move('/images/doctors' , $ImageName);

        Doctor::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password' => $request->password,
            'national_id' => $request->national_id,
            'avatar_image' => 'images/doctors/'.$ImageName,
            'pharmacy_id'=> $pharmacyId
        ]);

        return redirect('dashboard/doctors');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $doctor = Doctor::find($id);
        $pharmacies = Pharmacy::all();


        return view('editdoctor',[
            "pharmacies" => $pharmacies,
            "doctor" => $doctor
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $doctor = Doctor::find($id);
        dd($doctor->avatar_image);

        $pharmacyName = $request->pharmacy_name;
        $pharmacy = Pharmacy::where ('name',$pharmacyName)->get('id');
        $pharmacyId = $pharmacy["0"]["id"];

        $this->validate($request , [
            'name' => 'required|min:3',
            'email'=> 'required',
            'password' => 'required | min:6',
            'national_id' => 'required | min:14 | max:14',
            'avatar_image' => 'required'
            ]);

            $doctorImage = $doctor->avatar_image;



            $ImageName = time().$doctorImage->getClientOriginalName();
            $doctorImage->move('images/doctors' , $ImageName);


            $doctor->update([
                'name' => $request->name,
                'email'=> $request->email,
                'password' => $request->password,
                'national_id' => $request->national_id,
                'avatar_image' => 'images/doctors/'.$ImageName,
                'pharmacy_id'=> $pharmacyId,
                ]);

                return redirect('dashboard/doctors');

            }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doctor = Doctor::find($id);
        $doctor->delete();
        return redirect()->back();
    }
}

