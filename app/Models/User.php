<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cep',
        'numero',
        'logradouro',
        'bairro',
        'cidade',
        'estado',
        'complemento',
        'telefone',
        'data_nascimento',
        'cpf',
        'saldo',
        'foto',
        'is_admin',
        'criado_por',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'data_nascimento' => 'date',
            'saldo' => 'decimal:2',
            'is_admin' => 'boolean',
        ];
    }

    public function getFotoUrlAttribute(): ?string
    {
        return url_foto($this->foto);
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    public function vendas()
    {
        return $this->hasMany(CompraItem::class, 'vendedor_id');
    }

    public function criador()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    public function podeSerGerenciadoPor(User $admin): bool
    {
        return $this->is_admin && ($this->id === $admin->id || $this->criado_por === $admin->id);
    }

    public function carrinho()
    {
        return $this->hasMany(CarrinhoItem::class);
    }
}
