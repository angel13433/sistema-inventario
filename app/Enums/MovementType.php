<?php

namespace App\Enums;

enum MovementType: string
{
    case Entry      = 'entry';
    case Exit       = 'exit';
    case Adjustment = 'adjustment';

    /**
     * Etiqueta en español para la interfaz de usuario.
     */
    public function label(): string
    {
        return match($this) {
            self::Entry      => 'Entrada',
            self::Exit       => 'Salida',
            self::Adjustment => 'Ajuste',
        };
    }

    /**
     * Color Tailwind CSS para badges en la UI.
     */
    public function color(): string
    {
        return match($this) {
            self::Entry      => 'green',
            self::Exit       => 'red',
            self::Adjustment => 'yellow',
        };
    }
}
