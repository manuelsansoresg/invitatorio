<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function show($slug)
    {
        $invitation = Invitation::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('invitation.show', compact('invitation'));
    }

    public function rsvp(Request $request, $slug)
    {
        $invitation = Invitation::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Validar y procesar la confirmación (por ahora solo simulación)
        // Aquí podrías guardar en una tabla 'rsvps' o enviar un correo

        return back()->with('success', '¡Gracias por confirmar tu asistencia!');
    }
}
