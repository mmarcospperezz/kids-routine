<?php

namespace App\Http\Controllers\Hijo;

use App\Http\Controllers\Controller;
use App\Models\Hijo;
use App\Models\Tarea;
use App\Models\TareaInstancia;
use App\Models\Recompensa;
use App\Models\Canje;
use App\Models\HistorialMonedas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function hijoActual(): Hijo
    {
        return Hijo::findOrFail(session('hijo_id'));
    }

    public function dashboard()
    {
        $hijo = $this->hijoActual();
        $this->generarInstanciasHoy($hijo);

        $instanciasHoy = TareaInstancia::with('tarea')
            ->where('id_hijo', $hijo->id_hijo)
            ->where('fecha_programada', today())
            ->whereIn('estado', ['PENDIENTE', 'COMPLETADA', 'RECHAZADA'])
            ->get();

        $tareasCompletadas = $instanciasHoy->where('estado', 'COMPLETADA')->count();
        $tareasTotal = $instanciasHoy->count();

        $canjesPendientes = Canje::with('recompensa')
            ->where('id_hijo', $hijo->id_hijo)
            ->whereIn('estado', ['PENDIENTE', 'APROBADO'])
            ->get();

        // Ranking semanal entre hermanos
        $semanaInicio = now()->startOfWeek();
        $hermanos = Hijo::where('id_padre', $hijo->id_padre)
            ->where('activo', true)
            ->get()
            ->map(function ($h) use ($semanaInicio) {
                $h->monedas_semana = DB::table('historial_monedas')
                    ->where('id_hijo', $h->id_hijo)
                    ->where('cantidad', '>', 0)
                    ->where('fecha', '>=', $semanaInicio)
                    ->sum('cantidad');
                return $h;
            })
            ->sortByDesc('monedas_semana')
            ->values();

        $ranking = $hermanos->count() > 1 ? $hermanos : collect();

        // Logros del hijo
        $logros = $hijo->logros()->orderByPivot('fecha_obtenido', 'desc')->get();

        return view('hijo.dashboard', compact(
            'hijo', 'instanciasHoy', 'canjesPendientes', 'tareasCompletadas', 'tareasTotal',
            'ranking', 'logros'
        ));
    }

    public function completarTarea(Request $request, TareaInstancia $instancia)
    {
        $hijo = $this->hijoActual();

        if ($instancia->id_hijo !== $hijo->id_hijo || $instancia->estado !== 'PENDIENTE') {
            abort(403);
        }

        $request->validate([
            'foto_prueba' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $datos = [
            'estado'           => 'COMPLETADA',
            'fecha_completada' => now(),
        ];

        if ($request->hasFile('foto_prueba')) {
            $datos['foto_prueba'] = $this->resizeFotoPrueba($request->file('foto_prueba')->getRealPath());
        }

        $instancia->update($datos);

        return back()->with('exito', '¡Genial! Has completado la tarea. Espera a que tu padre la valide.');
    }

    private function resizeFotoPrueba(string $path): string
    {
        $src = imagecreatefromstring(file_get_contents($path));
        if (function_exists('exif_read_data')) {
            $exif = @exif_read_data($path);
            $src = match ($exif['Orientation'] ?? 1) {
                3 => imagerotate($src, 180, 0),
                6 => imagerotate($src, -90, 0),
                8 => imagerotate($src, 90, 0),
                default => $src,
            };
        }
        $w = imagesx($src); $h = imagesy($src);
        $ratio = $w / $h;
        $nw = $ratio > 1 ? min($w, 600) : (int) (min($h, 600) * $ratio);
        $nh = $ratio > 1 ? (int) ($nw / $ratio) : min($h, 600);
        $dst = imagecreatetruecolor($nw, $nh);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
        ob_start(); imagejpeg($dst, null, 80); $jpeg = ob_get_clean();
        imagedestroy($src); imagedestroy($dst);
        return 'data:image/jpeg;base64,' . base64_encode($jpeg);
    }

    public function recompensas()
    {
        $hijo = $this->hijoActual();
        $recompensas = Recompensa::where('id_padre', $hijo->id_padre)
            ->where('activa', true)
            ->get();

        $canjesPendientes = Canje::where('id_hijo', $hijo->id_hijo)
            ->whereIn('estado', ['PENDIENTE', 'APROBADO'])
            ->with('recompensa')
            ->get();

        return view('hijo.recompensas', compact('hijo', 'recompensas', 'canjesPendientes'));
    }

    public function canjear(Recompensa $recompensa)
    {
        $hijo = $this->hijoActual();

        if ($recompensa->id_padre !== $hijo->id_padre || !$recompensa->activa) {
            abort(403);
        }

        if ($hijo->monedas < $recompensa->monedas_necesarias) {
            return back()->withErrors(['monedas' => 'No tienes suficientes monedas para esta recompensa.']);
        }

        // Recompensas no recurrentes: solo un canje activo a la vez
        if (!$recompensa->recurrente) {
            $yaActivo = Canje::where('id_hijo', $hijo->id_hijo)
                ->where('id_recompensa', $recompensa->id_recompensa)
                ->whereIn('estado', ['PENDIENTE', 'APROBADO'])
                ->exists();
            if ($yaActivo) {
                return back()->withErrors(['monedas' => 'Ya tienes un canje pendiente de esta recompensa.']);
            }
        }

        DB::transaction(function () use ($hijo, $recompensa) {
            $saldoAnterior = $hijo->monedas;
            $saldoPosterior = $saldoAnterior - $recompensa->monedas_necesarias;

            $hijo->update(['monedas' => $saldoPosterior]);

            $canje = Canje::create([
                'id_hijo'          => $hijo->id_hijo,
                'id_recompensa'    => $recompensa->id_recompensa,
                'monedas_gastadas' => $recompensa->monedas_necesarias,
                'fecha_caducidad'  => now()->addDays(30)->toDateString(),
            ]);

            HistorialMonedas::create([
                'id_hijo'        => $hijo->id_hijo,
                'cantidad'       => -$recompensa->monedas_necesarias,
                'saldo_anterior' => $saldoAnterior,
                'saldo_posterior'=> $saldoPosterior,
                'motivo'         => 'CANJE',
                'id_referencia'  => $canje->id_canje,
            ]);
        });

        // Logro primer canje
        try {
            app(\App\Services\AchievementService::class)->onPrimerCanje($hijo);
        } catch (\Throwable) {}

        return back()->with('exito', '¡Canje solicitado! Tu padre tiene que aprobarlo.');
    }

    private function generarInstanciasHoy(Hijo $hijo): void
    {
        $hoy = today();
        $diaSemana = (string) $hoy->dayOfWeekIso;

        $tareas = Tarea::where('id_hijo', $hijo->id_hijo)
            ->where('estado', 'ACTIVA')
            ->where('tipo', 'RECURRENTE')
            ->where(function ($q) use ($hoy) {
                $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', $hoy);
            })
            ->get();

        foreach ($tareas as $tarea) {
            $debeGenerarse = match ($tarea->recurrencia) {
                'DIARIA' => true,
                'SEMANAL' => $diaSemana === '1',
                'PERSONALIZADA' => in_array($diaSemana, $tarea->diasSemanaArray()),
                default => false,
            };

            if ($debeGenerarse) {
                TareaInstancia::firstOrCreate(
                    [
                        'id_tarea' => $tarea->id_tarea,
                        'id_hijo' => $hijo->id_hijo,
                        'fecha_programada' => $hoy,
                    ],
                    ['estado' => 'PENDIENTE']
                );
            }
        }
    }
}
