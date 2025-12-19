<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;


class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            // Kirim data user yang sedang login ke view
            'user' => $request->user(),
        ]);
    }

    /**
     * Mengupdate informasi profil user.
     */
   public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();

    // 1. Handle Upload Avatar
    if ($request->hasFile('avatar')) {
        // Upload file dan dapatkan path relatif
        $avatarPath = $this->uploadAvatar($request, $user);

        // Simpan path ke user
        $user->avatar = $avatarPath;
    }

    // 2. Update data lain (name, phone, address, dll)
    $user->fill($request->validated());

    // 3. Jika email berubah, reset status verifikasi
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    // 4. Save ke database
    $user->save(); // <<< pastikan save ini ada di sini

    return Redirect::route('profile.edit')
        ->with('success', 'Profil berhasil diperbarui!');
}


    /**
     * Helper khusus untuk menangani logika upload avatar.
     * Mengembalikan string path file yang tersimpan.
     */
    protected function uploadAvatar(ProfileUpdateRequest $request, $user): string
    {
        // Hapus avatar lama (Garbage Collection)
        // Cek 1: Apakah user punya avatar sebelumnya?
        // Cek 2: Apakah file fisiknya benar-benar ada di storage 'public'?
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Generate nama file unik untuk mencegah bentrok nama.
        // Format: avatar-{user_id}-{timestamp}.{ext}
        $filename = 'avatar-' . $user->id . '-' . time() . '.' . $request->file('avatar')->extension();

        // Simpan file ke folder: storage/app/public/avatars
        // return path relatif: "avatars/namafile.jpg"
        $path = $request->file('avatar')->storeAs('avatars', $filename, 'public');

        return $path;
    }

    /**
     * Menghapus avatar (tombol "Hapus Foto").
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Hapus file fisik
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);

            // Set kolom di database jadi NULL
            $user->update(['avatar' => null]);
        }

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }


    /**
     * Update password user.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Menghapus akun user permanen.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validasi password untuk keamanan sebelum hapus akun
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Logout dulu
        Auth::logout();

        // Hapus avatar fisik user sebelum hapus data user
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Hapus data user dari DB
        $user->delete();

        // Invalidate session agar tidak bisa dipakai lagi (Security)
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}