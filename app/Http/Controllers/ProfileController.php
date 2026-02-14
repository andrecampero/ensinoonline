<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
   public function edit()
   {
      return view('profile.edit');
   }

   public function update(Request $request)
   {
      $user = auth()->user();

      $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|string|email|max:255|unique:usuarios,email,' . $user->id,
         'current_password' => 'nullable|required_with:password',
         'password' => [
            'nullable',
            'min:8',
            'confirmed',
            'regex:/[a-z]/',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*#?&]/',
         ],
      ], [
         'password.regex' => 'A senha deve conter letras maiúsculas, minúsculas, números e caracteres especiais.',
      ]);

      $data = [
         'name' => $request->name,
         'email' => $request->email,
      ];

      if ($request->filled('password')) {
         if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
               'current_password' => ['A senha atual está incorreta.'],
            ]);
         }
         $data['password'] = Hash::make($request->password);
      }

      $user->update($data);

      return back()->with('success', 'Dados atualizados com sucesso!');
   }
}
