<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{

    public function index()
    {
        return response()->json(Contact::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:10',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'required|string',
            'pdf' => 'nullable|mimes:pdf|max:10240',
            'image' => 'nullable|image|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $contact = new Contact;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;


        if ($request->hasFile('pdf')) {
            $contact->pdf = $request->file('pdf')->store('pdfs');
        }

        if ($request->hasFile('image')) {
            $contact->image = $request->file('image')->store('images');
        }

        $contact->save();

        return response()->json(['message' => 'Contact added successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:10',
            'email' => 'sometimes|email|unique:contacts,email,' . $contact->id,
            'phone' => 'sometimes|string',
            'pdf' => 'nullable|mimes:pdf|max:10240',
            'image' => 'nullable|image|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $contact->name = $request->name ?? $contact->name;
        $contact->email = $request->email ?? $contact->email;
        $contact->phone = $request->phone ?? $contact->phone;

        if ($request->hasFile('pdf')) {
            $contact->pdf = $request->file('pdf')->store('pdfs');
        }

        if ($request->hasFile('image')) {
            $contact->image = $request->file('image')->store('images');
        }

        $contact->save();

        return response()->json(['message' => 'Contact updated successfully'], 200);
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }

        $contact->delete();

        return response()->json(['message' => 'Contact deleted successfully'], 200);
    }
}
