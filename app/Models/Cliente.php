<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'idCliente';
    public $timestamps = false;

    protected $fillable = [
        'razonSocial',
        'cuit',
        'direccion',
        'telefono',
        'correo',
        'fechaAlta',
        'estado'
    ];

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'idCliente', 'idCliente');
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'idCliente', 'idCliente');
    }

    /**
     * Obtener el cliente genérico para cotizaciones anónimas
     */
    public static function getClienteGenerico()
    {
        return self::where('cuit', '99999999999')->first();
    }

    /**
     * Crear cliente genérico si no existe
     */
    public static function crearClienteGenerico()
    {
        return self::create([
            'razonSocial' => 'CLIENTE OCASIONAL',
            'cuit' => '99999999999',
            'direccion' => 'No especificada',
            'telefono' => 'No especificado',
            'correo' => 'ocasional@ejemplo.com',
            'fechaAlta' => now(),
            'estado' => 'ACTIVO'
        ]);
    }
}