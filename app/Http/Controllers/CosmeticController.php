<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class CosmeticController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $customAvatarUploadPrice = 1_000_000; // 1 miljoen gems per nieuwe upload
        // Geen automatische set van theme_color meer; null betekent 'standaard' (zoals avatar null)
        // Zorg wel dat standaard kleur in purchased_theme_colors staat zodat hij als owned wordt gezien.
        $purchasedThemeColorsInit = $user->purchased_theme_colors ?? [];
        if (!in_array('#f3f4f6', $purchasedThemeColorsInit)) {
            $purchasedThemeColorsInit[] = '#f3f4f6';
            $user->purchased_theme_colors = $purchasedThemeColorsInit;
            $user->save();
        }
        // Statische avatars + dynamisch geuploade avatars van de gebruiker
        $avatars = [
            '__default', // pseudo optie voor nog geen gekozen avatar
            'avatar1.png',
            'avatar2.png',
            'avatar3.png',
            'avatar4.png',
            'avatar5.png',
            'avatar6.png',
            'avatar7.png',
            'avatar8.png',
            'avatar9.png',
            'avatar10.png',
        ];

        $customAvatars = [];
        $customGlob = glob(public_path('images/avatars/user_' . $user->id . '_*')) ?: [];
        foreach ($customGlob as $path) {
            $basename = basename($path);
            // kleine sanity check op extensie
            if (preg_match('/\.(png|jpe?g|webp)$/i', $basename)) {
                $customAvatars[] = $basename;
            }
        }
        if ($customAvatars) {
            $avatars = array_merge($avatars, $customAvatars);
        }
        $themeColors = [
            '#f3f4f6',
            '#2563eb',
            '#22d3ee',
            '#fbbf24',
            '#ef4444',
            '#e801fdff',
            '#10b981',
            '#8b5cf6', // violet-500
            '#f97316', // orange-500
            '#26ff00ff'
        ];
        // Nieuwe prijsstructuur: bedoeld dat 1 quiz (~1000 gems) een lage tier NIET meteen volledig betaalt.
        // Richtlijn: lage avatar ~5k (5 quizzes), hoogste ~50k (grote investering). Theme colors goedkoper maar nog steeds betekenisvol.
        $avatarPrices = [
            '__default'   => 0,
            'avatar1.png' => 10000,   // instap
            'avatar2.png' => 10000,
            'avatar3.png' => 10000,
            'avatar4.png' => 10000,
            'avatar5.png' => 15000,
            'avatar6.png' => 15000,
            'avatar7.png' => 25000,
            'avatar8.png' => 50000,
            'avatar9.png' => 50000,  // premium / long-term
            'avatar10.png' => 75000
        ];
        // Custom avatars zijn altijd gratis zodra geüpload (owner-only)
        foreach ($customAvatars as $c) {
            $avatarPrices[$c] = 0;
        }
        $themeColorPrices = [
            '#f3f4f6' => 0,
            '#2563eb' => 10000,
            '#22d3ee' => 10000,
            '#fbbf24' => 10000,
            '#ef4444' => 10000,
            '#e801fdff' => 10000,
            '#10b981' => 10000,
            '#8b5cf6' => 10000,
            '#f97316' => 10000,
            '#26ff00ff' => 10000
        ];
        // Geen default unlock van avatar1
        $purchasedAvatars = $user->purchased_avatars ?? [];
        $purchasedThemeColors = $user->purchased_theme_colors ?? ['#f3f4f6'];
        $effectiveThemeColor = $user->theme_color ?? '#f3f4f6';
        return view('cosmetics', compact(
            'user',
            'avatars',
            'themeColors',
            'avatarPrices',
            'themeColorPrices',
            'purchasedAvatars',
            'purchasedThemeColors',
            'effectiveThemeColor',
            'customAvatarUploadPrice'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'avatar' => 'nullable|string',
            'theme_color' => 'nullable|string',
        ]);
        $user = Auth::user();
        $avatar = $request->avatar;
        $color = $request->theme_color;

        // pricing (must match show) – zie toelichting boven in show()
        $avatarPrices = [
            '__default'   => 0,
            'avatar1.png' => 10000,   // instap
            'avatar2.png' => 10000,
            'avatar3.png' => 10000,
            'avatar4.png' => 10000,
            'avatar5.png' => 15000,
            'avatar6.png' => 15000,
            'avatar7.png' => 25000,
            'avatar8.png' => 50000,
            'avatar9.png' => 50000,  // premium / long-term
            'avatar10.png' => 75000
        ];
        // Voeg dynamisch geuploade avatars van deze user toe (gratis)
        $customGlob = glob(public_path('images/avatars/user_' . $user->id . '_*')) ?: [];
        foreach ($customGlob as $path) {
            $basename = basename($path);
            if (preg_match('/\.(png|jpe?g|webp)$/i', $basename)) {
                $avatarPrices[$basename] = 0;
            }
        }
        $themeColorPrices = [
            '#f3f4f6' => 0,
            '#2563eb' => 10000,
            '#22d3ee' => 10000,
            '#fbbf24' => 10000,
            '#ef4444' => 10000,
            '#e801fdff' => 10000,
            '#10b981' => 10000,
            '#8b5cf6' => 10000,
            '#f97316' => 10000,
            '#26ff00ff' => 10000
        ];

        $purchasedAvatars = $user->purchased_avatars ?? [];
        $purchasedThemeColors = $user->purchased_theme_colors ?? [];
        $changed = false;

        $wantsJson = $request->ajax() || $request->wantsJson() || str_contains($request->header('Accept',''), 'application/json');

        if ($avatar) {
            if (!array_key_exists($avatar, $avatarPrices)) {
                if ($wantsJson) {
                    return response()->json(['status' => 'error', 'message' => 'Ongeldig avatar.'], 422);
                }
                return redirect()->back()->with('error', 'Ongeldig avatar.')->withFragment('cosmetics-section');
            }
            if ($avatar === '__default') {
                // Terug naar standaard: sla null op (geen specifieke avatar)
                if ($user->avatar !== null) {
                    $user->avatar = null;
                    $changed = true;
                }
            } else {
                if (!in_array($avatar, $purchasedAvatars)) {
                    $price = $avatarPrices[$avatar];
                    if ($user->gems < $price) {
                        if ($wantsJson) {
                            return response()->json(['status' => 'error', 'message' => 'Niet genoeg gems voor deze avatar.'], 422);
                        }
                        return redirect()->back()->with('error', 'Niet genoeg gems voor deze avatar.')->withFragment('cosmetics-section');
                    }
                    // purchase
                    if ($price > 0) {
                        $user->gems -= $price;
                    }
                    $purchasedAvatars[] = $avatar;
                    $changed = true;
                }
                if ($user->avatar !== $avatar) {
                    $user->avatar = $avatar; // equip
                    $changed = true;
                }
            }
        }

        if ($color) {
            if (!array_key_exists($color, $themeColorPrices)) {
                if ($wantsJson) {
                    return response()->json(['status' => 'error', 'message' => 'Ongeldige kleur.'], 422);
                }
                return redirect()->back()->with('error', 'Ongeldige kleur.')->withFragment('cosmetics-section');
            }
            if ($color === '#f3f4f6') {
                // Terug naar standaard: sla null op
                if ($user->theme_color !== null) {
                    $user->theme_color = null;
                    $changed = true;
                }
            } else {
                if (!in_array($color, $purchasedThemeColors)) {
                    $price = $themeColorPrices[$color];
                    if ($user->gems < $price) {
                        if ($wantsJson) {
                            return response()->json(['status' => 'error', 'message' => 'Niet genoeg gems voor dit thema.'], 422);
                        }
                        return redirect()->back()->with('error', 'Niet genoeg gems voor dit thema.')->withFragment('cosmetics-section');
                    }
                    if ($price > 0) {
                        $user->gems -= $price;
                    }
                    $purchasedThemeColors[] = $color;
                    $changed = true;
                }
                if ($user->theme_color !== $color) {
                    $user->theme_color = $color; // equip
                    $changed = true;
                }
            }
        }

        if ($changed) {
            $user->purchased_avatars = array_values(array_unique($purchasedAvatars));
            $user->purchased_theme_colors = array_values(array_unique($purchasedThemeColors));
            $user->save();
        }

        if ($wantsJson) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Je wijzigingen zijn opgeslagen.',
                'gems' => $user->gems,
                'avatar' => $user->avatar,
                'theme_color' => $user->theme_color,
                'purchased_avatars' => $user->purchased_avatars ?? [],
                'purchased_theme_colors' => $user->purchased_theme_colors ?? []
            ]);
        }
        return redirect()->back()->with('success', 'Je wijzigingen zijn opgeslagen.')->withFragment('cosmetics-section');
    }

    /**
     * Upload een custom 1:1 avatar voor de gebruiker.
     */
    public function uploadCustomAvatar(Request $request)
    {
        $user = Auth::user();
        $price = 1_000_000; // 1 miljoen gems per upload
        $request->validate([
            'custom_avatar' => 'required|image|mimes:png,jpg,jpeg,webp|max:5120', // sta tot 5MB toe, we comprimeren zelf
        ], [
            'custom_avatar.image' => 'Het bestand moet een afbeelding zijn.',
            'custom_avatar.mimes' => 'Alleen PNG, JPG of WEBP toegestaan.',
            'custom_avatar.max' => 'Afbeelding mag maximaal 5MB zijn.'
        ]);

        // Eerst checken of genoeg gems voordat we intensief verwerken
        if ($user->gems < $price) {
            return $this->uploadErrorResponse($request, 'Niet genoeg gems (1.000.000 nodig) voor een nieuwe avatar upload.');
        }

        $file = $request->file('custom_avatar');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['png','jpg','jpeg','webp'])) {
            return $this->uploadErrorResponse($request, 'Ongeldig bestandsformaat.');
        }

        // Lees bytes & inspecteer
        $imageData = @file_get_contents($file->getRealPath());
        if (!$imageData) {
            return $this->uploadErrorResponse($request, 'Kon afbeelding niet lezen.');
        }
        $info = @getimagesizefromstring($imageData);
        if (!$info) {
            return $this->uploadErrorResponse($request, 'Ongeldige afbeelding.');
        }
        [$w, $h] = $info;
        if ($w <= 0 || $h <= 0 || $w !== $h) {
            return $this->uploadErrorResponse($request, 'De afbeelding moet exact vierkant (1:1) zijn.');
        }

        // Max dimension (client kan groot uploaden, we schalen naar max 256)
        $targetSize = 256;
        $scale = $w > $targetSize ? ($targetSize / $w) : 1;
        $newW = (int)floor($w * $scale);
        $newH = (int)floor($h * $scale);

        $src = @imagecreatefromstring($imageData);
        if (!$src) {
            return $this->uploadErrorResponse($request, 'Kon afbeelding niet verwerken.');
        }
        $dst = imagecreatetruecolor($newW, $newH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0,0,0,0, $newW,$newH, $w,$h);
        imagedestroy($src);

        $destDir = public_path('images/avatars');
        if (!File::exists($destDir)) {
            File::makeDirectory($destDir, 0775, true);
        }

        // Verwijder GEEN eerdere custom avatars meer: we staan meerdere uploads toe

        $baseFilename = 'user_' . $user->id . '_' . time() . '_' . bin2hex(random_bytes(2));
        $webpSupported = function_exists('imagewebp');
        $finalExt = $webpSupported ? 'webp' : 'png';
        $filename = $baseFilename . '.' . $finalExt;
        $fullPath = $destDir . '/' . $filename;
        if ($webpSupported) {
            imagewebp($dst, $fullPath, 80);
        } else {
            // fallback PNG
            imagepng($dst, $fullPath, 6);
        }
        imagedestroy($dst);

        $purchased = $user->purchased_avatars ?? [];
        if (!in_array($filename, $purchased)) {
            $purchased[] = $filename;
        }
        // Filter purchased_avatars alleen op daadwerkelijk bestaande bestanden (statische + alle custom glob)
    $static = ['avatar1.png','avatar2.png','avatar3.png','avatar4.png','avatar5.png','avatar6.png','avatar7.png','avatar8.png','avatar9.png','avatar10.png'];
        $customExisting = array_map('basename', glob($destDir . '/user_' . $user->id . '_*') ?: []);
        $valid = array_merge($static, $customExisting);
        $purchased = array_values(array_unique(array_intersect($purchased, $valid)));

        $user->purchased_avatars = $purchased;
        $user->avatar = $filename; // equip meteen
        // Trek kosten af
        $user->gems -= $price;
        if ($user->gems < 0) { $user->gems = 0; }
        $user->save();

        return $this->uploadSuccessResponse($request, $user);
    }

    private function uploadErrorResponse(Request $request, string $message)
    {
        $wantsJson = $request->ajax() || $request->wantsJson() || str_contains($request->header('Accept',''), 'application/json');
        if ($wantsJson) {
            return response()->json(['status' => 'error', 'message' => $message], 422);
        }
        return redirect()->back()->with('error', $message)->withFragment('cosmetics-section');
    }

    private function uploadSuccessResponse(Request $request, $user)
    {
        $wantsJson = $request->ajax() || $request->wantsJson() || str_contains($request->header('Accept',''), 'application/json');
        if ($wantsJson) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Avatar geüpload.',
                'avatar' => $user->avatar,
                'gems' => $user->gems,
                'purchased_avatars' => $user->purchased_avatars ?? [],
                'purchased_theme_colors' => $user->purchased_theme_colors ?? []
            ]);
        }
        return redirect()->route('cosmetics.show')->with('success', 'Avatar geüpload.')->withFragment('cosmetics-section');
    }
}
