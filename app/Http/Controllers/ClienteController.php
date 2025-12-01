<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();
        
        // Búsqueda por razón social o CUIT
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('razonSocial', 'like', '%' . $search . '%')
                  ->orWhere('cuit', 'like', '%' . $search . '%')
                  ->orWhere('correo', 'like', '%' . $search . '%');
            });
        }
        
        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }
        
        $clientes = $query->orderBy('razonSocial')->paginate(15);
        
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'razonSocial' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/'
            ],
            'cuit' => [
                'required',
                'string',
                'max:11',
                'regex:/^[0-9]+$/',
                'unique:clientes,cuit'
            ],
            'direccion' => 'nullable|string|max:150',
            'telefono' => [
                'nullable',
                'string',
                'size:10',
                'regex:/^[0-9]+$/'
            ],
            'correo' => 'nullable|email:rfc,dns|max:100',
        ], [
            'razonSocial.required' => 'La razón social/nombre es obligatoria.',
            'razonSocial.regex' => 'La razón social/nombre solo debe contener letras y espacios. No se permiten números, guiones, puntos, comas ni símbolos.',
            'cuit.required' => 'El CUIT/DNI es obligatorio.',
            'cuit.max' => 'El CUIT/DNI debe tener máximo 11 dígitos.',
            'cuit.regex' => 'El CUIT/DNI solo debe contener números, sin puntos ni guiones.',
            'cuit.unique' => 'Este CUIT/DNI ya está registrado.',
            'telefono.size' => 'El teléfono debe tener exactamente 10 dígitos.',
            'telefono.regex' => 'El teléfono solo debe contener números, sin guiones ni espacios.',
            'correo.email' => 'El formato del correo electrónico no es válido.',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente');
    }

    public function show($id)
    {
        $cliente = Cliente::with(['cotizaciones', 'facturas'])->findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'razonSocial' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/'
            ],
            'cuit' => [
                'required',
                'string',
                'max:11',
                'regex:/^[0-9]+$/',
                'unique:clientes,cuit,' . $id . ',idCliente'
            ],
            'direccion' => 'nullable|string|max:150',
            'telefono' => [
                'nullable',
                'string',
                'size:10',
                'regex:/^[0-9]+$/'
            ],
            'correo' => 'nullable|email:rfc,dns|max:100',
        ], [
            'razonSocial.required' => 'La razón social/nombre es obligatoria.',
            'razonSocial.regex' => 'La razón social/nombre solo debe contener letras y espacios. No se permiten números, guiones, puntos, comas ni símbolos.',
            'cuit.required' => 'El CUIT/DNI es obligatorio.',
            'cuit.max' => 'El CUIT/DNI debe tener máximo 11 dígitos.',
            'cuit.regex' => 'El CUIT/DNI solo debe contener números, sin puntos ni guiones.',
            'cuit.unique' => 'Este CUIT/DNI ya está registrado.',
            'telefono.size' => 'El teléfono debe tener exactamente 10 dígitos.',
            'telefono.regex' => 'El teléfono solo debe contener números, sin guiones ni espacios.',
            'correo.email' => 'El formato del correo electrónico no es válido.',
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update(['estado' => 'INACTIVO']);

        return redirect()->route('clientes.index')->with('success', 'Cliente desactivado correctamente');
    }

    public function activate($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update(['estado' => 'ACTIVO']);

        return redirect()->route('clientes.index')->with('success', 'Cliente activado correctamente');
    }
}